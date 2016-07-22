<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 前台搜索投资考察
 * @author 潘宗磊
 *
 */
class Controller_Platform_Investment extends Controller_Platform_Template{
	
	private $_investment_show_date = 'investment_show_date';
	private $_investment_show = 'investment_show';//默认页面缓存
	private $_investment_show_calendar = 'investment_show_calendar';//右侧轩终于月统计数据缓存
	private $_investment_show_calendar_left = 'investment_show_calendar_left';//左侧日历缓存数据
	private $_investment_history_date = 'investment_history_date';
	private $_investment_history = 'investment_history';
	private $_investment_history_calendar = 'investment_history_calendar';
	private $_investment_history_calendar_left = 'investment_history_calendar_left';
	private $_investment_bi = 'investment_bi';//BI投资考察浏览记录一天更新一次
	private $_cache_get_index_time = 86400;
	private $_cache_get_hour_time = 3600;
	
	/**
	 * 投资考察
     * @author 周进
     * @param $query
     * 参数总共6个  省市areaid、行业in_id、日历calendar、月历monthly、判断当前来历from、显示时间 time
     * from =calendar按天搜索  =monthly时按月搜索   默认按天
     */
    public function action_index(){
    	$content = View::factory("platform/investment/showinvest");
    	$this->content->maincontent = $content ;
    	$invest = new Service_Platform_Invest();

    	//初始化
    	$calendar = "";
    	$monthly = "";
    	//获取表单值
    	$search = Arr::map("HTML::chars", $this->request->query());
    	$time = Arr::get($search, 'time');
    	if (Arr::get($search, 'time')!=""&&Arr::get($search, 'from')==""){
    		$len = strrpos(Arr::get($search, 'time'), "-");
    		if ($len==4&&arr::get($search, 'monthly')==""){
    			$search['monthly'] = Arr::get($search, 'time');
    			$search['from']    = 'monthly';
    		}
    		if ($len==7&&arr::get($search, 'calendar')==""){
    			$search['calendar'] = Arr::get($search, 'time');
    			$search['from']    = 'calendar';
    		}
    	}
    	if (strrpos(arr::get($search, 'calendar'), "-")){
    		$calendar = explode("-", arr::get($search, 'calendar'));//2013-07-06
    		if ($calendar[0]==date('Y')&&$calendar[1]<date('m')){
    			$calendar[1] = date('m');
    		}
    		if ($calendar[0]==date('Y')&&$calendar[1]<=date('m')&&$calendar[2]<date('d')){
    			$calendar[2] = date('d');
    		}
    	}
    	else
    		$calendar = explode("-", date('Y-m-d'));
    	if (strrpos(arr::get($search, 'monthly'), "-")){
    		$monthly = explode("-", arr::get($search, 'monthly'));//2013-07
    		if ($monthly[0]==date('Y')&&$monthly[1]<date('m')){
    			$monthly[1] = date('m');
    		}
    	}
    	else{
    		$monthly = explode("-", date('Y-m'));
    	}

    	// 最小年份,最大年份 ,最小月份 ,最大月份 公用配置
    	$maxyyyy = date('Y')+3;
    	$dateconfig = array('YYYY_MIN'=>date('Y'),'YYYY_MAX'=>$maxyyyy,'YYYYMM_MIN'=>date('Ym'),'YYYYMM_MAX' => $maxyyyy.date('m'));
    	/****start 月历****/
	    	for ($i=$dateconfig['YYYY_MIN']-1;$i<$dateconfig['YYYY_MAX'];$i++){
	    		$monthly_config['yyyy'][] = $i+1;
	    	}
	    	for ($i=date('m')-1;$i<12;$i++){
	    		$monthly_config['mm'][0][] = $i+1;
	    	}
	    	$monthly_config['mm'][1] = $monthly_config['mm'][2] = $monthly_config['mm'][3] = array('1','2','3','4','5','6','7','8','9','10','11','12');
	    	$content->monthly_config = $monthly_config;

	    	if ($monthly==""){
	    		$monthly = explode("-", date('Y-m'));
	    	}
	    	$content->monthly_yyyy   = $monthly[0];
	    	$content->monthly_mm     = $monthly[1];

    	/****start 月历****/
    	/****start 日历****/
	    	//处理日历
	    	if ($calendar==""){
	    		$calendar = explode("-", date('Y-m-d'));
	    	}
	    	
	    	$yyyymm = $calendar[0] * 100 + $calendar[1];
	    	/***start限制最小与最大年月***/
	    	if (strlen($calendar[1])==1)
	    		$post_yyyymm = $calendar[0]."0".$calendar[1];
	    	else
	    		$post_yyyymm = $calendar[0].$calendar[1];
	    	// 由日历下拉选择框选择的年月
	    	if ($post_yyyymm < $dateconfig['YYYYMM_MIN']){
	    		$yyyymm = $dateconfig['YYYYMM_MIN'];
	    	}else if ($post_yyyymm > $dateconfig['YYYYMM_MAX']){
	    		$yyyymm = $dateconfig['YYYYMM_MAX'];
    		}else if ($post_yyyymm % 100 == 0){
	    		$yyyymm = $post_yyyymm - 100 + 12;
			}else if ($post_yyyymm % 100 == 13){
	    		$yyyymm = $post_yyyymm + 100 - 12;
			}else{
	    		$yyyymm = $post_yyyymm;
			}
	    	/***end限制最小与最大年月***/

	    	$yyyy = floor($yyyymm/100); // 要显示的年份
	    	$mm = $yyyymm % 100; //　要显示的月份
	    	$dd = date("d"); // 当天日期

	    	// 每月的天数
	    	$days = array(1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
	    			7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31);
	    	// 判断是否是闰年
	    	if ($yyyy % 400 == 0 || $yyyy % 4 == 0 && $yyyy % 100 != 0)
	    	{
	    		$days[2] = 29;
	    	}
	    	// 判断所选年月的１号是本年第几天
	    	for ($i = 1, $d = 1; $i < $mm; $i++)
	    	{
	    		$d += $days[$i];
	    	}
	    	// 取得所选月１号是星期几
	    	$week = (($yyyy - 1) + floor(($yyyy - 1)/4) - floor(($yyyy - 1)/100) + floor(($yyyy - 1)/400) + $d) % 7;
	    	// 计算显示本月日历需要几行
	    	$alldays = $week + $days[$mm];
	    	if ($alldays % 7 == 0)
	    	{
	    		$rows = floor($alldays/7);
	    	}
	    	else
	    	{
	    		$rows = floor($alldays/7) + 1;
	    	}
	    	// 构造显示月历数组
	    	$dayarray = array();
	    	for ($i = 0;$i < $week; $i++)
	    	{
	    		$dayarray[] = "";
	    	}
	    	for ($i = 1; $i < $days[$mm] + 1; $i++)
	    	{
	    		$dayarray[] = $i;
	    	}

	    	$content->rows		  = $rows;
	    	$content->dayarray	  = $dayarray;
	    	$content->dateconfig = $dateconfig;

	    	//选定值
	    	if (arr::get($search, 'from')=="calendar"){//日历选中
	    		$showtime = arr::get($search, 'calendar');
	    	}
	    	elseif (arr::get($search, 'from')=="monthly"){//月历选中
	    		$showtime = arr::get($search, 'monthly');
	    	}
	    	else{
	    		$showtime = Arr::get($search, 'time','');
	    	}
	    	$content->time = $showtime;
    	/**** end  日历****/
    	$content->monthly = $monthly;
	    $content->calendar = $calendar;
	    
	    $searchrecomandInvest = array();
	    $where = array('area'=>array('cit_id'=>88,'cit_name'=>'全国'),'indust'=>array('in_id'=>'','in_name'=>''),'time'=>'');
    	if(!empty($search['in_id'])){
    		$where['indust'] = $invest->getIndustryName($search['in_id']);
    		$searchrecomandInvest['parent_id'] = $search['in_id'];
    	}
    	if(!empty($search['areaid'])&&$search['areaid']!=88){
    		$searchrecomandInvest['areaid'] = $search['areaid'];
    		$where['area'] = $invest->getAreaName($search['areaid']);
    	}
    	if(!empty($showtime)){
    		$where['time'] = $showtime;
    		$searchrecomandInvest['investment_start'] = strtotime($showtime);
    	}

    	$service = new Service_Platform_Invest();
    	//添加默认打开页面没搜索条件的缓存start
    	//月份统计数据
    	$IndustryNumBydata_data = "";
    	$IndustryNumBydata_data_left = "";
    	$cache_result = "";
    	$memcache = Cache::instance ( 'memcache' );

    	
    	$invest = new Service_Platform_Invest();


    	$result = $invest->searchPlatformInvestment($searchrecomandInvest,arr::get($search, 'from'));
        if($this->isLogins()){
            $content->user_type=$this->userInfo()->user_type;
            //取当前登录用户的企业id @花文刚
            $com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find()->com_id;
        }else{
            $content->user="";
            $com_id = "";
        }

    	if($result['count']==0){
            $content->recomand = true;
            $recomand = $invest->recomandInvest($searchrecomandInvest, $where,arr::get($search, 'from'));
            $content->list = $invest->getResaultList($recomand[0]['list'],$com_id);
            $content->page = $recomand[0]['page'];
            $count = $recomand[0]['count'];
            $content->new = $recomand[1];
    	}
    	else{
            $content->list = $invest->getResaultList($result['list'],$com_id);
            $content->page = $result['page'];
    		$count = $result['count'];
    		$content->new = array('area'=>array('cit_id'=>88,'cit_name'=>'全国'),'indust'=>array('in_id'=>'','in_name'=>''),'time'=>'');
    	}

    	//日期统计数据
		if (!empty($recomand[1]['time'])){
    		$IndustryNumBydate = date('Y-m',strtotime($recomand[1]['time']));
    	}
    	elseif(arr::get($where,'time')!=""){
    		$IndustryNumBydate = date('Y-m',strtotime(arr::get($where,'time')));
    	}
    	elseif (!empty($search['time'])){
    		$IndustryNumBydate = date('Y-m',strtotime($search['time']));
    	}
    	else{
    		$IndustryNumBydate = strlen($mm)==1?$yyyy."-0".$mm:$yyyy."-".$mm;
    	}
    	
    	$investment_show_calendar = $memcache->get($this->_investment_show_calendar);
    	if (!empty($investment_show_calendar)){
    		if (arr::get($investment_show_calendar, $IndustryNumBydate)!=""){//已经查过对应月的直接读缓存
    			$IndustryNumBydata_data = arr::get($investment_show_calendar, $IndustryNumBydate);
    		}else{//第一次搜索对应月份 加入缓存
    			$IndustryNumBydata_data = $service->getIndustryNumBydate(0,$IndustryNumBydate);
    			$investment_show_calendar[$IndustryNumBydate] = $IndustryNumBydata_data;
    			$memcache->set($this->_investment_show_calendar, $investment_show_calendar, $this->_cache_get_index_time);
    		}

    	}
    	else{
    		$IndustryNumBydata_data = array($IndustryNumBydate=>$service->getIndustryNumBydate(0,$IndustryNumBydate));
    		$memcache->set($this->_investment_show_calendar, $IndustryNumBydata_data, $this->_cache_get_index_time);
    	}
    	 
    	if ($IndustryNumBydata_data=="")
    		$IndustryNumBydata_data = $service->getIndustryNumBydate(0,$IndustryNumBydate);
    	//左侧日历数据源
    	$IndustryNumBydate_left = strlen($mm)==1?$yyyy."-0".$mm:$yyyy."-".$mm;
    	$investment_show_calendar_left = $memcache->get($this->_investment_show_calendar_left);
    	if (!empty($investment_show_calendar_left)){
    		if (arr::get($investment_show_calendar_left, $IndustryNumBydate_left)!=""){//已经查过对应月的直接读缓存
    			$IndustryNumBydata_data_left = arr::get($investment_show_calendar_left, $IndustryNumBydate_left);
    		}else{//第一次搜索对应月份 加入缓存
    			$IndustryNumBydata_data_left = $service->getIndustryNumBydate(0,$IndustryNumBydate_left);
    			$investment_show_calendar_left[$IndustryNumBydate_left] = $IndustryNumBydata_data_left;
    			$memcache->set($this->_investment_show_calendar_left, $investment_show_calendar_left, $this->_cache_get_index_time);
    		}
    	}
    	else{
    		$IndustryNumBydata_data_left = array($IndustryNumBydate_left=>$service->getIndustryNumBydate(0,$IndustryNumBydate_left));
    		$memcache->set($this->_investment_show_calendar_left, $IndustryNumBydata_data_left, $this->_cache_get_index_time);
    	}
    	
    	if ($IndustryNumBydata_data_left=="")
    		$IndustryNumBydata_data_left = $service->getIndustryNumBydate(0,$IndustryNumBydate_left);
    	
    	$content->IndustryNumBydate = $IndustryNumBydate;
    	$content->industrynum = $IndustryNumBydata_data;
    	$content->industrynumleft = $IndustryNumBydata_data_left;
    	$content->count = $count;
    	$content->listIndustry = common::primaryIndustry(0);
    	$content->area = $invest->getArea();
    	$content->search = $where;
    	$content->investNum=$invest->getInvestNum();
    	$content->investHistoryNum=$invest->getHistoryInvestNum();
    	$url_query_data = "";
    	if(arr::get($where['area'],'cit_id')!=88){
    		$url_query_data .= "&areaid=".arr::get($where['area'],'cit_id');
    	}
    	if(arr::get($where['indust'], 'in_id')!=""){
    		$url_query_data .= "&in_id=".arr::get($where['indust'], 'in_id');
    	}
    	$url_query_data .= "&time=".$showtime."&from=".arr::get($search, 'from')."&monthly=".arr::get($search, 'monthly')."&calendar=".arr::get($search, 'calendar')."&";
    	$content->url_query_data = $url_query_data;
    	$friend_link = $memcache->get('friend_cache_touzikaocha'); 
    	if(empty($friend_link)){
    		$f_service = new Service_Platform_FriendLink();
        	$friend_link = $f_service->getFriendLinkList('touzikaocha');
        	$memcache->set('friend_cache_touzikaocha', $friend_link,604800);
    	}		
        $this->template->friend_link = $friend_link;
        $get = Arr::map("HTML::chars", $this->request->query());
    	$page = intval(Arr::get($get, 'page',0));
        if($page == 0){
            $str = "";
        }else{
            $str = '第'.$page.'页';
        }
    	$this->template->title = $str."投资考察会_一句话商机速配网";
    	$this->template->keywords = $str."投资考察,考察项目,投资考察会,一句话商机速配网";
    	$this->template->description = $str."一句话商机速配网投资考察会发布各种需要投资赚钱的好项目。火爆的考察项目一句话就能找到，投资项目赚钱考察上一句话找投资考察会就可以了。";
    }
    /**
     * 清除投资考察缓存的
     * @author 周进
     */
    public function action_clear(){
    	$memcache = Cache::instance ( 'memcache' );
	    $memcache->set($this->_investment_show_date,'',60);
	    $memcache->set($this->_investment_show,'',60);
	    $memcache->set($this->_investment_show_calendar,'',60);
	    $memcache->set($this->_investment_history_date,'',60);
	    $memcache->set($this->_investment_history,'',60);
	    $memcache->set($this->_investment_history_calendar,'',60);
	    $memcache->set($this->_investment_show_calendar_left,'',60);
	    $memcache->set($this->_investment_history_calendar_left,'',60);
    	exit;
    }
    /**
     * 投资考察左侧日历数据更新
     * @author 周进
     */
    public function action_demo(){
    	//投资考察左侧
        $invest = new Service_Platform_Invest();
        $invest->updateIndustryNumBydate();
//      //875外采投资考察
    	$investapi = new Service_Api_Invest();
    	$url = "http://man.875.cn/rest_meeting/postMeetingList";
    	$investapi->getInvest($url, time(),150);
        exit;
    }
    /**
     * 投资考察外采测试
     * @author 周进
     */
    public function action_test(){
    	$invest = ORM::factory('Projectinvest');
    	$result = $invest->where('investment_type', '=', 2)->find_all();
    	foreach ($result as $k=>$v){
    		echo $v->investment_id."=>".$v->project_id."=>".$v->investment_name."<br/>";
    	}
    	exit;
    }
    /**
     * 历史投资考察
     */
    public function action_historyinvest(){
    	$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];    	
    	if($url == URL::website('lishizhaoshang.html') || $url == URL::website('lishizhaoshang.htm')){
    		//self::redirect(urlbuilder::rootDir("lishizhaoshang"));
    		Header("HTTP/1.1 301 Moved Permanently");
    		Header("Location:".urlbuilder::rootDir("lishizhaoshang"));exit;
    	}
        $content = View::factory("platform/investment/historyinvest");
        $this->content->maincontent = $content ;        

