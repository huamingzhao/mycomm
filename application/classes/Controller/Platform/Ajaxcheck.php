<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 平台ajax 数据调用文件
 * @author 龚湧
 *
 */

class Controller_Platform_Ajaxcheck extends Controller{
    private $_cache_get_project_days = 'getProjectTotalDays';
    private $_cache_get_project_area = 'getprojectarea';
    private $_cache_get_project_time = 86400;
    private $_get_search_count = 8; //设置精准匹配返回的数据数
    /**
     * test function
     * 只是打印
     */
    public function action_printConfig() {
         //取得用户所选择的信息
        $service=new Service_Platform_Search();
        if($this->isLogins()){//用户登录时
            //取得用户id
            $user_id = $this->userId();
            $data = $service->getLoggedSearchConfig($user_id);
        }else{//未登录时
            $data = $service->getNotLoggedSearchConfig();
        }
        var_dump($data);
    }
    /**
     * 新版精准匹配
     * @author 施磊
     */
    public function action_projectMatchSearchNew() {
        //取得用户所选择的信息
        $service=new Service_Platform_Search();
        $serviceProject = new Service_Platform_Project();
        if($this->isLogins()){//用户登录时
            //取得用户id
            $user_id = $this->userId();
            $data = $service->getLoggedSearchConfig($user_id);
        }else{//未登录时
            $data = $service->getNotLoggedSearchConfig();
        }
        if(count($data) < 7) {
            $this->action_projectMatchSearch();
            return false;
        }
        $oneWord = $service->getSentence($data);
        $allCond = $service->getConditionName($data);
        $get = Arr::map("HTML::chars", $this->request->query());
        $page = Arr::get($get, 'page') ? $get['page'] : 1;
        $limit = Arr::get($get, 'get', 6) ? Arr::get($get, 'get') : $this->_get_search_count;
        //查询条件
        $res['data'] = $service->getQueryConditionList($data, $page, $limit);
        $count = $serviceProject->getProjectCount();
        $res['total'] = $count;
        echo json_encode($res);
    }
    /**
     * 查找所匹配的项目
     * 查找所匹配的项目并显示6个项目
     * @author 曹怀栋
     */
    public function action_projectMatchSearch(){
        //取得用户所选择的信息
        $service=new Service_Platform_Search();
        $serviceProject = new Service_Platform_Project();
        if($this->isLogins()){//用户登录时
            //取得用户id
            $user_id = $this->userId();
            $data = $service->getLoggedSearchConfig($user_id);
        }else{//未登录时
            $data = $service->getNotLoggedSearchConfig();
        }

        if(count($data) < 6 && $data) {
           foreach($data as $key => $val) {
               $data = array($key => $val);
           }
        }
        $arr_list['result'] = $data;

        //查询条件
        $res = $service->getQueryCondition($arr_list);
        $result_count = 0;
        $result=$res['result'];
        $resCond = $res['arr'];
        $resTotal = $serviceProject->getProjectCount();
        if(count($result) > 0 || $res['arr'] == 'noCond'){//当查询到数据的情况
            //取得项目总数
            $result_count = count($result);
            $result_array = $result;
            if($res['arr'] == 'noCond') {
                $res = $serviceProject->getProjectByProjectIds();
            } else {
                $res = $serviceProject->getProjectByProjectIds($result_array);
            }


        if($resCond == 'noCond') {
            $result_count = count($res);
        }
        $temp = ($res) ? array_chunk($res,6) : $res;
        $res = arr::get($temp, 0, array());
        }else{
            $result_count = 0;
            $res = array();
        }
        if($res) {
            foreach($res as $key => $val) {
                $res[$key]['link'] = urlbuilder::project($val['project_id']);
                $res[$key]['project_summary'] = 'test';
                if($val['project_source'] != 1) {
                   $res[$key]['project_logo'] = project::conversionProjectImg($val['project_source'], 'logo', $val);
                }else{
                    $res[$key]['project_logo']=URL::imgurl($val['project_logo']);
                }
            }
        }
        if($result_count == 0) {
           $res = $service->getRecommProject();
        }
        $rest['data'] = $res;
        $rest['count'] = $result_count;
        $rest['total'] = $resTotal ? $resTotal : 0;
        $rest['link'] = "http://".$_SERVER['HTTP_HOST']."/platform/guide/projectlist";
        echo json_encode($rest);
    }
    /**
     * 取得最近30天每天访问的项目总数
     * @author 曹怀栋
     */
    public function action_getProjectTotalDays(){
        $get = $this->request->query();
        //判断project_id是否存在
        if(!isset($get['project_id']) || !is_numeric($get['project_id'])){
            echo json_encode(false);exit;
        }
        //判断project_id是否存在项目表中
        $service = new Service_Platform_Project();
        $project_id = $service->getProjectInfoByID($get['project_id']);
        if($project_id == false){
            echo json_encode(false);exit;
        }
        //取得最近30天的时间
        $time = time();
        $res_date = array();
        $a = 0;
        for($i = $time-86400*30; $i < $time ;$i+=86400){
            $res_date[$a]['start_time'] = strtotime(date('Y-m-d',$i))+1;
            $res_date[$a]['end_time'] = strtotime(date('Y-m-d',$i))+86399;
            $a++;
        }
        unset($a);
        $service = Service::factory("Platform_Browsing");

        $memcache = Cache::instance('memcache');
        try {
             $get_num = $memcache->get($this->_cache_get_project_days.$project_id);
        }
        catch (Cache_Exception $e) {
             $get_num = array();
        }
        if(!$get_num) {
            $get_num = $service->getProjectMultiple($get['project_id']);
            $memcache->set($this->_cache_get_project_days.$project_id, $get_num, $this->_cache_get_project_time);
        }
        $data = array();
        foreach ($res_date as $k=>$v){
            $data[$k]['date'] = date('Y-m-d',$v['start_time']);
            $num = $service->getEverydayCount($project_id,$v);
            //根据倍数和取得的项目总数来得到相应的项目总数
            $data[$k]['num'] = $get_num ? $num*$get_num : $num;
        }
        $res['result'] = $data;
        echo json_encode($res);exit;
    }
    /**
     * 取得最近30天地区的访问
     * @author 曹怀栋
     */
    public function action_getProjectArea(){
        $get = $this->request->query();
        $serviceBrowsing = new Service_Platform_Browsing();
        //判断project_id是否存在
        if(!isset($get['project_id']) || !is_numeric($get['project_id'])){
            echo json_encode(false);exit;
        }
        //判断project_id是否存在项目表中
        $service = new Service_Platform_Project();
        $project_id = $service->getProjectInfoByID($get['project_id']);
        $project_id = $project_id->project_id;
        if($project_id == false){
            echo json_encode(false);exit;
        }
        $memcache = Cache::instance('memcache');
        $result = array();
        try {
             $result = $memcache->get($this->_cache_get_project_area.$project_id);
        }
        catch (Cache_Exception $e) {
             $result = array();
        }

        if(!$result) {
            //当前一个月的开始的结束时间
            $end =strtotime(date('Y-m-d', strtotime('-1 days')))+86399;
            $start = strtotime(date('Y-m-d', strtotime('-30 days')))+1;
            $result = $serviceBrowsing->getProjectPvCount($project_id, $start, $end);
            $memcache->set($this->_cache_get_project_area.$project_id, $result, $this->_cache_get_project_time);
        }
        //把ip转化为地名
        foreach ($result as $k=>&$v){
            $ip_address = common::convertip(long2ip($v['ip']));
            if(substr($ip_address, 2) != '本地') {
                $v['ips'] = substr($ip_address, 2);
            }else {
                unset($result[$k]);
            }

        }
        $res['result'] = $result;
        $temp = array();
        if($res['result']) {
           foreach($res['result'] as $keyB => $valB) {
                if(isset($temp[$valB['ips']])) {
                    $temp[$valB['ips']]['count'] += $valB['count'];
                }else{
                    $temp[$valB['ips']] = $valB;
                }
           }
        }
        $return = array();
        if($temp) {
            $num = 0;
            foreach($temp as $keyC => $valC) {
                if($num <= 7) {
                    $return[] = $valC;
                }
                $num ++;
            }
        }
        $res['result'] = $return;
        echo json_encode($res);
    }
    /**
     * 取得随机条件项目
     * @author 曹怀栋
     */
    public function action_getRandomConditions(){
        $get = $this->request->post();
        $search=new Service_Platform_Search();
        //这个方法是调用（沈鹏飞）的接口
        $searchresult=$search->getWordSearch(trim($get['w']));
        $searchresult['matches'] = isset($searchresult['matches']) ? $searchresult['matches'] : array();
        $result = array();
        if(count($searchresult['matches']) > 0){
            foreach ($searchresult['matches'] as $k=>$v){
                if($k < 14){
                    $result['matches'][$k]['id'] = $v['id'];
                    $project=ORM::factory('Project',$v['id']);
                    if($project->project_logo !=""){
                        if($project->project_source != 1) {
                            $proInfo = $project->as_array();
                            $result['matches'][$k]['logo'] = project::conversionProjectImg($project->project_source, 'logo', $proInfo);
                        }else{
                            $result['matches'][$k]['logo'] = URL::imgurl($project->project_logo);
                        }
                    }else{
                        $result['matches'][$k]['logo'] ="#";
                    }
                    $result['matches'][$k]['project_brand_name'] = $project->project_brand_name;
                }
            }
        }else{
            $result['matches'] = array();
        }
        if($result['matches']) {
            foreach($result['matches'] as $key => $val) {
                $result['matches'][$key]['link'] = urlbuilder::project($val['id']);
            }
        }
        $result['total'] = $searchresult['total'];
        echo json_encode($result);
    }
    /**
     * 查找所匹配的项目总数，返回给flash
     * @author 曹怀栋
     */
    public function action_projectCountFlash(){
        //取得用户所选择的信息
        $service=new Service_Platform_Search();
        $serviceProject = new Service_Platform_Project();
        if($this->isLogins()){//用户登录时
            //取得用户id
            $user_id = $this->userId();
            $data = $service->getLoggedSearchConfig($user_id);
        }else{//未登录时
            $data = $service->getNotLoggedSearchConfig();
        }
        $arr_list['result'] = $data;
        //查询条件
        $res = $service->getQueryCondition($arr_list);
        //取得项目总数
        $result=count($res['result']);
        if($result > 0){
            $result_count = $result;
        }else{
            $result_count = $serviceProject->getProjectCount();
        }
        $this->ajaxRst($result_count,'','');
    }

