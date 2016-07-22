<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  网络展会
 * @author 花文刚
 */
class Controller_Platform_Exhibition extends Controller_Platform_Template{
	/**
 	* 展会首页
 	* @author 花文刚
 	*/
	public function action_index(){
            
		$content = View::factory('platform/exhibition/index');
		$this->content->maincontent = $content;

                $service = new Service_Platform_Exhibition();
                $memcache = Cache::instance ( 'memcache' );
                $key = 'getExhibitionShow';
                $time = '86400';
                //正在开展中
                $showing = $memcache->get($key.'_showing');
                if(!$showing) {
                    $showing = $service->getExhibitionShow(1);
                    $memcache->set($key.'_showing', $showing, $time);
                }
                if(count($showing) == 1 && arr::get($showing, 0, array())) {
                    self::redirect(urlbuilder::exhbInfo(arr::get($showing[0], 'exhibition_id', 0)));
                }
                //即将开展的
                $to_show = $memcache->get($key.'_to_show');
                if(!$to_show) {
                    $to_show = $service->getExhibitionShow(2);
                    $memcache->set($key.'_to_show', $to_show, $time);
                }
                //已经结束的
                $showed = $memcache->get($key.'_showed');
                if(!$showed) {
                    $showed = $service->getExhibitionShow(3);
                    $memcache->set($key.'_showed', $showed, $time);
                }
                $hongbaoAll = $memcache->get($key.'_getExhbHbCount');
                if(!$hongbaoAll) {
                    $hongbaoAll = $service->getExhbHbCount();
                    $memcache->set($key.'_getExhbHbCount', $hongbaoAll, 120);
                }
                $content->showing = $showing;
                $content->to_show = $to_show;
                $content->showed = $showed;
                $content->hongbaoAll = $hongbaoAll;
                //播报
                $exhb_service = new Service_Platform_ExhbProject();
                //echo "<pre>";print_r($exhb_service->getDuoChangBoBao());exit;
                $content->bobao = $exhb_service->getDuoChangBoBao();
                
        $content->title = "一句话招商加盟展会|连锁加盟_投资创业网络展会服务平台";
      	$content->keywords = "一句话招商加盟展会，连锁加盟展会，投资创业展会";
      	$content->description = "一句话招商加盟网络展会汇集众多最新招商加盟、连锁加盟、投资创业网络展会信息，为企业提供项目展示机会，为投资者速配最合适项目的优质服务平台！";

	}

    /**
     * 展会单页
     * @author 花文刚
     */
    public function action_single(){
        $content = View::factory('platform/exhibition/single');
        $this->content->maincontent = $content;

        $get = Arr::map("HTML::chars", $this->request->query());
        $exhibition_id = arr::get($get, 'exhibition_id');

        $service = new Service_Platform_Exhibition();
        //正在开展中
        $showing = $service->getExhibitionByID($exhibition_id);

        //即将开展的
        $to_show = $service->getExhibitionShow(2);
        //已经结束的
        $showed = $service->getExhibitionShow(3);
        
        $content->showing=$showing;
        $content->to_show=$to_show;
        $content->showed=$showed;
    }
    
