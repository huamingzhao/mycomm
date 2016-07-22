<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 招商项目controller
 * @author 曹怀栋
 *
 */
class Controller_User_Company_Project extends Controller_User_Company_Template {

    public function before(){
        parent::before();
        $forms = Arr::map("HTML::chars", $this->request->query());
        $post = Arr::map("HTML::chars", $this->request->post());

        if(isset($forms['project_id']) && $forms['project_id']) {
            $project_id = intval($forms['project_id']);
            $service = new Service_User_Company_Project();
            $com_id = $service->getCompanyId($this->userId());
            $projectPromission = $service->checkProjectPermission($project_id, $com_id);
            if(!$projectPromission){
                self::redirect("/company/member/project/showproject");
            }
        }
        if(isset($post['project_id']) && $post['project_id']) {
            $project_id = intval($post['project_id']);
            $service = new Service_User_Company_Project();
            $com_id = $service->getCompanyId($this->userId());
            $projectPromission = $service->checkProjectPermission($project_id, $com_id);
            if(!$projectPromission){
                self::redirect("/company/member/project/showproject");
            }
        }
    }

    /**
     * 我认领的项目
     * @author 钟涛
     */
    public function action_showMyRenglingProject() {
        $content = View::factory("user/company/project/noprojectrenling");
        $this->content->rightcontent = $content;
    }
    /**
     * 修改推广信息
     * @author 施磊
     */
    public function action_doUpdateProjectSpread() {
    		#开启缓存
    		$redis = Cache::instance ('redis');
            #实例化
            $service = new Service_User_Company_Project();
            $post = Arr::map("HTML::chars", $this->request->post());
            $project_id = $post['project_id'];
            #判断项目是不是审核通过
            $int_num = $service->get_project_status($project_id);
            $type = ($post['type'] == 1) ? '添加' : '修改';
            if($int_num == intval(2)){
            	$service->updateProjectTempStatus($project_id);
            	$redis->set($project_id."_project_tuiguang_list", json_encode($post),'2592000');
            	$service->delect_redis_status($project_id);
            	$service->updateProjectByParam($project_id, array("project_reason"=>''));
            	$redis->set($project_id."_project_tuiguang_list_status",2,"2592000");
            }else{
            	$project_advert = $post['project_advert'];
            	$project_xuanchuan_da_logo = $post['project_xuanchuan_da_logo'] ? str_replace("/s_", "/b_", $post['project_xuanchuan_da_logo']) : $post['project_xuanchuan_da_logo'];
            	$project_xuanchuan_xiao_logo = $post['project_xuanchuan_xiao_logo'];
            	$project_tag = $post['project_tag'];
            	$project_summary = $post['project_summary'];
            	if($int_num == 3){
            		$param = array('project_advert' => $project_advert, 'project_summary' => $project_summary, 'project_status'=>1);
            	}else{
            		$param = array('project_advert' => $project_advert, 'project_summary' => $project_summary);
            	}
            	
            	$service->updateProjectByParam($project_id, $param);
            	if($project_xuanchuan_da_logo) {
            		$data = array('project_id' => $project_id, 'img' => array($project_xuanchuan_da_logo));
            		$service->addProjectXuanChuanImages($data, '4');
            	}
            	if($project_xuanchuan_xiao_logo) {
            		$data = array('project_id' => $project_id, 'img' => array($project_xuanchuan_xiao_logo));
            		$service->addProjectXuanChuanImages($data, '5');
            	}
            	$tagArr = $post['project_tag'] ? explode(' ', $post['project_tag']) : $post['project_tag'];
            	if($tagArr) $service->addProjectTag($project_id, $tagArr);
            }
            $service->updateProjectTime($project_id);
            $session = Session::instance();
            $session->set('showProId', $project_id);
            $session->set('showProMsg', "<font>恭喜您，项目推广信息{$type}成功,</font><span>审核通过后，投资者就可在您的项目官网查看到。</span>");
            self::redirect("/company/member/project/showproject");
    }
    /**
     * 修改推广信息
     * @author 施磊
     */
    public function action_updateProjectSpread() {
            $get = Arr::map("HTML::chars", $this->request->query());
            $service = new Service_User_Company_Project();
            $content = View::factory("user/company/project/updateprojectspread");
            $redis = Cache::instance ('redis');
            $str_image_big = "";
            $str_image_small = "";
            $project_tag = "";
            $result = array();
            $result = $service->getProject(arr::get($get, 'project_id'));
            //读取当前id的数据
            $type = arr::get($get, 'type');
            #判断项目是不是  审核通过的项目
            $int_project_status = $service->get_project_status(arr::get($get, "project_id"));
            if($int_project_status == 2){            	
            	#获取缓存数据
            	$json_project_redis_list = $redis->get(arr::get($get, "project_id")."_project_tuiguang_list");
            	if($json_project_redis_list){
            		$arr_project_list = (array)json_decode($json_project_redis_list);
            		$str_image_big = isset($arr_project_list['project_xuanchuan_da_logo']) ? $arr_project_list['project_xuanchuan_da_logo'] : "";
            		$str_image_small = isset($arr_project_list['project_xuanchuan_xiao_logo']) ? $arr_project_list['project_xuanchuan_xiao_logo'] : "";
            		$project_tag = isset($arr_project_list['project_tag']) ? $arr_project_list['project_tag'] : "";
            		$result['project_summary'] = isset($arr_project_list['project_summary']) ? $arr_project_list['project_summary'] : "";
            		$result['project_advert'] = isset($arr_project_list['project_advert']) ? $arr_project_list['project_advert'] : "";
            	}else{
            		$project_tag = $service->getProjectTagByProjectId(arr::get($get, 'project_id'));
            		$project_tag = $project_tag ? implode(' ', $project_tag) : '';
            		 
            		#获取项目的广告图片
            		$arr_xuanchuan_image =  $service->getXuanChuanPic(arr::get($get, 'project_id'));
            		
            		if(is_array($arr_xuanchuan_image) && !empty($arr_xuanchuan_image)){
            			foreach ($arr_xuanchuan_image as $key=>$val){
            				if($val['project_type'] == intval(4)){
            					$str_image_big = URL::imgurl($val['project_img']);
            				}elseif($val['project_type'] == intval(5)){
            					$str_image_small = URL::imgurl($val['project_img']);
            				}
            			}
            		}
            	}
            }else{
            	$project_tag = $service->getProjectTagByProjectId(arr::get($get, 'project_id'));
            	$project_tag = $project_tag ? implode(' ', $project_tag) : '';
            	#获取项目的广告图片
            	$arr_xuanchuan_image =  $service->getXuanChuanPic(arr::get($get, 'project_id'));
            	 
            	if(is_array($arr_xuanchuan_image) && !empty($arr_xuanchuan_image)){
            		foreach ($arr_xuanchuan_image as $key=>$val){
            			if($val['project_type'] == intval(4)){
            				$str_image_big = URL::imgurl($val['project_img']);
            			}elseif($val['project_type'] == intval(5)){
            				$str_image_small = URL::imgurl($val['project_img']);
            			}
            		}
            	}
            }	
            $content->result = $result;
            $content->project_id = arr::get($get, 'project_id');
            //取得项目标签
            $ProjectTag = $service->findProjectTag(20);
            $content->ProjectTag = $ProjectTag;
            $content->project_tag = $project_tag;
            $content->str_image_big = $str_image_big;
            $content->str_image_small = $str_image_small;
            $content->type = $type;
            $this->content->rightcontent = $content;
    }
    /**
     * 修改联系人
     * @author 施磊
     */
    public function action_updateProjectContact() {
    		$redis = Cache::instance ('redis');
            $get = Arr::map("HTML::chars", $this->request->query());
            $service = new Service_User_Company_Project();
            $arr_project_content_list = array();
            //读取当前id的数据
            $result = $service->getProject(arr::get($get, 'project_id'));
            if($result['project_status'] == 2){            	
            	$json_project_content_list = $redis->get(arr::get($get, 'project_id')."_project_content_list");
            	if($json_project_content_list){
            		$arr_project_content_list = (array)json_decode($json_project_content_list);
            		#姓名
            		$result['project_contact_people'] = isset($arr_project_content_list['project_contact_people']) ? $arr_project_content_list['project_contact_people'] : "";
            		#手机号码
            		$result['project_handset'] = arr::get($arr_project_content_list, "project_handset");
            		#职位 
            		$result['project_position'] = isset($arr_project_content_list['project_position']) ? $arr_project_content_list['project_position'] : "";
            		#电话号码
            		$result['project_phone'] = isset($arr_project_content_list['project_phone']) ? explode('+', $arr_project_content_list['project_phone']): "";
            	}else{
            		$result['project_phone'] = explode('+', $result['project_phone']);
            	}
            }else{
            	$result['project_phone'] = explode('+', $result['project_phone']);
            }
            $type = arr::get($get, 'type');
            $content = View::factory("user/company/project/updateprojectcontact");
            $content->result = $result;
            $content->type = $type;
            $content->project_id = arr::get($get, 'project_id');
            $this->content->rightcontent = $content;
    }
    /**
     * 修改联系人
     * @author 施磊
     */
    public function action_doUpdateProjectContact() {
    	$redis = Cache::instance("redis");
        #实例化
        $service = new Service_User_Company_Project();
        $post = Arr::map("HTML::chars", $this->request->post());
        $project_id = $post['project_id'];
        $type = ($post['type'] == 1) ? '完善' : '修改';
        #判断当前是不是审核通过的项目
        $int_project_status = $service->get_project_status($project_id);
        if($int_project_status == intval(2)){
        	$service->updateProjectTempStatus($project_id);
        	$project_phone = $post['project_phone'];
        	$project_phone_fj = $post['project_phone_fj'];
        	if($project_phone_fj != '') {
        		$post['project_phone'] = $project_phone.'+'.$project_phone_fj;
        	}
        	$redis->set($project_id."_project_content_list",json_encode($post),"2592000");
        	$service->delect_redis_status($project_id);
        	$service->updateProjectByParam($project_id,array("project_reason"=>''));
        	$redis->set($project_id."_project_content_list_status",2,"2592000");
        }else{
        	$project_contact_people = $post['project_contact_people'];
        	$project_position = $post['project_position'];
        	$project_handset = $post['project_handset'];
        	$project_phone = $post['project_phone'];
        	$project_phone_fj = $post['project_phone_fj'];
        	if($project_phone_fj != '') {
        		$project_phone = $project_phone.'+'.$project_phone_fj;
        	}
        	if($int_project_status == 3){
        		$param = array('project_status'=>1,'project_contact_people' => $project_contact_people, 'project_position' => $project_position, 'project_handset' => $project_handset, 'project_phone' => $project_phone);
        	}else{
        		$param = array('project_contact_people' => $project_contact_people, 'project_position' => $project_position, 'project_handset' => $project_handset, 'project_phone' => $project_phone);
        	}
        	
        	$service->updateProjectByParam($project_id, $param);
        }
        
      
        $session = Session::instance();
        $session->set('showProId', $project_id);
        $session->set('showProMsg', "<font>恭喜您，联系人信息{$type}成功。</font><span>审核通过后，投资者就可在您的项目官网查看到。</span>");
        self::redirect("/company/member/project/showproject");
    }
    
    
    public function showProject() {
    	$redis = Cache::instance("redis");
        #实例化
        $service = new Service_User_Company_Project();
        #加载模版
        $content = View::factory("user/company/project/showProjectNew");
        #取得企业id
        $com_id = $service->getCompanyId($this->userId());
        #通过企业id取得项目列表
        $result = $service->showProjectAndRenling($com_id);
        #企业信息
        $checkStatus = $service->checkComInfo($com_id, $this->userId());
        #认领信息
        $query_data = $this->request->query();
        $renling = arr::get($query_data,'tpye','');
        #获得项目总数
        $procount = $service->getProjectCount($com_id);
        #检查用户的一些信息是否完整
        if(!$checkStatus['comStatus']) {
            $content = View::factory("user/company/company_tel");
            $content->title = '我的项目';
            $content->classStyle = 'ryl_company_phone_tel_check';
            if (!$checkStatus['authStatus'] && $procount==0) {
                $content->errMsg = '很抱歉，您需要先验证手机号码且上传企业资质图片才可以发布项目哦！';
            } else {
                $content->errMsg = '很抱歉，您需要先验证手机号码才可以发布项目哦！';
            }

            $content->hrefUrl = '/company/member/valid/mobile';
        } else if (!$checkStatus['authStatus'] && $procount==0) {
            $content = View::factory("user/company/company_tel");
            $content->title = '我的项目';
            $content->classStyle = 'ryl_company_tel_check';
            $content->hrefUrl = '/company/member/basic/comCertification';
            $content->errMsg = '很抱歉，您需要先上传企业资质图片才可以发布项目哦。';
        }
        #如果没有项目？
        if (!$result['page']->total_items) {
            self::redirect("/company/member/project/addproject");
        }
        $this->content->rightcontent = $content;
        $news = new Service_News_Article();
        $list = $service->checkProtList($result['list'],$com_id);
        //echo "<pre>"; print_R($list);exit;
        if(!empty($list)){
            foreach($list as $k => $v){
                $list[$k]['haspnews'] = $news->getProjectZixunTof($v['project_id']);
            }
        }
      // echo "<pre>"; print_R($list);exit;
        $content->list = $list;
        $content->page = $result['page'];
        $content->com_id = $com_id;
        #获取海报状态
        //$int_haibao_status = $redis->get("");
        //当添加项目完成后，跳转的此页面要弹出提示层并删除Session
        $session = Session::instance();
        if ($session->get("showProId")) {
            $content->showProId = $session->get("showProId");
            $content->showProMsg = $session->get("showProMsg");
            $session->delete("showProId");
            $session->delete("showProMsg");
        } else {
            $content->showProId = $session->get("showProId");
            $content->showProMsg = $session->get("showProMsg");
        }
    }
    /**
     * 招商项目列表
     * @author 曹怀栋
     */
    public function action_showProject() {
        $this->showProject();
    }
    /**
     * 企业认领的项目
     * @author 钟涛
     */
    public function action_showProjectRenling() {
        $service = new Service_User_Company_Project();
        //取得企业id
        $com_id = $service->getCompanyId($this->userId());

        //通过企业id取得项目列表
        $result = $service->showProjectRenling($com_id);
//         if(count($result['list'])==0){//没有数据
//             $content = View::factory("user/company/project/noprojectrenling");
//         }else{
            $list= $service->getResaultList($result['list'],$com_id);
            $content = View::factory("user/company/project/showprojectrenling");
            $content->list =$list;
            $content->page = $result['page'];
//         }

        $query_data = $this->request->query();
        $renling=arr::get($query_data,'tpye','');
        $content->isrenling = $renling;
        $this->content->rightcontent = $content;
    }

