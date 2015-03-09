<?php
namespace Component;

define('VERSION_NS_COMPONENT_COMMUNITY_COLLECTION_VIEWER', '1.0.1');
/*
Version History:
  1.0.1 (2015-03-07)
    1) Now namespaced and PSR-2 compliant

*/
class CommunityCollectionViewer extends CollectionViewer
{
    public function __construct()
    {
        parent::__construct();
        $this->_ident =             'community_collection_viewer';
        $this->_cm_podcast =        'module_cm_podcast';
        $this->_cm_podcastalbum =   'podcastalbum';
    }

    public function getversion()
    {
        return VERSION_NS_COMPONENT_COMMUNITY_COLLECTION_VIEWER;
    }
}
