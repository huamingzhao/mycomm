<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_UpdateOutsidePro extends Minion_Task {
    
    protected function _execute(array $params) {
        $count = DB::select()->from('project')->where('project_status', '=', 1)->where('project_source', '=', 5)->limit(10)->offset(0)->execute()->as_array();
        $time = date('H', time());
        if($count) {
            if ($count) {
                foreach($count as $val) {
                    $params = array('project_status' => 2, 'project_passtime' => time());
                    DB::update('project')->set($params)->where('project_id', '=', $val['project_id'])->execute();
                    $params = array('status' => 2);
                    DB::update('project_industry')->set($params)->where('project_id', '=', $val['project_id'])->execute();
                    DB::update('project_area')->set($params)->where('project_id', '=', $val['project_id'])->execute();
                    echo $val['project_id'].'-';
                }
            }
        }
    }
}