<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户 项目信息
 * @author 钟涛
 */
class Controller_User_Person_Project extends Controller_User_Person_Template{
    /**
     * 搜索项目
     * @author 钟涛
     */
    public function action_searchProject(){
        $service=new Service_User_Person_User();
        $serviceProject = new Service_Platform_Project();
        //获取登录user_id
        $userid = $this->userid();
        //获得个人基本信息
        $personinfo = $service->getPersonInfo($userid);

            $invest = new Service_User_Person_Invest();
            $per_service = new Service_User_Person_Project();
            $plat_service =new Service_Platform_Search();
            $public_serice=new Service_Public();
            //view页面加载
            $content = View::factory("user/person/searchproject");
            $this->content->rightcontent = $content;
            //获取表单值
            $search = $this->request->query();
            unset($search['x']);
            unset($search['y']);
            $history_serarch_count=$per_service->getSearchConditionsAllCount($userid);
            //第一次加载数据
            if(count($search)==0){
                if($history_serarch_count>0){
                    $histroy_data=$plat_service->getLoggedSearchConfigByID($userid);
                    foreach($histroy_data as $v){
                        if($v->question_id==2){//地区
                            if($v->question_answer_id>100){
                                $search['area_id']=$v->question_answer_id;//城市
                                $search['pro_id']=$public_serice->getProidByCityid($v->question_answer_id);//省份
                            }else{
                                $search['pro_id']=$v->question_answer_id;//省份
                            }
                        }else{
                            $search['question'.$v->question_id.'_id']=$v->question_answer_id;
                        }
                    }
                }
            }
            //1级取得行业
            $content->list_industry= common::primaryIndustry(0);
            //读取省级地区列表
            $pro=$invest->getArea();
            $all=array('cit_id' => 88,'cit_name' => '全国');
            array_unshift($pro, $all);
            $content->area = $pro;
            //获取城市地区
            $pro_id=arr::get($search, 'pro_id','');
            if($pro_id !='' && $pro_id !='88'){
                $area = array('pro_id'=>$pro_id);
                $content->cityarea=common::arrArea($area);
            }else{
                $content->cityarea=array();
            }
            //金额
            $content->money = guide::attr7();
            //项目风险
            $content->question10 = guide::attr10();
            //项目投资回报率
            $content->question8 = guide::attr8();
            //项目适合人脉关系
            $content->question5 = guide::attr5();
            //项目投资形式
            $content->question1 = guide::attr1();
            //你曾经做过什么
            $content->question3 = guide::attr3();
            //想和谁一起做
            $content->question4 = guide::attr4();
            //您想什么时候做生意
            $content->question9 = guide::attr9();
            if(arr::get($search, 'question8_id','')!="" || arr::get($search, 'question5_id','')!="" ||arr::get($search, 'question1_id','')!="" ||arr::get($search, 'question3_id','')!="" ||arr::get($search, 'question4_id','')!="" ||arr::get($search, 'question9_id','')!="" ){
                $content->hidden = false;
            }else{
                $content->hidden = true;
            }
            $content->search = $search;

            $arr['result'] = Array();
            for($a=1;$a<=10;$a++) {//循环对10个问题组合
                if(arr::get($search, 'question'.$a.'_id','')!=''){
                    $arr['result'][$a]=arr::get($search, 'question'.$a.'_id','');
                }
            }
            if(arr::get($search, 'area_id','')!=''){
                $arr['result']['2']=arr::get($search, 'area_id');
                $search['question2_id']=arr::get($search, 'area_id');
            }elseif(arr::get($search, 'pro_id','')!=''){
                $arr['result']['2']=arr::get($search, 'pro_id');
                $search['question2_id']=arr::get($search, 'pro_id');
            }else{    }
            if(count($arr['result'])){
                $comments = $plat_service->getLoggedSearchConfigByID($userid);
                foreach ($comments as $comment){
                    $comment->delete();
                }
                foreach ($arr['result'] as $k=>$v){
                    $plat_service->setLoggedSearchConfig($userid,$k,$v);
                }
                //组合一句话内容
                $qconfig=$plat_service->getLoggedSearchConfig($userid);
                $sentence = $plat_service->getSentence($qconfig);
                $sentences = '';
                foreach($sentence as $str){
                    $sentences .= $str;
                }
                $content->content_text = $sentences;
                $arrlist = $plat_service->getQueryCondition($arr);
                if($arrlist['arr'] != 'noCond') {
                    $per_service->updateSearchConditions(count($arrlist['result']),$search,$this->userId());
                    $content->totalcount = count($arrlist['result']);
                }else {
                    $where = '';
                    if($arrlist['arr'] == 'noCond') {
                        $where = " where project_status=2";
                    }
                   $content->totalcount = $serviceProject->getProjectCount();
                }
            }else{

                $content->content_text = '';
                $content->totalcount = 0;
            }
            $new_count=$per_service->getSearchConditionsAllCount($userid);
            $content->search_count =$new_count;

    }

