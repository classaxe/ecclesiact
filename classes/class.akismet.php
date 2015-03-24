<?php
define ("VERSION_AKISMET","0.41.f");
/*
Version History:
  0.41.f (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
    2) Split out SocketWriteRead class into its own file

*/
class Akismet {
/**
 * @package		akismet
 * @author		Alex Potsides, {@link http://www.achingbrain.net http://www.achingbrain.net}
 * @version		0.4
 * @copyright	Alex Potsides, {@link http://www.achingbrain.net http://www.achingbrain.net}
 * @license		http://www.opensource.org/licenses/bsd-license.php BSD License
 */
  private $version = '0.4';
  private $wordPressAPIKey;
  private $blogURL;
  private $comment;
  private $apiPort;
  private $akismetServer;
  private $akismetVersion;

  // This prevents some potentially sensitive information from being sent accross the wire.
  private $ignore =
    array(
      'HTTP_COOKIE',
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_FORWARDED_HOST',
      'HTTP_MAX_FORWARDS',
      'HTTP_X_FORWARDED_SERVER',
      'REDIRECT_STATUS',
      'SERVER_PORT',
      'PATH',
      'DOCUMENT_ROOT',
      'SERVER_ADMIN',
      'QUERY_STRING',
      'PHP_SELF'
    );

  /**
  *	@param	string	$blogURL			The URL of your blog.
  *	@param	string	$wordPressAPIKey	WordPress API key.
  */
  public function __construct($blogURL='', $wordPressAPIKey='') {
    $this->blogURL = $blogURL;
    $this->wordPressAPIKey = $wordPressAPIKey;

    // Set some default values
    $this->apiPort = 80;
    $this->akismetServer = 'rest.akismet.com';
    $this->akismetVersion = '1.1';

    // Start to populate the comment data
    $this->comment['blog'] = $blogURL;
    $this->comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

    if(isset($_SERVER['HTTP_REFERER'])) {
    	$this->comment['referrer'] = $_SERVER['HTTP_REFERER'];
    }
    $this->comment['user_ip'] = $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR') ? $_SERVER['REMOTE_ADDR'] : getenv('HTTP_X_FORWARDED_FOR');
  }

  /**
  * Makes a request to the Akismet service to see if the API key passed to the constructor is valid.
  * Use this method if you suspect your API key is invalid.
  * @return bool	True is if the key is valid, false if not.
  */
  public function isKeyValid() {
    // Check to see if the key is valid
    if ($this->wordPressAPIKey==''){
      return 'No key';
    }
    $response = $this->sendRequest('key=' . $this->wordPressAPIKey . '&blog=' . $this->blogURL, $this->akismetServer, '/' . $this->akismetVersion . '/verify-key');
    if (!is_array($response) || count($response)<2) {
      return 'Unknown';
    }
    switch ($response[1]) {
      case 'valid':     return 'Pass';      break;
      case 'invalid':   return 'Fail';      break;
      default:          return 'Unknown';   break;
    }
  }

  // makes a request to the Akismet service
  private function sendRequest($request, $host, $path) {
    $http_request  = "POST " . $path . " HTTP/1.0\r\n";
    $http_request .= "Host: " . $host . "\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
    $http_request .= "Content-Length: " . strlen($request) . "\r\n";
    $http_request .= "User-Agent: Akismet PHP5 Class " . $this->version . " | Akismet/1.11\r\n";
    $http_request .= "\r\n";
    $http_request .= $request;

    $socketWriteRead = new SocketWriteRead($host, $this->apiPort, $http_request);
    $socketWriteRead->send();

    return explode("\r\n\r\n", $socketWriteRead->getResponse(), 2);
  }

  // Formats the data for transmission
  private function getQueryString() {
    foreach($_SERVER as $key => $value) {
      if(!in_array($key, $this->ignore)) {
        if($key == 'REMOTE_ADDR') {
          $this->comment[$key] = $this->comment['user_ip'];
        }
        else {
          $this->comment[$key] = $value;
        }
      }
    }

    $query_string = '';

    foreach($this->comment as $key => $data) {
    	if(!is_array($data)) {
    	  $query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';
    	}
    }

    return $query_string;
  }