        //初始化
        $calendar = "";
        $monthly = "";
        //获取表单值
        $search = Arr::map("HTML::chars", $this->request->query());
        $time = Arr::get($search, 'time');
        if (Arr::get($search, 'time')!=""&&Arr::get($search, 'from')==""){
        	$len = strrpos(Arr::get($search, 'time'), "-");
        	if ($len==4&&arr::get($search, 'monthly')==""){
        		$search['monthly'] = Arr::get($search, 'time');
        		$search['from']    = 'monthly';
        	}
        	if ($len==7&&arr::get($search, 'calendar')==""){
        		$search['calendar'] = Arr::get($search, 'time');
        		$search['from']    = 'calendar';
        	}
        }
        if (strrpos(arr::get($search, 'calendar'), "-")){
        	$calendar = explode("-", arr::get($search, 'calendar'));//2013-07-06
        	if ($calendar[0]==date('Y')&&$calendar[1]>date('m')){
        		$calendar[1] = date('m');
        	}
        	if ($calendar[0]==date('Y')&&$calendar[1]>=date('m')&&$calendar[2]>date('d')){
        		$calendar[2] = date('d');
        	}
        }
        else
        	$calendar = explode("-", date('Y-m-d'));
        if (strrpos(arr::get($search, 'monthly'), "-")){
        	$monthly = explode("-", arr::get($search, 'monthly'));//2013-07
        	if ($monthly[0]==date('Y')&&$monthly[1]>date('m')){
        		$monthly[1] = date('m');
        	}
        }
        else{
        	$monthly = explode("-", date('Y-m'));
        }

