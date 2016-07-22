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
class Task_OutsidePro extends Minion_Task {

    /**
     * 外采定期采集脚本
     *  @author 施磊
     *  @return null
     *  @shell php minion --task=OutsidePro
     */
    protected function _execute(array $params) {
        //echo memory_get_usage();
        $count = DB::select()->from('test_project')->where('project_status', '=', 1)->execute()->count();
        var_dump($count);
        if ($count) {
            for ($i = 0; $i < 5; $i++) {
                $project = DB::select()->from('test_project')->where('project_status', '=', 1)->limit(100)->offset($i)->execute()->as_array();

                if ($project) {
                    foreach ($project as $key => $val) {
                        #第一步 检查是否有企业信息
                        /*
                        if (!$val['com_id'] && $val['com_name']) {
                            $company = DB::select()->from('test_company')->where('com_name', '=', $val['com_name'])->execute()->as_array();
                            if ($company) {
                                $params = array('com_id' => $company[0]['com_id']);
                                DB::update('test_project')->set($params)->where('pro_id', '=', $val['pro_id'])->execute();
                                continue;
                            }
                        }
                        unset($company);*/
                        echo $val['pro_id'];
                        $this->addArea(array('pro_id' => $val['pro_id'], 'pro_area' => $val['pro_area'], 'pro_industry' => $val['pro_industry'], 'pro_amount_type' => $val['pro_amount_type']));
                        $this->addIndustry($val);
                        $this->addAmount($val);
                        $proSta = $this->checkAreaAndIndustry($val);
                        if($proSta['area'] && $proSta['industry'] && $proSta['amount']) {
                        $param = array('project_brand_name', 'com_id', 'project_logo', 'project_brand_birthplace', 'project_principal_products', 'projcet_founding_time', 'project_joining_fee', 'project_security_deposit', 'project_phone','project_contact_people', 'project_handset', 'project_summary','outside_id','project_source','project_status', 'project_amount_type','outside_com_id');
                            $cond['project_brand_name'] = $val['pro_name'];
                            $cond['com_id'] = 0;
                            $cond['project_logo'] = $val['pro_logo'];
                            $cond['project_brand_birthplace'] = $val['pro_brand_birthplace'];
                            $cond['project_principal_products'] = $val['pro_principal_products'];
                            $cond['projcet_founding_time'] = $val['pro_founding_time'];
                            $cond['project_joining_fee'] = $val['pro_joining_fee'] ? $val['pro_joining_fee'] : 0;
                            $cond['project_security_deposit'] = $val['pro_security_deposit'];
                            //$cond['project_groups_label'] = $val['pro_groups_label'];
                            $cond['project_phone'] = $val['pro_phone'];
                            $cond['project_contact_people'] = $val['pro_contact_people'];
                            $cond['project_handset'] = $val['pro_handset'];
                            $cond['project_summary'] = $val['pro_summary'];
                            //图片
                            $cond['outside_id'] = $val['outside_id'];
                            $cond['project_source'] = 5;
                            $cond['project_status'] = 1;
                            $cond['project_amount_type'] = 1;
                            $cond['outside_com_id'] = $val['com_id'] ? $val['com_id'] : 0;
                            if(!is_bool($proSta['amount'])) {
                                $param = array('project_brand_name', 'com_id', 'project_logo', 'project_brand_birthplace', 'project_principal_products', 'projcet_founding_time', 'project_joining_fee', 'project_security_deposit', 'project_phone','project_contact_people', 'project_handset', 'project_summary','outside_id','project_source','project_status','project_amount_type','outside_com_id');
                                $cond['project_amount_type'] = $proSta['amount'];
                            }
                            #检查是否已经存在项目
                            $proList = DB::select()->from('project')->where('project_brand_name', '=', $val['pro_name'])->limit(1)->execute()->as_array();
                            $params = array('project_status' => 0);
                            if($proList || !$val['pro_logo'] || is_int(strpos($val['pro_name'], '公司'))) {
                                var_dump($proList, $val['pro_logo']);
                                DB::update('test_project')->set($params)->where('pro_id', '=', $val['pro_id'])->execute(); continue;
                            }
                            
                            $pro = DB::insert('project', $param)->values($cond)->execute();
                            //var_dump($pro, $param, $cond);
                            if(isset($pro[0]) && $pro[0]) {
                                $params = array('project_status' => 2);
                                DB::update('test_project')->set($params)->where('pro_id', '=', $val['pro_id'])->execute(); 
                                //处理行业什么的
                                if(!is_bool($proSta['industry'])) {
                                    foreach($proSta['industry'] as $valInd) {
                                        $indParam = array('project_id', 'industry_id', 'status');
                                        $indCond = array($pro[0], $valInd, 2);
                                        $pro = DB::insert('project_industry', $indParam)->values($indCond)->execute();
                                    }
                                }
                                
                                if(!is_bool($proSta['area'])) {
                                    foreach($proSta['area'] as $valArea) {
                                        $indParam = array('project_id', 'area_id', 'pro_id', 'status');
                                        $indCond = array($pro[0], $valArea, $valArea, 2);
                                        $pro = DB::insert('project_area', $indParam)->values($indCond)->execute();
                                    }
                                }
                            }
                            
                        }else{
                            continue;
                        }
                    }
                }

                unset($project);
            }
        }
    }

