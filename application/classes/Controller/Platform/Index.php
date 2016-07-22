<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 新平台前端页面首页
 * @author 钟涛
 *
 */
class Controller_Platform_Index extends Controller_Platform_Template{
    private $_sta_project_index = 'sta_project_index';
    private $_new_project_index = 'new_project_index';
    private $_cache_get_index_time = 86400;
    //快速注册
    private $_cache_get_project_total   = 'getProjectTotal';
    private $_cache_get_user_total      = 'getUserTotal';
    private $_cache_get_total_time      = 86400;

     /**
     * 前端页面首页
     * @author 嵇烨
     */
    public function action_index(){
		
    	$content = View::factory('quickPublish/index');
    	$this->template->content = $content;
    	$obj_service =  new Service_QuickPublish_ProjectComplaint();
    	$arr_data = $obj_service->IndexList();
    	//  echo "<pre>"; print_r($arr_data);exit;
    	$content->arr_data = $arr_data;
    	
//         #获取类型
//         $get = secure::secureInput(secure::secureUTF(isset($_GET['type'])?$_GET['type']:1));
//         $content = View::factory('platform/home/index');
//          #获取用户user_id
//          //$user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
//          $service =new Service_Platform_Search();
//          #首页默认的时候 没有任何的浏览记录(有浏览记录通过ajax 处理)
//          $data = $service->getProjectInfoToIndex(1);
//             #资讯内容推荐
//          $Service_News_Article = new Service_News_Article();
//          $memcache = Cache::instance('memcache');
//         #创业投资前沿趋势开始
//         $arr_return_News_Article = array();
//         $arr_return_News_Article = $memcache->get("chuangyetouzi");
//         if(empty($arr_return_News_Article)){
//             $arr_data_News_Article = $Service_News_Article->getListByColumnId(intval(1),intval(1),intval(6));
//             $arr_News_Article = array();
//             foreach ($arr_data_News_Article['list'] as $key=>$val){
//                 $arr_News_Article[] = $val->as_array();
//             }
//             $arr_return_News_Article = array();
//             if(!empty($arr_News_Article)){
//                 $arr_return_News_Article = self::do_Is_Have_Image($arr_News_Article);
//             }
//             $memcache->set("chuangyetouzi", $arr_return_News_Article,86400);
//         }
//         #资讯内容推荐结束
//         #行业加盟开始
//         $arr_return_hangyetoushi = array();
//         $arr_return_hangyetoushi = $memcache->get("hangyetoushi");
//         if(empty($arr_return_hangyetoushi)){
//             $arr_data_hangyetoushi = $Service_News_Article->getListByColumnId(intval(6),intval(1),intval(6));
//             $arr_hangyetoushi_data = array();
//             foreach ($arr_data_hangyetoushi['list'] as $key=>$val){
//                 $arr_hangyetoushi_data[] = $val->as_array();
//             }
//             $arr_return_hangyetoushi = array();
//             if(!empty($arr_hangyetoushi_data)){
//                 $arr_return_hangyetoushi = self::do_Is_Have_Image($arr_hangyetoushi_data);
//             }
//             $memcache->set("hangyetoushi",$arr_return_hangyetoushi,86400);
//         }
//         #行业加盟结束
//         #创业开店开始
//         $arr_return_chuangye = array();
//         $arr_return_chuangye = $memcache->get("chuangyekaidian");
//         if(empty($arr_return_chuangye)){
//             $str_sql_chuangye = $Service_News_Article->getListByColumnId(intval(3),intval(1),intval(6));
//             $arr_chuangye_data = array();
//             foreach ($str_sql_chuangye['list'] as $key=>$val){
//                 $arr_chuangye_data[] = $val->as_array();
//             }
//             $arr_return_chuangye = array();
//             if(!empty($arr_chuangye_data)){
//                 $arr_return_chuangye = self::do_Is_Have_Image($arr_chuangye_data);
//             }
//             $memcache->set("chuangyekaidian",$arr_return_chuangye,86400);
//         }
//         #创业开店结束
//         #开店经营开始
//         $arr_return_kaidian = array();
//         $arr_return_kaidian = $memcache->get("kaidian");
//         if(empty($arr_return_kaidian)){
//             $str_sql_kaidian = $Service_News_Article->getListByColumnId(intval(4),intval(1),intval(6));
//             $arr_kaidian_data = array();
//             foreach ($str_sql_kaidian['list'] as $key=>$val){
//                 $arr_kaidian_data[] = $val->as_array();
//             }
//             if(!empty($arr_kaidian_data)){
//                 $arr_return_kaidian = self::do_Is_Have_Image($arr_kaidian_data);
//             }
//             $memcache->set("kaidian",$arr_return_kaidian,86400);
//         }

//         #开店经营结束
//        //echo "<pre>"; print_R($arr_return_News_Article);exit;
//         $content->touziqianyan = $arr_return_News_Article;
//         $content->hangyetoushi = $arr_return_hangyetoushi;
//         $content->chuangye = $arr_return_chuangye;
//         $content->kaidian = $arr_return_kaidian;
//         $content->arr_data = $data;
//         $content->src_type = $get;
        $memcache = Cache::instance ('memcache');
        $friend_link = $memcache->get('friend_cache_index');
        if(empty($friend_link)){
            $f_service = new Service_Platform_FriendLink();
            $friend_link = $f_service->getFriendLinkList('index');
            $memcache->set('friend_cache_index', $friend_link,604800);
        }
        $this->template->friend_link = $friend_link;
        $this->content->maincontent = $content;
        $this->template->title = "一句话生意网-找生意,找商机,免费发布生意,中国最大的生意信息服务平台";
        $this->template->keywords = "一句话,生意网,商机网,找生意,免费发布生意";
        $this->template->description = "一句话生意网为用户提供免费,快速,真实的生意信息发布服务,是中国最大的生意信息服务平台，专业找代理商,加盟商,批发商,代销商,经销商,生意有困难,上一句话生意网,人人信赖的生意信息服务平台。";
    }
    