        // 最小年份,最大年份 ,最小月份 ,最大月份 公用配置
        $minyyyy = date('Y')-3;
        $dateconfig = array('YYYY_MIN'=>$minyyyy,'YYYY_MAX'=>date('Y'),'YYYYMM_MIN'=>$minyyyy.date('m'),'YYYYMM_MAX' => date('Ym'));
        /****start 月历****/
        for ($i=$dateconfig['YYYY_MAX']+1;$i>$dateconfig['YYYY_MIN'];$i--){
        	$monthly_config['yyyy'][] = $i-1;
        }
        for ($i=0;$i<date('m');$i++){
        	$monthly_config['mm'][0][] = $i+1;
        }
        $monthly_config['mm'][1] = $monthly_config['mm'][2] = $monthly_config['mm'][3] = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $content->monthly_config = $monthly_config;

        if ($monthly==""){
        	$monthly = explode("-", date('Y-m'));
        }
        $content->monthly_yyyy   = $monthly[0];
        $content->monthly_mm     = $monthly[1];

        /****start 月历****/
        /****start 日历****/
        //处理日历
	        if ($calendar==""){
	        	$calendar = explode("-", date('Y-m-d'));
	        }

	        $yyyymm = $calendar[0] * 100 + $calendar[1];
	        /***start限制最小与最大年月***/
	        if (strlen($calendar[1])==1)
	    		$post_yyyymm = $calendar[0]."0".$calendar[1];
	    	else
	    		$post_yyyymm = $calendar[0].$calendar[1];
	        // 由日历下拉选择框选择的年月
	        if ($post_yyyymm < $dateconfig['YYYYMM_MIN'])
	        	$yyyymm = $dateconfig['YYYYMM_MIN'];
	        else if ($post_yyyymm > $dateconfig['YYYYMM_MAX'])
	        	$yyyymm = $dateconfig['YYYYMM_MAX'];
	        else if ($post_yyyymm % 100 == 0)
	        	$yyyymm = $post_yyyymm - 100 + 12;
	        else if ($post_yyyymm % 100 == 13)
	        	$yyyymm = $post_yyyymm + 100 - 12;
	        else
	        	$yyyymm = $post_yyyymm;
	        /***end限制最小与最大年月***/

