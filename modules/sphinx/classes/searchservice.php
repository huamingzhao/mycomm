<?php

defined('SYSPATH') OR die('No direct script access.');

/**
 * 搜索服务封装
 * @author 沈鹏飞
 *
 */
class searchservice {

    static $instance;
    static $searchServerIp;
    static $searchServerPort;
    static $searchServerTimeout;

    /**
     * 初始化sphinx
     */
    function __construct() {
        try {
            Kohana::load(MODPATH . 'sphinx/classes/sphinxapi.php');
            self::$searchServerIp = Kohana::$config->load('sphinx.local.host');
            self::$searchServerPort = intval(Kohana::$config->load('sphinx.local.port'));
            self::$searchServerTimeout = intval(Kohana::$config->load('sphinx.local.timeout'));
            $this->search = new SphinxClient();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 精准匹配
     * @author 施磊
     * @param  array $cond 
     */
    public function searchCond($cond, $limit = 8) {
        $modProject = new Service_Platform_Project();
        $price = array('a1' => 9, 'a2' => 5, 'a3' => 6, 'a4' => 7, 'a5' => 17, 'a6' => 3, 'area_pid' => 11, 'industry_pid' => 21);
        //向搜索服务器请求
        $this->search->SetServer(self::$searchServerIp, self::$searchServerPort);
        $this->search->SetConnectTimeout(self::$searchServerTimeout);
        $this->search->SetArrayResult(true);

        //分页
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $off = ($page - 1) * $limit;

        $this->search->SetLimits($off, $limit);
        
        //设置最大搜索时间
        $this->search->setMaxQueryTime(50);
        $this->search->SetMatchMode(SPH_MATCH_EXTENDED2);
        
       $this->search->SetFieldWeights($price); //设置字段的权重，如果area命中，那么权重算2
       $this->search->SetSortMode('SPH_SORT_EXPR', '@weight'); //按照权重排序
       $condShow = array('1' => 'a1', '2' => 'a2', '5' => 'a3', '6' => 'a4', '7' => 'a5', '10' => 'a6');
       
       $query = array();
       $queryMust = array();
       $nowPrice = 21;
        foreach($condShow as $key => $val) {
            if(!arr::get($cond, $key, 0)) {
                if($key == 2) {
                    $nowPrice += $price['area_pid'];
                }
                if($key == 6) {
                    $nowPrice += $price['industry_pid'];
                }
            
                $nowPrice += $price[$val];
            }
        }
       if($cond) {
            foreach($cond as $key => $val) {
                if($key == 2) {
                    if($val == 88) {
                        $nowPrice += 16;
                        continue;
                    }else{
                        $query[] = " @area_pid".' '.$modProject->getCityPid($val).' ';
                        $query[] = " @a2".' 88 ';
                    }
                }
                if($key == 6) {
                    $inPid = $modProject->getIndustryPid($val) ? $modProject->getIndustryPid($val) : $val;
                    $query[] = " @industry_pid".' '.$inPid.' ';
                }
                if($key == 7) {
                    $query[] = " @a5".' '.$val.' ';
                    continue;
                }
                $query[] = " @".$condShow[$key].' '.$val.' ';
            }
       }
       $query = implode('|', $query);
       $queryMust = implode(' ', $queryMust);
       $allQuery = "($query)";
       $allQuery .= ($queryMust) ? " ($queryMust)" : "";
        //搜索关键字
        $res = $result = $this->search->Query("$allQuery", Kohana::$config->load('search.guide_search'));
        $return = array('list' => array(), 'allId' => array());
        if(arr::get($res, 'matches', array())) {
            $forRes = arr::get($res, 'matches', array());
           foreach($forRes as $key => $val) {
               $return['list'][$val['id']] = floor ($val['weight']/1000)+$nowPrice;
               $return['allId'][] = $val['id'];
           } 
        }
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        if($debug) var_dump($allQuery,$nowPrice, $forRes);
        return $return;
    }
    /**
     * 直搜bySora
     */
    public function searchWordBySolr($word, $cond = '') {
        $modProject = new Service_Platform_Project ();
        $res = $this->checkWord($word);
        $amount = $this->getWordAmountSector($word);
        $word = $amount['word'];
        $cond['atype'] = arr::get($cond, 'atype', 0);
        $newWordArr = array();
        $searchAmout = $this->getWordAmount($word, arr::get($cond, 'atype', 0));
        //$word = trim($searchAmout['word']);
        if(!$cond['atype']) {
            #获得价格区间
            if(isset($searchAmout['eddAmountSector']) && $searchAmout['eddAmountSector']) {
                foreach($searchAmout['eddAmountSector'] as $val) {
                    $newWordArr[] = '"'.$modProject->getQuestCont (7, $val).'"';
                }
            }
        }else{
            $newWordArr[] = '"'.$modProject->getQuestCont (7, $cond['atype']).'"';
        }
        $Search = new Service_Api_Search();       
        $participle = $Search->getParticiple($word);
        $word_1 = str_replace(' ', '', $word);
        $correctWord = $Search->getCorrectWord($word_1);
        $words = array();
        $areaNewArr = array();
        if($participle) {
            foreach($participle as $val) {
                $words[$val] = array('hits' => 1, 'docs' => 1);
                $ormReturn = ORM::factory("Directory")->where('keyword', '=', $val)->find()->as_array();
                if(arr::get($ormReturn, 'question_type', 0)) {
                    #地区的特殊处理
                    if(arr::get($ormReturn, 'question_type', 0) == 2) {
                        $areaNewArr[] = '全国';
                        $newWordArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), arr::get($ormReturn, 'question_id', 0));
                        $citPid = $modProject->getCityPid(arr::get($ormReturn, 'question_id', 0));
                        if($citPid) {
                            $areaNewArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), $citPid);
                            $city = ORM::factory("City")->where('cit_id', '=', $citPid)->find()->as_array();
                            if($city['area']) {
                                $areaNewArr[] = $city['area'];
                            }
                        }else {
                            $city = ORM::factory("City")->where('cit_id', '=', arr::get($ormReturn, 'question_id', 0))->find()->as_array();
                            if($city['area']) {
                                $areaNewArr[] = $city['area'];
                            }
                        }
                    }
                    #处理行业
                    elseif(arr::get($ormReturn, 'question_type', 0) == 6) {
                        $indPid = $modProject->getIndustryPid(arr::get($ormReturn, 'question_id', 0));
                        if($indPid) {
                            $newWordArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), $indPid);    
                        }
                        $newWordArr[] = $val;

                    }else{
                        $newWordArr[] = $val;
                    }
                }
                else{
                    $newWordArr[] = $val;
                }
            }
        }
        $newWordArr = array_unique($newWordArr);
        $newWordArr = implode(' ', $newWordArr);
        $areaNewArr = array_unique($areaNewArr);
        $areaNewArr = implode(' ', $areaNewArr);
        $limit = 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        //设置排序
        $sortArr = array(1 => 'score', 2 => 'projectApprovingCount', 3 => 'projectId');
        $sort = '';
        
       // $newWordArr = $newWordArr.' projectSourceType:1';
        $search = $Search->getSearch($amount['word'] ,$areaNewArr, $offset, $sort);
        if($page == 1) {
            $searchOutSide = $Search->getSearch($amount['word'], $areaNewArr,0,$sort,'生意街商机汇',2);
             if(isset($_GET['debug']) && $_GET['debug']==1) var_dump('sjh',$searchOutSide);
            if(arr::get($searchOutSide, 'matches', array()) && arr::get($search, 'matches', array())) {
                //arsort($searchOutSide['matches']);
                foreach($searchOutSide['matches'] as $key => $val) {
                    if(arr::get($search['matches'], $key)) {
                        unset($search['matches'][$key]);
                    }else{
                        array_pop($search['matches']);
                    }
                }
               array_splice($search['matches'], 0, 0, $searchOutSide['matches']);
                
            }
        }
        $search['words'] = $words;
        $search['correctWord'] = $correctWord;
        if(isset($_GET['debug']) && $_GET['debug']==1){
            var_dump($participle,$words, $newWordArr,$areaNewArr,$sort);
        }
        return $search;
        
    }
	
	/**
	 * 直搜bySora[搜索投资者]
	 */
	public function searchInvestorBySolr($word, $cond = '') {
		$modProject = new Service_Platform_Project ();
		$res = $this->checkWord ( $word );
		$amount = $this->getWordAmountSector ( $word );
		$word = $amount ['word'];
		$cond ['atype'] = arr::get ( $cond, 'atype', 0 );
		$newWordArr = array ();
		$searchAmout = $this->getWordAmount ( $word, arr::get ( $cond, 'atype', 0 ) );
		$word = trim ( $searchAmout ['word'] );
		if (! $cond ['atype']) {
			// 得价格区间
			if (isset ( $searchAmout ['eddAmountSector'] ) && $searchAmout ['eddAmountSector']) {
				foreach ( $searchAmout ['eddAmountSector'] as $val ) {
					$newWordArr [] = '"' . $modProject->getQuestCont ( 7, $val ) . '"';
				}
			}
		} else {
			$newWordArr [] = '"' . $modProject->getQuestCont ( 7, $cond ['atype'] ) . '"';
		}
		$Search = new Service_Api_Search ();
		$participle = $Search->getParticipleByInvestor ( $word );
		$words = array ();
		$areaNewArr = array ();
		if ($participle) {
			foreach ( $participle as $val ) {
				$words [$val] = array (
						'hits' => 1,
						'docs' => 1 
				);
				$ormReturn = ORM::factory ( "Directory" )->where ( 'keyword', '=', $val )->find ()->as_array ();
			   if(arr::get($ormReturn, 'question_type', 0)) {
                    #地区的特殊处理
                    if(arr::get($ormReturn, 'question_type', 0) == 2) {
                        $areaNewArr[] = '全国';
                        $newWordArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), arr::get($ormReturn, 'question_id', 0));
                        $citPid = $modProject->getCityPid(arr::get($ormReturn, 'question_id', 0));
                        if($citPid) {
                            $areaNewArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), $citPid);
                            $city = ORM::factory("City")->where('cit_id', '=', $citPid)->find()->as_array();
                            if($city['area']) {
                                $areaNewArr[] = $city['area'];
                            }
                        }else {
                            $city = ORM::factory("City")->where('cit_id', '=', arr::get($ormReturn, 'question_id', 0))->find()->as_array();
                            if($city['area']) {
                                $areaNewArr[] = $city['area'];
                            }
                        }
                    }
                    #处理行业
                    elseif(arr::get($ormReturn, 'question_type', 0) == 6) {
                        $indPid = $modProject->getIndustryPid(arr::get($ormReturn, 'question_id', 0));
                        if($indPid) {
                            $newWordArr[] = $modProject->getQuestCont (arr::get($ormReturn, 'question_type', 0), $indPid);    
                        }
                        $newWordArr[] = $val;

                    }else{
                        $newWordArr[] = $val;
                    }
                }else {
					$newWordArr [] = $val;
				}
			}
		}
		$newWordArr = array_unique ( $newWordArr );
		$newWordArr = implode ( ' ', $newWordArr );
		$areaNewArr = array_unique ( $areaNewArr );
		$areaNewArr = implode ( ' ', $areaNewArr );
	
		$limit = 10;
		if($newWordArr){
			$page = isset ( $_GET ['p'] ) ? intval ( $_GET ['p'] ) : 1;
		}else{
			$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
		}
		$offset = ($page - 1) * $limit;
		$sort='score desc';
		$search = $Search->getSearchByInvestor ( $newWordArr, $areaNewArr, $offset, $sort );
		$search ['words'] = $words;
		if (isset ( $_GET ['debug'] ) && $_GET ['debug'] == 1) {
			var_dump ( $words, $newWordArr );
		}
		return $search;
	}
    
    /**
     * 搜索关键字 by [搜索投资者]
     * @param string $word
     * @return Ambigous <boolean, multitype:>
     */
    public function searchKeywordInvestor($word, $cond = '') {
    	$res = $this->checkWord($word);
    	$amount = $this->getWordAmountSector($word);
    	$word = $amount['word'];
    	//金额
    	$cond['atype'] = arr::get($cond, 'atype', 0);
    	$return = $this->getWordAmount($word, arr::get($cond, 'atype', 0));
    	$return['endAmountKey'] = array_unique(array_merge($amount['endAmountKey'], $return['endAmountKey']));
    	$return['eddAmountSector'] = array_unique(array_merge($amount['eddAmountSector'], $return['eddAmountSector']));
    	$word = trim($return['word']);
    	if (isset($res['code']) && $res['code'] == '200') {
    		$res = NULL;
    		//向搜索服务器请求
    		$this->search->SetServer(self::$searchServerIp, self::$searchServerPort);
    		$this->search->SetConnectTimeout(self::$searchServerTimeout);
    		$this->search->SetArrayResult(true);
    		$limit = 10;
    		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    		$off = ($page - 1) * $limit;
    		$this->search->SetLimits($off, $limit);
    		$this->search->SetMatchMode(SPH_MATCH_ALL);
    		/**
    		 * 搜索投资金额区间
    		 * 1   5万以下
    		 * 2   5-10万
    		 * 3   10-20万
    		 * 4   20-50万
    		 * 5   50万以上
    		*/
    		//金额
    		if (isset($return['eddAmountSector']) && $return['eddAmountSector'] && !$cond['atype']) {
    			$this->search->SetFilter("per_amount", $return['eddAmountSector']);
    		} elseif ($cond['atype']) {
    			$this->search->SetFilter("per_amount", array($cond['atype']));
    		}else{	}
    
    		//是否验证手机
    		if (isset($cond['istatus']) && $cond['istatus']) {
    			$this->search->SetFilter("valid_mobile", array($cond['istatus']));
    		}
    		//设置排序
    		//$sortArr = array(1 => 'per_createtime', 2 => 'per_createtime');
    		//$sort = arr::get($sortArr, arr::get($cond, 'sort', 1));
    		$this->search->SetSortMode(SPH_SORT_EXTENDED, "per_createtime desc");
    
    		//设置最大搜索时间
    		$this->search->setMaxQueryTime(5);
    		$this->search->SetMatchMode(SPH_MATCH_EXTENDED2);
    
    		//$ssss = $this->search->BuildKeywords($word, 'user_person', false);
    		//$this->search->SetSortMode(SPH_RANK_WORDCOUNT);
    		//$this->search->ResetFilters();
    		//$this->search->SetSortMode(SPH_SORT_RELEVANCE);
    		//搜索关键字
    		//行业+行业
    		//     		echo $word;exit;
    		//     		if (isset($cond['inid']) && $cond['inid'] && isset($cond['areaid']) && $cond['areaid']) {
    		//     			$res = $result = $this->search->Query("@industry_id {$cond['inid']} @area_id {$cond['areaid']} $word", "user_person");
    		//     		}elseif(isset($cond['inid']) && $cond['inid']){//行业
    		//     			$res = $result = $this->search->Query("@industry_id {$cond['inid']} $word", "user_person");
    		//     		}elseif(isset($cond['areaid']) && $cond['areaid']){//地区
    		//     			$res = $result = $this->search->Query("@area_id {$cond['areaid']} $word", "user_person");
    		//     		}else{
    		$res = $result = $this->search->Query($word, "user_person");
    		//}
    		//封装返回值  增加状态码code
    		if (is_array($res) && !empty($res)) {
    			if (isset($return['endAmountKey']) && $return['endAmountKey']) {
    				if (!isset($res['words']) || !$res['words']) {
    					$res['words'] = array();
    				}
    				$res['eddAmountSector'] = $return['eddAmountSector'];
    				$res['words'] = $this->pushWord($res['words'], $return['endAmountKey']);
    			}
    			$res['code'] = '200';
    			unset($res['error'], $res['warning'], $res['status'], $res['fields'], $res['attrs'], $res['total_found'], $res['time']);
    		} else {
    			$res['code'] = '201';
    			$res['error'] = '未知错误';
    		}
    	}
    
    	return $res;
    }
    
    /**
     * 搜索关键字
     * @param string $word
     * @return Ambigous <boolean, multitype:>
     */
    public function searchKeyword($word, $cond = '') {
        $res = $this->checkWord($word);
        $amount = $this->getWordAmountSector($word);
        $word = $amount['word'];
        $cond['atype'] = arr::get($cond, 'atype', 0);
        $return = $this->getWordAmount($word, arr::get($cond, 'atype', 0));
        $return['endAmountKey'] = array_unique(array_merge($amount['endAmountKey'], $return['endAmountKey']));
        $return['eddAmountSector'] = array_unique(array_merge($amount['eddAmountSector'], $return['eddAmountSector']));
        $word = trim($word);
        if (isset($res['code']) && $res['code'] == '200') {
            $res = NULL;
            //向搜索服务器请求
            $this->search->SetServer(self::$searchServerIp, self::$searchServerPort);
            $this->search->SetConnectTimeout(self::$searchServerTimeout);
            $this->search->SetArrayResult(true);


            $limit = 10;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $off = ($page - 1) * $limit;

            $this->search->SetLimits($off, $limit);


            // $this->search->SetMatchMode(SPH_MATCH_ALL);
            //$this->search->SetSortMode(SPH_SORT_EXTENDED, "key_id desc");
            //$this->search->SetSortMode(SPH_SORT_RELEVANCE);
            //$this->search->SetFilter("project_id_attr",array(6839,6840));
            //$this->search->SetFilterRange("project_id_attr",1,9000);

            /**
             * 搜索投资金额区间
             * 1   5万以下
             * 2   5-10万
             * 3   10-20万
             * 4   20-50万
             * 5   50万以上
             */
            if (isset($return['eddAmountSector']) && $return['eddAmountSector'] && !$cond['atype']) {
                $this->search->SetFilter("project_amount_type", $return['eddAmountSector']);
            } elseif ($cond['atype']) {
                $this->search->SetFilter("project_amount_type", array($cond['atype']));
            }
            if (isset($cond['project_investment_status']) && $cond['project_investment_status']) {
                $this->search->SetFilter("project_investment_status", array($cond['project_investment_status']));
            }
            //设置排序
            $sortArr = array(1 => 'project_approving_count', 2 => 'project_approving_count', 3 => 'project_addtime');
            $sort = arr::get($sortArr, arr::get($cond, 'sort', 1));
            $this->search->SetSortMode(SPH_SORT_EXTENDED, "{$sort} desc");

            //设置最大搜索时间
            $this->search->setMaxQueryTime(5);
            $this->search->SetMatchMode(SPH_MATCH_ANY);

            $ssss = $this->search->BuildKeywords($word, Kohana::$config->load('search.index_search'), false);
            //$this->search->SetSortMode(SPH_RANK_WORDCOUNT);
            //$this->search->ResetFilters();
            //$this->search->SetSortMode(SPH_SORT_RELEVANCE);
            //搜索关键字
            $res = $result = $this->search->Query($word, Kohana::$config->load('search.index_search'));

            //封装返回值  增加状态码code
            if (is_array($res) && !empty($res)) {
                if (isset($return['endAmountKey']) && $return['endAmountKey']) {
                    if (!isset($res['words']) || !$res['words']) {
                        $res['words'] = array();
                    }
                    $res['eddAmountSector'] = $return['eddAmountSector'];
                    $res['words'] = $this->pushWord($res['words'], $return['endAmountKey']);
                }
                $res['code'] = '200';
                unset($res['error'], $res['warning'], $res['status'], $res['fields'], $res['attrs'], $res['total_found'], $res['time']);
            } else {
                $res['code'] = '201';
                $res['error'] = '未知错误';
            }
        }

        return $res;
    }

    /**
     * 一句话搜索分解出金额区间
     * @author 施磊
     */
    public function getWordAmountSector($word) {
        $amountKey = array('5万以下' => 1, '5 - 10万' => 2, '10 - 20万' => 3, '20 - 50万' => 4, '50万以上' => 5);
        $endAmountKey = array();
        $eddAmountSector = array();
        foreach ($amountKey as $key => $val) {
            if (is_int(strpos($word, $key))) {
                $endAmountKey[] = $key;
                $eddAmountSector[] = $val;
                $word = str_replace($key, '', $word);
            }
        }
        /*
        if (is_int(strpos($word, '全国'))) {
            $endAmountKey[] = '全国';
            $word = str_replace('全国', '', $word);
            
        }*/
        $eddAmountSector = array_unique($eddAmountSector);
        return $return = array('endAmountKey' => $endAmountKey, 'eddAmountSector' => $eddAmountSector, 'word' => $word);
    }

    /*
     * 一句话搜索分解出金额 
     * @author 施磊
     * @param string $word
     * @return string
     */

    public function getWordAmount($word, $atype) {
        //先检查所有数字 还没添加浮点数的正则
        $cond = '/(\d+)/s';
        //关键词 
        $amountKey = array('百' => 100, '千' => 1000, '万' => 10000, '百万' => 1000000, '千万' => 10000000, 'w' => 10000, 'k' => 1000, 'W' => 10000, 'K' => 1000);
        //屏蔽金额
        $unShowKey = array('2014', '2013', '2012', '2011', '2000');
        //屏蔽一些无用词
        $unShowWord = array('-', '到', '以下', '以上');
        //价格区间
        $amountSector = array(
            1 => array(
                '<' => 50000
            ),
            2 => array(
                '>=' => 50000,
                '<' => 100000
            ),
            3 => array(
                '>=' => 100000,
                '<' => 200000
            ),
            4 => array(
                '>=' => 200000,
                '<=' => 500000
            ),
            5 => array(
                '>' => 500000
            ),
        );
        preg_match_all($cond, $word, $arr);
        $endAmountArr = array();
        $endAmountKey = array();
        $eddAmountSector = array();
        $unit = '';
        $unitVal = 0;
        if ($arr[0]) {
            $arr[0] = array_reverse($arr[0]);
            foreach ($arr[0] as $key => $val) {
                $endAmountArr[$key] = '';
                $endAmountStr = '';
                //继续匹配单位
                foreach ($amountKey as $keyT => $valT) {
                    $condNext = $val . $keyT . '元';
                    $condNextSec = $val . $keyT;
                    if (is_int(strpos($word, $condNext))) {
                        $endAmountArr[$key] = $val * $valT;
                        $endAmountKey[$key] = $condNext;
                        $endAmountStr = $condNext;
                        $unit = $keyT . '元';
                        $unitVal = $valT;
                        continue;
                    } else if (is_int(strpos($word, $condNextSec))) {
                        $endAmountArr[$key] = $val * $valT;
                        $endAmountKey[$key] = $condNextSec;
                        $endAmountStr = $condNextSec;
                        $unit = $keyT;
                        $unitVal = $valT;

                        continue;
                    } else if (is_int(strpos($word, $val))) {
                        if (intval($endAmountArr[$key]) < $val && !in_array($val, $unShowKey)) {
                            if ($unitVal) {
                                $endAmountArr[$key] = $val * $unitVal;
                            } else {
                                $endAmountArr[$key] = $val;
                            }
                            $endAmountKey[$key] = $val . $unit;
                            $endAmountStr = $val;
                        }
                    }
                }
                //删除掉金额
                if ($endAmountStr)
                    $word = str_replace($endAmountStr, '', $word);
            }
            $endAmountKey = array_reverse($endAmountKey);
            $endAmountArr = array_filter($endAmountArr);
            //匹配区间
            if (count($endAmountArr)) {
                foreach ($endAmountArr as $valTr) {
                    $status = TRUE;
                    foreach ($amountSector as $keyF => $valF) {
                        if ($status) {
                            foreach ($valF as $keyFi => $valFi) {
                                if (!$status) {
                                    $status = false;
                                    break;
                                }
                                // 悲剧 不能用这样的判断 只能修改成脑残的判断方式var_dump(($valTr.$keyFi.$valFi), (bool)($valTr.$keyFi.$valFi));
                                if ($keyFi == '>') {
                                    $status = (bool) ($valTr > $valFi);
                                } else if ($keyFi == '<') {
                                    $status = (bool) ($valTr < $valFi);
                                } else if ($keyFi == '>=') {
                                    $status = (bool) ($valTr >= $valFi);
                                } else if ($keyFi == '<=') {
                                    $status = (bool) ($valTr <= $valFi);
                                }
                            }
                            if ($status) {
                                $eddAmountSector[] = $keyF;
                                break;
                            } else {
                                $status = TRUE;
                            }
                        }
                    }
                }
            }
        }
        foreach ($unShowWord as $valW) {
            $word = str_replace($valW, '', $word);
        }
        $eddAmountSector = array_unique($eddAmountSector);
        if ($atype) {
            $endAmountKey = array();
        }
        return $return = array('endAmountKey' => $endAmountKey, 'eddAmountSector' => $eddAmountSector, 'word' => $word);
    }

    /**
     * 新增push金额关键词
     */
    public function pushWord($word, $endAmountKey) {
        if ($endAmountKey) {
            foreach ($endAmountKey as $val) {
                $word[$val] = array('docs' => 0, 'hits' => 0);
            }
        }
        return $word;
    }

    /**
     * 预先检查关键词
     * @param string $word
     * @return array
     */
    public function checkWord($word) {
        switch ($word) {
            case "":
                $res['code'] = '201';
                $res['error'] = '搜索的关键词不能为空';
                break;
            default:
                $res['code'] = '200';
                break;
        }

        return $res;
    }

    /**
     * 设置高亮
     * @param unknown_type $result
     * @param unknown_type $key
     * @param unknown_type $c
     * @param unknown_type $index
     * @param unknown_type $words
     * @param unknown_type $opt
     * @return boolean|unknown
     */
    public function myBuildExcerpts($result, $key, &$c, $index, $words, $opt) {
        if (!is_array($result) || count($result) < 1)
            return false;
        if (!is_array($key)) {
            $key = array($key);
        }
        $resultTemp = $result[0];
        if (!is_array($resultTemp))
            return false;
        foreach ($key as $k) {
            if (!array_key_exists($k, $resultTemp)) {
                return false;
            }
            foreach ($result as $val) {
                $docs[] = iconv("gbk", "utf-8", $val[$k]);
            }
            $docs = &$c->BuildExcerpts($docs, $index, $words, $opt);
            foreach ($docs as $dKey => $v) {
                $result[$dKey][$k] = iconv("utf-8", "gbk", $v);
            }
            unset($docs);
        }
        return $result;
    }

    /**
     * 创建单例
     * @return searchService
     */
    static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new searchService();
        }
        return self::$instance;
    }

}