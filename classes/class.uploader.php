<?php
/*
Version History:
  1.0.7 (2015-11-23)
    1) More PSR-2 compliant
    2) Now handles non-chunked files that come via ajax upload
*/
class Uploader extends Record
{
    const VERSION = '1.0.7';
    const DENIED =
        'ascx,asis,asp,aspx,bat,cfc,cfm,cgi,dll,exe,htaccess,jsp,php,php3,php5,phtm,phtml,pl,reg,sh,shtml,shtm';

    public function __construct($type = 'image', $upload_base = '')
    {
        switch(strToLower($type)){
            case "file":
            case "flash":
            case "image":
            case "media":
            case "video":
                $this->_type =          $type;
                break;
            default:
                throw new Exception('Invalid type '.$type);
            break;
        }
        $this->_upload_base =   rtrim($upload_base, '/').'/';
    }

    public function do_upload()
    {
        $file_name =        $_FILES[ 'file' ][ 'name' ];
        $source_file_path = $_FILES[ 'file' ][ 'tmp_name' ];
        $file_ext_arr =     explode(".", $file_name);
        $ext =              strToLower(array_pop($file_ext_arr));
        $arr_denied =       explode(',', self::DENIED);
        $arr_flash =        explode(',', 'fla,flv,swf');
        $arr_image =        explode(',', 'gif,ico,jpg,jpeg,png');
        $arr_media =        explode(',', 'mp3');
        $arr_video =        explode(',', 'flv,gif,jpg,jpeg,png');
        $file_name =        implode('.', $file_ext_arr);
        if (in_array($ext, $arr_denied)) {
            return array(
                'status' =>     '403',
                'message' =>    'Denied file extension .'.$ext
            );
        }
        switch(strToLower($this->_type)){
            case "file":
                // Anything goes (unless explicitly denied)
                break;
            case "flash":
                if (!in_array($ext, $arr_flash)) {
                    return array(
                        'status' =>     '403',
                        'message' =>    'Invalid type .'.$ext.' for uploaded '.$this->_type
                    );
                }
                break;
            case "image":
                if (!in_array($ext, $arr_image)) {
                    return array(
                        'status' =>     '403',
                        'message' =>    'Invalid type .'.$ext.' for uploaded '.$this->_type
                    );
                }
                break;
            case "media":
                if (!in_array($ext, $arr_media)) {
                    return array(
                        'status' =>     '403',
                        'message' =>    'Invalid type .'.$ext.' for uploaded '.$this->_type
                    );
                }
                break;
            case "video":
                if (!in_array($ext, $arr_video)) {
                    return array(
                        'status' =>     '403',
                        'message' =>    'Invalid type .'.$ext.' for uploaded '.$this->_type
                    );
                }
                break;
        }
        $stage_dir =        $_SERVER['DOCUMENT_ROOT'] . "/UserFiles/";
        $file_id =          (isset($_POST['fileId']) ?         $_POST['fileId'] :         '0');
        $partition_index =  (isset($_POST['partitionIndex']) ? $_POST['partitionIndex'] : '0');
        $partition_count =  (isset($_POST['partitionCount']) ? $_POST['partitionCount'] : 1);
        $file_length =      (isset($_POST['fileLength']) ?     $_POST['fileLength'] :     filesize($source_file_path));
        $sessionID =        session_id();
        $chunk_file =       $stage_dir.$sessionID.".".$file_id.".".$partition_index;
        if (!move_uploaded_file($source_file_path, $chunk_file)) {
            return array(
                'status' =>     '500',
                'message' =>    'Cannot move uploaded file "'.$source_file_path.'" to "'.$chunk_file.'"'
            );
        }
        //    check if we have collected all partitions properly
        $all_in_place = true;
        $partitions_length = 0;
        for ($i = 0; $all_in_place && $i<$partition_count; $i++) {
            $partition_file = $stage_dir.$sessionID.".".$file_id.".".$i;
            if (file_exists($partition_file)) {
                $partitions_length += filesize($partition_file);
            } else {
                $all_in_place = false;
            }
        }
        if ($partition_index==$partition_count -1 && (!$all_in_place||$partitions_length!=intval($file_length))) {
            return array(
                'status' =>     '500',
                'message' =>    'Reassembly error');
        }
        if ($all_in_place) {
            $file = $_SERVER[ 'DOCUMENT_ROOT' ].$this->_upload_base . $sessionID . "." . $file_id;
            $file_handle = fopen($file, 'w');
            for ($i = 0; $all_in_place && $i < $partition_count; $i++) {
                $partition_file = $stage_dir . $sessionID . "." . $file_id . "." . $i;
                $partition_file_handle = fopen($partition_file, "rb");
                $contents = fread($partition_file_handle, filesize($partition_file));
                fclose($partition_file_handle);
                fwrite($file_handle, $contents);
                unlink($partition_file);
            }
            fclose($file_handle);
            $file_path =      $this->_upload_base . get_web_safe_ID($file_name).".".$ext;
            if (file_exists($_SERVER[ 'DOCUMENT_ROOT' ].$file_path)) {
                unlink($_SERVER[ 'DOCUMENT_ROOT' ].$file_path);
            }
            rename($file, $_SERVER[ 'DOCUMENT_ROOT' ].$file_path);
            return array(
                'status' =>     '200',
                'size' =>       (int)$file_length,
                'extension' =>  $ext,
                'filename' =>   get_web_safe_ID($file_name).".".$ext,
                'message' =>    'Uploaded',
                'path' =>       $file_path
            );
        }
        return array(
            'status' =>     '100',
            'message' =>    'Continue'
        );
    }
}