    /**
     * 首页推荐测试用
     * @author 郁政
     * @return array
     */
    public function action_testTuiJian(){
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$service = new Service_Platform_Search();
    	if(Arr::get($get, "debug") == 1){
    		$res = $service->getYouLookTuiJian(3,1);
    		echo "<pre>";print_r($res);exit;
    	}
    }
    
    /**
     * 特殊处理图片   如果有图片放第一位
     * @author 嵇烨
     */
    protected function  do_Is_Have_Image($arr_data){
        $arr_return_data = array();
        $arr_have_image = array();
        if(is_array($arr_data) && !empty($arr_data)){
            foreach ($arr_data as $key=>$val){
                if($val['article_img'] == ""){
                    $arr_have_no_image[] = $val;
                }else{
                    $arr_have_image[] = $val;
                }
            }
            if(isset($arr_have_no_image)){
                $arr_return_data = array_merge($arr_have_image,$arr_have_no_image);
            }else{
                $arr_return_data = isset($arr_have_image) ? $arr_have_image :"";
            }

        }
        return $arr_return_data;
    }

    /**
     * 清除友情链接cache
     * @author 郁政
     */
    public function action_clear(){
        $memcache = Cache::instance ('memcache');
        $memcache->delete('friend_cache_index');
        $memcache->delete('friend_cache_youqing');
        $memcache->delete('friend_cache_search');
        $memcache->delete('friend_cache_touzikaocha');
        $memcache->delete('friend_cache_zixun');
        exit;
    }

