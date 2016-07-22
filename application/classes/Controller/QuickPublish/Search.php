<?php

defined('SYSPATH') OR die('No direct script access.');

class Controller_QuickPublish_Search extends Controller_Template {

    public function before() {
        parent::before();
    }

    /**
     * 快速发布直搜
     */
    public function action_search() {
        $is_search = 1;
        #判断是否有搜索词
        if (!isset($_GET['w'])) {
            $is_search = $_GET ? 1 : 0;
            $_GET['w'] = '';
        }
        
        #初始化
        $search = new Service_QuickPublish_Search();
        $modProject = new Service_Platform_Project ();
        $memcache = Cache::instance('memcache');

        $_GET['page'] = secure::secureInput(secure::secureUTF(arr::get($_GET, 'page', 1)));
        //print_r($_GET);
        $arrPingY = array_flip(common::getAreaPinYin());
        $cond['industry_id'] = secure::secureInput(intval(arr::get($_GET, 'industry_id', 0)));
        $cond['pid'] = $cond['industry_id'] ? $modProject->getIndustryPid($cond ['industry_id']) ? $modProject->getIndustryPid($cond ['industry_id']) : $cond ['industry_id'] : 0;
        $cond['atype'] = secure::secureInput(intval(arr::get($_GET, 'atype', 0)));
        $cond['area_id'] = arr::get($arrPingY , secure::secureInput(arr::get($_GET, 'area_id', '')),0);
        $cond['pmodel'] = secure::secureInput(intval(arr::get($_GET, 'pmodel', 0)));        
        if($cond['industry_id'] || $cond['atype'] || $cond['area_id']) {
            $is_search = 0;
        }  
        $isLogin = $this->isLogins();
        
        $industry = common::getIndustryList ();
        $pModel = common::puickProjectModel2();
        //推荐广告
        $serviceProject = new Service_QuickPublish_Project();
        $advertList = $serviceProject->getQuickAdvertOne();
        $area = array('pro_id' => 0);
        $areas = common::arrArea($area);
        $word = $_GET['w'] = secure::secureInput(secure::secureUTF(arr::get($_GET, 'w', 1)));
        $search_count = 0;
        $local_arr = array();  
        if ($is_search && $word) {
            #搜索词$word = $_GET['w'] = secure::secureInput(secure::secureUTF(arr::get($_GET, 'w', 1)));
            //$loginStatus = $this->isLogins();
            //$user_id = $loginStatus ? $this->userInfo()->user_id : 0;
            
            $searchresult_key = 'getQuickWordSearch' . $_GET['page'] .'_'. $word;
            $searchresultSearch = $memcache->get($searchresult_key);
            if (!$searchresultSearch) {
                $searchresult = $search->getWordSearch($word);
                $matches = arr::get($searchresult, 'matches', array());
                $search_count = arr::get($searchresult, 'total', 0);
                if ($matches) {
                    $searchresultSearch = $search->getSearchPro($matches, $search_count ,2);
                    $searchresultSearch['search_count'] = $search_count;
                    $memcache->set($searchresult_key, $searchresultSearch, 300);
                }
            }
            //seo
            $serviceProject = new Service_QuickPublish_Project();
            $get = Arr::map("HTML::chars", $this->request->query());
            $page = $_GET['page'];
            $strpage = ""; 
	    	if($page == 1){
	            $strpage = "";
	        }else{
	            $strpage = '第'.$page.'页';
	        } 
                $advertList = $serviceProject->getQuickAdvertOne();
	        $this->template->title = $strpage.'【'.$word.'】招商加盟信息_一句话商机网';
	        $this->template->keywords = $word;
	        $this->template->description = '一句话商机网(www.yjh.com)为你提供'.$word.'招商加盟信息。';
        }else{             
            //ip所在地城市
            $service = new Service_QuickPublish_Project(); 
            if(isset($_SERVER['HTTP_REFERER'])){
            	if(!strpos($_SERVER['HTTP_REFERER'],'/zs')){
			        $ip = Request::$client_ip;	       
			        $local = str_replace('- ','',common::convertip($ip));
			        $local_arr = $service->getCityIdByName($local);   
			        if($local_arr && isset($local_arr['cit_id']) && $cond['area_id'] === 0 && $cond['industry_id'] === false && $cond['pid'] === 0 && $cond['atype'] === false && $cond['pmodel'] === false){
			        	self::redirect(URL::website('/zs/'.common::getAreaPinYin($local_arr['cit_id']).'/'));
			        }  
            	}       
            }   
	        $condWord[] = $cond['area_id'] ? $modProject->getQuestCont (2, $cond['area_id']) : '';
        	$condWord[] = $cond['atype'] ? $modProject->getQuestCont (7, $cond['atype']) : ''; 
            $condWord[] = $cond['industry_id'] ? $modProject->getQuestCont (6, $cond['industry_id']): ''; 
            $condWord[] = $cond['pmodel'] ? arr::get($pModel,  $cond['pmodel'], ''): ''; 
            $condWord = array_filter($condWord) ? implode('', $condWord) : '';
            $searchresult_key = 'getQuickWordSearch_' . $_GET['page'] .'_'.  json_encode($cond);
            $searchresultSearch = array();
            $searchresultSearch = $memcache->get($searchresult_key);           
            if (!$searchresultSearch) {
               $searchresult = $search->getCondSearch($cond);
                $matches = arr::get($searchresult, 'matches', array());
                $search_count = arr::get($searchresult, 'total', 0);
                if ($matches) {
                    $searchresultSearch = $search->getSearchPro($matches, $search_count);
                    $searchresultSearch['search_count'] = $search_count;
                    $memcache->set($searchresult_key, $searchresultSearch, 300);
                } 
            }
            //seo
            $serviceProject = new Service_QuickPublish_Project();
            $advertList = $serviceProject->getQuickAdvertOne($cond['pid']);
            $get = Arr::map("HTML::chars", $this->request->query());
            $page = $_GET['page'];
            $strpage = ""; 
	    	if($page == 1){
	            $strpage = "";
	        }else{
	            $strpage = '第'.$page.'页';
	        }     
	        $areaid = arr::get($arrPingY , secure::secureInput(arr::get($_GET, 'area_id', '')), 0);        
	        $inid = intval(Arr::get($get, 'industry_id',0));
	        //$pid = $serviceProject->getIndustryPid($inid);
	        $atype = intval(Arr::get($get, 'atype',0));
        	if($atype == ""){
                $atype = 0;
            }
        	$pmodel = intval(Arr::get($get, 'pmodel',0));
            if($pmodel == ""){
                $pmodel = 0;
            }
            if(!($areaid == 0 && $inid == 0 && $atype == 0 && $pmodel == 0)){
                $seoList = $service->getHotTj($areaid, $inid, $atype, $pmodel);
            	$cityName = "";
            	$hangye = "";	            
	            $touzi = "";
	            $xingshi = "";
	            $arr = common::puickProjectModel();
	            if($areaid != 0 && $inid == 0 && $atype == 0 && $pmodel == 0){
	            	$cityName = $modProject->getQuestCont(2, $areaid);
	            	$this->template->title = $strpage.$cityName.'招商加盟_'.$cityName.'代理_'.$cityName.'连锁开店_'.$cityName.'创业项目_一句话商机网';
			    	$this->template->keywords = $cityName.'招商加盟，'.$cityName.'代理，'.$cityName.'连锁开店，'.$cityName.'创业项目';
			    	$this->template->description = $cityName.'一句话商机网招商加盟专区汇聚大量的'.$cityName.'招商加盟信息，有招商加盟信息发布、免费查询功能，找'.$cityName.'招商加盟项目信息，请关注一句话商机网'.$cityName.'招商加盟专区。';
	            }elseif($areaid == 0 && $inid != 0 && $atype == 0 && $pmodel == 0){
	            	$hangye = $modProject->getQuestCont(6,$inid);
	            	$this->template->title = $strpage.'【'.$hangye.'加盟 | '.$hangye.'代理】 - 一句话商机网';
			    	$this->template->keywords = $hangye.'加盟，'.$hangye.'代理，一句话商机网';
			    	$this->template->description = '一句话商机网'.$hangye.'加盟频道有大量的'.$hangye.'加盟、'.$hangye.'代理信息，在这里你可以发布'.$hangye.'加盟、查看更多'.$hangye.'加盟、'.$hangye.'代理信息，选项目、发布项目，上一句话'.$hangye.'加盟频道。';
	            }elseif($areaid == 0 && $inid == 0 && $atype != 0 && $pmodel == 0){
	            	$touzi = $modProject->getQuestCont(7, $atype);
	            	$this->template->title = $strpage.'【'.$touzi.'项目加盟 | '.$touzi.'项目代理】 - 一句话商机网';
			    	$this->template->keywords = $touzi.'项目加盟，'.$touzi.'项目代理，一句话商机网';
			    	$this->template->description = '一句话商机网'.$touzi.'项目加盟频道有大量的'.$touzi.'加盟、'.$touzi.'代理信息，在这里你可以发布'.$touzi.'项目加盟、查看更多'.$touzi.'项目加盟、'.$touzi.'项目代理信息，选项目、发布项目，上一句话招商加盟频道。';
	            }elseif($areaid == 0 && $inid == 0 && $atype == 0 && $pmodel != 0){	            	
	            	$xingshi = $arr[$pmodel];
	            	$this->template->title = $strpage.'【'.$xingshi.'招商项目】 - 一句话商机网';
			    	$this->template->keywords = $xingshi.'招商项目，一句话商机网';
			    	$this->template->description = '一句话商机网'.$xingshi.'招商项目频道有大量的'.$xingshi.'招商项目信息，在这里你可以发布'.$xingshi.'招商项目、查看更多'.$xingshi.'招商项目信息，选项目、发布项目，上一句话'.$xingshi.'招商项目频加盟频道。';
	            }else{
		            if($areaid != 0){
	                    $cityName = $modProject->getQuestCont(2, $areaid);
	                }
	                if($inid != 0){
	                    $hangye = $modProject->getQuestCont(6,$inid);
	                }
	                if($atype != 0){
	                    $touzi = $modProject->getQuestCont(7, $atype);
	                }
	                if($pmodel != 0){
	                    $xingshi = $arr[$pmodel];
	                }
	                $this->template->title = $strpage.'【'.$cityName.$hangye.$touzi.$xingshi.'加盟】'.$cityName.$hangye.$touzi.$xingshi.'代理_一句话商机网';
			    	$this->template->keywords = $cityName.$hangye.$touzi.$xingshi.'加盟，'.$cityName.$hangye.$touzi.$xingshi.'代理，一句话商机网';
			    	$this->template->description = '一句话商机网'.$cityName.$hangye.$touzi.$xingshi.'加盟专区汇聚大量的'.$cityName.$hangye.$touzi.$xingshi.'招商加盟信息，有招商加盟信息发布、免费查询功能，找'.$cityName.$hangye.$touzi.$xingshi.'代理加盟项目信息，请关注'.$cityName.$hangye.$touzi.$xingshi.'加盟专区。';
	            }
            }else{
                $seoList = $service->getHotCityByIndex();                
            	$this->template->title = "全国招商加盟_全国代理加盟_项目招商加盟免费信息发布平台_一句话商机网";
		    	$this->template->keywords = "全国招商加盟，全国代理、全国加盟、代理加盟、项目发布，一句话商机网";
		    	$this->template->description = "一句话商机网招商加盟频道是全国项目招商加盟免费信息发布、搜索、查阅平台。Yjh商机网招商加盟频道有海量的、真实的、优质的商家发布的招商加盟、代理加盟项目供创业者选择加盟，商家发布项目、个人想创业，请关注一句话商机网招商加盟频道。";
            }
            
        }
        $searchresultPv = array();
        if(!$searchresultSearch) {
            if($is_search && !$word) {
                $searchresult_key = 'getQuickWordSearchAllList'.'_'.$_GET['page'];
                $searchresultSearch = $memcache->get($searchresult_key);
                if(!$searchresultSearch) {
                    $searchresultSearch = $search->getListOrderTime();
                    $memcache->set($searchresult_key, $searchresultSearch, 300);
                }
            }else{
                $searchresult_key = 'getQuickWordSearchPVList';
                $searchresultSearch = $memcache->get($searchresult_key);
                if(!$searchresultSearch) {
                    $searchresultSearch = $search->getListOrderPv();
                    $memcache->set($searchresult_key, $searchresultSearch, 86400);
                }
            }
        }
        $this->template->content = View::factory("quickPublish/Search_list");     
        $this->template->content->local_arr = $local_arr;    
        $this->template->content->word = $word;
        $this->template->word = $word;
        $this->template->content->isLogin = $isLogin;
        $this->template->content->industry = $industry;
        $this->template->content->pModel = $pModel;
        $this->template->content->areas = $areas;
        $this->template->content->cond = $cond;
        $this->template->content->condWord = isset($condWord) ? $condWord : '';
        $this->template->content->is_search = $is_search;
        $this->template->content->search_count = arr::get($searchresultSearch, 'search_count', 0);
        $this->template->content->searchresultPv = $searchresultPv;        
        $this->template->content->page = arr::get($searchresultSearch, 'page');
        $this->template->content->list = arr::get($searchresultSearch, 'list'); 
        $this->template->content->seoList = isset($seoList) ? $seoList : array(); 
        $this->template->content->advertList = $advertList; 
        
    }
    
    /**
     * 快速发布 项目向导
     * @author stone shi
     */
    public function action_index() {
        $isLogin = $this->isLogins();
        $industry = common::getIndustryList ();
        $service = new Service_QuickPublish_Project();
        $seoList = $service->getHotCityByIndex();
        $this->template->content = View::factory("quickPublish/Search_index");
        $this->template->content->industry = $industry;
        $this->template->content->isLogin = $isLogin;
        $this->template->content->seoList = $seoList;
        //var_dump($seoList);
        //$this->template->content->whereAreYou = array('name' => 'url');
    }
    /**
     * 快速发布广告跳转页
     * @author stone shi
     */
    public function action_advert() {
        $id = intval(arr::get($_GET, 'id', 0));
        $service = new Service_QuickPublish_Project();
        $info = $service->getQuickAdvertInfo($id);
        if(!arr::get($info, 'id', 0)) {
            self::redirect(URL::website('/'));
        }
        $this->template->content = View::factory("quickPublish/advert");
        $this->template->content->id = $id;
        $this->template->content->info = $info;
    }
}
