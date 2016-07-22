<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ajax
 * @author 曹怀栋
 *
 */
class Controller_User_Company_Ajaxcheck extends Controller{

    /**
     * 删除招商项目中的指定的一张图片
     * @author 曹怀栋
     */
    public function action_deleteProjectImg(){
    	$redis = Cache::instance("redis");
        $get = Arr::map("HTML::chars", $this->request->query());
        $delete_type = isset($get['redis_type']) ? $get['redis_type'] :"";
        $arr_data_images = array();
        $arr_new_data_images = array();
       
        $redis_type_name = "";
        $redis_type_delete = isset($get['redis_type_delete']) ? $get['redis_type_delete'] :"";
        $result = false;

        #判断是删除数据库 还是删除缓存
        if($delete_type !=""){
        	#判断是资质图片还是产品图片
        	if($redis_type_delete){
        		$redis_type_name = arr::get($get, 'project_id')."_project_zizhi_images";
        	}else{
        		$redis_type_name = arr::get($get, 'project_id')."_project_images";
        	}
        	#拿取缓存图片
        	$arr_data_images = $redis->get($redis_type_name);
        	if($arr_data_images){ 
        		$arr_new_data_images = (array)json_decode($arr_data_images);
        	}
        	if($arr_new_data_images){
        		foreach ($arr_new_data_images as $key=>$val){
        			if($key == $delete_type){
        				unset($arr_new_data_images[$key]);
        				$result = true;
        			}
        		}
        	}
        	$redis->set($redis_type_name, json_encode($arr_new_data_images));
        }else{
        	$service = new Service_User_Company_Project();
        	$result = $service->deleteProjectImages(arr::get($get,'project_id'),$this->userId(),arr::get($get,'id'));
        	$service->updateProjectTime(arr::get($get,'project_id'));
        }
        if($result == 1){
            if(arr::get($get,'project_type') == 1){
                self::redirect("/company/member/project/addproimg?project_id=".arr::get($get,'project_id'));
            }else{
                self::redirect("/company/member/project/addprocertsimg?project_id=".arr::get($get,'project_id'));
            }
        }
    }

    /**
     * ajax读取投资人群信息
     * @author 曹怀栋
     */
    public function action_findTag(){
        if($this->request->is_ajax()){
            $msg = array();
            $tag = ORM::factory("Tag")->where('tag_status', '=', 1)->find_all();
            foreach ($tag as $k=>$v){
                $msg[$v->tag_id]['tag_id']=$v->tag_id;
                $msg[$v->tag_id]['tag_name']=$v->tag_name;
            }
            echo json_encode($msg);
        }
    }

    /**
     * 读取指定行业的二级信息
     * @author 曹怀栋
     */
    public function action_getIndustry(){
        if($this->request->is_ajax()){
            $post = Arr::map("HTML::chars", $this->request->post());
            $msg = array();
            if(isset($post['parent_id']) && is_numeric($post['parent_id'])){
            $msgs =common::primaryIndustry($post['parent_id']);
            foreach ($msgs as $k=>$v){
                $msg[$k]['industry_id']=$v->industry_id;
                $msg[$k]['industry_name']=$v->industry_name;
            }
            echo json_encode($msg);
         }else{
                echo json_encode($msg);
         }
        }
    }

    /**
     * 购买名片服务
     * @author 周进
     * @date 2013/01/24
     */
    public function action_cardservice(){
        $this->isLogin();
        $service = new Service_Account();
        $result = array('status'=>FALSE,'msg'=>'','type'=>0);
        $post = Arr::map("HTML::chars", $this->request->post());
        if(Arr::get($post,'type_number')!=""){//购买处理
            if (!isset($post['buy_type_'.$post['type_number']]))
                $result = array('status'=>FALSE,'msg'=>'很抱歉，您购买服务失败，您可重新购买！');
            else{
                $data['service_id'] = $post['buy_type_'.$post['type_number']];
                $result = $service->buyService($data,$this->userId());
                if ($result['status']==FALSE)
                    $result = array('status'=>FALSE,'msg'=>$result['message'],'type'=>0);
                else{
                    $result = array('status'=>TRUE,'msg'=>$result['message'],'type'=>0);
                }
            }
        }
        echo json_encode($result);
    }

