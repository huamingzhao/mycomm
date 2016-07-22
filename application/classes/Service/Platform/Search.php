<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * TODO cookie的时间和日期保存为配置形式
 * 首页搜索和精准搜索服务
 * @author 龚湧
 *
 */
class Service_Platform_Search {

    //配置问题对应的匹配度
    private $_question_perce = array(1 => 13, 2 => 11, 3 => 7, 4 => 21, 5 => 17, 6 => 3);

    /**
     *
      array(
      2 =>
      array (size=2)
      'q' => string '你想在哪里做?' (length=19)
      'attr' =>
      array (size=5)
      1 => string '公务员' (length=9)
      2 => string '上班族' (length=9)
      3 => string '做生意1-3年' (length=15)
      4 => string '做生意3年以上的' (length=22)
      5 => string '赋闲在家' (length=12)
      3 =>
      array (size=1)
      'q' => string '你曾经做过什么?' (length=22)
      )
     * 获取所有引导问题
     * @author 龚湧
     * @return array |boolean
     */
    public function getAllQuestion() {
        return guide::getAllQuestion();
    }

    /**
     * 一句话搜索项目
     * @author 沈鹏飞
     * @param string $word
     * @return array
     */
    public function getWordSearch($word, $cond = array()) {
        //$result = searchservice::getInstance()->searchKeyword($word, $cond);
        $result = searchservice::getInstance()->searchWordBySolr($word, $cond);

        //获取关键字归属的问题分类
        //if(isset($result['code']) && $result['code']=='200' && isset($result['words'])){
        //    $questiontype=$this->getWordType($result['words']);
        //    //获取重构问题分类之后的关键字列表
        //    $result=$this->reConstruct($questiontype);
        //}
        return $result;
    }

    /**
     * 一句话搜索投资者
     * @author 钟涛
     * @param string $word
     * @return array
     */
    public function getWordSearchInvestor($word, $cond = array()) {
        $result = searchservice::getInstance()->searchInvestorBySolr($word, $cond);
        return $result;
    }
    
    /**
     * 一句话搜索投资者[默认走sql筛选]
     * @author 钟涛
     */
  	public function getInvestorByUserTable (){
    	$permodel= ORM::factory('Personinfo');
    	$permodel->join('ssouser','LEFT')->on('user_id','=','per_user_id');
    	$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
    	$permodel->where('per_realname','!=',''); //不包括没有姓名的
    	$page_list=$permodel->select('*')->reset(false)->find_all( );
    	$total_count=count($page_list);
    	$perpage = Pagination::factory(array(
    			'total_items'    => $total_count,
    			'items_per_page' => 10,
    	));
    	$page_list2=$permodel->select('*')->limit($perpage->items_per_page)->offset($perpage->offset)->order_by('last_logintime', 'DESC')->find_all( );
    	$resultlist=array();
    	$resultlist['matches']=array();
    	foreach($page_list2 as $perlist){
    		$resultlist['matches'][]=$perlist->per_user_id;
    	}
    	$resultlist['total']=$total_count;
    	$resultlist['words']=array();
    	return $resultlist;
    }
    
    /**
     * 通过条件拼接成新的搜索语句
     * @author 施磊
     */
    public function newWord($cond) {
        $getArr = array('inid' => 6, 'areaid' => 2, 'atype' => 7, 'risk' => 10, 'pmodel' => 1);
        $mod = new Service_Platform_Project();
        $return = '';
        foreach ($getArr as $key => $val) {
            if (isset($cond[$key]) && $cond[$key])
                $return .= $mod->getQuestCont($val, $cond[$key]) . ' ';
        }
        if ($cond['unallow']) {
            $return .= implode(' ', explode('|', $cond['unallow']));
        }
        return $return;
    }

