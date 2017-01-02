<?php
/*
Version History:
  1.0.14 (2016-12-31)
    1) Remote::get_items() now uses obj::getFilteredSortedAndPagedRecords() to get local records
*/
class Remote extends Base
{
    const VERSION = '1.0.14';

    public $URL;
    public $isLocal;

    public function __construct($URL = '', $isLocal = false)
    {
        $this->URL =        $URL;
        $this->isLocal =    $isLocal;
    }

    public function get_items(
        $Object_type,
        $what = "",
        $YYYY = "",
        $MM = "",
        $limit = 0,
        $category = "*",
        $offset = 0,
        $category_master = 0,
        $memberID = 0,
        $personID = 0,
        $DD = "",
        $container_path = "",
        $container_subs = false
    ) {
        switch ($Object_type) {
            case "config":
                $submode =      'config';
                break;
            case "articles":
                $Object_type =  'Article';
                $record_type =  'article';
                $submode =      'shared_articles';
                break;
            case "events":
                $Object_type =  'Event';
                $record_type =  'event';
                $submode =      'shared_events';
                break;
            case "gallery-images":
                $Object_type =  'Gallery_Image';
                $record_type =  'gallery-image';
                $submode =      'shared_gallery_images';
                break;
            case "jobs":
                $Object_type =  'Job_Posting';
                $record_type =  'job';
                $submode =      'shared_jobs';
                break;
            case "news":
                $Object_type =  'News_Item';
                $record_type =  'news';
                $submode =      'shared_news';
                break;
            case "podcasts":
                $Object_type =  'Podcast';
                $record_type =  'podcast';
                $submode =      'shared_podcasts';
                break;
        }
        switch ($this->isLocal) {
            case true:
                $ObjSystem = new System;
                if (!$systemID = $ObjSystem->get_IDs_for_URLs($this->URL)) {
                    return array();
                }
                $Obj =      new $Object_type(0,$systemID);
                $results =  $Obj->getFilteredSortedAndPagedRecords(
                    array(
                        'byRemote' =>               true,
                        'filter_category_list' =>   $category,
                        'filter_category_master' => $category_master,
                        'filter_container_path' =>  $container_path,
                        'filter_container_subs' =>  $container_subs,
                        'filter_date_DD' =>         $DD,
                        'filter_date_MM' =>         $MM,
                        'filter_date_YYYY' =>       $YYYY,
                        'filter_memberID' =>        $memberID,
                        'filter_personID' =>        $personID,
                        'filter_what' =>            $what,
                        'results_limit' =>          $limit,
                        'results_offset' =>         $offset,
                        'results_order' =>          'date',
                    )
                );
                $out = $results['data'];
                return $out;
            break;
            default:
                $URL =
                     trim($this->URL, '/').'/'
                    .($Object_type=='config' ?
                     '?mode=rss&submode='.$submode.'&what='.$what
                     :
                     'rss/'.$submode.'/?what='.$what
                     )
                    .($YYYY ?             "&YYYY=".$YYYY : "")
                    .($MM ?               "&MM=".$MM : "")
                    .($DD ?               "&DD=".$DD : "")
                    .($limit ?            "&limit=".$limit : "")
                    .($category!='*' ?    "&category=".$category : "")
                    .($offset ?           "&offset=".$offset : "")
                    .($category_master ?  "&category_master=".$category_master : "")
                    .($memberID ?         "&memberID=".$memberID : "")
                    .($personID ?         "&personID=".$personID : "")
                    .($container_path ?   "&container_path=".$container_path : "")
                    .($container_subs ?   "&container_subs=".$container_subs : "")          ;
      //        y($URL);die;
                $Obj_RSS = new RSS($URL);
                $records = $Obj_RSS->get_items();
      //        y($records);
                if (count($records)) {
                    foreach ($records as &$record) {
                        if (isset($record_type)) {
                            $record['type'] = $record_type;
                        }
                    }
                    return $records;
                }
                break;
        }
    }
}