	        $yyyy = floor($yyyymm/100); // 要显示的年份
	        $mm = $yyyymm % 100; //　要显示的月份
	        $dd = date("d"); // 当天日期

	        // 每月的天数
	        $days = array(1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
	        		7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31);
	        // 判断是否是闰年
	        if ($yyyy % 400 == 0 || $yyyy % 4 == 0 && $yyyy % 100 != 0)
	        {
	        	$days[2] = 29;
	        }
	        // 判断所选年月的１号是本年第几天
	        for ($i = 1, $d = 1; $i < $mm; $i++)
	        {
	        $d += $days[$i];
	        }
	        // 取得所选月１号是星期几
	        $week = (($yyyy - 1) + floor(($yyyy - 1)/4) - floor(($yyyy - 1)/100) + floor(($yyyy - 1)/400) + $d) % 7;
	        // 计算显示本月日历需要几行
	        $alldays = $week + $days[$mm];
	        if ($alldays % 7 == 0)
	        {
	        $rows = floor($alldays/7);
	        }
	        else
	        {
	        $rows = floor($alldays/7) + 1;
	        }
	        // 构造显示月历数组
	        $dayarray = array();
	        for ($i = 0;$i < $week; $i++)
	        {
	        $dayarray[] = "";
	        }
	        for ($i = 1; $i < $days[$mm] + 1; $i++)
	        {
	        $dayarray[] = $i;
	        }