    private function addArea($list) {
        $pro_area = explode(',', preg_replace("/\r/", '', $list['pro_area']));
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
        $pro_area = explode(',', preg_replace("/\r/", '', $list['pro_industry']));
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

    private function checkAreaAndIndustry($list = array()) {
        $return = array('area' => false, 'industry' => false, 'amount' => false);
        $pro_area =  array_filter(explode(',', preg_replace("/\r/", '', $list['pro_area'])));
        $pro_industry =  array_filter(explode(',', preg_replace("/\r/", '', $list['pro_industry'])));
        $amount_type = preg_replace("/\r/", '', $list['pro_amount_type']);
        $pro_area_status = true;
        $pro_area_val = array();
        $pro_industry_status = true;
        $pro_industry_val = array();
        $pro_amount_type = '';
        #处理地区
        if ($pro_area) {
            foreach ($pro_area as $val) {
                if (trim($val)) {
                    $area = DB::select('question_id')->from('directory')->where('keyword', '=', $val)->where('status', '!=', 2)->where('question_type', '=', 2)->limit(1)->execute()->as_array();
                  
                    if ($area && $area[0]['question_id']) {
                        $pro_area_val[$area[0]['question_id']] = $area[0]['question_id'];
                        continue;
                    } else {
                        $pro_area_status = false;
                        break;
                    }
                    unset($area);
                }
            }
            if ($pro_area_status)
                $return['area'] = $pro_area_val;
            if(!$return['area'])  $return['area'] = TRUE;
        }else {
            $return['area'] = TRUE;
        }
        #处理行业
        if ($pro_industry) {
            foreach ($pro_industry as $val) {
                if (trim($val)) {
                    $area = DB::select('question_id')->from('directory')->where('keyword', '=', $val)->where('status', '!=', 2)->where('question_type', '=', 6)->limit(1)->execute()->as_array();
                    if ($area && $area[0]['question_id']) {
                        $pro_industry_val[$area[0]['question_id']] = $area[0]['question_id'];
                        continue;
                    } else {
                        $pro_industry_status = false;
                        break;
                    }
                    unset($area);
                }
            }
            if ($pro_industry_status)
                $return['industry'] = $pro_industry_val;
            if(!$return['industry'])  $return['industry'] = TRUE;
        }else {
            $return['industry'] = TRUE;
        }

        #处理金额
        if ($amount_type) {
            $area = DB::select('question_id')->from('directory')->where('keyword', '=', $amount_type)->where('status', '!=', 2)->where('question_type', '=', 7)->limit(1)->execute()->as_array();
            if ($area && $area[0]['question_id']) {
                $pro_amount_type = $area[0]['question_id'];
            } else {
                $pro_amount_type = '';
            }
            unset($area);
            if ($pro_amount_type)
                $return['amount'] = $pro_amount_type;
        }else {
            $return['amount'] = TRUE;
        }
        if(!$return['amount'])  $return['amount'] = TRUE;
        return $return;
    }

}
