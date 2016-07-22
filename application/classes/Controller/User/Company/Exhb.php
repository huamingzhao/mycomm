<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 网络展会
 * @author stone shi
 *
 */
class Controller_User_Company_Exhb extends Controller_User_Company_Template {
    
    public function before(){
        parent::before();
        $forms = Arr::map("HTML::chars", $this->request->query());
        $post = Arr::map("HTML::chars", $this->request->post());

    	if(isset($forms['project_id']) && $forms['project_id'] && !arr::get($forms, 'without', 0)) {
            $project_id = intval($forms['project_id']);
            $service = new Service_User_Company_Project();
            $com_id = $service->getCompanyId($this->userId());
            $service = new Service_User_Company_Exhb();
            $projectPromission = $service->checkProjectPermission($com_id, $project_id);
            if(!$projectPromission){
                self::redirect("/company/member/exhb/projectList");
            }
        }
        if(isset($post['project_id']) && $post['project_id'] && !arr::get($post, 'without', 0)) {
            $project_id = intval($post['project_id']);
            $service = new Service_User_Company_Project();
            $com_id = $service->getCompanyId($this->userId());
            $service = new Service_User_Company_Exhb();
            $projectPromission = $service->checkProjectPermission($com_id, $project_id);
            if(!$projectPromission){
                self::redirect("/company/member/exhb/projectList");
            }
        }
    }
    
    /**
     * 网络展会参展项目
     * @author stone shi
     */
    public function action_projectList() {
        #实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
        
        #加载模版
        $content = View::factory("user/company/exhb/projectlist");
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
        #企业信息
        $checkStatus = $servicePro->checkComInfo($com_id, $this->userId());
        
        #加载弹出框
        $session = Session::instance();
        $showMsg = 0;
        $showMsg = $session->get("showExhbProMsg");
        //预留前置判断
        #获得项目总数
        $proCount = $servicePro->getEffectiveProjectCount($com_id);
        if(!$proCount) {
            $content = View::factory("user/company/exhb/projectErrMsg");
            $content->errMsg = '你还没有发布属于你的平台项目，需要将你的一句话平台（招商）项目加入展会中!';
        }
        
        #获得展会项目总数
        $exhbProCount = $service->getExhbProCount($com_id);
        if(!$exhbProCount) {
            $content = View::factory("user/company/exhb/projectErrMsg");
            $content->errMsg = '你还没有发布属于你的展会项目，需要将你的一句话平台（招商）项目加入展会中!';
        }
        
        $exhbProList =$service->getExhbProjectList($com_id);
        $this->content->rightcontent = $content;
        $content->exhbProList = $exhbProList;
        $content->showMsg = $showMsg;        
        $showMsg ? $session->delete("showExhbProMsg") : '';
    }
    