    /**
     * @author 龚湧
     */
    public function action_guideConfig(){
        $service = Service::factory("Platform_Search");
        $qid = $this->request->post("qid");
        $aid = $this->request->post("aid");
        //检测用户是否已经登录，采取不同的方式保存配置
        $service_log = new Service_Platform_Search();
        $ip = ip2long(Request::$client_ip);
        if($this->isLogins()){
            $user_id = $this->userId();
            $service->setLoggedSearchConfig($user_id,$qid,$aid);
            $config = array (
                    "user_id" => $user_id,
                    "q_id" => $qid,
                    "a_id" => $aid,
                    "ip" => $ip,
                    "add_time"=>time()
            );
        }
        //未登录用户
        else{
            $config = $this->request->post("config");
            $service->setNotLoggedSearchConfig($config,$qid,$aid);
            $config = array (
                    "user_id" => 0,
                    "q_id" => $qid,
                    "a_id" => $aid,
                    "ip" => $ip,
                    "add_time"=>time()
            );
        }
        //保存搜索记录 BI统计用
        $service_log->biSearchLog($config);
    }

    /**
     * ajax 动态获取一句话  TODO 要更正，ajax直接获取头信息发送的cookie
     * @author 龚湧
     */
    public function action_getActiveSentence(){
        $qconfig = array();
        $service = Service::factory("Platform_Search");
        if($this->isLogins()){
            $qconfig = $service->getLoggedSearchConfig($this->userId());
        }
        else{
            $config = Cookie::get("guideConfig");
            if($config){
                $qconfig = json_decode(base64_decode($config),true);
            }
        }
        $sentence = $service->getSentence($qconfig);
        $sentences = '';
        foreach($sentence as $str){
            $sentences .= $str;
        }
        if(empty($qconfig)){
            echo "我想做项目";
        }
        echo $sentences;
    }

    /**
     * 获取问题配置，页面初始化调用
     * @author 龚湧
     */
    public function action_getGuideConfig(){
        $qconfig = array();
        $service = Service::factory("Platform_Search");
        if($this->isLogins()){
            $qconfig = $service->getLoggedSearchConfig($this->userId());
        }
        else{
            $config = Cookie::get("guideConfig");
            if($config){
                $qconfig = json_decode(base64_decode($config),true);
            }
        }
        echo json_encode($qconfig);
    }

    /**
     * AJAX联动菜单数据
     * @author 周进
     */
   /* public function action_searchComplete(){
        $post = $this->request->query();
        $queryString = isset($post['keywords'])&&$post['keywords']!=""?$post['keywords']:'';
        $service = Service::factory("Platform_Search");
        echo $service->getSearchComplete($queryString);
    }*/
    public function action_searchComplete(){
        $post = $this->request->query();
        $queryString = isset($post['keywords'])&&$post['keywords']!=""?$post['keywords']:'';
        $mod = new Service_Api_Search();
        $data = $mod->getSuggest($queryString);
        if(!empty($data)){
        	$return = array('isError' => false, 'data' => $data);
        }else{
        	$return = array('isError' => true, 'data' => $data);
        }
        echo json_encode($return, TRUE);
    }

    /**
     * 项目列表页面中个人向企业递出名片
     * @author 曹怀栋
     */
    public function action_cardOut(){
        $post = Arr::map("HTML::chars", $this->request->post());
        if(!$this->isLogins()){
            echo "no_login";exit;
        }
        //判断所传的值是否存在
        if(isset($post['to_user_id']) && is_numeric($post['to_user_id'])){
            $data['to_user_id'] = $post['to_user_id'];
        }else{
            echo false;exit;
        }
        //用户的id
        $user_id = $this->userId();
        $service=new Service_Card();
        $res =$service->addOutCard($user_id, $data);
        echo $res['status'];
    }

    /**
     * 精准匹配项目列表页面中(更新问题的值)
     * @author 曹怀栋
     */
    public function action_updateAccurateMatching(){
        $post = Arr::map("HTML::chars", $this->request->post());
        //判断所传的值是否存在
        if(isset($post['name']) && isset($post['value']) && is_numeric($post['value'])){
            if(stristr($post['name'], 'str') === FALSE) {
                echo "no";exit;
            }
            $question_id = substr($post['name'], 3);
            $question_id = intval($question_id);
            $question_answer_id = $post['value'];
            $service=new Service_Platform_Search();
            if(!$this->isLogins()){
                $config = $this->request->post("config");
                $service->setNotLoggedSearchConfig($config,$question_id,$question_answer_id);
                echo "ok";exit;
            }else{
                //用户的id
                $user_id = $this->userId();
                $service->updateAccurateMatching($user_id,$question_id,$question_answer_id);
                echo "ok";exit;
            }
        }else{
            echo "no";exit;
        }
    }

