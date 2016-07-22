<?php

defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

/**
 * 项目向导
 *
 * @author 施磊
 *
 */
class Controller_Platform_ProjectGuide extends Controller_Platform_Template {
    private $_cache_get_approing_30_ranking = 'getApproing30Ranking';
    private $_cache_get_approing_7_ranking = 'getApproing7Ranking';
    private $_cache_get_watch_30_ranking = 'getWatch30Ranking';
    private $_cache_get_watch_7_ranking = 'getWatch7Ranking';
    private $_cache_get_click_30_ranking = 'getClick30Ranking';
    private $_cache_get_click_7_ranking = 'getClick7Ranking';
    private $_cache_get_new_project_ranking = 'getNewProjectRanking';
    private $_cache_woman = 'getwoman';
    private $_cache_student = 'getstudent';
    private $_cache_farmer = 'getfarmer';
    private $_cache_white = 'getwhite';
    private $_cache_get_total_time = 86400;
    //快速注册
    private $_cache_get_project_total   = 'getProjectTotal';
    private $_cache_get_user_total      = 'getUserTotal';

    /**
     * index
     *
     * @author 施磊
     */
    public function action_ProjectGuide() {
        $mod = new Service_Platform_ProjectGuide ();
        $memcache = Cache::instance ( 'memcache' );
        $get = Arr::map ( "HTML::chars", $this->request->query () );
        if (isset ( $get ['cache'] )) {
            $cache = $get ['cache'];
        }
        #最佳口碑数据
        try {
             if(isset($cache) && $cache == 0){
                 $appRanking = 0;
             }else{
                 $appRanking = $memcache->get($this->_cache_get_approing_30_ranking);
             }
        }catch (Cache_Exception $e){
            $appRanking = 0;
        }
        if($appRanking == 0){
            $multipletype = $mod->getMultipleList(10, 1, 1);
            $approing30list = $mod->getApproingRanking(10);
            $arr3 = $mod->getDisposeArr($multipletype, $approing30list,10);
            $appRanking = $mod->showNeedRanking($arr3);
        }
        #最受关注项目
        try {
             if(isset($cache) && $cache == 0){
                 $watchRanking = 0;
             }else{
                 $watchRanking = $memcache->get($this->_cache_get_watch_30_ranking);
             }
        }catch (Cache_Exception $e){
            $watchRanking = 0;
        }
        if($watchRanking == 0){
            $multipletype = $mod->getMultipleList(10, 2, 1);
            $watch30list = $mod->getWatchRanking(10);
            $arr3 = $mod->getDisposeArr($multipletype, $watch30list,10);
            $watchRanking = $mod->showNeedRanking($arr3);
        }
        // 有分类
        $industry = common::getIndustryList ();
        try {
            if (isset ( $cache ) && $cache == 0) {
                $woman = 0;
            } else {
                $woman = $memcache->get ( $this->_cache_woman );
            }
        } catch ( Cache_Exception $e ) {
            $woman = 0;
        }
        if ($woman == 0) {
            $woman = $mod->getProjectRecommend ( 4 );
            $memcache->set ( $this->_cache_woman, $woman, $this->_cache_get_total_time );
        }
        try {
            if (isset ( $cache ) && $cache == 0) {
                $student = 0;
            } else {
                $student = $memcache->get ( $this->_cache_student );
            }
        } catch ( Cache_Exception $e ) {
            $student = 0;
        }
        if ($student == 0) {
            $student = $mod->getProjectRecommend ( 3 );
            $memcache->set ( $this->_cache_student, $student, $this->_cache_get_total_time );
        }

        try {
            if (isset ( $cache ) && $cache == 0) {
                $farmer = 0;
            } else {
                $farmer = $memcache->get ( $this->_cache_farmer );
            }
        } catch ( Cache_Exception $e ) {
            $farmer = 0;
        }
        if ($farmer == 0) {
            $farmer = $mod->getProjectRecommend ( 6 );
            $memcache->set ( $this->_cache_farmer, $farmer, $this->_cache_get_total_time );
        }

        try {
            if (isset ( $cache ) && $cache == 0) {
                $white = 0;
            } else {
                $white = $memcache->get ( $this->_cache_white );
            }
        } catch ( Cache_Exception $e ) {
            $white = 0;
        }
        if ($white == 0) {
            $white = $mod->getProjectRecommend ( 5 );
            $memcache->set ( $this->_cache_white, $white, $this->_cache_get_total_time );
        }
        $content = View::factory ( "platform/projectGuide/projectguide" );
        $this->content->maincontent = $content;
        $this->template->title = "项目向导_创业项目向导_一句话";
        $this->template->keywords = "项目向导，创业项目向导，一句话";
        $this->template->description = "项目向导为您提供各种创业项目，按不同分类及各种标准、规范的分类。帮助您方便、快捷、正确的选择创业投资项目。一句话项目向导是您最好的选择。";
        $this->content->maincontent->appRanking = $appRanking;
        $this->content->maincontent->watchRanking = $watchRanking;
        $this->content->maincontent->industry = $industry;
        $this->content->maincontent->woman = arr::get ( $woman, 'list', array () );
        $this->content->maincontent->student = arr::get ( $student, 'list', array () );
        $this->content->maincontent->farmer = arr::get ( $farmer, 'list', array () );
        $this->content->maincontent->white = arr::get ( $white, 'list', array () );
    }