    /**
     * 企业认领的项目【走搜索框】
     * @author 钟涛
     */
    public function action_searchProjectRenling() {
        $service = new Service_User_Company_Project();
        //取得企业id
        $com_id = $service->getCompanyId($this->userId());
        $form = Arr::map("HTML::chars", $this->request->query());
        $content = View::factory("user/company/project/showprojectrenling");
        $input_content=arr::get($form,'condition','');
        $searchresult = $service->searchProjectRenling($input_content);
        $match = arr::get($searchresult, 'matches', array());
        $total = 0;
        if(isset($searchresult['total'])) {
            $total = $searchresult['total'];
        }
        if($total==0){
            $page= '';
            $result=array();
        }else{
            $result= $service->getResaultListArr($match,$com_id);
            //分页条件
            $page = Pagination::factory(array(
                    'total_items' => $total,
                    'items_per_page' => 10,
                    'view' => 'pagination/Simple',
            ));
        }
        $content->list =$result;
        $content->inputvalue =$input_content;
        $content->page = $page;
        $this->content->rightcontent = $content;
    }

    /**
     * 招商项目添加
     * @author 嵇烨
     */
    public function action_addProject() {
        $content = View::factory("user/company/project/addproject");
        $session = Session::instance();
        $service = new Service_User_Company_Project();
        $service_account = Service::factory("Account");
        $account = $service_account->getAccount($this->userId());
        $user_account = $service_account->useAccount($this->userId(),10,0);
        //echo "<pre>"; print_R($user_account);exit;
        $account = arr::get($account, 'account', 0);
        //取得企业id
        $com_id = $service->getCompanyId($this->userId());
        #检查是否能发项目
        $projectMoney = $service->userProjectCount($this->userId(), $com_id);
        $checkStatus = $service->checkComInfo($com_id, $this->userId());
        if (!$checkStatus['comStatus']) {
            $content = View::factory("user/company/company_tel");
            $content->title = '我的项目';
            $content->classStyle = 'ryl_company_phone_tel_check';
            if (!$checkStatus['authStatus']) {
                $content->errMsg = '很抱歉，您需要先验证手机号码且上传企业资质图片才可以发布项目哦！';
				 $content->display_type = 1;
            } else {
                $content->errMsg = '很抱歉，您需要先验证手机号码才可以发布项目哦！';
				$content->display_type = 2;
            }
            $content->hrefUrl = '/company/member/valid/mobile';
        } else if (!$checkStatus['authStatus']) {
            $content = View::factory("user/company/company_tel");
            $content->title = '我的项目';
            $content->classStyle = 'ryl_company_tel_check';
            $content->hrefUrl = '/company/member/basic/comCertification';
            $content->errMsg = '很抱歉，您需要先上传企业资质图片才可以发布项目哦。';
			$content->display_type = 3;
        }
		$content->display_project_type = 1;
        $this->content->rightcontent = $content;
        #点过充值否
        //unset($_COOKIE['user_click_kouqian']);
        $content->user_click_kouqian = arr::get($_COOKIE, 'user_click_kouqian', 0);
        #服务产品化
        $content->projectMoney = $projectMoney;
        #账户余额
        $content->account = $account;
		#用户账户
		$content->user_account = $user_account;
        $content->list = common::primaryIndustry(0);
        //取得投资人群标签
        $content->tag = $service->findTag();
        //取得项目标签
        $ProjectTag = $service->findProjectTag(20);
        $content->ProjectTag = $ProjectTag;
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area);
        $content->project_count = $service->getProjectCount($com_id);
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            $_COOKIE['user_click_kouqian'] = 0;
            unset($_COOKIE['user_click_kouqian']);
            if($projectMoney['code'] == 2) {
                $accountServiec = new Service_Account();
                $account_result = $accountServiec->useAccount($this->userId(), 10, 0, '发布'.$form['project_brand_name'].'项目');
            }
            if(($projectMoney['code'] == 2 && arr::get($account_result,'is_forbid')) || $projectMoney['code'] == 1) {
                $msg = "<font>项目发布失败。</font>您的账号被禁用。";
                $session->set('showProId', intval(123));
                $session->set('showProMsg', $msg);
                self::redirect("/company/member/project/addProject");
            }
            #处理一下品牌发源地
            $city_parent_name = $service->getAreaNameByAreaId(isset($form['province_id']) ? $form['province_id'] :"");
            $city_child_name = $service->getAreaNameByAreaId(isset($form['per_area_id']) ? $form['per_area_id'] : "");
            if($city_parent_name && $city_child_name){
            	if($city_child_name == '国外'){
            		$form['project_brand_birthplace'] = $city_child_name;
            	}elseif($city_parent_name == $city_child_name){
                    $form['project_brand_birthplace'] = $city_parent_name;
                }else{
                    $form['project_brand_birthplace'] = $city_parent_name." ".$city_child_name;
                }
            }
            #处理项目行业
            $form['project_industry_id'] = array();
            $form['project_industry_id'][] = isset($form['industry_id1'])?$form['industry_id1'] :"";
            $form['project_industry_id'][] = isset($form['industry_id2'])?$form['industry_id2'] :"";
            #去除字段
               if(isset($form['province_id'])){ unset($form['province_id']);};
               if(isset($form['per_area_id'])){ unset($form['per_area_id']);};
               if(isset($form['industry_id1'])){unset($form['industry_id1']);};
               if(isset($form['industry_id2'])){unset($form['industry_id2']);};
            //数组改成字符串
            $form = $service->arrayToString($form);
            //if ($result == 1) {
                