    /**
     * 精准匹配项目列表页面中（删除指定查询属性）
     * @author 曹怀栋
     */
    public function action_deleteAccurateMatching(){
        $post = Arr::map("HTML::chars", $this->request->post());
        //判断所传的值是否存在
        if(isset($post['name']) && is_numeric($post['name'])){
            $question_id = $post['name'];
            $service=new Service_Platform_Search();
            if(!$this->isLogins()){
                $config = $this->request->post("config");
                $service->deleteNotLoggedSearchConfig($config,$question_id);
                echo "ok";exit;
            }else{
                //用户的id
                $user_id = $this->userId();
                $res =$service->deleteAccurateMatching($user_id,$question_id);
                echo "ok";exit;
            }
        }else{
            echo "no";exit;
        }
    }

    /**
     * 精准匹配项目列表页面中(取得问题的值列表)
     * @author 曹怀栋
     */
    public function action_getSpecifiedAttribute(){
        if($this->request->is_ajax()){
            $post = Arr::map("HTML::chars", $this->request->post());
            $msg = array();
            //判断所传的值是否存在
            if(isset($post['question_id']) && is_numeric($post['question_id'])){

                $question_id = $post['question_id'];
                $service=new Service_Platform_Search();
                if(isset($post['industry_id']) && is_numeric($post['industry_id'])){
                    $msg = array();
                    $msgs =$service->getSpecifiedAttribute($question_id,$post['industry_id']);
                    foreach ($msgs as $k=>$v){
                            $msg[$k]['industry_name']=$v->industry_name;
                            $msg[$k]['industry_id']=$v->industry_id;
                    }
                }else{
                    $res =$service->getSpecifiedAttribute($question_id);

                    if(count($res) < 1){
                        echo json_encode("no");exit;
                    }
                    foreach ($res as $k=>$v){
                        if($k-1 >= 0){
                            $msg[$k-1]['val']=$v;
                            $msg[$k-1]['industry_id']=$k;
                        }
                    }
                }
                $arr = $msg;
                $msg = '';
                if($arr) {
                    while ($value = current($arr)) {
                            $msg[] = $value;
                            next($arr);
                    }
                }
                $this->ajaxRst($msg,'','');
            }else{
                echo json_encode("no");exit;
            }
        }
    }

    /**
     * 个人给项目递送名片
     * @author 钟涛
     */
    public function action_sendCompanycard(){
        if(!$this->isLogins()){
            $to_url=base64_encode(URL::website("platform/projectGuide/ProjectGuideIndustry"));
            $msg['error'] = 'notlogin';
            echo json_encode($msg);exit;
        }
        $postdata = $this->request->post();
        //获取登录user_id
        $userInfo=$this->userInfo();
        $user_id =$userInfo->user_id;
        $card_service=new Service_Card();
        //判断用户类型
        $usertpye=$userInfo->user_type;
        //判断个人是否已经完善个人名片信息
        $perservice=new Service_User_Person_User();
        $perinfo=$perservice->getPerson($user_id);
        if($usertpye !=2){
            $msg['error'] = '<p class="errorbox">很抱歉，只能个人用户才能够给项目递送名片哦！</p>';
        }else if($perinfo->per_id==null){
            $msg['error'] = '<p class="errorbox" style="width:365px;">您还未生成名片，请生成名片后再向招商者递出您的名片！</p>';
        }
        else{
            $ser_plat=new Service_Platform_Search();
            $is_sendcard = $ser_plat->getCardInfo($user_id,$postdata['to_user_id']);
            if(!$is_sendcard){
                //添加新的递出的名片
                $result=$card_service->addOutCard($user_id,$postdata);
                switch ($result['status']) {
                    case '100':
                        $msg['count'] = $result['count'];
                        $msg['error'] = '';
                        break;
                    default:
                        $msg['count'] = '';
                        $msg['error'] = '<p class="errorbox">很抱歉，递出名片失败，请重新递出您的个人名片！</p>';
                        break;
                }
            }else{
                $msg['count'] = '';
                $msg['error'] = '<p class="errorbox">很抱歉，您已经给该企业递送过名片，暂时无法递送！</p>';
            }
        }
        echo json_encode($msg);
    }

    /**
     * sso 
     * 个人咨询、索要资料、发送意向
     * @author 钟涛
     */
    public function action_sendLetterByPer(){
    	if(!$this->isLogins()){
    		$msg['error'] = 'notlogin';
    		echo json_encode($msg);exit;
    	}
    	
    	$postdata = Arr::map("HTML::chars", $this->request->post());
    	//获取登录user_id
    	$userInfo=$this->userInfo();
    	//echo json_encode($userInfo);
    	//echo "<pre>"; print_r($userInfo);exit;
    	$user_id =$userInfo->user_id;
    	$card_service=new Service_Card();
    	//判断用户类型
    	$usertpye=$userInfo->user_type;
 
    	//判断个人是否已经完善个人名片信息
    	$perservice=new Service_User_Person_User();
    	$perinfo=$perservice->getPerson($user_id);
    	if(arr::get($postdata,'type')==2){
    		$errortype = '索要资料';
    	}elseif(arr::get($postdata,'type')==3){
    		$errortype = '发送意向';
    	}else{
    		$errortype = '咨询';
    	}  	
    	$msg['error']='';
    	/*if($usertpye !=2){
    		$msg['type']=1;
    		$msg['error'] = '<p class="errorbox">很抱歉，只能个人用户才能够'.$errortype.'哦！</p>';
    	}*/
    	if(!$userInfo->valid_mobile){//手机必须验证才能发名片
    		$msg['type']=2;
    		$msg['error'] = '<p class="errorbox" style="width:365px;">您的手机号还未验证哟！快去验证手机号，再'.$errortype.'吧！<a style=" font-size: 14px;" target="_blank" href="'.URL::website("person/member/valid/mobile").'">去验证>></a></p>';
    	}
    	/*else if(!$perinfo->per_id || !$userInfo->mobile){   		
    		$msg['type']=1;
    		$msg['error'] = '<p class="errorbox" style="width:365px;">您还未完善基本信息，请完善基本信息后再'.$errortype.'吧！<a style=" font-size: 14px;" target="_blank" href="'.URL::website("person/member/basic/personupdate").'">去完善</a></p>';
    	}*/
    	else{
    	    $perservice2=new Service_User_Company_Card();
    		/*if(arr::get($postdata,'type')){
    			$ret=$perservice2->justIsSend($user_id,$postdata);
    			if($ret===true){//已经咨询
    				$msg['type']=2;
    				if(arr::get($postdata,'type')==2){
    					$msg['error'] = '您今天已经索要过资料，请明天再来';
    				}elseif(arr::get($postdata,'type')==3){
    					$msg['error'] = '您今天已经发送过名片，请明天再来！';
    				}else{
    					$msg['error'] = '您今天已经咨询过，请明天再来';
    				}
    			}else{
    				if($ret>=10){//当天已经发送超过10次
    					$msg['error'] = '您今天已共咨询过10次，请明天再来';
    				}else{
	    				$msg=$perservice2->getReceivecardLoginid($user_id);
	    				$msg['error'] ='';
	    				$msg['name'] = mb_substr(arr::get($msg,'name',''),0,6,'UTF-8');//截取名字@赵路生
    				}
    			}
    		}*/    		
    		//$msg=$perservice2->getReceivecardLoginid($user_id);
    		$msg = (array)$userInfo;
    		$msg['error'] ='';
    		$msg['name'] = mb_substr(arr::get($msg,'name',''),0,6,'UTF-8');//截取名字@赵路生
    	}
    	echo json_encode($msg);
    }
    