    /**
     * 显示排行榜
     * @author 郁政
     */
    public function action_showProjectRankingList(){
        $content = View::factory('platform/projectGuide/projectrankinglist');
        $this->content->maincontent = $content;
        $memcache = Cache::instance('memcache');
        $service = new Service_Platform_ProjectGuide();
        $ranking = array();
        $get = Arr::map("HTML::chars", $this->request->query());
        if(isset($get['cache'])){
            $cache = $get['cache'];
        }
          //最佳口碑项目排名cache
        try {
             if(isset($cache) && $cache == 0){
                 $approing_30_ranking = 0;
                 $approing_7_ranking = 0;
             }else{
                 $approing_30_ranking = $memcache->get($this->_cache_get_approing_30_ranking);
                $approing_7_ranking = $memcache->get($this->_cache_get_approing_7_ranking);
             }
        }catch (Cache_Exception $e){
            $approing_30_ranking = 0;
            $approing_7_ranking = 0;
        }
        if($approing_30_ranking == 0 || $approing_7_ranking == 0){
            //30天赞
            $multipletype = $service->getMultipleList(10, 1, 1);
            $approing30list = $service->getApproingRanking(10,30,3);
            $arr3 = $service->getDisposeArr($multipletype, $approing30list,10);
            $approing30list = $service->showNeedRanking($arr3);
            //7天赞
            $multipletype = $service->getMultipleList(10, 1, 2);
            $approing7list = $service->getApproingRanking(10,7,3);
            $arr3 = $service->getDisposeArr($multipletype, $approing7list,10);
            $approing7list = $service->showNeedRanking($arr3);
            $memcache->set( $this->_cache_get_approing_30_ranking, $approing30list, $this->_cache_get_total_time );
            $memcache->set( $this->_cache_get_approing_7_ranking, $approing7list, $this->_cache_get_total_time );
        }else{
            $approing30list = $approing_30_ranking;
            $approing7list = $approing_7_ranking;
        }
          //最受关注项目排名cache
        try {
             if(isset($cache) && $cache == 0){
                 $watch_30_ranking = 0;
                $watch_7_ranking = 0;
             }else{
                 $watch_30_ranking = $memcache->get($this->_cache_get_watch_30_ranking);
                 $watch_7_ranking = $memcache->get($this->_cache_get_watch_7_ranking);
             }
        }catch (Cache_Exception $e){
            $watch_30_ranking = 0;
            $watch_7_ranking = 0;
        }
        if($watch_30_ranking == 0 || $watch_7_ranking == 0){
            //30天关注
            $multipletype = $service->getMultipleList(10, 2, 1);
            $watch30list = $service->getWatchRanking(10,30,3);
            $arr3 = $service->getDisposeArr($multipletype, $watch30list,10);
            $watch30list = $service->showNeedRanking($arr3);
            //7天关注
            $multipletype = $service->getMultipleList(10, 2, 2);
            $watch7list = $service->getWatchRanking(10,7,3);
            $arr3 = $service->getDisposeArr($multipletype, $watch7list,10);
            $watch7list = $service->showNeedRanking($arr3);
            $memcache->set( $this->_cache_get_watch_30_ranking, $watch30list, $this->_cache_get_total_time );
            $memcache->set( $this->_cache_get_watch_7_ranking, $watch7list, $this->_cache_get_total_time );
        }else{
            $watch30list = $watch_30_ranking;
            $watch7list = $watch_7_ranking;
        }
          //最热项目排名cache
          try {
              if(isset($cache) && $cache == 0){
                $click_30_ranking = 0;
                $click_7_ranking = 0;
            }else{
                $click_30_ranking = $memcache->get($this->_cache_get_click_30_ranking);
                $click_7_ranking = $memcache->get($this->_cache_get_click_7_ranking);
            }
        }catch (Cache_Exception $e){
            $click_30_ranking = 0;
            $click_7_ranking = 0;
        }
        if($click_30_ranking == 0 || $click_7_ranking == 0){
            //30天PV
            $multipletype = $service->getMultipleList(10, 3, 1);
            $click30list = $service->getClickRanking(10,30,3);
            $arr3 = $service->getDisposeArr($multipletype, $click30list,10);
            $click30list = $service->showNeedRanking($arr3);
            //7天PV
            $multipletype = $service->getMultipleList(10, 3, 2);
            $click7list = $service->getClickRanking(10,7,3);
            $arr3 = $service->getDisposeArr($multipletype, $click7list,10);
            $click7list = $service->showNeedRanking($arr3);
            $memcache->set( $this->_cache_get_click_30_ranking, $click30list, $this->_cache_get_total_time );
            $memcache->set( $this->_cache_get_click_7_ranking, $click7list, $this->_cache_get_total_time );
        }else{
            $click30list = $click_30_ranking;
            $click7list = $click_7_ranking;
        }
          //最新项目排名cache
        try {
             if(isset($cache) && $cache == 0){
                 $new_project_ranking = 0;
             }else{
                 $new_project_ranking = $memcache->get($this->_cache_get_new_project_ranking);
             }
        }catch (Cache_Exception $e){
            $new_project_ranking = 0;
        }
        if($new_project_ranking == 0){
            $newprojectlist = $service->getNewProList(10);
            $newprojectlist = $service->showNeedRanking($newprojectlist['data']);
            $memcache->set( $this->_cache_get_new_project_ranking, $newprojectlist, $this->_cache_get_total_time );
        }else{
            $newprojectlist = $new_project_ranking;
        }
        //热门项目列表        
        $hotprojectlist = array();
        $hotprojectlist = $memcache->get('paihang_hot_project');
        if(!$hotprojectlist){
        	$hotproject = $service->getHardProByConfig(5); 
        	$hotprojectlist = $service->getNewHotListNeed($hotproject);
        	$memcache->set('paihang_hot_project',$hotprojectlist,7200);
        }           
        $content->hotprojectlist = $hotprojectlist;
        $this->template->title = "排行榜_创业最新排行榜_一句话商机速配网";
        $this->template->keywords = "创业排行榜，创业最新排行榜，一句话商机速配网";
        $this->template->description = "一句话商机速配网创业排行榜为您提供最佳口碑项目、最受关注项目最热项目、最新项目等项目排行，方便您更好的选择更好的创业项目！一句话生意网创业排行榜值得一看。";
        $content->approing30list = $approing30list;
        $content->approing7list = $approing7list;
        $content->watch30list = $watch30list;
        $content->watch7list = $watch7list;
        $content->click30list = $click30list;
        $content->click7list = $click7list;
        $content->newprojectlist = $newprojectlist;
        $this->template->xiangdaoshow = 2;
    }