	        $content->rows		  = $rows;
        	$content->dayarray	  = $dayarray;
        	$content->dateconfig = $dateconfig;

        	//选定值
        	if (arr::get($search, 'from')=="calendar"){//日历选中
        	$showtime = arr::get($search, 'calendar');
	        }
	        elseif (arr::get($search, 'from')=="monthly"){//月历选中
	        	$showtime = arr::get($search, 'monthly');
	        }
	        else{
	        	$showtime = Arr::get($search, 'time','');
	        }
	    	$content->time = $showtime;
// 	    	//月份统计数据
// 	    	$service = new Service_Platform_Invest();
// 	    	$IndustryNumBydata = $service->getIndustryNumBymonth();
// 	    	$content->industrynum = $IndustryNumBydata;
	    /**** end  日历****/
	    $content->monthly = $monthly;
	    $content->calendar = $calendar;
	    
	    $service = new Service_Platform_Invest();
        $searchrecomandInvest = array();
        $where = array('area'=>array('cit_id'=>88,'cit_name'=>'全国'),'indust'=>array('in_id'=>'','in_name'=>''),'time'=>'');
        if(!empty($search['in_id'])){
            $where['indust'] = $service->getIndustryName($search['in_id']);
            $searchrecomandInvest['parent_id'] = $search['in_id'];
        }
        if(!empty($search['areaid'])&&$search['areaid']!=88){
            $searchrecomandInvest['areaid'] = $search['areaid'];
            $where['area'] = $service->getAreaName($search['areaid']);
        }
        if(!empty($showtime)){
            $where['time'] = $showtime;
            $searchrecomandInvest['investment_start'] = strtotime($showtime);
        }
        
        
        //添加默认打开页面没搜索条件的缓存start
        //月份统计数据
        $IndustryNumBydata_data = "";
        $cache_result = "";
        $IndustryNumBydata_data_left = "";
        $memcache = Cache::instance ( 'memcache' );