    /**
     * @sso
     * 企业首页
     * @author 钟涛
     */
    public function action_comCenter(){
        $content = View::factory('platform/home/comcenter');
        $this->content->maincontent = $content;
        $this->template->title = "登陆_企业服务 | 一句话";
        $this->template->keywords = "登陆，企业登陆，一句话";
        $this->template->description = "登陆企业服务，一句话搞定！";
        if($this->request->method()== HTTP_Request::POST){
            //登陆前先退出
            if( $this->loginUser()===true ){
                $token = Cookie::get("authautologin");
                Service_Sso_Client::instance()->logout($token);
            }
            $this->content->maincontent = $content;
            $post = Arr::map("HTML::chars", $this->request->post());
            //登录验证
            $service = new Service_User();
            // 判断用户是否为企业用户,如果不是则返回登陆页面
            $lgr    = $service->checkLoginName( $post['email'] );
            if($lgr &&  $lgr->user_type!='1' ){
                $content->error = array('email'=>"请使用企业用户账号登陆");
                $content->emails = $this->request->post('email');
            }else{
                $result = $service->loginCaptcha($post);
                if($result != 1){
                    $content->error = $result;
                    $content->emails = $this->request->post('email');
                }else{
                    $last_login_user_status = ORM::factory("User",$this->loginUserId());
                    $user['user_id'] = $this->loginUserId();
                    $user['last_logintime'] = time();
                    $user['last_login_ip'] = ip2long(Request::$client_ip);


                    //用户信息更新
                    $usertype =$service->updateUser($user);
                    $this->addUserLoginLog($user['user_id'], $usertype);

                    //上次跳转过来的地址
                    $to_url = $this->request->query("to_url");
                    //登录成功跳转
                    $this->userType($usertype,$to_url);
                    //self::redirect("platform/index/comCenter");
                }
            }
        }else{
            if($this->loginUser()){//已经登录的用户
                $content->is_logoin = true;
                $username=$this->userInfo();
                if($username && $username->user_type == 1){//企业用户
                    $user_name= mb_substr($username->user_name,0,17);
                }elseif($username && $username->user_type == 2){//个人用户显示登录框
                    $content->is_logoin = false;
                    $user_name='';
                }else{
                    $user_name='';
                }
                $content->com_name = $user_name;
            }else{
                $content->is_logoin = false;
                $content->com_name = '';
            }
        }
    }

    /**
     * 前端页面首页
     * @author 曹怀栋
     */
    public function action_projectList(){
        $content = View::factory('platform/home/projectlist');
        $this->content->maincontent = $content;
        $service =new Service_Platform_Search();
        $result=$service->getWordSearch(trim($_GET['w']));
        $arr = $service->getProjectSearchList(1,$result);
        $arrlist = $service->getQueryCondition($arr);
        $array_list = $service->getProjectSqlSearch($arrlist);
        //通过企业id取得企业的user_id
        foreach ($array_list['list'] as $k=>&$v){
            $v['com_id'] = $service->getComUserid($v['com_id']);
            //判断是否登录
            if($this->isLogins()){
                $card = $service->getCardInfo($this->loginUserId(),$v['com_id']);
                //判断是否递出名片
                if($card ==true){
                    $v['card'] = "ok";
                }else{
                    $v['card'] = "no";
                }
            }else{
                $v['card'] = "no";
            }
        }
        //项目列表
        $content->project_list = $array_list;
    }

    /**
     * 一句话直搜，搜索标签
     * @author 沈鹏飞
     */