    /**
     * 显示排行榜完整榜单
     *
     * @author 郁政
     */
    public function action_showRankingListDetail() {
        $content = View::factory ( 'platform/projectGuide/projectrankingdetail' );
        $this->content->maincontent = $content;
        $service = new Service_Platform_ProjectGuide ();
        $search = new Service_Platform_Search ();
        $data = Arr::map ( "HTML::chars", $this->request->query () );
        $type = intval ( Arr::get ( $data, "type" ) );
        $time = intval ( Arr::get ( $data, "time" ) );
        // 当前用户的userid
        $loginStatus = $this->isLogins ();
        $user_id = $loginStatus ? $this->userInfo ()->user_id : 0;
        
        //----个人快速注册开始
        $invest = new Service_User_Person_Invest();
        $pro = $invest->getArea();
        $content->area = $pro;
        $memcache          = Cache::instance('memcache');
        try {
        	$platform_num  = $memcache->get( $this->_cache_get_project_total );
        }
        catch (Cache_Exception $e) {
        	$platform_num  = 0;
        }
        
        if( $platform_num==0 ){
        	$browing            = new Service_Platform_Browsing();
        	$platform_num       = $browing->getProjectCount();
        	$memcache->set( $this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time );
        }
        
        //总用户数
        try {
        	$user_num  = $memcache->get( $this->_cache_get_user_total );
        }
        catch (Cache_Exception $e) {
        	$user_num  = 0;
        }
        if( $user_num==0 ){
        	$service_user = new Service_User();
        	$user_num           = $service_user->getRegUserNum();
        	$memcache->set( $this->_cache_get_user_total, $user_num, $this->_cache_get_total_time );
        }
        $content->user_num = $user_num;
        $content->platform_num =$platform_num;
        
        if ($type == 1) {
            if ($time != 1 && $time != 2) {
                $time = 1;
            }
            //30天赞
            $multipletype = $service->getMultipleList(20, 1, 1);
            $approing30list = $service->getApproingRanking ( 20 );
            $arr3 = $service->getDisposeArr($multipletype, $approing30list,20);
            $approing30list = $service->showNeedRanking ( $arr3 );
            //7天赞
            $multipletype = $service->getMultipleList(20, 1, 2);
            $approing7list = $service->getApproingRanking ( 20, 7 );
            $arr3 = $service->getDisposeArr($multipletype, $approing7list,20);
            $approing7list = $service->showNeedRanking ( $arr3 );
            $list_1 = $search->pushProjectInfo ( $approing30list, $user_id );
            //echo "<pre>"; print_r($list_1);exit;
            $list_2 = $search->pushProjectInfo ( $approing7list, $user_id );
            $content->list_1 = $list_1;
            $content->list_2 = $list_2;
            $content->user_id = $user_id;
            $content->loginStatus = $loginStatus;
            $content->type = $type;
            $content->time = $time;
            $this->template->title = "【最佳口碑项目排行】最佳口碑项目排行投资、创业、开店、加盟_一句话商机速配网";
            $this->template->keywords = "最佳口碑项目排行，项目加盟，项目投资，开店项目，一句话";
            $this->template->description = "创业排行榜为您提供最佳口碑项目排行，大量的最佳口碑项目排行投资、创业、开店、加盟信息方便您更好的选择更好的创业项目！";
        } elseif ($type == 2) {
            if ($time != 1 && $time != 2) {
                $time = 1;
            }
            //30天关注
            $multipletype = $service->getMultipleList(20, 2, 1);
            $watch30list = $service->getWatchRanking ( 20 );
             $arr3 = $service->getDisposeArr($multipletype, $watch30list,20);
            $watch30list = $service->showNeedRanking ( $arr3 );
            //7天关注
            $multipletype = $service->getMultipleList(20, 2, 2);
            $watch7list = $service->getWatchRanking ( 20, 7 );
             $arr3 = $service->getDisposeArr($multipletype, $watch7list,20);
            $watch7list = $service->showNeedRanking ( $arr3 );
            $list_1 = $search->pushProjectInfo ( $watch30list, $user_id );
            $list_2 = $search->pushProjectInfo ( $watch7list, $user_id );
            $content->list_1 = $list_1;
            $content->list_2 = $list_2;
            $content->user_id = $user_id;
            $content->loginStatus = $loginStatus;
            $content->type = $type;
            $content->time = $time;
            $this->template->title = "【最受关注项目排行】最受关注项目排行投资、创业、开店、加盟_一句话商机速配网";
            $this->template->keywords = "最受关注项目排行，项目加盟，项目投资，开店项目，一句话";
            $this->template->description = "创业排行榜为您提供最受关注项目排行，大量的最受关注项目排行投资、创业、开店、加盟信息方便您更好的选择更好的创业项目！";
        } elseif ($type == 3) {
            if ($time != 1 && $time != 2) {
                $time = 1;
            }
            //30天PV
            $multipletype = $service->getMultipleList(20, 3, 1);
            $click30list = $service->getClickRanking ( 20 );
             $arr3 = $service->getDisposeArr($multipletype, $click30list,20);
            $click30list = $service->showNeedRanking ( $arr3 );
            //7天PV
            $multipletype = $service->getMultipleList(20, 3, 2);
            $click7list = $service->getClickRanking ( 20, 7 );
             $arr3 = $service->getDisposeArr($multipletype, $click7list,20);
            $click7list = $service->showNeedRanking ( $arr3 );
            $list_1 = $search->pushProjectInfo ( $click30list, $user_id );
            $list_2 = $search->pushProjectInfo ( $click7list, $user_id );
            $content->list_1 = $list_1;
            $content->list_2 = $list_2;
            $content->user_id = $user_id;
            $content->loginStatus = $loginStatus;
            $content->type = $type;
            $content->time = $time;
            $this->template->title = "【最热项目排行】最热项目排行投资、创业、开店、加盟_一句话商机速配网";
            $this->template->keywords = "最热项目排行，项目加盟，项目投资，开店项目，一句话";
            $this->template->description = "创业排行榜为您提供最热项目排行，大量的最热项目排行投资、创业、开店、加盟信息方便您更好的选择更好的创业项目！";
        } elseif ($type == 4) {
            $newprojectlist = $service->getNewProList(20);
            $newprojectlist = $service->showNeedRanking($newprojectlist['data']);
            $list_1 = $search->pushProjectInfo ( $newprojectlist, $user_id );
            $content->list_1 = $list_1;
            $content->user_id = $user_id;
            $content->loginStatus = $loginStatus;
            $content->type = $type;
            $this->template->title = "【最新项目排行】最新项目排行投资、创业、开店、加盟_一句话商机速配网";
            $this->template->keywords = "最新项目排行，项目加盟，项目投资，开店项目，一句话";
            $this->template->description = "创业排行榜为您提供最新项目排行，大量的最新项目排行投资、创业、开店、加盟信息方便您更好的选择更好的创业项目！";
        } else {
            self::redirect ( URL::website ( 'platform/projectGuide/showProjectRankingList' ) );
        }
        $this->template->xiangdaoshow = 2;
    }

