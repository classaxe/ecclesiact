<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class CommunityCollectionViewer extends CollectionViewer
{
    const VERSION = '1.0.2';

    public function __construct()
    {
        parent::__construct();
        $this->_ident =             'community_collection_viewer';
        $this->_cm_podcast =        'module_cm_podcast';
        $this->_cm_podcastalbum =   'podcastalbum';
    }
}
