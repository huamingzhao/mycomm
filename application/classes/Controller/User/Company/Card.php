<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户名片信息
 * @author 钟涛
 */
class Controller_User_Company_Card extends Controller_User_Company_Template{
	
	/**
	 * 我的企业名片
	 * @author 钟涛
	 * @modify 周进  增加了项目表中的一些内容处理  @2012.11.22
	 */
	public function action_myCard2(){
// 		$ser=new Service_Public();
// 		echo phpinfo();exit;
// 		$ser->updateImage();
	}
	
	/**
	 * 企业已经查看的名片
	 * @author 钟涛
	 */
	public function action_alreadyViewCard(){
		//获取登录user_id
		$userid = $this->userInfo()->user_id;
		$service = new Service_User_Company_Card();
		$result=$service->getAlreadyViewCard($userid);
// 		echo '<pre>';
// 		echo count($result);
// 		print_r($result);
		//view页面加载
		if(count($result['list'])){
			$content = View::factory("user/company/alreadyviewcard");
		}else{
            //view页面加载 未查看名片
            $content = View::factory("user/company/noviewcard");
        }
		$this->content->rightcontent = $content;
		$content->list = $result['list'];
		$content->page = $result['page'];
	}
	
    /**
     * @sso
     * 我的企业名片
     * @author 钟涛
     * @modify 周进  增加了项目表中的一些内容处理  @2012.11.22
     */
    public function action_myCard(){
        //获取登录user_id
        $userid = $this->userInfo()->user_id;
        $cardstyleid=$this->userInfo(true)->basic->card_style;
        $company = ORM::factory('Companyinfo');
        $companyresult = $company->where('com_user_id', '=', $userid)->find()->as_array();

        $service = new Service_User_Company_User();
        $pro_service =new Service_User_Company_Project();
        $card_service =new Service_User_Company_Card();

        $com_com	= $this->is_complete_basic($userid);
        if( $com_com===false ){//判断是否已经完善企业信息
            $content = View::factory("user/company/company_tel");
            $this->content->rightcontent = $content;
            $content->title = '我的名片';
            $content->errMsg = '请先填写企业基本信息，这样您才有自己的名片哟！';
            $content->hrefUrl = "/company/member/basic/editcompany?type=1";
            $content->display_type = 4;
        }else{
            $user = $this->userInfo(true);
            $visit_card=$this->userInfo(true)->basic->visit_card;
            if( $visit_card==0){//判断是否访问过名片
                $user->basic->visit_card = 1;
                $user->basic->update();
            }
            //view页面加载
            $content = View::factory("user/company/businessinfo");
            $this->content->rightcontent = $content;
            //获取我的企业名片 多做了一步处理公司电话的操作
            $companinfo = $service->getCompanyInfo($userid);
            $companinfo->com_phone = $card_service->checkComPhone($companinfo->com_phone);
            $content->companyinfo = $companinfo;
            $content->cardstyleid = $cardstyleid;
            //名片上面序列化保存的项目信息
            $data = unserialize($content->companyinfo->com_card_config);
            $projectinfo = $card_service->getProjectByCompanyCard($data);
            $content->logo=$projectinfo['logo'];//名片上面logo
            $content->brand = $projectinfo['brand'];//名片上面项目
            $time=isset($data['time'])?$data['time']:0;//名片修改时间

            //获取地址
            if( ceil($companinfo->com_area)>0 ){
                $area_arr= array('id'=>$companinfo->com_area);
                $rs_area= common::arrArea($area_arr);
                $area_name= $rs_area->cit_name;
            }else{
                $area_name= '';
            }
            if( ceil( $companinfo->com_city!='' )>0  ){
                $city_arr= array('id'=>$companinfo->com_city);
                $rs_city= common::arrArea($city_arr);
                $city_name= $rs_city->cit_name;
            }else{
                $city_name= '';
            }
            $companinfo->com_adress = $area_name.$city_name.$companinfo->com_adress;
            //获取我的企业项目信息
            $content->pro = $service->findProjectInfo($content->companyinfo->com_id);
            //判断企业是否已经有通过审核的项目
            $ishasproject=$pro_service->isHasProject($content->companyinfo->com_id);
            $content->ishasproject = $ishasproject;
            if($ishasproject){//已经有通过审核的
                $content->isfisrthasproject = false;
            }else{
                 if($pro_service->isHasProjectSheng($content->companyinfo->com_id)){//有在审核的项目
                     $content->isfisrthasproject = true;
                 }else{
                     $content->isfisrthasproject = false;
                 }
            }
            //获取新项目数量（以点击生成名片时间为基准，之后新建的项目均为新项目）
            $content->newprojectcount = $pro_service->getNewProjectCount($time,$content->companyinfo->com_id);
        }
    }