	/**
	 *	Tests for spam.
	 *
	 *	Uses the web service provided by {@link http://www.akismet.com Akismet} to see whether or not the submitted comment is spam.  Returns a boolean value.
	 *
	 *	@return		bool	True if the comment is spam, false if not
	 *  @throws		Will throw an exception if the API key passed to the constructor is invalid.
	 */
	public function isCommentSpam() {
		$response = $this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.rest.akismet.com', '/' . $this->akismetVersion . '/comment-check');
		if(@isset($response[1]) && $response[1] == 'invalid' && !$this->isKeyValid()) {
			throw new exception('The Wordpress API key passed to the Akismet constructor is invalid.  Please obtain a valid one from http://wordpress.com/api-keys/');
		}
		
		return (@isset($response[1]) ? $response[1] == 'true' : false);
	}

	/**
	 *	Submit spam that is incorrectly tagged as ham.
	 *	Using this function will make you a good citizen as it helps Akismet to learn from its mistakes.  This will improve the service for everybody.
	 */
	public function submitSpam() {
		$response = $this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-spam');
        if (isset($response[1])){
          return $response[1];
        }
        return false;
	}
	/**
	 *	Submit ham that is incorrectly tagged as spam.
	 *	Using this function will make you a good citizen as it helps Akismet to learn from its mistakes.  This will improve the service for everybody.
	 */
	public function submitHam() {
		$response = $this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-ham');
        if (isset($response[1])){
          return $response[1];
        }
        return false;
	}
	
	/**
	 *	To override the user IP address when submitting spam/ham later on
	 *	@param string $userip	An IP address.  Optional.
	 */
	public function setUserIP($userip) {
		$this->comment['user_ip'] = $userip;
	}
	
	/**
	 *	To override the referring page when submitting spam/ham later on
	 *	@param string $referrer	The referring page.  Optional.
	 */
	public function setReferrer($referrer) {
		$this->comment['referrer'] = $referrer;
	}
	
	/**
	 *	A permanent URL referencing the blog post the comment was submitted to.
	 *	@param string $permalink	The URL.  Optional.
	 */
	public function setPermalink($permalink) {
		$this->comment['permalink'] = $permalink;
	}
	
	/**
	 *	The type of comment being submitted.
	 *	May be blank, comment, trackback, pingback, or a made up value like "registration" or "wiki".
	 */
	public function setCommentType($commentType) {
		$this->comment['comment_type'] = $commentType;
	}
	
	/**
	 *	The name that the author submitted with the comment.
	 */
	public function setCommentAuthor($commentAuthor) {
		$this->comment['comment_author'] = $commentAuthor;
	}
	
	/**
	 *	The email address that the author submitted with the comment.
	 *
	 *	The address is assumed to be valid.
	 */
	public function setCommentAuthorEmail($authorEmail) {
		$this->comment['comment_author_email'] = $authorEmail;
	}
	
	/**
	 *	The URL that the author submitted with the comment.
	 */	
	public function setCommentAuthorURL($authorURL) {
		$this->comment['comment_author_url'] = $authorURL;
	}
	
	/**
	 *	The comment's body text.
	 */
	public function setCommentContent($commentBody) {
		$this->comment['comment_content'] = $commentBody;
	}
	
	/**
	 *	Defaults to 80
	 */
	public function setAPIPort($apiPort) {
		$this->apiPort = $apiPort;
	}
	
	/**
	 *	Defaults to rest.akismet.com
	 */
	public function setAkismetServer($akismetServer) {
		$this->akismetServer = $akismetServer;
	}
	
	/**
	 *	Defaults to '1.1'
	 */
	public function setAkismetVersion($akismetVersion) {
		$this->akismetVersion = $akismetVersion;
	}
    public static function getVersion(){
        return VERSION_AKISMET;
    }
}

?>