    public function action_search(){
        $is_search = 1;
        if(!isset($_GET['w'])){
            //Kohana::location(URL::website("platform/index"));
            $is_search = $_GET ? 1 : 0;
            $_GET['w'] = '';
        }
        ##########公用部分############
        $search=new Service_Platform_Search();
        $serviceGuide = new Service_Platform_ProjectGuide ();
        $memcache = Cache::instance ( 'memcache' );
        #获取用户
       // $ip = ip2long(Request::$client_ip);
        #获取用户user_id
       // $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
        #找项目首页
       // $data_key = 'getProjectInfoByIpOrUserId'.$ip.$user_id;
        $_GET['page']=secure::secureInput(secure::secureUTF(arr::get($_GET,'page',1)));
        #############################

        if($is_search == 1){
            $_GET['w'] = $wordShow = $inputvalue =  secure::secureInput(secure::secureUTF($_GET['w']));

            if($inputvalue=='请输入您要搜索的条件。如： 餐饮 10万 上海'){
                $_GET['w'] = $wordShow = '';
            }else{
                $_GET['w'] = $wordShow = $inputvalue;
            }

            $cond = array();
            $cond['sort'] = secure::secureInput(intval(arr::get($_GET, 'sort', 1)));

            #行业id
            $cond['industry_id'] = secure::secureInput(intval(arr::get($_GET, 'parent_id', 0)));
            $cond['inid'] = secure::secureInput(intval(arr::get($_GET, 'parent_id', 0)));
            #地区id
            $cond['area_id'] = secure::secureInput(intval(arr::get($_GET, 'per_area_id', 0)));
            $cond['areaid'] = secure::secureInput(intval(arr::get($_GET, 'per_area_id', 0)));
            #投资金额
            $cond['project_amount_type'] = secure::secureInput(intval(arr::get($_GET, 'per_amount', 0)));
            $cond['atype'] = secure::secureInput(intval(arr::get($_GET, 'per_amount', 0)));
            $seachAmount = searchservice::getInstance()->getWordAmount($wordShow, 0);

            #投资风险
            $cond['risk'] = secure::secureInput(intval(arr::get($_GET, 'risk', 0)));
            #招商形式
            $cond['project_model'] = secure::secureInput(intval(arr::get($_GET, 'pmodel', 0)));
            $cond['pmodel'] = secure::secureInput(intval(arr::get($_GET, 'pmodel', 0)));
            #有无招商会
            $cond['project_investment_status'] = secure::secureInput(intval(arr::get($_GET, 'istatus', 0)));
            $cond['istatus'] = secure::secureInput(intval(arr::get($_GET, 'istatus', 0)));
            #未匹配到的标签
            $cond['unallow'] = secure::secureInput(arr::get($_GET, 'unallow', ''));

            $loginStatus = $this->isLogins();
            $user_id = $loginStatus ? $this->userInfo()->user_id : 0;

            /**
             * 搜索彩蛋程序
             * @author stone shi
             */
            $caiDan = $this->caidan($_GET['w']);

            $wordNew = $search->newWord($cond);
            if(is_int(strpos($_GET['w'], '招商会')) && !isset($_GET['istatus'])) {
                $cond['project_investment_status'] = 1;
                $cond['istatus'] = 1;
            }
            if(count(arr::get($seachAmount, 'eddAmountSector', array())) == 1 && !$wordNew) {
                $cond['atype'] = arr::get(arr::get($seachAmount, 'eddAmountSector', array()), 0, 0);
                $cond['project_amount_type'] = $cond['atype'];
            }
            if($wordNew) {
                $searchresult_key = 'getWordSearch'.$_GET['page'].$wordNew.json_encode($cond);
                $searchresult = $memcache->get($searchresult_key);
                if(!$searchresult) {
                    $searchresult=$search->getWordSearch($wordNew, $cond);
                    if($searchresult) {
                        $memcache->set($searchresult_key, $searchresult, 86400);
                    }
                }
            }else{
                $searchresult_key = 'getWordSearch'.$_GET['page'].$wordShow.json_encode($cond);
                $searchresult = $memcache->get($searchresult_key);
                if(!$searchresult) {
                    $searchresult=$search->getWordSearch($wordShow, $cond);
                    if($searchresult) {
                        $memcache->set($searchresult_key, $searchresult, 86400);
                    }
                }
            }
            #扩充搜索高亮显示
            $match = arr::get($searchresult, 'matches', array());
            //var_dump($match);
            $amountSector = isset($searchresult['eddAmountSector']) ? $searchresult['eddAmountSector'] : array();
            $keywords = (isset($searchresult['words']))  ? $searchresult['words'] : array();
            $keyGroup = $search->searchWordGroup($keywords);
            $seachCond = $search->searchCondGroup($keyGroup, $cond);
            $seachCond['cond']['w'] = $wordShow;
            //搜索纠错
            $arrWord = Arr::get($searchresult, 'correctWord' , array());
            $correctWord = array();
            $correctWord = $this->_getCorrectWord($arrWord,$_GET['w']);
            // 临时调试使用
            if(isset($_GET['debug']) && $_GET['debug']==1){
                Kohana::debug($searchresult);
            }

            $project_id_list=isset($searchresult['matches'])?$searchresult['matches']:array();
            $arr=NULL;
            foreach ($project_id_list as $val){
                $arr['result'][]=$val['id'];
            }
            $total = 0;
            if(isset($searchresult['total'])) {
                $total = $searchresult['total'];
            }
            #保留判断
            if(arr::get($caiDan, 'caidan_id') != 1){
                 $result_key = 'seacrchPro_'.json_encode($arr);
                 $result = $memcache->get($result_key);
                 if(!$result || !$arr) {
                    $result = $search->getProjectSqlSearch($arr, $total, $user_id, $cond['sort']);
                    $memcache->set($result_key, $result, 86400);
                 }
            }else{
                $result = $search->getSeachFeige(arr::get($caiDan, 'caidan_type'));
            }

            //历史搜索
            if(!empty($arr['result'])){
                $str = trim($_GET['w']);
                $str = preg_replace('/\s+/','',$str);
                $new_search[] = $str;
                $history_search = Cookie::get('history_search') ? unserialize(Cookie::get('history_search')) : array();
                $history_search = array_merge($new_search,$history_search);
                $history_search = array_unique($history_search);
                $history_search = array_slice($history_search, 0 , 4);
                Cookie::set("history_search",serialize($history_search),2592000);
                foreach($history_search as $val){
                    if($val == ""){
                        unset($val);
                    }
                }
            }else{
                $history_search = Cookie::get('history_search') ? unserialize(Cookie::get('history_search')) : array();
            }
            $content = View::factory('platform/home/searchProjectList');
            if($wordNew) {
                $content->selectvalue = $wordNew;
            }else{
                $content->selectvalue = $wordShow;
            }
            $this->content->maincontent = $content;
            $content->keywords = $keywords;
            $area = array('pro_id' => 0);
            $content->areas = common::arrArea($area);
            $content->Industry = common::getIndustryList();
            $content->keyGroup = $keyGroup;
            $content->project_list = $result;
            $content->match = $match;
            //暂时的tag数
            $content->project_tag_count_show = 4;
            $content->wordShow = $wordShow;
            $content->sort = $cond['sort'];
            $content->istatus = $cond['istatus'];
            $content->allTag = $seachCond['cond'];
            $content->keyList = $seachCond['keyList'];
            $content->user_id = $user_id;
            $content->loginStatus = $loginStatus;
            $search_word = $_GET['w'];
            $content->postlist = $search_word;
            $content->history_search = $history_search;
            $content->correctWord = $correctWord;
            $w = $_GET['w'];
            $w = preg_replace('/\s+/','',$w);
            $p = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $p_string = "";
            if($p == 1){
                $p_string = "";
            }else{
                $p_string = '第'.$p.'页';
            }
            if($w == ''){
                $this->template->title = $p_string.'搜项目_查找项目，上一句话 ';
                $this->template->keywords = '找搜项目，查找项目，一句话';
                $this->template->description = $p_string.'搜项目、查找项目、上一句话搜查项目';
            }elseif($p == "" || $p == 1){
                $this->template->title = $w.'_找项目，一句话的事 ';
                $this->template->keywords = $w.'，找项目，一句话';
                $this->template->description = $w.'，找项目，一句话的事';
            }else{
                $this->template->title = $p_string.$w.'_找项目，一句话的事 ';
                $this->template->keywords = $w.'，找项目，一句话';
                $this->template->description = $p_string.$w.'，找项目，一句话的事';
            }
            $content->now_search = $w;
            #最新入住的项目
            $content->newProject = $serviceGuide->getNewProjectList(4,3,3,2);
            #最火赚钱好项目
            $content->statisticsAll = $serviceGuide->getStatisticsAll(6,4,2);

    }else{
         $content = View::factory('platform/home/searchProjectIndex');
         //找项目首页
         $data = $search->getProjectInfoToIndex(2);
         #人群创业
         $arr_peopleList = array();
         #白领创业
         $WhiteWork = $memcache->get("WhiteWork");
         #如果为空
         if(empty($WhiteWork)){
         $WhiteWork = $serviceGuide->getProjectListByCrowdId(intval(5),5);
         $memcache->set("WhiteWork",arr::get($WhiteWork,"list"),$this->_cache_get_index_time);
         }
         $arr_peopleList['WhiteWork'] = arr::get($WhiteWork,"list") ? arr::get($WhiteWork,"list") : $WhiteWork;
         #大学生创业
         $Collegestudents = $memcache->get("Collegestudents");
         if(empty($Collegestudents)){
                $Collegestudents = $serviceGuide->getProjectListByCrowdId(intval(3),5);
                $memcache->set("Collegestudents",arr::get($Collegestudents,"list"),$this->_cache_get_index_time);
         }
                $arr_peopleList['Collegestudents'] = arr::get($Collegestudents,"list") ?arr::get($Collegestudents,"list") : $Collegestudents;
         #农民创业好项目
         $farmers = $memcache->get("farmers");
         if(empty($farmers)){
             $farmers = $serviceGuide->getProjectListByCrowdId(intval(6),5);
            $memcache->set("farmers",arr::get($farmers,"list"),$this->_cache_get_index_time);
         }
         $arr_peopleList['farmers'] = arr::get($farmers,"list") ? arr::get($farmers,"list") : $farmers;
         #女性创业好项目
         $women = $memcache->get("women");
         if(empty($women)){
             $women = $serviceGuide->getProjectListByCrowdId(intval(4),5);
             $memcache->set("women",arr::get($women,"list"),$this->_cache_get_index_time);
         }
            $arr_peopleList['women'] = arr::get($women,"list") ? arr::get($women,"list") : $women;
            $content->arr_peopleList = $arr_peopleList;
            #历史记录
            $history_search = Cookie::get('history_search') ? unserialize(Cookie::get('history_search')) : array();
            $content->history_search = $history_search;
            #在cookie中调出来的数据
            $content->arr_cookie_data = $data;
            $friend_link = $memcache->get('friend_cache_search');
            if(empty($friend_link)){
                $f_service = new Service_Platform_FriendLink();
                $friend_link = $f_service->getFriendLinkList('search');
                $memcache->set('friend_cache_search', $friend_link,604800);
            }
            #最新入住的项目
            $content->newProject = $serviceGuide->getNewProjectList(6,6,6,2);
            #最火赚钱好项目
            $content->statisticsAll = $serviceGuide->getStatisticsAll(11,7,2);
            $this->template->friend_link = $friend_link;
            $this->template->title = "一句话商机搜索找项目_一句话商机速配网";
            $this->template->description = "一句话商机搜索找项目、找好项目、找赚钱项目、找创业项目，上一句话找项目频道。这里有大量的好项目、新项目、赚钱项目等你找。涵盖白领、大学生、农民、女性等领域的投资赚钱找项目，轻松赚钱找项目只需一句话的事。";
            $this->template->keywords = "一句话找项目,一句话商机搜索,投赚钱项目，投资项目,一句话商机速配网";
        }

        #####################公用#########################
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

        $this->content->maincontent = $content;
        $this->template->xiangdaoshow = 1;
        #项目标签
        $content->Tags = $search->getTags();

        #项目的总数
        $content->projectAllNum = $search->_getProjectAllCount();
        #最新浏览的项目
        $content->ProjectAndPersonInfo = $search->getNewWatchProjectInfo();
        #项目的申请数量
        $content->project_Card_Num = $search->_getProjectComCarNum();

    }