    /**
     * 网络展会参展项目添加
     * @author 郁政
     */
    public function action_addProject() {
    	$content = View::factory("user/company/exhb/addproject");
    	$this->content->rightcontent = $content;
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
    	if ($this->request->method() == HTTP_Request::POST) {        	
        	$form = Arr::map("HTML::chars", $this->request->post());
        	$exhb_id = Arr::get($form, 'exhibition_id',0);       	   	
        	#处理项目行业
            $form['project_industry_id'] = array();
            $form['project_industry_id'][] = isset($form['industry_id1'])?$form['industry_id1'] :"";
            $form['project_industry_id'][] = isset($form['industry_id2'])?$form['industry_id2'] :"";
            //echo "<pre>";print_r(explode(',', isset($form['project_img_url'])?$form['project_img_url'] :""));exit;
            
            #处理产品展示图片
            $form['project_img_display'] = array();                       
            $str1 = isset($form['project_img_url']) ? $form['project_img_url'] : "";
            $form['project_img_url'] = explode(',',$str1);            
            foreach($form['project_img_url'] as $k => $v){
            	$form['project_img_display'][$k]['project_img_url'] = $v;
            }            
            $str2 = isset($form['project_imgname']) ? $form['project_imgname'] : "";
            $form['project_imgname'] = explode(',', $str2);
        	foreach($form['project_imgname'] as $k => $v){
            	$form['project_img_display'][$k]['project_imgname'] = $v;
            }            
            $str3 = isset($form['project_describe']) ? $form['project_describe'] : "";
            $form['project_describe'] = explode(',', $str3);
        	foreach($form['project_describe'] as $k => $v){
            	$form['project_img_display'][$k]['project_describe'] = $v;
            }
            //echo "<pre>";print_r($form);exit;
        	$res = $service->addProject($form);
        	$session = Session::instance();
        	if($res){
        		$msg = "<font>恭喜您，项目发布成功！</font>我们将在一个工作日内完成审核，审核通过3小时后，投资者通过搜索，进入项目官网即可查看相关详情。";
        		$session->set('showExhbProId', intval(123));
        		$session->set('showExhbProMsg', $msg);
        		self::redirect("/company/member/exhb/projectList");
        	}else{
        		self::redirect("/company/member/exhb/addProject");
        	}       	
        	
        }  
        #取得网络展会id
        $get = Arr::map("HTML::chars", $this->request->query());
        $exhb_id = Arr::get($get, 'exhibition_id',0);
    	$exhb_info = $service->exhbInfoById($exhb_id);
      	if(!(isset($exhb_info['exhibition_id']) && $exhb_info['exhibition_id'])){
       		self::redirect("/company/member/exhb/projectList");
      	}
        $content->exhb_id = $exhb_id;        
        #取得项目分类信息
        $content->catalog_info = $service->getCatalogIdByExhibitionId($exhb_id);
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
        $content->com_id = $com_id;
        #企业信息
        $checkStatus = $servicePro->checkComInfo($com_id, $this->userId());
        #获取企业在一句话平台发布的项目
        $yijuhuaPro = $service->getYiJuHuaProjectByCom($com_id);
        $content->yijuhuaPro = $yijuhuaPro;      
        #获取历史展会参展项目
        $historyPro = $service->getHistoryExhbProByCom($com_id);        
        $content->historyPro = $historyPro;        
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area);   
        #取得项目id和type
        $project_id = Arr::get($get, 'project_id',0);        
    	$type = Arr::get($get, 'type',1);
    	   
