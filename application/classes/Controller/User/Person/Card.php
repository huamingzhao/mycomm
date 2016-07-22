<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户名片信息
 * author 钟涛
 */
class Controller_User_Person_Card extends Controller_User_Person_Template{
    /**
     * 个人我的名片
     * @author 钟涛
     */
    public function action_myCard(){
        $service = new Service_User_Person_User();
        //获取登录user_id
        $userid = $this->userid();
        //获得个人基本信息
        $personinfo = $service->getPersonInfo($this->userId());
         if(!$this->is_complete_basic3($this->userId())){//只判断是否已经完善基本信息
             $content = View::factory("user/person/nocard");
             $this->content->rightcontent = $content;
         }
         else{
            //view页面加载
            $area = $this->getPersonalArea($this->userId());//获得个人地域信息
            $content = View::factory("user/person/personinfo_modify");
            $this->content->rightcontent = $content;
            $result = $service->getPersonInfo($userid);
            $content->industry=$service->getPersonalIndustryString($userid);
            $content->this_area = $service->getPerasonalAreaString($userid);
            $content->per_phone = $result['mobile'];
            $content->email = $result['email'];
            $content->personinfo = $result['person'];
            $content->area = $area;
            $content->cardstyleid = $this->userInfo(true)->basic->card_style;
            $content->per_age = $result['per_age'];
            $content->per_education = $result['per_education'];
            $content->per_connections = $result['per_connections'];
            $content->per_investment_style = $result['per_investment_style'];
            $content->per_amount = $result['per_amount'];
            $content->ishasexperience = $result['ishasexperience'];
            $content->userid = $userid;
            //从事行业一级大分类
            $allindustry = common::primaryIndustry(0);
            foreach ($allindustry as $key=>$lv){
                $industry_first[$lv->industry_id] = $lv->industry_name;
            }
            array_unshift($industry_first,'请选择');
            $content->industry_first = $industry_first;
            //从业经验赋值
            $experiences = $service->listExperience($userid);
            $service_user= new Service_User();
            if(!empty($experiences)){
                foreach( $experiences as $k=>$v ){
                    //行业类别
                    $rs_profession= $service_user->getProfessionRow( $v['exp_industry_sort'] );
                    $experiences[$k]['exp_industry_sort_name']= $rs_profession['profession_name'];
                    //职业类别
                    $rs_pos= $service_user->getPositionRow( $v['exp_occupation_type'] );
                    $experiences[$k]['pos_name']= $rs_pos['position_name'];
                    //职业名称
                    if( $v['exp_occupation_name']!='0' ){
                        $rs_pos= $service_user->getPositionRow( $v['exp_occupation_name'] );
                        $experiences[$k]['occ_name']= $rs_pos['position_name'];
                    }else{
                        $experiences[$k]['occ_name']= $v['exp_user_occupation_name'];
                    }
                }
            }
            $content->experiences = $experiences;


        }
    }

    /**
     * 选择个人名片模板信息
     * @author 周进
     */
    public function action_cardStyle(){
        //view页面加载
        $content = View::factory("user/person/card_template");
        $this->content->rightcontent = $content;
        //获取登录user_id
        $userid =$this->userInfo()->user_id;
        $service=new Service_User_Person_User();
        //获取当前页数
        $urlpage = $this->request->query('page')?$this->request->query('page'):1;
        //获取模板图片列表
        $return_arr=$service->getCardStyleInfo($urlpage,'person');
        $content->imglist = $return_arr['list'];
        $content->page= $return_arr['page'];
        //预览名片模板时获取名片显示内容列表信息
        $result = $service->getPersonInfo($userid);
        $content->per_phone = $result['mobile'];
        $content->email = $result['email'];
        $content->personinfo = $result['person'];
    }

