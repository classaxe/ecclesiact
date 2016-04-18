<?php
/*
Version History:
  1.0.3 (2016-04-17)
    1) No longer tries to extend Base class - called from a context that doesn't know about autoloading
*/

// This cannot extend Base as it is called from a context that doesn't support autoloading
class RSS_Proxy
{
    const VERSION = '1.0.3';

    static private $_cache_dir;
  
    private static function _safe_url($sFolder)
    {
        $sFolder = preg_replace("/^[\.\/]*/", "", $sFolder);
        $sFolder = preg_replace("/[\.\/]*$/", "", $sFolder);
        $sFolder_arr = explode('#', $sFolder);
        return $sFolder_arr[0];
    }

    private static function _translate_url_to_filename($sFileName)
    {
        $sFileName = str_replace(array('http://','https://'), '', $sFileName);
        return
             RSS_Proxy::$_cache_dir
            .str_replace(array('/','?','.','=','&amp;','&'), '_', $sFileName)
            .'.cache';
    }

    private static function _cache_save($sFileName, &$sFileContent)
    {
        file_put_contents($sFileName, $sFileContent);
    }

    private static function _get_remote_xml($url)
    {
        $sFile = @file_get_contents($url);
        return $sFile;
    }


    private static function _cache_read($sLocalName)
    {
        $hFile = fopen($sLocalName, "r");
        if ($hFile && filesize($sLocalName) > 0) {
            $sFile = fread($hFile, filesize($sLocalName));
            fclose($hFile);
            return $sFile;
        } else {
            return "File is missing: ".$sLocalName;
        }
    }

    public static function get($sFileName, $max_age_seconds = 600)
    {
        RSS_Proxy::$_cache_dir = SYS_SHARED.'rss_proxy_cache/';
        $sFileName = RSS_Proxy::_safe_url($sFileName);
        header("Content-type: text/xml");
        if (empty( $sFileName )) {
            return "<empty>nothing</empty>";
        }
        $sLocalName = RSS_Proxy::_translate_url_to_filename($sFileName);
        $sFile = "";
        if (file_exists($sLocalName)) {
            $nMTime = filemtime($sLocalName);
            if (( time() - $nMTime ) > $max_age_seconds) {
                $sFile = RSS_Proxy::_get_remote_xml($sFileName);
                RSS_Proxy::_cache_save($sLocalName, $sFile);
            } else {
                $sFile = RSS_Proxy::_cache_read($sLocalName);
            }
        } else {
            $sFile = RSS_Proxy::_get_remote_xml($sFileName);
            RSS_Proxy::_cache_save($sLocalName, $sFile);
        }
        return $sFile;
    }

    public static function getVersion()
    {
        return static::VERSION;
    }
}