    /**
     * 项目大全分类页
     *
     * @author 施磊
     */
    public function action_ProjectGuideIndustry() {  
        $service = new Service_Platform_Search ();
        $modProject = new Service_Platform_Project ();
        $mod = new Service_Platform_ProjectGuide ();
        $Search = new Service_Api_Search();
        $memcache = Cache::instance('memcache');
        $loginStatus = $this->isLogins ();
        $user_id = $loginStatus ? $this->userInfo ()->user_id : 0;
        $arr_list ['result'] = array ();
        $condName = array ();
    	// 业id
        $cond ['industry_id'] = intval ( arr::get ( $_GET, 'inid', 0 ) );
        $cond ['pid'] = $modProject->getIndustryPid ( $cond ['industry_id'] );
        $cond ['inid'] = intval ( arr::get ( $_GET, 'inid', 0 ) );
        if ($cond ['inid']) {
            $arr_list ['result'] [6] = $cond ['inid'];
            $condName ['inid'] = $modProject->getQuestCont ( 6, $cond ['inid'] );
        }
        // 资金额
        $cond ['project_amount_type'] = intval ( arr::get ( $_GET, 'atype', 0 ) );
        $cond ['atype'] = intval ( arr::get ( $_GET, 'atype', 0 ) );
        if ($cond ['atype']) {
            $arr_list ['result'] [7] = $cond ['atype'];
            $condName ['atype'] = $modProject->getQuestCont ( 7, $cond ['atype'] );
        }        
        // 商形式
        $cond ['project_model'] = intval ( arr::get ( $_GET, 'pmodel', 0 ) );
        $cond ['pmodel'] = intval ( arr::get ( $_GET, 'pmodel', 0 ) );
        if ($cond ['pmodel']) {
            $arr_list ['result'] [1] = $cond ['pmodel'];
            $condName ['pmodel'] = $modProject->getQuestCont ( 1, $cond ['pmodel'] );
        }
        $guideNumb = $memcache->get(json_encode($cond));
        if(!$guideNumb) {
            $guideNumb = $Search->getGuideNumb($cond);
            $memcache->set(json_encode($cond), $guideNumb, $this->_cache_get_total_time );
        }
        $res = $memcache->get(json_encode($cond).'_result');
        if ($arr_list ['result']) {
            if(!$res) {
                $res = $service->getQueryCondition ( $arr_list );
                $memcache->set(json_encode($cond).'_result', $res, $this->_cache_get_total_time );
            }
            if(arr::get ( $_GET, 'debug', 0)) var_dump($arr_list, $res);
        }
        $serviceProject = new Service_Platform_Project ();
        // 有分类
        $industry = common::getIndustryList ();
        $resList = array (
                'list' => array (),
                'page' => ''
        );
        $pidList = array (
                'list' => array (),
                'page' => ''
        ); 
        //判断是否没选任何条件
        $isDefault = false;
        if($cond['project_amount_type'] == 0 && $cond['industry_id'] == 0 && $cond['project_model'] == 0){
        	$isDefault = true;
        }else{
        	$isDefault = false;
        }
        //热门项目列表       
        $industry_name = $mod->getIndustryNameById($cond['industry_id']);
        if($cond['industry_id'] != 0){ 
        	if($cond['pid'] == 0){
        		$industry_id = $cond['inid'];
        	}else{
        		$industry_id = $cond['pid'];
        	}        	       	
        	$hotprojectlist = array();
	        $hotprojectlist = $memcache->get('industry_hot_project_'.$industry_id);
	        if(!$hotprojectlist){
		        $hotproject = $mod->getProForIndustry(5,$industry_id);		        
	        	$hotprojectlist = $mod->getNewHotListNeed($hotproject);
	        	$memcache->set('industry_hot_project_'.$industry_id,$hotprojectlist,7200); 
	        }   		
        }else{
        	$hotprojectlist = array();
	        $hotprojectlist = $memcache->get('daohang_hot_project');
	        if(!$hotprojectlist){
	        	$hotproject = $mod->getHardProByConfig(5); 
	        	$hotprojectlist = $mod->getNewHotListNeed($hotproject);
	        	$memcache->set('daohang_hot_project',$hotprojectlist,7200);
	        }    
        }        
        //该变量记录是否有查找结果
        $hasRes = 0;
        //排序方式
        $get = Arr::map("HTML::chars", $this->request->query());
        $type = intval(Arr::get($_GET, 'type',0));
    	$order = intval(Arr::get($_GET, 'order',0));
    	$page = intval(Arr::get($get, 'page',0));
        if ($res ['result'] || (! $cond ['atype'] && ! $cond ['industry_id'] && ! $cond ['pmodel'])) {
        	$memcacheGuide1 = $memcache->get('project_guide1_'.$cond['inid'].'_'.$cond['atype'].'_'.$cond['pmodel'].'_'.$type.'_'.$order.'_'.$page);
        	if($memcacheGuide1){
        		$resList = $memcacheGuide1;
        	}else{
        		$resList = $serviceProject->getProjectByIdsPage ( $res ['result'], $user_id, $type, $order );
        		
           		$memcache->set('project_guide1_'.$cond['inid'].'_'.$cond['atype'].'_'.$cond['pmodel'].'_'.$type.'_'.$order.'_'.$page, $resList,86400);
        		
        	} 
        }else{
        	$resList = $serviceProject->showListByNoResult($user_id);
        	$hasRes = 1;    
        }
       //echo "<pre>"; print_r($resList);exit;
        if ($cond ['pid'] && $cond ['industry_id']) {
            $arr_list ['result'] [6] = $cond ['pid'];
            $pidRes = $service->getQueryCondition ( $arr_list );
            if ($pidRes ['result']) {
            	$memcacheGuide2 = $memcache->get('project_guide2_'.$cond['inid'].'_'.$cond['atype'].'_'.$cond['pmodel'].'_'.$type.'_'.$order.'_'.$page);
	        	if($memcacheGuide2){
	        		$pidList = $memcacheGuide2;
	        	}else{
                	$pidList = $serviceProject->getProjectByIdsPage ( $pidRes ['result'], $user_id, $type, $order );
                	
                	$memcache->set('project_guide2_'.$cond['inid'].'_'.$cond['atype'].'_'.$cond['pmodel'].'_'.$type.'_'.$order.'_'.$page, $pidList,86400);
                	
	        	}
            }else{       	
            	$pidList = $serviceProject->showListByNoResult($user_id);            	       	
            }
        }        
        $project_industry_id = null;
        $content = View::factory ( "platform/projectGuide/pgindustry" );
        $service_project =  new  Service_User_Company_Project();
        // 断行业id
        if ($cond ['industry_id'] == 0) {
            $project_industry_id = 8;
        } elseif ($cond ['industry_id'] <= 7) {
            $project_industry_id = $cond ['industry_id'];
        } elseif ($cond ['industry_id'] >= 8) {
            $project_industry_id = $cond ['pid'];
        }
       $array_name = array (
                '8' => 'all_industry',
                '1' => 'canyin_industry',
                '2' => 'liansuo_industry',
                '3' => 'fushi',
                '4' => 'jiaju_industry',
                '5' => 'jiaoyu_industry',
                '6' => 'jiushui_industry',
                '7' => 'xinqitei_industry'
        );

       $industryName = " ";
        foreach ($array_name as $key=>$val){
            if($key == $project_industry_id){
                $industryName = $val;
            }
        }
        #从memcache 中拿取数据
        
        $memcacheList = $memcache->get($industryName);
        #如果$memcache中有的话
        if($memcacheList){
            $show_project_industry = $memcacheList;
        }else{
            $project_industry = $mod->getProjectByIndustryId ( intval ( $project_industry_id ), 6 );
            $show_project_industry = $mod->disposeProjectIndustry ( $project_industry, $industryName);
        }
        $this->content->maincontent = $content;
        //设置title，keyword，description  
        $strpage = ""; 
    	if($page == 0){
            $strpage = "";
        }else{
            $strpage = '第'.$page.'页';
        }     
        $inid = intval(Arr::get($get, 'inid',0));
        $pid = $serviceProject->getIndustryPid($inid);
        $atype = intval(Arr::get($get, 'atype',0));
    	if($atype == ""){
                $atype = 0;
            }
            $pmodel = intval(Arr::get($get, 'pmodel',0));
            if($pmodel == ""){
                $pmodel = 0;
            }
        if(!($pid == 0 && $inid == 0 && $atype == 0 && $pmodel == 0)){
            $hangye1 = "";
            $hangye2 = "";
            $touzi = "";
            $xingshi = "";
            if($pid == 0 && $inid == 0 && $atype == 0 && $pmodel != 0){
                $xingshi = $modProject->getQuestCont(1, $pmodel);
                $this->template->title = $strpage."【".$xingshi."】".$xingshi."项目投资加盟_一句话商机速配网";
                $this->template->keywords = $xingshi."，".$xingshi."项目投资加盟，".$xingshi."投资，".$xingshi."加盟";
                $this->template->description = $strpage.$xingshi."创业、".$xingshi."投资、".$xingshi."加盟，上一句话生意网，可以找到合适的".$xingshi."投资创业项目。";
            }elseif($pid == 0 && $inid == 0 && $atype != 0 && $pmodel == 0){
                $touzi = $modProject->getQuestCont(7, $atype);
                $this->template->title = $strpage."【".$touzi."项目投资加盟】".$touzi."创业、投资、加盟_一句话商机速配网";
                $this->template->keywords = $touzi."创业，".$touzi."投资，".$touzi."加盟,一句话商机速配网";
                $this->template->description = $strpage.$touzi."项目投资加盟，".$touzi.'创业、'.$touzi."投资、".$touzi."加盟，上一句话生意网，可以找到合适您预算的投资创业项目。";
            }elseif($pid ==0 && $inid != 0 && $atype == 0 && $pmodel == 0){
                $hangye1 = $modProject->getQuestCont(6,$inid);
                $this->template->title = $strpage."【".$hangye1."加盟】".$hangye1."投资_".$hangye1."开店_一句话商机网";
                $this->template->keywords = $hangye1."，".$hangye1."加盟，".$hangye1."投资，一句话商机网";
                $this->template->description = $strpage.$hangye1."加盟项目是一句话商机速配网为用户精心打造、推送的".$hangye1."加盟信息，大量的".$hangye1."加盟项目均为正规企业上传，选择".$hangye1."加盟、投资、开店就上一句话商机网。我们为您的".$hangye1."加盟投资提供全面的投资保障，让您的创业投资更安全、更可靠、更容易成功！";
            }elseif($pid !=0 && $inid != 0 && $atype == 0 && $pmodel == 0){
                $hangye2 = $modProject->getQuestCont(6,$inid);
                $this->template->title = $strpage."【".$hangye2."加盟】".$hangye2."开店_".$hangye2."投资_一句话商机网";
                $this->template->keywords = $hangye2."，".$hangye2."加盟，".$hangye2."投资，一句话商机网";
                $this->template->description = $strpage.$hangye2."加盟项目是一句话商机速配网为用户精心打造、推送的".$hangye2."加盟信息，大量的".$hangye2."加盟项目均为正规企业上传，选择".$hangye2."加盟、投资、开店就上一句话商机网。";
            }else{
                if($pid != 0){
                    $hangye1 = $modProject->getQuestCont(6,$pid);
                }
                if($inid != 0){
                    $hangye2 = $modProject->getQuestCont(6,$inid);
                }
                if($atype != 0){
                    $touzi = $modProject->getQuestCont(7, $atype);
                }
                if($pmodel != 0){
                    $xingshi = $modProject->getQuestCont(1, $pmodel);
                }
                $this->template->title = $strpage."【".$hangye1.$hangye2.$touzi.$xingshi."】投资、创业、加盟_一句话商机速配网";
                $this->template->keywords = $hangye1.$hangye2.$touzi.$xingshi."，".$hangye1.$hangye2.$touzi.$xingshi."创业，".$hangye1.$hangye2.$touzi.$xingshi."投资，".$hangye1.$hangye2.$touzi.$xingshi."加盟";
                $this->template->description = $strpage.$hangye1.$hangye2.$touzi.$xingshi."创业、".$hangye1.$hangye2.$touzi.$xingshi."投资、".$hangye1.$hangye2.$touzi.$xingshi."加盟，上一句话投资加盟平台，大量的投资项目任你挑！选投资项目上一句话商机速配网。";
            }
        }else{
            $this->template->title = $strpage."分类导航_创业分类导航_一句话商机速配网";
            $this->template->keywords = "创业选择,创业分类导航,一句话商机速配网";
            $this->template->description = "一句话商机速配网".$strpage."创业分类导航为您提供各种分类的项目，涉及到餐饮娱乐、连锁零售、服饰箱包、家居建材、教育培训、酒水饮料、新奇特、投资金额、招商形式等不同的分类，一句话创业分类导航是不错的选择。";
        }
        
        //----个人快速注册开始
        $invest = new Service_User_Person_Invest();
        $pro = $invest->getArea();
        $content->area = $pro;
        $memcache          = Cache::instance('memcache');
        try {
        	$platform_num  = $memcache->get( $this->_cache_get_project_total );
        }
        catch (Cache_Exception $e) {
        	$platform_num  = 0;
        }
        if( $platform_num==0 ){
        	$browing            = new Service_Platform_Browsing();
        	$platform_num       = $browing->getProjectCount();
        	$memcache->set( $this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time );
        }
        
        //总用户数
        try {
        	$user_num  = $memcache->get( $this->_cache_get_user_total );
        }
        catch (Cache_Exception $e) {
        	$user_num  = 0;
        }
        if( $user_num==0 ){
        	$service_user = new Service_User();
        	$user_num           = $service_user->getRegUserNum();
        	$memcache->set( $this->_cache_get_user_total, $user_num, $this->_cache_get_total_time );
        }
        $content->user_num = $user_num;
        $content->platform_num =$platform_num;
        
        $this->content->maincontent->industry = $industry;
        $this->content->maincontent->cond = $cond;
        $this->content->maincontent->condName = $condName;
        $this->content->maincontent->project_list = $resList;
        $this->content->maincontent->pidlist = $pidList;
        $this->content->maincontent->user_id = $user_id;
        $this->content->maincontent->loginStatus = $loginStatus;
        $this->content->maincontent->projectIndustry = $show_project_industry;
        $this->content->maincontent->guideNumb = $guideNumb;
        $this->template->xiangdaoshow = 2;
        $this->content->maincontent->hasRes = $hasRes;
        $content->type = $type;
        $content->order = $order;
        $content->hotprojectlist = $hotprojectlist;
        $content->industry_name = $industry_name;
        $content->isDefault = $isDefault;
    }