    /**
     * 购买招商会服务
     * @author 潘宗磊
     */
    public function action_buyInvestService(){
        $this->isLogin();
        $service = new Service_Account();
        $result = array('status'=>FALSE,'msg'=>'');
        $post = Arr::map("HTML::chars", $this->request->post());
		$status = $post['status'];
		if($status == "0"){
			$account = new Service_Account();
			$result = $account->manageAccount($this->userId(),5);
			if($result['status']==true){
				$apply = ORM::factory('Applyinvest',$post['apply_id']);
				if($apply->apply_id>0){
					$apply->apply_status=2;
					$apply->update();
					$msg = $result['type'].'#'.$apply->apply_mobile;
				}else{
					$msg = '操作失败！';
				}
			}else{
				$msg = $result['type'].'#'.$result['message'];
			}		
		}
		else{
			$apply = ORM::factory('Applyinvest',$post['apply_id']);
				if($apply->apply_id>0){
					$msg = '1'.'#'.$apply->apply_mobile;
				}else{
					$msg = '操作失败！';
				}
		
		}

        echo $msg;
    }

    /**
     * 添加汇款处理
     * @author 周进
     */
    public function action_outLineRecharge(){
        $result = array('status'=>FALSE,'msg'=>'');
        $post = Arr::map("HTML::chars", $this->request->post());
        if (Arr::get($post, 'order_bank_name')=='0'&&Arr::get($post, 'order_bank_name_add')!="")
            $post['order_bank_name'] = Arr::get($post, 'order_bank_name_add');
        if(ceil($post['order_account'])>100000)
            $result = array('status'=>FALSE,'msg'=>"对不起，您的输入金额超出上限，无法提交。");
        else{
            $accountservice = new Service_Account();
            $result = $accountservice->editOutLineRecharge($this->userId(),$post);
            if ($result==FALSE)
                $result = array('status'=>FALSE,'msg'=>"对不起，您的信息填写不完善，无法提交。");
            else{
                $result = array('status'=>TRUE,'msg'=>"您的汇款信息已经添加成功，请耐心等待审核。");
            }
        }
        echo json_encode($result);
    }

    /**
     * 删除单个企业资质认证图片
     * @author 曹怀栋
     */
    public function action_deleteCertification(){
        $post = Arr::map("HTML::chars", $this->request->post());
        $service = Service::factory('User_Company_User');
        $result = $service->deleteCertification(arr::get($post,'id'));
        if($result ==1){
            $result = array('status'=>true,'msg'=>"删除成功!");
        }else{
            $result = array('status'=>false,'msg'=>"删除失败!");
        }
        echo json_encode($result);
    }

    /**
     * 我的招商会信息
     * @author 潘宗磊
     */
    public function action_investinfor() {
        $service = new Service_User_Company_Project();
        $content = View::factory("user/company/project/investinfor");
        $form = Arr::map("HTML::chars", $this->request->query());
        //判断是否是本用户和项目id是否存在
        if(arr::get($form, 'project_id')!=""){
            $res = $service->getOneProject(arr::get($form, 'project_id'), $this->userId());
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
            $project_status = ORM::factory("Project", $form['project_id'])->project_status;
        }
        $com_id = $service->getCompanyId($this->userId());
        $projects = ORM::factory("Project")->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->find_all();
        $content->projects = $projects;
        $invest = new Service_User_Person_Invest();
        $content->area = $invest->getArea();
        $content->project_status = isset($project_status)?$project_status:1;
        $content->forms = $form;
        $this->response->body($content);
    }