    /**
     * 手机应用
     * @author 钟涛
     */
    public function action_mobilephone(){
        echo '手机应用页面[待添加静态页面]';exit;
        $content = View::factory('platform/home/index');
        $this->content->maincontent = $content;
    }

    /**
     * 官方微博
     * @author 钟涛
     */
    public function action_weibo(){
        echo '官方微博页面[待添加静态页面]';exit;
        $content = View::factory('platform/home/index');
        $this->content->maincontent = $content;
    }


    /**
     * 服务条款
     * @author 钟涛
     */
    public function action_fuwu(){
        echo '服务条款页面[待添加静态页面]';exit;
        $content = View::factory('platform/home/index');
        $this->content->maincontent = $content;
    }


    /**
     * 联系我们
     * @author 钟涛
     */
    public function action_contact(){
        echo '联系我们页面[待添加静态页面]';exit;
        $content = View::factory('platform/home/index');
        $this->content->maincontent = $content;
    }


    /**
     * @sso
     * 邮箱验证错误展示页面
     * @author 周进
     */
    public function action_showEmailFail(){
        $post = $this->request->query();
        $user = array();
        if (Arr::get($post, 'user_id')!=""){
            //$user = ORM::factory('user')->where("user_id", "=", Arr::get($post, 'user_id'))->find()->as_array();
            $client = Service_Sso_Client::instance();
            $user= (array) $client->getUserInfoById(Arr::get($post, 'user_id'));
        }
        $content = View::factory('platform/home/showemailfail');
        $this->content->maincontent = $content;
        $this->content->maincontent->data = $user;
    }