    /**
     * 我收到投资者的名片信息
     * @author 钟涛
     */
    public function action_receiveCard(){
        $service=new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        //获取登录user_id
        $userid = $this->userInfo()->user_id;
        //判断是否邮箱验证

        if($card_service->getReceiveCardCount($userid)){
            //view页面加载 已收到名片
            $content = View::factory("user/company/personinfo");
            //获取页面post表单值
            $search = $this->request->query();
            //2级取得行业
            $list_industry2=array();
            if(isset($search['parent_id']) && is_numeric($search['parent_id'])){
                $list_industry2s =common::primaryIndustry($search['parent_id']);
                foreach ($list_industry2s as $k=>$v){
                    $list_industry2[$k]['industry_id']=$v->industry_id;
                    $list_industry2[$k]['industry_name']=$v->industry_name;
                }
            }
            //获取我收到投资者的名片信息列表
            $return_arr=$card_service->searchReceiveCardInfo($search,$userid);
            $per_service = new Service_User_Person_User();
            $inv_ser = new Service_Platform_SearchInvestor();
            foreach($return_arr['list'] as $k=>$v){
                //获得个人意向投资地区
                $return_arr['list'][$k]['per_area'] = $per_service->getPersonalArea($v['per_user_id']);
                //获取个人所在地，只获取省份
                $return_arr['list'][$k]['locate_per_area'] = $per_service->getPerasonalAreaStringOnlyPro($v['per_user_id']);
                //活跃度
                $return_arr['list'][$k]['huoyuedu'] = $inv_ser->getAllScore($v['per_user_id']);//活跃度
            }
            $content->list_industry2=$list_industry2;
            $content->list=$return_arr['list'];
            $content->page= $return_arr['page'];
            $content->postlist=$search;
        }
        else{
            //view页面加载 未收到名片
            $content = View::factory("user/company/noreceivecard");
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 我递出的投资者的名片信息
     * @author 钟涛
     */
    public function action_outCard(){
        $service=new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        //获取登录user_id
        $userid = $this->userInfo()->user_id;
        //判断是否邮箱验证

        if($card_service->getOutCardCount($userid)){
            //view页面加载 已递出名片信息
            $content = View::factory("user/company/outcard");
            //获取页面post表单值
            $search = $this->request->query();
            //2级取得行业
            $list_industry2=array();
            if(isset($search['parent_id']) && is_numeric($search['parent_id'])){
                $list_industry2s =common::primaryIndustry($search['parent_id']);
                foreach ($list_industry2s as $k=>$v){
                    $list_industry2[$k]['industry_id']=$v->industry_id;
                    $list_industry2[$k]['industry_name']=$v->industry_name;
                }
            }
            //获取我递出的投资者名片信息列表
            $return_arr=$card_service->searchOutCardInfo($search,$userid);
            $content->list_industry2=$list_industry2;
            $content->list=$return_arr['list'];
            $content->page= $return_arr['page'];
            $content->postlist=$search;
        }
        else{
            //view页面加载 未递出名片
            $content = View::factory("user/company/nooutcard");
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 选择名片模板信息
     * @author 钟涛
     */
    public function action_cardStyle(){
        //view页面加载
        $content = View::factory("user/company/card_template");
        $this->content->rightcontent = $content;
        //获取登录user_id
        $userid =$this->userInfo()->user_id;
        $service=new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        //获取当前页数
        $urlpage = $this->request->query('page')?$this->request->query('page'):1;
        //获取模板图片列表
        $return_arr=$card_service->getCardStyleInfo($urlpage);
        $content->imglist = $return_arr['list'];
        $content->page= $return_arr['page'];
        //获取我的企业名片 多做了一步处理公司电话的操作
        $companinfo = $service->getCompanyInfo($userid);
        $companinfo->com_phone = $card_service->checkComPhone($companinfo->com_phone);
        $content->companyinfo = $companinfo;
        //名片上面序列化保存的项目信息
        $data = unserialize($content->companyinfo->com_card_config);
        $projectinfo = $card_service->getProjectByCompanyCard($data);
        $content->logo=$projectinfo['logo'];//名片上面logo
        $content->brand = $projectinfo['brand'];//名片上面项目
        $content->pro = $service->findProjectInfo($content->companyinfo->com_id);
    }

    /**
     * 保存名片模板信息
     * @author 钟涛
     */
    public function action_saveCardStyle(){
        //获取登录user_id
        $user_id =$this->userInfo()->user_id;
        //获取当前选择的名片模板ID
        $cardstyle = arr::get($this->request->query('cardkey'),'cardkey',0);
        $card_service=new Service_User_Company_Card();
        //更新模板id
        if($card_service->updateCardStyleInfo($user_id, $cardstyle)){
            self::redirect("/company/member/card/mycard");
        }
    }

    /**
     * 更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateReceiveDelStatus(){
        $get = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $result = $card_service->updateReceiveDelStatus(arr::get($get,'id'));
        if($result){
            self::redirect("/company/member/card/receiveCard");
        }
    }

    /**
     * 批量更改我收到的名片为已删除状态
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
        self::redirect("/company/member/card/receiveCard");
    }

    /**
     * 更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateOutDelStatus(){
        $get = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $result = $card_service->updateOutDelStatus(arr::get($get,'id'));
        if($result){
            self::redirect("/company/member/card/outCard");
        }
    }

    /**
     * 批量更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function action_updateBatchOutDelStatus(){
        $getdata = Arr::map("HTML::chars", $this->request->query());
        $card_service=new Service_User_Company_Card();
        $cardidarr=array();
        //名片id数组
        $cardidarr= explode(",", $getdata['cardidarr']);
        //更改我收到的名片未已删除状态
        $card_service->updateBatchOutDelStatus($cardidarr);
        self::redirect("/company/member/card/outCard");
    }

    /**
     * 显示完善企业名片
     * @author 周进
     */
    public function action_completeCard(){
        $userid=$this->userInfo()->user_id;
        $company = ORM::factory('Companyinfo');
        $companyresult = $company->where('com_user_id', '=', $userid)->find()->as_array();
        //判断是否已经完善企业信息
        if ($companyresult['com_id']>0){
            $card_service=new Service_User_Company_Card();
            $content = View::factory("user/company/completecard");
            $service = new Service_User_Company_Card();
            $result = $service->getCompanyCard($userid);
            $content->companyinfo = $result['companyinfo'];

            //名片上面序列化保存的项目信息
            $data = unserialize($content->companyinfo->com_card_config);
            $projectinfo = $card_service->getProjectByCompanyCard($data);
            $content->logo=$projectinfo['logo'];//名片上面logo
            $content->brand = $projectinfo['brand'];//名片上面项目
            $content->pro = $result['pro'];
            $this->content->rightcontent = $content;
        }
        else{
            //跳转到完善企业信息页面
            self::redirect("/company/member/basic/company");
        }
    }

    /**
     * 完善企业名片处理
     * @author 周进
     */
    public function action_actCompleteCard(){
        if (HTTP_Request::POST === $this->request->method())
        {
            $post = Arr::map("HTML::chars", $this->request->post());
            $content = View::factory("user/company/businessinfo");
            $card_service=new Service_User_Company_Card();
            $pro_service=new Service_User_Company_Project();
            $service=new Service_User_Company_User();
            $result = $card_service->updateCompanyCard($this->userInfo()->user_id,$post);
            if ($result['status'] == false){
                echo '操作失败，数据格式不符合规范！';//改为标号
                self::redirect("/company/member/card/completecard");
                exit;
            }
            //@sso 赵路生 2013-11-11
            $content->cardstyleid = $this->userInfo(true)->basic->card_style;
            //获取我的企业名片 多做了一步处理公司电话的操作
            $companinfo = $service->getCompanyInfo($this->userInfo()->user_id);
            $companinfo->com_phone = $card_service->checkComPhone($companinfo->com_phone);
            $content->companyinfo = $companinfo;
            //名片上面序列化保存的项目信息
            $data = unserialize($content->companyinfo->com_card_config);
            $projectinfo = $card_service->getProjectByCompanyCard($data);
            $content->logo=$projectinfo['logo'];//名片上面logo
            $content->brand = $projectinfo['brand'];//名片上面项目

            $content->pro = $result['pro'];
            $time=isset($data['time'])?$data['time']:0;
            //判断企业是否已经有通过审核的项目
            $ishasproject=$pro_service->isHasProject($content->companyinfo->com_id);
            $content->ishasproject = $ishasproject;
            if($ishasproject){//已经有通过审核的
                $content->isfisrthasproject = false;
            }else{
                 if($pro_service->isHasProjectSheng($content->companyinfo->com_id)){//有在审核的项目
                     $content->isfisrthasproject = true;
                 }else{
                     $content->isfisrthasproject = false;
                 }
            }
            //获取新项目数量（以点击生成名片时间为基准，之后新建的项目均为新项目）
            $content->newprojectcount = $pro_service->getNewProjectCount($time,$content->companyinfo->com_id);
            $this->content->rightcontent = $content;
            //生成一张图片
            $card_service->getComCardImage($companinfo,$result['pro'],$projectinfo['brand']);
        }
    }

    /**
     * 收藏名片列表
     * @author 周进
     */
    public function action_favorite(){
        $search=Arr::map("HTML::chars", $this->request->query());
        if(arr::get($search,'industry_id')===0){
            $search['industry_id']='';
        }
        $card_service=new Service_User_Company_Card();
        if($card_service->getFavoriteNums($this->userId())>0){
            $content = View::factory("user/company/favorite");
            $favoriteids = $this->request->post('exchangecard_favorite_id');
            if (is_array($favoriteids))
                 $card_service->updateFavorite($this->userId(),2,$favoriteids);
            $search['exchange_status'] = isset($search['exchange_status'])?$search['exchange_status']:0;
            $search['from_read_status'] = isset($search['from_read_status'])?$search['from_read_status']:'-1';
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
            $result = $card_service->searchFavorite($search,$this->userId());
            $content->list_industry2=$list_industry2;
            $content->list = $result['list'];
            $content->page = $result['page'];
            $content->totalcount = $result['page']->total_items;
            $content->postlist = $search;
        }
        else
            $content = View::factory("user/company/nofavorite");
        $this->content->rightcontent = $content;
    }

    /**
     * 企业用户取消收藏的名片
     * @author 周进
     */
    public function action_updatefavorite(){
        $card_service=new Service_User_Company_Card();
        $result = $card_service->updateFavorite($this->userId(),1, $this->request->query('favorite_id'));
        $search['exchange_status'] = isset($search['exchange_status'])?$search['exchange_status']:0;
        $search['from_read_status'] = isset($search['from_read_status'])?$search['from_read_status']:'-1';
        $content = View::factory("user/company/favorite");
        $result = $card_service->searchFavorite($search,$this->userId());
        $content->list = $result['list'];
        $content->page = $result['page'];
        $content->totalcount = $result['total_count'];
        $content->postlist = $search;
        $this->content->rightcontent = $content;
    }

    /**
     * 搜索投资者页面收藏名片
     */
    public function action_addfavorite(){
        $card_service = new Service_User_Company_Card();
        $result=$card_service->addFavorite($this->userId(),1, $this->request->query('favorite_id'));
        $this->redirect("company/member/investor/search");
    }
}