        if($this->isLogins()){
            $content->user_type=$this->userInfo()->user_type;
            $com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find()->com_id;
        }else{
            $content->user="";
            $com_id = "";
        }

        $result = $service->searchHistoryInvestment($searchrecomandInvest,arr::get($search, 'from'),$com_id);

        if($result['count']==0){
            $content->recomand = true;
            $recomand = $service->recomandHistoryInvest($searchrecomandInvest, $where,arr::get($search, 'from'),$com_id);
            $content->list = $recomand[0]['list'];
            $content->page = $recomand[0]['page'];
            $count = $recomand[0]['count'];
            $content->new = $recomand[1];
        }else{
            $content->list = $result['list'];
            $content->page = $result['page'];
            $count = $result['count'];
            $content->new = array('area'=>array('cit_id'=>88,'cit_name'=>'全国'),'indust'=>array('in_id'=>'','in_name'=>''),'time'=>'');
        }

        
        //日期统计数据
        if (!empty($recomand[1]['time'])){
        	$IndustryNumBydate = date('Y-m',strtotime($recomand[1]['time']));
        }
        elseif(arr::get($where,'time')!=""){
        	$IndustryNumBydate = date('Y-m',strtotime(arr::get($where,'time')));
        }
        elseif (!empty($search['time'])){
        	$IndustryNumBydate = date('Y-m',strtotime($search['time']));
        }
        else{
        	$IndustryNumBydate = strlen($mm)==1?$yyyy."-0".$mm:$yyyy."-".$mm;
        }