    /**
     * 海报图片
     * @author 潘宗磊
     */
    public function action_getPosterImg() {
        $service = new Service_User_Company_Project();
        $content = View::factory("user/company/project/posterimg");
        $form = Arr::map("HTML::chars", $this->request->query());
        //判断是否是本用户和项目id是否存在
        if(arr::get($form, 'project_id')!=""){
            $res = $service->getOneProject(arr::get($form, 'project_id'), $this->userId());
            if ($res == false) {
                self::redirect("/company/member/project/addproject");
            }
        }
        $content->poster = ORM::factory("ProjectposterContent",arr::get($form, 'project_id'));;
        $this->response->body($content);
    }

    /**
     * 上传企业图片
     * @author 施磊
     */
    public function action_uploadComLogo() {
        $post = Arr::map("HTML::chars", $this->request->post());
        if(isset($post['data'])) {
            $mod = new Service_User_Company_User();
            $mod->editComUser($this->userId(), $post['data']);
            $this->jsonEnArr('200', $post['data']);
        }else{
            $this->jsonEnArr('500', '上传图片为空');
        }
    }

    /**
     * 返回ajax状态
     * @author 施磊
     * @param int $code 状态码
     * @param string or array $msg 提示信息
     * @param int $type 0 为 直接echo 1 是return
     * @return json
     */
    private function jsonEnArr($code, $msg, $type = 0) {
        $arr = array('code' => $code, 'msg' => $msg, 'date' => time());
        $return = json_encode($arr);
        if($type) {
            return $return;
        }else{
            echo $return;exit;
        }
    }

    /**
     * 获取名片服务使用的日志记录用于服务详情
     * @author 周进
     * @date 2013/05/13
     */
    public function action_getCardServiceLog(){
        $this->isLogin();
        $service = new Service_Account();
        $post = Arr::map("HTML::chars", $this->request->post());
        if(Arr::get($post,'buy_id')!=""){
            $result = $service->getServiceAccountLog(str_replace("test_","",Arr::get($post,'buy_id')));
        }
        echo json_encode($result);
    }

    /**
     * 获取省份的数据
     * @author许晟玮
     */
    public function action_getAreaInfo(){
        $invest= new Service_User_Person_Invest();
        //获取省份 城市
        $pro= $invest->getArea();
        $all= array('cit_id' => 88,'cit_name' => '全国');
        array_unshift($pro, $all);
        echo json_encode( $pro );
    }
    //end function
    /**
     * 验证项目名称
     * @author 嵇烨
     */
    public function action_changeName(){
        $post = Arr::map("HTML::chars", $this->request->post());
        $user_id = $this->userId();
		$int = 0;
        $server = new Service_User_Company_Project();
        $int = $server->changeProjectName($user_id,$post['project_name']);
        echo json_encode($int);
    }
    /**
     * 检查项目名称是否唯一
     * @author jiye
     * @date 2013/11/1
     */
    public  function action_check_project_name(){
    	$post = Arr::map("HTML::chars", $this->request->post());
    	$server = new Service_User_Company_Project();
    	$return_data = array();
    	$return_data['status'] = $server->check_project_name(arr::get($post, "project_name"));
    	echo json_encode($return_data);
    }
    /**
     * 检查项目推广语是否唯一
     * @author jiye
     * @date 2013/11/1
     */
    public  function action_check_project_advert(){
    	$post = Arr::map("HTML::chars", $this->request->post());
    	$server = new Service_User_Company_Project();
    	$return_data = array();
		$user_id = $this->userId();
    	$return_data  = $server->check_project_advert(arr::get($post, "project_advert"),$user_id);
    	echo json_encode($return_data);
    }
    