    /**
     * 整站404页面
     *
     * @author 龚湧
     */
    public function action_error404(){
        $view = View::factory("error404");
        $this->content->maincontent = $view;
        $this->template->title = "您的访问出错了_404错误提示_一句话";
        $this->template->keywords = "您的访问出错了,404";
        $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
    }
    /**
     * 查看更多的浏览的信息
     * @author 嵇烨
     */
    public function action_showHaveBrose(){
        $content = View::factory('platform/home/showhavebrose');
        #浏览项目的记录
        $service =new Service_Platform_Search();
        #获取用户的id
        $user_id = Cookie::get("user_id") ? Cookie::get("user_id") : 0;
        #获取用户的ip
        $ip = ip2long(Request::$client_ip);
        #获取浏览的信息
        $arr_data = $service->_getVistedLog($user_id,$ip,10);
        #当数据为零的时候跳回首页面
        if(count($arr_data) ==0 || empty($arr_data)){
             self::redirect("/platform/index/showHaveBroseIndex");
        }
        #处理数据项目的id
        $arr_project_id = array();
        foreach ($arr_data as $key=>$val){
            $arr_project_id [] = $val['operate_id'];
        }
        #获取项目的信息
        $arr_project_data = $service->_getProjectInfoByarr($arr_project_id);
        //echo "<pre>"; print_r($arr_data); print_r($arr_project_data);exit;
        #把浏览的id放进数组中
            foreach ($arr_project_data as $key=>$val){
                foreach ($arr_data as $k=>$v){
                    if($val['project_id'] == $v['operate_id']){
                        $arr_project_data [$key]['brose_id'] = $v['id'];
                        break;
                    }
                }
            }
            $service =  new Service_Platform_Search();
            $Service_ProjectGuide = new Service_Platform_ProjectGuide();
            #大家可能喜欢的新项目
            $arr_people_like_data = $service->getProinfoByIndustryAmount(intval(3));
            #根据您最近浏览的项目为您推荐
            $arr_first_project_data = $service->_getOtherProject($arr_data[0],$ip,intval(5));
            #如果小于六时
            if(count($arr_first_project_data) < 5){
                $arr_data = $Service_ProjectGuide->getNewProjectListNByRandom(intval(5) - count($arr_first_project_data));
                foreach ($arr_data as $key=>$val){
                    $arr_first_project_data[] = $val['project_id'];
                }
            }
            #推荐的项目信息
               $arr_return_project_data = $service->_getProjectInfoByarr($arr_first_project_data);

            if(count($arr_project_data) == 1){
                $arr_project_datas[] = $arr_project_data;
            }else{
                $arr_project_datas =  @array_chunk($arr_project_data,@floor(count($arr_project_data)/2));
            }
        $content->arr_data = $arr_project_datas;
        $content->arr_recommended_project_data = $arr_return_project_data;
        $content->arr_people_like_data = $arr_people_like_data ;
        $this->content->maincontent = $content;
        $this->template->title = "您最近浏览过的项目_一句话";
        $this->template->keywords = "一句话，浏览过的项目";
        $this->template->description = "您最近浏览过的项目，请管理，可清空。";
    }
    /**
     * 删除浏览过的项目
     * @author 嵇烨
     */
    public function action_deleteHavaBrose(){
     #获取要删除的id
     $get = Arr::map("HTML::chars", $this->request->query());
     $id = intval(arr::get($get, "id"));
     $service =new Service_Platform_Search();
     #删除项目
     $bool = $service->_deleteHavaBrowseProject($id);
         #返回值
         if($bool == true){
             self::redirect("/platform/index/showHaveBrose");
         }
    }
    /**
     * 项目删除是主页面
     * @author 嵇烨
     */