    /**
     * 提交【个人咨询、索要资料、发送意向】
     * @author 钟涛
     * 2013-07-24
     */
    public function action_sendLetterSubmit(){
    	if(!$this->isLogins()){
    		$msg['error'] = 'notlogin';
    		echo json_encode($msg);exit;
    	}
    	$postdata = Arr::map("HTML::chars", $this->request->post());
    	//获取登录user_id
    	$user_id =$this->userInfo()->user_id;
    	if(arr::get($postdata,'type') && arr::get($postdata,'projectid')){
	    	$perservice2=new Service_User_Company_Card();
	    	$pser=new Service_Platform_Project();
	    	$to_user_id=$pser->getUseridByProjectID(arr::get($postdata,'projectid'));
	    	if($to_user_id){
	    		$postdata['to_user_id']=$to_user_id;
	    	}else{
	    		$postdata['to_user_id']=0;
	    	}
	    	$ret=$perservice2->addOutCardByPerson($user_id,$postdata);
	    	if($ret==false){//递送失败
	    		$msg['type'] = 1;
	    		$msg['error']='您今天已经咨询过，请明天再来！';
	    	}else{//递送成功
	    		if(arr::get($postdata,'type')==2){
	    			$msg['type'] = 2;
	    			$msg['error'] = '您已向企业索要资料<br/>企业稍后会把资料发送至您的邮箱中，请注意查收！';
	    		}elseif(arr::get($postdata,'type')==3){
	    			$msg['type'] = 1;
	    			$msg['error'] = '您的投资意向已成功发送至企业！';
	    		}else{
	    			$msg['type'] = 2;
	    			$msg['error'] = '您已把联系方式和留言递送给企业<br/>企业收到您的信息和留言后将第一时间与您联系！';
	    		}
	    	}
    	}else{
    		$msg['type'] = 1;
    		$msg['error']='页面数据有误，请刷新后重试！';
    	}
    	echo json_encode($msg);
    }
    
    /**
     * 企业首页判断是否登录且判断是否是企业用户
     * @author 钟涛
     */
    public function action_comCenter(){
        if(!$this->isLogins()){
            $to_url=base64_encode(URL::website("platform/projectGuide/ProjectGuideIndustry"));
            $msg['error'] = 'notlogin';
            $msg['type'] = '1';//未登录
            echo json_encode($msg);exit;
        }
        //判断用户类型
        $usertpye=$this->userInfo()->user_type;
        if($usertpye !=1){
            $msg['error']='个人用户无法操作';
            $msg['type'] = '2';//个人用户登录
        }else{
            $msg['error']='';
            $msg['type'] = '3';//企业用户登录
        }
        echo json_encode($msg);exit;
    }

    /**
     * 只判断是否登录
     * @author 钟涛
     */
    public function action_justLogin(){
    	if(!$this->isLogins()){
    		$msg['error'] = 'notlogin';
    		echo json_encode($msg);exit;
    	}
    	$msg['error'] = '';
    	echo json_encode($msg);exit;
    }
    
    /**
     * 举报
     * @author 钟涛
     */
    public function action_jubao(){
    	$postdata = Arr::map("HTML::chars", $this->request->post());
    	if(!$this->isLogins()){//未登录
    		$postdata['report_contact_name']=arr::get($postdata,'name','');//姓名
    		$postdata['report_mobile']=arr::get($postdata,'mobile','');//手机
    		$postdata['report_email']=arr::get($postdata,'email','');//邮箱
    		$postdata['report_identity_type']=1;//游客
    	}else{//已登录
    		$user=$this->userInfo();
    		$perservice=new Service_User_Person_User();
            $perinfo=$perservice->getPerson($user->user_id);
            //个人真实姓名
            if($perinfo->per_realname){//存在
    			$postdata['report_contact_name']=$perinfo->per_realname;
            }else{//没完善个人基本信息
            	$postdata['report_contact_name']='';
            }
            $postdata['report_mobile']=$user->mobile;//手机
            $postdata['report_email']=$user->email;//邮箱
            if($user->user_type ==1){
            	$postdata['report_identity_type']=2;//企业用户
            }else{
            	$postdata['report_identity_type']=3;//个人用户
            }
    	}
		//公共部分
    	$postdata['report_project_id']=arr::get($postdata,'projectid','');//项目id
    	$postdata['report_project_name']=arr::get($postdata,'projectname','');//项目name
    	$postdata['report_content']=arr::get($postdata,'content','');//举报内容
    	$postdata['report_type']=arr::get($postdata,'type',1);//举报类型
    	$sers=new Service_Platform_Project();
    	//添加数据
    	$result=$sers->addJubao($postdata);
    	if($result){
    		$msg['error']='您的举报已经成功提交，我们将会尽快处理';
    	}else{
    		$msg['error']='页面数据有误，请刷新后重试';
    	}
    	echo json_encode($msg);exit;
    }
    
    /**
     * 发信前检查[是否登录、是否企业用户、是否完善企业基本信息]
     * @author 钟涛
     */
    public function action_justSendLetter(){
        if(!$this->isLogins()){//判断是否登录
            $msg['error'] = 'notlogin';
            echo json_encode($msg);exit;
        }
        $usertpye=$this->userInfo()->user_type;
        $sers=new Service_User_Company_User();
        if($usertpye !=1){//判断用户类型
            $msg['error']='很报歉，只有企业用户才可向投资者发信';
        }elseif(!$sers->is_complete_basic($this->userInfo()->user_id)){//判断是否完善企业基本信息
            $msg['error']='很报歉，您未完善基本信息，去<a target="_black" href="/company/member/basic/editCompany?type=1">完善信息</a>后，才可向投资者发信。';
        }
        else{
            $msg['error']='';
        }
        echo json_encode($msg);exit;
    }

    /**
     * 记录个人用户最近浏览的项目的情况
     * @author 钟涛
     */
    public function action_addPersonAboutPro(){
        $msg['error']='';
        $postdata = Arr::map("HTML::chars", $this->request->post());
        $projectid=arr::get($postdata,'projectid',0);
        $service=new Service_Platform_Project();
        if($this->isLogins()){
            if($projectid){
                 $user_id =$this->userInfo()->user_id;
                 $user_type =$this->userInfo()->user_type;
                 if($user_type==2){//个人用户添加活跃度by钟涛
                     $count=$service->getPersonBehaviourCount($user_id,$projectid);
                     if($count==0){
                         $ser1=new Service_User_Person_Points();
                         $ser1->addPoints($user_id, 'view_project');//查看项目
                     }
                 }
                 $result=$service->addPersonBehaviour($user_id,$projectid,1);
             }
            echo json_encode($msg);exit;
        }else{//未登录情况
            if($projectid){
                $result=$service->addPersonBehaviour(0,$projectid,1);
            }
            echo json_encode($msg);exit;
        }
    }

    /**
     * 记录个人用户最近浏览的招商会的情况
     * @author 钟涛
     */
    public function action_addPersonAboutInv(){
        $msg['error']='';
        $postdata = Arr::map("HTML::chars", $this->request->post());
        //招商会id
        $investmentid=arr::get($postdata,'investmentid',0);
        $service=new Service_Platform_Project();
        if($this->isLogins()){
            if($investmentid){
                 $user_id =$this->userInfo()->user_id;
                 $result=$service->addPersonBehaviour($user_id,$investmentid,2);
             }
            echo json_encode($msg);exit;
        }else{//未登录情况
            if($investmentid){
                $result=$service->addPersonBehaviour(0,$investmentid,2);
            }
            echo json_encode($msg);exit;
        }
    }

