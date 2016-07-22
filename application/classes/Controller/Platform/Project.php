<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 项目官网controller
 * @author 钟涛
 *
 */
class Controller_Platform_Project extends Controller_Platform_Template
{
    private $_cache_get_project_total = 'getProjectTotal';
    private $_cache_get_user_total = 'getUserTotal';
    private $_cache_get_total_time = 86400;
    /**
     * @var 项目id
     */
    private $t_projectid;

    /**
     * @var 是否显示404
     */
    private $t_error;

    /**
     * @var 项目信息
     */
    private $t_projectinfo;

    /**
     * @var 企业基本信息
     */
    private $t_companyinfo;

    /**
     * @var 项目是否是被认领的
     */
    private $t_renlinginfo;

    /**
     * @var 项目是否是被认领的
     */
    private $t_touzikaocha = FALSE;

    private $_investment_bi = 'investment_bi';
    
    /*
     * 新增的一些用来那撒的变量
     */
    private $outside_user_company = false;
    private $outside_test_company = true;

    public function action_detail()
    {
    	d(1);
    	$project_id = 35999;
    	$content = View::factory('quickPublish/project_info');
    	exit;
    	$project_obj=new Service_QuickPublish_ProjectComplaint();
    	$project_info=$project_obj->getProjectDetail($project_id,2);
    	d($project_info);
    	$service = new Service_Platform_Project();
    	$service_user = new Service_User_Company_Project();
    	//项目基本信息
        if ($this->isLogins()) {
			 $iscompany = $service->getUseridByProjectID($project_id);
                if ($iscompany == $this->userId()) { //是否是自己的项目
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
          echo  '<br/>海报地址：'.urlbuilder::project($projectinfo->project_id); //http://www.myczzs.com/haibao/35999.html 
          //项目来源(1本站，2表示875，3生意街，4外采) 图片：http://static.yijuhua-alpha.net/images/common/company_default.jpg
   		  if ($projectinfo->project_source != 1)
   		  {
                $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
                if(project::checkProLogo($tpurl))
                	echo $tpurl;
                else
                	echo URL::webstatic('images/common/company_default.jpg');
   		  }
          else 
          {
                $tpurl=URL::imgurl($projectinfo->project_logo);
                 if(project::checkProLogo($tpurl))
                 	echo $tpurl;
                 else
                 	echo URL::webstatic('images/common/company_default.jpg');
                 
            } 
           	// 品牌名称
            echo '<br/>品牌名称：'.$projectinfo->project_brand_name;
            if(mb_strlen($projectinfo->project_brand_name)>16){
            	echo '<br/>截取品牌名称：'.mb_substr($projectinfo->project_brand_name,0,16,'UTF-8').'...';
            }
            else{
            	echo '<br/>全部-品牌名称：'.$projectinfo->project_brand_name;
            }
		 
		 $projectservice = new Service_User_Company_Project();
		 //行业
		 $pro_industry = $projectservice->getProjectindustryAndId($project_id);
		/* d($pro_industry);
		 $arr_pro=explode(",",$pro_industry);
         if(isset($arr_pro[0]) && $arr_pro[0]!='')
               echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($arr_pro[0]).'">'.$arr_pro[0].'</a>';
         if(isset($arr_pro[1]) && $arr_pro[1]!='')
                echo '、<a target="_Blank" href="/platform/index/search?w='.urlencode($arr_pro[1]).'">'.$arr_pro[1].'</a>';
		*/
		 $industry_zhong = '';
		 if (arr::get($pro_industry, 'one_name', '')) {
		 	$industry_zhong .= arr::get($pro_industry, 'one_name', '');
		 }
		 if (arr::get($pro_industry, 'two_name', '')) {
		 	$industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
		 }
		 echo '<br/>行业：'.$industry_zhong;
		 //意向加盟人数
		 $pro_industry_count = $projectservice->getPvCountByProjectid($project_id);
		 echo  '<br/>意向加盟人数：'.$pro_industry_count;
         //招商形式
         echo '<br/>招商形式：';
         $projectcomodel = $projectservice->getProjectCoModel($project_id);
         $pro_count=count($projectcomodel);
         if($pro_count){
         	$lst = common::businessForm();
         	$comodel_text=0;
         	foreach ($projectcomodel as $v){
         		$comodel_text++;
         		echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($lst[$v]).'">'.$lst[$v].'</a>';
         		if($comodel_text < $pro_count){
         			echo '、';
         		}
         	}
         }
         //招商地区[纯中文显示]
         echo  '<br/>招商地区：';
         $pro_area = $projectservice->getProjectArea($project_id);
         $area_zhong = '';
         if (count($pro_area) && is_array($pro_area)) {
         	$area = '';
         	foreach ($pro_area as $v) {
         		$area = $area . $v . ',';
         	}
         	$area = substr($area, 0, -1);
         	if (mb_strlen($area) > 500) {
         		$area_zhong = mb_substr($area, 0, 500, 'UTF-8') . '...';
         	} else {
         		$area_zhong = $area;
         	}
         } else {
         	$area_zhong = $pro_area;
         }
         echo $area_zhong;
         //投资金额
         echo '<br/>投资金额:';
         $project_amount_type=$projectinfo->project_amount_type;
         $moeny=common::moneyArr();
         echo isset($moeny[$project_amount_type])?$moeny[$project_amount_type]:'--';
         //联系电话
         echo '<br/>联系电话:';
         //d($projectinfo);
         echo '<br/>品牌发源地：'.$projectinfo->project_brand_birthplace;
         echo '----------------------------';
         //企业基本信息
         $companyinfo = $service->getCompanyByProjectID2($project_id);
         // 如果project的outside_com_id有值，并且能够使用
         /* if($companyinfo && !$companyinfo->com_id && $projectinfo && $projectinfo->outside_com_id){
          $com_user_ser = new Service_User_Company_User();
         $outside_user_result = $com_user_ser->getOutComInfoByOutComid($projectinfo->outside_com_id);
         if($outside_user_result && $outside_user_result->com_id){
         $this->outside_user_company = true;
         $companyinfo = $outside_user_result;
         }else{
         // 去test_company里面看看有没有
         $outside_test_result = $com_user_ser->getTestComInfoByOutComid($projectinfo->outside_com_id);
         if($outside_test_result && $outside_test_result->com_id){
         $this->outside_test_company = true;
         $companyinfo = $outside_test_result;
         }
         }
         }*/
    	//企业基本信息
    	
    	$companyinfo = $this->t_companyinfo;
    }
    
    
    
    /**
     * 项目官网头部+尾部公共部分
     * @author 钟涛
     */
    public function before()
    {
        parent::before();
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');


        $service = new Service_Platform_Project();
        //避免老版本历史招商会只有招商会ID进来报错
        if ($project_id == "" && arr::get($get, 'investid') != "") {
            $project_id = ORM::factory('Projectinvest', arr::get($get, 'investid'))->project_id;
        }
        $this->t_projectid = $project_id;
        if (!intval($project_id)) {
            $this->t_error = true;
        } else {
            $this->t_error = false; //不显示404页面

            //项目官网公共部分
            $this->content->maincontent = View::factory('platform/project/project_left');

            
            /*读取省级地区列表 Start*/
            $invest = new Service_User_Person_Invest();
            $pro = $invest->getArea();
            $this->content->maincontent->area = $pro;
            /*读取省级地区列表 End*/
            /*取得项目总数 Start*/
            $memcache = Cache::instance('memcache');
            try {
                $platform_num = $memcache->get($this->_cache_get_project_total);
            } catch (Cache_Exception $e) {
                $platform_num = 0;
            }

            if ($platform_num == 0) {
                $browing = new Service_Platform_Browsing();
                $platform_num = $browing->getProjectCount();
                $memcache->set($this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time);
            }
            /*取得项目总数 End*/
            /*取得总用户数 Start*/
            try {
                $user_num = $memcache->get($this->_cache_get_user_total);
            } catch (Cache_Exception $e) {
                $user_num = 0;
            }
            if ($user_num == 0) {
                $service_user = new Service_User();
                $user_num = $service_user->getRegUserNum();
                $memcache->set($this->_cache_get_user_total, $user_num, $this->_cache_get_total_time);
            }
            /*取得总用户数 End*/
            $this->content->maincontent->user_num = $user_num;          //取得总用户数
            $this->content->maincontent->platform_num = $platform_num;  //取得项目总数
           

            //1. 根据项目id得到企业id
            //2. 企业用户信息
            //3. 申请认领项目信息
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = ORM::factory('ProjectRenling')->where('project_id', '=', $project_id)->where('project_status', '=', 1)->count_all();
            $this->t_renlinginfo = $renlingmodel;
            
            
            $this->content->maincontent->setting = common::getSpecificProjectSetting(); // 给好项目递送名片记录日志表设计
            $this->content->maincontent->industryid = $service->getIndustryByPorjectid($project_id);  //根据项目id，得到1级+2级行业id和名称
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) { //是否是自己的项目
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
            $this->t_projectinfo = $projectinfo;
            //企业基本信息
            $companyinfo = $service->getCompanyByProjectID2($project_id);
            //****
            // 如果project的outside_com_id有值，并且能够使用
            if($companyinfo && !$companyinfo->com_id && $projectinfo && $projectinfo->outside_com_id){
            	$com_user_ser = new Service_User_Company_User();
            	$outside_user_result = $com_user_ser->getOutComInfoByOutComid($projectinfo->outside_com_id);
            	if($outside_user_result && $outside_user_result->com_id){
            		$this->outside_user_company = true;
            		$companyinfo = $outside_user_result;
            	}else{
            		// 去test_company里面看看有没有
            		$outside_test_result = $com_user_ser->getTestComInfoByOutComid($projectinfo->outside_com_id);
            		if($outside_test_result && $outside_test_result->com_id){
            			$this->outside_test_company = true;
            			$companyinfo = $outside_test_result;
            		}
            	}
            }
            $this->t_companyinfo = $companyinfo;
            //****
            
            if ($projectinfo) { //是否有项目基本信息和企业基本信息
                //是否显示招商会链接
                if ($service->isProjectInvest($project_id)) {
                    $this->content->maincontent->isProjectInvest = 1;
                } else {
                    $this->content->maincontent->isProjectInvest = 0;
                }

                if(arr::get($get, 'investid')){
                    $invest_id = arr::get($get, 'investid');
                }
                else{
                    $invest_id = $service->isProjectInvest($project_id,1);
                }
                $this->content->maincontent->invest_id = $invest_id;
                
                $this->content->maincontent->projectname = $projectinfo->project_brand_name; //品牌名称
                $this->content->maincontent->project_advert = $projectinfo->project_advert;  //项目广告语
                if($projectinfo->outside_com_name){
                	$this->content->maincontent->comname = $projectinfo->outside_com_name;   //企业名称
                }else{
	                if (isset($companyinfo->com_name)) {
	                    $this->content->maincontent->comname = $companyinfo->com_name;
	                } else {
	                    $this->content->maincontent->comname = '';
	                }
                }
                $this->content->maincontent->projectinfo = $projectinfo;
                $this->content->maincontent->companyinfo = $companyinfo;
                /*企业用户id + 用户诚信等级 Start*/
                $com_user_id = $service->getUseridByProjectID($project_id);
                $integrityservice = new Service_User_Company_Integrity();
                $ity_level = $integrityservice->getIntegrityLevel($com_user_id);
                $this->content->maincontent->ity_level = $ity_level;
                /*企业用户id + 用户诚信等级 End*/
                //是否有公司信息
                if (isset($companyinfo) && $companyinfo->com_id) {
                    $this->content->maincontent->is_has_company = true;
                } else {
                    $this->content->maincontent->is_has_company = false;
                }
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                if (count($image_arr)) {
                    $this->content->maincontent->is_has_image = true;
                } else {
                    $this->content->maincontent->is_has_image = false;
                }
                //海报是否存在
                $this->content->maincontent->ispage = $service->isPoster($project_id);
                //是否有项目资质
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $this->content->maincontent->isCerts = $iscerts['projectcertsStatus'];
                //判断是否是认领成功的项目
                $this->content->maincontent->isrenglingok = $service->getRenlingInfoData($project_id);
                //记录contrller_方法名 
                $con_method = $this->request->controller() . '_' . $this->request->action();
                $this->content->maincontent->proactionmethod = $con_method;
                //判断是否已登录
                if ($this->loginUser()) {
                    //当前登录用户id
                    $user_id = $this->userInfo()->user_id;
                    $plat_service = new Service_Platform_Search();
                    //是否已经收藏项目
                    $wathcproject = $service->getProjectWatchCount($user_id, $project_id);
                    //获取用户类型
                    $user_type = $this->userInfo()->user_type;
                    $this->content->maincontent->wathcproject = $wathcproject;
                    $this->content->maincontent->usertype = $user_type;
                    $this->content->maincontent->userid = $user_id;
                    $this->content->maincontent->reg_time = $this->userInfo()->reg_time;
                } else {
                    $this->content->maincontent->wathcproject = false;
                    $this->content->maincontent->userid = 0;
                    $this->content->maincontent->usertype = 3;
                    $this->content->maincontent->reg_time = 0;
                }
            } else { //404页面
                $this->t_error = true;
            }
        }
    }

    /**
     * 项目官网详情页面
     * 修改这个action  记得修改  action_zixuninfo-》项目资讯详情  action_projectzixunlist
     * @author 钟涛
     */
    public function action_index()
    {
    	#开启缓存
    	$redis = Cache::instance ('redis');
    	$project_count_num = 0;
    	$int_project_redis_count = "";
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
            $this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $content = View::factory('platform/project/project_info');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            $service_user = new Service_User_Company_Project();
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            $projectservice = new Service_User_Company_Project();
            //人脉关系
            //$content->connection = $projectservice->getProjectConnection($project_id);
            //项目行业
            $pro_industry = $projectservice->getProjectindustryAndId($project_id);
            //意向行业人数[当前项目的总浏览量]
            $pro_industry_count = $projectservice->getPvCountByProjectid($project_id);
            //申请加盟者人数
            $cardser = new Service_Card();
            //$jiaomeng_count = $cardser->getJiamengCountAll($project_id);
            $jiaomeng_count = 0;
            //企业用户id
            $com_user_id = $service->getUseridByProjectID($project_id);
            //招商地区
            $pro_area = $projectservice->getProjectArea($project_id);
            //取得人群
            $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
            if (count($group_text) == 0) {
                $content->group_text = "不限";
            } else {
                $content->group_text = $group_text;
            }
            $monarr = common::moneyArr();
            $content->monarr = common::moneyArr();
            //是否有项目图片信息
            $xuanchuanimage = "";
            $image_arr = $projectservice->getXuanChuanPic($project_id);

            if (is_array($image_arr) && !empty($image_arr)) {
                foreach ($image_arr as $key => $val) {
                    if ($val['project_type'] == intval(4)) {
                        $xuanchuanimage = $val['project_img'];
                    }
                }
            }
            if ($xuanchuanimage != '') {
                $content->is_has_image = true;
                $content->bigimage = url::imgurl($xuanchuanimage);
            } else {
                $content->is_has_image = false;
                $content->bigimage = '';
            }
            //招商形式
            $projectcomodel = $projectservice->getProjectCoModel($project_id);
            $content->projectcomodel = $projectcomodel;

            //投资行业[纯中文显示]
            $industry_zhong = '';
            if (arr::get($pro_industry, 'one_name', '')) {
                $industry_zhong .= arr::get($pro_industry, 'one_name', '');
            }
            if (arr::get($pro_industry, 'two_name', '')) {
                $industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
            }
            //招商地区[纯中文显示]
            $area_zhong = '';
            if (count($pro_area) && is_array($pro_area)) {
                $area = '';
                foreach ($pro_area as $v) {
                    $area = $area . $v . ',';
                }
                $area = substr($area, 0, -1);
                if (mb_strlen($area) > 16) {
                    $area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
                } else {
                    $area_zhong = $area;
                }
            } else {
                $area_zhong = $pro_area;
            }

            //判断是否是认领成功的项目
            $content->isrenglingok = $service->getRenlingInfoData($project_id);
            //获取投资保障状态
            try {
                $service_status = new Service_User_Company_ComStatus();
                $rs_all_server = $service_status->getCompanyStatusInfo($com_user_id, "all");
                $p_status = $projectinfo->project_source;
                if (($p_status == "4" || $p_status == "5") && $this->t_renlinginfo == 0) {
                    $rs_all_server['base'] = "2";
                    $rs_all_server['quality'] = "1";
                    $rs_all_server['safe'] = "2";
                    $rs_all_server['server'] = "2";
                }
                if ($p_status == "2" || $p_status == "3") {
                    $rs_all_server['server'] = "2";
                }
                $content->server_status_all = $rs_all_server;
            } catch (Exception $e) {
            }

            //是否有公司信息
            if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
                $content->is_has_company = true;
            } else {
                $content->is_has_company = false;
            }

            //根据项目ID返回对应企业用户登录userid
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = $this->t_renlinginfo;
            if ($this->isLogins()) {
                if ($projectinfo && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) && $renlingmodel == 0) { //是中国加盟网的项目显示认领图标
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            } else {
                if ($projectinfo && $renlingmodel == 0 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5)) { //没有被认领成功
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            }
            #获取项目宣传图
            $model = new Service_Platform_Search();
            $xuanchuan = $model->getProjectXuanChuanImage($project_id,intval(5));
            $content->xuanchuan_project_logo = $xuanchuan;
            //获取热门项目
            $top_projectct = $projectservice->getTopByIndustry($project_id);
            //获取最新的项目
            $newtop_projectct=$projectservice->getTopNew10ByIndustry($project_id);
            //获取项目的历史咨询 //@赵路生
            $history_consult = $service->getProjectHistoryConsult($project_id);
            $content->top_projectct = $top_projectct;
            $content->newtop_projectct = $newtop_projectct;
            $content->industry_zhong = $industry_zhong; //行业汉字组合
            $content->area_zhong = $area_zhong; //地区汉字组合
            $content->projectinfo = $projectinfo; //项目信息
            $content->companyinfo = $companyinfo; //公司信息
            $content->pro_industry = $pro_industry; //行业信息
            $content->pro_industry_count = $pro_industry_count; //意向加盟数量
            $content->jiaomeng_count = $jiaomeng_count; //申请加盟数量
			$content->history_consult = $history_consult; //该项目对应的个人用户的历史咨询

            //项目行业对应的行业新闻列表 @花文刚
            $service_article = new Service_News_Article();
            $industry_article_list = array();
            //todo 临时取消项目新闻显示
            if(false && !empty($pro_industry) && isset($pro_industry['one_id']) && $pro_industry['one_id']>0){
                //暂时只取一级行业的新闻 @花文刚
                $industry_article = $service_article->getIndustryNews(0,$pro_industry['one_id'],"hyxw_xm",10);
                $industry_article_list = $industry_article['list'];
            }
            $content->industry_article_list = $industry_article_list;


            //项目对应的文章列表
            $result_article = $service_article->getProjectArticleList($project_id, 10);
            $article_list = $result_article['list'];
            $content->article_list = $article_list;
            //当前页数
            $get = Arr::map("HTML::chars", $this->request->query());
            $nowpage = Arr::get($get, 'page', 0);
            $content->nowpage = $nowpage;

            //基业给的项目logo
            $projectlogonew=$model->replace_project_logo($projectinfo->project_source , $projectinfo->project_logo, $projectinfo->outside_id);
            $content->projectlogonew = URL::imgurl($projectlogonew);
            //seo优化
            $seo = $service->getProSeo($project_id);
            if($seo){
            	$this->template->title = isset($seo['title']) ? $seo['title'] : '';
            	$this->template->keywords = isset($seo['keyword']) ? $seo['keyword'] : '';
            	$this->template->description = isset($seo['description']) ? $seo['description'] : '';
            }else{
	            $this->template->title = "【" . $projectinfo->project_brand_name . "加盟】" . $projectinfo->project_brand_name . "开店、加盟、投资、创业_一句话创业投资平台";
	            $this->template->keywords = $projectinfo->project_brand_name . "加盟，" . $projectinfo->project_brand_name . ",". $projectinfo->project_brand_name . "开店加盟，" . $projectinfo->project_brand_name . "投资，" . $projectinfo->project_brand_name . "创业";
	            $project_source = $service->getProSource($project_id);
	            if(isset($companyinfo->com_name) && $industry_zhong != "" && $projectinfo->project_amount_type!= 0 && $area_zhong != ""){
	            	$mon = isset($monarr[$projectinfo->project_amount_type]) ? $monarr[$projectinfo->project_amount_type] : '';
	            	$str = $projectinfo->project_brand_name.'是'.$companyinfo->com_name.'的最成功的的产品之一。'.$companyinfo->com_name.'拥有一支专业化的团队，以卓越的服务品质和超越的经营策略打造极具市场竞争力的强势品牌。在'.$industry_zhong.'领域里，'.$projectinfo->project_brand_name.'拥有很大的市场份额，'.$mon.'就可以加盟、开店、创业，经常在'.$area_zhong.'举办招商会、投资考察会。加盟'.$projectinfo->project_brand_name.'的最好办法就是通过一句话招商平台，每月都有大量的'.$industry_zhong.'投资考察会。'.$projectinfo->project_brand_name.'加盟会是您创业项目的最好选择！';
	            	$str = mb_substr($str, 0,100);             	       	
	            	$this->template->description = '一句话（www.yjh.com）提供'.$projectinfo->project_brand_name.'加盟、投资、创业等信息。'.$str;
	            }else{
	            	$str = '一句话加盟网提供'.$projectinfo->project_brand_name.'开店加盟信息，'.$projectinfo->project_brand_name.'在全国各地都有招商加盟会，'.$projectinfo->project_brand_name.'投资考察以最好的服务、最全的产品线、优秀的品质及拥有同行业下最大化的市场份额，让'.$projectinfo->project_brand_name.'开店加盟、投资、赚钱更容易。';
	            	$str = mb_substr($str, 0,100);
	            	$this->template->description = '一句话（www.yjh.com）提供'.$projectinfo->project_brand_name.'加盟、投资、创业等信息。'.$str;
	            }
            }            
        }
    }

