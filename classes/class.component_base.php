<?php
define("VERSION_COMPONENT_BASE", "1.0.19");
/*
Version History:
  1.0.19 (2015-03-01)
    1) Now reduced to a stub file that simply extends Component\Base
       This file will eventuall;y be removed.

  (Older version history in class.component_base.txt)
*/

class Component_Base extends Component\Base
{
    public function get_version()
    {
        return VERSION_COMPONENT_BASE;
    }
}