    /**
     * 个人收藏项目
     * @author 钟涛
     */
    public function action_watchProject(){
        //判断是否登录
        if(!$this->isLogins()){
            $msg['error'] = 'notlogin';
            echo json_encode($msg);exit;
        }
        $postdata = $this->request->post();
        $projectid=arr::get($postdata,'project_id');
        //获取登录user_id
        $user_id =$this->userInfo()->user_id;
        $service=new Service_Platform_Project();
        //判断用户类型
        $usertpye=$this->userInfo()->user_type;
        $msg['error']='';
        if($usertpye !=2){
            $msg['error'] = '<p class="errorbox">很抱歉，只能个人用户才能够收藏项目哦！</p>';
        }else if(!intval($user_id) || !intval($projectid)){
            $msg['error'] = '<p class="errorbox">很抱歉，页面有误,请稍后重试！</p>';
        }
        else{
            //个人收藏企业项目
            $result=$service->addProjectWatchInfo($user_id,$projectid);
        }
        echo json_encode($msg);
    }

    /**
     * 个人取消收藏项目
     * @author 钟涛
     */
    public function action_cancelwatchProject(){
        //判断是否登录
        if(!$this->isLogins()){
            $msg['error'] = 'notlogin';
            echo json_encode($msg);exit;
        }
        $postdata = $this->request->post();
        $projectid=arr::get($postdata,'project_id');
        //获取登录user_id
        $user_id =$this->userInfo()->user_id;
        $service=new Service_Platform_Project();
        $msg['error']='';
        if(!intval($user_id)){
            $msg['error'] = '<p class="errorbox">很抱歉，页面有误,请稍后重试！</p>';
        }else if($projectid){
            $projectarr= explode(",", $postdata['project_id']);
            //添加新的递出的名片
            $result=$service->updateProjectWatchInfo($user_id,$projectarr);
        }else{
            $msg['error'] = '<p class="errorbox">很抱歉，页面有误,请稍后重试！</p>';
        }
        echo json_encode($msg);
    }

    /**
     * 认领项目
     * @author 钟涛
     */
    public function action_renlingProject(){
        //判断是否登录
        if(!$this->isLogins()){
            $msg['error'] = 'notlogin';
            $msg['type'] = '1';//未登录
            echo json_encode($msg);exit;
        }else{
        	//判断用户类型
        	$usertpye=$this->userInfo()->user_type;
        	if($usertpye ==2){
        		$msg['type'] = '2';//个人用户登录
        	}else{
        		$msg['type'] = '3';//企业用户登录
        	}
            $msg['error'] = '';
            echo json_encode($msg);
        }
    }

    /**
     * 记录个人用户分享项目&资讯时添加活跃度
     * @author 钟涛
     */
    public function action_addPointsByPerson(){
        if($this->isLogins()){
            $userinfo=$this->userInfo();
            $user_id =$userinfo->user_id;
            $user_type =$userinfo->user_type;
            if($user_type==2){//个人用户添加活跃度by钟涛
                $ser1=new Service_User_Person_Points();
                $ser1->addPoints($user_id, 'share_project');//分享项目&资讯
            }
            echo json_encode('');exit;
        }else{//未登录情况
            echo json_encode('');exit;
        }
    }