    /**
     * 按投资人群查项目
     *
     * @author 嵇烨
     */
    public function action_getProjectListByCorwd() {
        $model = new Service_Platform_ProjectGuide ();
        $content = View::factory ( "platform/projectGuide/projectcrowd" );
        $data = Arr::map ( "HTML::chars", $this->request->query () );
        $type = intval ( arr::get ( $data, "type" ) );
    	$page = intval(Arr::get($data, 'page',0));
        if($page == 0){
            $strpage = "";
        }else{
            $strpage = '第'.$page.'页';
        } 
        if ($type > 4) {
            $type = 1;
        }
        //设置title,keywords,description
        if($type == 0){
            $this->template->title = $strpage."按人群找投资项目_按人群找创业项目_一句话商机速配网";
            $this->template->keywords = "创业项目,按人群找项目,一句话商机速配网";
            $this->template->description = $strpage."一句话生意网提供按人群找创业项目，提供女性创业首先项目、大学生创业首先项目、农民创业首选项目、白领创业首选项目等。一句话招商平台拥有大量的投资群体、创业群体，在这里一定有适合您创业的项目。";
        }elseif($type == 1){
            $this->template->title = $strpage."【女性创业首选项目】_女性创业首选项目创业、投资、加盟_一句话商机速配网";
            $this->template->keywords = "女性创业首选项目，女性创业首选项目创业，女性创业首选项目投资，女性创业首选项目加盟，一句话商机速配网";
            $this->template->description = $strpage."一句话生意网提供按人群找创业项目，提供女性创业首选项目创业、投资、加盟机会，大量的加盟项目推荐给有创业需求的你。找女性创业首选项目创业、投资、加盟上一句话商机速配平台最好。";
        }elseif($type == 2){
            $this->template->title = $strpage."【大学生创业首选项目】_大学生创业首选项目创业、投资、加盟_一句话商机速配网";
            $this->template->keywords = "大学生创业首选项目，大学生创业首选项目创业，大学生创业首选项目投资，大学生创业首选项目加盟，一句话商机速配网";
            $this->template->description = $strpage."一句话投资赚钱平台提供按人群找创业项目，提供大学生创业首选项目创业、投资、加盟机会，大量的加盟项目推荐给有创业需求的你。找大学生创业首选项目创业、投资、加盟上一句话生意网最好。";
        }elseif($type == 3){
            $this->template->title = $strpage."【农民创业首选项目】_农民创业首选项目创业、投资、加盟_一句话商机速配网";
            $this->template->keywords = "农民创业首选项目，农民创业首选项目创业，农民创业首选项目投资，农民创业首选项目加盟，一句话商机速配网";
            $this->template->description = $strpage."一句话投资赚钱平台提供按人群找创业项目，提供农民创业首选项目创业、投资、加盟机会，大量的加盟项目推荐给有创业需求的你。找农民创业首选项目创业、投资、加盟上一句话生意网最好。";
        }elseif($type == 4){
            $this->template->title = $strpage."【白领创业首选项目】_白领创业首选项目创业、投资、加盟_一句话商机速配网";
            $this->template->keywords = "白领创业首选项目，白领创业首选项目创业，白领创业首选项目投资，白领创业首选项目加盟，一句话商机速配网";
            $this->template->description = $strpage."一句话投资赚钱平台提供按人群找创业项目，提供白领创业首选项目创业、投资、加盟机会，大量的加盟项目推荐给有创业需求的你。找白领创业首选项目创业、投资、加盟上一句话生意网最好。";
        }
        $projectcorwd_id = null;
        // 投资人群id数组 4->女性 3->大学生 6->农民 5->白领
        $projectcorwd = array (
                '1' => 4,
                '2' => 3,
                '3' => 6,
                '4' => 5
        );
        foreach ( $projectcorwd as $key => $val ) {
            if ($type == $key) {
                $projectcorwd_id = $val;
            }
        }
        $loginStatus = $this->isLogins ();
        // 当前用用户的id
        $user_id = $loginStatus ? $this->userInfo ()->user_id : 0;
        if ($projectcorwd_id > 0) {
            // 根据投资人群的id获取项目信息
            $projectList = $model->getProjectListByCrowdId ( intval ( $projectcorwd_id ), 20, intval ( $user_id ) );
        } else {
            // 没有投资人群的id时 默认是 女性创业
            $projectList = $model->getProjectListByCrowdId ( intval ( 4 ), 20, intval ( $user_id ) );
        }
        $memcache  = Cache::instance('memcache');
        //热门项目列表        
        $hotprojectlist = array();
        $hotprojectlist = $memcache->get('renqun_hot_project');
        if(!$hotprojectlist){
        	$hotproject = $model->getHardProByConfig(5); 
        	$hotprojectlist = $model->getNewHotListNeed($hotproject);
        	$memcache->set('renqun_hot_project',$hotprojectlist,7200);
        }    
        //----个人快速注册开始
        $invest = new Service_User_Person_Invest();
        $pro = $invest->getArea();
        $content->area = $pro;        
        try {
        	$platform_num  = $memcache->get( $this->_cache_get_project_total );
        }
        catch (Cache_Exception $e) {
        	$platform_num  = 0;
        }
        
        if( $platform_num==0 ){
        	$browing            = new Service_Platform_Browsing();
        	$platform_num       = $browing->getProjectCount();
        	$memcache->set( $this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time );
        }
        
        //总用户数
        try {
        	$user_num  = $memcache->get( $this->_cache_get_user_total );
        }
        catch (Cache_Exception $e) {
        	$user_num  = 0;
        }
        if( $user_num==0 ){
        	$service_user = new Service_User();
        	$user_num           = $service_user->getRegUserNum();
        	$memcache->set( $this->_cache_get_user_total, $user_num, $this->_cache_get_total_time );
        }
        $content->user_num = $user_num;
        $content->platform_num =$platform_num;
        
        $content->loginStatus = $loginStatus;
        $content->projectList = $projectList;
        $content->user_id = $user_id;
        $content->type = $type;
        $this->content->maincontent = $content;
        $this->template->xiangdaoshow = 2;
        $content->hotprojectlist = $hotprojectlist;
    }