                if ($com_id == false) {
                    $session->set("addProject2", 'addProject');
                    //没有完成企业基本信息的，先保存已经填写好的到session里，等完成基本信息后再写入数据库
                    $session->set("addproject", $form);
                    //返回添加页面并输入法com_id = "empty"弹出填写基本信息层
                    $content->list = common::primaryIndustry(0);
                    $content->forms = $form;
                    $content->com_id = "empty";
                    $content->projectcerts_count = 0;
                } else {
                    $form['com_id'] = $com_id;
                    if($projectMoney['code'] != 1) {
                        $res = $service->addProject($form,null, $this->userId());
                    }
                    if ($res > 0) {
                        $account = new Service_Account();
                        if($projectMoney['code'] == 2) {
                            $accountServiec = new Service_Account();
                            $account_result = $accountServiec->useAccount($this->userId(), 10, 1, '发布'.$form['project_brand_name'].'项目');
                        }
                        $msg = "<font>恭喜您，项目发布成功！</font>我们将在一个工作日内完成审核，审核通过3小时后，投资者通过搜索，进入项目官网即可查看相关详情。";
                         $session->set('showProId', intval(123));
                         $session->set('showProMsg', $msg);
                        self::redirect("/company/member/project/showproject");
                    }else{
                        self::redirect("/company/member/project/addProject");
                    }
                }
        }
    }
    /**
     * 招商项目添加(项目图片)
     * @author 曹怀栋
     */
    public function action_addProImg() {
        $service = new Service_User_Company_Project();
        $session = Session::instance();
        $arr_data_images = array();
        $arr_new_data_image = array();
        $arr_new_data_images = array();
        #开启缓存
      // $redis =  Rediska_Manager::get("list");
       	$redis = Cache::instance("redis");
        $int_project_status = $service->get_project_status($session->get("project_id"));
        
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            if (arr::get($form, 'data')) {
                $data['img'] = explode('||', arr::get($form, 'data'));
                if($int_project_status == 2){                	
                	#先去获取缓存中是不是有图片
                	$project_id = $session->get("project_id");                	
                	$arr_data_images = $redis->get($project_id."_project_images");
                	if($arr_data_images){
                		$arr_new_data_image = (array)json_decode($arr_data_images);
                		#循环放进数组
                		if(count($arr_new_data_image) > 0){
                			foreach ($arr_new_data_image as $key=>$val){
                				array_push($data['img'],$val);
                			}
                		}
                	}
                	$service->updateProjectTempStatus($project_id);
                	$redis->set($project_id."_project_images",json_encode($data['img']),"2592000");
                	$service->delect_redis_status($project_id);
                	$service->updateProjectByParam($project_id, array("project_reason"=>''));
                	$redis->set($project_id."_project_images_status",2,"2592000");
                }else{
                	if ($session->get("project_id")) {
                		$data['project_id'] = $session->get("project_id");
                		$service->updateProjectTime($data['project_id']);
                		$res = $service->addProjectImages($data, 1); //1表示项目图片
                		if ($res == true) {
                			$service->updateProjectStatus($session->get("project_id"), 1);
                			return true;
                			exit;
                		}
                	}
                }
                $service->updateProjectTime($project_id);
            }
        }
        $content = View::factory("user/company/project/addproimg");
        $this->content->rightcontent = $content;
        $forms = Arr::map("HTML::chars", $this->request->query());
        //判断是否是本用户和项目id是否存在
        $res = $service->getOneProject(arr::get($forms, 'project_id'), $this->userId());
        //创建session，为以后用来获得project_id
        $session->set("project_id", arr::get($forms, 'project_id'));
        #判断是不是已经审核通过的项目
  		if($int_project_status == intval(2) || $int_project_status == 0){
  			#去获取项目缓存中的图片
  			$arr_data_images = $redis->get(arr::get($forms, 'project_id')."_project_images");
  			if($arr_data_images){
  				$arr_new_data_image = (array)json_decode($arr_data_images);
  				#做一下标示 是数据库中 还是缓存中的数据
  				foreach ($arr_new_data_image as $key=>$val){
  					$arr_new_data_images[$key]['redis_type'] = intval(1); 
  					$arr_new_data_images[$key]['project_img'] = $val;
  					$arr_new_data_images[$key]['project_certs_id'] = 0;
  					$arr_new_data_images[$key]['project_id'] = arr::get($forms, 'project_id');
  				}
  			}
  			#获取数据库中的图片
  			$res = $service->getProjectImag(arr::get($forms, 'project_id'), 1);
  			if($res['list'] && count($res['list']) > 0){
  				#循环拿取数据
  				$arr = array();
  				foreach ($res['list'] as $key=>$val){
  					$arr [$key]['project_certs_id'] = $val->project_certs_id;
  					$arr [$key]['project_id'] = $val->project_id;
  					$arr [$key]['project_img'] = $val->project_img;
  					$arr [$key]['redis_type'] = intval(2);
  				}
  				#合并数组
  				foreach ($arr as $key=>$val){
  					array_push($arr_new_data_images,$val);
  				}
  				$res['list'] = $arr_new_data_images;
  			}else{
  				$res['list'] = $arr_new_data_images;
  			}
  		}else{
  			//取得项目图片信息
  			$res = $service->getProjectImag(arr::get($forms, 'project_id'), 1);
  			$project_image = array();
  			foreach ($res['list'] as $key=>$val){
  				$project_image[]= $val->as_array();
  				$project_image[$key]['redis_type'] = 2;
  			}
  			$res['list'] = $project_image;
  		}
  		$project_info = $service->getProjectInfo(arr::get($forms, 'project_id'), 1);
  		$project_status = $project_info['project_status'];
        $content->list =$res['list'];
        $content->pro_list = $res['list'];
        $content->page = $res['page'];
        $content->forms = $forms;
        $content->project_info = $project_info;
        $type = (arr::get($forms, 'type') == 1) ? '上传' : '修改';
        if($project_status == 1) {
            $msg = "<font>恭喜您，产品图片已经{$type}成功。</font>项目审核通过后，投资者就可在您的项目官网查看到。";
        }else{
            $msg = "<font>恭喜您，产品图片已经{$type}成功。</font>投资者可在您的项目官网查看到。";
        }
        $session->set('showProId', arr::get($forms, 'project_id'));
        $session->set('showProMsg', $msg);
    }

    /**
     * 招商项目添加(项目资质图片)
     * @author 曹怀栋
     */
    public function action_addProCertsImg() {
    	$forms = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        $session = Session::instance();
        $redis = Cache::instance("redis");
        $return = array();
        $arr_new_data_image = array();
        $project_id = $session->get("project_id") ? $session->get("project_id") : arr::get($forms,"project_id");
        #获取项目的状态
        $int_project_status = $service->get_project_status($project_id);
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            if (arr::get($form, 'data')) {
                $data['img'] = explode('||', arr::get($form, 'data'));
                $data['name'] = explode('||', arr::get($form, 'txt'));
                $arr_data = array();
                $arr_new_data = array();
                if ($session->get("project_id")) {
                	if($int_project_status == 2 ){
                		#处理数组拼和拼
                		if(count($data['img']) > 0 && count($data['name']) > 0){
                			foreach ($data['img'] as $key=>$val){
                				$arr_data['project_img'] = $val;
                				$arr_data['project_imgname'] = $data['name'][$key];
                				$arr_new_data [] = (array)$arr_data;
                			}
                		}
                		#先去获取缓存中是不是有图片
                		$arr_data_images = $redis->get($session->get("project_id")."_project_zizhi_images");
                		if($arr_data_images){
                			$arr_new_data_image = (array)json_decode($arr_data_images);
                			#循环放进数组
                			if(count($arr_new_data_image) > 0){
                				foreach ($arr_new_data_image as $key=>$val){
                					array_push($arr_new_data,(array)$val);
                				}
                			}
                		}
                		$service->updateProjectTempStatus($project_id);
                		$redis->set($session->get("project_id")."_project_zizhi_images",json_encode($arr_new_data),"2592000");
						$service->delect_redis_status($project_id);
						$service->updateProjectByParam($project_id, array("project_reason"=>''));
						$redis->set($project_id."_project_zizhi_images_status",2,"2592000");
					}else{
                		$data['project_id'] = $project_id;
                		$service->updateProjectTime($data['project_id']);
                		$res = $service->addProjectImages($data, 2); //2表示项目资质图片
                		if ($res == true) {
                			$service->updateProjectStatus($project_id, 1);
                			return true;
                			exit;
                		}
                	}
                	$service->updateProjectTime($project_id);
                }
            }
        }
        $content = View::factory("user/company/project/addprocertsimg");
        $this->content->rightcontent = $content;
        
        //判断是否是本用户和项目id是否存在
        $res = $service->getOneProject(arr::get($forms, 'project_id'), $this->userId());
        if ($res == false) {
            self::redirect("/company/member/project/addproject");
        }
        #获取图片信息
        if($int_project_status == 2 || $int_project_status == 0){
        	#去获取项目缓存中的图片
        	$arr_data_images = $redis->get(arr::get($forms, 'project_id')."_project_zizhi_images");
        	if($arr_data_images){
  				$arr_new_data_image = (array)json_decode($arr_data_images);
  			}
  			#模拟真实数据
  			$returns =array();
  			foreach ($arr_new_data_image as $key=>$val){
  				$returns[] = (array)$val;
  			}
  			if(count($returns) > 0){
	  			foreach ($returns as $key=>$val){
	  				$return[$key]['redis_type'] = 1;
	  				$return[$key]['project_id'] = $project_id;
	  				$return[$key]['project_certs_id'] = 0;
	  				$return[$key]['project_imgname'] = $val['project_imgname'];
	  				$return[$key]['project_img'] = $val['project_img'];
	  			}
  			}
  			#获取数据库的数据库
  			$res = $service->getProjectImag(arr::get($forms, 'project_id'), 2);
  			#循环拿取数据
  			$arr = array();
  			if(count($res['list']) > 0){
  				foreach ($res['list'] as $key=>$val){
  					$arr[$key]['redis_type'] =  2;
  					$arr[$key]['project_id'] =  $val->project_id;
  					$arr[$key]['project_type'] = $val->project_type;
  					$arr[$key]['project_img'] =  $val->project_img;
  					$arr[$key]['project_imgname'] =  $val->project_imgname;
  					$arr[$key]['project_certs_id'] =  $val->project_certs_id;
  					
  				}
  				#合并数组
  				foreach ($arr as $key=>$val){
  					array_push($return,$val);
  				}
  			}
  			
        }else{
        	$res = $service->getProjectImag(arr::get($forms, 'project_id'), 2);
        	foreach ($res['list'] as $key=>$val){
        		$return[]= $val->as_array();
        		$return[$key]['redis_type'] = 2;
        	}
        }
        //创建session，为以后用来获得project_id
        $session->set("project_id", arr::get($forms, 'project_id'));
        //echo "<pre>"; print_R($return);exit;
        //取得项目图片信息
        $project_info = $service->getProjectInfo(arr::get($forms, 'project_id'), 1);
        $content->list = $return;
        $content->page = $res['page'];
        $content->forms = $forms;
        $content->project_info = $project_info;
        $project_status = $project_info['project_status'];
        $type = (arr::get($forms, 'type') == 1) ? '上传' : '修改';
        if($project_status == 1) {
            $msg = "<font>恭喜您，项目资质图片已经{$type}成功。</font>项目审核通过后，投资者就可在您的项目官网查看到。";
        }else{
            $msg = "<font>恭喜您，项目资质图片已经{$type}成功。</font>投资者可在您的项目官网查看到。";
        }
        $session->set('showProId', arr::get($forms, 'project_id'));
        $session->set('showProMsg', $msg);
    }

    /**
     * 招商项目添加(项目海报)
     * @author 曹怀栋
     */
    public function action_addProPlaybill() {
        $form = Arr::map("HTML::chars", $this->request->query());
        $project_id = intval(Arr::get($form, "project_id"));
        //不存在项目id，调整到项目列表
        if (!$project_id) {
            self::redirect("company/member/project/showproject");
        }
        $posters = Service::factory("User_Company_Poster");
        $project = Service::factory("User_Company_Project");
        //项目是否存在
        $is_project = $project->isProjectExists($this->userId(), $project_id);
        if (!$is_project) {
            self::redirect("company/member/project/showproject");
        }
        //项目海报
        $poster = $posters->getPosterById($project_id);
        //项目图片
        $project_images = $project->getProjectImg($project_id);
        $service = new Service_User_Company_Project();
        //添加海报,表单提交,只是针对模板的
        if ($this->request->method() == HTTP_Request::POST) {
            if ($this->request->post("template")) {
                $p = $posters->addPosterByTemplate(
                        array(
                            "project_id" => $project_id,
                            "template_id" => $this->request->post("template")
                        )
                );
                $service->updateProjectTime($project_id);
                if ($p) {
                    //跳转
                    self::redirect("company/member/project/proinvestment?project_id={$project_id}");
                }
            }
        }
        $service = new Service_User_Company_Project();
        $service->updateProjectTime($project_id);
        $project_info = $service->getProjectInfo($project_id);
        $content = View::factory("user/company/project/addproplaybill");
        $this->content->rightcontent = $content;
        $content->forms = $form;
        $content->poster = $poster;
        $content->is_project = $is_project;
        $content->project_images = $project_images;
        $content->project_info = $project_info;
    }