    /**
     * 产品页面[项目图片]
     * @author 钟涛
     */
    public function action_Images()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
 			$this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $get = Arr::map("HTML::chars", $this->request->query());
            $content = View::factory('platform/project/project_image');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            $content->projectinfo = $projectinfo;
            //图片
            $imgresult = Array('b_image' => array(), 's_image' => array());
            //项目图片
            $imgae = $service->getProjectImageByID($project_id);
            if (count($imgae)) {
                foreach ($imgae as $k => $v) {
                    if ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) {
                        $imgresult['b_image'][$k]['b_image'] = $v['b_image'];
                        $imgresult['s_image'][$k]['s_image'] = str_replace("poster/html/ps_{$projectinfo->outside_id}/project_images/", "poster/html/ps_{$projectinfo->outside_id}/project_images/125_100/", $v['b_image']);
                    } else {
                        $imgresult['b_image'][$k]['b_image'] = $v['b_image'];
                        $imgresult['s_image'][$k]['s_image'] = $v['s_image'];
                    }
                }
            }
            $content->imgresult = $imgresult;
            //seo优化
            $this->template->title = $projectinfo->project_brand_name . '_一句话商机速配网';
            $this->template->keywords = $projectinfo->project_brand_name . '，一句话商机速配网' ;
            $this->template->description = $projectinfo->project_brand_name . '为您提供大量的、全方位的、高清的、近距离的产品展示图及产品效果图。' . $projectinfo->project_brand_name . '全部都是经手工拍摄真实的实物图。选创业项目，上一句话商机速配网参考大量的' . $projectinfo->project_brand_name;
        }
    }

    /**
     * 资质页面[项目资质]
     * @author 钟涛
     */
    public function action_projectCerts()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
 			$this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $get = Arr::map("HTML::chars", $this->request->query());
            $content = View::factory('platform/project/project_certs');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            $content->projectinfo = $projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            //项目资质图片
            $imgresult = array();
            $project = $service->getProjectCertesByIDNew($project_id);
            if (count($project)) {
                foreach ($project as $k => $v) {
                    $imgresult[$k]['b_image'] = $v['b_image'];
                    $imgresult[$k]['s_image'] = $v['s_image'];
                    $imgresult[$k]['content'] = $v['content'] ? $v['content'] : '';
                }
            }

            $content->imgresult = $imgresult;
            if (isset($companyinfo->com_name)) {
            	$companyinfo_com_name=$companyinfo->com_name;
            }else{
            	$companyinfo_com_name='';
            }
            //seo优化
            $this->template->title = $companyinfo_com_name . '企业营业执照_' . $companyinfo_com_name. '企业资质认证_一句话商机速配';
            $this->template->keywords = $companyinfo_com_name . ',' . $companyinfo_com_name . '企业营业执照,' . $companyinfo_com_name . '企业资质认证，一句话商机速配网';
            $this->template->description = $companyinfo_com_name . '企业营业执照资质认证提供' . $companyinfo_com_name . '企业营业执照及' . $companyinfo_com_name . '资质认证，您可以放心的投资加盟，一句话生意网担保，加盟无忧、创业无忧。';
        }
    }

    /**
     * 项目官网-公司
     * @author 钟涛
     */
    public function action_projectCompany()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
 			$this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $content = View::factory('platform/project/project_company');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();           
            $projectinfo = $this->t_projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            $content->projectinfo = $projectinfo;
               
            $content->companyinfo = $companyinfo;
            $content->outside_user_company = $this->outside_user_company;
            $content->outside_test_company = $this->outside_test_company;
            // 外采项目的公司如果outside_com_id也不能获取信息的话就会走这一步
			if($companyinfo && !$companyinfo->com_id && !$this->outside_user_company && !$this->outside_test_company && $projectinfo && !$projectinfo->outside_com_id){
				$model = new Service_Platform_Search();
				//基业给的项目logo
				$projectlogonew=$model->replace_project_logo($projectinfo->project_source , $projectinfo->project_logo, $projectinfo->outside_id);
				$content->projectlogonew = URL::imgurl($projectlogonew);
				$projectservice = new Service_User_Company_Project();
				//意向行业人数[当前项目的总浏览量]
				$content->pro_industry_count = $projectservice->getPvCountByProjectid($projectinfo->project_id);
				$content->monarr = common::moneyArr();
				//项目行业
			    $content->pro_industry = $projectservice->getProjectindustryAndId($projectinfo->project_id);
				//取得人群
			    $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
			    if (count($group_text) == 0) {
			    	$content->group_text = "不限";
			    } else {
			    	$content->group_text = $group_text;
			    }
				//招商地区
			    $pro_area = $projectservice->getProjectArea($projectinfo->project_id);
				//招商地区[纯中文显示]
				$area_zhong = '';
				if (count($pro_area) && is_array($pro_area)) {
					$area = '';
					foreach ($pro_area as $v) {
						$area = $area . $v . ',';
					}
					$area = substr($area, 0, -1);
					if (mb_strlen($area) > 16) {
						$area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
					} else {
						$area_zhong = $area;
					}
				} else {
					$area_zhong = $pro_area;
				}
				$content->area_zhong = $area_zhong;
				//是否有公司信息
				if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
					$content->is_has_company = true;
				} else {
					$content->is_has_company = false;
				}
				 //招商形式
			    $content->projectcomodel = $projectservice->getProjectCoModel($projectinfo->project_id);
            }//*

            $userservice = new Service_User_Company_User();
            if(isset($companyinfo->com_id)){
            	$content->comlogo = $userservice->getCompanyLogo($companyinfo->com_id);
            }else{
            	$content->comlogo ='';
            }
            //企业用户id
            $com_user_id = 0;
            if (isset($companyinfo->com_user_id)) {
                $com_user_id = $companyinfo->com_user_id;
            }
            $content->com_user_id = $com_user_id;
            //是否有企业资质信息
            $zizhi_result = $service->getProjectCompanysByID($project_id);
            $comser=new Service_User_Company_User();
            if(isset($companyinfo->com_id)){
            	$zizhi_status=$comser->checkCompanyCertificationStatus($companyinfo->com_id);
            }else{
            	$zizhi_status=0;
            }
            if ($zizhi_status==1 && $zizhi_result != "") {
                $content->is_has_zizhiimage = true;
            } else {
                $content->is_has_zizhiimage = false;
            }
            $comname='';
        	if($projectinfo->outside_com_name){
          		$comname = $projectinfo->outside_com_name;
         	}else{
	       		if (isset($companyinfo->com_name)) {
	        		$comname = $companyinfo->com_name;
	         	} 
        	}
            //项目名称项目|投资赚钱好项目，一句话的事
            $this->template->title = $comname . '_一句话商机速配网';
            $this->template->keywords = $comname . "，一句话商机速配网";
            
            if (isset($companyinfo->com_desc)) {
                $this->template->description = mb_substr($companyinfo->com_desc, 0, 110, 'UTF-8');
            }
        }
    }

    /**
     * 项目官网首页-封面【封面不存在，已修改为详情页面】
     * @author 钟涛
     */
    public function action_index_old()
    {
//         $get = Arr::map("HTML::chars", $this->request->query());
//         $project_id=arr::get($get,'projectid');
//         if(!intval($project_id)){
//             $content = View::factory('platform/page_404');
//             $this->content->maincontent = $content;
//         }else{
//            $service = new Service_Platform_Project();
//             //企业基本信息
//             $companyinfo=$service->getCompanyByProjectID($project_id);
//             //项目基本信息
//             $iscompany=$service->getUseridByProjectID($project_id);
//             if($this->isLogins()){
//                 if($iscompany==$this->userId()){
//                     $projectinfo=$service->getProjectInfoByIDAll($project_id);
//                 }else{
//                     $projectinfo=$service->getProjectInfoByID($project_id);
//                 }
//             }else{
//                 $projectinfo=$service->getProjectInfoByID($project_id);
//             }
//             if($companyinfo && $projectinfo){
//                 $content = View::factory('platform/project/projectcenter');
//                 $this->content->maincontent = $content;
//                 $content->project_id = $project_id;
//                 //企业基本信息
//                 $content->company_list = $companyinfo->as_array();
//                 //项目名
//                 $content->project_name = $projectinfo->project_brand_name;
//                 //项目简介
//                 $content->project_summary = $projectinfo->project_summary;
//                 $projectservice=new Service_User_Company_Project();
//                 //招商地区
//                 $pro_area=$projectservice->getProjectArea($project_id);
//                 $area='';
//                 if(count($pro_area) && is_array($pro_area)){
//                     foreach ($pro_area as $v){
//                         $area=$area.$v.',';
//                     }
//                  $area= substr($area,0,-1);
//                 }else{
//                     $area=$pro_area;
//                 }
//                 $sumary_text=htmlspecialchars_decode($projectinfo->project_summary);
//                 $ke_sumary_text= mb_substr(strip_tags($sumary_text),0,50,'UTF-8');
//                 //seo优化
//                 $this->template->title = $projectinfo->project_brand_name.'|'.$companyinfo->com_name.$area.$content->project_name;
//                 $this->template->keywords = $projectinfo->project_brand_name.','.'投资项目，投资赚钱好项目';
//                 $this->template->description = '一句话投资招商平台为用户提供'.$projectinfo->project_brand_name.'项目，'.$ke_sumary_text.'致力于为投资者提供最专业、快速、精准的匹配项目。投赚钱好项目，一句话的事。';
//                 //项目访问量统计
//                 $service->insertProjectStatistics($project_id);
//                 if(!empty($companyinfo->com_phone)){//获取座机号码
//                     $com_phone=explode('+', $companyinfo->com_phone);
//                     if(!empty($com_phone[1])){//判断座机号码是否为空
//                         $content->com_phone = $com_phone[0];
//                         $content->branch_phone = $com_phone[1];
//                     }else{
//                         $content->com_phone = $companyinfo->com_phone;
//                         $content->branch_phone = '';
//                     }
//                 }else{
//                     $content->com_phone = "";
//                     $content->branch_phone = '';
//                 }
//             }
//             else{
//                 $content = View::factory('platform/page_404');
//                 $this->content->maincontent = $content;
//             }
//         }
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $service = new Service_Platform_Project();
            //项目访问量统计
            $service->insertProjectStatistics($project_id);
            $project_source = $service->getProSource($project_id);
            if ($project_source == 5 && !$service->getRenlingInfoData($project_id)) {
                $content = View::factory('platform/project/project_xq');
                //是否可以投递名片
                $isShowSendCard = $service->isShowSendCard($project_id);
                $content->isShowSendCard = $isShowSendCard;
            } else {
                $content = View::factory('platform/project/projectinfo');
            }
            $this->content->maincontent = $content;
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = ORM::factory('ProjectRenling')->where('project_id', '=', $project_id)->where('project_status', '=', 1)->count_all();
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
                if ($projectinfo && $this->userInfo()->user_type == 1 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) && $renlingmodel == 0) { //企业用户且是中国加盟网的项目显示认领图标
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false; //个人用户不显示认领图标
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
                if ($projectinfo && $renlingmodel == 0 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5)) { //没有被认领成功
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            }
            //企业基本信息
            $companyinfo = $service->getCompanyByProjectID($project_id);

            if ($projectinfo && $companyinfo) {
                $content->projectinfo = $projectinfo;
                $content->companyinfo = $companyinfo;
                $projectservice = new Service_User_Company_Project();
                //人脉关系
                $content->connection = $projectservice->getProjectConnection($project_id);
                //项目行业
                $pro_industry = $projectservice->getProjectindustry($project_id);
                $content->pro_industry = $pro_industry;
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //招商地区
                $pro_area = $projectservice->getProjectArea($project_id);
                $area = '';
                if (count($pro_area) && is_array($pro_area)) {
                    foreach ($pro_area as $v) {
                        $area = $area . $v . ',';
                    }
                    $area = substr($area, 0, -1);
                } else {
                    $area = $pro_area;
                }
                $content->pro_area = $pro_area;
                //取得人群
                $group_text = $projectservice->getProjectCrowd($projectinfo->project_id, $projectinfo->project_groups_label);
                if ($group_text == ",") {
                    $content->group_text = "";
                } else {
                    $content->group_text = $group_text;
                }
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                //招商形式
                $projectcomodel = $projectservice->getProjectCoModel($project_id);
                $content->projectcomodel = $projectcomodel;
                $content->isinvest = $isinvest;
                $xingshi = '';
                $lst = common::businessForm();
                if (count($projectcomodel)) {
                    $comodel_text = '';
                    foreach ($projectcomodel as $v) {
                        $comodel_text = $comodel_text . $lst[$v] . ',';
                    }
                    $xingshi = substr($comodel_text, 0, -1);
                }
                $monarr = common::moneyArr();
                $list = guide::attr10();
                //seo优化
                //地域+招商形式+行业+投资金额+风险性+项目名称
//                if($projectinfo->project_amount_type){
//                    $mon=$monarr[$projectinfo->project_amount_type];
//                }else{
//                    $mon='';
//                }
//                $this->template->title = $area.$xingshi.$pro_industry.$mon.$list[$projectinfo->risk].$projectinfo->project_brand_name;
//                $Keywords_this=$area.$pro_industry.$mon.','.$area.$list[$projectinfo->risk];
//                $this->template->keywords = $Keywords_this;
//                $this->template->description = '一句话投资招商平台为用户提供专业、快速、精准匹配的符合'.$Keywords_this.'的'.$projectinfo->project_brand_name.'项目。投资赚钱好项目，一句话的事。';

                //设置title,keywords,description
                $this->template->title = "【" . $projectinfo->project_brand_name . "】" . $projectinfo->project_brand_name . "开店加盟、投资、创业_一句话创业投资平台";
                $this->template->keywords = $projectinfo->project_brand_name . "，" . $projectinfo->project_brand_name . "开店加盟，" . $projectinfo->project_brand_name . "加盟，" . $projectinfo->project_brand_name . "投资，" . $projectinfo->project_brand_name . "创业，一句话";
                if ($project_source != 5) {
                    $str = mb_substr($projectinfo->product_features, 0, 95, 'UTF-8');
                    $this->template->description = "一句话（yijhua.net）提供" . $projectinfo->project_brand_name . "加盟、投资、创业等信息。" . $str;
                } else {
                    $str = mb_substr($projectinfo->project_summary, 0, 95, 'UTF-8');
                    $this->template->description = "一句话（yijhua.net）提供" . $projectinfo->project_brand_name . "加盟、投资、创业等信息。" . $str;
                }
                //企业用户id
                $com_user_id = $service->getUseridByProjectID($project_id);
                $content->com_user_id = $com_user_id;
                //用户诚信等级
                $integrityservice = new Service_User_Company_Integrity();
                $ity_level = $integrityservice->getIntegrityLevel($com_user_id);
                $content->ity_level = $ity_level;
                //判断是否已登录
                if ($this->loginUser()) {
                    //当前登录用户id
                    $user_id = $this->userInfo()->user_id;
                    $plat_service = new Service_Platform_Search();
                    //是否已经发送名片
                    $card = $plat_service->getCardInfo($user_id, $com_user_id);
                    //是否已经收藏项目
                    $wathcproject = $service->getProjectWatchCount($user_id, $project_id);
                    //获取用户类型
                    $user_type = $this->userInfo()->user_type;
                    $content->userid = $user_id;
                    $content->card = $card;
                    $content->projectid = $project_id;
                    $content->wathcproject = $wathcproject;
                    $content->usertype = $user_type;
                } else {
                    $content->card = false;
                    $content->wathcproject = false;
                    $content->userid = 0;
                    $content->usertype = 3;
                }
                //获取投资保障状态
                try {
                    $service_status = new Service_User_Company_ComStatus();
                    $rs_all_server = $service_status->getCompanyStatusInfo($com_user_id, "all");
                    $p_status = $projectinfo->project_source;
                    //print_r ($projectinfo);exit;
                    //$rl_status= $service->getRenlingInfoData($project_id);

                    //项目来源(1本站，2表示875，3生意街，4外采)
                    //echo $p_status;
                    //$renlingmodel>0 认领成功
                    if (($p_status == "4" || $p_status == "5") && $renlingmodel == 0) {

                        $rs_all_server['base'] = "2";
                        $rs_all_server['quality'] = "1";
                        $rs_all_server['safe'] = "2";
                        $rs_all_server['server'] = "2";
                    }

                    if ($p_status == "2" || $p_status == "3") {
                        $rs_all_server['server'] = "2";
                    }
                    $content->server_status_all = $rs_all_server;
                } catch (Exception $e) {
                }

            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
    }


    /**
     * 项目官网-公司
     * @author 钟涛
     */
    public function action_projectCompan_old()
    {
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $content = View::factory('platform/project/projectcompany');
            $this->content->maincontent = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
            }
            //企业基本信息
            $companyinfo = $service->getCompanyByProjectID($project_id);
            if ($projectinfo && $companyinfo) {
                $content->projectinfo = $projectinfo;
                $content->companyinfo = $companyinfo;
                $projectModel = new Service_User_Company_Project();
                $userservice = new Service_User_Company_User();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                $content->projectinfo = $projectinfo;
                $content->isinvest = $isinvest;
                //企业用户id
                $com_user_id = $service->getUseridByProjectID($project_id);
                $content->com_user_id = $com_user_id;
                if(isset($companyinfo->com_id)){
                	$content->comlogo = $userservice->getCompanyLogo($companyinfo->com_id);
                }else{
                	$content->comlogo ='';
                }
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
                //是否有企业资质信息
                $zizhi_result = $service->getProjectCompanysByID($project_id);
                if ($zizhi_result != "") {
                    $content->is_has_zizhiimage = true;
                } else {
                    $content->is_has_zizhiimage = false;
                }
                //用户诚信等级
                $integrityservice = new Service_User_Company_Integrity();
                $ity_level = $integrityservice->getIntegrityLevel($com_user_id);
                $content->ity_level = $ity_level;
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //seo优化
                //项目名称项目|投资赚钱好项目，一句话的事
                $this->template->title = $companyinfo->com_name . '_一句话';
                $this->template->keywords = $companyinfo->com_name . "，一句话";
                $this->template->description = mb_substr($companyinfo->com_desc, 0, 110, 'UTF-8');;

            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
    }

    /**
     * 项目官网-封底
     * @author 钟涛
     */
    public function action_projectEnd()
    {
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $content = View::factory('platform/project/projectend');
            $this->content->maincontent = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
            }
            //企业基本信息
            $companyinfo = $service->getCompanyByProjectID($project_id);
            if ($projectinfo && $companyinfo) {
                $content->projectinfo = $projectinfo;
                $content->companyinfo = $companyinfo;
                $projectModel = new Service_User_Company_Project();
                $userservice = new Service_User_Company_User();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                $content->projectinfo = $projectinfo;
                $content->isinvest = $isinvest;
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //海报修改
                $urlToEncode = urlbuilder::project($project_id);
                //$urlToEncode=URL::website('platform/project/index').'?projectid='.$project_id;
                $content->encodeimage = $this->generateQRfromGoogle($urlToEncode);
                //seo优化
                //项目名称项目|投资赚钱好项目，一句话的事
                $this->template->title = $projectinfo->project_brand_name . '项目|投资赚钱好项目，一句话的事';
                $this->template->keywords = $projectinfo->project_brand_name;
                $this->template->description = '一句话投资招商平台，专业、快速、精准的匹配到投资者最适合的赚钱好项目—‘' . $projectinfo->project_brand_name . '’项目。投资赚钱好项目，一句话的事。';
                //企业用户id
                $com_user_id = $service->getUseridByProjectID($project_id);
                $content->com_user_id = $com_user_id;
                //判断是否已登录
                if ($this->loginUser()) {
                    //当前登录用户id
                    $user_id = $this->userInfo()->user_id;
                    $plat_service = new Service_Platform_Search();
                    //是否已经发送名片
                    $card = $plat_service->getCardInfo($user_id, $com_user_id);
                    //是否已经收藏项目
                    $wathcproject = $service->getProjectWatchCount($user_id, $project_id);
                    $content->userid = $user_id;
                    $content->card = $card;
                    $content->wathcproject = $wathcproject;
                } else {
                    $content->card = false;
                    $content->wathcproject = false;
                    $content->userid = 0;
                }
                if (!empty($companyinfo->com_phone)) { //获取座机号码
                    $com_phone = explode('+', $companyinfo->com_phone);
                    if (!empty($com_phone[1])) { //判断座机号码是否为空
                        $content->com_phone = $com_phone[0];
                        $content->branch_phone = $com_phone[1];
                    } else {
                        $content->com_phone = $companyinfo->com_phone;
                        $content->branch_phone = '';
                    }
                } else {
                    $content->com_phone = "";
                    $content->branch_phone = '';
                }
            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
    }

    /**
     * 根据url地址生成二微码图片
     * @author 钟涛
     */
    function generateQRfromGoogle($chl, $widhtHeight = '150', $EC_level = 'L', $margin = '0')
    {
        $size = 10;
        return '<img src="http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $chl . '" alt="QR code" widhtHeight="' . $size . '" widhtHeight="' . $size . '"/>';
    }

    /**
     * flash接口：根据项目id获取项目图片
     * @author 钟涛
     */
    public function action_getImageByPorjecid()
    {
        //返回结果
        $result = Array('logo' => Array("logo" => ""), 'b_image' => array(), 's_image' => array());
        //错误返回空
        $error_result = json_encode($result);
        $project_id = $this->request->post("projectid");
        if (!intval($project_id)) {
            echo json_encode($error_result);
            exit;
        }
        $service = new Service_Platform_Project();
        //项目基本信息
        $iscompany = $service->getUseridByProjectID($project_id);
        if ($this->isLogins()) {
            if ($iscompany == $this->userId()) {
                $projectinfo = $service->getProjectInfoByIDAll($project_id);
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
        } else {
            $projectinfo = $service->getProjectInfoByID($project_id);
        }
        if ($projectinfo) {
            //项目logo
            if ($projectinfo->project_source != 1) {
                $result['logo'] = project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
            } else {
                $result['logo'] = URL::imgurl($projectinfo->project_logo);
            }
            //项目图片
            $imgae = $service->getProjectImageByID($project_id);
            if (count($imgae)) {
                foreach ($imgae as $k => $v) {
                    if ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) {
                        $result['b_image'][$k]['b_image'] = $v['b_image'];
                        $result['s_image'][$k]['s_image'] = str_replace("poster/html/ps_{$projectinfo->outside_id}/project_images/", "poster/html/ps_{$projectinfo->outside_id}/project_images/125_100/", $v['b_image']);
                    } else {
                        $result['b_image'][$k]['b_image'] = $v['b_image'];
                        $result['s_image'][$k]['s_image'] = $v['s_image'];
                    }
                }
            }
            echo json_encode($result);
            exit;
        } else {
            echo json_encode($error_result);
            exit;
        }
    }

    /**
     * flash接口：根据项目id获取资质图片
     * @author 钟涛
     */
    public function action_getCertsByPorjecid()
    {
        //返回结果
        $result = Array('b_image' => array(), 's_image' => array(), 'content' => array());
        //错误返回空
        $error_result = json_encode($result);
        $project_id = $this->request->post("projectid");
        if (!intval($project_id)) {
            echo json_encode($error_result);
            exit;
        }
        $service = new Service_Platform_Project();
        //项目基本信息
        $iscompany = $service->getUseridByProjectID($project_id);
        if ($this->isLogins()) {
            if ($iscompany == $this->userId()) {
                $projectinfo = $service->getProjectInfoByIDAll($project_id);
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
        } else {
            $projectinfo = $service->getProjectInfoByID($project_id);
        }
        if ($projectinfo) {
            //项目资质图片
            $project = $service->getProjectCertesByIDNew($project_id);
            if (count($project)) {
                foreach ($project as $k => $v) {
                    $result['b_image'][$k]['b_image'] = $v['b_image'];
                    $result['s_image'][$k]['s_image'] = $v['s_image'];
                    $result['content'][$k]['content'] = $v['content'] ? $v['content'] : '';
                }
            }
            echo json_encode($result);
            exit;
        } else {
            echo $error_result;
            exit;
        }
    }

    /**
     * 根据项目id获取项目图片
     * @author 钟涛
     */
    public function action_Images_old()
    {
        //返回结果
        $result = Array('logo' => Array("logo" => ""), 'b_image' => array(), 's_image' => array());
        //错误返回空
        $error_result = json_encode($result);
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $content = View::factory('platform/project/projectimage');
            $this->content->maincontent = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
            }
            if ($projectinfo) {
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                $content->projectinfo = $projectinfo;
                $content->isinvest = $isinvest;
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //seo优化
                $this->template->title = $projectinfo->project_brand_name . '_一句话创业投资平台';
                $this->template->keywords = $projectinfo->project_brand_name . ',一句话';
                $this->template->description = $projectinfo->project_brand_name . "为您提供大量的、全方位的、高清的、近距离的产品展示图及产品效果图。" . $projectinfo->project_brand_name . "全部都是经手工拍摄真实的实物图。选创业项目，上一句话参考大量的" . $projectinfo->project_brand_name;
            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
    }

    /**
     * 项目官网招商会页面
     * @author 潘宗磊
     */
    public function action_projectInvest()
    {
        $get = Arr::map("HTML::chars", $this->request->query());
        $in_id = arr::get($get, 'investid');
        $service = new Service_Platform_Project();
        $plat_service = new Service_Platform_Search();
        //是否用默认title，keyword，description
        $seo_default = true;
        $history = 0;

        if (intval($in_id)) {
            $project_id = ORM::factory('Projectinvest', $in_id)->project_id;
        }
        if (!intval($project_id)) {
            $this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                }

                //取当前登录用户的企业id @花文刚
                $com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find()->com_id;
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $com_id = "";
            }
            if ($projectinfo) {
                $start_time = ORM::factory('Projectinvest', $in_id)->investment_start;
                $reslut = $service->getInvestById($in_id,$com_id);
                if($start_time<=time()){
                    $history = 1;
                }
                if (intval($history)) { //历史招商会

                    $content = View::factory('platform/project/project_invest_history');
                    //当前登录用户id
                    $com_user_id = $service->getUseridByProjectID($project_id);

                    if ($this->loginUser()) {
                        if ($iscompany == $this->userId()) {
                            $content->ismy = 1;
                        }
                        $user_id = $this->userInfo()->user_id;
                        //是否已经发送名片
                        $card = $plat_service->getCardInfo($user_id, $com_user_id);
                        $content->card = $card;
                        $content->userid = $user_id;
                    } else {
                        $content->card = false;
                        $content->userid = 0;
                    }
                    $content->com_user_id = $com_user_id;
                    //获取招商会播报信息
                    $com_project = new Service_User_Company_Project();
                    $content->bobao = $com_project->getBobao($in_id);
                    $content->invest = $reslut;
                    $content->start = $reslut['investment_start'];
                    $content->end = $reslut['investment_end'];
                    $content->city = $reslut['investment_city'];
                } else { //直接传入商会ID 正常还没开始的招商会
                    $now = time();
                    $user = "";
                    if ($this->isLogins()) {
                        $user = ORM::factory("Personinfo")->where('per_user_id', '=', $this->userInfo()->user_id)->find();
                        if ($iscompany == $this->userId()) {
                            $investNum = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_start', '>=', $now)->count_all();
                            $investes = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_start', '>=', $now)->find_all();
                        } else {
                            $investNum = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_status', '=', 1)->where('investment_start', '>=', $now)->count_all();
                            $investes = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_status', '=', 1)->where('investment_start', '>=', $now)->find_all();
                        }
                    } else {
                        $investNum = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_status', '=', 1)->where('investment_start', '>=', $now)->count_all();
                        $investes = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_status', '=', 1)->where('investment_start', '>=', $now)->find_all();
                    }

                    if ($investNum == 0 || arr::get($reslut, 'investment_id') == "") {
                        //$this->response->status(404);
                        $content = View::factory("platform/project/project_invest_tishi");
//                         $this->content->maincontent = $view;
                        $this->template->title = $projectinfo->project_brand_name . "投资考察会_一句话商机速配网";
                        $this->template->keywords = $projectinfo->project_brand_name . "投资考察会,一句话商机速配网";
                        $this->template->description = "您好，项目" . $projectinfo->project_brand_name . "目前没举办投资考察会！敬请期待下场" . $projectinfo->project_brand_name . "投资考察会";
                        $seo_default = false;
                    } else {
                        $content = View::factory('platform/project/project_invest');

                        $content->user = $user;
                        //获取开始时间的最小时间，和招商会时间的最大时间
                        $start = empty($start) ? 0 : min($start);
                        $end = empty($end) ? 0 : max($end);

                        #获取即将召开的招商会的id
                        $arr_data = $service->getWillInvest($project_id,$com_id);
                        #招商会id合并
                        $arr_investmentid = array();
                        if ($arr_data) {
                            foreach ($arr_data as $key => $val) {
                                $arr_investmentid[] = $val['investment_id'];
                            }
                        }
                        $content->investmentsid = implode(" ", $arr_investmentid);
                        $content->investment_id = isset($arr_data[0]['investment_id']) ? $arr_data[0]['investment_id'] : 0;
                        $content->start = $reslut['investment_start'];
                        $content->end = $reslut['investment_end'];
                        $content->city = $reslut['investment_city'];
                        $content->invest = $reslut;


                        if ($investNum == 1) {
                            $content->city = $reslut['investment_address'];
                        }
                        //判断是否已登录
                        if ($this->isLogins()) {
                            $content->mobile = $this->userInfo()->mobile;
                            $content->user_id = $this->userInfo()->user_id;
                            $content->user_type = $this->userInfo()->user_type;
                            //获取这个项目所有的招商会场次
                            if (!empty($reslut)) {
                                $invest_array = $reslut;

                                $invest_array['num'] = ORM::factory('Applyinvest')->where('invest_id', '=', $reslut['investment_id'])->where('user_id', '=', $this->userInfo()->user_id)->count_all();

                                $content->invest_array = $invest_array;
                            }

                        }
                    }
                }

                $willInvest = $service->getWillInvest($project_id,$com_id);
                $content->willInvest_num = count($willInvest);
                //即将开始的招商会
                $content->willInvest = $willInvest;
                //历史投资考察会
                $content->historyInvest = $service->getWillInvest($project_id,$com_id,true);

                //招商会统计浏览次数
                $service_u_c = new Service_User_Company_Project();
                $view_num = $service_u_c->getInvestmentHaveWatch($in_id);
                $content->investment_bi = $reslut['virtual_viewer'] + $view_num + 1;

                $companyinfo = $service->getCompanyByProjectID($project_id);
                $content->companyinfo = $companyinfo;
                //右侧项目详情start
                $projectservice = new Service_User_Company_Project();
                $content->monarr = common::moneyArr();
                //项目行业
                $pro_industry = $projectservice->getProjectindustryAndId($project_id);
                $content->pro_industry = $pro_industry;
                //投资行业[纯中文显示]
                $industry_zhong = '';
                if (arr::get($pro_industry, 'one_name', '')) {
                    $industry_zhong .= arr::get($pro_industry, 'one_name', '');
                }
                if (arr::get($pro_industry, 'two_name', '')) {
                    $industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
                }

                //取得人群
                $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
                if (count($group_text) == 0) {
                    $content->group_text = "不限";
                } else {
                    $content->group_text = $group_text;
                }
                //招商地区[纯中文显示]
                //招商地区
                $pro_area = $projectservice->getProjectArea($project_id);
                $area_zhong = '';
                if (count($pro_area) && is_array($pro_area)) {
                    $area = '';
                    foreach ($pro_area as $v) {
                        $area = $area . $v . ',';
                    }
                    $area = substr($area, 0, -1);
                    if (mb_strlen($area) > 16) {
                        $area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
                    } else {
                        $area_zhong = $area;
                    }
                } else {
                    $area_zhong = $pro_area;
                }
                $content->area_zhong = $area_zhong;
                //是否有公司信息
                if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
                    $content->is_has_company = true;
                } else {
                    $content->is_has_company = false;
                }
                //招商形式
                $projectcomodel = $projectservice->getProjectCoModel($project_id);
                $content->projectcomodel = $projectcomodel;
                //end

                //$this->content->maincontent = $content;
                $this->content->maincontent->content = $content;

                //设置title,keywords,description

                $com_name = empty($companyinfo->com_name) ? '' : $companyinfo->com_name;
                $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($reslut, 'investment_details'), 0)));
                $str = Text::limit_chars(strip_tags($string), 100);
                //从第7个字开始截取
                $str = mb_substr($str, 7);
                if ($seo_default) {
                    $this->template->title = $reslut['investment_name'] . "投资考察招商会_一句话商机速配网";
                    $this->template->keywords = $reslut['investment_name'] . "，" . $com_name . "招商，" . $com_name . "加盟，一句话商机速配网";
                    $this->template->description = $reslut['investment_name'] . "投资考察招商会是由" . $str;
                }
                $content->project_id = $project_id;
                $content->projectinfo = $projectinfo;
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //判断是否有资质图片
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                $content->int_id = $in_id;
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
            } else {
                $this->response->status(404);
                $view = View::factory("error404");
                $this->content->maincontent = $view;
                $this->template->title = "您的访问出错了_404错误提示_一句话";
                $this->template->keywords = "您的访问出错了,404";
                $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
            }
        }
    }

    /**
     * 项目官网招商会报名
     * @author 潘宗磊
     */
    public function action_applyInvest()
    {
        if ($this->request->method() == HTTP_Request::POST) {
            $post = Arr::map("HTML::chars", $this->request->post());
            $project_id = intval(arr::get($post, 'projectid'));
            $invest_id = intval(arr::get($post, 'invest_id'));
            if (!intval($project_id)) {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            } else {
                $service = new Service_Platform_Project();
                //项目基本信息
                $iscompany = $service->getUseridByProjectID($project_id);
                if ($this->isLogins()) {
                    if ($iscompany == $this->userId()) {
                        $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    } else {
                        $projectinfo = $service->getProjectInfoByID($project_id);
                    }
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                }


                if ($projectinfo) {
                    $invest = new Service_User_Person_Invest();
                    unset($post['projectid']);
                    unset($post['x']);
                    unset($post['y']);

                    $apply = $invest->applyInvest($post);
                    if ($apply > 0) {

                        //self::redirect('/platform/project/projectInvest?projectid='.$project_id);
                        self::redirect(urlbuilder::projectInvest($invest_id));
                    }
                }
            }
        }
    }

    /**
     * flash接口：根据项目id获取资质图片
     * @author 潘宗磊
     */
    public function action_getCertsByPorjecid_old()
    {
        //返回结果
        $result = "";
        //错误返回空
        $error_result = "";
        $project_id = $this->request->query("projectid");
        if (!intval($project_id)) {
            echo $error_result;
            exit;
        }
        $service = new Service_Platform_Project();
        //项目基本信息
        $iscompany = $service->getUseridByProjectID($project_id);
        if ($this->isLogins()) {
            if ($iscompany == $this->userId()) {
                $projectinfo = $service->getProjectInfoByIDAll($project_id);
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
        } else {
            $projectinfo = $service->getProjectInfoByID($project_id);
        }
        if ($projectinfo) {
            //项目图片
            $project = $service->getProjectCertesByID($project_id);
            $company = $service->getProjectCompanysByID($project_id);
            $result = '<content>' . $company . $project . '</content>';
            echo $result;
            exit;
        } else {
            echo $error_result;
            exit;
        }
    }

    /**
     * flash接口：根据项目id获取企业资质图片
     * @author 潘宗磊
     */
    public function action_getCompanyCertsByPorjecid()
    {
        //返回结果
        $result = '';
        //错误返回空
        $error_result = "";
        $project_id = $this->request->query("projectid");
        if (!intval($project_id)) {
            echo $error_result;
            exit;
        }
        $service = new Service_Platform_Project();
        //项目基本信息
        $iscompany = $service->getUseridByProjectID($project_id);
        if ($this->isLogins()) {
            if ($iscompany == $this->userId()) {
                $projectinfo = $service->getProjectInfoByIDAll($project_id);
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
            }
        } else {
            $projectinfo = $service->getProjectInfoByID($project_id);
        }
        if ($projectinfo) {
            $result = $service->getProjectCompanysByID($project_id);
            if ($result == "") {
                $result = $service->getProjectImageByIDXml($project_id);
            }
            echo '<content>' . $result . '</content>';
            exit;
        } else {
            echo $error_result;
            exit;
        }
    }

    /**
     * flash接口：根据招商会id获取播报现场图片
     * @author 潘宗磊
     */
    public function action_getBobaoByInvestid()
    {
        //返回结果
        $result = '';
        //错误返回空
        $error_result = "";
        $invest_id = $this->request->query("investid");
        if (!intval($invest_id)) {
            echo $error_result;
            exit;
        }
        $service = new Service_Platform_Project();
        $result = $service->getBobaoImageByIDXml($invest_id);
        echo '<content>' . $result . '</content>';
        exit;
    }

    /**
     * 项目官网资质图片
     * @author 潘宗磊
     */
    public function action_projectCerts_old()
    {
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $service = new Service_Platform_Project();
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
            }
            if ($projectinfo) {
                $content = View::factory('platform/project/projectcerts');
                $this->content->maincontent = $content;
                $content->projectinfo = $projectinfo;
                $content->project_id = $project_id;
                $com_name = $service->getComName($project_id);
                $content->com_name = $com_name;
                //获得项目资质图片
                $certs = $service->getProjectCertsByID($project_id);
                $content->certs = $certs;
                //获得企业资质图片
                $company = $service->getProjectCompanyByID($project_id);
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                //企业基本信息
                $companyinfo = $service->getCompanyByProjectID($project_id);
                //项目来源(1本站，2表示875，3生意街，4外采)
                $content->ispage = $service->isPoster($project_id);
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                //seo优化
                //项目名称+项目资质认证|公司名称+企业资质认证
//                $this->template->title = $projectinfo->project_brand_name.'项目资质认证|'.$companyinfo->com_name.'企业资质认证';
//                $this->template->keywords = $projectinfo->project_brand_name.','.$projectinfo->project_brand_name.'项目资质,'.$companyinfo->com_name.$projectinfo->project_brand_name;
//                $this->template->description =' ';
                $this->template->title = $com_name . "_" . $com_name . "企业营业执照企业资质认证_一句话";
                $this->template->keywords = $com_name . "，" . $com_name . "企业营业执照，" . $com_name . "企业资质认证，一句话";
                $this->template->description = $com_name . "企业营业执照企业资质认证提供" . $com_name . "企业营业执照及" . $com_name . "资质认证，您可以放心的投资加盟，一句话平台担保，无忧加盟。";
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                if ($iscerts['projectcertsStatus'] == false) {
                    $content = View::factory('platform/page_404');
                    $this->content->maincontent = $content;
                }
                $content->company = $company;
                $content->isinvest = $isinvest;
            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
    }

    /**
     * 项目官网海报
     * @author 潘宗磊
     */
    public function action_projectPoster()
    {
        $get = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($get, 'projectid');
        if ($this->t_error) { //显示404页面
        	$this->response->status(404);
        	$view = View::factory("error404");
        	$this->content->maincontent = $view;
        	$this->template->title = "您的访问出错了_404错误提示_一句话";
        	$this->template->keywords = "您的访问出错了,404";
        	$this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $service = new Service_Platform_Project();
            //项目基本信息
            $iscompany = $service->getUseridByProjectID($project_id);
            if ($this->isLogins()) {
                if ($iscompany == $this->userId()) {
                    $projectinfo = $service->getProjectInfoByIDAll($project_id);
                    $isinvest = $service->isProjectInvest($project_id, 1);
                } else {
                    $projectinfo = $service->getProjectInfoByID($project_id);
                    $isinvest = $service->isProjectInvest($project_id);
                }
            } else {
                $projectinfo = $service->getProjectInfoByID($project_id);
                $isinvest = $service->isProjectInvest($project_id);
            }
            if ($projectinfo) {
                $content = View::factory('platform/project/project_poster');
                //$this->content->maincontent = $content;
                $this->content->maincontent->content = $content;
                $content->project_id = $project_id;
                $content->project_brand_name = $projectinfo->project_brand_name;
                //设置默认值
                $content->ispage = false;
                //获得海报信息
                $poster = $service->getProjectPosterByID($project_id);
                //是否有项目图片信息
                $image_arr = $service->getProjectImageByID($project_id);
                if (count($image_arr)) {
                    $content->is_has_image = true;
                } else {
                    $content->is_has_image = false;
                }
                if ($projectinfo->project_source == 4) {
                    $posterModel = new Service_Platform_Poster();
                    $ispage = $posterModel->isCollectPoster($poster['outside_id']);
                    #先去判断外采的海报优先    在去判断本站的制作的海报
                    if ($ispage == true) {
                    	$content->ispage = $ispage;
                        $outPoster = $service->getOutIframe(url::imgurl("poster/html/ps_" . $poster['outside_id'] . "/poster.html"), 'gb2312');
                        $content->outPoster = $outPoster;
                    }else{
                    	#本站海报
                    	$poster_benzhan = ORM::factory("ProjectposterContent", $projectinfo->project_id)->content;
                    	if($poster_benzhan){
                    		$outPoster = $service->getOutIframe(URL::imgurl($poster_benzhan));
                    		$content->outPoster = $outPoster;
                    		$content->ispage = true;
                    	}else{
                    		$content->ispage = false;
                    	}
                    }
                } elseif ($projectinfo->project_source != 4) { 
					if($projectinfo->project_source == 1 || $projectinfo->project_source == 5){
						$poster_benzhan = ORM::factory("ProjectposterContent", $projectinfo->project_id)->content;
						if($poster_benzhan){
							$outPoster = $service->getOutIframe(URL::imgurl($poster_benzhan));
							$content->outPoster = $outPoster;
							$content->ispage = true;
						}else{
							$content->ispage = false;
						}
					}else{
					$poster875 = ORM::factory("ProjectposterContent", $projectinfo->project_id)->content;
                    if ($poster875 != "") {
                    	if($poster875 && $projectinfo->project_source == 2){
                    		if(substr($poster875,-1) != "/"){
                    			$poster875 = $poster875."/";
                    		}
                    	}
                        $outPoster = $service->getOutIframe($poster875);
                        $content->outPoster = $outPoster;
                        $content->ispage = true;
                    } else {
                        $content->ispage = false;
                    }
					}
                    
                }
                $projectModel = new Service_User_Company_Project();
                $iscerts = $projectModel->checkProAllInfo($project_id);
                $content->isCerts = $iscerts['projectcertsStatus'];
                //判断是否是认领成功的项目
                $content->isrenglingok = $service->getRenlingInfoData($project_id);
                $content->poster = $poster;
                $content->isinvest = $isinvest;
                $content->projectinfo = $projectinfo;

                $this->template->title = $projectinfo->project_brand_name . "海报_一句话商机速配网";
                $this->template->keywords = $projectinfo->project_brand_name . "，" . $projectinfo->project_brand_name . '海报，一句话商机速配网';
                $this->template->description = '一句话商机速配网'.$projectinfo->project_brand_name . "海报为您提供漂亮的" . $projectinfo->project_brand_name . "海报宣传图片。让你更有信心加盟" . $projectinfo->project_brand_name . "，" . $projectinfo->project_brand_name . "加盟海报为你提供最好的加盟信息。";
            } else {
                $content = View::factory('platform/page_404');
                $this->content->maincontent = $content;
            }
        }
       }
        
    }

    /**
     * sso
     * 项目认领信息
     * @author 钟涛
     */
    public function action_renlingProjectInfo()
    {
        $content = View::factory("platform/project/renlinginfo");
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $session = Session::instance();
            //创建session，为以后用来获得project_id
            $session->set("renling_project_id" . $this->userId(), $project_id);
            $service = new Service_User_Company_Project();
            //取得项目图片信息
            $get_com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find();
            $res = $service->getRenlingProjectImagAll($project_id, $get_com_id->com_id);
			//@sso 赵路生 2013-11-12
            $com_user = new Service_User_Company_User();
            $company =$com_user->getCompanyInfo($this->userId());
            $renlingmodel = ORM::factory('ProjectRenling')->where('com_id', '=', $company->com_id)->where('project_id', '=', $project_id)->find();
            $content->project_id = $project_id;
            $content->list = $res['list'];
            $content->renlinglist = $renlingmodel->as_array();
            $content->pro_list = $res['list'];
            $content->page = $res['page'];
            $this->content->maincontent = $content;
        }
    }

    /**
     * 项目认领信息修改
     * @author 钟涛
     */
    public function action_updateProjectInfo()
    {
        $content = View::factory("platform/project/updaterengling");
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $session = Session::instance();
            //创建session，为以后用来获得project_id
            $session->set("renling_project_id" . $this->userId(), $project_id);
            $service = new Service_User_Company_Project();
            //取得项目图片信息
            $get_com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find();
            $res = $service->getRenlingProjectImagAll($project_id, $get_com_id->com_id);
			//@sso 赵路生  2013-11-12
            $com_user_ser = new Service_User_Company_User();          
            $company = $com_user_ser->getCompanyInfo($this->userId());
            $renlingmodel = ORM::factory('ProjectRenling')->where('com_id', '=', $company->com_id)->where('project_id', '=', $project_id)->find();
            $content->project_id = $project_id;
            $content->list = $res['list'];
            $content->renlinglist = $renlingmodel->as_array();
            if ($renlingmodel->project_status == 1) { //已经审核通过
                echo '当前项目已经认领审核通过啦，无需再次修改信息！';
                exit;
            }
            $content->pro_list = $res['list'];
            $content->page = $res['page'];
            $this->content->maincontent = $content;
        }
    }

    /**
     * 项目认领步骤1-验证手机号码
     * @author 钟涛
     */
    public function action_claimPhone()
    {
        $service_user = Service::factory("User");
        $user = $this->userInfo();
        //默认第一步
        $content = View::factory("platform/project/valid_mobile_one");
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $content->project_id = $project_id;
            $content->mobile = $user->mobile;
            //通过验证的显示模板
            if ($user->valid_mobile and $this->request->query("to") != "change") {
                $content = View::factory("platform/project/valid_mobile_success");
                $content->mobile = $user->mobile;
                $content->project_id = $project_id;
            }
            $this->content->maincontent = $content;
        }
    }

    /**
     * sso
     * 项目认领步骤2-企业资质认证
     * @author 钟涛
     */
    public function action_comCertification()
    {
        //$user = ORM::factory("user", $this->userId());
        //@sso 赵路生  2013-11-12
		$com_user_ser = new Service_User_Company_User();          
		$company = $com_user_ser->getCompanyInfo($this->userId());
        $content = View::factory("platform/project/uploadcertification");
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $content->project_id = $project_id;
            $commonimg_list = ORM::factory("CommonImg")->where('table_name', '=', 1)->where('user_id', '=', $this->userId())->find_all();
            $commonimg = array();
            foreach ($commonimg_list as $k => $v) {
                $commonimg[$v->field_name][$k]['common_img_id'] = $v->common_img_id;
                $commonimg[$v->field_name][$k]['url'] = URL::imgurl(str_replace('/b_', '/s_', $v->url));
            }
            //工商营业执照上传图片总数
            $com_business_licence = isset($commonimg['com_business_licence']) ? count($commonimg['com_business_licence']) : 0;
            //税务登记证上传图片总数
            $tax_certificate = isset($commonimg['tax_certificate']) ? count($commonimg['tax_certificate']) : 0;
            //组织机构代码证上传图片总数
            $organization_credit = isset($commonimg['organization_credit']) ? count($commonimg['organization_credit']) : 0;
            if ($com_business_licence == 0 || $organization_credit == 0) { //没有上传图片
                $content->is_has_img = true;
            } else {
                $content->is_has_img = false;
            }
            //税务登记证审核状态
            $content->tax_certificate_status = $company->tax_certificate_status;
            //工商营业执照审核状态
            $content->com_business_licence_status = $company->com_business_licence_status;
            //组织机构代码证审核状态
            $content->organization_credit_status = $company->organization_credit_status;

            $content->commonimg_list = $commonimg;
            //工商营业执照上传图片总数
            $content->com_business_licence = isset($commonimg['com_business_licence']) ? count($commonimg['com_business_licence']) : 0;
            //税务登记证上传图片总数
            $content->tax_certificate = isset($commonimg['tax_certificate']) ? count($commonimg['tax_certificate']) : 0;
            //组织机构代码证上传图片总数
            $content->organization_credit = isset($commonimg['organization_credit']) ? count($commonimg['organization_credit']) : 0;
            //审核未通过原因
            $content->com_auth_unpass_reason = $company->com_auth_unpass_reason;
            if ($company->tax_certificate_status == 2 || $company->com_business_licence_status == 2 || $company->organization_credit_status == 2) {
                $content->is_has_img_no = true; //审核不通过
            } else {
                $content->is_has_img_no = false;
            }
            $this->content->maincontent = $content;
        }
    }

    /**
     * 项目认领步骤3-填写认领信息
     * @author 钟涛
     */
    public function action_writeProjectInfo()
    {
        $content = View::factory("platform/project/renlingedit");
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404');
            $this->content->maincontent = $content;
        } else {
            $session = Session::instance();
            //创建session，为以后用来获得project_id
            $session->set("renling_project_id" . $this->userId(), $project_id);
            $service = new Service_User_Company_Project();
            //取得项目图片信息
            $res = $service->getRenlingProjectImagAll($project_id);
           //@sso 赵路生  2013-11-12
			$com_user_ser = new Service_User_Company_User();          
			$company = $com_user_ser->getCompanyInfo($this->userId());
            $renlingmodel = ORM::factory('ProjectRenling')->where('com_id', '=', $company->com_id)->where('project_id', '=', $project_id)->find();
            $content->project_id = $project_id;
            $content->list = $res['list'];
            $content->renlinglist = $renlingmodel->as_array();
            $content->pro_list = $res['list'];
            $content->page = $res['page'];
            $this->content->maincontent = $content;
        }
    }

    /**
     * 项目认领步骤3-插入项目图片
     * @author 钟涛
     */
    public function action_addRenlingProjectImage()
    {
        $session = Session::instance();
        $pro_ser = new Service_Platform_Project();
        $form = Arr::map("HTML::chars", $this->request->post());
        if (arr::get($form, 'data')) {
            $data['img'] = explode('||', arr::get($form, 'data'));
            $data['name'] = explode('||', arr::get($form, 'txt'));
            if ($session->get("renling_project_id" . $this->userId())) {
                $data['project_id'] = $session->get("renling_project_id" . $this->userId());
                $get_com_id = ORM::factory('Usercompany')->where('com_user_id', '=', $this->userId())->find();
                $data['com_id'] = $get_com_id->com_id;
                $res = $pro_ser->addRenlingProjectImages($data);
                if ($res != false) {
                    echo $res;
                    exit;
                } else {
                    echo '';
                    exit;
                }
            }
        } else {
            echo '';
            exit;
        }
    }

    /**
     * sso
     * 项目认领步骤3-插入认领信息数据
     * @author 钟涛
     */
    public function action_addRenlingProjectInfo()
    {
        //$user = ORM::factory("user", $this->userId());
        //@sso 赵路生  2013-11-12
		$com_user_ser = new Service_User_Company_User();
		$user = $this->userInfo(); //获得该登陆用户的基本信息
		$company = $com_user_ser->getCompanyInfo($user->user_id);
        if ($company->com_id == NULL) { //没有完善企业基本信息
            $ormModel = ORM::factory('Companyinfo');
            $ormModel->com_user_id = $user->user_id;
            $ormModel->com_name = $user->user_name;
            $returndata = $ormModel->create();
            $com_id = $returndata->com_id;
        } else {
            $com_id = $company->com_id;
        }
        $user = $this->userInfo(); //获得该登陆用户的基本信息
        //如果没有通过手机验证
        if (!$user->valid_mobile) {
            self::redirect("platform/project/claim" . '?project_id=' . $project_id);
            exit;
        }
        $query_data = Arr::map("HTML::chars", $this->request->query());
        $project_id = arr::get($query_data, 'project_id', '');
        if (!intval($project_id)) {
            self::redirect("platform/project/claim" . '?project_id=' . $project_id);
        }
        $getdata = Arr::map("HTML::chars", $this->request->post());
        $valid_post = Validation::factory($getdata);
        //进行提交的数据后台验证
        $valid_post->rule("projectname",
            function (Validation $valid_error, $field, $value) {
                if (!Valid::not_empty($value)) {
                    $valid_error->error($field, '请输入项目名称');
                    return false;
                } else {
                    return true;
                }
            },
            array(':validation', ':field', ':value')

        )
            ->rule("companyename",
                function (Validation $valid_error, $field, $value) {
                    if (!Valid::not_empty($value)) {
                        $valid_error->error($field, '请输入公司名称');
                        return false;
                    } else {
                        return true;
                    }
                },
                array(':validation', ':field', ':value')

            )
            ->rule("mainproject",
                function (Validation $valid_error, $field, $value) {
                    if (!Valid::not_empty($value)) {
                        $valid_error->error($field, '请输入主营产品');
                        return false;
                    } else {
                        return true;
                    }
                },
                array(':validation', ':field', ':value')

            )
            ->rule("com_phone",
                function (Validation $valid_error, $field, $value) {
                    if (!Valid::not_empty($value)) {
                        $valid_error->error($field, '请输入您常用的手机号码或座机号码');
                        return false;
                    } else {
                        return true;
                    }
                },
                array(':validation', ':field', ':value')

            )
            ->rule("com_contact",
                function (Validation $valid_error, $field, $value) {
                    if (!Valid::not_empty($value)) {
                        $valid_error->error($field, '请输入联系人');
                        return false;
                    } else {
                        return true;
                    }

                },
                array(':validation', ':field', ':value')

            );

        if (!$valid_post->check()) {
            self::redirect("platform/project/claim" . '?project_id=' . $project_id);
        }

        //资质图片上传
        if (isset($getdata['certs1']) || isset($getdata['certs2'])) {
            //@sso 赵路生  2013-11-12
			$com_user_ser = new Service_User_Company_User();          
			$company = $com_user_ser->getCompanyInfo($this->userId());
            $service = Service::factory('User_Company_User');
            $field_name = "";
            foreach ($getdata as $k => $v) {
                if ($k == 'certs1') {
                    $field_name = "com_business_licence";
                } elseif ($k == 'certs2') {
                    $field_name = "organization_credit";
                }
                $service->uploadCertification($v, $field_name, $this->userId(), $company, 0);
            }
        }

        $session = Session::instance();
        //创建session，为以后用来获得project_id
        $session->set("renling_project_id" . $this->userId(), $project_id);
        if ($this->request->method() == HTTP_Request::POST) {
            $pro_ser = new Service_Platform_Project();
            $projectinfo = ORM::factory('Project', $project_id);
            $addresult = $pro_ser->addProjectRenlin($project_id, $com_id, $projectinfo->com_id, $getdata);
            if ($addresult['status']) {
                self::redirect("company/member/project/showprojectrenling?tpye=renling");
            } else {
                self::redirect("platform/project/claim" . '?project_id=' . $project_id);
            }
        }
    }

    /**
     * sso
     * 项目认领步骤整合-验证手机号码(如有不显示) 企业资质认证(如有不显示) 填写认领信息
     * @author 钟涛
     */
    public function action_claim()
    {
        //判断用户类型
        $usertpye = $this->userInfo()->user_type;
        if ($usertpye != 1) { //只能企业用户去认领
            self::redirect("person/member");
            exit;
        }
        $service_user = Service::factory("User");
        $user = $this->userInfo(); //获得该登陆用户的基本信息
        $content = View::factory("platform/project/renlingedit");
        //默认第一步
        $query_data = Arr::map("HTML::chars", $this->request->query()); //过滤
        $project_id = arr::get($query_data, 'project_id', ''); //获取project_id,并且默认值为空
        if (!intval($project_id)) {
            $content = View::factory('platform/page_404'); //获取404页面
            $this->content->maincontent = $content;
        } else {
            //通过验证的显示模板
            if ($user->valid_mobile) {
                $content->is_mobile = true;
            } else {
                $content->is_mobile = false;
            }
            $content->mobile = $this->userInfo()->mobile;
            //企业图片未上传的
            //@sso 赵路生 2013-11-11
            //$company = $user->user_company;
            $company = $this->userInfo(true)->user_company; //这里是使用的ORM的那种模式来进行访问的 通过user表has_one user_company这种对应关系来寻找的
//             $commonimg_list = ORM::factory("CommonImg")->where('table_name','=',1)->where('user_id','=',$this->userId())->find_all(); //获取图片列表
//             $commonimg = array();
//             foreach ($commonimg_list as $k=>$v){
//                 $commonimg[$v->field_name][$k]['common_img_id'] = $v->common_img_id;
//                 $commonimg[$v->field_name][$k]['url'] = URL::imgurl(str_replace('/b_','/s_', $v->url));
//             }
//             //工商营业执照上传图片总数
//             $com_business_licence=isset($commonimg['com_business_licence']) ? count($commonimg['com_business_licence']) : 0;
//             //税务登记证上传图片总数
//             $tax_certificate=isset($commonimg['tax_certificate']) ? count($commonimg['tax_certificate']) : 0;
//             //组织机构代码证上传图片总数
//             $organization_credit=isset($commonimg['organization_credit']) ? count($commonimg['organization_credit']) : 0;
//             if($com_business_licence==0||$organization_credit==0){//没有上传图片
//                 $content->is_not_has_img=true;
//             }else{
//                 $content->is_not_has_img=false;
//             }
//             $content->com_business_licence=$com_business_licence;
//             $content->organization_credit=$organization_credit;
//             $content->tax_certificate=$tax_certificate;
            //填写认领信息
            $session = Session::instance();
            //创建session，为以后用来获得project_id
            $session->set("renling_project_id" . $this->userId(), $project_id); //session添加数据
            $service = new Service_User_Company_Project();
            $res = $service->getRenlingProjectImagAll($project_id, $company->com_id);
            //@sso 赵路生  2013-11-12
			$com_user_ser = new Service_User_Company_User();          
			$company = $com_user_ser->getCompanyInfo($this->userId());
            $renlingmodel = ORM::factory('ProjectRenling')->where('com_id', '=', $company->com_id)->where('project_id', '=', $project_id)->find();
            $content->project_id = $project_id;
            $content->list = $res['list'];
            $content->renlinglist = $renlingmodel->as_array();
            $content->pro_list = $res['list'];
            $content->page = $res['page'];
            $this->content->maincontent = $content;
        }
    }


    /**
     * 项目官网资讯详情页面
     * @author 许晟玮
     */
    public function action_zixuninfo()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
        	$this->response->status(404);
        	$view = View::factory("error404");
        	$this->content->maincontent = $view;
        	$this->template->title = "您的访问出错了_404错误提示_一句话";
        	$this->template->keywords = "您的访问出错了,404";
        	$this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $content = View::factory('platform/project/projectzixun_info');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            //项目访问量统计
            $service->insertProjectStatistics($project_id);
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            $projectservice = new Service_User_Company_Project();
            //人脉关系
            //$content->connection = $projectservice->getProjectConnection($project_id);
            //项目行业
            $pro_industry = $projectservice->getProjectindustryAndId($project_id);
            //意向行业人数[当前项目的总浏览量]
            $pro_industry_count = $projectservice->getPvCountByProjectid($project_id);
            //申请加盟者人数
            $cardser = new Service_Card();
            $jiaomeng_count = $cardser->getJiamengCountAll($project_id);
            //企业用户id
            $com_user_id = $service->getUseridByProjectID($project_id);
            //招商地区
            $pro_area = $projectservice->getProjectArea($project_id);
            //取得人群
            $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
            if (count($group_text) == 0) {
                $content->group_text = "不限";
            } else {
                $content->group_text = $group_text;
            }
            $content->monarr = common::moneyArr();
            //是否有项目图片信息
            $xuanchuanimage = "";
            $image_arr = $projectservice->getXuanChuanPic($project_id);

            if (is_array($image_arr) && !empty($image_arr)) {
                foreach ($image_arr as $key => $val) {
                    if ($val['project_type'] == intval(4)) {
                        $xuanchuanimage = $val['project_img'];
                    }
                }
            }
            if ($xuanchuanimage != '') {
                $content->is_has_image = true;
                $content->bigimage = url::imgurl($xuanchuanimage);
            } else {
                $content->is_has_image = false;
                $content->bigimage = '';
            }
            //招商形式
            $projectcomodel = $projectservice->getProjectCoModel($project_id);
            $content->projectcomodel = $projectcomodel;

            //投资行业[纯中文显示]
            $industry_zhong = '';
            if (arr::get($pro_industry, 'one_name', '')) {
                $industry_zhong .= arr::get($pro_industry, 'one_name', '');
            }
            if (arr::get($pro_industry, 'two_name', '')) {
                $industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
            }
            //招商地区[纯中文显示]
            $area_zhong = '';
            if (count($pro_area) && is_array($pro_area)) {
                $area = '';
                foreach ($pro_area as $v) {
                    $area = $area . $v . ',';
                }
                $area = substr($area, 0, -1);
                if (mb_strlen($area) > 16) {
                    $area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
                } else {
                    $area_zhong = $area;
                }
            } else {
                $area_zhong = $pro_area;
            }

            //判断是否是认领成功的项目
            $content->isrenglingok = $service->getRenlingInfoData($project_id);
            //获取投资保障状态
            try {
                $service_status = new Service_User_Company_ComStatus();
                $rs_all_server = $service_status->getCompanyStatusInfo($com_user_id, "all");
                $p_status = $projectinfo->project_source;
                if (($p_status == "4" || $p_status == "5") && $this->t_renlinginfo == 0) {
                    $rs_all_server['base'] = "2";
                    $rs_all_server['quality'] = "1";
                    $rs_all_server['safe'] = "2";
                    $rs_all_server['server'] = "2";
                }
                if ($p_status == "2" || $p_status == "3") {
                    $rs_all_server['server'] = "2";
                }
                $content->server_status_all = $rs_all_server;
            } catch (Exception $e) {
            }

            //是否有公司信息
            if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
                $content->is_has_company = true;
            } else {
                $content->is_has_company = false;
            }

            //根据项目ID返回对应企业用户登录userid
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = $this->t_renlinginfo;
            if ($this->isLogins()) {
                if ($projectinfo && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) && $renlingmodel == 0) { //是中国加盟网的项目显示认领图标
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            } else {
                if ($projectinfo && $renlingmodel == 0 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5)) { //没有被认领成功
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            }
            #获取项目宣传图
            $model = new Service_Platform_Search();
            $xuanchuan = $model->getProjectXuanChuanImage($project_id,intval(5));
            $content->xuanchuan_project_logo = $xuanchuan;

            //获取热门项目
            $top_projectct = $projectservice->getTopByIndustry($project_id);
            //获取项目的历史咨询 //@赵路生
            $history_consult = $service->getProjectHistoryConsult($project_id);
            $content->top_projectct = $top_projectct;

            //项目对应的文章列表
            $service_article = new Service_News_Article();
            $result_article = $service_article->getProjectArticleList($project_id, 10);
            $article_list = $result_article['list'];
            $article_page = $result_article['page'];
            $content->article_list = $article_list;
            $content->article_page = $article_page;

            //项目行业对应的行业新闻列表 @花文刚
            $industry_article_list = array();
            if(!empty($pro_industry) && isset($pro_industry['one_id']) && $pro_industry['one_id']>0){
                //暂时只取一级行业的新闻 @花文刚
                $industry_article = $service_article->getIndustryNews(0,$pro_industry['one_id'],"hyxw_xm",10);
                $industry_article_list = $industry_article['list'];
            }
            $content->industry_article_list = $industry_article_list;

            $content->industry_zhong = $industry_zhong; //行业汉字组合
            $content->area_zhong = $area_zhong; //地区汉字组合
            $content->projectinfo = $projectinfo; //项目信息
            $content->companyinfo = $companyinfo; //公司信息
            $content->pro_industry = $pro_industry; //行业信息
            $content->pro_industry_count = $pro_industry_count; //意向加盟数量
            $content->jiaomeng_count = $jiaomeng_count; //申请加盟数量
            $content->history_consult = $history_consult; //官网项目的历史咨询
            //项目对应的文章详情
            $service_article = new Service_News_Article();
            $get = Arr::map("HTML::chars", $this->request->query());
            $aid = Arr::get($get, 'id');
            $rs_article = $service_article->getInfoRow($aid);
            if ($rs_article === false) {
                self::redirect("/project/{$project_id}.html");
            } else {
                //是否是这个项目的新闻
                $rs_ainfo = $service_article->getProjectZixunRow($aid);
                if (ceil($rs_ainfo['project_id']) != ceil($project_id)) {
                    self::redirect("/project/{$project_id}.html");
                } else {

                }
                $content->article = $rs_article;
                //相关新闻(当前用户发布的)
                $article_uid = ceil($rs_article['user_id']);
                $rsua = $service_article->getUserTouGaoZixunListProjectInfo($article_uid, 15, $project_id);
                $rsua_list = $rsua['list'];
                $content->rsua_list = $rsua_list;
                $memcache = Cache::instance('memcache');
                //获取当前资讯的PV数
                $pv_count = $memcache->get("cache_zixun_pv_count_" . $aid);
                if (empty($pv_count)) {
                    $zixun_api_service = new Service_Api_Zixun();
                    $pv_count_rs = $zixun_api_service->getPvNum($aid);
                    $pv_count = $pv_count_rs['data'];
                    $memcache->set("cache_zixun_pv_count_" . $aid, $pv_count, 86400);
                }

                // @花文刚
                $content->pv = ceil($pv_count + $rs_article['article_onlooker']);

            }

            //基业给的项目logo
            $projectlogonew=$model->replace_project_logo($projectinfo->project_source , $projectinfo->project_logo, $projectinfo->outside_id);
            $content->projectlogonew = URL::imgurl($projectlogonew);


            //seo优化
            $this->template->title = "{$rs_article['article_name']}_{$projectinfo->project_brand_name}最新新闻_一句话网";
            $this->template->keywords = "{$rs_article['article_name']},{$rs_article['article_tag']},一句话";
            $this->template->description = "{$projectinfo->project_brand_name}最新新闻{$rs_article['article_name']}，" . UTF8::substr($rs_article['article_content'], 0, 100);


        }
    }


    /**
     * 项目官网新闻列表
     * 修改这个action  记得修改  action_zixuninfo-》项目资讯详情
     * @author 钟涛
     */
    public function action_projectzixunlist()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
        	$this->response->status(404);
        	$view = View::factory("error404");
        	$this->content->maincontent = $view;
        	$this->template->title = "您的访问出错了_404错误提示_一句话";
        	$this->template->keywords = "您的访问出错了,404";
        	$this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $content = View::factory('platform/project/project_zixun_list');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            $projectservice = new Service_User_Company_Project();
            //人脉关系
            //$content->connection = $projectservice->getProjectConnection($project_id);
            //项目行业
            $pro_industry = $projectservice->getProjectindustryAndId($project_id);
            //意向行业人数[当前项目的总浏览量]
            $pro_industry_count = $projectservice->getPvCountByProjectid($project_id);
            //申请加盟者人数
            $cardser = new Service_Card();
            $jiaomeng_count = $cardser->getJiamengCountAll($project_id);
            //企业用户id
            $com_user_id = $service->getUseridByProjectID($project_id);
            //招商地区
            $pro_area = $projectservice->getProjectArea($project_id);
            //取得人群
            $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
            if (count($group_text) == 0) {
                $content->group_text = "不限";
            } else {
                $content->group_text = $group_text;
            }
            $content->monarr = common::moneyArr();
            //是否有项目图片信息
            $xuanchuanimage = "";
            $image_arr = $projectservice->getXuanChuanPic($project_id);

            if (is_array($image_arr) && !empty($image_arr)) {
                foreach ($image_arr as $key => $val) {
                    if ($val['project_type'] == intval(4)) {
                        $xuanchuanimage = $val['project_img'];
                    }
                }
            }
            if ($xuanchuanimage != '') {
                $content->is_has_image = true;
                $content->bigimage = url::imgurl($xuanchuanimage);
            } else {
                $content->is_has_image = false;
                $content->bigimage = '';
            }
            //招商形式
            $projectcomodel = $projectservice->getProjectCoModel($project_id);
            $content->projectcomodel = $projectcomodel;

            //投资行业[纯中文显示]
            $industry_zhong = '';
            if (arr::get($pro_industry, 'one_name', '')) {
                $industry_zhong .= arr::get($pro_industry, 'one_name', '');
            }
            if (arr::get($pro_industry, 'two_name', '')) {
                $industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
            }
            //招商地区[纯中文显示]
            $area_zhong = '';
            if (count($pro_area) && is_array($pro_area)) {
                $area = '';
                foreach ($pro_area as $v) {
                    $area = $area . $v . ',';
                }
                $area = substr($area, 0, -1);
                if (mb_strlen($area) > 16) {
                    $area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
                } else {
                    $area_zhong = $area;
                }
            } else {
                $area_zhong = $pro_area;
            }

            //判断是否是认领成功的项目
            $content->isrenglingok = $service->getRenlingInfoData($project_id);
            //获取投资保障状态
            try {
                $service_status = new Service_User_Company_ComStatus();
                $rs_all_server = $service_status->getCompanyStatusInfo($com_user_id, "all");
                $p_status = $projectinfo->project_source;
                if (($p_status == "4" || $p_status == "5") && $this->t_renlinginfo == 0) {
                    $rs_all_server['base'] = "2";
                    $rs_all_server['quality'] = "1";
                    $rs_all_server['safe'] = "2";
                    $rs_all_server['server'] = "2";
                }
                if ($p_status == "2" || $p_status == "3") {
                    $rs_all_server['server'] = "2";
                }
                $content->server_status_all = $rs_all_server;
            } catch (Exception $e) {
            }

            //是否有公司信息
            if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
                $content->is_has_company = true;
            } else {
                $content->is_has_company = false;
            }

            //根据项目ID返回对应企业用户登录userid
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = $this->t_renlinginfo;
            if ($this->isLogins()) {
                if ($projectinfo && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) && $renlingmodel == 0) { //是中国加盟网的项目显示认领图标
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            } else {
                if ($projectinfo && $renlingmodel == 0 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5)) { //没有被认领成功
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            }
            #获取项目宣传图
            $model = new Service_Platform_Search();
            $xuanchuan = $model->getProjectXuanChuanImage($project_id,intval(5));
            $content->xuanchuan_project_logo = $xuanchuan;

            //获取热门项目
            $top_projectct = $projectservice->getTopByIndustry($project_id);
            //获取最新的项目
            $newtop_projectct=$projectservice->getTopNew10ByIndustry($project_id);
            //获取项目的历史咨询 //@赵路生
            $history_consult = $service->getProjectHistoryConsult($project_id);
            $content->top_projectct = $top_projectct;
            $content->newtop_projectct = $newtop_projectct;

            $content->industry_zhong = $industry_zhong; //行业汉字组合
            $content->area_zhong = $area_zhong; //地区汉字组合
            $content->projectinfo = $projectinfo; //项目信息
            $content->companyinfo = $companyinfo; //公司信息
            $content->pro_industry = $pro_industry; //行业信息
            $content->pro_industry_count = $pro_industry_count; //意向加盟数量
            $content->jiaomeng_count = $jiaomeng_count; //申请加盟数量
            $content->history_consult = $history_consult; //获取该项目的历史咨询

            //项目对应的文章列表
            $service_article = new Service_News_Article();
            $result_article = $service_article->getProjectArticleList($project_id, 10);
            $article_list = $result_article['list'];
            $article_page = $result_article['page'];
            $content->article_list = $article_list;
            $content->article_page = $article_page;

            //项目行业对应的行业新闻列表 @花文刚
            $industry_article_list = array();
            if(!empty($pro_industry) && isset($pro_industry['one_id']) && $pro_industry['one_id']>0){
                //暂时只取一级行业的新闻 @花文刚
                $industry_article = $service_article->getIndustryNews(0,$pro_industry['one_id'],"hyxw_xm",10);
                $industry_article_list = $industry_article['list'];
            }
            $content->industry_article_list = $industry_article_list;

            //当前页数
            $get = Arr::map("HTML::chars", $this->request->query());
            $nowpage = Arr::get($get, 'page', 0);
            $content->nowpage = $nowpage;

            $memcache = Cache::instance('memcache');
            if($nowpage == 0){
                $article_list_menu = $article_list;
                $industry_article_list_menu = $industry_article_list;
                $memcache->set("article_list_project_".$project_id, $article_list_menu, $this->_cache_get_total_time);
                $memcache->set("industry_article_list_project_".$project_id, $industry_article_list_menu, $this->_cache_get_total_time);
            }else{
                $article_list_menu =  $memcache->get("article_list_project_".$project_id);
                $industry_article_list_menu =  $memcache->get("industry_article_list_project_".$project_id);
            }
            $content->article_list_menu = $article_list_menu;
            $content->industry_article_list_menu = $industry_article_list_menu;

            //基业给的项目logo
            $projectlogonew=$model->replace_project_logo($projectinfo->project_source , $projectinfo->project_logo, $projectinfo->outside_id);
            $content->projectlogonew = URL::imgurl($projectlogonew);
            $com_name = empty($companyinfo->com_name) ? '' : $companyinfo->com_name;
            if (ceil($nowpage) > 0) {
                $this->template->title = "第{$nowpage}页{$projectinfo->project_brand_name}最新新闻_{$projectinfo->project_brand_name}品牌新闻专区_一句话网";
                $this->template->keywords = "第{$nowpage}页,{$projectinfo->project_brand_name}最新新闻,{$projectinfo->project_brand_name}品牌新闻,{$projectinfo->project_brand_name}新闻专区,一句话";
                $this->template->description = "一句话网{$projectinfo->project_brand_name}品牌新闻专区提供第{$nowpage}页{$projectinfo->project_brand_name}最新新闻、最新{$projectinfo->project_brand_name}新闻，{$com_name}针对{$projectinfo->project_brand_name}进行了大量的品牌故事、品牌资讯、品牌新闻宣传，引导用户更好的加盟{$projectinfo->project_brand_name}";
            } else {
                //seo优化
                $this->template->title = "{$projectinfo->project_brand_name}最新新闻_{$projectinfo->project_brand_name}品牌新闻专区_一句话网";
                $this->template->keywords = "{$projectinfo->project_brand_name}最新新闻,{$projectinfo->project_brand_name}品牌新闻,{$projectinfo->project_brand_name}新闻专区,一句话";
                $this->template->description = "一句话网{$projectinfo->project_brand_name}品牌新闻专区提供{$projectinfo->project_brand_name}最新新闻、最新{$projectinfo->project_brand_name}新闻，{$com_name}针对{$projectinfo->project_brand_name}进行了大量的品牌故事、品牌资讯、品牌新闻宣传，引导用户更好的加盟{$projectinfo->project_brand_name}。";
            }


        }
    }

    /**
     * 项目官网行业新闻列表
     * @author 花文刚
     */
    public function action_industryzixunlist()
    {
        $project_id = $this->t_projectid;
        if ($this->t_error) { //显示404页面
            $this->response->status(404);
            $view = View::factory("error404");
            $this->content->maincontent = $view;
            $this->template->title = "您的访问出错了_404错误提示_一句话";
            $this->template->keywords = "您的访问出错了,404";
            $this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
        } else {
            $content = View::factory('platform/project/industry_zixun_list');
            $this->content->maincontent->content = $content;
            $service = new Service_Platform_Project();
            //项目基本信息
            $projectinfo = $this->t_projectinfo;
            //企业基本信息
            $companyinfo = $this->t_companyinfo;
            $projectservice = new Service_User_Company_Project();
            //人脉关系
            //$content->connection = $projectservice->getProjectConnection($project_id);
            //项目行业
            $pro_industry = $projectservice->getProjectindustryAndId($project_id);
            //意向行业人数[当前项目的总浏览量]
            $pro_industry_count = $projectservice->getPvCountByProjectid($project_id);
            //申请加盟者人数
            $cardser = new Service_Card();
            $jiaomeng_count = $cardser->getJiamengCountAll($project_id);
            //企业用户id
            $com_user_id = $service->getUseridByProjectID($project_id);
            //招商地区
            $pro_area = $projectservice->getProjectArea($project_id);
            //取得人群
            $group_text = $projectservice->getProjectCrowdAndId($projectinfo->project_id, $projectinfo->project_groups_label);
            if (count($group_text) == 0) {
                $content->group_text = "不限";
            } else {
                $content->group_text = $group_text;
            }
            $content->monarr = common::moneyArr();
            //是否有项目图片信息
            $xuanchuanimage = "";
            $image_arr = $projectservice->getXuanChuanPic($project_id);

            if (is_array($image_arr) && !empty($image_arr)) {
                foreach ($image_arr as $key => $val) {
                    if ($val['project_type'] == intval(4)) {
                        $xuanchuanimage = $val['project_img'];
                    }
                }
            }
            if ($xuanchuanimage != '') {
                $content->is_has_image = true;
                $content->bigimage = url::imgurl($xuanchuanimage);
            } else {
                $content->is_has_image = false;
                $content->bigimage = '';
            }
            //招商形式
            $projectcomodel = $projectservice->getProjectCoModel($project_id);
            $content->projectcomodel = $projectcomodel;

            //投资行业[纯中文显示]
            $industry_zhong = '';
            if (arr::get($pro_industry, 'one_name', '')) {
                $industry_zhong .= arr::get($pro_industry, 'one_name', '');
            }
            if (arr::get($pro_industry, 'two_name', '')) {
                $industry_zhong .= '、' . arr::get($pro_industry, 'two_name', '');
            }
            //招商地区[纯中文显示]
            $area_zhong = '';
            if (count($pro_area) && is_array($pro_area)) {
                $area = '';
                foreach ($pro_area as $v) {
                    $area = $area . $v . ',';
                }
                $area = substr($area, 0, -1);
                if (mb_strlen($area) > 16) {
                    $area_zhong = mb_substr($area, 0, 16, 'UTF-8') . '...';
                } else {
                    $area_zhong = $area;
                }
            } else {
                $area_zhong = $pro_area;
            }

            //判断是否是认领成功的项目
            $content->isrenglingok = $service->getRenlingInfoData($project_id);
            //获取投资保障状态
            try {
                $service_status = new Service_User_Company_ComStatus();
                $rs_all_server = $service_status->getCompanyStatusInfo($com_user_id, "all");
                $p_status = $projectinfo->project_source;
                if (($p_status == "4" || $p_status == "5") && $this->t_renlinginfo == 0) {
                    $rs_all_server['base'] = "2";
                    $rs_all_server['quality'] = "1";
                    $rs_all_server['safe'] = "2";
                    $rs_all_server['server'] = "2";
                }
                if ($p_status == "2" || $p_status == "3") {
                    $rs_all_server['server'] = "2";
                }
                $content->server_status_all = $rs_all_server;
            } catch (Exception $e) {
            }

            //是否有公司信息
            if ((isset($companyinfo->com_desc) && $companyinfo->com_desc) || $projectinfo->outside_com_introduce) {
                $content->is_has_company = true;
            } else {
                $content->is_has_company = false;
            }

            //根据项目ID返回对应企业用户登录userid
            $iscompany = $service->getUseridByProjectID($project_id);
            $renlingmodel = $this->t_renlinginfo;
            if ($this->isLogins()) {
                if ($projectinfo && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5) && $renlingmodel == 0) { //是中国加盟网的项目显示认领图标
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            } else {
                if ($projectinfo && $renlingmodel == 0 && ($projectinfo->project_source == 4 || $projectinfo->project_source == 5)) { //没有被认领成功
                    $content->isshowrenling = true;
                } else {
                    $content->isshowrenling = false;
                }
            }
            #获取项目宣传图
            $model = new Service_Platform_Search();
            $xuanchuan = $model->getProjectXuanChuanImage($project_id,intval(5));
            $content->xuanchuan_project_logo = $xuanchuan;

            //获取热门项目
            $top_projectct = $projectservice->getTopByIndustry($project_id);
            //获取最新的项目
            $newtop_projectct=$projectservice->getTopNew10ByIndustry($project_id);
            $content->top_projectct = $top_projectct;
            $content->newtop_projectct = $newtop_projectct;

            //获取项目的历史咨询 //@赵路生
            $history_consult = $service->getProjectHistoryConsult($project_id);
            $content->history_consult = $history_consult; //获取该项目的历史咨询

            $content->industry_zhong = $industry_zhong; //行业汉字组合
            $content->area_zhong = $area_zhong; //地区汉字组合
            $content->projectinfo = $projectinfo; //项目信息
            $content->companyinfo = $companyinfo; //公司信息
            $content->pro_industry = $pro_industry; //行业信息
            $content->pro_industry_count = $pro_industry_count; //意向加盟数量
            $content->jiaomeng_count = $jiaomeng_count; //申请加盟数量

            //项目对应的文章列表
            $service_article = new Service_News_Article();
            $result_article = $service_article->getProjectArticleList($project_id, 10);
            $article_list = $result_article['list'];
            $content->article_list = $article_list;

            //项目行业对应的行业新闻列表 @花文刚
            //暂时只取一级行业的新闻 @花文刚
            $industry_article = $service_article->getIndustryNews(0,$pro_industry['one_id'],"hyxw_xm",10);
            $content->industry_article_list = $industry_article['list'];
            $content->article_page = $industry_article['page'];

            //项目行业对应的行业新闻列表 @花文刚
            $industry_article_list = array();
            $industry_article_page = "";
            if(!empty($pro_industry) && isset($pro_industry['one_id']) && $pro_industry['one_id']>0){
                //暂时只取一级行业的新闻 @花文刚
                $industry_article = $service_article->getIndustryNews(0,$pro_industry['one_id'],"hyxw_xm",10);
                $industry_article_list = $industry_article['list'];
                $industry_article_page = $industry_article['page'];
            }
            $content->industry_article_list = $industry_article_list;
            $content->article_page = $industry_article_page;


            //当前页数
            $get = Arr::map("HTML::chars", $this->request->query());
            $nowpage = Arr::get($get, 'page', 0);
            $content->nowpage = $nowpage;

            $memcache = Cache::instance('memcache');
            if($nowpage == 0){
                $article_list_menu = $article_list;
                $industry_article_list_menu = $industry_article_list;
                $memcache->set("article_list_project_".$project_id, $article_list_menu, $this->_cache_get_total_time);
                $memcache->set("industry_article_list_project_".$project_id, $industry_article_list_menu, $this->_cache_get_total_time);
            }else{
                $article_list_menu =  $memcache->get("article_list_project_".$project_id);
                $industry_article_list_menu =  $memcache->get("industry_article_list_project_".$project_id);
            }
            $content->article_list_menu = $article_list_menu;
            $content->industry_article_list_menu = $industry_article_list_menu;

            //基业给的项目logo
            $projectlogonew=$model->replace_project_logo($projectinfo->project_source , $projectinfo->project_logo, $projectinfo->outside_id);
            $content->projectlogonew = URL::imgurl($projectlogonew);

            if (ceil($nowpage) > 0) {
                $this->template->title = "第{$nowpage}页【{$industry_zhong}最新新闻】_{$industry_zhong}新闻录_一句话商机速配网";
                $this->template->keywords = "第{$nowpage}页{$industry_zhong}最新新闻,{$industry_zhong}新闻录,{$industry_zhong}新闻专区,一句话商机速配网";
                $this->template->description = "第{$nowpage}页一句话商机速配网{$industry_zhong}新闻专区提供{$industry_zhong}最新新闻、最新{$industry_zhong}新闻、最新{$industry_zhong}加盟新闻等，一句话网有最新、最及时、最全的{$industry_zhong}新闻。";
            } else {
                //seo优化
                $this->template->title = "【{$industry_zhong}最新新闻】_{$industry_zhong}新闻录_一句话商机速配网";
                $this->template->keywords = "{$industry_zhong}最新新闻,{$industry_zhong}新闻录,{$industry_zhong}新闻专区,一句话商机速配网";
                $this->template->description = "一句话商机速配网{$industry_zhong}新闻专区提供{$industry_zhong}最新新闻、最新{$industry_zhong}新闻、最新{$industry_zhong}加盟新闻等，一句话网有最新、最及时、最全的{$industry_zhong}新闻。";
            }


        }
    }

}