    /**
     * 展会详情
     * @author stone shi
     * 
     */
    public function action_exhibition() {
        $get = Arr::map("HTML::chars", $this->request->query());
        $exhibition_id = intval(arr::get($get, 'exhibition_id'));
        if(!$exhibition_id) self::redirect("/platform/exhibition/");
        
        $service = new Service_Platform_Exhibition();
        
        $exhibitionInfo = $service->getExhibitionByID($exhibition_id);
        if(!$exhibitionInfo) self::redirect("/platform/exhibition/");
        $exhibitionCatalog = $service->getExhibitionCatalog($exhibition_id);
        
        #0未开始 1开始 2结束
        $status = 0;
        $nowTime = time();
        
        if(arr::get($exhibitionInfo, 'exhibition_start', 0) < $nowTime && arr::get($exhibitionInfo, 'exhibition_end', 0) > $nowTime) {
            $status = 1;
        }elseif(arr::get($exhibitionInfo, 'exhibition_end', 0) < $nowTime) {
            $status = 2;
        }
        $exhb_service = new Service_Platform_ExhbProject();
        if($status == 1) {
            $content = View::factory('platform/exhibition/exhibition');
            //播报
            //echo "<pre>";print_r($exhb_service->getDanChangBoBao($exhibition_id));exit;
            $content->bobao = $exhb_service->getDanChangBoBao($exhibition_id);   
        }elseif($status == 2) {
            $content = View::factory('platform/exhibition/exhibition_end');
            //播报
            //echo "<pre>";print_r($exhb_service->getLiShiBoBao($exhibition_id));exit;
            $content->bobao = $exhb_service->getLiShiBoBao($exhibition_id);   
        }elseif($status == 0) {
            $content = View::factory('platform/exhibition/exhibition_start');
            //播报
            //echo "<pre>";print_r($exhb_service->getYuGaoBoBao($exhibition_id));exit;
            $content->bobao = $exhb_service->getYuGaoBoBao($exhibition_id);   
        }
        $famousProject = $service->getFamousProject($exhibition_id);
        
        $consultingProject = $service->getConsultingProject($exhibition_id);
        
        $projectCount = $service->getProjectCount($exhibition_id);
        
        $memcache = Cache::instance ( 'memcache' );
                $key = 'getExhibitionShow';
                $time = '86400';
                //正在开展中
                $now_show = $memcache->get($key.'_showing');
                if(!$now_show) {
                    $now_show = $service->getExhibitionShow(1);
                    $memcache->set($key.'_showing', $now_show, $time);
                }
                //即将开展的
                $to_show = $memcache->get($key.'_to_show');
                if(!$to_show) {
                    $to_show = $service->getExhibitionShow(2);
                    $memcache->set($key.'_to_show', $to_show, $time);
                }
                //已经结束的
                $showed = $memcache->get($key.'_showed');
                if(!$showed) {
                    $showed = $service->getExhibitionShow(3);
                    $memcache->set($key.'_showed', $showed, $time);
                }
        
        $newProject = $service->getNewProject($exhibition_id);
        $content->newProject = $newProject;
        $content->famousProject = $famousProject;
        $content->consultingProject = $consultingProject;
        $this->content->maincontent = $content;
        $content->to_show=$to_show;
        $content->now_show = $now_show;
        $content->showed=$showed;
        $content->exhibitionInfo = $exhibitionInfo;
        $content->projectCount = $projectCount;
        $content->exhibitionCatalog = $exhibitionCatalog;
        //用户类型
        $user_type = 0;
        if($this->loginUser()){
        	$service_user = new Service_User();
			//获取登录user_id
	    	$user_id = $this->userid();  
	      	$userinfo = $service_user->getUserInfoById($user_id); 
	      	$user_type = $userinfo->user_type;
        }
        $content->user_type = $user_type;
        $content->to_url = urlencode(urlbuilder::exhbInfo($exhibition_id));
        $exhb_name = arr::get($exhibitionInfo, 'exhibition_name', '');
        $content->title = $exhb_name."_一句话招商展会网";
      	$content->keywords = $exhb_name."，招商加盟展会，一句话招商展会网";
      	$content->description = "一句话招商展会网".$exhb_name;
    }
	/**
	 * 展览栏目
	 * @author jiye
	 */
    public function action_exhibition_column(){
    	
    	//获取展览会的类型
    	$get = Arr::map("HTML::chars", $this->request->query());
    	 //print_R($get);
    	$exhibition_id = intval(arr::get($get,"exhibition_id"));
    	$catalog_id = intval(arr::get($get,"catalog_id"));
    	//使用缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$time = '86400';
    	
    	$service = new Service_Platform_Exhibition();
    	$service_new  = new Service_Platform_ExhbProject();
    	$arr_data = array();
    	$arr_Exhibition_list = $memcache->get("zhanhui_".$exhibition_id);
    	if(arr::get($arr_Exhibition_list, "Exhibition_list")){
    		//echo "<pre>"; print_R($arr_Exhibition_list);exit;
    		$arr_data['Exhibition_list'] = arr::get($arr_Exhibition_list, "Exhibition_list");
    	}else{
    		$arr_data['Exhibition_list'] = $service->getExhibitionByID($exhibition_id);
    		$memcache->set("zhanhui_".$exhibition_id, $arr_data,$time);
    	}
    	//exit;
    	
    	#0未开始 1开始 2结束
    	$status = 0;
    	$nowTime = time();
    	if(arr::get($arr_data['Exhibition_list'], 'exhibition_start', 0) < $nowTime && arr::get($arr_data['Exhibition_list'], 'exhibition_end', 0) > $nowTime) {
    		$status = 1;
    	}elseif(arr::get($arr_data['Exhibition_list'], 'exhibition_end', 0) < $nowTime) {
    		$status = 2;
    	}
    	$display = 1;
    	if($status == 0 || $status == 1){
    		if($status == 0){
    			$display = 2;
    		}
    		$content = View::factory('platform/exhibition/exhibitionColumn');	
    	}
    	if($status == 2){
    		$content = View::factory('platform/exhibition/exhibitionColumnEnd');
    	}
    	$this->content->maincontent = $content;
    	//咨询或者加盟
    	$int_type  = intval(arr::get($get,"type",0));
    	//获取展会信息
    	
    	//展会id
    	$arr_data['exhibition_id'] = $exhibition_id;
    	//展会子类
    	$arr_data['catalog_id'] = $catalog_id;
    	
    	//获取参展项目
    	$arr_data['project_count'] = $service->getProjectCount($exhibition_id);
    	
    	//领取红包的数量
    	$arr_data['hongbao_count'] = $service->get_hongbao_num($exhibition_id);
    	//获取展会的分类数据
    	$arr_catalog = $service->getExhibitionCatalog($exhibition_id);
    	//获取展会的参加人数
    	$arr_data['people_num'] = $service->getExhbPerNum($exhibition_id);
    	//获取显示数据 (麻痹  没有表 数据写死)
    	
    	
    	$key = "zhanhuilist";
    	//获取缓存数据
    	$str_key = "zhanhuilist-".$exhibition_id."-".$catalog_id."-".arr::get($get,"type",0)."-".arr::get($get, "page",1);
    	$arr_memcache_data = $memcache->get($str_key);
    	if(count(arr::get($arr_memcache_data,"list")) == 0){
    		if($catalog_id){
    			//子类
    			$arr_data_ExhbProject = $service->getExhbProjectByCatalogId($catalog_id,$int_type,intval(arr::get($get,"page",0)));
    		}else{
    			//父类
    			$arr_data_ExhbProject = $service->getList($exhibition_id,$int_type,intval(arr::get($get,"page",0)));
    		}
    		$arr_data['count'] = arr::get($arr_data_ExhbProject, "count");
    		$arr_data['page'] = arr::get($arr_data_ExhbProject, "page");
    		$arr_data['list'] = $service->do_list(arr::get($arr_data_ExhbProject,"list",array()));
    		$arr_data['offset_num'] = arr::get($arr_data_ExhbProject, "offset_num");
    		$memcache->set($str_key, $arr_data,$time);
    	}else{
    		$arr_data['count'] = arr::get($arr_memcache_data, "count");
    		$arr_data['page'] = arr::get($arr_memcache_data, "page");
    		$arr_data['list'] = arr::get($arr_memcache_data,"list");
    		$arr_data['offset_num'] = arr::get($arr_memcache_data, "offset_num");
    		//echo "<pre>"; print_r($arr_data);exit;
    	}
    	//用户类型
    	$user_type = 0;
    	if($this->loginUser()){
    		$service_user = new Service_User();
    		//获取登录user_id
    		$user_id = $this->userid();
    		$userinfo = $service_user->getUserInfoById($user_id);
    		$user_type = $userinfo->user_type;
    	}
    	//签约项目数
    	$content->sucprojectnum = $service_new->getSucProjectNum($exhibition_id);
    	//签约人数
    	$content->sucpeoplenum = $service_new->getSucPeopleNum($exhibition_id);
    	//成功率
    	$content->chenggonglu = $service->getExhbSigningCount($exhibition_id,arr::get($arr_data, "people_num","20"));
    	$content->user_type = $user_type;
    	$content->arr_data = $arr_data;
    	$content->arr_catalog = $arr_catalog;
    	$content->page_num = intval(arr::get($get,"page",1));
    	$content->int_type = $int_type;
    	$content->display = $display;
    	$content->to_url = urlencode(urlbuilder::exhbInfo($exhibition_id));
    	//播报
    	$exhb_service = new Service_Platform_ExhbProject();
    	if($status == 0){
    		$content->bobao = $exhb_service->getYuGaoBoBao($exhibition_id);
    	}elseif($status == 1){
    		$content->bobao = $exhb_service->getDanChangBoBao($exhibition_id);   
    	}elseif($status == 2){
    		$content->bobao = $exhb_service->getLiShiBoBao($exhibition_id);
    	}
    	//echo "<pre>"; print_R($display);exit;         	 	       	  	
    	$catalog_name = (isset($arr_catalog[$catalog_id-1]['catalog_name']) && $arr_catalog[$catalog_id-1]['catalog_name']) ? $arr_catalog[$catalog_id-1]['catalog_name'] : '';
    	$content->title = $catalog_name."招商加盟展会_一句话招商加盟展会平台";
      	$content->keywords = $catalog_name."招商加盟，招商加盟展会，一句话招商加盟展会";
      	$content->description = "一句话招商加盟展会平台提供最全".$catalog_name."招商加盟展会信息。";
    }
    
    
   public function action_updateExhbPro() {
       $get = Arr::map("HTML::chars", $this->request->query());
       $token = arr::get($get, 'token');
       $exhbId = arr::get($get, 'exhbId', 1);
       $page = arr::get($get, 'page', 0);
       $catalog_id = arr::get($get, 'catalog_id', 1);
       $tmpToken = 'caonima';
       if($tmpToken != $token) {echo 'token is error';exit;}
       $exhb_service = new Service_Platform_ExhbProject();
       $exhb_service->daoruExhbPro($exhbId, $catalog_id, $page);
   }
}
?>