/**
     * 招商项目添加(我的招商会添加)
     * @author 曹怀栋
     */
    public function action_addProInvestment() {
        $service = new Service_User_Company_Project();
        //$ucmsg = new Service_User_Ucmsg();
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            $input = array();
            $com_id = $service->getCompanyId($this->userId());
            $ret['path'] = $form['investment_logo'];
            $service->updateProjectTime($form['project_id']);

            $bool = false;

             $input['investment_name']=$form['investment_name'];
             $input['investment_logo']=common::getImgUrl($ret['path']);
             $input['investment_address']=$form['investment_address'];
             $input['com_name']=$form['com_name'];
             $input['project_id']=$form['project_id'];
             $input['com_phone']=$form['com_phone'];
             $input['investment_start']=$form['investment_start'];
             $input['investment_end']=$form['investment_end'];
             $input['investment_details']=$form['investment_details'];
             $input['investment_agenda']=$form['investment_agenda'];
             $input['investment_preferential']=$form['investment_preferential'];
             $input['com_id']=$com_id;
             if(!empty($form['investment_id'])){
                 $input['investment_id']=$form['investment_id'];
              }else{
                unset($input['investment_id']);
              }
             $input['putup_type']=$form['putup_type'];
             $input['investment_province']=$form['investment_province'];
             $input['investment_city']=$form['investment_city'];
            $input['telephone']=$form['telephone'].'+'.$form['extension'];

            //虚拟意向人数
            $input['virtual_viewer']=rand(200,500);
             $input = Arr::map("HTML::chars", $input);
             $res = $service->addInvestCheck($input);
             if($res == 1){
                $resault = $service->updateProjectInvest($input,$this->userId(), $com_id);
             }else{
                return false;
               }


             if($resault == true){
                $project_status = ORM::factory('Project',$form['project_id']);
                if($project_status->project_status == 0){
                    $this->_submitProjectComm($form['project_id']);
                } else {
                    #判断项目是不是被收藏了
                    $arr_projectwatch = $service->ProjectIsWatch($project_status->project_id);
                    #当添加的是发送信息
                    if($bool == true ){
                        foreach ($arr_projectwatch as $key=>$val){
                            /**$ucmsg->pushMsg($val['watch_user_id'],'person_tzkch',"您收藏的<a target='_blank' href='".urlbuilder::project($project_status->project_id)."'>'".$project_status->project_brand_name."'</a>项目即将召开投资考察会",urlbuilder::projectInvest($project_status->project_id));**/
                            $smsg = Smsg::instance();
                            //内部消息发送
                            $smsg->register(
                                    "tip_person_tzkch",//企业发布投资考察会
                                    Smsg::TIP,//类型
                                    array(
                                            "to_user_id"=>$val['watch_user_id'],
                                            "msg_type_name"=>"person_tzkch",
                                            "to_url"=>urlbuilder::projectInvest($project_status->project_id)
                                    ),
                                    array(
                                            "url"=>urlbuilder::project($project_status->project_id)."'>'".$project_status->project_brand_name,
                                            "name"=>$project_status->project_brand_name
                                    )
                            );

                        }
                    }
                    echo '<script> window.parent.location.href="/company/member/project/myinvestment"; </script>';
                }
            } else {
                echo '<script> window.parent.location.href="/company/member/project/addinvest?project_id='.arr::get($form, 'project_id').'"; </script>';
            }
        } else {

            $content = View::factory("user/company/project/addproinvestment");
            $this->content->rightcontent = $content;
            $form = Arr::map("HTML::chars", $this->request->query());
            self::redirect("/company/member/project/addinvest?project_id=".$form['project_id']."&investment_id=".$form['investment_id']."&type=2");
            //判断是否是本用户和项目id是否存在
            $res = $service->getOneProject(arr::get($form, 'project_id'), $this->userId());
            if ($res == false) {
                self::redirect("/company/member/project/addproject");
            }
            $resault = $service->getInvestProjectid($form['project_id']);
            //存在我的招商会时
            if ($resault != false) {
                $invests = ORM::factory('Projectinvest')->where("project_id", "=", $resault->project_id)->find_all();
                $form['investment_name'] = $resault->investment_name;
                $form['investment_logo'] = URL::imgurl($resault->investment_logo);
                $form['com_name'] = $resault->com_name;
                $form['com_phone'] = str_replace('+', '-', $resault->com_phone);
                $form['investment_address'] = $resault->investment_address;
                $form['investment_details'] = $resault->investment_details;
                $form['investment_agenda'] = $resault->investment_agenda;
                $form['investment_preferential'] = $resault->investment_preferential;
                $form['putup_type'] = $resault->putup_type;
                $content->invests = $invests;
                $invest = new Service_User_Person_Invest();
                foreach ($invests as $v) {
                    $city[$v->investment_province] = $invest->getArea($v->investment_province);
                }
                $content->city = $city;
            } else {//表示还没有发布我的招商会
                $content->invest = false;
                $projectModel = ORM::factory('Project', $form['project_id']);
                $form['com_name'] = $projectModel->project_contact_people;
                $form['com_phone'] = str_replace('+', '-', $projectModel->project_phone);
            }
            $invest = new Service_User_Person_Invest();
            $project_info = $service->getProjectInfo(arr::get($form, 'project_id'));
            $content->project_info = $project_info;
            $content->area = $invest->getArea();
            $content->project_status = ORM::factory("Project", $form['project_id'])->project_status;
            $content->forms = $form;
        }
    }

    /**
     * 招商项目添加(我申请的招商会详情)
     * @author 潘宗磊
     */
    public function action_viewProInvestment() {
        $content = View::factory("user/company/project/viewinvestment");
        $this->content->rightcontent = $content;
        $service = new Service_User_Company_Project();
        $form = Arr::map("HTML::chars", $this->request->query());
        $form['project_id'] = intval($form['project_id']);
        //判断是否是本用户和项目id是否存在
        $res = $service->getOneProject(intval(arr::get($form, 'project_id')), $this->userId());
        if ($res == false) {
            self::redirect("/company/member/project/addproject");
        }
        $resault = $service->getInvestProjectid($form['project_id']);
        if ($resault == false) {
            self::redirect("/company/member/project/addProInvestment?project_id=" . $form['project_id']);
        }
        $invests = ORM::factory('Projectinvest')->where("project_id", "=", $resault->project_id)->find_all();
        $form['investment_name'] = $resault->investment_name;
        $form['investment_logo'] = URL::imgurl($resault->investment_logo);
        $form['com_name'] = $resault->com_name;
        $form['com_phone'] = $resault->com_phone;
        $form['investment_address'] = $resault->investment_address;
        $form['investment_details'] = $resault->investment_details;
        $form['investment_agenda'] = $resault->investment_agenda;
        $form['investment_start'] = $resault->investment_start-time();
        $form['investment_id'] = $resault->investment_id;
        $form['putup_type'] = $resault->putup_type;
        $form['investment_isadd'] = $resault->investment_isadd;
        $project_info = $service->getProjectInfo(arr::get($form, 'project_id'));
        $content->project_info = $project_info;
        $content->invests = $invests;
        $content->forms = $form;
    }

    /**
     * 招商项目添加(我的招商会)
     * @author 曹怀栋
     */
    public function action_proInvestment() {
        $form = Arr::map("HTML::chars", $this->request->query());
        //判断是否是本用户和项目id是否存在
        self::redirect("/company/member/project/viewProInvestment?project_id=" . arr::get($form, 'project_id'));
        /*  $content = View::factory("user/company/project/proinvestment");
          $this->content->rightcontent = $content;
          $service = new Service_User_Company_Project();
          $form = Arr::map("HTML::chars", $this->request->query());
          //判断是否是本用户和项目id是否存在
          $res = $service->getOneProject(arr::get($form,'project_id'),$this->userId());
          if($res == false){
          self::redirect("/company/member/project/addproject");
          }
          if(!isset($form['project_id'])){
          $content->invest = false;
          }else{
          $res = $service->getInvestProjectid(arr::get($form,'project_id'));
          if($res != false){
          self::redirect("/company/member/project/viewProInvestment?project_id=".arr::get($form,'project_id'));
          }
          }
          $content->forms = $form; */
    }

    /**
     * 招商项目添加(提交审核项目)
     * @author 曹怀栋
     */
    public function action_addProPublish() {
        $content = View::factory("user/company/project/addpropublish");
        $this->content->rightcontent = $content;
        $forms = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        //判断是否是本用户和项目id是否存在
        $res = $service->getOneProject(arr::get($forms, 'project_id'), $this->userId());
        if ($res == false) {
            self::redirect("/company/member/project/addproject");
        }
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            $service->updateProjectTime($form['project_id']);
            //更新模版类型
            $result = $service->updateProjectPublish($form);
            if ($result == true) {
                //弹出提交审核提示框
                $session = Session::instance();
                $session->set("addProject1", 'addProject');
                self::redirect("/company/member/project/showproject");
            }
        }

        //读取当前project_id的数据
        if (arr::get($forms, 'project_id')) {
            $result = $service->getProject(arr::get($forms, 'project_id'));
            $forms['project_template'] = $result['project_template'];
        } else {
            $forms['project_template'] = '';
        }
        $content->forms = $forms;
    }

    /**
     * 发布项目
     * @author施磊
     */
    public function action_submitProject() {
        $forms = Arr::map("HTML::chars", $this->request->query());
        $project_id = intval($forms['project_id']);
        $this->_submitProjectComm($project_id);
    }

    private function _submitProjectComm($project_id) {
        $service = new Service_User_Company_Project();
        $result = $service->submitProject($project_id);
        if ($result == true) {
            //弹出提交审核提示框
            $session = Session::instance();
            $session->set("addProject1", 'addProject');
            self::redirect("/company/member/project/showproject");
        }
    }

    /**
     * 招商项目编辑
     * @author 曹怀栋
     */
    public function action_updateProject() {
        if ($this->request->method() == HTTP_Request::POST) {
            $content = View::factory("user/company/project/updatproject");
            $this->content->rightcontent = $content;
            $service = new Service_User_Company_Project();
            $form = Arr::map("HTML::chars", $this->request->post());
            #判断是不是当前的项目名称是不是一样
           if($form['project_brand_name'] != $form['old_name']){
                   $bool = $service->changeProjectName($this->userId(), $form['project_brand_name']);
                   #若果存在直接跳转
                   if($bool == true){
                      self::redirect("/company/member/project/updateproject?project_id=".$form['project_id']."&type=2&project_brand_name=".$form['project_brand_name']);
                      exit;
                   }
           }
           $project_brand_birthplace = explode(" ",$form['project_brand_birthplace']);
           if(isset($project_brand_birthplace[0]) && isset($project_brand_birthplace[1])){
               if($project_brand_birthplace[1] == "不限"){
                   $form['project_brand_birthplace'] = $project_brand_birthplace[0];
               }
           }
            #消除老的名称
            unset($form['old_name']);
            $submitType = $form['submitType'];
            unset($form['submitType']);
            //取得企业id
            $com_id = $service->getCompanyId($this->userId());
            //数组改成字符串
            $form = $service->arrayToString($form);
            //验证添加信息是否正确
            $result = $service->addProjectCheck($form);
            if ($result == 1) {//验证信息正确
                /*
                  if(!empty($form['project_logo'])){
                  $ret = common::uploadPic($_FILES, 'project_logo',array('w'=>120, 'h'=>150));
                  //当图片上传失败时停止修改
                  if($ret['error'] !=""){
                  self::redirect("/company/member/project/updateproject?project_id=".arr::get($form, 'project_id'));
                  }
                  $form['project_logo'] = $ret['path'];
                  }else{
                  $form['project_logo'] = $form['project_logo_old'];
                  }
                 * *
                 */
                //取得企业id
                $form['com_id'] = $com_id;

                //更新数据
                $result = $service->updateProject($form, $this->userId());
                if ($result == 1) {
                    if (Arr::get($this->request->query(), "from") == 'home') {
                        self::redirect('company/member');
                        exit();
                    }
                    if ($submitType != 1) {
                        $this->_submitProjectComm(arr::get($form, 'project_id'));
                    } else {
                        self::redirect("/company/member/project/addproimg?project_id=" . arr::get($form, 'project_id'));
                    }
                }
                self::redirect("/company/member/project/updateproject?project_id=" . arr::get($form, 'project_id'));
            } else {//验证信息失败
                //输入错误信息时，直接跳回编辑页
                self::redirect("/company/member/project/updateproject?project_id=" . arr::get($form, 'project_id'));
            }
        } else {
            $get = Arr::map("HTML::chars", $this->request->query());
            $service = new Service_User_Company_Project();
            //读取当前id的数据
            $result = $service->getProject(arr::get($get, 'project_id'));
            #拿取项目宣照片
            $result['project_xuanchuan_logo'] = $service->getXuanChuanPic(arr::get($get, 'project_id'));
            $project_brand_birthplace = $result['project_brand_birthplace'] ? $result['project_brand_birthplace'] :"";
            //品牌发源地
            $arr_data = explode(" ", $result['project_brand_birthplace']);
            if(isset($arr_data[0]) && !empty($arr_data[0])){
               $area2_name =  $service->getAreaName($arr_data[0]);
            }
            $result['project_brand_birthplace'] = $arr_data;
            $result['project_phone'] = explode('+', $result['project_phone']);
            $content = View::factory("user/company/project/updatproject");
            $project_tag = $service->getProjectTagByProjectId(arr::get($get, 'project_id'));
            $this->content->rightcontent = $content;
            //取得行业
            $list_industry = $service->getIndustryNew(arr::get($get, 'project_id'));
            $content->list_industry = $list_industry;
            $content->indList = common::primaryIndustry(0);
            $content->areas2 = isset($area2_name) ? $area2_name : array();
            $area = array('pro_id' => 0);
            $content->areas = common::arrArea($area);
            $result['project_tag'] = ($project_tag) ? implode(' ', $project_tag) : '';
            $content->forms = $result;
            //取得投资人群信息
            $content->tag = $service->findTag();
            $content->project_brand_birthplace = $project_brand_birthplace;
            //取得人脉关系信息
            $content->get_onnection = $service->getProjectConnection($result['project_id']);
            $content->type = isset($get['type']) ? $get['type'] : 1;
            $content->project_name = isset($get['project_brand_name']) ? $get['project_brand_name'] : "";
        }
    }

    /**
     * 项目详细页
     * @author 施磊
     */
    public function action_projectInfo() {
        $get = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        //读取当前id的数据
        $result = $service->getProject(arr::get($get, 'project_id'));
        $result['project_phone'] = explode('+', $result['project_phone']);
        $content = View::factory("user/company/project/projectinfo");
        $project_tag = $service->getProjectTagByProjectId(arr::get($get, 'project_id'));
        $this->content->rightcontent = $content;
        $project_id = arr::get($get, 'project_id');
        $com_id = $service->getCompanyId($this->userId());
        $projectPromission = $service->checkProjectPermission($project_id, $com_id);
        if(!$projectPromission){
            self::redirect("/company/member/project/showproject");
        }
        //取得行业
        $list_industry = $service->getIndustryNew(arr::get($get, 'project_id'));
        //项目行业
        $pro_industry = $service->getProjectindustry(arr::get($get, 'project_id'));
        //招商地区
        $pro_area = $service->getProjectArea($project_id);
        //取得人群
        $group_text = $service->getProjectCrowd($project_id, $result['project_groups_label']);
        if ($group_text == ",") {
            $content->group_text = "";
        } else {
            $content->group_text = $group_text;
        }
        //招商形式
        $projectcomodel = $service->getProjectCoModel($project_id);
        #项目宣传页
        $content->xuanchuan = $service->getXuanChuanPic($project_id);
        $content->call = $result['project_phone'];
        $content->projectcomodel = $projectcomodel;
        $content->pro_area = $pro_area;
        $content->pro_industry = $pro_industry;
        $content->list_industry = $service->getIndustryNew(arr::get($get, 'project_id'));
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area);
        $result['project_tag'] = ($project_tag) ? implode(' ', $project_tag) : '';
        $content->forms = $result;
        //取得投资人群信息
        $content->tag = $service->findTag();
        //取得人脉关系信息
        $content->get_onnection = $service->getProjectConnection($result['project_id']);
    }

    /**
     * 删除单个招商项目
     * @author 曹怀栋
     */
    public function action_deleteProject() {
        $get = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        $result = $service->deleteProject(arr::get($get, 'id'), $this->userId());
        if ($result == 1) {
            $path = parse_url($this->request->referrer());
            $path = $path['path'];
            $array = array('/company/member', '/company/member/basic', '/company/member/basic/index');
            if (in_array($path, $array)) {
                self::redirect('company/member');
                exit();
            }
            self::redirect("/company/member/project/showproject");
        }
    }


    /**
     * 我的招商会
     * @author 潘宗磊
     */
    public function action_myInvestment() {
        $content = View::factory("user/company/project/myinvestment");
        $this->content->rightcontent = $content;

        $get = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        //判断企业信息是否完善
        $com_id = $service->getCompanyId($this->userId());
        $res = $service->getInvestment($com_id,arr::get($get, 'project_id'));
        if ($res['count'] == 0 || $com_id == false) {
            $content = View::factory("user/company/project/noinvest");
            $this->content->rightcontent = $content;
            $num = ORM::factory('Project')->where('com_id','=',$com_id)->where('project_status','>',0)->where('project_status','<',4)->count_all();
            $content->num=$num;
        }else{
            $content->now = time();
            $content->invest=$res['list'];
            $content->page=$res['page'];
            $content->page_num = arr::get($get, 'page');
        }
    }
    /**
     * 投资考察会的详情页
     * @author 嵇烨
     */
    public  function  action_myInvestDetails(){
        $form = Arr::map("HTML::chars", $this->request->query());
        $content = View::factory("user/company/project/myinvestdetails");
        $invest_id =  arr::get($form, 'invest_id');
        $service = new Service_User_Company_Project();

        $result = $service->searchViewLog($form);

        $this->content->rightcontent = $content;
        $content->form=$form;
        $content->invest_id= $invest_id;
        $content->list= $service->getViewLogResaultList($result['list']);
        $content->page= $result['page'];
    }
    /**
     * 当前招商会报名列表
     * @author 潘宗磊
     */
    public function action_myApplyInvest() {
        $content = View::factory("user/company/project/myapplyinvest");
        $this->content->rightcontent = $content;
        $service = new Service_User_Company_Project();
        //判断企业信息是否完善
        $com_id = $service->getCompanyId($this->userId());
        $invest_id = $this->request->query('invest_id');
        $per = $service->checkInvestPermission($invest_id, $com_id);
        if(!$per){
            self::redirect("/company/member/project/myinvestment");
        }

        $account = new Service_Account();
        $account_test = $account->useAccount($this->userId(),5,0,'查看投资者');

        $applyNum = ORM::factory('Applyinvest')->where('invest_id','=',$invest_id)->count_all();
        $invest = ORM::factory("Projectinvest",$invest_id);
        $res = $service->getApplyinvest($invest->investment_id);
        $content->invest_model = $invest;
        $content->applyNum = $applyNum;
        $content->account_test = $account_test;
        $content->invest = $res['list'];
        $content->bobao = $res['bobao'];
        $content->page = $res['page'];
    }

    /**
     * 当前招商会报名列表导出
     * @author 潘宗磊
     */
    public function action_downloadApplyXls() {
        $service = new Service_User_Company_Project();
        //判断企业信息是否完善
        $com_id = $service->getCompanyId($this->userId());
        if (!$com_id) {
            self::redirect("/company/member/basic/company");
        }
        $invest_id = $this->request->query('invest_id');
        $per = $service->checkInvestPermission($invest_id, $com_id);
        if(!$per){
            self::redirect("/company/member/project/myinvestment");
        }
        $service->downloadApplyXls($invest_id);
    }



    /**
     * 招商项目添加(我的招商会添加)
     * @author 曹怀栋
     */
    public function action_addInvest() {
        $service = new Service_User_Company_Project();
        //判断企业信息是否完善
        $form = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($form, 'project_id');
        $type = arr::get($form, 'type');
        $com_id = $service->getCompanyId($this->userId());
        $num = ORM::factory('Project')->where('com_id','=',$com_id)->where('project_status','>',0)->where('project_status','<',4)->count_all();
        $account = new Service_Account();
        $account_test = $account->useAccount($this->userId(),11,0,'发布投资考察会');

        if ($num == 0) {
            $content = View::factory("user/company/project/noinvest");
            $this->content->rightcontent = $content;
            $content->project_id = $project_id;
            $content->num=-1;
        }else{
            $service = new Service_User_Company_Project();
            $content = View::factory("user/company/project/investinfor");
            $this->content->rightcontent = $content;
            $form = Arr::map("HTML::chars", $this->request->query());
                //$money = $service_account->getAccountTotalRecharge($user_id);
                $investCount = 0;
                #是否是招商同会员
                $seruser=new Service_User_Company_User();
                //是否已开通招通服务
                $is_invest_status = $seruser->isPlatformServiceFee($com_id);
                $msg = '';
                $investBCount = $is_invest_status ? 3 : 1;
            //判断是否是本用户和项目id是否存在
            if(arr::get($form, 'project_id')!=""){
                $res = $service->getOneProject(arr::get($form, 'project_id'), $this->userId());
                //$resault = $service->getInvestProjectid($form['project_id']);
                    $investCount = $service->getInvertByProjectId($form['project_id']);
                    $jian =  ($investBCount-count($investCount)) > 0 ? ($investBCount-count($investCount)) : 0;
                    if($jian) {
                        $msg = '<span class="tishi21">还可以免费发布<font style="color:red; font-size: 12px;">'.$jian.'场</font>投资考察会</span>';
                    }else{
                        $msg = '<span class="tishi21">免费发布场数已经用完，想免费发布更多，详看<a href="/company/member/account/platformAccountAbout" style="color:blue;">充值及服务</a></span>';
                    }

                //存在我的招商会时
                if ($investCount) {
                    if(arr::get($form, 'investment_id')!=""){
                        $investment_id = arr::get($form, 'investment_id');
                        $invests = ORM::factory('Projectinvest')->where("investment_id", "=", $investment_id)->find()->as_array();

                        $invest_bak = ORM::factory("Investbakup",$investment_id);
                        if($invest_bak->invest_id){
                            $invests = unserialize($invest_bak->content);
                        }
                    }
                    else{
                        $invests = ORM::factory('Projectinvest')->where("project_id", "=", $form['project_id'])->order_by('investment_addtime','DESC')->find()->as_array();
                    }

                    $form['investment_name'] = $invests['investment_name'];
                    $form['investment_logo'] = URL::imgurl($invests['investment_logo']);
                    $form['com_name'] = $invests['com_name'];
                    $form['com_phone'] = str_replace('+', '-', $invests['com_phone']);
                    $form['investment_address'] = $invests['investment_address'];
                    $form['investment_details'] = $invests['investment_details'];
                    $form['investment_agenda'] = $invests['investment_agenda'];
                    $form['investment_preferential'] = $invests['investment_preferential'];
                    $form['putup_type'] = $invests['putup_type'];

                    if(!empty($invests['telephone'])){//获取座机号码
                        $telephone=explode('+', $invests['telephone']);
                        if(!empty($telephone[1])){//判断分机号码是否为空
                            $form ['telephone'] = $telephone[0];
                            $form ['extension'] = $telephone[1];
                        }else{
                            $form ['telephone'] = $telephone[0];
                            $form ['extension'] = "";
                        }
                    }else{
                        $form ['telephone'] = "";
                        $form ['extension'] = "";
                    }


                    $content->invests = $invests;
                    $invest = new Service_User_Person_Invest();

                    $city[$invests['investment_province']] = $invest->getArea($invests['investment_province']);

                    $content->city = $city;
                } else {//表示还没有发布我的招商会
                    $content->invest = false;
                    $com = ORM::factory ( 'Companyinfo', $com_id );
                    $form ['com_name'] = $com->com_contact;
                    $form ['com_phone'] = $this->userInfo()->mobile;

                    if(!empty($com->com_phone)){//获取座机号码
                        $telephone=explode('+', $com->com_phone);
                        if(!empty($telephone[1])){//判断分机号码是否为空
                            $form ['telephone'] = $telephone[0];
                            $form ['extension'] = $telephone[1];
                        }else{
                            $form ['telephone'] = $telephone[0];
                            $form ['extension'] = "";
                        }
                    }else{
                        $form ['telephone'] = "";
                        $form ['extension'] = "";
                    }
                }
                $project_status = ORM::factory("Project", $form['project_id'])->project_status;
            }
            $com_id = $service->getCompanyId($this->userId());
            $projects = ORM::factory("Project")->where('com_id', '=', $com_id)->where('project_status', '=', 2)->find_all();
            $content->projects = $projects;
            $invest = new Service_User_Person_Invest();
            $content->area = $invest->getArea();
            $content->project_status = isset($project_status)?$project_status:1;
            $content->forms = $form;
            $content->investCount = count($investCount);
            $content->investBCount = $investBCount;
            $content->is_invest_status = $is_invest_status;
            $content->msg = $msg;
            $content->account_test = $account_test;
            $content->type = $type;

            //接受url传过来的investment_id @花文刚
            if(isset($form['investment_id']) && $type==2){
                $content->investment_id = $form['investment_id'];
            }
            else{
                $content->investment_id = 0;
            }

        }
    }

    /**
     * 招商项目添加(我的海报添加)
     * @author 潘宗磊
     */
    public function action_addPoster(){ 
        $form = Arr::map("HTML::chars", $this->request->query());
        $redis = Cache::instance("redis");
        $type = arr::get($form, 'type',0);
        $show = arr::get($form, 'show',0);
        $arr_project_haibao = array();
        //判断是否是本用户和项目id是否存在
        $service = new Service_User_Company_Project();
        $res = $service->getOneProject(arr::get($form, 'project_id'), $this->userId());
        if ($res == false) {
            self::redirect("/company/member/project/addproject");
        }
        $content = View::factory("user/company/project/addposter2");
        $this->content->rightcontent = $content;
        $project_info = $service->getProjectInfo(arr::get($form, 'project_id'));
        /*if($project_info['project_status'] == 2){
        	$json_project_haibao = $redis->get(arr::get($form, 'project_id')."_project_haibao");
			if($json_project_haibao){
				$poster = array();
				$arr_project_haibao = (array)json_decode($json_project_haibao);
				$poster['project_id'] = arr::get($form, 'project_id');
				$poster['poster_type'] = 2;
				$poster['poster_status'] = 1;
				$poster['poster_unpass_reason'] = "";
			}else{
				$poster = $service->getProjectPoster(arr::get($form, 'project_id'));
				$service->updateProjectTime(arr::get($form, 'project_id'));
				if($poster->project_id>0&&$poster->poster_type==2){
					$content->poster = $poster;
					$content->posterimg = ORM::factory("ProjectposterContent",$poster->project_id)->upload_img;
				}
				//判断外采海报是否存在
				$posterModel = new Service_Platform_Poster();
				$ispage = $posterModel->isCollectPoster($project_info['outside_id']);
				if($poster->poster_type==1&&$project_info['project_status']==2&&$ispage==true){
					$poster->poster_status=2;
					$content->poster = $poster;
				}
			}
        }else{*/
        	
        	$poster = $service->getProjectPoster(arr::get($form, 'project_id'));
        	$service->updateProjectTime(arr::get($form, 'project_id'));
        	if($poster->project_id>0&&$poster->poster_type==2){
        		$content->poster = $poster;
        		$content->posterimg = ORM::factory("ProjectposterContent",$poster->project_id)->upload_img;
        	}
        	//判断外采海报是否存在
        	$posterModel = new Service_Platform_Poster();
        	$ispage = $posterModel->isCollectPoster($project_info['outside_id']);
        	if($poster->poster_type==1&&$project_info['project_status']==2&&$ispage==true){
        		$poster->poster_status=2;
        		$content->poster = $poster;
        	}
        	
        	$poster = $service->getProjectPoster(arr::get($form, 'project_id'));
        	$service->updateProjectTime(arr::get($form, 'project_id'));
        	if($poster->project_id>0&&$poster->poster_type==2){
        		$content->poster = $poster;
        		$content->posterimg = ORM::factory("ProjectposterContent",$poster->project_id)->upload_img;
        	}
        	//判断外采海报是否存在
        	$posterModel = new Service_Platform_Poster();
        	$ispage = $posterModel->isCollectPoster($project_info['outside_id']);
        	if($poster->poster_type==1&&$project_info['project_status']==2&&$ispage==true){
        		$poster->poster_status=2;
        		$content->poster = $poster;
        	}
        //}
       // echo "<pre>"; print_r($poster->as_array());exit;
        //$project_info = $service->getProjectInfo(arr::get($form, 'project_id'));
		
		if(!is_array($poster)){
			$poster = $poster->as_array();
		}
		if($project_info['project_status'] == 2 && $poster['poster_status'] == 2){
			$int_status = $redis->get(arr::get($form, 'project_id')."_project_haibao_status");
			if($int_status){
				$poster['poster_status'] = $int_status;
			}
		}
		//echo "<pre>"; print_R($poster);exit;
		//echo 33;exit;
        $content->poster = $poster;
        $content->project_info = $project_info;
        $content->status = ORM::factory("Project",arr::get($form, 'project_id'))->project_status;
        $content->forms = $form;
        $content->type = $type;
        $content->show = $show;
    }

    /**
     * 招商项目添加(我的招商会播报)
     * @author 潘宗磊
     */
    public function action_addBobao(){
        $service = new Service_User_Company_Project();
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            $pram = "";
            $page_num = (int)arr::get($form, 'page_num');
            if($page_num>0){
                $pram = "?page=".$page_num;
            }
            $result = $service->addBobao($form);
            if($result){
                self::redirect('/company/member/project/myinvestment'.$pram);
            }
        }

        $invest_id = $this->request->query('invest_id');
        $page_num = $this->request->query('page_num');
        $bobao = $service->getBobao($invest_id);

        if(ORM::factory("Bobao",$invest_id)->invest_id){
            if($bobao['bobao_status'] == 1 || $bobao['bobao_status'] == 2){
                $content = View::factory("user/company/project/viewbobao");
            }
            else{
                $content = View::factory("user/company/project/addbobao");
                $content->invest_id = $invest_id;
                $content->page_num = $page_num;
            }
            $this->content->rightcontent = $content;
            $content->bobao = $bobao;
        }
        else{
            $content = View::factory("user/company/project/addbobao");
            $this->content->rightcontent = $content;
            $content->bobao = $bobao;
            $content->invest_id = $invest_id;
            $content->page_num = $page_num;
        }

    }

    /**
     * 发布现场图片
     * @author 潘宗磊
     */
    public function action_addBobaoImg(){
        $service = new Service_User_Company_Project();
        if ($this->request->method() == HTTP_Request::POST) {
            $form = Arr::map("HTML::chars", $this->request->post());
            $img = "";
            foreach ($form['bobao_img'] as $v){
                $img .= common::getImgUrl($v).'|';
            }
            $form['bobao_img'] = $img;
            $result = $service->addBobaoImg($form);
            if($result){
                self::redirect('/company/member/project/myinvestment');
            }
        }

        $get= Arr::map("HTML::chars", $this->request->query());
        $invest_id= Arr::get($get, 'invest_id','0');
        $img = ORM::factory("Bobao",$invest_id)->bobao_img;
        $bobao_img = explode('|',$img);
        $bobao_img_show = array();
        foreach($bobao_img as $img){
            if($img){
                $bobao_img_show[] = URL::imgurl($img);
            }
        }
        $num = count($bobao_img_show);

        //允许上传的最大图片数 @花文刚
        $img_allow = 15;
        //还能上传的图片数 @花文刚
        $img_left = $img_allow - $num;

        $content = View::factory("user/company/project/addbobaoimg");
        $content->invest_id = $invest_id;
        $content->bobao_img = $bobao_img_show;
        $content->img_allow = $img_allow;
        $content->img_left = $img_left;
        $this->content->rightcontent = $content;

    }

    /**
     * 显示项目统计
     *@author许晟玮
     */
    public function action_showProjectPv(){
        $content = View::factory("user/company/project/projectstat");
        $this->content->rightcontent = $content;
        $userid= $this->userId();
        $get= Arr::map("HTML::chars", $this->request->query());
        $projectid= Arr::get($get, 'project_id','0');
        if( $projectid=='0' ){
            //error
            self::redirect('/company/member/project/showproject');
        }else{
            //用户 项目ID 匹配
            $project_service= new Service_Platform_Project();
            $projectinfo= $project_service->getProjectInfoByIDAll($projectid);

            $pro_name= $projectinfo->project_brand_name;

            $content->pro_name = $pro_name;
            $content->projectid= $projectid;

            $begin= Arr::get($get, 'begin');
            $end= Arr::get($get, 'end');
            $stat_service= new Service_Api_Stat();
            //pv
            $stat_project_pv= $stat_service->getVisitPv( '1',$projectid,$begin,$end );
            $code= $stat_project_pv['code'];
            $result_pro= array();
            $result_web= array();
            if( $code=='200' ){
                $data= $stat_project_pv['data'];

                if( !empty($data) ){
                    foreach ( $data as $k=>$v ){
                        $result_pro[$k]['date']= $v['date'];
                        $result_pro[$k]['pv']= $v['pv'];
                        $result_web[$k]['date']= $v['date'];
                        $result_web[$k]['compv']= $v['compv'];
                    }
                }else{
                    //data is null
                }
            }else{
                //error
            }

            $content->begin= $begin;
            $content->end= $end;
            $content->result= json_encode($result_pro);
            $content->result_web= json_encode($result_web);;

        }

    }
    //end function

    /**
     * 显示项目详情
     *@author 郁政
     */
    public function action_showProjectDetail(){
        $content = View::factory("user/company/project/showProjectDetail");
        $this->content->rightcontent = $content;
        $redis = Cache::instance ('redis');
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = Arr::get($get, 'project_id');
        $service = new Service_User_Company_Project();
        $result = $service->getProject($project_id);
        $arr_project_basic_list = array();
        $touzi_name = "";
        $arr_project_images = array();
        $arr_project_tuiguang_list = array();
        $arr_project_zizhi_images = array();
        $arr_project_haibao = array();
        $arr_project_more_jiben_list = array();
        $arr_project_content_list = array();
        if($result['project_status'] == 2){
        	#查找项目基本信息
        	$json_project_basic_list = $redis->get($project_id."_project_basic_list");
        	#存在缓存
        	if($json_project_basic_list){
        		$arr_project_basic_list = (array)json_decode($json_project_basic_list);
        		#项目名称
        		$result['project_brand_name'] = $arr_project_basic_list['project_brand_name'];
        		#项目logo
        		$result['project_logo'] = $arr_project_basic_list['project_logo'];
        		#成立时间
        		$result['projcet_founding_time'] = $arr_project_basic_list['projcet_founding_time'];
        		#投资金额
        		$result['project_amount_type'] = $arr_project_basic_list['project_amount_type'];
        		#加盟费用
        		$result['need_money'] = $arr_project_basic_list['need_money'];
        		#适合人群
        		//var_dump($arr_project_basic_list ['Investment_groups']);exit;
        		$group_text = $service->getProjectCrowd($project_id,$arr_project_basic_list ['Investment_groups']);
        		//echo $group_text;exit;
        		#查找招商地区 province_id
        		$str_are = "";
        		foreach ($arr_project_basic_list['project_city'] as $key=>$val){
        			$str_are .= $service->getAreaNameByAreaId($val)." ";
        		}
        		$pro_area = $str_are;
        		#品牌发源地
        		$city_parent_name = $service->getAreaNameByAreaId(isset($arr_project_basic_list['province_id']) ? $arr_project_basic_list['province_id'] :"");
        		$city_child_name = $service->getAreaNameByAreaId(isset($arr_project_basic_list['per_area_id']) ? $arr_project_basic_list['per_area_id'] : "");
        		if($city_parent_name && $city_child_name){
	        		if($city_child_name == '国外'){
	            		$result['project_brand_birthplace'] = $city_child_name;
	            	}elseif($city_parent_name == $city_child_name){
        				$result['project_brand_birthplace'] = $city_parent_name;
        			}else{
        				$result['project_brand_birthplace'] = $city_parent_name." ".$city_child_name;
        			}
        		}
        		#项目行业 getIndustryNameByIndustryId
				$str_industry_name = $service->getIndustryNameByIndustryId($arr_project_basic_list['industry_id1']);
				$str_industry_name .= " ".$service->getIndustryNameByIndustryId($arr_project_basic_list['industry_id2']);
				$pro_industry = $str_industry_name;
				#招商形式
				$projectcomodel =  $arr_project_basic_list['project_co_model']; 
        	}else{
        		//招商地区
        		$pro_area = $service->getProjectArea($project_id);
        		//招商形式
        		$projectcomodel = $service->getProjectCoModel($project_id);
        		//取得人群
        		$group_text = $service->getProjectCrowd($project_id, $result['project_groups_label']);
        		//项目行业
        		$pro_industry = $service->getProjectindustry($project_id);
        	}
        	#拿取缓存推广信息
        	$json_project_tuiguang_list = $redis->get($project_id."_project_tuiguang_list");
        	#存在缓存
        	if($json_project_tuiguang_list){
        		$arr_project_tuiguang_list = (array)json_decode($json_project_tuiguang_list);
        		#项目推广语
        		$result['project_advert'] = $arr_project_tuiguang_list['project_advert'];
        		#项目大图
        		$bigImg = $arr_project_tuiguang_list['project_xuanchuan_da_logo'] ? URL::imgurl($arr_project_tuiguang_list['project_xuanchuan_da_logo']) : "";
        		#项目小图
        		$smallImg = $arr_project_tuiguang_list['project_xuanchuan_xiao_logo'] ? URL::imgurl($arr_project_tuiguang_list['project_xuanchuan_xiao_logo']) : "";
        		#项目简介
        		$result['project_summary'] = $arr_project_tuiguang_list['project_summary'];
        		#项目标签
        		$tag = explode(" ",$arr_project_tuiguang_list['project_tag']);
        	}else{
	        	$bigImg = '';
	        	$smallImg ='';
	        	$arr_xuanchuan_image = $service->getXuanChuanPic($project_id);
	        	if(is_array($arr_xuanchuan_image) && !empty($arr_xuanchuan_image)){
	        		foreach ($arr_xuanchuan_image as $key=>$val){
	        			if($val['project_type'] == intval(4)){
	        				$bigImg = URL::imgurl($val['project_img']);
	        			}elseif($val['project_type'] == intval(5)){
	        				$smallImg = URL::imgurl($val['project_img']);
	        			}
	        		}
	        	}
	        	//项目标签
	        	$tag = $service->getProjectTagByProjectId($project_id);
        	}
        	#项目产品图片
        	$json_project_images = $redis->get($project_id."_project_images");
        	#存在缓存
        	if($json_project_images){
        		$arr_project_images = (array)json_decode($json_project_images);
        		$productImg=$service->get_Cache_And_Database_Images($project_id,$arr_project_images,1);
        	}else{
        		$productImg = $service->getProjectImag($project_id, 1);
        		$productImg['list'] = $service->Do_arr_list($productImg['list']);
        	}
			#产品资质图片
			$json_project_zizhi_images = $redis->get($project_id."_project_zizhi_images");
			#存在缓存
			if($json_project_zizhi_images){
				$arr_project_zizhi_images = (array)json_decode($json_project_zizhi_images);
				$zizhiImg = $service->get_Cache_And_Database_Images($project_id,$arr_project_zizhi_images,2);
			}else{
				$zizhiImg = $service->getProjectImag($project_id, 2);
				$zizhiImg['list'] = $service->Do_arr_list($zizhiImg['list']);
			}
			#项目基本信息
			$json_project_more_jiben_list = $redis->get($project_id."_project_more_jiben_list");
			if($json_project_more_jiben_list){
				$arr_project_more_jiben_list = (array)json_decode($json_project_more_jiben_list);
				#主营产品
				$result['project_principal_products'] = $arr_project_more_jiben_list['project_principal_products'] ;
				#加盟费
				$result['project_joining_fee'] = $arr_project_more_jiben_list['project_joining_fee'];
				#保证金
				$result['project_security_deposit'] = $arr_project_more_jiben_list['project_security_deposit'];
				#回报率
				$result['rate_return'] = isset($arr_project_more_jiben_list['rate_return']) ? $arr_project_more_jiben_list['rate_return'] : 0 ;
				#人脉关系
				$get_onnection = isset($arr_project_more_jiben_list['connection']) ? $arr_project_more_jiben_list['connection'] :"";
				#产品特点
				$result['product_features'] = $arr_project_more_jiben_list['product_features'];
				#加盟详情
				$result['project_join_conditions'] = $arr_project_more_jiben_list['project_join_conditions'];
				//echo "<pre>"; print_R($arr_project_more_jiben_list);exit;
			}else{
				//取得人脉关系信息
				$get_onnection= $service->getProjectConnection($project_id);
			}
			#项目联系人信息
			$json_project_content_list = $redis->get($project_id."_project_content_list");
			if($json_project_content_list){
				$arr_project_content_list = (array)json_decode($json_project_content_list);
				#联系人
				$result['project_contact_people'] = $arr_project_content_list['project_contact_people'];
				#职位
				$result['project_position'] = $arr_project_content_list['project_position'];
				#手机号码 
				$result['project_handset'] = $arr_project_content_list['project_handset'];
				#公司号码
				$result['project_phone'] = explode('+', $arr_project_content_list['project_phone']);
			}else{
				$result['project_phone'] = explode('+', $result['project_phone']);
			}
			#项目海报
			$json_project_haibao = $redis->get($project_id."_project_haibao");
			$int_project_haibao_status = $redis->get($project_id."_project_haibao_status");
			if($json_project_haibao){
				$arr_project_haibao = (array)json_decode($json_project_haibao);
				$poster['project_id'] = $project_id;
				$poster['poster_status'] = $int_project_haibao_status ? $int_project_haibao_status : 1;
				$posterImg = isset($arr_project_haibao[0]) ? $arr_project_haibao[0]: "";
			}else{
			//取得海报信息
        	$poster = $service->getProjectPoster($project_id);
        	$poster = $poster->as_array();
        	//取得海报图片
        	$posterImg = $service->getPosterContent($project_id);
			}
        }else{
        	$result['project_phone'] = explode('+', $result['project_phone']);
        	//招商地区
        	$pro_area = $service->getProjectArea($project_id);
        	//招商形式
        	$projectcomodel = $service->getProjectCoModel($project_id);
        	//取得人群
        	$group_text = $service->getProjectCrowd($project_id, $result['project_groups_label']);
        	//项目行业
        	$pro_industry = $service->getProjectindustry($project_id);
        	//取得人脉关系信息
        	$get_onnection= $service->getProjectConnection($project_id);
        	//取得项目广告图
        	$arr_xuanchuan_image = $service->getXuanChuanPic($project_id);
        	$bigImg = '';
        	$smallImg ='';
        	if(is_array($arr_xuanchuan_image) && !empty($arr_xuanchuan_image)){
        		foreach ($arr_xuanchuan_image as $key=>$val){
        			if($val['project_type'] == intval(4)){
        				$bigImg = URL::imgurl($val['project_img']);
        			}elseif($val['project_type'] == intval(5)){
        				$smallImg = URL::imgurl($val['project_img']);
        			}
        		}
        	}
        	//取得产品图片信息
        	$productImg = $service->getProjectImag($project_id, 1);
        	$productImg['list'] = $service->Do_arr_list($productImg['list']);
        	//取得资质图片信息
        	$zizhiImg = $service->getProjectImag($project_id, 2);
        	$zizhiImg['list'] = $service->Do_arr_list($zizhiImg['list']);
        	//取得海报信息
        	$poster = $service->getProjectPoster($project_id);
        	$poster = $poster->as_array();
        	//$poster =  $service->Do_arr_list($poster);
        	//echo "<pre>"; print_R($poster);exit;
        	//取得海报图片
        	$posterImg = $service->getPosterContent($project_id);
        	//获取项目标签
        	$tag = $service->getProjectTagByProjectId($project_id);
        	//var_dump($tag); exit;
        }
      //echo "<pre>"; var_dump($productImg); exit;
        if ($group_text == ",") {
            $content->group_text = "";
        } else {
            $content->group_text = $group_text;
        }
        #项目审核状态
        $json_project_stataus = $redis->get($project_id."_project_status");
        if($json_project_stataus){
	        $int_project_status = json_decode($json_project_stataus);
	       	$result['project_status_temp'] = $int_project_status;
        }
        $content->pro_industry = $pro_industry;
        //echo "<pre>";print_r($result);exit;
        $content->result = $result;
        $content->get_onnection = $get_onnection;
        $content->pro_area = $pro_area;
        $content->projectcomodel = $projectcomodel;
        $content->call = $result['project_phone'];
        $content->bigImg = $bigImg;
        $content->smallImg = $smallImg;
        $content->productImg = $productImg['list'];
        $content->productImgCount = $productImg['count'];
        $content->zizhiImg = $zizhiImg['list'];
        $content->zizhiImgCount = $zizhiImg['count'];
        $content->poster = $poster;
        $content->posterImg = $posterImg;
        $content->tag = $tag;
    }
    
    /**
     * 显示修改项目基本信息页
     *@author 郁政
     */
    public function action_editBasicInfo(){
    	#启动缓存
    	$redis = Cache::instance ('redis');
        $content = View::factory("user/company/project/editBasicInfo");
        $this->content->rightcontent = $content;
        $get = Arr::map("HTML::chars", $this->request->query());
        $service = new Service_User_Company_Project();
        $project_id = Arr::get($get, 'project_id');
        #判断修改项目是不是审核通过 如果是审核通过修改去缓存中查找数据
        $int_num = $service->get_project_status($project_id);
        $search = new Service_Platform_Search();
        #去缓存中找数据
        $project_list = null;
        $arr_data = array();
        if($int_num == intval(2)){        	
        	$project_list = $redis->get($project_id."_project_basic_list");
        	if(!empty($project_list)){
        		#处理数据
        		$result = (array)json_decode($project_list);
        		//echo "<pre>"; print_R($result);exit;
        		if(is_array($result)){
        			#特殊处理品牌发源地
        			$city_parent_name = $service->getAreaNameByAreaId(arr::get($result,"province_id"));
        			$city_child_name = $service->getAreaNameByAreaId(isset($result['per_area_id']) ? $result['per_area_id'] : "");
        			//echo $city_parent_name;exit;
        			if($city_parent_name && $city_child_name){
	        			if($city_child_name == '国外'){
		            		$result['project_brand_birthplace'] = $city_child_name;
		            	}elseif($city_parent_name == $city_child_name){
        					$result['project_brand_birthplace'] = $city_parent_name;
        				}else{
        					$result['project_brand_birthplace'] = $city_parent_name." ".$city_child_name;
        				}
        			}
        			$touzi = array();
        			#出来投资人群
        			foreach ($result['Investment_groups'] as $key=>$val){
        				$touzi[] = $service->get_project_investment_groups($val);
        			}
        			$result['project_investment_groups'] = $touzi;
        			
        			$result['project_groups_label'] = isset($result['label']) ? $result['label'] : array() ;
        			$result['area'] = $service->Do_arr_area_list($result['project_city']);
        			#获取项目招商地区
        			
        		}else{
        			$result = $service->getProject($project_id);
        		}
        	}else{
        		#为空的时候就直接查找数据库
        		$result = $service->getProject($project_id);
        	}
        }else{
        	$result = $service->getProject($project_id);
        }
       // echo "<pre>"; print_r($result);exit;
        $content->forms = $result;
        //品牌发源地
        $birthplace = isset($result['project_brand_birthplace']) ? explode(" ", $result['project_brand_birthplace']) : "";
	 	if($birthplace){
	 		$place = array_pop($birthplace);
	 		$place2 = array_shift($birthplace);
	 		$province_id = $service->getAreaIdByAreaName($place2);
	 		$per_area_id = $service->getAreaIdByAreaName($place);
	 	}
        $content->place = isset($place) ? $place : "";
        $content->place2 = isset($place2) ? $place2 : "";
        $content->province_id = isset($province_id) ? $province_id : "";
        $content->per_area_id = isset($per_area_id) ? $per_area_id : "";
        //取得投资人群标签
        $content->tag = $service->findTag();
        //地区
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area);
        //取得行业id和行业名
        if($int_num == 2){
        	if(isset($result['industry_id1']) && isset($result['industry_id2']) && isset($result['project_co_model'])){
        		$content->industry_id1 = $result['industry_id1'];
        		if($result['industry_id1'] != ""){
        			$content->industry_name1 = $service->getIndustryNameByIndustryId($result['industry_id1']);
        		}else{
        			$content->industry_name1 = "";
        		}
        		$industry_id2 = $result['industry_id2'];
        		$content->industry_id2 = $industry_id2;
        		if($result['industry_id2'] != ""){
        			$content->industry_name2 = $service->getIndustryNameByIndustryId($result['industry_id2']);
        		}else{
        			$content->industry_name2 = "";
        		}
        		//招商形式
        		$projectcomodel = $result['project_co_model'];
        	}else{
        		$industry_id1 = $search->_getProjectIndustryId($project_id,2);
        		$content->industry_id1 = $industry_id1;
        		if($industry_id1 != ""){
        			$content->industry_name1 = $service->getIndustryNameByIndustryId($industry_id1);
        		}else{
        			$content->industry_name1 = "";
        		}
        		$industry_id2 = $search->_getProjectIndustryId($project_id,1);
        		$content->industry_id2 = $industry_id2;
        		
        		if($industry_id2 != ""){
        			$content->industry_name2 = $service->getIndustryNameByIndustryId($industry_id2);
        		}else{
        			$content->industry_name2 = "";
        		}
        		$projectcomodel = $service->getProjectCoModel($project_id);
        	}
        	
        }else{
        	$industry_id1 = $search->_getProjectIndustryId($project_id,2);
        	$content->industry_id1 = $industry_id1;
        	if($industry_id1 != ""){
        		$content->industry_name1 = $service->getIndustryNameByIndustryId($industry_id1);
        	}else{
        		$content->industry_name1 = "";
        	}
        	$industry_id2 = $search->_getProjectIndustryId($project_id,1);
        	$content->industry_id2 = $industry_id2;
        	 
        	if($industry_id2 != ""){
        		$content->industry_name2 = $service->getIndustryNameByIndustryId($industry_id2);
        	}else{
        		$content->industry_name2 = "";
        	}
        	$projectcomodel = $service->getProjectCoModel($project_id);
        }
        $content->projectcomodel = $projectcomodel;
    }
    /**
     * 修改项目基本信息
     *@author 郁政
     */
    public function action_updateBasicInfo(){
    	#启动缓存
    	$redis = Cache::instance ('redis');
        $form = Arr::map("HTML::chars", $this->request->post());
        $project_id = Arr::get($form, 'project_id');
        $service = new Service_User_Company_Project();
        #判断这个项目是不是审核通过的项目
        $int_num = $service ->get_project_status($project_id);
        //echo "<pre>"; print_r($form);exit;
        #处理数据
        if($int_num == intval(2)){
        	$service->updateProjectTempStatus($project_id);
        	#存放数据开始  已json_encode 格式存放
        	$redis->set($project_id."_project_basic_list", json_encode($form),"2592000");
        	$redis->set($project_id."_project_basic_list_status",2,"2592000");
        	$service->delect_redis_status($project_id);
        	$service->updateProjectByParam($project_id, array("project_reason"=>''));
        	#修改的数量
        	$redis->get($project_id."_project_cout");
        }else{
        	$date = array();
        	if($int_num ==3){
        		$date['project_status'] = 1;
        	}
        	$date['project_brand_name'] = Arr::get($form, 'project_brand_name');
        	$date['project_logo'] = Arr::get($form, 'project_logo');
        	#处理一下品牌发源地
        	$city_parent_name = $service->getAreaNameByAreaId(isset($form['province_id']) ? $form['province_id'] :"");
        	$city_child_name = $service->getAreaNameByAreaId(isset($form['per_area_id']) ? $form['per_area_id'] : "");
        	if($city_parent_name && $city_child_name){
        		if($city_child_name == '国外'){
            		$date['project_brand_birthplace'] = $city_child_name;
            	}elseif($city_parent_name == $city_child_name){
        			$date['project_brand_birthplace'] = $city_parent_name;
        		}else{
        			$date['project_brand_birthplace'] = $city_parent_name." ".$city_child_name;
        		}
        	}
        	$date['projcet_founding_time'] = strtotime(Arr::get($form, 'projcet_founding_time').'0101');
        	$date['project_amount_type'] = Arr::get($form, 'project_amount_type');
        	$date['project_groups_label'] = serialize(Arr::get($form, 'label'));
        	#拼音处理
        	$date['project_pinyin'] = pinyin::getinitial(arr::get($form,"project_brand_name"));
        	$service->updateProjectByParam($project_id, $date);
        	$ind = array();
        	$ind[] = $form['industry_id1'];
        	$ind[] = $form['industry_id2'];
        	//所属行业
        	$service->updateProjectIndustry($project_id, $ind);
        	//招商地区
        	$service->updateProjectArea($project_id, $form['project_city']);
        	//招商形式
        	$service->updateProjectModel($project_id, $form['project_co_model']);
        	//适合人群
				$service->updateProjectCrowd($project_id, arr::get($form,"Investment_groups",array()));
        	
        }
        $service->updateProjectTime($project_id);
        $session = Session::instance();
        $session->set('showProId', $project_id);
        $session->set('showProMsg', '<font>恭喜您，基本信息修改成功,</font><span>审核通过后，投资者就可在您的项目官网查看到。</span>');
        self::redirect("/company/member/project/showproject");
    }
    /**
     * 显示更多项目详情页
     *@author 郁政
     */
    public function action_editMoreInfo(){
        $content = View::factory("user/company/project/editMoreInfo");
        $redis = Cache::instance ('redis');
        $this->content->rightcontent = $content;
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = Arr::get($get, 'project_id');
        $type = Arr::get($get, 'type');
        $service = new Service_User_Company_Project();
        $result = $service->getProject($project_id);
        $arr_project_more_jiben_list = array();
        #判断是不是审核通过的项目
        if($result['project_status'] == 2){
        	$json_project_more_jiben_list = $redis->get($project_id."_project_more_jiben_list");
        	if($json_project_more_jiben_list){
        		$arr_project_more_jiben_list = (array)json_decode($json_project_more_jiben_list);
        		if($arr_project_more_jiben_list){
        			$result['project_principal_products'] = isset($arr_project_more_jiben_list['project_principal_products']) ? $arr_project_more_jiben_list['project_principal_products'] : "";
        			$result['project_joining_fee'] = isset($arr_project_more_jiben_list['project_joining_fee']) ? $arr_project_more_jiben_list['project_joining_fee'] : "";
        			$result['project_security_deposit'] = isset($arr_project_more_jiben_list['project_security_deposit']) ? $arr_project_more_jiben_list['project_security_deposit'] : "";
        			//$result['need_money'] = isset($arr_project_more_jiben_list['project_principal_products']) ? $arr_project_more_jiben_list['need_money'] : "";
        			$result['rate_return'] = isset($arr_project_more_jiben_list['rate_return']) ? $arr_project_more_jiben_list['rate_return'] : "";
        			//$result['connection'] = isset($arr_project_more_jiben_list['connection']) ? $arr_project_more_jiben_list['connection'] : "";
        			$result['product_features'] = isset($arr_project_more_jiben_list['product_features']) ? $arr_project_more_jiben_list['product_features'] : "";
        			$result['project_join_conditions'] = isset($arr_project_more_jiben_list['project_join_conditions']) ? $arr_project_more_jiben_list['project_join_conditions'] : "";
        		}
        	}else{
        		$result = $service->getProject($project_id);
        	}
        }else{
        	$result = $service->getProject($project_id);
        }
        $people_onnection = array();
        $content->forms = $result;
        if($result['project_status'] == 2){
        	$people_onnection = isset($arr_project_more_jiben_list['connection']) ? $arr_project_more_jiben_list['connection'] : "";
        }else{
        	$people_onnection = $service->getProjectConnection($project_id);
        }
        //取得人脉关系信息
        $content->get_onnection = $people_onnection;
        $content->type = $type;
    }

    /**
     * 修改更多详情
     *@author 郁政
     */
    public function action_updateMoreInfo(){
    	$redis = Cache::instance("redis");
        $form = Arr::map("HTML::chars", $this->request->post());
        $project_id = Arr::get($form, 'project_id');
        $type = Arr::get($form, 'type');
        $service = new Service_User_Company_Project();
        #检查项目是不是审核通过
        $int_project_status = $service->get_project_status($project_id);
        if($int_project_status == 2){
        	$service->updateProjectTempStatus($project_id);
        	$redis->set($project_id."_project_more_jiben_list",json_encode($form),'2592000');
        	$service->delect_redis_status($project_id);
        	$service->updateProjectByParam($project_id, array("project_reason"=>''));
        	$redis->set($project_id."_project_more_jiben_list_status",2,'2592000');
        }else{
        	$date = array();
        	if($int_project_status == 3){
        		$date['project_status'] = 1;
        	}
        	$date['project_principal_products'] = Arr::get($form, 'project_principal_products');
        	$date['project_joining_fee'] = Arr::get($form, 'project_joining_fee');
        	$date['project_security_deposit'] = Arr::get($form, 'project_security_deposit');
        	$date['rate_return'] = Arr::get($form, 'rate_return');
        	$date['product_features'] = Arr::get($form, 'product_features');
        	$date['project_join_conditions'] = Arr::get($form, 'project_join_conditions');
        	$service->updateProjectByParam($project_id, $date);
        	//添加人脉关系
        	$service->updateProjectConnection($project_id,Arr::get($form, 'connection'));
        }
        $service->updateProjectTime($project_id);
        $session = Session::instance();
        if($type == 1) {
            $msg = '添加';
        }elseif($type == 2){
            $msg = '完善';
        }else{
            $msg = '修改';
        }
        $session->set('showProId', $project_id);
        $session->set('showProMsg', "<font>恭喜您，更多详情信息{$msg}成功,</font><span>审核通过后，投资者就可在您的项目官网查看到。</span>");
        self::redirect("/company/member/project/showproject");
    }

    /**
     * 更新海报后传信息跳转
     *@author 郁政
     */
    public function action_updatePoster(){
        $form = Arr::map("HTML::chars", $this->request->query());
        $project_id = Arr::get($form, 'project_id');
        $type = (arr::get($form, 'type') == 1) ? '上传' : '修改';
        $session = Session::instance();
        $session->set('showProId', $project_id);
        $session->set('showProMsg', '<font>恭喜您，项目宣传海报已经'.$type.'成功</font>我们将在3个工作日内完成审核，审核通过后，投资者就可在您的项目官网查看到。');
        self::redirect("/company/member/project/showproject");
    }
    /**
     * 获取项目-收到的名片
     *@author 赵路生
     */
    public function action_getProjectCard(){
        $content = View::factory("user/company/project/projectcard");
        $this->content->rightcontent = $content;
        $userid= $this->userId();
        //获取数据
        $get= Arr::map("HTML::chars", $this->request->query());
        $project_id = intval(arr::get($get,'project_id',0));
        $person_conds['start_time'] = arr::get($get,'start_time','');
        $person_conds['end_time'] = arr::get($get,'end_time','');
        $person_conds['invester_name'] = arr::get($get,'invester_name','');
        $time = time();

        if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/", $person_conds['start_time'], $m)) {
            $person_conds['start_time'] = mktime(0,0,0,$m[2],$m[3],$m[1]);
        }else{
            $person_conds['start_time'] = 0;//如果没有给起始时间，0开始
        }
        if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/", $person_conds['end_time'], $m)) {
            $person_conds['end_time'] = mktime(23,59,59,$m[2],$m[3],$m[1]);
        }else{
            $person_conds['end_time'] = $time;//如果没有给结束时间,当前时间为结束时间
        }
        //添加开始

        $service_Investor=new Service_Platform_SearchInvestor();
        //找到id企业用户对应的信息
        $cominfo=ORM::factory('Companyinfo')->where('com_user_id','=',$userid)->find();
        $comid=$cominfo->com_id;
        //--现在这里要拿取的是匹配到的个人用户id

        $com_service = new Service_User_Company_Project();
        //通过项目id--找到个人用户信息
        //default_id = 6851 for test
        $person_ids  = $com_service->getCardInfoByProID($project_id,$person_conds);

        //返回个人信息
        $per_service = new Service_User_Person_User();
        $per_result=$per_service->getPersonInfoNew($person_ids,$userid);

        //如果没有收到名片
        $notice = '';
        if(empty($per_result['resultlist'])){
            if($person_conds['start_time']!=0 || $person_conds['end_time']!=$time){
                if($person_conds['invester_name']){
                    $notice = "该时间段内没有收到包含'<span style='color:red;'>".$person_conds['invester_name']."</span>'向该项目递送的名片！";
                }else{
                    $notice = "该时间段内没有投资者向该项目递送的名片！";
                }
            }else{
                if($person_conds['invester_name']){
                    $notice = "暂时还没有收到包含'<span style='color:red;'>".$person_conds['invester_name']."</span>'向该项目递送的名片！";
                }else{
                    $notice = "该项目暂时还没有收到投资者递送的名片！";
                }
            }
        }

        $content->page = $per_result['page'];
        $content->per_info = $per_result['resultlist'];
        $content->notice = $notice;
        $content->get = $get;
    }
    
    /**
     * 显示告示
     * @author 党中央
     */
    public function action_showGaoShi(){
    	$content = View::factory("user/company/project/showGaoshi");
    	$this->content->rightcontent = $content;
    }
}