    	$service = new Service_User_Company_Exhb(); 		
    	$exbhBasic = $service->getExhbProjectBasic($project_id,$type);
    	$exhbTuiGuangInfo = $service->getExhbTuiGuangInfo($project_id,$type);
    	$exhbMore = $service->getExhbProjectMore($project_id,$type);
    	$exhbOtherInfo = $service->getExhbProjectOtherInfo($project_id,$com_id,$type);
    	$list = array_merge($exbhBasic,$exhbTuiGuangInfo,$exhbMore,$exhbOtherInfo);    		
    	//echo "<pre>";print_r($list);exit;
    	$content->project_id = $project_id;    	     	
    	$content->forms = $list; 
    	$content->type = $type;
    }
    
    /**
     * 网络展会参展项目详情页
     * @author 郁政
     */
   public function action_showExhbProDetail(){
   		$content = View::factory("user/company/exhb/showExhbProDetail");
    	$this->content->rightcontent = $content;    	
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);   
    	$exhb_id = Arr::get($get, 'exhibition_id',0);  	
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();   		
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
   		$isExhPro = $service->isHaveExhPro($project_id, $com_id);
        if(!$isExhPro){
        	self::redirect("/company/member/exhb/projectList");
        }
        //缓存中拿数据
        $proInfo = $service->getProject($project_id);
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $redis = Cache::instance("redis");
        $baseRes = $redis->get($project_id."_exhb_project_base");
        $arr_base = array();
        if($status == 1 && $baseRes){
        	$arr_base = (array)json_decode($baseRes);
        	//取得招商形式
	    	$arr_base['project_model'] = $arr_base['project_co_model'];
	    	//取得招商地区
	    	$arr_base['area'] = $service->Do_arr_area_list($arr_base['project_city']); 
	    	//取得行业	
	    	$arr_base['pro_industry']['industry_id1'] = $arr_base['industry_id1'];
	    	$arr_base['pro_industry']['industry_id2'] = $arr_base['industry_id2'];
	    	$industry_id = isset($arr_base['industry_id2']) ? $arr_base['industry_id2'] : $arr_base['industry_id1'];
	    	$arr_base['pro_industry']['industry_name'] = $service->getIndustryNameByIndustryId($industry_id); 
        	$exbhBasic = $arr_base;
        }else{
        	$exbhBasic = $service->getExhbProjectBasic($project_id,2);
        }
   		$tuiguangRes = $redis->get($project_id."_exhb_project_tuiguang");
        $arr_tuiguang = array();
        if($status == 1 && $tuiguangRes){
        	$arr_tuiguang = (array)json_decode($tuiguangRes);
        	$arr_tuiguang['coupon_deadline'] = strtotime($arr_tuiguang['coupon_deadline']);
        	$exhbTuiGuangInfo = $arr_tuiguang;       	        	
        }else{
        	$exhbTuiGuangInfo = $service->getExhbTuiGuangInfo($project_id,2);
        }
   		$moreRes = $redis->get($project_id."_exhb_project_more");
        $arr_more = array();
        if($status == 1 && $moreRes){
        	$arr_more = (array)json_decode($moreRes);
        	#处理产品展示图片
	       	$arr_more['project_zhanshi'] = array();  
        	$str1 = isset($arr_more['project_img_url']) ? $arr_more['project_img_url'] : "";
	      	$arr_more['project_img_url'] = explode(',',$str1);            
	       	foreach($arr_more['project_img_url'] as $k => $v){
	      		$arr_more['project_zhanshi'][$k]['project_zhanshi_pic'] = $v;
	     	}            
	   		$str2 = isset($arr_more['project_imgname']) ? $arr_more['project_imgname'] : "";
	      	$arr_more['project_imgname'] = explode(',', $str2);
	       	foreach($arr_more['project_imgname'] as $k => $v){
	       		$arr_more['project_zhanshi'][$k]['project_zhanshi_pic_name'] = $v;
	   		}            
	      	$str3 = isset($arr_more['project_describe']) ? $arr_more['project_describe'] : "";
	       	$arr_more['project_describe'] = explode(',', $str3);
	       	foreach($arr_more['project_describe'] as $k => $v){
	       		$arr_more['project_zhanshi'][$k]['project_zhanshi_shuoming'] = $v;
	      	} 
	      	$exhbMore = $arr_more;
        }else{
        	$exhbMore = $service->getExhbProjectMore($project_id,2);
        }
   		$otherRes = $redis->get($project_id."_exhb_project_other");
        $arr_other = array();
        if($status == 1 && $otherRes){
        	$arr_other = (array)json_decode($otherRes);
        	$exhbOtherInfo = $arr_other;
        }else{
        	$exhbOtherInfo = $service->getExhbProjectOtherInfo($project_id,$com_id,2);
        }
	    $list = array_merge($exbhBasic,$exhbTuiGuangInfo,$exhbMore,$exhbOtherInfo); 
	    $res = $service->showExhbProDetail($list);
        //echo "<pre>";print_r($res);exit;	
    	$content->exhb_id = $exhb_id;
    	$content->forms = $res;
   }
   
    /**
     * 网络展会参展项目基本信息页
     * @author 郁政
     */
	public function action_showExhbBasic(){
    	$content = View::factory("user/company/exhb/showExhbBasic");
    	$this->content->rightcontent = $content; 
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);    	  
    	$exhb_id = Arr::get($get, 'exhibition_id',0); 		
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project(); 
        $exhb_info = $service->exhbInfoById($exhb_id);
      	if(!(isset($exhb_info['exhibition_id']) && $exhb_info['exhibition_id'])){
       		self::redirect("/company/member/exhb/projectList");
      	} 
      	$proInfo = $service->getProject($project_id);
      	$catalog_id = isset($proInfo['catalog_id']) ? $proInfo['catalog_id'] : 0;
      	$content->catalog_id = $catalog_id;
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
        $isExhPro = $service->isHaveExhPro($project_id, $com_id);
        if(!$isExhPro){
        	self::redirect("/company/member/exhb/projectList");
        }
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $redis = Cache::instance("redis");
        $baseRes = $redis->get($project_id."_exhb_project_base");
        if($status == 1 && $baseRes){
        	$res = (array)json_decode($baseRes); 	
        	//取得招商形式
	    	$res['project_model'] = $res['project_co_model'];
        	//取得招商地区
	    	$res['area'] = $service->Do_arr_area_list($res['project_city']); 
	    	//取得行业	
	    	$res['pro_industry']['industry_id1'] = $res['industry_id1'];
	    	$res['pro_industry']['industry_id2'] = $res['industry_id2'];
	    	$industry_id = isset($res['industry_id2']) ? $res['industry_id2'] : $res['industry_id1'];
	    	$res['pro_industry']['industry_name'] = $service->getIndustryNameByIndustryId($industry_id);	    	 
        }else{
        	$res = $service->getExhbProjectBasic($project_id,2); 
        }    	
    	//echo "<pre>";print_r($res);exit;   	
    	$content->forms = $res;    	
        $content->project_id = $project_id;     
        $content->exhb_id = $exhb_id;	
        $content->status = $status;
        #取得项目分类信息
        $content->catalog_info = $service->getCatalogIdByExhibitionId($exhb_id);
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area); 
	}
	
	/**
     * 网络展会参展修改项目基本信息页
     * @author 郁政
     */
	public function action_editExhbBasic(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$exhb_id = Arr::get($form, 'exhb_id');
		$status = Arr::get($form,"status");
		#实例化
        $service = new Service_User_Company_Exhb();
		$redis = Cache::instance("redis");
		if($status == 1){
			$redis->set($project_id."_exhb_project_base_status",json_encode(1),'2592000');
			$redis->set($project_id."_exhb_project_base",json_encode($form),'2592000');
			//修改临时字段
			$res = $service->updateTempStatus($project_id);
		}else{
			#处理项目行业
	        $form['project_industry_id'] = array();
	       	$form['project_industry_id'][] = isset($form['industry_id1'])?$form['industry_id1'] :"";
	     	$form['project_industry_id'][] = isset($form['industry_id2'])?$form['industry_id2'] :"";
	        //echo "<pre>";print_r($form);exit;       
			$res = $service->editExhbBasic($form);
		}       
		if($res){
			self::redirect('/company/member/exhb/showExhbProDetail?exhibition_id='.$exhb_id.'&project_id='.$project_id);
		}
	}
	
	
	/**
     * 网络展会参展项目推广信息页
     * @author 郁政
     */
	public function action_showExhbTuiGuang(){
		$content = View::factory("user/company/exhb/showExhbTuiGuang");
    	$this->content->rightcontent = $content; 
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);
    	$exhb_id = Arr::get($get, 'exhibition_id',0);
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
		$isExhPro = $service->isHaveExhPro($project_id, $com_id);
        if(!$isExhPro){
        	self::redirect("/company/member/exhb/projectList");
        }
        $proInfo = $service->getProject($project_id);
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $redis = Cache::instance("redis");
        $tuiguangRes = $redis->get($project_id."_exhb_project_tuiguang");
        if($status == 1 && $tuiguangRes){
        	$res = (array)json_decode($tuiguangRes);
        	$res['coupon_deadline'] = strtotime($res['coupon_deadline']);
        }else{
        	$res = $service->getExhbTuiGuangInfo($project_id,2);
        }    	
    	//echo "<pre>";print_r($res);exit;
    	$content->forms = $res;
    	$content->project_id = $project_id;
    	$content->exhb_id = $exhb_id;	
    	$proInfo = $service->getProject($project_id);
    	$content->status = $status;
	}
	
	
	/**
     * 网络展会参展项目修改推广信息页
     * @author 郁政
     */
	public function action_editExhbTuiGuang(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$exhb_id = Arr::get($form, 'exhb_id');
		$status = Arr::get($form,"status");
		#实例化
        $service = new Service_User_Company_Exhb();
		$redis = Cache::instance("redis");
		if($status == 1){
			$redis->set($project_id."_exhb_project_tuiguang_status",json_encode(1),'2592000');
			$redis->set($project_id."_exhb_project_tuiguang",json_encode($form),'2592000');
			//修改临时字段
			$res = $service->updateTempStatus($project_id);
		}else{
			//echo "<pre>";print_r($form);exit;    
	        $res = $service->editExhbTuiGuang($form);
		}        
		if($res){
			self::redirect('/company/member/exhb/showExhbProDetail?exhibition_id='.$exhb_id.'&project_id='.$project_id);
		}
	}
	
	/**
     * 网络展会参展项目更多信息页
     * @author 郁政
     */
	public function action_showExhbMore(){
		$content = View::factory("user/company/exhb/showExhbMore");
    	$this->content->rightcontent = $content; 
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);
    	$exhb_id = Arr::get($get, 'exhibition_id',0);
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
		$isExhPro = $service->isHaveExhPro($project_id, $com_id);
        if(!$isExhPro){
        	self::redirect("/company/member/exhb/projectList");
        }
        $proInfo = $service->getProject($project_id);
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $redis = Cache::instance("redis");
        $moreRes = $redis->get($project_id."_exhb_project_more");
        if($status == 1 && $moreRes){
        	$res = (array)json_decode($moreRes);
        	#处理产品展示图片
	       	$res['project_zhanshi'] = array();  
        	$str1 = isset($res['project_img_url']) ? $res['project_img_url'] : "";
	      	$res['project_img_url'] = explode(',',$str1);            
	       	foreach($res['project_img_url'] as $k => $v){
	      		$res['project_zhanshi'][$k]['project_zhanshi_pic'] = $v;
	     	}            
	   		$str2 = isset($res['project_imgname']) ? $res['project_imgname'] : "";
	      	$res['project_imgname'] = explode(',', $str2);
	       	foreach($res['project_imgname'] as $k => $v){
	       		$res['project_zhanshi'][$k]['project_zhanshi_pic_name'] = $v;
	   		}            
	      	$str3 = isset($res['project_describe']) ? $res['project_describe'] : "";
	       	$res['project_describe'] = explode(',', $str3);
	       	foreach($res['project_describe'] as $k => $v){
	       		$res['project_zhanshi'][$k]['project_zhanshi_shuoming'] = $v;
	      	}        	
        }else{
        	$res = $service->getExhbProjectMore($project_id,2); 
        } 
    	//echo "<pre>";print_r($res);exit;   	
    	$content->forms = $res;
    	$content->project_id = $project_id;
    	$content->exhb_id = $exhb_id;
    	$content->status = $status;
	}
	
	/**
     * 网络展会参展项目修改更多信息页
     * @author 郁政
     */
	public function action_editExhbMore(){
		$form = Arr::map("HTML::chars", $this->request->post());
		//echo "<pre>"; print_R($form);exit;
		$project_id =  Arr::get($form, 'project_id');
		$exhb_id = Arr::get($form, 'exhb_id');
		$status = Arr::get($form,"status");
		$project_is_have_upload_video_status = arr::get($form,"project_status");
		if(isset($from['project_status'])){unset($from['project_status']);}
		#实例化
        $service = new Service_User_Company_Exhb();
		$redis = Cache::instance("redis");
		if($status == 1){
			$redis->set($project_id."_exhb_project_more_status",json_encode(1),'2592000');
			$redis->set($project_id."_exhb_project_more",json_encode($form),'2592000');
			if($project_is_have_upload_video_status == 10){
				$redis->set($project_id."_project_is_have_upload_video",true,'2592000');
				//echo 22;exit;
			}
			//修改临时字段
			$res = $service->updateTempStatus($project_id);
		}else{
			#处理产品展示图片
	       	$form['project_img_display'] = array();                       
	    	$str1 = isset($form['project_img_url']) ? $form['project_img_url'] : "";
	      	$form['project_img_url'] = explode(',',$str1);            
	       	foreach($form['project_img_url'] as $k => $v){
	      		$form['project_img_display'][$k]['project_img_url'] = $v;
	     	}            
	   		$str2 = isset($form['project_imgname']) ? $form['project_imgname'] : "";
	      	$form['project_imgname'] = explode(',', $str2);
	       	foreach($form['project_imgname'] as $k => $v){
	       		$form['project_img_display'][$k]['project_imgname'] = $v;
	   		}            
	      	$str3 = isset($form['project_describe']) ? $form['project_describe'] : "";
	       	$form['project_describe'] = explode(',', $str3);
	       	foreach($form['project_describe'] as $k => $v){
	       		$form['project_img_display'][$k]['project_describe'] = $v;
	      	}
			//echo "<pre>";print_r($form);exit;    
      		$res = $service->editExhbMore($form);
		}     	
		if($res){
			self::redirect('/company/member/exhb/showExhbProDetail?exhibition_id='.$exhb_id.'&project_id='.$project_id);
		}
	}
	
	/**
     * 网络展会参展项目其他信息页
     * @author 郁政
     */
	public function action_showExhbOther(){
		$content = View::factory("user/company/exhb/showExhbOther");
    	$this->content->rightcontent = $content; 
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);
    	$exhb_id = Arr::get($get, 'exhibition_id',0);
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
		$isExhPro = $service->isHaveExhPro($project_id, $com_id);
        if(!$isExhPro){
        	self::redirect("/company/member/exhb/projectList");
        }
        $proInfo = $service->getProject($project_id);
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $redis = Cache::instance("redis");
        $otherRes = $redis->get($project_id."_exhb_project_other");
        if($status == 1 && $otherRes){
        	$res = (array)json_decode($otherRes);        	
        }else{
        	$res = $service->getExhbProjectOtherInfo($project_id,$com_id,2);
        }        
        //echo "<pre>";print_r($res);exit;
        $content->forms = $res;
        $content->project_id = $project_id;
        $content->exhb_id = $exhb_id;
    	$content->status = $status;
	}
	
	/**
     * 网络展会参展项目修改其他信息页
     * @author 郁政
     */
	public function action_editExhbOther(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$exhb_id = Arr::get($form, 'exhb_id');
		$status = Arr::get($form,"status");
		#实例化
        $service = new Service_User_Company_Exhb();
		$redis = Cache::instance("redis");
		if($status == 1){
			$redis->set($project_id."_exhb_project_other_status",json_encode(1),'2592000');
			$redis->set($project_id."_exhb_project_other",json_encode($form),'2592000');
			//修改临时字段
			$res = $service->updateTempStatus($project_id);
		}else{
			//echo "<pre>";print_r($form);exit;  
			$res = $service->editExhbOther($form);
		}       
		if($res){
			self::redirect('/company/member/exhb/showExhbProDetail?exhibition_id='.$exhb_id.'&project_id='.$project_id);
		}
	}
	
	/**
     * 意向加盟页
     * @author 郁政
     */
	public function action_showYiXiangList(){
		$content = View::factory("user/company/exhb/showyixianglist");
    	$this->content->rightcontent = $content; 
    	#实例化
        $service = new Service_User_Company_Exhb();
        $servicePro = new Service_User_Company_Project();
        #取得企业id
        $com_id = $servicePro->getCompanyId($this->userId());
        if($this->request->method() == HTTP_Request::GET) {  
        	$get = Arr::map("HTML::chars", $this->request->query());
        	$type = Arr::get($get, 'type',1);
        	$name = Arr::get($get, 'name','');
        	$status = Arr::get($get, 'status',0);
        	if($type == 1 && $name == '' && $status == 0){
        		$cond = array();
        	}else{
        		$cond = array(	      
        			'type' => $type,  		
	        		'name' => $name,
	        		'status' => $status
	        	);
        	}         	
        	$res = $service->getYiXiangList($com_id,$cond,$this->userId());   
        	//echo "<pre>";print_r($res);exit;    	
        }
        $content->forms = $res['list'];
        $content->page = $res['page']; 
        $content->type = $type;
        $content->name = $name;
        $content->status = $status;       
	}
	
	/**
	 * 申请在线沟通
	 * @author 党员
	 */
	public function  action_ApplyForCommunication(){
		$Service_User_Company_Project = new Service_User_Company_Project();
		$service = new Service_User_Company_Exhb();
		$get = Arr::map("HTML::chars", $this->request->query());
		//没有
		$int_is_hava_exnb_project = 1;
		#获取用户id
		$int_user_id = $this->userId();
		#获取企业id
		$int_com_id = $Service_User_Company_Project->getCompanyId(intval($int_user_id));
		if(empty($int_user_id) || $int_com_id == false){ self::redirect("/company/member/basic/editcompany?type=1");};
		#判断有没有参展项目
		$count = $service->getExhbProjectCount($int_com_id);
		if($count > 0){
			self::redirect("/company/member/exhb/showCommunication?com_id=".$int_com_id);
		}else{
			//提示页面
			$content = View::factory("user/company/exhb/havenoexhbproject");
		}
		$this->content->rightcontent = $content;
	}
	/**
	 * 执行添加客服
	 * @author jiye
	 */
	public function action_AddCommunication(){
		$post = Arr::map("HTML::chars", $this->request->post());
		$int_com_id = arr::get($post,"com_id");
		$service = new Service_User_Company_Exhb();
		$session = Session::instance();
		if($int_com_id){
			$bool = $service->AddCommunication($post);
			if($bool == true){
				$session->set('showCommunication',true);
			}
			self::redirect("/company/member/exhb/showCommunication?type=1&com_id=".$int_com_id);
		}
	}
	
	/**
	 * 获取客服信息
	 * @author jiye
	 */
	public function action_showCommunication(){
		$get = Arr::map("HTML::chars", $this->request->query());
		$session = Session::instance();
		$bool = $session->get("showCommunication") ? $session->get("showCommunication") : false;
		$session->set('showCommunication',"");
		$type=arr::get($get, "type",'0');
		$Service_User_Company_Project = new Service_User_Company_Project();
		$service = new Service_User_Company_Exhb();
		$com_id = arr::get($get, "com_id");
		#获取用户id
		$int_user_id = $this->userId();
		#获取企业id
		$int_com_id = $Service_User_Company_Project->getCompanyId(intval($int_user_id));	
 		if($com_id != $int_com_id){
 			self::redirect("/company/member/basic/editcompany?type=1");
 		}
 		//获取客服组信息
		$arr_data_Customer = $service->getCommunication($int_com_id);
		//echo "<pre>"; print_r($arr_data_Customer);exit;
		//获取客服信息
		$arr_data = $service->gettablelist("Customerinfo",array("content"=>"customer_id","contentfiled"=>arr::get($arr_data_Customer, "customer_id")));
		//echo "<pre>"; print_R($arr_data_Customer);exit;
		$int_status = 0;
		if(empty($arr_data_Customer) || arr::get($arr_data_Customer,"customer_id")== ""){ 
			//没有任何客服页面添加页面
			$content = View::factory("user/company/exhb/showaddcommunication");
		}else{
			if($type == 0){
				if(arr::get($arr_data_Customer, "customer_status") == 3){
					//没有通过
					$content = View::factory("user/company/exhb/showcommunicationnopass");
				}elseif(arr::get($arr_data_Customer, "customer_status") == 2){
					//通过
					$content = View::factory("user/company/exhb/showcommunicationpass");
				}elseif(arr::get($arr_data_Customer, "customer_status") == 1){
					//进入人工审核页面
					$content = View::factory("user/company/exhb/showcommunicationpeople");
				}
			}else{
				//有客服页面
				$content = View::factory("user/company/exhb/showcommunication");
			}
		}
		$this->content->rightcontent = $content;
		$content->com_id = $int_com_id;
		$content->bool = $bool;
		$content->arr_data = $arr_data;
		$content->isUpdateCommunication = $session->get("isUpdateCommunication");
		$content->arr_data_Customer = $arr_data_Customer;
	}
	
	/**
	 * 修改客服
	 */
	public function action_UpdateCommunication(){
		$get = Arr::map("HTML::chars", $this->request->query());
		$Service_User_Company_Project = new Service_User_Company_Project();
		$content = View::factory("user/company/exhb/updatecommunication");
		$this->content->rightcontent = $content;
		$service = new Service_User_Company_Exhb();
		
		$redis = Cache::instance ('redis');
		$com_id = arr::get($get, "com_id");
		$exhb_customer_id = arr::get($get, "exhb_customer_id");
		$session = Session::instance();
		#获取用户id
		$int_user_id = $this->userId();
		#获取企业id
		$int_com_id = $Service_User_Company_Project->getCompanyId(intval($int_user_id));
		//查找客服 是不是这个企业的
		$int_count = $service->isComCommunication($com_id);
		if($com_id !=$int_com_id || $int_count == 0){
			self::redirect("/company/member");
		}
		#获取单个客服信息
		$arr_data  = $service->getOneCommunication(arr::get($get,"exhb_customer_id"));
		$arr_data_Customer = $service->getCommunication($int_com_id);
		#判断是不是在一个月之内的	
		if(arr::get($arr_data_Customer, "customer_status") == 2 && arr::get($arr_data_Customer, "customer_passtime")){
			$pass_date = date("Y-m-d H:i:s",arr::get($arr_data_Customer, "customer_passtime"));
			$date30day =  date("Y-m-d H:i:s",strtotime("$pass_date   +30  day"));   //日期天数相加函数
			$now_time = date("Y-m-d H:i:s",time());
			$update_num = $redis->get(arr::get($get,"exhb_customer_id")."Communication");
			if($update_num >= 2 && $now_time < $date30day){
				$session->set("showCommunication",true);
				self::redirect("/company/member/exhb/showCommunication?type=1&com_id=".$int_com_id);
			}
		}
		$content->com_id = $com_id;
		$content->arr_data = $arr_data;
		$content->kefu = arr::get($get,"kefu");
		
	}
	
	/**
	 * 执行修改
	 * @author jiye
	 */
	public function action_DoUpdateCommunication(){
		$post = Arr::map("HTML::chars", $this->request->post());
		$redis = Cache::instance ('redis');
 		if($post){
			$session = Session::instance();
			$service = new Service_User_Company_Exhb();
			$arr_data  = $service->getKeFuZu(arr::get($post,"customer_id"));
			$int_status= arr::get($arr_data, "customer_status");
			$bool = $service->UpdateOneCommunication($post);
			if($bool == true){
				$session->set('showCommunication',true);
				if($int_status == 2){
					//echo 22;exit;
					$update_num = $redis->get(arr::get($post,"customer_info_id")."Communication") ? $redis->get(arr::get($post,"customer_info_id")."Communication") : 0 ;
					$redis->set(arr::get($post,"customer_info_id")."Communication",intval($update_num)+intval(1),864000);
				}
			}
			self::redirect("/company/member/exhb/showCommunication?type=1&com_id=".arr::get($post, "com_id"));
		}
	}
	
	/**
	 * 删除客服
	 * @author jiye
	 */
	public function action_DeleteCommunication(){
		$get = Arr::map("HTML::chars", $this->request->query());
		//echo "<pre>"; print_r($get);exit;
		$Service_User_Company_Project = new Service_User_Company_Project();
		$service = new Service_User_Company_Exhb();
		$com_id = arr::get($get, "com_id");
		$customer_id = arr::get($get, "customer_id");
		$customer_info_id = arr::get($get, "customer_info_id");
		#获取用户id
		$int_user_id = $this->userId();
		#获取企业id
		$int_com_id = $Service_User_Company_Project->getCompanyId(intval($int_user_id));
		//查找客服 是不是这个企业的
		$int_count = $service->isComCommunication($com_id);
		if($com_id !=$int_com_id || $int_count == 0){
			self::redirect("/company/member");
		}
		//删除客服
		$bool = $service->DeleteCommunication($customer_id,$customer_info_id);
		if($bool == true){
			self::redirect("/company/member/exhb/showCommunication?type=1&com_id=".$com_id);
		}
	}
        
     /**
     * 显示项目统计
     *
     */
    public function action_showProjectPv(){
        $content = View::factory("user/company/exhb/projectstat");
        $this->content->rightcontent = $content;
        $userid= $this->userId();
        $get= Arr::map("HTML::chars", $this->request->query());
        $projectid= Arr::get($get, 'project_id','0');
        if( $projectid=='0' ){
            //error
            self::redirect('/company/member/exhb/projectList');
        }else{
            //用户 项目ID 匹配
            $service = new Service_User_Company_Exhb();
            $projectinfo = $service->getProjectBasicInfo($projectid);

            $pro_name= arr::get($projectinfo, 'project_brand_name', '');

            $content->pro_name = $pro_name;
            $content->projectid= $projectid;

            $begin= Arr::get($get, 'begin');
            $end= Arr::get($get, 'end');

            $content->begin= $begin;
            $content->end= $end;

        }

    }
    
}
