<?php
define('MODULE_CHURCH_VERSION', '1.0.17');
/*
Version History:
  1.0.17 (2015-02-14)
    1) Moved Church_Component::bible_links() into its own class
    2) Moved Church_Component::component_prayer_request() into its own class
    3) Moved component_daily_bible_verse() into its own class
    4) Removed Church::bible_Links() - not needed
    5) Removed discrete function import_pr() - was one time use, no longer needed
    6) Now PSR-2 compliant

  (Older version history in module.church.txt)
*/
class Church extends Posting
{
    public function __construct($ID = "")
    {
        $this->set_module_version(MODULE_CHURCH_VERSION);
    }

    public function install()
    {
        $sql = str_replace('$systemID', SYS_ID, file_get_contents(SYS_MODULES.'module.church.install.sql'));
        $this->uninstall();
        $commands = Backup::db_split_sql($sql);
        foreach ($commands as $command) {
            $this->do_sql_query($command);
        }
        return 'Installed Module '.$this->_get_object_name();
    }

    public function uninstall()
    {
        $sql = str_replace('$systemID', SYS_ID, file_get_contents(SYS_MODULES.'module.church.uninstall.sql'));
        $commands = Backup::db_split_sql($sql);
        foreach ($commands as $command) {
            $this->do_sql_query($command);
        }
        return 'Uninstalled Module '.$this->_get_object_name();
    }
}