    /**
     * 匹配关键词 和 条件
     * @author 施磊
     */
    public function searchCondGroup($keywords, $cond) {
        $mod = new Service_Platform_Project();
        $return = array('cond' => array(), 'keyList' => array());
        $getArr = array('sort', 'inid', 'areaid', 'atype', 'risk', 'pmodel', 'istatus');
        $return['cond']['sort'] = arr::get($cond, 'sort', 0);
        $return['cond']['istatus'] = arr::get($cond, 'istatus', 0);
        if ($keywords) {
            foreach ($keywords as $key => $val) {
                if ($key == 1) {
                    if (isset($val['allowKey']) && $val['allowKey']) {
                        foreach ($val['allowKey'] as $keyT => $valT) {
                            $return['cond']['pmodel'] = $keyT;
                            $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                        }
                    }
                } elseif ($key == 2) {
                    if (isset($val['allowKey']) && $val['allowKey']) {
                        foreach ($val['allowKey'] as $keyT => $valT) {
                            $pid = $mod->getCityPid($keyT);
                            if ($pid == 0 && count($val['allowKey']) == 1) {
                                $return['cond']['areaid'] = $keyT;
                                $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                            } elseif ($pid != 0) {
                                $return['cond']['areaid'] = $keyT;
                                $return['keyList']['allow'][$key][$pid] = $mod->getQuestCont($key, $pid);
                                $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                            }
                        }
                    }
                } elseif ($key == 7) {
                    if (isset($val['allowKey']) && $val['allowKey']) {
                        foreach ($val['allowKey'] as $keyT => $valT) {
                            $return['cond']['atype'] = $keyT;
                            $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                        }
                    }
                } elseif ($key == 10) {
                    if (isset($val['allowKey']) && $val['allowKey']) {
                        foreach ($val['allowKey'] as $keyT => $valT) {
                            $return['cond']['risk'] = $keyT;
                            $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                        }
                    }
                } elseif ($key == 6) {
                    if (isset($val['allowKey']) && $val['allowKey']) {
                        foreach ($val['allowKey'] as $keyT => $valT) {
                            $pid = $mod->getIndustryPid($keyT);
                            if ($pid == 0 && count($val['allowKey']) == 1) {
                                $return['cond']['inid'] = $keyT;
                                $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                            } elseif ($pid != 0) {
                                $return['cond']['inid'] = $keyT;
                                $return['keyList']['allow'][$key][$pid] = $mod->getQuestCont($key, $pid);
                                $return['keyList']['allow'][$key][$keyT] = $mod->getQuestCont($key, $keyT);
                            }
                        }
                    }
                }
                if (isset($val['unallowKey']) && $val['unallowKey']) {
                    foreach ($val['unallowKey'] as $keyT => $valT) {
                        if (arr::get($val['unallowKey'], $keyT)) {
                            if ($key) {
                                foreach ($valT as $keyTr => $valTr) {

                                    $return['keyList']['unallow'][$key][$keyT] = $mod->getQuestCont($key, $valTr['qs_val']);
                                    $return['cond']['unallow'][] = $mod->getQuestCont($key, $valTr['qs_val']);
                                }
                            } else {
                                foreach (arr::get($val['unallowKey'], $keyT) as $keyTr => $valTr) {
                                    $return['keyList']['unallow'][$key][] = $keyTr;
                                    $return['cond']['unallow'][] = $keyTr;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($cond['inid']) {
            $return['cond']['inid'] = $cond['inid'];
            $return['keyList']['allow'][6] = array();
            $return['keyList']['unallow'][6] = array();
            $pid = $mod->getIndustryPid($cond['inid']);
            if ($pid) {
                $return['keyList']['allow'][6][$pid] = $mod->getQuestCont(6, $pid);
                $return['keyList']['allow'][6][$cond['inid']] = $mod->getQuestCont(6, $cond['inid']);
            } else {
                $return['keyList']['allow'][6][$cond['inid']] = $mod->getQuestCont(6, $cond['inid']);
            }
        }
        if ($cond['areaid']) {
            $return['cond']['areaid'] = $cond['areaid'];
            $return['keyList']['allow'][2] = array();
            $return['keyList']['unallow'][2] = array();
            $pid = $mod->getCityPid($cond['areaid']);
            if ($pid) {
                $return['keyList']['allow'][2][$pid] = $mod->getQuestCont(2, $pid);
                $return['keyList']['allow'][2][$cond['areaid']] = $mod->getQuestCont(2, $cond['areaid']);
            } else {
                $return['keyList']['allow'][2][$cond['areaid']] = $mod->getQuestCont(2, $cond['areaid']);
            }
        }

        if (isset($cond['atype']) && $cond['atype']) {
            $return['cond']['atype'] = $cond['atype'];
            $return['keyList']['allow'][7] = array();
            $return['keyList']['unallow'][7] = array();
            $return['keyList']['allow'][7][$cond['atype']] = $mod->getQuestCont(7, $cond['atype']);
        }
        if (isset($cond['risk']) && $cond['risk']) {
            $return['cond']['risk'] = $cond['risk'];
            $return['keyList']['allow'][10] = array();
            $return['keyList']['unallow'][10] = array();
            $return['keyList']['allow'][10][$cond['risk']] = $mod->getQuestCont(10, $cond['risk']);
        }
        if (isset($cond['pmodel']) && $cond['pmodel']) {
            $return['cond']['pmodel'] = $cond['pmodel'];
            $return['keyList']['allow'][1] = array();
            $return['keyList']['unallow'][1] = array();
            $return['keyList']['allow'][1][$cond['pmodel']] = $mod->getQuestCont(1, $cond['pmodel']);
        }


        #附加判断
        if (arr::get(arr::get($return['keyList'], 'allow', array()), 2, array()) && !arr::get($return['keyList']['allow'][2], 88, array())) {
            $tempArr[88] = "全国";
            $return['keyList']['allow'][2] = $tempArr + $return['keyList']['allow'][2];
        }
        return $return;
    }

    /**
     * 分组关键词
     * @author 施磊
     */
    public function searchWordGroup($keyGroup = array()) {
        $return = array();
        $tempArea = array();
        $tempIndustry = array();
        if ($keyGroup) {
            foreach ($keyGroup as $key => $val) {
                $ormReturn = ORM::factory("Directory")->where('keyword', '=', $key)->find()->as_array();
                $keyGroup[$key]['qs_id'] = arr::get($ormReturn, 'question_type', 0);
                $keyGroup[$key]['qs_val'] = arr::get($ormReturn, 'question_id', 0);
            }
            foreach ($keyGroup as $keyT => $valT) {
                if (!arr::get($return, $valT['qs_id'])) {
                    $return[$valT['qs_id']]['unallowKey'] = array();
                }
                #未匹配到的标签
                if ($valT['qs_id'] == 0) {
                    $return[$valT['qs_id']]['unallowKey'][] = array($keyT => $valT);
                    continue;
                }
                #地区
                elseif ($valT['qs_id'] == 2) {
                    $nowArea = $this->groupArea($valT['qs_val']);
                    $valT['pid'] = $nowArea['pid'];
                    $allAreaid = array();
                    #超过2个父级行业id 所有都变成未匹配的
                    if (count($allAreaid) >= 2) {
                        $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                    }
                    if (!$tempArea) {
                        $tempArea = $nowArea;
                        $valT['city_name'] = $nowArea['area_name'];
                        $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                        $return[$valT['qs_id']]['unallowKey'][] = array();
                        if ($nowArea['pid'] == 0) {
                            $allAreaid[] = $nowArea['area_id'];
                        }
                        continue;
                    } else {
                        #如果存在的一个一级
                        if ($tempArea['pid'] == 0) {
                            #传入相同的2个一级
                            if ($nowArea['pid'] == 0 && $tempArea['area_id'] == $nowArea['area_id']) {
                                continue;
                            }
                            #传入2个不同的一级 取后者
                            elseif ($nowArea['pid'] == 0 && $tempArea['area_id'] != $nowArea['pid']) {

                                if ($nowArea['pid'] == 0) {
                                    $allAreaid[] = $nowArea['area_id'];
                                }
                                if (count($return[$valT['qs_id']]['allowKey']) > 0) {
                                    $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                    $return[$valT['qs_id']]['unallowKey'] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                    $return[$valT['qs_id']]['allowKey'] = array();
                                    continue;
                                } else {
                                    $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                    continue;
                                }
                            }
                            #传入这个一级的子集
                            elseif ($nowArea['pid'] != 0 && $nowArea['pid'] == $tempArea['area_id']) {
                                $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                $tempArea = $nowArea;
                                continue;
                            }
                            #传入的不是这个一级的子集
                            elseif ($nowArea['pid'] != 0 && $nowArea['pid'] != $tempArea['area_id']) {
                                $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                continue;
                            }
                        } else {
                            #原有的是2级 传入的是它的一级
                            if ($nowArea['pid'] == 0 && $tempArea['pid'] == $nowArea['area_id']) {
                                $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                continue;
                            }
                            #传入的不是这个2级的一级 取后者
                            elseif ($nowArea['pid'] == 0 && $tempArea['pid'] != $nowArea['area_id']) {
                                if ($nowArea['pid'] == 0) {
                                    $allAreaid[] = $nowArea['area_id'];
                                }
                                if (count($return[$valT['qs_id']]['allowKey']) > 0) {
                                    $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                    $return[$valT['qs_id']]['unallowKey'] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                    $return[$valT['qs_id']]['allowKey'] = array();
                                    continue;
                                } else {
                                    $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                    continue;
                                }
                            }
                            #传入的是同一个一级的2级 替换
                            elseif ($nowArea['pid'] != 0 && $tempArea['pid'] == $nowArea['pid']) {
                                $return[$valT['qs_id']]['allowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                $return[$valT['qs_id']]['unallowKey'][$tempArea['area_id']] = $return[$valT['qs_id']]['allowKey'][$tempArea['area_id']];
                                unset($return[$valT['qs_id']]['allowKey'][$tempArea['area_id']]);
                                $tempArea = $nowArea;
                                continue;
                            }
                            #传入的是不同一级的2级
                            elseif ($nowArea['pid'] != 0 && $tempArea['pid'] != $nowArea['pid']) {
                                $allAreaid[] = $nowArea['pid'];
                                if (count($return[$valT['qs_id']]['allowKey']) > 1) {
                                    $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                } else {
                                    $return[$valT['qs_id']]['unallowKey'][$nowArea['area_id']] = array($keyT => $valT);
                                    $return[$valT['qs_id']]['unallowKey'][$tempArea['area_id']] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                    $return[$valT['qs_id']]['allowKey'] = array();
                                    $tempIndustry = $nowArea;
                                }

                                continue;
                            }
                        }
                    }
                }
                #行业
                elseif ($valT['qs_id'] == 6) {
                    $nowIndustry = $this->groupIndustry($valT['qs_val']);
                    $valT['pid'] = $nowIndustry['pid'];
                    $allPid = array();
                    #超过2个父级行业id 所有都变成未匹配的
                    if (count($allPid) >= 2) {
                        $return[$valT['qs_id']]['unallowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                    }
                    if (!$tempIndustry) {
                        $tempIndustry = $nowIndustry;
                        $valT['industry_name'] = $nowIndustry['industry_name'];
                        $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                        $return[$valT['qs_id']]['unallowKey'][] = array();
                        if ($nowIndustry['pid'] == 0) {
                            $allPid[] = $nowIndustry['industry_id'];
                        }
                        continue;
                    } else {
                        #对比一级行业
                        if ($tempIndustry['pid'] == 0) {
                            #同是一级行业 名称相同
                            if ($nowIndustry['pid'] == 0 && $nowIndustry['industry_id'] == $tempIndustry['industry_id']) {
                                continue;
                            }
                            #同是一级行业 取后者
                            elseif ($nowIndustry['pid'] == 0 && $nowIndustry['industry_id'] != $tempIndustry['industry_id']) {
                                if ($nowIndustry['pid'] == 0) {
                                    $allPid[] = $nowIndustry['industry_id'];
                                }
                                if (count($return[$valT['qs_id']]['allowKey']) > 0) {
                                    $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                    $return[$valT['qs_id']]['unallowKey'] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                    $return[$valT['qs_id']]['allowKey'] = array();
                                    continue;
                                } else {
                                    $return[$valT['qs_id']]['unallowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                    continue;
                                }
                            }
                            #传入的是它的子集
                            elseif ($nowIndustry['pid'] != 0 && $nowIndustry['pid'] == $tempIndustry['industry_id']) {
                                $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                $tempIndustry = $nowIndustry;
                                continue;
                            }
                            #传入的不是它的子集
                            elseif ($nowIndustry['pid'] != 0 && $nowIndustry['pid'] != $tempIndustry['industry_id']) {
                                $return[$valT['qs_id']]['unallowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                continue;
                            }
                        } else {
                            #原有的是2级 传入的是它的一级
                            if ($nowIndustry['pid'] == 0 && $tempIndustry['pid'] == $nowIndustry['industry_id']) {
                                $allPid[] = $nowIndustry['industry_id'];
                                $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                continue;
                            }
                            #原有的是2级 传入的不是它的一级 替换
                            elseif ($nowIndustry['pid'] == 0 && $tempIndustry['pid'] != $nowIndustry['industry_id']) {
                                $allPid[] = $nowIndustry['industry_id'];
                                $allPid[] = $nowIndustry['industry_id'];
                                $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                $return[$valT['qs_id']]['unallowKey'] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                $return[$valT['qs_id']]['allowKey'] = array();
                                $tempIndustry = $nowIndustry;
                                continue;
                            }
                            #原有的是2级 传入的也是2级
                            elseif ($nowIndustry['pid'] != 0 && $tempIndustry['industry_id'] != $nowIndustry['industry_id']) {
                                $return[$valT['qs_id']]['allowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                $return[$valT['qs_id']]['unallowKey'][$tempIndustry['industry_id']] = $return[$valT['qs_id']]['allowKey'][$tempIndustry['industry_id']];
                                unset($return[$valT['qs_id']]['allowKey'][$tempIndustry['industry_id']]);
                                $tempIndustry = $nowIndustry;
                                continue;
                            }
                            #传入的是不同一级的2级
                            elseif ($nowIndustry['pid'] != 0 && $tempIndustry['pid'] != $nowIndustry['pid']) {

                                $allPid[] = $nowIndustry['pid'];
                                if (count($return[$valT['qs_id']]['allowKey']) > 1) {
                                    $return[$valT['qs_id']]['unallowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                } else {
                                    $return[$valT['qs_id']]['unallowKey'][$nowIndustry['industry_id']] = array($keyT => $valT);
                                    $return[$valT['qs_id']]['unallowKey'][$tempIndustry['industry_id']] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                                    $return[$valT['qs_id']]['allowKey'] = array();
                                    $tempIndustry = $nowIndustry;
                                }

                                continue;
                            }
                        }
                    }
                } else {
                    if (isset($return[$valT['qs_id']]['allowKey'])) {
                        $return[$valT['qs_id']]['unallowKey'] = array_merge($return[$valT['qs_id']]['unallowKey'], $return[$valT['qs_id']]['allowKey']);
                        $return[$valT['qs_id']]['allowKey'] = array();
                        $return[$valT['qs_id']]['allowKey'][$valT['qs_val']] = array($keyT => $valT);
                    } else {
                        $return[$valT['qs_id']]['allowKey'][$valT['qs_val']] = array($keyT => $valT);
                    }
                }
            }
        }
        return $return;
    }

    /**
     * 解析地区关键词
     * @author 施磊
     */
    public function groupArea($area_id) {
        $return = array();
        $allArea = common::getAreaList();
        if ($allArea) {
            foreach ($allArea as $key => $val) {
                if ($key == $area_id) {
                    $return = array('pid' => 0, 'area_name' => $val['first_name'], 'area_id' => $area_id);
                    break;
                } else {
                    foreach ($val['secord'] as $keyT => $valT) {
                        if ($keyT && $keyT == $area_id) {
                            $return = array('pid' => $key, 'area_name' => $valT, 'area_id' => $area_id);
                            break;
                        }
                    }
                }
            }
        }
        return $return;
    }

    /**
     * 解析行业 关键词
     * @author 施磊
     */
    public function groupIndustry($industry_id) {
        $return = array();
        $allIndustry = common::getIndustryList();
        if ($allIndustry) {
            foreach ($allIndustry as $key => $val) {
                if ($industry_id == $key) {
                    $return = array('pid' => 0, 'industry_name' => $val['first_name'], 'industry_id' => $industry_id);
                    break;
                } else {
                    foreach ($val['secord'] as $keyT => $valT) {
                        if ($keyT && $industry_id == $keyT) {
                            $return = array('pid' => $key, 'industry_name' => $valT, 'industry_id' => $industry_id);
                            break;
                        }
                    }
                }
            }
        }
        return $return;
    }

    /**
     * 分解判断是否在标签内
     * @author 施磊
     */
    public function checkProjectTag($list, $keywords, $amountSector = array()) {
        $return = array();
        $amountType = array(1 => '5万以下', 2 => '5-10万', 3 => '10-20万', 4 => '20-50万', 5 => '50万以上');
        //不匹配的关键词
        $unShowTag = array('5万', '20万', '50万', '10万', '以上', '5', '10', '20', '50', '万');
        $project_amount_type = arr::get($list, 'project_amount_type', 0);
        if (isset($list['project_tags']) && $list['project_tags']) {
            $project_tag = $list['project_tags'];
            $project_tagArr = explode(',', $list['project_tags']);
            if ($keywords) {
                foreach ($keywords as $key => $val) {
                    if (is_int(strpos($project_tag, (string) $key)) && $key && !in_array($key, $unShowTag)) {
                        foreach ($project_tagArr as $valT) {
                            if (is_int(strpos($valT, (string) $key))) {
                                //可以做累加排序
                                if (!in_array($valT, $return)) {
                                    $return[] = $valT;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($amountSector && $project_amount_type && in_array($project_amount_type, $amountSector)) {
            if (!in_array(arr::get($amountType, $project_amount_type, 0), $return) && arr::get($amountType, $project_amount_type, 0)) {
                $return[] = arr::get($amountType, $project_amount_type, 0);
            }
        }
        return $return;
    }

    /**
     * 根据关键字去词库表中查找问题类型
     * @param unknown_type $word
     * @return NULL
     */
    public function getWordType($word) {
        $type = array();
        $monery_arr = array();

        if (is_array($word) && !empty($word)) {
            foreach ($word as $key => $value) {
                if (is_numeric($key)) {
                    $monery_arr[] = $key;
                }
                $typeObject = ORM::factory('Directory')->where('keyword', '=', $key)->find_all();
                foreach ($typeObject as $value) {
                    $type[] = $value->as_array();
                }
            }
        }

        if (is_array($monery_arr)) {
            $type['money'] = $monery_arr;
        }

        return $type;
    }

    /**
     * 将匹配词库的出来的结果集重构成为按问题分类的数据
     * @param array $matchWord
     */
    public function reConstruct($matchWord) {
        $constructResult = array();
        if (isset($matchWord['money'])) {
            $money = $matchWord['money'];
            unset($matchWord['money']);
        }
        if (is_array($matchWord)) {
            foreach ($matchWord as $subMatchWord) {
                //处理投资金额
                if ($subMatchWord['question_type'] == 7) {

                    $constructResult[$subMatchWord['question_type']] = isset($subMatchWord['question_id']) ?
                            $subMatchWord['question_id'] : $subMatchWord['keyword'];
                } else {
                    //TODO 原先支持多数组。根据怀栋目前的接口支持情况，暂时关闭，不支持数据。
                    /*
                      $constructResult[$subMatchWord['question_type']][]=array('id'=>$subMatchWord['id'],
                      'keyword'=>$subMatchWord['keyword'],
                      'question_id'=>$subMatchWord['question_id'],);
                     */
                    $constructResult[$subMatchWord['question_type']] = isset($subMatchWord['question_id']) ?
                            $subMatchWord['question_id'] : $subMatchWord['keyword'];
                }
            }
        }

        if (isset($money)) {
            foreach ($money as $value) {
                if (isset($constructResult[7])) {
                    $constructResult[7] = $value . $constructResult[7];
                } else {
                    $constructResult[7] = $value;
                }
            }
        }

        //判断第二个问题。地区。  如果没有传入地区，则使用全国（88）作为默认值，传递给临时接口使用
        if (!isset($constructResult['2'])) {
            $constructResult['2'] = 88;
        }

        return $constructResult;
    }

    /**
     * 已登录用户保存或更新选择问题答案
     * @param int $user_id
     * @param int $question_id
     * @param int $answer_id
     * @return boolean
     */
    public function setLoggedSearchConfig($user_id, $question_id, $answer_id) {
        $qst = ORM::factory("Searchconfig")
                ->where("user_id", "=", $user_id)
                ->where("question_id", "=", $question_id)
                ->find();
        //目前只有十个问题，序号已知
        if (!is_numeric($question_id) or $question_id < 1 or $question_id > 10) {
            return false;
        }
        if (!$qst->id) {
            $qst->user_id = $user_id;
            $qst->question_id = $question_id;
            $qst->add_time = time();
        }
        //更新操作
        else {
            $qst->update_time = time();
        }
        $qst->question_answer_id = $answer_id;
        try {
            $qst->save();
        } catch (Kohana_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 已登录用户保存或更新选择问题答案
     * @param int $user_id
     */
    public function getLoggedSearchConfigByID($user_id) {
        $qst = ORM::factory("Searchconfig")
                ->where("user_id", "=", $user_id)
                ->find_all();
        return $qst;
    }

    /**
     * 已登录用户筛选条件数量
     * @param int $user_id
     */
    public function getLoggedSearchConfigCount($user_id) {
        $qst = ORM::factory("Searchconfig")
                ->where("user_id", "=", $user_id)
                ->count_all();
        return $qst;
    }

    /**
     * 获取已登录用户问题配置
     * tips 以线上规则为准
     * @param int $user_id
     * @return array
     */
    public function getLoggedSearchConfigs($user_id) {
        $config = array();
        $models = ORM::factory("Searchconfig");
        $congigs = $models->where("user_id", "=", $user_id)
                ->find_all();
        if ($congigs->count()) {
            foreach ($congigs as $cfg) {
                $config[$cfg->question_id] = $cfg->question_answer_id;
            }
        }
        return $config;
    }

    /**
     * 修改版，对行业做了特殊处理==》
     * 获取已登录用户问题配置
     * tips 以线上规则为准
     * @param int $user_id
     * @return array
     */
    public function getLoggedSearchConfig($user_id) {
        $config = array();
        $models = ORM::factory("Searchconfig");
        $congigs = $models->where("user_id", "=", $user_id)
                ->find_all();
        if ($congigs->count()) {
            foreach ($congigs as $cfg) {
                $config[$cfg->question_id] = $cfg->question_answer_id;
                if ($cfg->question_id == 6) {
                    $industry_id = $this->getIndustry($config[$cfg->question_id]);
                    if ($industry_id) {
                        $config[$cfg->question_id] = $industry_id; //二级行业装换成一级行业
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 未登录用户cookie保存配置
     */
    public function setNotLoggedSearchConfig($config, $question_id, $answer_id) {
        if (Cookie::get("guideConfig")) {
            $config = Cookie::get("guideConfig");
            $configs = json_decode(base64_decode($config), true);
            $configs[$question_id] = $answer_id;
            $configs = base64_encode(json_encode($configs));
            Cookie::set("guideConfig", $configs, 86400);
        } else {
            $config = base64_encode(json_encode(array($question_id => $answer_id)));
            Cookie::set("guideConfig", $config, 86400);
        }
    }

    /**
     * 未登录用户问题配置
     * @author 龚湧
     * @return array
     */
    public function getNotLoggedSearchConfigs() {
        $config = array();
        $configs = Cookie::get("guideConfig");
        if ($configs) {
            $config = json_decode(base64_decode($configs), true);
        }
        return $config;
    }

    /**
     * 对行业做了特殊处理===》
     * 未登录用户问题配置
     * @author 龚湧
     * @return array
     */
    public function getNotLoggedSearchConfig() {
        $config = array();
        $configs = Cookie::get("guideConfig");
        if ($configs) {
            $config = json_decode(base64_decode($configs), true);
            foreach ($config as $qid => $cfg) {
                if ($qid == 6) {
                    $industry_id = $this->getIndustry($cfg);
                    if ($industry_id) {
                        $config[$qid] = $industry_id; //二级行业装换成一级行业
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 未登录用户cookie删除指定值配置
     */
    public function deleteNotLoggedSearchConfig($config, $question_id) {
        if (Cookie::get("guideConfig")) {
            $config = explode("~", $config);
            $config = $config[1];
            $configs = json_decode(base64_decode($config), true);
            if (isset($configs[$question_id])) {
                unset($configs[$question_id]);
                $configs = base64_encode(json_encode($configs));
                Cookie::set("guideConfig", $configs, 86400);
            }
        }
    }

    /**
     * 取得一句话所收索到的项目列表
     * type=1表示输入的,type=2表示其它
     * @author 曹怀栋
     * @return array
     */
    public function getProjectSearchList($type, $array = array()) {
        $data = array();
        $ser = new Service_Public();
        if ($type == 1) {//输入框输入的情况下
            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_numeric($v)) {//传值是数字
                        $data[$k] = intval($v);
                    } else {//传值是中文
                        if ($k == 7) {
                            $data[$k] = $ser->getMoneyRange($v);
                        }
                    }
                }
            }
        } else {//其它的情况下
        }
        //输出写入的这一句话
        //输入的地区
        if (isset($data[2]) && is_numeric($data[2])) {
            $arr[2] = $ser->getAreaName($data[2]);
        } else {
            $arr[2] = "";
        }
        //输入的金钱
        if (isset($array[7])) {
            $arr[7] = $array[7];
        } else {
            $arr[7] = "";
        }
        //输入的行业
        if (isset($array[6])) {
            $arr[6] = $array[6];
        } else {
            $arr[6] = "";
        }

        $arr_list['result'] = $data;
        $arr_list['arr'] = $arr;
        return $arr_list;
    }

    /**
     * 匹配出最适合你的项目列表
     * @author 曹怀栋
     * @return array
     */
    public function getMatchingProjectList($user_id = null) {
        if ($user_id == null) {
            $data = $this->getNotLoggedSearchConfigs();
        } else {
            $data = $this->getLoggedSearchConfigs($user_id);
        }
        $arr_list['result'] = $data;
        $arr_list['arr'] = $this->getArrName($data);
        return $arr_list;
    }

    public function getArrName($data) {
        $arr = array();
        //问题1属性
        if (isset($data[1]) && is_numeric($data[1])) {
            $str1 = guide::attr1();
            $arr[1]['name'] = $str1[$data[1]];
            $arr[1]['id'] = $data[1];
            unset($str1);
        }
        //问题2属性(地区)
        if (isset($data[2]) && is_numeric($data[2])) {
            $ser = new Service_Public();
            $arr[2]['name'] = $ser->getAreaName($data[2]);
            $arr[2]['id'] = $data[2];
        }
        //问题3属性
        if (isset($data[3]) && is_numeric($data[3])) {
            $str3 = guide::attr3();
            $arr[3]['name'] = $str3[$data[3]];
            $arr[3]['id'] = $data[3];
            unset($str3);
        }
        //问题4属性
        if (isset($data[4]) && is_numeric($data[4])) {
            $str4 = guide::attr4();
            $arr[4]['name'] = $str4[$data[4]];
            $arr[4]['id'] = $data[4];
            unset($str4);
        }
        //问题5属性
        if (isset($data[5]) && is_numeric($data[5])) {
            $str5 = guide::attr5();
            $arr[5]['name'] = $str5[$data[5]];
            $arr[5]['id'] = $data[5];
            unset($str5);
        }
        //问题6属性
        if (isset($data[6]) && is_numeric($data[6])) {
            $str6 = guide::attr6();
            $str = ORM::factory("industry")->where('industry_id', '=', $data[6])->find();
            $arr[6]['name'] = $str->industry_name;
            $arr[6]['id'] = $str->industry_id;
            unset($str6);
        }
        //问题7属性
        if (isset($data[7]) && is_numeric($data[7])) {
            $str7 = guide::attr7();
            $arr[7]['name'] = $str7[$data[7]];
            $arr[7]['id'] = $data[7];
            unset($str7);
        }
        //问题8属性
        if (isset($data[8]) && is_numeric($data[8])) {
            $str8 = guide::attr8();
            $arr[8]['name'] = $str8[$data[8]];
            $arr[8]['id'] = $data[8];
            unset($str8);
        }
        //问题9属性
        if (isset($data[9]) && is_numeric($data[9])) {
            $str9 = guide::attr9();
            $arr[9]['name'] = $str9[$data[9]];
            $arr[9]['id'] = $data[9];
            unset($str9);
        }
        //问题10属性
        if (isset($data[10]) && is_numeric($data[10])) {
            $str10 = guide::attr10();
            $arr[10]['name'] = $str10[$data[10]];
            $arr[10]['id'] = $data[10];
            unset($str10);
        }
        return $arr;
    }

    /**
     *  精准匹配 查询
     *  @author 施磊
     *  @param array $data 答题的答案
     *  @param int $limit 要取得的项目数  0为 全部
     *  @param bool $cache 是否要缓存结果
     */
    public function getQueryConditionList($data, $page = 1, $limit = 8, $cache = TRUE) {
        $serviceProject = new Service_Platform_Project();
        $res = searchservice::getInstance()->searchCond($data, $limit);
        $ids = arr::get($res, 'allId', array());
        $price = arr::get($res, 'list', array());
        $res = $serviceProject->getProjectByProjectIds($ids);
        $return = $this->checkSearchList($res, $price);
        return $return;
        $return = array();
        $cacheArray = array(0, 1, 2, 3);
        $limit = intval($limit);
        $limit = $limit ? "limit 0,{$limit}" : ' ';
        $cache_key = 'seach' . json_encode($data) . $cacheArray[$page - 1] . $limit;
        $cache_time = '86400';
        if ($cache) {
            $memcache = Cache::instance('memcache');
            try {
                //$return = $memcache->get($cache_key);
            } catch (Cache_Exception $e) {
                $return = array();
            }
        }
        if (!$return) {
            for ($i = 1; $i <= 10; $i++) {
                $sqlCase = 'sqlCase' . $i;
                $as = 'as' . $i;
                $$as = intval(arr::get($data, $i, 0));
                $$sqlCase = 0;
            }
            //先实现逻辑 再优化
            #您想做哪种生意
            if ($as1 != 0) {
                $sqlCase1 = " CASE WHEN (((SELECT COUNT(project_model_id) FROM czzs_project_model as g WHERE g.project_id = a.project_id AND g.type_id = {$as1}) = 0) > 0) THEN {$this->_question_perce[1]} WHEN ((SELECT COUNT(project_model_id) FROM czzs_project_model as h WHERE h.project_id = a.project_id AND h.type_id = {$as1}) = 0) THEN 0 ELSE {$this->_question_perce[1]} END";
            }
            #您想在哪里做
            if ($as2 != 0 && $as2 != 88) {
                // $sqlCase2 = " CASE WHEN A2 != {$as2} AND area_pid != {$as2} THEN {$this->_question_perce[2]} WHEN A2 = {$as2} OR area_pid = {$as2} OR area_pid = 88 THEN 0  END";
                $sqlCase2 = " CASE WHEN (SELECT COUNT(project_area_id) FROM czzs_project_area as c WHERE c.project_id = a.project_id AND (c.area_id = {$as2} or c.pro_id = {$as2})) = 0 THEN {$this->_question_perce[2]} WHEN (SELECT COUNT(project_area_id) FROM czzs_project_area as d WHERE d.project_id = a.project_id AND (d.area_id = {$as2} or d.pro_id = {$as2})) > 0 THEN 0 ELSE {$this->_question_perce[2]} END";
            }
            #您有哪些人脉关系
            if ($as5 != 0 && $as5 != 6) {
                $sqlCase3 = " CASE WHEN ((SELECT COUNT(pc_id) FROM czzs_project_connection as e WHERE e.project_id = a.project_id AND e.connection_id = {$as5}) = 0) THEN {$this->_question_perce[3]} WHEN ((SELECT COUNT(pc_id) FROM czzs_project_connection as f WHERE f.project_id = a.project_id AND f.connection_id = {$as5}) > 0) THEN 0 ELSE {$this->_question_perce[3]}  END";
            }
            #您想做什么行业
            if ($as6 != 0) {
                $sqlCase6 = " CASE WHEN A4 != {$as6} AND industry_pid != {$as6} THEN {$this->_question_perce[4]} WHEN A4 = {$as6} or industry_pid = {$as6} THEN 0 ELSE {$this->_question_perce[4]}  END";
            }
            #您准备投资多少钱
            if ($as7 != 0) {
                $sqlCase5 = " CASE WHEN A5 != {$as7} THEN {$this->_question_perce[5]} WHEN A5 = {$as7} THEN 0 ELSE {$this->_question_perce[5]} END";
            }
            #您对生意的预期
            if ($as10 != 0) {
                $sqlCase6 = " CASE WHEN A6 != {$as10} THEN {$this->_question_perce[6]} WHEN A6 = {$as10} THEN 0 ELSE {$this->_question_perce[6]}  END";
            }

            $sql = "SELECT distinct(a.project_id),b.outside_id,b.project_logo,b.project_source,b.project_brand_name,b.project_pv_count as countSta,(100-({$sqlCase1})-({$sqlCase2})-({$sqlCase3})-({$sqlCase4})-({$sqlCase5})-({$sqlCase6})) as perce FROM  pro_answer a LEFT JOIN czzs_project b ON a.project_id=b.project_id  ORDER BY perce DESC ";
            $return = DB::query(Database::SELECT, $sql . $limit)->execute()->as_array();
            echo $sql . $limit;
            $return = $this->checkSearchList($return);
            //一次性把4页缓存全部写入 手动分页
            $arrChunk = array_chunk($return, 8);
            foreach ($cacheArray as $key => $val) {
                if (Arr::get($arrChunk, $key, 0)) {
                    $cache_key = 'seach' . json_encode($data) . $val . $limit;
                    if ($cache) {
                        $memcache->set($cache_key, $arrChunk[$key], $cache_time);
                    }
                }
            }
            $return = $arrChunk[$page - 1];
        }
        return $return;
    }

    /**
     * 针对精准匹配的数据重组
     * @author 施磊
     */
    public function checkSearchList($allProject = array(), $price = array()) {
        if ($allProject) {
            $mod = new Service_Platform_Browsing();
            foreach ($allProject as $key => $val) {
                $allProject[$val['project_id']] = $val;
                $allProject[$val['project_id']]['link'] = urlbuilder::project($val['project_id']);
                if ($val['project_source'] != 1) {
                    $allProject[$val['project_id']]['project_logo'] = project::conversionProjectImg($val['project_source'], 'logo', $val);
                } else {
                    $allProject[$val['project_id']]['project_logo'] = URL::imgurl($val['project_logo']);
                }
                $allProject[$val['project_id']]['perce'] = arr::get($price, $val['project_id'], 0);
            }
            $return = array();
            if ($price) {
                foreach ($price as $key => $val) {
                    if (arr::get($allProject, $key, 0)) {
                        $return[] = $allProject[$key];
                    }
                }
            }
            return $return;
        }
        return $allProject;
    }

    /**
     * 项目查询条件处理
     * $type=1一句话所收索到的,$type=2精准收索到的,$type=3精准收索到的6条数据的条件
     * @author 曹怀栋
     * @return array
     */
    public function getQueryCondition($arr_list) {
        $arr_list = Arr::map(array("HTML::chars"), $arr_list);
        $data = isset($arr_list['result']) ? $arr_list['result'] : "";
        $arr = isset($arr_list['arr']) ? $arr_list['arr'] : "";
        unset($arr_list);
        $star_level = 0;
        $array_projectid = array();
        //查询条件
        if (count($data) > 0) {
            if (isset($data['1'])) {//生意类型(招商形式)
                $where = "WHERE pa.type_id=" . $data['1'] . " and pa.status=2";
                //查询条件处理

                $result_model = DB::select('project_id')->distinct(TRUE)->from('project_model')->where('type_id', '=', $data['1'])->where('status', '=', 2)->execute()->as_array();
                if (count($result_model) > 0) {
                    foreach ($result_model as $k => $v) {
                        if (!empty($v['project_id'])) {
                            $array_projectid[0][$k] = $v['project_id'];
                        }
                    }
                } else {
                    $array_projectid[0] = array();
                }
                //星级
                $star_level = $star_level + 1;
            }
            if (isset($data['2'])) {//地区(88表示全国，所有的都可以查到)
                if ($data['2'] != 88) {
                    $result_area = DB::select('project_id')->distinct(TRUE)->from('project_area')->where('area_id', '=', $data['2'])->where('status', '=', 2)->execute()->as_array();
                } else {
                    $result_area = DB::select('project_id')->distinct(TRUE)->from('project_area')->where('status', '=', 2)->execute()->as_array();
                }

                //查询条件处理
                //$sql = "FROM czzs_project_area pa " . $where;

                if (count($result_area) > 0) {
                    foreach ($result_area as $k => $v) {
                        if (!empty($v['project_id'])) {
                            $array_projectid[1][$k] = $v['project_id'];
                        }
                    }
                } else {
                    $array_projectid[1] = array();
                }
                //星级
                $star_level = $star_level + 1;
            }
            if (isset($data['5'])) {//人脉关系
                if ($data['5'] != 6) {
                    $result_connection = DB::select('project_id')->distinct(TRUE)->from('project_connection')->where('connection_id', '=', $data['5'])->where('status', '=', 2)->execute()->as_array();
                } else {
                    $result_connection = DB::select('project_id')->distinct(TRUE)->from('project_connection')->where('status', '=', 2)->execute()->as_array();
                }

                if (count($result_connection) > 0) {
                    foreach ($result_connection as $k => $v) {
                        if (!empty($v['project_id'])) {
                            $array_projectid[2][$k] = $v['project_id'];
                        }
                    }
                } else {
                    $array_projectid[2] = array();
                }
                //星级
                $star_level = $star_level + 1;
            }
            if (isset($data['6'])) {//行业
                $Industry = common::primaryIndustry($data['6']);
                $ids = array();
                foreach ($Industry as $val) {
                    $ids[] = $val->industry_id;
                }
                if ($ids) {
                    $project_industry = DB::select('project_id')->distinct(TRUE)->from('project_industry')->where('status', '=', 2)->and_where_open()->where('industry_id', '=', $data['6'])->or_where('industry_id', 'in', $ids)->and_where_close()->execute()->as_array();
                } else {
                    $project_industry = DB::select('project_id')->distinct(TRUE)->from('project_industry')->where('status', '=', 2)->where('industry_id', '=', $data['6'])->execute()->as_array();
                }

                if (count($project_industry) > 0) {
                    foreach ($project_industry as $k => $v) {
                        if (!empty($v['project_id'])) {
                            $array_projectid[3][$k] = $v['project_id'];
                        }
                    }
                } else {
                    $array_projectid[3] = array();
                }
                //星级
                $star_level = $star_level + 1;
            }
            //project表中的3个查询条件
            if (isset($data['7']) || isset($data['8']) || isset($data['10'])) {
                $where = array();
                if (isset($data['8']))
                    $where['rate_return'] = $data['8'];
                if (isset($data['10']))
                    $where['risk'] = $data['10'];
                if (isset($data['7']))
                    $where['project_amount_type'] = $data['7'];
                if ($where) {
                    //查询条件处理
                    $array_projectid[4] = $this->getProjectQueryConditionsS($where);
                } else {
                    $array_projectid[4] = array();
                }

                //星级
                $star_level = $star_level + 3;
            } else {
                if ((!isset($data['1']) && !isset($data['2']) && !isset($data['5']) && !isset($data['6']) && !isset($data['7']) && !isset($data['8']) && !isset($data['10']))) {
                    $arr = 'noCond';
                }
            }
        } else {
            $arr = 'noCond';
        }
        //echo "<pre>";print_r($array_projectid);
        //通过搜索条件来取的相同的项目id
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        if($debug) var_dump(1,arr::get($array_projectid, $_GET['debug']-1, array()));
        if (count($array_projectid) > 0) {
            $arr_list['result'] = $this->getArrayIntersectProject($array_projectid);
        } else {
            $arr_list['result'] = array();
        }
        unset($array_projectid);
        $arr_list['arr'] = $arr;
        $arr_list['star_level'] = $star_level;
        return $arr_list;
    }

    /**
     * 通过搜索条件来取的相同的项目id
     * @author 曹怀栋
     */
    public function getArrayIntersectProject($arr) {
        $tmp_arr = array();
        if (count($arr) > 0) {
            $tmp_arr = array_shift($arr);
            foreach ($arr as $key => $val) {
                $tmp_arr = array_intersect($tmp_arr, $val);
            }
        }
        return $tmp_arr;
    }

    /**
     * project表的4个查询条件的sql 改
     * @author 施磊
     * @return array
     */
    public function getProjectQueryConditionsS($where) {
        $sql = DB::select('project_id')->distinct(TRUE)->from('project')->where('project_status', '=', 2);
        if ($where) {
            foreach ($where as $key => $val) {
                $sql->where($key, '=', $val);
            }
        }
        $result_project = $sql->execute()->as_array();
        $pt = array();
        if (count($result_project) > 0) {
            foreach ($result_project as $k => $v) {
                if (!empty($v['project_id'])) {
                    $pt[$k] = $v['project_id'];
                }
            }
        }
        return $pt;
    }

    /**
     * 飞哥要的
     * @author stone shi
     */
    public function getSeachFeige($project_source = 1) {
        $sql = DB::select()->from('project');
        if ($project_source == 2 || $project_source == 4) {
            $sql->and_where_open()->where('project_source', '=', intval($project_source))->or_where('project_source', '=', intval($project_source) + 1)->and_where_close();
        } else {
            $sql->where('project_source', '=', intval($project_source));
        }
        $sql->where('project_status', '=', 2);
        $result_count = $sql->execute()->count();
        $page = Pagination::factory(array(
                    'total_items' => $result_count,
                    'items_per_page' => 10,
                    'view' => 'pagination/Simple',
                    'current_page' => array('source' => 'search', 'key' => 'page')
        ));
        $sql = DB::select()->from('project');
        if ($project_source == 2 || $project_source == 4) {
            $sql->and_where_open()->where('project_source', '=', intval($project_source))->or_where('project_source', '=', intval($project_source) + 1)->and_where_close();
        } else {
            $sql->where('project_source', '=', intval($project_source));
        }
        $res = $sql->where('project_status', '=', 2)->limit($page->items_per_page)->offset($page->offset)->execute();
        $res = $res->as_array();
        return array(
            'page' => $page,
            'list' => $this->pushProjectInfo($res),
            'list_make_up' => array(),
            'total_count' => $result_count,
        );
    }

    /**
     * 项目查询sql语句
     * @author 曹怀栋
     * @return array
     */
    public function getProjectSqlSearch($arr_list, $total = 0, $user_id = 0, $sort = 0) {
        $result = $arr_list['result'];
        $arr = isset($arr_list['arr']) ? $arr_list['arr'] : array();
        if ($arr_list && is_array($arr_list)) {
            $arr_list = Arr::map(array("HTML::chars"), $arr_list);
        }
        $arr_list['star_level'] = isset($arr_list['star_level']) ? $arr_list['star_level'] : 1;
        $sortArr = array(1 => 'project_approving_count', 2 => 'project_approving_count', 3 => 'project_addtime');
        //取得项目总数
        $result_count = $total ? $total : count($result);
        $res1 = array();
        if ($result_count > 0 && $result) {///当查询到数据的情况
            if ($result_count > 500) {
                $result_count_1 = 500;
            } else {
                $result_count_1 = $result_count;
            }
            $result_array = $total ? $result : array_chunk($result, 10);
            //分页条件
            $page = Pagination::factory(array(
                        'total_items' => $result_count_1,
                        'items_per_page' => 10,
                        'view' => 'pagination/Simple',
                        'current_page' => array('source' => 'search', 'key' => 'page')
            ));
            $sql = DB::select('project_id', 'com_id', 'project_advert', 'outside_id', 'product_features', 'project_amount_type', 'project_source', 'project_approving_count', 'project_pv_count', 'project_brand_name', 'project_logo', 'project_summary', 'project_tags', 'project_vip_set','project_real_order')->from('project');
            if ($total) {
                if ($result_array)
                    $sql->where('project_id', 'in', $result_array);
            } else {
                if ($result_array[$page->current_page - 1])
                    $sql->where('project_id', 'in', $result_array[$page->current_page - 1]);
            }
            $sql->where('project_status', '=', 2);
            /*
              $order = ' ';
              if ($sort) {
              $orderCond = arr::get($sortArr, $sort, 'project_approving_count');
              $sql->order_by($orderCond, 'DESC');
              } */
            $res = $sql->execute()->as_array();
            //较真数据
            $newRes = array();
            if ($res && $result_array) {
                foreach ($result_array as $key => $val) {
                    foreach ($res as $keyT => $valT) {
                        if ($valT['project_id'] == $val) {
                            $newRes[$key] = $valT;
                        }
                    }
                }
            }
            $res = $newRes;
            //$res=$search->BuildExcerpts($res,'mysql','火锅',$opts);
            //Kohana::debug($res);
            //不足十个项目时，补足十个项目
            $res1 = array();
        } else {//当没有查询到数据的情况
            $result_key = 'seacrchPro_noPro';
            $memcache = Cache::instance ( 'memcache' );
            $result = $memcache->get($result_key);
            $harPro = $result;
            $res = array();
            if(!$harPro) {
                $guide_service = new Service_Platform_ProjectGuide();
                $limit = 50;
                $harPro = $guide_service->getHardProByConfig($limit);
                $memcache->set($result_key, $harPro, 86400);
            }
                $page = Pagination::factory(array(
                            'total_items' => 50,
                            'items_per_page' => 10,
                            'view' => 'pagination/Simple',
                            'current_page' => array('source' => 'search', 'key' => 'page')
                ));

                $res = array();
                $limit = " limit {$page->items_per_page} offset {$page->offset}";
                $sql = DB::select('project_id', 'com_id', 'outside_id', 'project_advert', 'product_features', 'project_amount_type', 'project_source', 'project_approving_count', 'project_pv_count', 'project_brand_name', 'project_logo', 'project_summary', 'project_tags', 'project_vip_set')->from('project');
                $sql->where('project_status', '=', 2);
                $sql->where('project_id', 'in', $harPro);
                $sql->limit($page->items_per_page)->offset($page->offset);
                $sql->order_by('project_pv_count', 'desc');
                $res1 = $sql->execute()->as_array();
                
            }
        


        return array(
            'star_level' => $arr_list['star_level'],
            'inaword' => $arr,
            'page' => $page,
            'list' => $this->pushProjectInfo($res, $user_id),
            'list_make_up' => $this->pushProjectInfo($res1, $user_id),
            'total_count' => $result_count,
        );
    }

    /**
     * pushProjectInfo
     * @author 施磊
     *
     */
    public function pushProjectInfo($project_list, $user_id = 0) {
        $service = new Service_User_Company_Project();
        $servicePlatform = new Service_Platform_Search();
        $servicePlatformProject = new Service_Platform_Project();
        $invest = new Service_Platform_Invest();       
        if ($project_list) {
            foreach ($project_list as $key => $val) {
                if (($val['project_source'] == 5 || $val['project_source'] == 4) && $val['project_logo']) { 
                    $project_list[$key]['project_logo'] = str_replace("poster/html/ps_{$val['outside_id']}/project_logo/", "poster/html/ps_{$val['outside_id']}/project_logo/150_120/", $val['project_logo']);

                } else {
                    $project_list[$key]['project_logo'] = $val['project_logo'];
                }
                #判断有没有小图
                $xuanchuanimage = $servicePlatform->getProjectXuanChuanImage($val['project_id'], intval(5));
                if ($xuanchuanimage) {
                    $project_list[$key]['project_logo'] = $xuanchuanimage;
                }
                $project_list[$key]['projectcomodel'] = $service->getProjectCoModel($val['project_id']);
                $project_list[$key]['postCart'] = $user_id ? $servicePlatform->getCardZixun($user_id, $val['project_id']) : 0;
                $project_list[$key]['viewProject'] = $user_id ? $servicePlatformProject->getProjectWatchCount($user_id, $val['project_id']) : 0;
                $project_list[$key]['industry'] = $service->getProjectindustry($val['project_id']);
                $project_list[$key]['industryArr'] = $service->getProjectindustry2($val['project_id']);
                $com_id = $servicePlatformProject->getUseridByProjectID($val['project_id']);
                $project_list[$key]['com_user_id'] = $com_id ? $com_id : 0;
                $project_list[$key]['isApproving'] = $servicePlatformProject->isApproving($user_id, $val['project_id']);
                $project_list[$key]['project_approving_count'] = $servicePlatformProject->getApprovingCount($val['project_id']);
                $project_list[$key]['issetInvestment'] =$servicePlatformProject->getProjectInvest($val['project_id']);
                $project_list[$key]['projectImg'] = $servicePlatformProject->getTwoProjectImgs($val['project_id'], $val['outside_id'], $val['project_source']);                
                //$project_list[$key]['projectInvester'] = $invest->getTodayLastInvest($val['project_id']);            	
            }
        }
        return $project_list;
    }

    /**
     * 获得PV总量前6名的项目
     * @author 施磊
     */
    public function getRecommProject() {
        $model = ORM::factory('Projectstatistics');
        $serviceProject = new Service_Platform_Project();
        $res1 = DB::select(array(DB::expr('COUNT("statistics_id")'), 'num'), 'project.project_id')->from('project_statistics')->join('project')->on('project.project_id', '=', 'project_statistics.project_id')->where('project_status', '=', 2)->group_by('project_statistics.project_id')->order_by('num', 'DESC')->limit(6)->offset(0)->execute()->as_array();
        $return = array();
        $ids = array();
        if ($res1) {
            foreach ($res1 as $val) {
                $ids[] = $val['project_id'];
            }
            $return = $serviceProject->getProjectByProjectIds($ids);
            if ($return) {
                foreach ($return as $keyT => $valT) {
                    $return[$keyT]['link'] = URL::website("platform/project/index?projectid={$valT['project_id']}");
                    $return[$keyT]['project_summary'] = 'test';
                    if ($valT['project_source'] != 1) {
                        $return[$keyT]['project_logo'] = project::conversionProjectImg($valT['project_source'], 'logo', $valT);
                    } else {
                        $return[$keyT]['project_logo'] = URL::imgurl($valT['project_logo']);
                    }
                }
            }
        }

        return $return;
    }

    /**
     * 根据搜索条件获取一句话
     * @author 龚湧
     * @param array $config
     * @return array
     */
    public function getSentence(array $config) {
        //规则匹配，id为排序
        $list = array(
            /*
              "1" => array(
              'qid'=>"3", //问题id
              'pre'=>"是", //问题前缀
              "next"=>"," //问题后缀
              ),
             */
            "2" => array(
                'qid' => "5",
                "pre" => "",
                "next" => "，"
            ),
            "3" => array(
                'qid' => "sp",
                "pre" => "想",
                "next" => ""
            ),
            /*
              "4" => array(
              'qid'=>"4",
              "pre"=>"",
              "next"=>" "
              ),
             */
            /*
              "5" => array(
              'qid'=>"9",
              "pre"=>"",
              "next"=>" "
              ),
             */
            "6" => array(
                'qid' => "2",
                "pre" => "在",
                "next" => "，"
            ),
            "7" => array(
                'qid' => "7",
                "pre" => "投资",
                "next" => ""
            ),
            "8" => array(
                'qid' => "sp",
                "pre" => "做",
                "next" => ""
            ),
            "9" => array(
                'qid' => "10",
                "pre" => "",
                "next" => "的，"
            ),
            /*
              "10" => array(
              'qid'=>"8",
              "pre"=>"年收益率",
              "next"=>"的 "
              ),
             */
            "11" => array(
                'qid' => "6",
                "pre" => "",
                "next" => "类 "
            ),
            "12" => array(
                'qid' => "1",
                "pre" => "",
                "next" => "项目。"
            ),
        );
        $sentence = array();
        foreach ($list as $key => $l) {
            //第一句我
            if (!empty($config)) {
                $sentence[0] = '<em class="mine">我</em>';
            }
            if (Arr::get($config, $l ['qid'])) {
                //行业做特殊处理
                if ($l['qid'] == 6) {
                    $servie = Service::factory("Public");
                    $em = $servie->getIndustryNameById($config[6]);
                }
                //地区做特殊处理
                elseif ($l['qid'] != 2) {
                    $em = call_user_func(array(
                        "guide",
                        "attr" . $l ["qid"]
                    ));
                    //问题5 人脉关系做特殊处理
                    if ($l['qid'] == 5 and $config [$l ["qid"]] == 6) {
                        $em = "是草根";
                    } else {
                        $em = $em [$config [$l ["qid"]]];
                    }
                } else {
                    $servie = Service::factory("Public");
                    $em = $servie->getAreaName($config[2]);
                }
                $sentence [$key] = "<span>" . $l ["pre"] . "<em>" . $em . "</em>" . $l ['next'] . "</span>";
            } elseif ($l['qid'] == "sp" && !empty($config)) {
                $sentence [$key] = $l["pre"];
            } else {
                $sentence [$key] = "<span></span>";
            }
        }
        return $sentence;
    }

    /**
     * 获得所有条件名
     * @author 施磊
     */
    public function getConditionName(array $config) {
        $return = array();
        if ($config) {
            foreach ($config as $key => $val) {
                if ($key == 6) {
                    $servie = Service::factory("Public");
                    $em = $servie->getIndustryNameById($val);
                }
                //地区做特殊处理
                elseif ($key != 2) {
                    $em = call_user_func(array(
                        "guide",
                        "attr" . $key
                    ));
                    //问题5 人脉关系做特殊处理
                    if ($key == 5 and $val == 6) {
                        $em = "草根";
                    } else {
                        $em = $em [$val];
                    }
                } else {
                    $servie = Service::factory("Public");
                    $em = $servie->getAreaName($config[2]);
                }
                $return[] = $em;
            }
        }
        return $return;
    }

    /**
     * 获取输入框联动提示数据
     * @author 周进
     * @param string $queryString
     * @return json
     */
    public function getSearchComplete($queryString) {
        $data = array('isError' => true, 'data' => '');
        if ($queryString != '' && strlen($queryString) > 0) {
            $models = ORM::factory("Tag");
            $result = $models->where('tag_name', 'like', $queryString . '%')->where('tag_status', '=', '0')->find_all();
            if (count($result) > 0) {
                foreach ($result as $k => $v) {
                    $source[$k]['name'] = $v->tag_name;
                }
                $data = array('isError' => false, 'data' => $source);
            }
        }
        return json_encode($data);
    }

    /**
     * 通过企业id取得企业的user_id
     * @author 曹怀栋
     * @return int
     */
    public function getComUserid($com_id) {
        if (isset($com_id) && is_numeric($com_id)) {
            $resault = ORM::factory("Companyinfo", $com_id);
            return $resault->com_user_id;
        } else {
            return false;
        }
    }

    /**
     * 一句话项目列表中（判断是否已递出名片）
     * @author 钟涛
     * @return bool true表示已经递出名片 false表示没有递出名片
     */
    public function getCardInfo($from_user_id, $to_user_id) {
        if (!isset($to_user_id) || !isset($from_user_id)) {
            return false;
        }
        // 是否已经收到并交换
        $resault2 = ORM::factory("Cardinfo")->where('from_user_id', '=', $to_user_id)->where('to_user_id', '=', $from_user_id)->where('exchange_status', '=', '1')->find();
        // 是否递出并交换
        $resault3 = ORM::factory("Cardinfo")->where('from_user_id', '=', $from_user_id)->where('to_user_id', '=', $to_user_id)->where('exchange_status', '=', '1')->find();
        if ($resault2->card_id != null || $resault3->card_id != null) {
            return true; // 已经递出名片
        } else {
            // 是否已递出
            $resault = ORM::factory("Cardinfo")->where('from_user_id', '=', $from_user_id)->where('to_user_id', '=', $to_user_id)->find();
            $issent = false;
            if ($resault->send_time != null) {
                $send_time = $resault->send_time + 3600 * 7 * 24;
                if (time() - (604800 + $resault->send_time) > 0) { // 7天后又可以递出
                    $issent = false; // 没有递出名片[又可以递出名片]
                } else {
                    $issent = true;
                }
            } else {
                $issent = false; // 没有递出名片
            }
            return $issent;
        }
    }

    /**
     * 精准匹配中删除指定类型
     * $user_id是用户id,$question_id是问题id
     * @author 曹怀栋
     * @return boolean
     */
    public function deleteAccurateMatching($user_id, $question_id) {
        if (!isset($user_id) || !is_numeric($user_id) || !isset($question_id) || !is_numeric($question_id)) {
            return false;
        }
        $searchconfig = ORM::factory('Searchconfig');
        $resault = $searchconfig->where('user_id', '=', $user_id)->where('question_id', '=', $question_id)->find();
        if ($resault->id == "") {//表示没有此信息所以不用删除了,返回成功
            return true;
        }
        $searchconfig->delete($resault->id);
        return true;
    }

    /**
     * 精准匹配中(添加或更新指定类型)
     * $user_id是用户id,$question_id是问题id
     * @author 曹怀栋
     * @return boolean
     */
    public function updateAccurateMatching($user_id, $q_id, $qa_id) {
        if (!isset($user_id) || !is_numeric($user_id) || !isset($q_id) || !is_numeric($q_id) || !isset($qa_id) || !is_numeric($qa_id)) {
            return false;
        }
        $searchconfig = ORM::factory('Searchconfig');
        $resault = $searchconfig->where('user_id', '=', $user_id)->where('question_id', '=', $q_id)->find();
        if ($resault->id == "") {//表示没有此信息
            $searchconfig->user_id = intval($user_id);
            $searchconfig->question_id = intval($q_id);
            $searchconfig->question_answer_id = intval($qa_id);
            $searchconfig->add_time = time();
            $searchconfig->create();
        } else {
            $searchconfig->question_answer_id = intval($qa_id);
            $searchconfig->update_time = time();
            $searchconfig->update();
        }
        return true;
    }

    /**
     * 精准匹配项目列表页面中(一句话选择中指定属性的所有值)
     * $user_id是用户id,$question_id是问题id
     * @author 曹怀栋
     * @return boolean
     */
    public function getSpecifiedAttribute($q_id, $industry_id = null) {
        if (!is_numeric($q_id) || ($q_id > 10)) {
            return false;
        }
        switch ($q_id) {
            case 1:
                $str = guide::attr1();
                break;
            case 2:
                $str = guide::attr2();
                break;
            case 3:
                $str = guide::attr3();
                break;
            case 4:
                $str = guide::attr4();
                break;
            case 5:
                $str = guide::attr5();
                break;
            case 6:
                if ($industry_id != null) {
                    /*
                      $service = new Service_User_Company_Project();
                      $str=$service->getIndustry($industry_id);
                     *
                     */
                    $str = common::primaryIndustry($industry_id);
                } else {
                    $str = guide::attr6();
                }
                break;
            case 7:
                $str = guide::attr7();
                break;
            case 8:
                $str = guide::attr8();
                break;
            case 9:
                $str = guide::attr9();
                break;
            default:
                $str = guide::attr10();
                break;
        }
        return $str;
    }

    /**
     * 精准搜索问题搜索轨迹统计
     * @author 龚湧
     */
    public function biSearchLog(array $config) {
        $model = ORM::factory("Bisearchlog");
        //用户id
        $user_id = Arr::get($config, "user_id") ? Arr::get($config, "user_id") : 0;
        //ip地址
        $ip = Arr::get($config, "ip") ? Arr::get($config, "ip") : 0;
        //问题id
        $q_id = Arr::get($config, "q_id");
        //问题答案id
        $a_id = Arr::get($config, "a_id");
        //添加记录时间
        $add_time = time();
        $model->user_id = $user_id;
        $model->ip = $ip;
        $model->question_id = $q_id;
        $model->question_answer_id = $a_id;
        $model->add_time = $add_time;
        try {
            $model->create();
        } catch (Kohana_Exception $e) {
            
        }
        return false;
    }

    /**
     * 已登录清空配置
     * @author 龚湧
     * @param unknown_type $user_id
     */
    public function clearLoginConfig($user_id) {
        $model = ORM::factory("Searchconfig")
                ->where("user_id", "=", $user_id)
                ->find_all();
        try {
            foreach ($model as $m) {
                $m->delete();
            }
        } catch (Kohana_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 未登录用户清空当前问题配置
     * @author 龚湧
     * @return boolean
     */
    public function clearNotLoginConfig() {
        return Cookie::delete("guideConfig");
    }

    /**
     * 精准搜索问题匹配无结果统计
     * @author 潘宗磊
     */
    public function searchNoList($search) {
        $model = ORM::factory("Bisearchno");
        //用户id
        $user_id = Arr::get($search, "user_id") ? Arr::get($search, "user_id") : 0;
        //ip地址
        $ip = Arr::get($search, "ip") ? Arr::get($search, "ip") : 0;
        //问题id
        $q_id = Arr::get($search, "q_id");
        //问题答案id
        $a_id = Arr::get($search, "a_id");
        //添加记录时间
        $add_time = time();
        $model->user_id = $user_id;
        $model->ip = $ip;
        $model->question_id = $q_id;
        $model->question_answer_id = $a_id;
        $model->add_time = $add_time;
        try {
            $model->create();
        } catch (Kohana_Exception $e) {
            
        }
        return false;
    }

    /**
     * 一级行业和二级行业都返回一级行业，特殊业务需求
     * @author 龚湧
     * @param unknown_type $id
     */
    public function getIndustry($id) {
        $orm = ORM::factory("industry", $id);
        if ($orm->industry_id) {
            if ($orm->parent_id > 0) {
                return $orm->parent_id;
            }
            return $orm->industry_id;
        }
        return false;
    }

    /**
     * 一句话项目列表中（判断是否已递出名片）
     * @author 嵇烨
     * @return bool true表示已经递出名片 false表示没有递出名片
     */
    public function getCardInfos($from_user_id, $project_id) {
        if (!isset($project_id) || !isset($from_user_id)) {
            return false;
        }
        //通过项目的id来获取当前项目用户的id
        $com_id = ORM::factory("Project", intval($project_id))->com_id;
        if ($com_id > 0) {
            $to_user_id = ORM::factory("Usercompany", $com_id)->com_user_id;
            // 是否已经收到并交换
            $resault2 = ORM::factory("Cardinfo")->where('from_user_id', '=', $to_user_id)->where('to_user_id', '=', $from_user_id)->where('exchange_status', '=', '1')->find();
            // 是否递出并交换
            $resault3 = ORM::factory("Cardinfo")->where('from_user_id', '=', $from_user_id)->where('to_user_id', '=', $to_user_id)->where('exchange_status', '=', '1')->find();
            if ($resault2->card_id != null || $resault3->card_id != null) {
                return true; // 已经递出名片
            } else {
                // 是否已递出
                $resault = ORM::factory("Cardinfo")->where('from_user_id', '=', $from_user_id)->where('to_user_id', '=', $to_user_id)->find();
                $issent = false;
                if ($resault->send_time != null) {
                    $send_time = $resault->send_time + 3600 * 7 * 24;
                    if (time() - (604800 + $resault->send_time) > 0) { // 7天后又可以递出
                        $issent = false; // 没有递出名片[又可以递出名片]
                    } else {
                        $issent = true;
                    }
                } else {
                    $issent = false; // 没有递出名片
                }
                return $issent;
            }
        }
    }

    /**
     * 一句话项目列表中（判断当天是否已经咨询）
     * @author 钟涛
     * @return bool true表示已经咨询过  false表示没有咨询
     */
    public function getCardZixun($from_user_id, $project_id) {
        if (!isset($project_id) || !isset($from_user_id)) {
            return false;
        }
        //先判断当天是否已经咨询过了
        $today = strtotime(date('Y-m-d 00:00:00', time()));
        $ser_letter = ORM::factory('UserLetter')
                ->where('user_id', '=', $from_user_id)
                ->where('to_project_id', '=', $project_id)
                ->where('letter_type', '=', 1)
                ->where('add_time', '>=', $today)
                ->find();
        if ($ser_letter->id) {//已经咨询
            return true;
        }
        return false;
    }

    /**
     * 首页您可能喜欢的创业项目/大家都喜欢的创业项目
     * @author 嵇烨
     */
    public function getProjectInfoToIndex($page_type = 1) { 
    	$return_data = array();
    	$service = new Service_Platform_ProjectGuide();
    	if($page_type == 1){
    		#首页->您可能喜欢的创业项目
    		$return_data['YouMayLikeProject'] = $service->GetYouMayLikeProject(6);
    		//首页->#大家都喜欢的创业项目
    		$return_data['EveryOneMayLikeProject'] = $service->GetEveryOneMayLikeProject(4,2);
    	}else{
    		#找项目首页->您可能喜欢的创业项目
    		$return_data['YouMayLikeProject'] = $service->GetYouMayLikeProject(5);
    		//找项目首页->#大家都喜欢的创业项目
    		$return_data['EveryOneMayLikeProject'] = $service->GetEveryOneMayLikeProject(4,1,2);
    	}
    	return $return_data;
    }
    
    /**
     * 首页根据浏览记录推荐
     * @author 郁政
     * @return array
     */
    public function getYouLookTuiJian($limit,$debug = 0){
    	$res = array();
    	$arr1 = array();
    	$arr2 = array();
    	$arr3 = array();
    	#获取用户user_id
        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
        #获取用户ip
        $ip = ip2long(Request::$client_ip);
        $cache = Rediska_Manager::get("rc");
    	$arr_data = $this->_getVistedLog($user_id, $ip, $limit, 1);    	
    	//第一条     
    	if(isset($arr_data[0]['operate_id']) && $arr_data[0]['operate_id']){
    		$liulan_1 = $cache->get('userLook'.$arr_data[0]['operate_id']);
    		if($liulan_1){
    			$arr1 = array_filter(explode(' ',$liulan_1));  
    			if($arr1){
    				array_unshift($arr1,$arr_data[0]['operate_id']);
    				$arr1 = array_slice($arr1,0,6);
    				$res['first_item'] = $this->_getProjectInfoByarr($arr1);    				
    			} 			
    		}else{
    			$arr1 = $this->_getOtherProject($arr_data[0], $ip,5);
    			if($debug == 1){
    				echo "<pre>";print_r($arr1);exit;
    			}
    			array_unshift($arr1, $arr_data[0]['operate_id']);    			
    			$res['first_item'] = $this->_getProjectInfoByarr($arr1);  
    		}
    	}
    	//第二条     
    	if(isset($arr_data[1]['operate_id']) && $arr_data[1]['operate_id']){
    		$liulan_2 = $cache->get('userLook'.$arr_data[1]['operate_id']);
    		if($liulan_2){
    			$arr2 = array_filter(explode(' ',$liulan_2));  
    			if($arr1){    				
    				$arr2 = array_diff($arr2, $arr1);
    				array_unshift($arr2,$arr_data[1]['operate_id']);
    				$arr2 = array_slice($arr2, 0,6);
    				$res['second_item'] = $this->_getProjectInfoByarr($arr2);
    			}
    		}else{
    			$arr2 = $this->_getOtherProject($arr_data[1], $ip,5);
    			array_unshift($arr2, $arr_data[1]['operate_id']);
    			$res['second_item'] = $this->_getProjectInfoByarr($arr2);  
    		}
    	}
    	//第三条
    	if(isset($arr_data[2]['operate_id']) && $arr_data[2]['operate_id']){
    		$liulan_3 = $cache->get('userLook'.$arr_data[2]['operate_id']);
    		if($liulan_3){
    			$arr3 = array_filter(explode(' ',$liulan_3));
    			if($arr3){ 
    				$arr3 = array_diff($arr3, $arr1,$arr2);
    				$arr3 = array_slice($arr3, 0,6);
    				$res['third_item'] = $this->_getProjectInfoByarr($arr3);
    			}  
    		}else{
    			$arr3 = $this->_getOtherProject($arr_data[2], $ip,5);
    			array_unshift($arr3, $arr_data[2]['operate_id']);
    			$res['third_item'] = $this->_getProjectInfoByarr($arr3); 
    		}
    	}   
    	return $res;
    }
    
    
    /**
     * 过滤访问记录
     * @author 郁政
     * @return array
     */
    public function _filterVistedLog($arr_data) {
        $res = array();
        $arr = array();
        if (!empty($arr_data)) {
            foreach ($arr_data as $k => $v) {
                $arr_data[$k]['industry_id'] = $this->_getProjectIndustryId($v['operate_id']);
            }
        }
        foreach ($arr_data as $k => $val) {
            if (!in_array($val['industry_id'], $arr)) {
                $arr[] = $val['industry_id'];
                array_push($res, $val);
            }
        }
        return $res;
    }

    /**
     * 查找访问记录
     * @author 郁政
     * @return array
     */
    public function _getVistedLog($user_id, $ip, $limit = 3, $type = 0) {
        $arr_data = array();
        $arr = array();
        $arr1 = array();
        $arr2 = array();
        #如果用户没有登录
        if ($user_id == 0) {
            $history_project = Cookie::get('history_project') ? unserialize(Cookie::get('history_project')) : array();
            $arr_data = $history_project;
            if ($type != 0) {
                $arr_data = $this->_filterVistedLog($arr_data);
            }
            $arr_data = array_slice($arr_data, 0, $limit);
        } else {
            #当用户登录时
            $history_project = Cookie::get('history_project') ? unserialize(Cookie::get('history_project')) : array();
            if (count($history_project) < $limit) {
                $arr = $history_project;
                $model = ORM::factory("UserViewProjectLog")->where("user_id", "=", intval($user_id))->where('operate_type', '=', intval(1))->limit(intval($limit))->order_by("update_time", "DESC")->find_all();
                foreach ($model as $key => $val) {
                    $arr[] = $val->as_array();
                }
                //去重
                foreach ($arr as $k => $v) {
                    if (!in_array($v['operate_id'], $arr1)) {
                        $arr1[] = $v['operate_id'];
                        array_push($arr2, $v);
                    }
                }
                if ($type != 0) {
                    $arr2 = $this->_filterVistedLog($arr2);
                }
                $arr_data = array_slice($arr2, 0, $limit);
            } else {
                if ($type != 0) {
                    $history_project = $this->_filterVistedLog($history_project);
                }
                $arr_data = array_slice($history_project, 0, $limit);
            }
        }
        return $arr_data;
    }

    /**
     * 查找项目以及其他人看过此项目的其他项目
     * @author 郁政
     * @return array
     */
    public function _getOtherProject($arr, $ip, $top = 5) {
        $res = array();
        $user_view = array();
        $service = new Service_Platform_ProjectGuide();
        if (!empty($arr)) {
            $project_id = $arr['operate_id'];
            $industry_id = $this->_getProjectIndustryId($project_id);
            $user_view = ORM::factory('UserViewProjectLog')->where('operate_id', '=', $project_id)->where('ip', '<>', $ip)->group_by('ip')->order_by('update_time', 'desc')->limit($top)->find_all()->as_array();
            $ips = array();
            $other = array();
            $other2 = array();
            $other3 = array();
            $others = array();
            if (count($user_view) > 0) {
                foreach ($user_view as $v) {
                    $ips[] = $v->ip;
                }
            }
            try {
                if (count($ips) == 0) {
                    //当该项目没有行业时，用最新的项目补满
                    if ($industry_id == "") {
                        $pro = $service->getNewProjectRanking($top);
                        foreach ($pro as $k => $v) {
                            $res[] = $pro[$k]['project_id'];
                        }
                    } else {
                        //没用其他人浏览过该项目，根据二级行业补满
                        $res = $this->_getProjectByIndustryId($project_id, array(), $industry_id, $top);
                        //当二级行业项目不足时，用一级行业项目补满
                        if (count($res) < $top) {
                            $industry_id_2 = $this->_getProjectIndustryId($project_id, 2);
                            $res_2 = $this->_getProjectByIndustryId($project_id, array(), $industry_id_2, $top - count($res));
                            $res = array_merge($res, $res_2);
                        }
                    }
                } else {
                    ////有其他人浏览过该项目
                    foreach ($ips as $val) {
                        if ($industry_id == "") {
                            $other_view = ORM::factory('UserViewProjectLog')->where('ip', '=', $val)->where('operate_type', '=', 1)->where('operate_id', '<>', $project_id)->limit($top)->find_all();
                        } else {
                            $other_view = ORM::factory('UserViewProjectLog')->join('project_industry', 'left')->on('operate_id', '=', 'project_id')->where('status', '=', intval(2))->where('industry_id', '=', $industry_id)->where('ip', '=', $val)->where('operate_type', '=', 1)->where('operate_id', '<>', $project_id)->where('project_id', '<>', $project_id)->limit($top)->find_all();
                        }
                        foreach ($other_view as $value) {
                            $other[] = $value->as_array();
                        }
                        //根据operate_id去重
                        if (!empty($other)) {
                            foreach ($other as $v) {
                                if (!in_array($v['operate_id'], $other2)) {
                                    $other2[] = $v['operate_id'];
                                    array_push($other3, $v);
                                }
                            }
                            $other = $other3;
                        }
                    }
                    //有其他人浏览过该项目，但其他人访问的项目不足推荐数量
                    if (count($other) < $top) {
                        foreach ($other as $v) {
                            $others[] = $v['operate_id'];
                        }
                        //当该项目没有行业时，用最新的项目补满
                        if ($industry_id == "") {
                            $pro = $service->getNewProjectRanking($top - count($other));
                            foreach ($pro as $k => $v) {
                                $pros[] = $pro[$k]['project_id'];
                            }
                        } else {
                            $pros = $this->_getProjectByIndustryId($project_id, $others, $industry_id, $top - count($other));
                            //当二级行业项目不足时，用一级行业项目补满
                            if (count($pros) < ($top - count($other))) {
                                $industry_id_2 = $this->_getProjectIndustryId($project_id, 2);
                                $pros_2 = $this->_getProjectByIndustryId($project_id, $others, $industry_id_2, ($top - count($other)) - count($pros));
                                $pros = array_merge($pros, $pros_2);
                            }
                            $pros = array_merge($others, $pros);
                        }
                        $res = $pros;
                    } else {
                        //有其他人浏览过该项目，但其他人访问的项目满足推荐的项目,根据update_time排序
                        foreach ($other as $k => $v) {
                            $update_time[$k] = $v['update_time'];
                        }
                        array_multisort($update_time, SORT_DESC, $other);
                        $other = array_slice($other, 0, $top);
                        foreach ($other as $v) {
                            $res[] = $v['operate_id'];
                        }
                    }
                }
            } catch (ErrorException $e) {
                $res = $this->_getProjectByIndustryId($project_id, array(), $industry_id, $top);
                //当二级行业项目不足时，用一级行业项目补满
                if (count($res) < $top) {
                    $industry_id_2 = $this->_getProjectIndustryId($project_id, 2);
                    $res_2 = $this->_getProjectByIndustryId($project_id, array(), $industry_id_2, $top - count($res));
                    $res = array_merge($res, $res_2);
                }
            }
        }        
        return $res;
    }

    /**
     * 获取对应的行业数据
     * @author 嵇烨
     * return array
     */
    public function _getProjectByIndustryId($project_id, $others, $industry_id, $limt = 5) {
        $arr_project_id = array();
        if ($industry_id && $limt) {
            if (!empty($others) && is_array($others)) {
                $project_model = ORM::factory('Project')->join('project_industry', 'left')->on('project.project_id', '=', 'project_industry.project_id')->where('industry_id', '=', intval($industry_id))->where('project_status', '=', intval(2))->where('project.project_id', '<>', $project_id)->where('project.project_id', 'not in', $others)->group_by('project.project_id')->order_by('project_addtime', 'desc')->limit(intval($limt))->find_all();
            } else {
                $project_model = ORM::factory('Project')->join('project_industry', 'left')->on('project.project_id', '=', 'project_industry.project_id')->where('industry_id', '=', intval($industry_id))->where('project_status', '=', intval(2))->where('project.project_id', '<>', $project_id)->group_by('project.project_id')->order_by('project_addtime', 'desc')->limit(intval($limt))->find_all();
            }
            if (count($project_model) > 0) {
                foreach ($project_model as $key => $val) {
                    $arr_project_id[] = $val->project_id;
                }
            }
        }
        return $arr_project_id;
    }

    /**
     * 通过项目的id 来获取行业的id(二级行业)
     * @author  嵇烨
     *
     */
    public function _getProjectIndustryId($project_id, $type = 1) {
        $p_area = array();
        $Projectindustryid = "";
        if ($project_id) {
            $project_area = ORM::factory("Projectindustry")->where("project_id", "=", intval($project_id))->find_all();
            foreach ($project_area as $vv) {
                $p_area [] = ORM::factory("Projectindustry", $vv->pi_id)->industry_id;
            }
            #返回二级行业
            if ($type == 1) {
                if (isset($p_area[0]) && isset($p_area[1])) {
                    if ($p_area[0] > $p_area[1]) {
                        $Projectindustryid = $p_area[0];
                    } elseif ($p_area[0] < $p_area[1]) {
                        $Projectindustryid = $p_area[1];
                    }
                } elseif (isset($p_area[0])) {
                    $Projectindustryid = $p_area[0];
                }
            } elseif ($type == 2) {
                #返回一级行业
                if (isset($p_area[0]) && isset($p_area[1])) {
                    if ($p_area[0] > $p_area[1]) {
                        $Projectindustryid = $p_area[1];
                    } elseif ($p_area[0] < $p_area[1]) {
                        $Projectindustryid = $p_area[0];
                    }
                } elseif (isset($p_area[0])) {
                    $Projectindustryid = $p_area[0];
                }
            }
        }
        return $Projectindustryid;
    }

    /**
     * 通过数组去拿取项目信息
     * @author 嵇烨
     * return array
     */
    public function _getProjectInfoByarr($arr_data) {
        $service = new Service_User_Company_Project();
        $arr_return = array();
        if (!empty($arr_data) && is_array($arr_data)) {
            foreach ($arr_data as $k => $v) {
                $model = DB::select('project_id', 'project_advert', 'project_brand_name', 'project_logo', 'project_brand_birthplace', 'project_pv_count', 'project_amount_type', 'project_source', 'outside_id')->from('project')->where("project_id", "=", intval($v))->where("project_status", '=', '2')->execute()->as_array();
                $arr_return[] = isset($model[0]) ? $model[0] : ORM::factory("Project")->where("project_id", "=", intval($v))->where("project_status", '=', '2')->find()->as_array();
                $arr_return[$k]['industry_name'] = $service->getProjectindustry(intval($v));
                $pro_area = $service->getProjectArea(intval($v));
                $area = '';
                if (count($pro_area) && is_array($pro_area)) {
                    foreach ($pro_area as $val) {
                        $area = $area . $val . ',';
                    }
                    $arr_return[$k]['area_name'] = substr($area, 0, -1);
                } else {
                    $arr_return[$k]['area_name'] = $pro_area;
                }
                #项目pv值
                $arr_return[$k]['project_pv_count'] = $this->_getProjectCount($v);
            }
        }
        return $arr_return;
    }

    /**
     * 删除浏览过的项目
     * @author 嵇烨
     */
    public function _deleteHavaBrowseProject($id) {
        $bool = false;
        $bools = false;
        $arr_data = unserialize(Cookie::get('history_project'));
        $user_id = Cookie::get("user_id") ? Cookie::get("user_id") : 0;
        #分两种情况 1  登录时删除		2 没有登录时删除(只删cookie)
        if ($user_id > 0) {
            #1  登录时删除 
            if ($id != "all") {
                #拿取cookie值
                if (!empty($arr_data)) {
                    foreach ($arr_data as $key => $val) {
                        $history_project_id[] = $val['id'];
                    }
                    #循环删除
                    foreach ($arr_data as $key => $val) {
                        if ($val['id'] == intval($id)) {
                            #删除表中数据
                            $model = ORM::factory("UserViewProjectLog", intval($id));
                            if ($model->loaded()) {
                                $model->delete();
                            }
                            unset($arr_data[$key]);
                            #赋值
                            Cookie::set('history_project', serialize($arr_data), 2592000);
                        }
                    }
                    $bool = true;
                } else {
                    #删除表中数据
                    $model = ORM::factory("UserViewProjectLog", intval($id));
                    if ($model->loaded()) {
                        $model->delete();
                    }
                    $bool = true;
                }
            } else {
                Cookie::delete("history_project");
                $model = DB::delete('user_view_project_log')->where("user_id", "=", intval($user_id))->execute();
                if ($model > 0) {
                    $bool = true;
                }
            }
        } else {
            #2 没有登录时删除
            if ($id != "all") {
                #拿取cookie值
                if (!empty($arr_data)) {
                    #循环删除
                    foreach ($arr_data as $key => $val) {
                        if ($val['id'] == intval($id)) {
                            unset($arr_data[$key]);
                            #赋值
                            Cookie::set('history_project', serialize($arr_data), 2592000);
                        }
                    }
                    $bool = true;
                }
            } else {
                Cookie::delete("history_project");
                $bool = true;
            }
        }
        return $bool;
    }

    /**
     * 及时项目的pv数
     * @author 嵇烨
     * @param project_id
     * @return int
     */
    public function _getProjectCount($project_id) {
        $int_num = 0;
        if ($project_id) {
            $int_nums = ORM::factory("Projectstatistics")->where("project_id", "=", intval($project_id))->count_all();
            $int_num = $int_nums;
        }
        return $int_num;
    }

    /**
     * 获取项目的总数
     * @author 嵇烨
     * @return int
     */
    public function _getProjectAllCount() {
        $memcache = Cache::instance('memcache');
        $int_project_count = $memcache->get("int_project_count");
        if (empty($int_project_count)) {
            $int_project_count = ORM::factory("Project")->where("project_status", '=', intval(2))->count_all();
            $memcache->set("int_project_count", $int_project_count, 86400);
        }
        return $int_project_count;
    }

    /**
     * 获取最新项目的企业的名片数量
     * @author 嵇烨
     * @return array
     */
    public function _getProjectComCarNum() {
        $memcache = Cache::instance('memcache');
        $project_has_nums = $memcache->get("project_has_nums");
        $arr_datas = array();
        if (empty($project_has_nums)) {
            #获取项目的收到的名片的数量
            $count = ORM::factory("UserLetter")->where("user_type", '=', intval(2))->GROUP_BY("to_project_id")->order_by("to_project_id", "DESC")->count_all();
            #查找项目的
            $model = ORM::factory("UserLetter")->where("user_type", '=', intval(2))->GROUP_BY("to_project_id")->order_by("to_project_id", "DESC")->find()->as_array();
            if ($model['id'] > 0 && $model['id']) {
                #查找项目
                $arr_project_model = ORM::factory("Project", intval($model['to_project_id']))->as_array();
                if ($arr_project_model['project_id'] > 0) {
                    $arr_datas['roject_brand_name'] = $arr_project_model['project_brand_name'];
                    $arr_datas['project_nums'] = $count;
                    $arr_datas['project_id'] = $arr_project_model['project_id'];
                }
            }
            $memcache->set("project_has_nums", $arr_datas, 1800);
        } else {
            $arr_datas = $project_has_nums;
        }
        return $arr_datas;
    }

    /**
     * 随机取5条数据标签
     * @author 嵇烨
     * @param $int_num  几条数据    默认6条数据
     */
    public function getTags($int_num = 6) {
        $memcache = Cache::instance('memcache');
        $project_tags = "";
        $arr_new_data = array();
        $project_tags = $memcache->get("project_tags");
        $arr_return_data = array();
        $arr_data_tag_id = array();
        if (empty($project_tags)) {
        	#获取最热门的标签id
			$sql_tag_id = DB::select("tag_id",DB::expr('count(tag_id) as a'))->from("project_tag")->group_by('tag_id')->order_by("a","desc")->limit(20)->execute();
        	foreach ($sql_tag_id as $key=>$val){
				$arr_data_tag_id[] = arr::get($val, "tag_id");
			}
			if(count($arr_data_tag_id) > 0){
				$arr_data =  DB::select("tag_name")->from("tag")->where("tag_id","in",$arr_data_tag_id)->execute()->as_array();
			}
            $memcache->set("project_tags", $arr_data,36*60);
        } else {
            $arr_data = $project_tags;
        }
        #随机取数组中的五条数据
        $arr_new_data = array_rand($arr_data, intval($int_num));
        foreach ($arr_new_data as $key) {
            $arr_return_data [] = arr::get($arr_data[$key],"tag_name");
        }
        return array_unique($arr_return_data);
    }

    /**
     * 获取查看项目最新的用户
     * @author 嵇烨
     */
public function getNewWatchProjectInfo() {
        $memcache = Cache::instance('memcache');
        $users_info = $memcache->get("users_info");
        if (empty($users_info)) {
            $arr_return_data = array();
            $service = new Service_Platform_Project();
            $model = ORM::factory("UserViewProjectLog")->where("user_id", ">", intval(0))->where("operate_type", "=", intval(1))->order_by("update_time", "DESC")->limit(intval(1))->find()->as_array();
            if ($model['id'] > 0) {
                #通过user_id当前的用户名称
            	$arr_user_data = (array)Service_Sso_Client::instance()->getUserInfoById($model['user_id']);
                if ($arr_user_data['user_type'] == 1) {
                    $arr_return_data['user_name'] = $arr_user_data['user_name'] ? $arr_user_data['user_name'] : " ";
                } else {
                    $arr_data = DB::select()->from("user_person")->where("per_user_id", "=", $model['user_id'])->execute()->as_array();
                    if (!empty($arr_data) && $arr_data[0]['per_id'] > 0) {
                        $arr_return_data['user_name'] = $arr_data[0]['per_realname'];
                    } else {
                        $arr_return_data['user_name'] = mb_substr($arr_user_data['email'], 0, 5);
                    }
                }
                $arr_return_data['user_type'] = $arr_user_data['user_type'] ? $arr_user_data['user_type'] : 1;
                #获取项目信息
                $arr_data = $service->getProjectInfoByID($model['operate_id']);
                if ($arr_data != false && $arr_data->project_id > 0) {
                    $arr_return_data['project_id'] = $arr_data->project_id;
                    $arr_return_data['project_brand_name'] = $arr_data->project_brand_name;
                } else {
                    #拿取最新的项目
                    $model_project = ORM::factory("Project")->where("project_status", "=", intval(2))->order_by("project_passtime", "DESC")->limit(intval(1))->find()->as_array();
                    $arr_return_data['project_id'] = $model_project['project_id'];
                    $arr_return_data['project_brand_name'] = $model_project['project_brand_name'];
                }
                $memcache->set("users_info", $arr_return_data, 1800);
            }
        } else {
            $arr_return_data = $users_info;
        }
        return $arr_return_data;
    }

    /**
     * 根据行业id和投资金额类型返回项目信息
     * @author 郁政
     */
    public function getProinfoByIndustryAmount($limit = 3) {
        $memcache = Cache::instance('memcache');
        $new_project_list = $memcache->get("new_project_list");
        $res = array();
        if (empty($new_project_list)) {
            $project = ORM::factory('Project')->where("project_status", "=", intval(2))->order_by("project_passtime", 'DESC')->limit($limit)->find_all();
            foreach ($project as $v) {
                $res[] = $v->as_array();
            }
            $memcache->set("new_project_list", $res, '7200');
        } else {
            $res = $new_project_list;
        }
        return $res;
    }

    /**
     * 项目广告图
     * @author jiye
     * @param  project_id  项目id ; type 图片的类型
     * return  string
     */
    public function getProjectXuanChuanImage($int_project_id, $int_type) {
        $str_date = "";
        if ($int_project_id && $int_type) {
            $model = ORM::factory('Projectcerts')->where("project_id", "=", intval($int_project_id))->where("project_type", "=", intval($int_type))->find()->as_array();
            if ($model['project_certs_id'] > 0 && $model['project_img']) {
                $str_date = URL::imgurl($model['project_img']);
            }
        }
        return $str_date;
    }

    /**
     * 获取招商会总的浏览次数
     * @author  嵇烨
     * return int
     */
    public function getInvestmentHaveWatchNum($array_data, $type) {
        $int_num = 0;
        #浏览的总数
        if (is_array($array_data) && !empty($array_data) && $type) {
            #统计单个招商会的浏览记录
            if ($type == 1) {
                $int_num = ORM::factory("UserViewProjectLog")->where('operate_id', '=', intval($array_data['investment_id']))->where("operate_type", '=', intval(2))->reset(false)->count_all();
            } else {
                #统计多个招商浏览记录
                foreach ($array_data as $key => $val) {
                    $int_num += ORM::factory("UserViewProjectLog")->where('operate_id', '=', $val)->where("operate_type", '=', intval(2))->reset(false)->count_all();
                }
            }
        }
        return $int_num;
    }

    /**
     * 搜索记录入库
     * @author  郁政     
     */
    public function saveSearchRecord($search_record = array()) {
        $record = ORM::factory('SearchRecord');
        $record->user_id = Arr::get($search_record, 'user_id', 0);
        $record->ip = Arr::get($search_record, 'ip', 0);
        $record->key_word = Arr::get($search_record, 'key_word', '');
        $record->search_time = Arr::get($search_record, 'search_time', time());
        $record->create();
        $record->clear();
        return true;
    }

    /**
     * 搜索点击记录入库
     * @author  郁政     
     */
    public function saveSearchClick($search_click = array()) {
        $record = ORM::factory('SearchClick');
        $record->user_id = Arr::get($search_click, 'user_id', 0);
        $record->user_ip = Arr::get($search_click, 'user_ip', 0);
        $record->search_word = Arr::get($search_click, 'search_word', '');
        $record->click_id = Arr::get($search_click, 'click_id', '');
        $record->type = Arr::get($search_click, 'type', 1);
        $record->click_seat = Arr::get($search_click, 'click_seat', 1);
        $record->click_hot_zone = Arr::get($search_click, 'click_hot_zone', 1);
        $record->time = Arr::get($search_click, 'time', time());
        $record->create();
        $record->clear();
        return true;
    }

    /**
     * 获取搜索记录
     * @author  郁政     
     */
    public function getSearchRecord() {
        $result = array();
        $res = array();
        $record = ORM::factory('SearchRecord')->find_all()->as_array();
        if (!empty($record)) {
            foreach ($record as $k => $v) {
                $res[$k]['user_id'] = $v->user_id;
                $res[$k]['ip'] = $v->ip;
                $res[$k]['key_word'] = $v->key_word;
                $res[$k]['search_time'] = $v->search_time;
            }
        }
        $result[] = $res;
        return $result;
    }
    /**
     * 项目logo 替换    
     * @author 嵇烨
     * @飞哥  小窗给我说要替换的
     * @param project_source 项目类型     logo 路径   outside_id 外采id 
     */
    public function replace_project_logo($int_project_source,$str_logo_url,$int_outside_id){
    	$str_return = $str_logo_url;
    	if($int_project_source && $str_logo_url && $int_outside_id){
    		if(($int_project_source == 5 || $int_project_source == 4) && $str_logo_url){
    			$str_return = str_replace("poster/html/ps_{$int_outside_id}/project_logo/", "poster/html/ps_{$int_outside_id}/project_logo/150_120/", $str_logo_url);
    		}
    	}
    	return $str_return;
    }
}

