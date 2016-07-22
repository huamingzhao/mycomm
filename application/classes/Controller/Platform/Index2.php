<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 新平台前端页面首页
 * @author 钟涛
 *
 */
class Controller_Platform_Index extends Controller_Platform_Template{
    /**
     * 前端页面首页
     * @author 钟涛
     */
    public function action_index(){
        $content = View::factory('platform/home/index');
        $this->content->maincontent = $content;
    }

    /**
     * 企业首页
     * @author 钟涛
     */
    public function action_comCenter(){
        $content = View::factory('platform/home/comcenter');
        $this->content->maincontent = $content;
        $content->verification_code = common::verification_code();
        if($this->request->method()== HTTP_Request::POST){
            $this->content->maincontent = $content;
            $post = Arr::map("HTML::chars", $this->request->post());
            //登录验证
            $service = new Service_User();
            $result = $service->loginCaptcha($post);
            if($result != 1){
                $content->error = $result;
                $content->emails = $this->request->post('email');
            }else{
                $last_login_user_status = ORM::factory("User",$this->loginUserId());
                $user['user_id'] = $this->loginUserId();
                $user['last_logintime'] = time();
                $user['last_login_ip'] = ip2long(Request::$client_ip);
                //一句话信息导入
                $service_guide = new Service_Platform_Search();
                $local_config = $service_guide->getNotLoggedSearchConfig();
                if(!empty($local_config)){
                    //清除老配置
                    $service_guide->clearLoginConfig($this->loginUserId());
                    $service_guide->clearNotLoginConfig();
                    //导入最新的的配置
                    foreach ($local_config as $qid=>$aid){
                        $service_guide->setLoggedSearchConfig($this->loginUserId(), $qid, $aid);
                    }
                }
                /*
                //add by 龚湧 start 用户登录更新用户消息
                $last_logintime = $last_login_user_status->last_logintime;
                $user_type = $last_login_user_status->user_type;
                $ucmsg = Service::factory("User_Ucmsg");
                $ucmsg->generateMsg($this->loginUserId(),$last_logintime,$user_type);//更新和创建消息
                // end
                 */

                //用户信息更新
                $usertype =$service->updateUser($user);
                $this->addUserLoginLog($user['user_id'], $usertype);

                //上次跳转过来的地址
                $to_url = $this->request->query("to_url");
                //登录成功跳转
                $this->userType($usertype,$to_url);
                //self::redirect("platform/index/comCenter");
            }
        }else{
            if($this->loginUser()){//已经登录的用户
                $content->is_logoin = true;
                $username=$this->userInfo();
                if($username->user_type == 1){//企业用户
                    $seruser=new Service_User_Company_User();
                    $comresult=$seruser->getCompanyInfo($this->userInfo()->user_id);
                    $user_name=$comresult->com_name;
                    if(!$user_name){
                        $user_name=$username->email;
                    }
                }elseif($username->user_type == 2){//个人用户
                    $perservice=new Service_User_Person_User();
                    $perinfo=$perservice->getPerson($username->user_id);
                    //个人真实姓名
                    if($perinfo->per_realname){
                        $user_name=$perinfo->per_realname;
                    }else{
                        $user_name=$username->email;
                    }
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
        if(!isset($_GET['w'])){
            Kohana::location(URL::website("platform/index"));
        }
        $search=new Service_Platform_Search();
        $_GET['w'] = $wordShow = urldecode(secure::secureInput($_GET['w']));
        $searchresult=$search->getWordSearch($wordShow);
        $amountSector = isset($searchresult['eddAmountSector']) ? $searchresult['eddAmountSector'] : array();
        $keywords = isset($searchresult['words']) ? $searchresult['words'] : array();
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
        $result = $search->getProjectSqlSearch($arr, $total);
        $content = View::factory('platform/home/tag_projectlist');
        $this->content->maincontent = $content;
        $content->keywords = $keywords;
        //名片信息
        foreach ($result['list'] as $k=>$v){
            $result['list'][$k]['com_user_id'] = $search->getComUserid($v['com_id']);
            $result['list'][$k]['showTag'] = $search->checkProjectTag($v, $keywords, $amountSector);
            //判断是否登录
            if($this->isLogins()){
                $card = $search->getCardInfo($this->loginUserId(),$result['list'][$k]['com_user_id']);
                //判断是否递出名片
                if($card ==true){
                    $result['list'][$k]['card'] = "ok";
                }else{
                    $result['list'][$k]['card'] = "no";
                }
            }else{
                $result['list'][$k]['card'] = "no";
            }
        }
        $content->project_list = $result;
        //暂时的tag数
        $content->project_tag_count_show = 4;
        $content->wordShow = $wordShow;
        $this->template->title = mb_convert_encoding($_GET['w'],"utf-8")."项目";
        $this->template->description = '去投资，为投资者筛选符合'.mb_convert_encoding($_GET['w'],"utf-8").'条件的项目。投资赚钱好项目，一句话的事。';
        $this->template->keywords = mb_convert_encoding($_GET['w'],"utf-8").'，热门行业投资，投资赚钱好项目';
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
     * 邮箱验证错误展示页面
     * @author 周进
     */
    public function action_showEmailFail(){
        $post = $this->request->query();
        $user = array();
        if (Arr::get($post, 'user_id')!="")
            $user = ORM::factory('user')->where("user_id", "=", Arr::get($post, 'user_id'))->find()->as_array();
        $content = View::factory('platform/home/showemailfail');
        $this->content->maincontent = $content;
        $this->content->maincontent->data = $user;
    }
}