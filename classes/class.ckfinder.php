<?php
define('VERSION_CKFINDER','1.0.6');
/*
Version History:
  1.0.6 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/
class CKFinder{
  function __construct(){

  }

  function check_authentication(){
    return
      isset($_SESSION['person']) &&(
        $_SESSION['person']['permMASTERADMIN'] ||
        $_SESSION['person']['permSYSADMIN'] ||
        $_SESSION['person']['permSYSAPPROVER'] ||
        $_SESSION['person']['permSYSEDITOR']
      );
  }

  function config(&$config){
    $config['LicenseName'] =    'Ecclesiact Web System';
    $config['LicenseKey'] =     'EWA3-Q5LG-TYX6-TR8W-MMA6-JRWH-JHM2';
    $baseUrl = BASE_PATH.'UserFiles/';
    $baseDir = resolveUrl($baseUrl);
    $config['Thumbnails'] = array(
      'url' =>          $baseUrl.'_thumbs',
      'directory' =>    $baseDir.'_thumbs',
      'enabled' =>      true,
      'directAccess' => false,
      'maxWidth' =>     100,
      'maxHeight' =>    100,
      'bmpSupported' => false,
      'quality' =>      80
    );
    $config['Images'] = array(
      'maxWidth' => 0,
      'maxHeight' => 0,
      'quality' => 0
    );
    $config['RoleSessionVar'] = 'CKFinder_UserRole';
    $config['AccessControl'][] = array(
      'role' =>         '*',
      'resourceType' => '*',
      'folder' =>       '/',
      'folderView' =>   true,
      'folderCreate' => true,
      'folderRename' => true,
      'folderDelete' => true,
      'fileView' =>     true,
      'fileUpload' =>   true,
      'fileRename' =>   true,
      'fileDelete' =>   true
    );
    $config['DefaultResourceTypes'] = '';
    $config['ResourceType'][] = array(
      'name' =>                 'File',
      'url' =>                  $baseUrl.'File',
      'directory' =>            $baseDir.'File',
      'maxSize' =>              0,
      'allowedExtensions' =>    '',
      'deniedExtensions' =>     'php,php3,php5,phtml,asp,aspx,ascx,jsp,cfm,cfc,pl,bat,exe,dll,reg,cgi,htaccess,asis,sh,shtml,shtm,phtm'
    );
    $config['ResourceType'][] = array(
      'name' =>                 'Flash',
      'url' =>                  $baseUrl.'Flash',
      'directory' =>            $baseDir.'Flash',
      'maxSize' =>              0,
      'allowedExtensions' =>    'swf,fla,flv',
      'deniedExtensions' =>     ''
    );
    $config['ResourceType'][] = array(
      'name' =>                 'Image',
      'url' =>                  $baseUrl.'Image',
      'directory' =>            $baseDir.'Image',
      'maxSize' =>              0,
      'allowedExtensions' =>    'gif,ico,jpeg,jpg,png',
      'deniedExtensions' =>     ''
    );
    $config['ResourceType'][] = array(
      'name' =>                 'Media',
      'url' =>                  $baseUrl.'Media',
      'directory' =>            $baseDir.'Media',
      'maxSize' =>              0,
      'allowedExtensions' =>    'doc,flv,gif,jpg,jpeg,mp3,pdf,png',
      'deniedExtensions' =>     ''
    );
    $config['ResourceType'][] = array(
      'name' =>                 'Video',
      'url' =>                  $baseUrl.'Video',
      'directory' =>            $baseDir.'Video',
      'maxSize' =>              0,
      'allowedExtensions' =>    'f4v,flv,gif,jpg,jpeg,mov,m4v,mp4,png',
      'deniedExtensions' =>     ''
    );
    $config['CheckDoubleExtension'] =   true;
    $config['FilesystemEncoding'] =     'UTF-8';
    $config['SecureImageUploads'] =     true;
    $config['CheckSizeAfterScaling'] =  true;
    $config['HtmlExtensions'] =         array('html', 'htm', 'xml', 'js');
    $config['HideFolders'] =            array(".svn", "CVS");
    $config['HideFiles'] =              array(".*");
    $config['ChmodFiles'] =             0777;
    $config['ChmodFolders'] =           0777;
  }

  public static function getVersion(){
    return VERSION_CKFINDER;
  }
}

?>