    /**
     * 获取用户单个项目发布的文章数量,计算可以项目下，可以免费发布的数量
     *@author 许晟玮
     */
    public function action_getProjuectZixunNum(){
        $user_id= $this->userId();
        if( ceil( $user_id )==0 ){
            $show_msg_type= 'error';
        }else{
            $post= $this->request->post();
            $project_id= Arr::get($post, 'pid',0);
            if( ceil( $project_id )<=0 ){
                $show_msg_type= 'error';
            }else{
                //已经投稿的项目资讯(除了删除的)
                $service_article= new Service_News_Article();
                $result= $service_article->getUserProjectZixunCount( $project_id,$user_id );

                //获取用户的充值总金额
                $account_service= new Service_Account();
                $all_account= $account_service->getAccountTotalRecharge($user_id);

                //获取免费发布文章次数
                $free_tg_zixun= zixun::account_zixun($all_account);
                $can_fabu_num= ceil( $free_tg_zixun-count($result) );
                if( $can_fabu_num<0 ){
                    $can_fabu_num= 0;
                }else{
                }

                $company_service=new Service_User_Company_User();
                $company_result= $company_service->getCompanyInfo($user_id);
                if( !empty( $company_result->com_id ) ){
                    $com_id= $company_result->com_id;
                    //判断用户是否是招商通会员
                    $isplatformservicefee = $company_service->isPlatformServiceFee($com_id);
                    if( $isplatformservicefee===true ){
                        //招商通会员
                        $zst_use= '1';
                    }else{
                        //普通会员
                        $zst_use= '0';
                    }
                }else{
                    //普通会员
                    $zst_use= '0';
                }

                //判断是否用完了免费投稿字数
                if( ceil( $can_fabu_num )<=0 ){
                    //如是普通企业会员，免费投稿次数已经使用完
                    if( $zst_use=='0' ){
                        $show_msg_type= '1';
                    }
                    //如是招商通会员，免费投稿次数已经使用完，且累计充值金额小于20000的
                    if( $zst_use=='1' && ceil($all_account)<20000 ){
                        $show_msg_type= '2';
                    }

                    //如是招商通会员，免费投稿次数已经使用完，且累计充值金额>=20000的
                    if( $zst_use=='1' && ceil($all_account)>=20000 ){
                        $show_msg_type= '3';
                    }
                    $msg= 0;

                }else{
                    $show_msg_type= '0';
                    $msg= $can_fabu_num;
                }


            }
        }
        $return= array();
        $return['msg']= $msg;
        $return['type']= $show_msg_type;

        echo json_encode($return);

    }
    //end function