    /**
     * 按投资地区查找项目
     *
     * @author 嵇烨
     */
    public function action_getProjectListByArea() {
        $model = new Service_Platform_ProjectGuide ();
        $citymodel = new Service_Platform_Project ();
        $arr_area = Arr::map ( "HTML::chars", $this->request->query () );
        $memcache = Cache::instance('memcache');
        $page = arr::get($arr_area, "page",1);
        $key = "AreaList";
        // 取Cookie 地区的id
        $cookie_area_id = Cookie::get ( 'area_id' );
        if (empty ( $cookie_area_id )) {
            $area_id = 88;
        } else {
            $area_id = $cookie_area_id;
        }
        $area_id = isset ( $arr_area ['areaid'] ) ? $arr_area ['areaid'] : $area_id;
        // 取地区名称
        $cityName = $citymodel->getQuestCont ( 2, $area_id );
        // 置cookie 地区的id(一个月)
        Cookie::set ( 'area_id', $area_id, time () + intval ( 2592000 ) );
        $content = View::factory ( "platform/projectGuide/projectarea" );
        $loginStatus = $this->isLogins ();
        // 当前用户的id
        $user_id = $loginStatus ? $this->userInfo ()->user_id : 0;
      	$projectList = $memcache->get($key."-".$area_id."-".$page);
       	if(empty($projectList)){
       		$projectList = $model->getProjectListByCityId ( intval ( $area_id ), 20, $user_id );
       		// $array['total_count'] = $count;
       		//推进缓存
       		$memcache->set($key."-".$area_id."-".$page, $projectList,3600*2);
       	}
        if ($projectList == false || empty($projectList['list'])) {
            $projectList = $model->getProjectListByCityId ( intval ( 88 ), 10, $user_id, 1 );
            $content->isshow = 1;
        }
        $content->loginStatus = $loginStatus;
        $content->projectList = $projectList;
        $content->user_id = $user_id;
        $area = array (
                'pro_id' => 0
        );
        $content->areas = common::arrArea ( $area );
        $content->cityName = $cityName;
        $this->content->maincontent = $content;
        
        $memcache = Cache::instance('memcache');
    	//热门项目列表      
    	$hotprojectlist = array();
        $hotprojectlist = $memcache->get('diqu_hot_project');
        if(!$hotprojectlist){
        	$hotproject = $model->getHardProByConfig(5); 
        	$hotprojectlist = $model->getNewHotListNeed($hotproject);
        	$memcache->set('diqu_hot_project',$hotprojectlist,7200);
        }    
        $content->hotprojectlist = $hotprojectlist;
        
        //----个人快速注册开始
        $invest = new Service_User_Person_Invest();
        $pro = $invest->getArea();
        $content->area = $pro;        
        try {
        	$platform_num  = $memcache->get( $this->_cache_get_project_total );
        }
        catch (Cache_Exception $e) {
        	$platform_num  = 0;
        }
        
        if( $platform_num==0 ){
        	$browing            = new Service_Platform_Browsing();
        	$platform_num       = $browing->getProjectCount();
        	$memcache->set( $this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time );
        }
        
        //总用户数
        try {
        	$user_num  = $memcache->get( $this->_cache_get_user_total );
        }
        catch (Cache_Exception $e) {
        	$user_num  = 0;
        }
        if( $user_num==0 ){
        	$service_user = new Service_User();
        	$user_num           = $service_user->getRegUserNum();
        	$memcache->set( $this->_cache_get_user_total, $user_num, $this->_cache_get_total_time );
        }
        $content->user_num = $user_num;
        $content->platform_num =$platform_num;
        
        //设置title,keywords,description
        $page = intval(Arr::get($arr_area, 'page',0));
        if($page == 0){
            $strpage = "";
        }else{
            $strpage = '第'.$page.'页';
        } 
        if($area_id == 88){
            $this->template->title = $strpage."按地区找_按地区找创业项目_一句话商机速配网";
            $this->template->keywords = "创业项目，按地区找项目，一句话商机速配网";
            $this->template->description = $strpage."一句话生意网提供按地区找项目，提供全国30多个省市地区的创业项目及创业加盟信息和加盟方法。一句话够便捷、够方便，想创业就上一句话商机速配网。";
        }else{
            $this->template->title = $strpage."【".$cityName."】".$cityName."创业、加盟、投资_一句话商机速配网";
            $this->template->keywords = $cityName."，".$cityName."创业，".$cityName."加盟，".$cityName."投资，一句话商机速配网";
            $this->template->description = $strpage."一句话生意网提供全国范围内的创业投资项目，".$cityName."创业，".$cityName."加盟，".$cityName."投资，一句话就够了，上一句话找".$cityName."创业，".$cityName."加盟，".$cityName."投资最便捷。";
        }
        $this->template->xiangdaoshow = 2;
    }


