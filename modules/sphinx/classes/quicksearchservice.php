<?php

defined('SYSPATH') OR die('No direct script access.');

/**
 * 搜索服务封装
 * @author 沈鹏飞
 *
 */
class quicksearchservice {
     static $instance;
    /**
     * 创建单例
     * @return searchService
     */
    static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new quicksearchservice();
        }
        return self::$instance;
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
     * 快速搜索 条件搜索
     */
    public function searchCondBySolr($cond) {
        $Search = new Service_Api_Search(); 
        $modProject = new Service_Platform_Project();
        $pModel = common::puickProjectModel();
        $searchWord = array();
        if(isset($cond['industry_id']) && $cond['industry_id']) {
            if($cond['industry_id'] != $cond['pid']) {
                $industryName = $modProject->getQuestCont (6, $cond['industry_id']);
                $searchWord[] = ' industryname:'.$industryName; 
                if($industryName == '其他') {
                    $searchWord[] = ' industrypid:'.$modProject->getQuestCont (6, $cond['pid']);
                }
            }else{
                $searchWord[] = ' industrypid:'.$modProject->getQuestCont (6, $cond['industry_id']);
            }
        }
        if(isset($cond['area_id']) && $cond['area_id']) {
            $searchWord[] = ' areapid:'.$modProject->getQuestCont (2, $cond['area_id']);
        }
        if(isset($cond['atype']) && $cond['atype']) {
            $searchWord[] = ' projectamounttype:'.$modProject->getQuestCont (7, $cond['atype']);
        }
        if(isset($cond['pmodel']) && $cond['pmodel']) {
            $searchWord[] = ' pmodel:'.arr::get($pModel,  $cond['pmodel'], '');
        }
        if(!$searchWord) return array();
        $searchWord = implode(' AND ', $searchWord);
        $limit = 30;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        //设置排序
        $sortArr = array(1 => 'score', 2 => 'projectApprovingCount', 3 => 'projectId');
        
        $sort = 'projectupdatetime desc';
        $search = $Search->getQuickSearchByCond($searchWord , $offset, $sort);
        
        $search['words'] = $searchWord;
        $search['correctWord'] = '';
        if(isset($_GET['debug']) && $_GET['debug']==1){
           //var_dump($word ,$sort);
        }
        return $search;
    }
    /**
     * 快速直搜bySora
     */
    public function searchWordBySolr($word) {
        $res = $this->checkWord($word);
        $amount = $this->getWordAmountSector($word);
        $word = $amount['word'];
        
        $Search = new Service_Api_Search();       
        $participle = $Search->getParticiple($word);
        $word_1 = str_replace(' ', '', $word);
        $correctWord = '';
        $words = array();
        $areaNewArr = array();
        if($participle) {
            foreach($participle as $val) {
                $words[$val] = array('hits' => 1, 'docs' => 1);  
            }
        }
        $limit = 30;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        //设置排序
        $sortArr = array(1 => 'score', 2 => 'projectApprovingCount', 3 => 'projectId');
        
        $sort = '';
        
        $search = $Search->getQucikSearch($word , $offset, $sort);
        
        $search['words'] = $words;
        $search['correctWord'] = $correctWord;
        if(isset($_GET['debug']) && $_GET['debug']==1){
            var_dump($participle,$words ,$sort);
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
}