    /**
     * 获取用户的充值金额 及 可以免费发布的资讯数量生成浮层类型
     * @author许晟玮
     */
    public function action_getUserAllAccountAndFreeTgNum(){
        $user_id= $this->userId();
        if( ceil( $user_id )==0 ){
            $msg= 'error';
        }else{
            $post= $this->request->post();
            $project_id= Arr::get($post, 'pid',0);
            if( ceil($project_id)<=0 ){
                $msg= 'error';
            }else{
                //已经投稿的项目资讯(除了删除的)
                $service_article= new Service_News_Article();
                $result= $service_article->getUserProjectZixunCount( $project_id,$user_id );

                //获取用户的充值总金额
                $account_service= new Service_Account();
                $all_account= $account_service->getAccountTotalRecharge($user_id);

                //获取免费发布文章次数
                $free_tg_zixun= zixun::account_zixun($all_account);
                $can_fabu_num= ceil( $free_tg_zixun-count($result) );
                if( $can_fabu_num<0 ){
                    $can_fabu_num= 0;
                }else{
                }

                $company_service=new Service_User_Company_User();
                $company_result= $company_service->getCompanyInfo($user_id);
                if( !empty( $company_result->com_id ) ){
                    $com_id= $company_result->com_id;
                    //判断用户是否是招商通会员
                    $isplatformservicefee = $company_service->isPlatformServiceFee($com_id);
                    if( $isplatformservicefee===true ){
                        //招商通会员
                        $zst_use= '1';
                    }else{
                        //普通会员
                        $zst_use= '0';
                    }
                }else{
                    //普通会员
                    $zst_use= '0';
                }

                //判断是否用完了免费投稿字数
                if( ceil( $can_fabu_num )<=0 ){
                    //如是普通企业会员，免费投稿次数已经使用完
                    if( $zst_use=='0' ){
                        $show_msg_type= '1';
                    }
                    //如是招商通会员，免费投稿次数已经使用完，且累计充值金额小于20000的
                    if( $zst_use=='1' && ceil($all_account)<20000 ){
                        $show_msg_type= '2';
                    }

                    //如是招商通会员，免费投稿次数已经使用完，且累计充值金额>=20000的
                    if( $zst_use=='1' && ceil($all_account)>=20000 ){
                        $show_msg_type= '3';
                    }

                }else{
                    //没有用完，正常的投稿提示
                    $show_msg_type= '0';
                    //写入资讯数据
                    $service_column= new Service_News_Column();
                    $rs_column= $service_column->getColumnByName('项目新闻');
                    if( empty( $rs_column ) ){
                        $msg= 'column_error';
                    }else{
                        $column_id= ceil( $rs_column['column_id'] );
                        $service_zixun= new Service_News_Zixun();
                        $par= array();
                        $par['article_name']= Arr::get($post, 'article_name');
                        $par['article_tag']= Arr::get($post, 'article_tag');
                        if( $par['article_tag']=='选择文章关键字作为标签，有利于文章搜索推荐。标签之间以逗号隔开。' ){
                            $par['article_tag']= '';
                        }
                        $par['article_content']= Arr::get($post, 'article_content');
                        $par['parent_id']= $column_id;
                        $par['user_id']= $user_id;
                        $par['link_name']= '';
                        $par['link_phone']= '';
                        $par['push_reason']= '';

                        $add_result= $service_zixun->craeteZixun( $par );

                        $add_id= ceil($add_result['result']->article_id);

                        if( $add_id>0 ){
                            //添加项目资讯关联
                            $pinf= array();
                            $pinf['article_id']= $add_id;
                            $pinf['project_id']= $project_id;
                            $pinf['tg_status']= 1;
                            $pinf['tg_recommend']= 1;
                            $service_article->setProjectArticle( $pinf );

                        }else{

                        }
                    }

                }

                $msg= $show_msg_type;
            }


        }
        //$msg= '3';
        echo json_encode( $msg );

    }

    //end function

    /**
     * 写入投稿的草稿
     * @author许晟
     */
    public function action_setArticleSave(){
        $post= $this->request->post();
        $user_id= $this->userId();
        $service_zixun= new Service_News_Zixun();
        $service_column= new Service_News_Column();
        $project_id= Arr::get($post, 'pid',0);
        $service_article= new Service_News_Article();

        $rs_column= $service_column->getColumnByName('项目新闻');
        if( empty( $rs_column ) ){
            $msg= 'column_error';
        }else{
            $column_id= ceil( $rs_column['column_id'] );
            $service_zixun= new Service_News_Zixun();
            $par= array();
            $par['article_name']= Arr::get($post, 'article_name');
            $par['article_tag']= Arr::get($post, 'article_tag');
            if( $par['article_tag']=='选择文章关键字作为标签，有利于文章搜索推荐。标签之间以逗号隔开。' ){
                $par['article_tag']= '';
            }
            $par['article_content']= Arr::get($post, 'article_content');
            $par['parent_id']= $column_id;
            $par['user_id']= $user_id;
            $par['link_name']= '';
            $par['link_phone']= '';
            $par['push_reason']= '';
            $add_result= $service_zixun->craeteZixun( $par );
            $add_id= ceil($add_result['result']->article_id);
            if( $add_id>0 ){
                //添加项目资讯关联
                $pinf= array();
                $pinf['article_id']= $add_id;
                $pinf['project_id']= $project_id;
                $pinf['tg_status']= Arr::get($post, 'tgs',0);
                $pinf['tg_recommend']= Arr::get($post, 'tgr',0);
                $service_article->setProjectArticle( $pinf );

            }else{

            }
        }
    }
    //end function

