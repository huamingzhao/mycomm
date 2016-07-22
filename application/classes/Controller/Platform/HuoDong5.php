<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 好项目你评选活动
 * @author 郁政
 */
class Controller_Platform_HuoDong5 extends Controller_Platform_Template{
	/**
	 * 活动页首页
	 * @author 郁政
	 */
	public function action_index(){
		$content = View::factory('platform/huodong/goodprojectindex');
        $this->content->maincontent = $content;        
	}
	
	/**
	 * 活动列表页
	 * @author 郁政
	 */
	public function action_showList(){
		$content = View::factory('platform/huodong/goodprojectlist');
        $this->content->maincontent = $content; 
        $service = new Service_Platform_HuoDong5();
        $cache = Cache::instance("memcache");
        //最佳创业
        $list1 = array();
        $date1 = $cache->get("hd_zuijiachuangye");
        if($date1){
        	$list1 = $date1;
        }else{
        	$list1 = $service->getZuiJiaChuangYeList(34);
        	$cache->set("hd_zuijiachuangye", $list1,3600*24);	
        }        
        //创业首选
        $list2 = array();
        $date2 = $cache->get("hd_chuangyeshouxuan");
        if($date2){
        	$list2 = $date2;
        }else{
        	$list2 = $service->getChuangYeShouXuanList(26);
        	$cache->set("hd_chuangyeshouxuan", $list2,3600*24);
        }        
        //最新商机
        $list3 = array();
        $date3 = $cache->get("hd_zuixinshangji");
        if($date3){
        	$list3 = $date3;
        }else{
        	$list3 = $service->getZuiXinShangJiList(18);
        	$cache->set("hd_zuixinshangji", $list3,3600*24);
        }        
        //精品推荐
        $list4 = array();
        $date4 = $cache->get("hd_jingpintuijian");
        if($date4){
        	$list4 = $date4;
        }else{
        	$list4 = $service->getJingPinTuiJianList(20);
        	$cache->set("hd_jingpintuijian", $list4,3600*24);
        }        
        //知名品牌
        $list5 = array();
        $date5 = $cache->get("hd_zhimingpinpai");
        if($date5){
        	$list5 = $date5;
        }else{
        	$list5 = $service->getZhiMingPinPaiList(17);
        	$cache->set("hd_zhimingpinpai", $list5,3600*24);
        }        
        //商家推荐
        $list6 = array();
        $date6 = $cache->get("hd_shangjiatuijian");
        if($date6){
        	$list6 = $date6;
        }else{
        	$list6 = $service->getShangJiaTuiJianList(11);
        	$cache->set("hd_shangjiatuijian", $list6,3600*24);
        }     
        //播报
        $service_card = new Service_Card();
        $bobaoList = $service_card->getAnnouncementForSpePro();
        $content->bobaolist = $bobaoList;
        $content->list1 = $list1;
        $content->list2 = $list2;
        $content->list3 = $list3;
        $content->list4 = $list4;
        $content->list5 = $list5;
        $content->list6 = $list6;
	}
}
?>