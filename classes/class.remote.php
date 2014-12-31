<?php
define('VERSION_REMOTE','1.0.10');
/*
Version History:
  1.0.10 (2012-07-16)
    1) Minor code formatting tweaks
  1.0.9 (2012-07-14)
    1) Remote::get_items() now exists early if attempt to look up a local site failed
    2) Remote::get_items() now uses array of arguments for $Obj::get_records()

  (Older version history in class.remote.txt)
*/
class Remote {
  var $URL;
  var $isLocal;

  function __construct($URL='',$isLocal=false) {
    $this->URL =        $URL;
    $this->isLocal =    $isLocal;
  }

  function get_items(
    $Object_type,
    $what="",
    $YYYY="",
    $MM="",
    $limit=0,
    $category="*",
    $offset=0,
    $category_master=0,
    $memberID=0,
    $personID=0,
    $DD="",
    $container_path="",
    $container_subs=false
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
        if(!$systemID = $ObjSystem->get_IDs_for_URLs($this->URL)){
          return array();
        }
        $Obj =      new $Object_type(0,$systemID);
        $results =  $Obj->get_records(
          array(
            'byRemote' =>         true,
            'category' =>         $category,
            'category_master' =>  $category_master,
            'container_path' =>   $container_path,
            'container_subs' =>   $container_subs,
            'DD' =>               $DD,
            'limit' =>            $limit,
            'memberID' =>         $memberID,
            'MM' =>               $MM,
            'offset' =>           $offset,
            'order_by' =>         'date',
            'personID' =>         $personID,
            'what' =>             $what,
            'YYYY' =>             $YYYY
          )
        );
        $out = $results['data'];
        return $out;
      break;
      default:
        $URL =
           trim($this->URL,'/').'/'
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
          foreach ($records as &$record){
            if (isset($record_type)){
              $record['type'] = $record_type;
            }
          }
          return $records;
        }
      break;
    }
  }
  public function get_version(){
    return VERSION_REMOTE;
  }
}
?>