    /**
     * 扣钱发布文章
     * @author 许晟玮
     */
    public function action_kouqian(){
        $user_id= $this->userId();
        $post= $this->request->post();
        $title= Arr::get($post, 'title');
        $account = new Service_Account();
        $result= $account_result=$account->useAccount($user_id,12,0,'发布'.$title.'软文章');
        if( $result['type']=='-1' ){
            $result= $account_result=$account->useAccount($user_id,12,1,'发布'.$title.'软文章');
            if( $result['type']=='1' ){
                $msg= 'ok';
            }else{
                $msg= $result['message'];
            }
        }else{
            $msg= $result['message'];
        }
        $type= $result['type'];
        $result['msg']= $msg;
        $result['type']= $type;

        echo json_encode( $result );
    }
    //end function

    /**
     * 修改项目文章
     * @author 许晟玮
     */
    public function action_editprojectzixun(){

        $post= $this->request->post();
        $user_id= $this->userId();
        $service_zixun= new Service_News_Zixun();
        $service_column= new Service_News_Column();
        $project_id= Arr::get($post, 'pid',0);
        $service_article= new Service_News_Article();
        $article_id= Arr::get($post, 'article_id',0);
        if( ceil( $article_id )<=0 ){
            $msg= 'error';
        }else{
            $rs_column= $service_column->getColumnByName('项目新闻');
            if( empty( $rs_column ) ){
                $msg= 'column_error';
            }else{
                $column_id= ceil( $rs_column['column_id'] );
                $service_zixun= new Service_News_Zixun();
                $par= array();
                $par['article_name']= Arr::get($post, 'article_name');
                $par['article_tag']= Arr::get($post, 'article_tag');
                if( $par['article_tag']=='选择文章关键字作为标签，有利于文章搜索推荐。标签之间以逗号隔开。' ){
                    $par['article_tag']= '';
                }
                $par['article_content']= Arr::get($post, 'article_content');
                $par['parent_id']= $column_id;
                $par['user_id']= $user_id;
                $par['link_name']= '';
                $par['link_phone']= '';
                $par['push_reason']= '';
                $par['article_id']= $article_id;
                $service_zixun->craeteZixun( $par,2 );

                if( $article_id>0 ){
                    //修改项目资讯关联
                    $rss= $service_article->getProjectZixunRow($article_id);
                    $paid= ceil( $rss['id'] );
                    $pinf= array();
                    $pinf['id']= $paid;
                    $pinf['article_id']= $article_id;
                    $pinf['project_id']= $project_id;
                    $pinf['tg_status']= Arr::get($post, 'tgs',0);
                    $pinf['tg_recommend']= Arr::get($post, 'tgr',0);
                    $service_article->setProjectArticle( $pinf,2 );

                }else{
                }
                $msg= '0';
            }
        }

        echo json_encode( $msg );

    }
    //end function

    /**
     * 投资考察会审核失败原因
     * @author 花文刚
     */
    public function action_getReason(){
        $post = $this->request->post();
        $service = new Service_User_Company_Project();
        $invest_id = intval(arr::get($post,'invest_id'));

        $reslut = $service->getReason($invest_id);
        if($reslut){
            echo $reslut;
        }else{
            echo "没有原因";
        }
    }

    /**
     * 删除播报的现场图片
     * @author 花文刚
     * @date 2013/11/18
     */
    public  function action_delBobaoImg(){
        $post = Arr::map("HTML::chars", $this->request->post());
        $invest_id = intval(arr::get($post,'invest_id'));
        $img_url = arr::get($post,'img_url');
        $service = new Service_User_Company_Project();

        $img_url = common::getImgUrl($img_url);
        $reslut = $service->delBobaoImg($invest_id,$img_url);

        echo  $reslut;
    }

}