    #跑图片脚本
    /*public function action_projectPro(){
     $count = ORM::factory("Project")->where("project_source", "=", "5")->count_all();
        #每次跑100条数据
        $begin = 0;
        $end = 100;
        $now = 0;
        if ($count > 0) {
            for ($i = $count; $i > 0;) {
                $arr_res = DB::query(Database::SELECT, "SELECT project_id,outside_id FROM czzs_project where czzs_project.project_source = 5 limit {$begin},{$end}")->execute()->as_array();
                $begin = $begin + 100;
                $i = $i - 100;
                $this->_updateImageList($arr_res);
            }
        }
    }


    public function _updateImageList($arr_res){
    foreach ($arr_res as $key=>$val){
            #外采的id
            $int_outside_id = $val['outside_id'];
            #项目的id
            $int_project_id = $val['project_id'];
            #查询czzs_test_project表并获取项目图片(外采的)
            $arr_outProjectImageList = DB::query(Database::SELECT, "SELECT `pro_image` FROM czzs_test_project where czzs_test_project.outside_id = {$int_outside_id}")->execute()->as_array();
            if(!empty($arr_outProjectImageList[0]['pro_image'])){
                $arr_imageList = @array_unique(explode(",", $arr_outProjectImageList[0]['pro_image']));
                $certModel = ORM::factory("Projectcerts");
                #删除相同的项目id的图片
                $models = $certModel->where('project_id','=',$int_project_id)->find_all();
                if(count($models) >0){
                    foreach ($models as $m){
                        $m->delete();
                    }
                }
                    #写入数据库
                    foreach ($arr_imageList as $k=>$v){
                        $certModel->project_id = $int_project_id;
                        $certModel->project_type = 1;
                        $certModel->project_img = "poster/html/ps_".$int_outside_id."/".$v;
                        $certModel->project_addtime = time();
                        $certModel->create();
                        $certModel->clear();
                    }
                }
        }
        unset($arr_res);
    }*/