    /**
     * 添加从项目官网链接到公司网址记录信息
     * @author 钟涛
     */
    public function action_addProjectOutLog(){
        if($this->request->is_ajax()){
            //判断是否登录
            if($this->isLogins()){
                $userid= $this->userId();
            }else{
                $userid= 0;
            }
            $postdata = Arr::map("HTML::chars", $this->request->post());
            if(arr::get($postdata,'projectid',0) && arr::get($postdata, 'hrefurl','')){
                $service=new Service_Platform_Project();
                $result=$service->addProjectOutLog($userid,$postdata);
            }
        }
    }
    /**
     * 获取发信的信件内容[模板信件和精选信件]
     * @author 赵路生
     */
    public function action_getLetter(){
        if($this->request->is_ajax()){
            //注意判断是否登录
            if($this->isLogins() && $this->userInfo()->user_type == 1){
                $login_userid= $this->userId();
                $postdata = Arr::map("HTML::chars", $this->request->post());
                $service=new Service_Platform_SearchInvestor();
                //调用模板信件
                $tem_letter = $service->getTemLetter($login_userid);
                echo json_encode(html_entity_decode($tem_letter));
            }else{
                echo '';
            }
        }
    }
    /**
     * 更新模板信件内容
     * @author 赵路生
     */
    public function action_updateTemLetter(){
        if($this->request->is_ajax()){
            //注意判断是否登录
            if($this->isLogins() && $this->userInfo()->user_type == 1){
                $login_userid= $this->userId();
                $user_type =$this->userInfo()->user_type;
                $postdata = Arr::map("HTML::chars", $this->request->post());
                $modify_text = $postdata['modify_text'];
                $service=new Service_Platform_SearchInvestor();
                //调用方法
                $res = $service->updateTemLetter($login_userid,$modify_text,$user_type);
                if($res){
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * 发信
     * @author 赵路生
     */
    public function action_sendLetter(){
        if($this->request->is_ajax()){
            //注意判断是否登录
            if($this->isLogins() && $this->userInfo()->user_type == 1){
                $login_userid= $this->userId();
                $user_type =$this->userInfo()->user_type;
                $postdata = Arr::map("HTML::chars", $this->request->post());
                $to_user_id = $postdata['to_user_id'];
                $checked_val = $postdata['checked_val'];
                $service=new Service_Platform_SearchInvestor();
                //调用方法

            }
        }
        return false;
    }
    /**
     * 获取一个个人用户数据
     * @author 钟涛
     */
    public function action_getOnePersonInfo(){
        if($this->request->is_ajax()){
            //判断是否登录 没有登录即跳转到登录页
            $this->isLogin();
            $user_id = $this->userId();
            $postdata = Arr::map("HTML::chars", $this->request->post());
            $servicedate=new Service_Platform_SearchInvestor();
            $result=$servicedate->getOneInvestorInfo($user_id,$postdata);
            if($result!=''){
                $result2=$servicedate->getOneInvestorInfoHtml($result[0],$postdata['perk']);
                echo json_encode($result2);
            }else{
                $result['error']='nodate';
                echo json_encode($result);
            }
        }
    }
    /**
     * 获取搜索投资者页面热门词汇
     * @author 赵路生
     */
    public  function action_getTag(){
    	$tag = array();
        if($this->request->is_ajax()){
            $service = new Service_Platform_SearchInvestor();
            $tag = $service->findTag();         
        }
        echo json_encode($tag);
    }
    /**
     * 点击发信前 检查状态 是否已经在另一个页面已经点击过 && 同时检查是否处于登录状态
     * @author 赵路生
     */
    public  function action_checkSendLetterStatus(){
        if($this->request->is_ajax()){
            //判断是否登录 没有登录即跳转到登录页
            if($this->isLogin()){
                //获取登录user_id
                $login_userid =$this->userInfo()->user_id;
                $user_type = $this->userInfo()->user_type;
                if($user_type == 2){
                    $checkStatusResult = 1;//检查当前登录状态为个人用户登录
                    echo json_encode($checkStatusResult);
                    exit;
                }
                $postdata = Arr::map("HTML::chars", $this->request->post());
                if($postdata['to_user_id']){
                    $userid = $postdata['to_user_id'];
                }else{
                    self::redirect("zhaotouzi/");
                }
                $card_service=new Service_Card();
                if($card_service->getExchaneCardCountByTwoIdAll($userid, $login_userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($userid,$login_userid)){
                    $checkStatusResult = 2;//当前已经交换
                    echo json_encode($checkStatusResult);
                    exit;
                }elseif($card_service->getOutCardCountByTwoIdAll($userid, $login_userid)){//已递出
                    $query1 = DB::select()->from('card_info')->where('from_user_id', '=', $login_userid)->and_where('to_user_id', '=', $userid)->and_where('exchange_status', '=', 0);
                    $result1 = $query1->execute()->as_array();
                    if(isset($result1[0]['send_time'])){//记录递出名片时间[7天后又可重复递出]
                        $result['sendtime'] = $result1[0]['send_time'];
                    }else{
                        $result['sendtime'] = 0;
                    }
                    if(time()-(604800+$result['sendtime'])>0){
                        $checkStatusResult = 4;//递出名片，并且递出名片时间超过7天
                        echo json_encode($checkStatusResult);
                        exit;
                    }else{
                        $checkStatusResult = 3;//递出名片，并且时间没超过7天，不能重复递出
                        echo json_encode($checkStatusResult);
                        exit;
                    }
                }else{
                    $checkStatusResult = 6;//表示能够递出交换，一切正常
                    echo json_encode($checkStatusResult);
                    exit;
                }//end
            }else{
                $checkStatusResult = 5;//未登录
                echo json_encode($checkStatusResult);
                exit;
            }
        }
    }
    /**
     * 登录验证
     * @author 曹怀栋
     * @modify by 龚湧 2012.12.20
     */
    public function action_checkLogin(){
        $post = $this->request->post();
        if(!Captcha::valid($post['valid_code'])){
            $error = array('captcha'=>"验证码错误");
        }else{
            $user = new Service_User();
            $result = $user->loginCheck($post);
            if($result == 1){
                $users =Auth::instance()->login(arr::get($post,'email'),arr::get($post,'password'),arr::get($post,'remember'));
                if ($users === 'email'){
                    $error = array('email'=>"用户名不存在");
                }elseif ($users === 'password'){
                    $error = array('password'=>"密码不正确");
                }else{
                    $error = array('result'=>'输入正确');
                }
            }else{
                $error = $result;
            }
        }
        echo json_encode($error);
    }

    /**
     * 清空当前配置
     * @author 龚湧
     */
    public function action_clearGuide(){
        $service = new Service_Platform_Search();
        if($this->isLogins()){
            $user_id = $this->userId();
            $service->clearLoginConfig($user_id);
            $service->clearNotLoginConfig();
        }
        else{
            $service->clearNotLoginConfig();
        }
    }

    /**
     * 帮助中心-用户反馈
     * @author 钟涛
     */
    public function action_feedback(){
        if($this->request->is_ajax()){
            $post = $this->request->post();
            $service = new Service_Platform_Help();
            $result=$service->liuyan($post);
            echo json_encode($result);
        }
    }

    /**
     * 删除项目认领中的指定的一张图片
     * @author 钟涛
     */
    public function action_deleteRenlingProjectImg(){
        $get = Arr::map("HTML::chars", $this->request->post());
        $service = new Service_Platform_Project();
        $result = $service->deleteRenlingProjectImages(arr::get($get,'id'));
        $rea=array();
        if($result){
            $rea['error']='';
            //self::redirect("platform/project/writeProjectInfo?project_id=".arr::get($get,'project_id'));
        }else{
            $rea['error']='删除失败';
        }
        echo json_encode($rea);
    }

    /**
     * 项目的 赞
     * @author 郁政
     */
    public function action_addApproving(){
        $result = array();
        $post = $this->request->post();
        $loginStatus = $this->isLogins();
        $user_id = $loginStatus ? $this->userInfo()->user_id : 0;
        $project_id = intval($post['project_id']);
        $service = new Service_Platform_Project();
        $res = $service->addApproving($user_id, $project_id);
        if($res){
            $result['status'] = 1;
        }else{
            $result['status'] = 0;
        }
        echo json_encode($result);
    }
    /**
     * 随机获取5个标签
     * @author 嵇烨
     */
    public function action_getTags(){
           #定义数组
        $service = new Service_Platform_Search();
        $arr_return_data = $service->getTags();
        echo  json_encode($arr_return_data);
    }

    /**
     * 增加一条统计记录
     * @author许晟玮
     */
    public function action_setVisit(){

        $service= new Service_Api_Stat();
        $sid= '';
        $fromurl= URL::website('/');
        $pageurl= '';
        $ip= Request::$client_ip;
        $ipregion= common::convertip( Request::$client_ip );
        $useragent= Request::$user_agent;
        $typeid= '4';
        $pnid= Arr::get($this->request->post(), 'projectid');
        $service->setVisit( $sid,$fromurl,$pageurl,$ip,$ipregion,$useragent,$typeid,$pnid );

    }
    /**
     * 循环数据处理
     * @author jiye
     * @create time 2014/3/17
     */
    public function DoArrProject($data,$num = 6){
    	$str_content = "";
    	$xuanchuanModel = new Service_Platform_Search();
    	if($data){ $i =0;
    		foreach ($data as $key=>$val){$i++;
	    		if($i > $num){break;}
	    		if($i == $num){
	    			$str_content .= "<li class='last'>";
	    		}else{
	    			$str_content .= "<li>";
	    		}
	    		$img = "";
	    		#找去项目小图
	    		if(($val['project_source'] == 5 || $val['project_source'] == 4) && $val['project_logo']) {
	    			$val['project_logo'] = str_replace("poster/html/ps_{$val['outside_id']}/project_logo/", "poster/html/ps_{$val['outside_id']}/project_logo/150_120/", $val['project_logo']);
	    		}
	    		$img =  URL::imgurl($val['project_logo']);
	    		$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
	    		if($xuanchuanimage){ $img = $xuanchuanimage;};
	    		$onerror_imge = URL::webstatic('/images/common/company_default.jpg');
	    		#项目名
	    		if($val['project_advert']){ $project_brand_name = mb_substr($val['project_advert'], 0,16,'UTF-8')."" ;}else{ $project_brand_name = mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";}
	    		#图片
	    		$str_content .="<p class='img'><label><a href='".urlbuilder::project($val['project_id'])."' target='_blank' title='创业项目".$project_brand_name."'> <img src='{$img}' alt='创业项目{$project_brand_name}' onerror=\"$(this).attr('src','{$onerror_imge}')\" ></a></label></p>";
	    		$str_content .="<span><a href='".urlbuilder::project($val['project_id'])."' target='_blank' title='".$project_brand_name."'>".$project_brand_name."</a></span>";
	    		#金额
	    		$monarr= common::moneyArr();
	    		$money = arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];
	    		$str_content	.="<span class='browse_pro_money'>￥<em>".$money."</em></span>";
	    		#气人
	    		$project_pv_count = 1;
	    		if($val['project_pv_count'] != 0){ $project_pv_count = $val['project_pv_count'];}
	    		#发源地
	    		$project_brand_birthplace = $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";
	    		$str_content .="<div><p class='p_01'><a title='".$val['project_brand_birthplace']."' href='".urlbuilder::project($val['project_id'])."'>".$project_brand_birthplace."<em class='browsed_fc01'>品牌发源地</em></a></p><p class='p_02'><a href='".urlbuilder::project($val['project_id'])."'>".$project_pv_count."<em class='browsed_fc02'>项目人气</em></a></p></div>";
	    		$str_content .= "</li>";
    		}
    	}
    	return $str_content;
    }
	/**
     * 首页静态化加载
     * @author 嵇烨
     * return string
     */
	public function action_indexList(){
		$post = $this->request->post();
		$type = isset($post['type']) ? $post['type']:intval(1);
		$service= new Service_Platform_Search();
		$xuanchuanModel = new Service_Platform_Search();
		$Platform_ProjectGuide = new Service_Platform_ProjectGuide();
		$str_content = "";
		$user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
		$history_project = Cookie::get('history_project') ? unserialize(Cookie::get('history_project')) : array();
		$arr_return ['project_list'] = "";
		$arr_return ['YouMayLikeProject'] = "";
		$arr_project_list =array();
		if($user_id == 0 && empty($history_project)){
			$arr_return ['project_list'] = $str_content;
		}else{
			//拼装数据
			$arr_list =$service->getYouLookTuiJian(3);
			#第一次条浏览数据
			if(arr::get($arr_list, "first_item")){
				$str_content = "<div class='browse_record_title'><h2>根据浏览记录为您推荐</h2></div><div class='browsed_record_list'><span class='cur_01 cur'>您浏览过</span><span class='cur_02'>查看此项目的用户也查看了</span></div>";
				$str_content .="<ul class='browse_list'>";
				$str_content .=$this->DoArrProject(arr::get($arr_list, "first_item"),6);
				$str_content .="</ul>";
				$str_content .="<div class='clear'></div>";
				$str_content .="<div class='browse_view_more'><a rel='nofollow' href='".urlbuilder::root('del')."'  title='查看或编辑您最近浏览过的项目'>&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>";
			}
			#第二条数据
			if(arr::get($arr_list, "second_item")){
				$str_content .= "<div class='browse_record_title'><h2>根据浏览记录为您推荐</h2></div><div class='browsed_record_list'><span class='cur_01 cur'>您浏览过</span><span class='cur_02'>查看此项目的用户也查看了</span></div>";
				$str_content .=" <ul class='browse_list'>";
				$str_content .=$this->DoArrProject(arr::get($arr_list, "second_item"),6);
				$str_content .="</ul>";
				$str_content .="<div class='clear'></div>";
				$str_content .="<div class='browse_view_more'><a rel='nofollow' href='".urlbuilder::root('del')."'  title='查看或编辑您最近浏览过的项目'>&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>";
			}
			#第三条数据
			if(arr::get($arr_list, "third_item")){
					$str_content .= "<div class='browse_record_title'><h2>更多供您选择的项目</h2></div>";
					$str_content .="<ul class='browse_list'>";
					$str_content .=$this->DoArrProject(arr::get($arr_list, "third_item"),6);
					$str_content .="</ul>";
					$str_content .="<div class='clear'></div>";
					$str_content .= "<div class='kong_01'></div>";
			}
			#处理您可能喜欢的创业项目
			$ip = ip2long(Request::$client_ip);
			$user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
			$arr_have_look = $service->_getVistedLog($user_id, $ip, 1, 0);
			$arr_project_id =array();
			$arr_project_id_new =array();
			//获取一级行业
			$int_IndustryId = $service->_getProjectIndustryId(arr::get($arr_have_look[0],'operate_id'),2);
			//if($int_IndustryId){
				//获取config配置数据
// 				$project_ids = common::projectListImpAd();
// 				if($project_ids){
// 					foreach ($project_ids as $key=>$val){
// 						$IndustryId = $service->_getProjectIndustryId($val,2);
// 						if($IndustryId == $int_IndustryId){
// 							$arr_project_id = $val;
// 						}
// 					}
// 				}
				//配置文件数据
				$arr_project_ids = array_rand(common::projectListImpAd(),10);
				foreach ($arr_project_ids as $key=>$val){
					$arr_project_id [] = $val;
				}
				
				$arr_project_list = $Platform_ProjectGuide->GetProjectByArr($arr_project_id);
// 				//获取数据
// 				if($arr_project_id){
// 					$arr_project_id_new = array_unique($arr_project_id);
// 				}
// 				if($arr_project_id_new){
// 					$arr_project_list = $Platform_ProjectGuide->GetProjectByArr($arr_project_id_new);
// 				}
				if(count($arr_project_list) < 6){
					//彩蛋程序(填充数据)
					$arr_linshi_id = $Platform_ProjectGuide->getSpecialPro((6-count($arr_project_list)),1);
					$arr_linshi_list = $Platform_ProjectGuide->GetProjectByArr($arr_linshi_id);
					if($arr_project_list){
						$arr_project_list = @array_merge($arr_project_list,$arr_linshi_list);
					}else{
						$arr_project_list =$arr_linshi_list;
					}
				}
			//}
			$arr_return ['YouMayLikeProject'] = $this->DoArrProject($arr_project_list,6);
			$arr_return ['project_list'] = $str_content;
		}
		echo  json_encode($arr_return);exit;
	}
	/**
	 * 找项目首页静态化
	 * @author 嵇烨
	 * retur array
	 */
		public function action_findProjectIndex(){
			$service= new Service_Platform_Search();
			$xuanchuanModel = new Service_Platform_Search();
			$arr_return['project_content'] = array();
			$str_content = "";
			//拿取数据  接口
			$data = $service->getYouLookTuiJian(2);
			$user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
			$history_project = Cookie::get('history_project') ? unserialize(Cookie::get('history_project')) : array();
			if($user_id == 0 && empty($history_project)){
				$arr_return['project_content'] = $str_content;
			}
			if(arr::get($data, "first_item") || arr::get($data,"second_item")){
				$str_content = "<div class='browse_record_title'><span><h2>根据浏览记录为您推荐</h2></span></div>";
				$str_content .= "<div class='browsed_record_list'><span class='cur_01 cur'>您浏览过的项目</span><span class='cur_02'>浏览此项目的用户也浏览了以下项目</span></div>";
			}
			//第一条数据
			if(arr::get($data, "first_item")){
				$str_content .="<ul class='browse_list'>";
				$str_content .=$this->DoArrProject(arr::get($data, "first_item"),5);
				$str_content .= "<div class='clear'></div></ul><div class='clear'></div>";
			}
			//第二条数据
			if(arr::get($data,"second_item")){
				$str_content .= "<ul class='browse_list'>";
				$str_content .=$this->DoArrProject(arr::get($data,"second_item"),5);
				$str_content .= "<div class='clear'></div></ul><div class='clear'></div></ul>";
			}
			//查看您更多的浏览记录显示
			if(arr::get($data, "first_item") || arr::get($data,"second_item")){
				$str_content .="<div class='browse_view_more'><a href='".urlbuilder::root("del")."' target='_blank' title='查看或编辑您最近浏览过的项目'>&gt;&nbsp;查看您更多的浏览记录</a></div>";
			}
			$arr_return['project_content'] = $str_content;
			echo json_encode($arr_return);
			
			
		}

	/**
	 * 添加搜索记录
	 * @author 郁政	 
	 */
	public function action_saveSearchRecord(){				
		if($this->request->is_ajax()){
			$post = Arr::map("HTML::chars", $this->request->post());
			$search=new Service_Platform_Search();
			$search_record = array();
			#获取用户user_id
	        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;		
			#获取用户
	        $ip = ip2long(Request::$client_ip);
	        $search_record['user_id'] = $user_id;
	        $search_record['ip'] = $ip;
	        $search_record['key_word'] = secure::secureInput(secure::secureUTF(Arr::get($post, 'keyword')));
	        $search_record['search_time'] = time();
	        print_r($search_record);
	        $search->saveSearchRecord($search_record);
		}
		return true;	
	}
        
        /**
         * 添加搜索点击记录
         * @author 施磊
         */
        public function action_saveSearchClick(){
            if($this->request->is_ajax()){
			$post = Arr::map("HTML::chars", $this->request->post());
			$search=new Service_Platform_Search();
			$search_record = array();
			#获取用户user_id
                        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;		
                                #获取用户
                        $ip = ip2long(Request::$client_ip);
                        $search_record['user_id'] = $user_id;
                        $search_record['user_ip'] = $ip;
                        $search_record['search_word'] = secure::secureInput(secure::secureUTF(Arr::get($post, 'search_word')));
                        $search_record['click_id'] = Arr::get($post, 'click_id', 1);
                        $search_record['type'] = Arr::get($post, 'type', 1);
                        $search_record['click_seat'] = Arr::get($post, 'click_seat', 1);
                        $search_record['click_hot_zone'] = Arr::get($post, 'click_hot_zone', 1);
                        $search_record['time'] = time();
                        print_r($search_record);
                        $search->saveSearchClick($search_record);
			}
			return true;
        }
        /**
         * 统计项目pv
         * @author 嵇烨
         * date 2013/11/8
         * return true or false
         */
        public function action_TongJiProjectPv(){
        	if($this->request->is_ajax()){
        		$post = Arr::map("HTML::chars", $this->request->post());
        		$service = new Service_Platform_Project();
        		$return_data = array();
        		$return_data['status'] = $service->insertProjectStatistics(arr::get($post, "project_id"),arr::get($post, "type"));
        		return $return_data;
        	}
        }

    /**
     * 生成左侧日历数据
     * @author 花文刚
     */
    public function action_getIndustryNumBydata(){
        $service = new Service_Platform_Invest();
        $post = Arr::map("HTML::chars", $this->request->post());

        $ym = arr::get($post, "ym");
        $IndustryNumBydata_data_left = $service->getIndustryNumBydate(0,$ym);
        echo json_encode($IndustryNumBydata_data_left);

    }

    /**
     * 获取随机展会
     * @author 花文刚
     * $num 随机数量
     * $type 1-正在开展 2-即将开展 3-已结束的
     */
    public function action_getRandExhibition(){
        $service = new Service_Platform_Exhibition();
        $post = Arr::map("HTML::chars", $this->request->post());

        $type = arr::get($post, "type");
        $num = arr::get($post, "num");
        //即将开展的
        $to_show = $service->getExhibitionShow($type,0);
        $rand_key = array_rand($to_show,$num);
        $to_show_rand = array();
        for($i=0;$i<$num;$i++){
            $to_show_rand[] = $to_show[$rand_key[$i]];
        }
        echo json_encode($to_show_rand);
    }
    
	/**
	 * 瀑布流  展会分类
	 * @author 共产党
	 * 
	 */
    public function action_getWaterfallList(){
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$service = new Service_Platform_Exhibition();
    	$arr_data = array();
    	$arr_data = $service->getWaterfallList($get);
    	echo json_encode($arr_data);
    }
    /**
     * 瀑布流  展会分类分页
     * @author 共产党
     *
     */
    public function action_getWaterfallListPage(){
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$service = new Service_Platform_Exhibition();
    	$arr_data = array();
    	$arr_data = $service->getWaterfallList($get);
    	//echo "<pre>"; print_r($arr_data);exit;
    	echo $arr_data['page'];
    }
    
        /**
         *网络展会统计
         * @author stone shi
         * @author type 1为展会项目官网， 2 为项目在线交流 3为 名片
         */
        public function action_ExhbTj(){
        	if($this->request->is_ajax()){
        		$post = Arr::map("HTML::chars", $this->request->post());
                        $project_id = arr::get($post, 'project_id', 0);
                        if(!$project_id) { echo json_encode('error1');exit;}
                        $type = arr::get($post, 'type', 1);
                        $user_id = $this->userId ();
        		$service = new Service_Platform_ExhbProject();
        		$return = $service->addProStatistics($project_id, $user_id, $type);
                        echo json_encode('succ');exit;
        	}
        }

        
	/**
     * 修改优惠劵数量
     * @author 郁政
     */

	public function action_updateCouponNum(){
		if($this->request->is_ajax()){
			$post = Arr::map("HTML::chars", $this->request->post());
			$project_id = Arr::get($post, 'project_id', 0);
			$num = Arr::get($post, 'num',0);
			$service = new Service_Platform_ExhbProject();
			$res = $service->updateCouponNum($project_id, $num);
			echo json_encode($res);
			exit;
		}
	}

        
    /**
     * 获得数据
     * @author stone shi
     */
    public function action_showProjectPvApi() {
        $get = Arr::map("HTML::chars", $this->request->query());
        $projectid= Arr::get($get, 'pid','0');
        $begin = Arr::get($get, 'begin');
        $end = Arr::get($get, 'end');
        $service = new Service_User_Company_Exhb();
        $projectinfo = $service->getProjectPv($projectid, $begin, $end);
        echo json_encode($projectinfo);
    }
    
    /**
     * 客服账户的唯一性
     * @author ++ NM
     */
    public function action_CheckKefu(){
    	if($this->request->is_ajax()){
    		$int_return = 2;
    		$post = Arr::map("HTML::chars", $this->request->post());
    		$kefu_name = arr::get($post, "kefuName");
    		if($kefu_name){
    			$service = new Service_Platform_ExhbProject();
    			$int_return = $service->CheckKefu($kefu_name);
    		}
    		echo json_encode($int_return);
    		exit;
    	}
    }
    
    /**
     * 获取升级项目的信息
     * @author  党中央  
     */
    public function action_GetProjectUpgradeInfo(){
    	if($this->request->is_ajax()){
    		$post = Arr::map("HTML::chars", $this->request->post());
    		//echo "<pre>"; print_R($post);exit;
    		$Service_Company = new Service_User_Company_User();
    		//伪造数据   待确定    数据
    		$arr_data = array();
    		$ProjectUpgradeInfo = array();
    		$com_info =  $Service_Company->getCompanyInfo($this->userid());
    		$ProjectUpgradeInfo = $Service_Company->GetProjectUpgradeInfo(null,arr::get($post,"project_id"));
    		//echo "<pre>"; print_r($ProjectUpgradeInfo);exit;
    		$user = Service_Sso_Client::instance()->getUserInfoById( $this->userid() );
    		$arr_data['id'] = arr::get($ProjectUpgradeInfo,"project_id",0);
    		$arr_data['com_name'] = $com_info->com_name;
    		$arr_data['contact_name'] = arr::get($ProjectUpgradeInfo, "contact_name") ? arr::get($ProjectUpgradeInfo, "contact_name") : $com_info->com_contact;
    		$arr_data['mobile'] = arr::get($ProjectUpgradeInfo, "mobile") ? arr::get($ProjectUpgradeInfo, "mobile") :  $user->mobile;
    		$arr_data['email'] = arr::get($ProjectUpgradeInfo, "email")? arr::get($ProjectUpgradeInfo, "email") :$user->email;
    		$arr_data['address'] = arr::get($ProjectUpgradeInfo, "address") ? arr::get($ProjectUpgradeInfo, "address") : $com_info->com_adress;
    		echo json_encode($arr_data);
    	}
    }
	/**
	 * 执行升级项目的信息
	 * @author  党中央  jack 
	 */
    public function action_DoProjectUpgradeInfo(){
    	if($this->request->is_ajax()){
    		$post = Arr::map("HTML::chars",$this->request->post());
    		//echo "<pre>"; print_R($post);exit;
    		$Service_Company = new Service_User_Company_User();
    		$arr_data = array();
    		$arr_array = array();
    		$arr_array['project_id'] = arr::get($post,"shenqing_id");
    		$arr_array['out_project_id'] = arr::get($post,"project_new_id");
    		$arr_array['email'] = arr::get($post,"email");
    		$arr_array['contact_name'] = arr::get($post,"contact_name");
    		$arr_array['mobile'] = arr::get($post,"mobile");
    		$arr_array['address'] = trim(arr::get($post,"address"));
    		$arr_array['add_time'] =time();
    		$arr_array['user_id'] = $this->userid();
    		$arr_array['admin_id'] = 0;
    		$int = $Service_Company->DoProjectUpgradeInfo($arr_array);
    		$com_info =  $Service_Company->getCompanyInfo($this->userid());
    		$arr_data['status'] = $int;
    		$arr_data['com_name'] = $com_info->com_name;
    		echo json_encode($arr_data);
    	}
    }
  
}