    /**
     * 我收藏的项目
     * @author 钟涛
     */
    public function action_watchProject(){
        $service=new Service_User_Person_User();
        //获取登录user_id
        $userid = $this->userid();
        //获得个人基本信息
        $personinfo = $service->getPersonInfo($userid);
        $wathch_project = new Service_User_Person_Project();
        $total_count=$wathch_project->getProjectWatchAllCount($userid);
        if($total_count<1){//当前用户没有收藏任何项目
        	//view页面加载
            $content = View::factory("user/person/watchproject_nodata");
            $this->content->rightcontent = $content;
        }else{
                //view页面加载
			$content = View::factory("user/person/watchproject");
			$this->content->rightcontent = $content;
			$invest = new Service_User_Person_Invest();
			//获取表单值
			$search = $this->request->query();
			unset($search['x']);
			unset($search['y']);
            //获取项目相关信息
            $result = $wathch_project->searchWatchProjecList($search,$userid);
            $content->listIndustry = common::primaryIndustry(0);
            //读取省级地区列表
            $content->area = $invest->getArea();
            //获取城市地区
            $pro_id=arr::get($search, 'pro_id','');
            if($pro_id !=''){
                 $area = array('pro_id'=>$pro_id);
                 $content->cityarea=common::arrArea($area);
            }else{
                 $content->cityarea=array();
            }
            //金额
            $content->money = common::moneyArr();
            //1级取得行业
            $content->list_industry= common::primaryIndustry(0);
            //2级取得行业
            $list_industry2=array();
            if(isset($search['parent_id']) && is_numeric($search['parent_id'])){
                 $list_industry2s =common::primaryIndustry($search['parent_id']);
                 foreach ($list_industry2s as $k=>$v){
                    $list_industry2[$k]['industry_id']=$v->industry_id;
                    $list_industry2[$k]['industry_name']=$v->industry_name;
                }
            }
            $content->list_industry2=$list_industry2;
            $content->search = $search;
            $content->list = $result['list'];
            $content->page = $result['page'];
        }
    }

    /**
     * 取消收藏项目
     * @author 钟涛
     */
    public function action_updateWatchProjectStatus(){
        $postdata=Arr::map("HTML::chars", $this->request->query());
        $service = new Service_Platform_Project();
        $cardidarr=array();
        //项目id数组
        $project_arr= explode(",", $postdata['project_arr']);
        //获取登录user_id
        $userid = $this->userid();
        //取消收藏
        $service->updateProjectWatchInfo($userid,$project_arr);
        self::redirect("/person/member/project/watchProject");
    }

    /**
     * 搜索项目历史记录
     * @author 钟涛
     */
    public function action_searchWatchProjectList(){
         //view页面加载
         $content = View::factory("user/person/searchwatchproject_list");
         $this->content->rightcontent = $content;
         $seachlist_project = new Service_User_Person_Project();
         $result=$seachlist_project->getSearchProjecList($this->userid());
         $content->list = $result;
    }

    /**
     * 删除搜索项目历史记录
     * @author 钟涛
     */
    public function action_deleteConditionsByID(){
        $getdata = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Person_Project();
        $id=$getdata['id'];
        if(intval($id)) {
            $service->deleteConditionsByid($id);
        }
        self::redirect("/person/member/project/searchWatchProjectList");
    }
}