<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 新平台静态页面
 * @author 钟涛
 *
 */
class Controller_Platform_Static extends Controller_Platform_Template{
    /**
     * 投资宝
     * @author 钟涛
     */
    public function action_touzibao(){
        $content = View::factory('platform/home/touzibao');
        $this->content->maincontent = $content;
        $this->template->title = '诚信保障 先行赔付|一句话 投资赚钱好项目，一句话的事';
        $this->template->keywords = '诚信保障，先行赔付，诚信项目，投资好项目，投资赚钱项目';
        $this->template->description = '一句话，投资宝先行赔付计划由通路快建全新招商平台“一句话”推出的一项针对个人创业者投资保障的服务，通过先行赔付方式保证项目真实、有效，确保投资者投资安全。投资赚钱好项目，一句话的事。';
    }
    /**
     * 进入微信
     * @author 钟涛
     */
    public function action_weixin(){
        $content = View::factory('platform/home/weixin');
        $this->content->maincontent = $content;
        $this->template->title = '进入微信 - 一句话';
        $this->template->keywords = '进入微信,一句话,一句话微信';
        $this->template->description = '通过扫描一句话微信二维码，关注一句话，一句话微信分享更多创业项目。';
    }

    /**
     * 网站地图
     * @author 嵇烨
     */
    public function action_sitemap(){
        #清除memcahe
        $get = Arr::map ( "HTML::chars", $this->request->query () );
        $content = View::factory('platform/home/map');
        $this->content->maincontent = $content;
        $server = new Service_Map_Map();
//        $pageNum = isset($get['page']) ? intval($get['page']) : 1;
//        $arr_caheProject = array();
        #memcahe拿取数据
        $memcache = Cache::instance ( 'memcache' );
//        $arr_caheProject =  $memcache->get("project_info");
//        if(!empty($arr_caheProject)){
//            $arr_newProject = $arr_caheProject;;
//            $page = arr::get($arr_caheProject, 'page', "");
//        }else{
//          #第一次加载数据 拿取最新的1500个
//            $arr_projectList = $server->getProjectInfo();
//            $arr_newProject = $server->dealProject($arr_projectList,$pageNum);
//            $page = arr::get($arr_newProject, 'page', "");
//        }
//        #找分好组的数组
//        $fen_arr_data = array();
//        $arr_datas = array();
//        $fen_arr_data = $memcache->get("fenzu_project_info");
//        if($fen_arr_data){ 
//        	$arr_data = $fen_arr_data;
//        }else{
//        	$arr_datas = @array_chunk($arr_newProject['list'],250);
//        	$memcache->set("fenzu_project_info",$arr_datas,'86400');
//        	$arr_data = $arr_datas;
//        }
		$arr_data = array();
        $content->projectList = $arr_data;
//        $content->page = $page;
        $content->type = "0";
        $content->isdisplay = 1;
        $this->template->title = '网站地图_网站导航图_一句话';
        $this->template->keywords = '网站地图，投资赚钱项目，一句话';
        $this->template->description = ' 一句话网站地图，在这里你可以更方便的找到你需要的产品，更快的找到合适的投资项目。投资好项目赚钱一句话的事。';
        if(isset($get['clear']) == 1){
            $memcache->delete_all();
        }
       
    }
    /**
     * 友情链接
     * @author 嵇烨
     */
    public function action_link(){
        $content = View::factory('platform/home/friendlylink');
        $this->content->maincontent = $content;
        $memcache = Cache::instance ('memcache');
        $friend_link = $memcache->get('friend_cache_youqing'); 
        if(empty($friend_link)){
        	$f_service = new Service_Platform_FriendLink();
        	$friend_link = $f_service->getFriendLinkList('youqing');
        	$memcache->set('friend_cache_youqing', $friend_link,604800);
        }        
        $content->friend_link = $friend_link;
        $this->template->title = "友情链接_合作链接_一句话";
        $this->template->keywords = "友情链接，合作链接，一句话";
        $this->template->description = "友情链接合作、链接合作、网站合作，请联系一句话。";
    }

    /**
     * 地图字符收索
     * @author 嵇烨
     */
    public function action_seachMapByGrapheme(){
        $content = View::factory('platform/home/map');
        $this->content->maincontent = $content;
        $get = Arr::map("HTML::chars", $this->request->query());
        $str_stringname  = $get['type'];
        $pageNum = isset($get['page']) ? intval($get['page']) : 1;
        #开始查找项目
        $server = new Service_Map_Map();
        $arr_caheProject =array();
        #memcahe拿取数据
        $memcache = Cache::instance ( 'memcache' );
        $arr_caheProject =  $memcache->get("project_info_{$str_stringname}_{$pageNum}");
        if(!empty($arr_caheProject)){
            $arr_newProject = $arr_caheProject;
            $page = $arr_caheProject['page'];
        }else{ 
            $seachProjectList = $server->findProjectByGrapheme($str_stringname);
            $arr_newProject = $server->dealProject($seachProjectList,$pageNum,$str_stringname);
            $page = $arr_newProject['page'];
        }
        #去找分好组的数据
        $arr_fenzu_data = array();
        $arr_datas =array();
        $arr_fenzu_data = $memcache->get("fenzu_project_info_{$str_stringname}_{$pageNum}");
        if(empty($arr_fenzu_data)){
        	$arr_datas = @array_chunk($arr_newProject['list'],@floor(count($arr_newProject['list'])/4));
        	$arr_data = $arr_datas;
        	$memcache->set("fenzu_project_info_{$str_stringname}_{$pageNum}", $arr_datas,'86400');
        }else{
        	$arr_data = $arr_fenzu_data;
        }
        $content->projectList = $arr_data;
        $content->page = $page;
        $content->isdisplay = 2;
        $content->type = $str_stringname;
        $this->template->title = '热门关键词字母'.$str_stringname.'分类 第'.$pageNum.'页结果_一句话创业投资平台';
        $this->template->keywords = '关键词'.$str_stringname.'，一句话';
        $this->template->description = '热门关键词字母'.$str_stringname.'分类 第'.$pageNum.'页结果，一句话创业投资平台。';
    }



}