    #导出外采项目脚本
    public function action_getOutProject(){
        #获取总数
        $count = ORM::factory("Project")->where("project_status","=",2)->count_all();
        #分页跑
         $page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' =>500
        ) );
        #获取项目信息
        $projectList = ORM::factory("Project")->limit ( $page->items_per_page )->offset ( $page->offset )->where("project_status","=",2)->find_all()->as_array();
        #导出数组
        $arr_out = array();
        foreach ($projectList as $key=>$val){
            try {
            #项目id
            $arr_data[$key]['project_id'] = $val->project_id;
            #项目名称
            $arr_data[$key] ['project_brand_name']= $val->project_brand_name;
            #企业联系人
            $arr_data[$key] ['project_contact_people']= $val->project_contact_people;
            #项目人手机号码
            $arr_data[$key] ['project_handset'] = $val->project_handset;
            #项目联系人座机号码
            $arr_data[$key] ['project_phone'] =  $val->project_phone;
            #企业名称
            $comInfo = $this->_getComInfo($val->com_id);
            $arr_data[$key] ['com_name'] = $comInfo['com_name'];
            #企业网址
            $arr_data[$key]['com_site'] = $comInfo['com_site'];
            #企业号码
            $arr_data[$key]['com_phone'] = $comInfo['com_phone'];
            if($arr_data[$key]['com_phone']){
            	$new_moble = common::decodeMoible($arr_data[$key]['com_phone']);
            	if($new_moble){
            		$arr_data[$key]['com_phone'] = $new_moble;
            	}
            }
            $userInfo = $this->_getUserInfo($comInfo['com_user_id']);
            #用户账号
            $arr_data[$key]['email']= $userInfo['email'];
            $arr_out=$arr_data;
            }catch ( ErrorException $e){
                echo $val->project_id;exit;
            }
        }

        #导出开始
        header('Content-Type: text/xls');
        header ( "Content-type:application/vnd.ms-excel;charset=utf-8" );
         $str = mb_convert_encoding("外采信息", 'gbk', 'utf-8');
         header('Content-Disposition: attachment;filename="' .$str . '.xls"');
         header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
         header('Expires:0');
         $table_data = '<table border="1">';
         $table_data .='<tr><td>'.mb_convert_encoding("项目ID", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目名称", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目联系人", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目联系人手机号码", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业联系人座机号码", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业名称", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业网址", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业号码", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业在一句话平台的账号", 'gbk', 'utf-8').'</td>';
         foreach ($arr_out as $line)
         {
             $table_data .= '<tr>';
             foreach ($line as $key => &$item)
             {
                 $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                 $table_data .= '<td>' . $item . '</td>';
             }
             $table_data .= '</tr>';
         }
         $table_data .='</table>';
         echo $table_data;
         die();
    }
    #获取企业信息
    private function _getComInfo($com_id){
        $model = ORM::factory("Usercompany",intval($com_id))->as_array();
        return $model;
    }
    #获取用户信息
    private function _getUserInfo($user_id){
        $model = ORM::factory("User",intval($user_id))->as_array();
        return  $model;
    }

/*
    #纠正peoject表的peoject_tag字段数据
    protected function action_updateprojectTag (){
        $count = DB::select()->from('Project')->where("project_status", "=", "2")->execute()->count();
        // echo $count;exit;
        #每次跑100条数据
        if ($count > 0) {
            for ($i=0 ;$i<= $count/100; $i++) {
                $arr_res = DB::select("*")->from('Project')->where('project_status', '=', 2)->limit(100)->offset($i)->execute()->as_array();
                $this->_doUpdateProjectTag($arr_res);
            }
        }
    }

    #修改字段
    protected function action_doUpdateProjectTag($arr_data){
    $str_data = null;
        foreach ($arr_data as $key=>$val){
        if(empty($val['project_tags'])){
            try{
            #拼接项目名称
            $str_data .=$val['project_brand_name'].",";
                #拼接项目投资金额
                $str_data .=$this->_get_project_amount_type($val['project_amount_type']);
                #拼接招商地区
                $str_data .=$this->_get_project_are($val['project_id']);
                #拼接招商行业
                $str_data .=$this->_get_project_industry($val['project_id']);
                #获取招商形式
                $str_data .=$this->_get_project_model($val['project_id']);
                #获取人脉关系
                $str_data .=$this->_get_Project_Connection($val['project_id']);
                #拼接自定义标签
                $str_data .=$this->_get_project_tag($val['project_id']);
                #拼接投资风险
                $str_data .=$this->_get_risk($val['risk']);
                #执行修改
                $model = $project_molde = ORM::factory("Project",$val['project_id']);
                $model->project_tags = $str_data;
                        $model->project_status = 8;
                        $model->update();
                        $model->clear();
                        $str_data = null;  }catch (Error $e){
                        echo $val['project_id'];exit;
                        }
                        }else {
                        continue;
            }
        }
            }
                #获取项目自定义标签
                protected function _get_project_tag($int_project_id){
                $str_name =null;
                if($int_project_id){
                $ormModel = ORM::factory('Projecttag')->where('project_id', '=', $int_project_id)->find_all();
                        $tagModel = ORM::factory('tag')->find_all();
                        foreach ($ormModel as $key=>$val) {
                            $tagModel = ORM::factory('tag',$val->tag_id)->as_array();
                            if($tagModel['tag_id']){
                            $str_name .= $tagModel['tag_name'].",";
                }
                }
                }
                        return $str_name;
                }
                    #获取投资风险
                    protected function _get_risk($int_num){
                    $str_name = NULL;
                    if($int_num){
                    $investmentStyle =common::investmentStyle();
                    foreach ( $investmentStyle as $k => $v ) {
                    if ($int_num == $k) {
                    $str_name .= $v;
                    }
                    }
                    }
                    return $str_name;
                    }
                    #获取人脉关系
                    protected function _get_Project_Connection($int_project_id){
                    $str_name = null;
                        if($int_project_id){
                        $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $int_project_id)->find_all();
                        if(!empty($project_connection)){
                            $connectionsArr = common::connectionsArr();
                                foreach ($project_connection as $key=>$val){
                                foreach ($connectionsArr as $k=>$v){
                                if($val->connection_id == $k){
                                $str_name .= $v.",";
                                }
                                }
                                }
                                }
                                }
                                return  $str_name;
                                }
                                #获取招商形式
                                protected function _get_project_model($int_project_id){
                                $str_name = null;
                                    if($int_project_id){
                                    $model = ORM::factory('Projectmodel')->where("project_id", "=", $int_project_id)->find_all();
                                    if(!empty($model)){
                                    $type = common::businessForm ();
                                    foreach ($model as $key=>$val){
                                    foreach ($type as $k=>$v){
                                    if($val->type_id == $k){
                                        $str_name .=$v.",";
                                    }
                                    }
                                    }
                                    }
                                    }
                                    return $str_name;
                                    }
                                            #获取招商行业
                                            protected function _get_project_industry($int_project_id){
                                        $str_name = null;
                                        $server = new  Service_Public();
                                        if($int_project_id){
                                            $model = ORM::factory("Projectindustry")->where("project_id", "=", $int_project_id)->find_all()->as_array();
                                            if(!empty($model)){
                                            foreach ($model as $key=>$val){
                                            $str_name .= $server->getIndustryNameById($val->industry_id).",";
    }
    }
    }
    return $str_name;
    }
    #获取项目地区
    protected function _get_project_are($int_project_id){
    $str_name = null;
    $server = new  Service_Public();
    if($int_project_id){
        $model = ORM::factory("Projectarea")->where("project_id", "=", $int_project_id)->find_all()->as_array();
        if(!empty($model)){
        foreach ($model as $key=>$val){
            $str_name .= $server->getAreaName($val->area_id).",";
            }
            }
            }
            return $str_name;
            }
            #获取投资金额
            protected function _get_project_amount_type($int_num){
            $str_name = null;
            if($int_num){
            $amount_type = common::moneyArr ();
            foreach ( $amount_type as $k => $v ) {
            if ($int_num == $k) {
            $str_name = $v."," ;
            }
            }
            }
            return $str_name;
            }
			*/



}