    public function action_showHaveBroseIndex(){
        #获取一句话人气项目 7天
        $Service_ProjectGuide = new Service_Platform_ProjectGuide();
        $project_list_sevent_day = $Service_ProjectGuide->getMaxProjectListPv(intval(6));
        $content = View::factory('platform/home/showhavebroseindex');
        $content->project_list_sevent_day = $project_list_sevent_day;
        $this->content->maincontent = $content;
    }

    /**
     * 搜索纠错（自用）
     * @author 郁政
     */
    private function _getCorrectWord($arrWord,$word){
        $correctWord = array();
        if($arrWord){
            foreach($arrWord as $k => $v){
                $ar = explode('	', $v);
                if(count($ar) <= 2) {
                    $tempArr = explode('    ', arr::get($ar, 0, ''));
                    $correctWord[$k]['cword'] = arr::get($tempArr, 1, '') ? arr::get($tempArr, 1, '') : arr::get($ar, 0, '');
                    $correctWord[$k]['num'] = arr::get($ar, 1, 0);
                }elseif(count($ar) == 3) {
                    $correctWord[$k]['cword'] = arr::get($ar, 1, '') ? arr::get($ar, 1, '') : '';
                    $correctWord[$k]['num'] = arr::get($ar, 2, 0);
                }
            }
            foreach($correctWord as $key => $val){
                if(isset($val['cword']) && $val['cword'] == $word){
                    unset($correctWord[$key]);
                }
            }
            $correctWord = array_merge($correctWord,array());
            if(!$correctWord) return array();
            foreach($correctWord as $key => $valt){
                $num[$key] = $valt['num'];
            }
            array_multisort($num,SORT_DESC,$correctWord);
            $correctWord = array_slice($correctWord, 0,3);
        }
        return $correctWord;
    }

    /**
     * 彩蛋程序
     * @author stone shi
     */
    public function caidan($word = '') {
        $return = array('caidan_id' => 0, 'caidan_type' => 0);
        if(!$word) return $return;
        $caidanOne = array('1' => 'site:1', '2' => 'site:23', '4' => 'site:45', '6' => 'site:6');
        foreach($caidanOne as $key => $val) {
            if(is_int(strpos($word, $val))) {
                $return['caidan_id'] = 1;
                $return['caidan_type'] = $key;
                break;
            }
        }
        return $return;
    }
}