    /**
     * 保存名片模板信息
     * @author 曹怀栋
     */
    public function action_saveCardStyle(){
        //获取当前选择的名片模板ID
        $cardstyle = arr::get($this->request->query(),'cardkey',0);
        $service=new Service_User_Person_User();
        //更新模板id
        if($service->updateCardStyleInfo($this->userId(), $cardstyle)){
            self::redirect("/person/member/card/mycard");
        }
    }

    /**
     * 个人名片公开度（类型分为1，2，3）
     * @author 曹怀栋
     */
    public function action_cardOpenDegree(){
        if($this->request->method()== HTTP_Request::POST){
            $post = Arr::map("HTML::chars", $this->request->post());
            $service = new Service_User_Person_User();
            $result = $service->cardOpenStutas2($this->userId(),arr::get($post,"publicity"));
            self::redirect("/person/member/card/mycard");

        }
    }

    /**
     * 个人收到的名片
     * @author钟涛
     */
    public function action_receiveCard(){
        $service=new Service_User_Person_User();
        $cardservice=new Service_User_Company_Card();
        $card_service=new Service_User_Person_Card();
        $pro_service = new Service_User_Company_Project();
        if($cardservice->getReceiveCardCount($this->userId())){
            //view页面加载 已收到名片
            $content = View::factory("user/person/receivecard");
            //获取页面post表单值
            $search = $this->request->query();
            //获取我收到投资者的名片信息列表
            $return_arr=$card_service->searchReceiveCard($search,$this->userId());
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
            $content->list=$card_service->getAllSerializeArrayList($return_arr['list']);
            $content->page= $return_arr['page'];
            $content->postlist=$search;
        }
        else{
            //view页面加载 未收到名片
            $content = View::factory("user/person/noreceivecard");
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 个人用户递出名片
     * @author 钟涛
     */
    public function action_OutCard(){
        $service=new Service_User_Person_User();
        $card_service=new Service_User_Person_Card();
        //获取登录user_id
        $userid = $this->userId();
        //判断是否邮箱验证
        if (!$service->getEmailValidCount($userid)){
            self::redirect("/person/basic/vemail");
        }
        if($card_service->getOutCardCount($userid)){
            //view页面加载 已递出名片信息
            $content = View::factory("user/person/outcard");
            //获取页面post表单值
            $search = $this->request->query();
            //获取我递出的投资者名片信息列表
            $return_arr=$card_service->searchSendCard($search,$userid);
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
            $content->list=$card_service->getAllSerializeArrayList($return_arr['list']);
            $content->page= $return_arr['page'];
            $content->postlist=$search;
        }
        else{
            //view页面加载 未递出名片
            $content = View::factory("user/person/nooutcard");
        }
        $this->content->rightcontent = $content;

    }

    /**
     * 个人更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateReceiveDelStatus(){
        $get = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $result = $card_service->updateReceiveDelStatus(arr::get($get,'id'));
        if($result){
            self::redirect("/person/member/card/receivecard");
        }
    }

    /**
     * 个人批量更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateBatchReceiveDelStatus(){
        $postdata=Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $cardidarr=array();
        //名片id数组
        $cardidarr= explode(",", $postdata['cardidarr']);
        //更改我收到的名片未已删除状态
        $card_service->updateBatchReceiveDelStatus($cardidarr);
        self::redirect("/person/member/card/receiveCard");
    }

    /**
     * 个人更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateOutDelStatus(){
        $get = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $result = $card_service->updateOutDelStatus(arr::get($get,'id'));
        if($result){
            self::redirect("/person/member/card/outCard");
        }
    }

    /**
     * 个人批量更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateBatchOutDelStatus(){
        $getdata = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $cardidarr=array();
        //名片id数组
        $cardidarr= explode(",", $getdata['cardidarr']);
        //更改我递出的名片为已删除状态
        $card_service->updateBatchOutDelStatus($cardidarr);
        self::redirect("/person/member/card/outCard");
    }
        /**
     * 收藏名片列表
     * @author 周进
     */
    public function action_favorite(){
        $search = $this->request->query();
        $card_service=new Service_User_Person_Card();
        $content = View::factory("user/person/favorite");
        if($card_service->getFavoriteNums($this->userId())>0){
            $favoriteids = $this->request->post('exchangecard_favorite_id');
            if (is_array($favoriteids))
                $card_service->updateFavorite($this->userId(),2,$favoriteids);
            //获取搜索到的收藏的名片信息列表
            $search['exchange_status'] = isset($search['exchange_status'])?$search['exchange_status']:0;
            $search['from_read_status'] = isset($search['from_read_status'])?$search['from_read_status']:'-1';
            $result = $card_service->searchFavorite($search,$this->userId());
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
            $content->list = $card_service->getAllSerializeArrayList($result['list']);
            $content->page = $result['page'];
            $content->totalcount = $result['total_count'];
            $content->postlist = $search;
        }
        else
            $content = View::factory("user/person/nofavorite");
        $this->content->rightcontent = $content;
    }

    /**
     * 个人用户取消收藏的名片
     * @author 周进
     */
    public function action_updatefavorite(){
        $card_service=new Service_User_Person_Card();
        $result = $card_service->updateFavorite($this->userId(),1, $this->request->query('favorite_id'));
        $search['exchange_status'] = isset($search['exchange_status'])?$search['exchange_status']:0;
        $search['from_read_status'] = isset($search['from_read_status'])?$search['from_read_status']:'-1';
        $content = View::factory("user/person/favorite");
        $result = $card_service->searchFavorite($search,$this->userId());
        $content->list = $card_service->getSerializeArrayList($result['list']);
        $content->page = $result['page'];
        $content->totalcount = $result['total_count'];
        $content->postlist = $search;
        $this->content->rightcontent = $content;
    }

    /**
     * 个人中心 -- 咨询历史
     * @author 赵路生
     */
    public function action_historyConsult(){
        $content = View::factory("user/person/history_consult");
        $this->content->rightcontent = $content;
        //获取登录的user_id
        $userid = $this->userId();
        if(!$userid){
            exit;
        }
        $project_service = new Service_Platform_Project();
        $project_com_service = new Service_User_Company_Project();
        //获取金额的
        $monarr = common::moneyArr();
        $service = new Service_User_Person_Card();
        $consult_list = array();
        $return_arr = $service->getHistoryConsult($userid);
        if(count($return_arr['list'])>0){
            foreach($return_arr['list'] as $value){
                //获取地区
                $pro_area = $project_com_service->getProjectArea($value->to_project_id);
                $area = '';
                if($pro_area == '全国'){
                    $area = $pro_area;
                }else{
                    foreach($pro_area as $v){
                        $area .= $v.' ';
                    }
                }
                //获取项目的信息
                $projectinfo = $project_service->getProjectInfoByIDAllNew($value->to_project_id);
                $url = URL::webstatic('images/common/company_default.jpg');
                if($projectinfo){
                    //项目logo图片处理
                    if($projectinfo->project_source != 1) {
                        $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
                        if(!project::checkProLogo($tpurl)){
                            $tpurl = $url;
                        }
                    }else{
                        $tpurl=URL::imgurl($projectinfo->project_logo);
                        if(!project::checkProLogo($tpurl)){
                            $tpurl = $url;
                        }
                    }
                    $consult_list[] = array($value->to_project_id,$area,$tpurl,$projectinfo->project_brand_name,Arr::get($monarr,$projectinfo->project_amount_type,'暂无'),$value->content,date('Y-m-d H:i:s', $value->add_time),$projectinfo->project_status);
                }else{
                    $consult_list[] = array($value->to_project_id,$area,$url,'','暂无',$value->content,date('Y-m-d H:i:s', $value->add_time),0);
                }
            }
        }
        $this->content->rightcontent->page = $return_arr['page'];
        $this->content->rightcontent->consult_list = $consult_list;
    }//end function
}