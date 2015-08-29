<?php
define('VERSION_CRON', '1.0.11');
/*
Version History:
  1.0.11 (2015-08-29)
    1) Message now gives information about job run time

*/
class CRON extends Record
{
    public static function heartbeat()
    {
        static::actions();
        if (get_var('mem')) {
            mem('in heartbeat()');
            y(mem());
        }
    }

    public static function actions()
    {
        static::runScheduledTasks();
        static::sendNotifications();
        static::updateMapsPending();
        static::updateTimestamps();
    }

    protected static function runScheduledTasks()
    {
        $Obj_ST = new Scheduled_Task;
        $Obj_ST->all_tasks_load();
        $Obj_ST->all_tasks_run();
    }

    protected static function sendNotifications()
    {
        $Obj_N = new Notification;
        $Obj_N->notify_all();
    }

    protected static function updateMapsPending()
    {
        Google_Map::on_schedule_update_pending();
    }

    protected static function updateTimestamps()
    {
        $Obj_S = new System;
        $Obj_S->set_field_for_all('cron_job_heartbeat_last_run', get_timestamp());
    }

    public static function getVersion()
    {
        return VERSION_CRON;
    }
}
