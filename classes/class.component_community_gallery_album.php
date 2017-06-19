<?php
/*
Version History:
  1.0.0 (2017-06-19)
    1) Newly added class to allow specific overriding of getAlbumPath() when using Component_Gallery_Album in a
       community display context
*/

class Component_Community_Gallery_Album extends Component_Gallery_Album
{
    const VERSION = '1.0.0';

    protected function getAlbumPath()
    {
        global $page_vars;
        if ($page_vars['path']==$page_vars['path_real'].trim($this->_cp['indicated_root_folder'], '/')) {
            return '';
        }
        return
             "//"
            .($this->_cp['filter_root_path'] ? trim($this->_cp['filter_root_path'], '/').'/' : '')
            .substr(
                $page_vars['path'],
                strlen(
                    $page_vars['path_real']
                    .$this->_cp['indicated_root_folder'].'/'
                )
            );
    }
}