        $investment_show_calendar = $memcache->get($this->_investment_history_calendar);
        if (!empty($investment_show_calendar)){
        	if (arr::get($investment_show_calendar, $IndustryNumBydate)!=""){//已经查过对应月的直接读缓存
        		$IndustryNumBydata_data = arr::get($investment_show_calendar, $IndustryNumBydate);
        	}else{//第一次搜索对应月份 加入缓存
        		$IndustryNumBydata_data = $service->getIndustryNumBydate(0,$IndustryNumBydate);
        		$investment_show_calendar[$IndustryNumBydate] = $IndustryNumBydata_data;
        		$memcache->set($this->_investment_history_calendar, $investment_show_calendar, $this->_cache_get_index_time);
        	}
        }
        else{
        	$IndustryNumBydata_data = array($IndustryNumBydate=>$service->getIndustryNumBydate(0,$IndustryNumBydate));
        	$memcache->set($this->_investment_history_calendar, $IndustryNumBydata_data, $this->_cache_get_index_time);
        }
        if ($IndustryNumBydata_data=="")
        	$IndustryNumBydata_data = $service->getIndustryNumBydate(0,$IndustryNumBydate);
        //左侧日历
        $IndustryNumBydate_left = strlen($mm)==1?$yyyy."-0".$mm:$yyyy."-".$mm;
        $investment_show_calendar_left = $memcache->get($this->_investment_history_calendar_left);
        if (!empty($investment_show_calendar_left)){
        	if (arr::get($investment_show_calendar_left, $IndustryNumBydate_left)!=""){//已经查过对应月的直接读缓存
        		$IndustryNumBydata_data_left = arr::get($investment_show_calendar_left, $IndustryNumBydate_left);
        	}else{//第一次搜索对应月份 加入缓存
        		$IndustryNumBydata_data_left = $service->getIndustryNumBydate(0,$IndustryNumBydate_left);
        		$investment_show_calendar_left[$IndustryNumBydate_left] = $IndustryNumBydata_data_left;
        		$memcache->set($this->_investment_history_calendar_left, $investment_show_calendar_left, $this->_cache_get_index_time);
        	}
        }
        else{
        	$IndustryNumBydata_data_left = array($IndustryNumBydate_left=>$service->getIndustryNumBydate(0,$IndustryNumBydate_left));
        	$memcache->set($this->_investment_history_calendar_left, $IndustryNumBydata_data_left, $this->_cache_get_index_time);
        }
        if ($IndustryNumBydata_data_left=="")
        	$IndustryNumBydata_data_left = $service->getIndustryNumBydate(0,$IndustryNumBydate_left);
        
        $content->IndustryNumBydate = $IndustryNumBydate;
        $content->industrynum = $IndustryNumBydata_data;
        $content->industrynumleft = $IndustryNumBydata_data_left;
        $content->count = $count;
        $content->listIndustry = common::primaryIndustry(0);
        $content->area = $service->getArea();
        $content->search = $where;
        $content->investNum=$service->getInvestNum();
        $content->investHistoryNum=$service->getHistoryInvestNum();
        $url_query_data = "";
        if(arr::get($where['area'],'cit_id')!=88){
            $url_query_data .= "&areaid=".arr::get($where['area'],'cit_id');
        }
        if(arr::get($where['indust'], 'in_id')!=""){
            $url_query_data .= "&in_id=".arr::get($where['indust'], 'in_id');
        }
        $url_query_data .= "&time=".$showtime."&from=".arr::get($search, 'from')."&monthly=".arr::get($search, 'monthly')."&calendar=".arr::get($search, 'calendar')."&";
        $content->url_query_data = $url_query_data;
        $page = Arr::get($search, 'page' , 1);
        if($page == 1){
	        $this->template->title = "历史投资考察_历史投资考察会_一句话";
	        $this->template->keywords = "历史投资考察，历史投资考察会，一句话";
	       	$this->template->description = "这里有大量的历史投资考察、历史投资考察会相关的信息，各个投资考察会已经圆满结束，完成了投资、考察、招商等工作！为各个创业者提供了很好的选择机会，历史投资考察供您参考！";
        }else{
        	$this->template->title = "第{$page}页历史投资考察_历史投资考察会_一句话";
	        $this->template->keywords = "第{$page}页，历史投资考察，历史投资考察会，一句话";
	       	$this->template->description = "第{$page}页历史投资考察频道有大量的历史投资考察、历史投资考察会相关的信息，各个投资考察会已经圆满结束，完成了投资、考察、招商等工作！为各个创业者提供了很好的选择机会，历史投资考察供您参考！";
        }
    }

}