<?php
define('VERSION_PRAYER_REQUEST', '1.0.0');
/*
Version History:
  1.0.0 (2015-02-14)
    1) Initial release - Moved from Church Module

*/
class Prayer_Request extends Posting
{
    public function __construct($ID = "")
    {
        parent::__construct('postings', $ID);
        $this->_set_type('prayer-request');
        $this->_set_object_name('Prayer Request');
        $this->_set_message_associated('');
    }

    public function get_version()
    {
        return VERSION_PRAYER_REQUEST;
    }
}
