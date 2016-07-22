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
class Task_OutsideAttr extends Minion_Task {

    /**
     * 外采定期采集脚本
     *  @author 施磊
     *  @return null
     *  @shell php minion --task=OutsideAttr
     */
    protected function _execute(array $params) {
        $count = DB::select()->from('test_project')->where('project_status', '=', 1)->execute()->count();
        if ($count) {
            for ($i = 0; $i < $count/100; $i++) {
                $project = DB::select()->from('test_project')->where('project_status', '=', 1)->limit(100)->offset($i)->execute()->as_array();

                if ($project) {
                    foreach ($project as $key => $val) {
                        $this->addArea(array('pro_id' => $val['pro_id'], 'pro_area' => $val['pro_area'], 'pro_industry' => $val['pro_industry'], 'pro_amount_type' => $val['pro_amount_type']));
                        $this->addIndustry($val);
                        $this->addAmount($val);
                    }
                }

                unset($project);
            }
           
            
        }
    }
    
     private function addArea($list) {
        $pro_area = array_filter(explode(',', preg_replace("/\r/", '', $list['pro_area'])));
        $pro_area_status = true;
        $pro_area_val = array();
        if ($pro_area) {
            foreach ($pro_area as $val) {
                if (trim($val)) {

                    $pro_area_status = false;
                    try {
                        $cond = array('keyword' => $val, 'question_type' => 2, 'status' => '2');
                        $param = array('keyword', 'question_type', 'status');
                        DB::insert('directory', $param)->values($cond)->execute();
                        echo $val;
                        continue;
                    } catch (Exception $exc) {
                        continue;
                    }

                    unset($area);
                }
            }
        }
    }

    private function addIndustry($list) {
        $pro_area = array_filter(explode(',', preg_replace("/\r/", '', $list['pro_industry'])));
        $pro_area_status = true;
        $pro_area_val = array();
        if ($pro_area) {
            foreach ($pro_area as $val) {
                if (trim($val)) {

                    $pro_area_status = false;
                    try {
                        $cond = array('keyword' => $val, 'question_type' => 6, 'status' => '2');
                        $param = array('keyword', 'question_type', 'status');
                        DB::insert('directory', $param)->values($cond)->execute();
                        continue;
                    } catch (Exception $exc) {
                        continue;
                    }

                    unset($area);
                }
            }
        }
    }

    private function addAmount($list) {
        $amount_type = preg_replace("/\r/", '', $list['pro_amount_type']);
        if(!$amount_type) return false;
        $cond = array('keyword' => $amount_type, 'question_type' => 7, 'status' => '2');
        $param = array('keyword', 'question_type', 'status');
        try {
            DB::insert('directory', $param)->values($cond)->execute();
        } catch (Exception $exc) {
            
        }
    }
    
}