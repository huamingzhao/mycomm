<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 搜索controller
 * @author 龚湧
 *
 */
class Controller_Platform_Guide extends Controller_Platform_Template{
private $_get_search_count = 8; //设置精准匹配返回的数据数
    /**
    * 精准搜索页
    * @author 龚湧
    */
    public function action_index(){
        $qconfig = array();
        $service = Service::factory("Platform_Search");
        $qst = $service->getAllQuestion();

        if($this->isLogins()){
            $user_id = $this->userId();
            $qconfig = $service->getLoggedSearchConfigs($user_id);
            //如果登录用户未做过任何题目，本地存在配置，则导入本地配置
            if(empty($qconfig)){
                $qconfig = $service->getNotLoggedSearchConfig();
                foreach($qconfig as $qid=>$aid){
                    $service->setLoggedSearchConfig($user_id,$qid,$aid);
                }
            }
        }
        //未登录用户从cookie中获取配置，同时ajax更新cookie保存配置
        else{
            $qconfig = $service->getNotLoggedSearchConfigs();
        }

        $content = View::factory("platform/search/accurate");
        $this->content->maincontent = $content ;

        //地区做特殊处理
        if(Arr::get($qconfig, "2")){
            $area = Service::factory("Public");
            $area_name =  $area->getAreaName($qconfig[2]);
            //地区名称
            $content->area_name = $area_name;
        }
        //行业做特殊处理
        if(Arr::get($qconfig, "6")){
            $idy = Service::factory("Public");
            $idy_name =  $idy->getIndustryNameById($qconfig[6]);
            //地区名称
            $content->idy_name = $idy_name;

            $sub_industry = $idy->getIndustryBrother($qconfig[6]);
            //二级地区初始化
            $content->sub_industry = $sub_industry;
        }

        //问题列表
        $content->qst = $qst;
        //回答问题配置
        $content->qconfig = $qconfig;
        //一句话初始化
        $content->sentence = $service->getSentence($qconfig);
        $this->template->title = '独家项目匹配系统_一句话投资赚钱项目';
        $this->template->description = '一句话独家项目匹配系统为您精准匹配创业投资项目。根据您的创业意向及创业问题回答为您匹配最合适您的好项目。';
        $this->template->keywords = '独家项目匹配系统，精准匹配，一句话';
    }
    /**
     *  精准匹配结果页
     * @author  施磊
     */
    public function  action_getProjectList() {
        $content = View::factory("platform/search/getprojectlist");
        //取得用户所选择的信息
        $service=new Service_Platform_Search();
        if($this->isLogins()){//用户登录时
            //取得用户id
            $user_id = $this->userId();
            $data = $service->getLoggedSearchConfigs($user_id);
        }else{//未登录时
            $data = $service->getNotLoggedSearchConfigs();
        }
        $oneWord = $service->getSentence($data);
        $allCond = $service->getConditionName($data);
        $get = Arr::map("HTML::chars", $this->request->query());
        $page = Arr::get($get, 'page') ? $get['page'] : 1;
        $limit = Arr::get($get, 'get', 0) ? Arr::get($get, 'get') : $this->_get_search_count;
        //查询条件

        $res = $service->getQueryConditionList($data, $page, $limit);
        $content->projectList = $res;
        $content->page = $page;
        $content->oneWord = $oneWord;
        $content->allCond = $allCond;
        $content->allCondCss = array('a', 'b', 'c', 'd', 'e', 'f');
        $this->content->maincontent = $content ;
    }
    /**
     * 精准搜索项目列表页面
     * @author 曹怀栋
     */
    /*废弃不用了
    public function action_projectList(){
        $qconfig = array();
        $service =new Service_Platform_Search();
        $per_service = new Service_User_Person_Project();
        $com_service = new Service_User_Company_Project();
        $get = Arr::map("HTML::chars", $this->request->query());
        $arr=array();
        $is_usercenter_link=arr::get($get,'type','');
        if($is_usercenter_link!=''){//从用户中心搜索历史记录链接过来的[钟涛修改]
           $data_result= $per_service->getOneConditionsByid(arr::get($get,'id',0));
           if($data_result['id']){
               for($a=1;$a<=10;$a++) {//循环对10个问题组合
                     if($data_result['question'.$a.'_id']){
                         $arr['result'][$a]=$data_result['question'.$a.'_id'];
                      }
               }
               $arr['arr']=$service->getArrName($arr['result']);
           }
        }else{
            if($this->isLogins()){//登录时
                $arr = $service->getMatchingProjectList($this->userId());
            }else{//未登录时
                $arr = $service->getMatchingProjectList();
            }
        }
        $arrlist = $service->getQueryCondition($arr);
        $array_list = $service->getProjectSqlSearch($arrlist);
        $array_list['inaword'] = ($array_list['inaword'] == 'noCond') ? '' : $array_list['inaword'];
        //个人用户登录时添加筛选项目历史记录[钟涛修改]
        if($this->isLogins() && $this->userinfo()->user_type==2){
            if(isset($arr['result']) && count($arr['result'])){
                $seracharr=array();
                foreach($arr['result'] as $k=>$value){
                    $search['question'.$k.'_id']=$value;
                }
                $per_service->updateSearchConditions($array_list['total_count'],$search,$this->userId());
            }
        }
        $content = View::factory("platform/search/projectlist");
        //通过企业id取得企业的user_id
        foreach ($array_list['list'] as $k=>&$v){
            $v['com_user_id'] = $service->getComUserid($v['com_id']);
            //判断是否登录
            if($this->isLogins()){
                $card = $service->getCardInfo($this->loginUserId(),$v['com_user_id']);
                //判断是否递出名片
                if($card ==true){
                    $array_list['list'][$k]['card'] = "ok";
                }else{
                    $array_list['list'][$k]['card'] = "no";
                }
            }else{
                $array_list['list'][$k]['card'] = "no";
            }
            $array_list['list'][$k]['iconStatus'] = $com_service->checkProAllInfo($v['project_id']);
            //项目来源(1本站，2表示875，3生意街，4外采)
            if($v['project_source']==4){//判断是否存在外采海报
                $posterModel = new Service_Platform_Poster();
                $ispage = $posterModel->isCollectPoster($v['outside_id']);
                $array_list['list'][$k]['iconStatus']['ispage']=$ispage;
            }else{
                $array_list['list'][$k]['iconStatus']['ispage']=false;
            }
        }
        if(count($array_list['list_make_up']) > 0){
        foreach ($array_list['list_make_up'] as $k=>&$v){
            $v['com_user_id'] = $service->getComUserid($v['com_id']);
            //判断是否登录
            if($this->isLogins()){
                $card = $service->getCardInfo($this->loginUserId(),$v['com_user_id']);
                //判断是否递出名片
                if($card ==true){
                    $v['card'] = "ok";
                }else{
                    $v['card'] = "no";
                }
            }else{
                $v['card'] = "no";
            }
            $v['iconStatus'] = $com_service->checkProAllInfo($v['project_id']);
            //项目来源(1本站，2表示875，3生意街，4外采)
            if($v['project_source']==4){//判断是否存在外采海报
                $posterModel = new Service_Platform_Poster();
                $ispage = $posterModel->isCollectPoster($v['outside_id']);
                $v['iconStatus']['ispage']=$ispage;
            }else{
                $v['iconStatus']['ispage']=false;
            }
        }
        }else{
            $q_id = array();
            $a_id = array();
            //如果没有匹配到相应的结果，做一次记录
            $temp = $array_list['list'];
            if(isset($arrlist['arr']) && $arrlist['arr'] != 'noCond') {
                $temp2 = $arrlist['arr'];
                foreach ($temp2 as $kT => $vT){
                  $q_id[]=$kT;
                  $a_id[]=$vT['id'];
              }
            }
            $array_list['list'] = $temp;

            if($this->isLogins()){//登录时
                $arr['user_id'] = $this->userId();
            }
            $arr['q_id'] = serialize($q_id);
            $arr['a_id'] = serialize($a_id);
            $arr['ip'] = $_SERVER['REMOTE_ADDR'];
            $service->searchNoList($arr);
        }
        $this->content->maincontent = $content ;
        //项目列表
        $content->project_list = $array_list;
    }
    */
}