<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_UpdatePinyin extends Minion_Task
{
    /**
     * @author 施磊
     * 修改项目的拼音
     */
    protected function _execute(array $params){

        #php minion --task=updatePinyin
        $count  = DB::select()->from('project')->where('project_pinyin', 'is',  NULL)->execute()->count();
        echo $count;
        if ($count) {
            for ($i = 0; $i < $count/100; $i++) {
                $project = DB::select()->from('project')->where('project_pinyin', 'is',  NULL)->limit(100)->offset($i)->execute()->as_array();

                if ($project) {
                    foreach ($project as $key => $val) {
                        $str = $val['project_brand_name'];
                        $pinyin = pinyin::getinitial($str);
                        if(!empty($pinyin)) {
                            $params = array('project_pinyin' => $pinyin);
                            $update = DB::update('project')->set($params)->where('project_id', '=', $val['project_id'])->execute();
                            unset($update);
                        }
                        continue;
                    }
                }

                unset($project);
            }
           
            
        }
    }
}