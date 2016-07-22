<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 模板中rightcontent 属性一定要有
 * 资讯模板
 * @author 龚湧
 */
class Controller_News_Template extends Controller_Template{
    /**
    * @var view
    */
    public $content;
    public $title;
    public $description;
    public $keywords;

    /**
     * 企业用户中心
     * @var 二级模板,指定即可
     */
    private $content_template = "news/template";
    private $_new_arctile_paihang = 'new_arctile_paihang';
    private $_new_arctile_paihang_today = 'new_arctile_paihang_today';
    private $_new_arctile_paihang_week = 'new_arctile_paihang_week';
    private $_new_arctile_paihang_month = 'new_arctile_paihang_month';
    private $_cache_get_index_time = 86400;

    public function before(){
        parent::before();
        $this->template->description = '';
        $this->template->keywords = '';
        $this->template->content = $this->content = View::factory($this->content_template);
        //记录contrller_方法名
        $con_method= $this->request->controller().'_'.$this->request->action();
        //设置默认值
        $seo_title=common::getCompanySEOTitle($con_method);
        if($seo_title==''){
            $this->template->title = '企业中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }else{
            $this->template->title = $seo_title.'－企业中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }
        $memcache = Cache::instance ( 'memcache' );
        //设置默认值
        $this->content->rightcontent = '';

        $column = new Service_News_Column();
        $column_menu= $column->getColumnMenu();
        $this->content->column = $column_menu;
		
        //热门标签
        $zxtag_service = new Service_News_Tag();
        //记录contrller_方法名
        $con_method= $this->request->controller().'_'.$this->request->action();        
        if(strpos($con_method, "Index_industrynews") === 0 || strpos($con_method, "Info_industryinfo") === 0 || strpos($con_method, "Search_getHangYeNewsTagList") === 0){
        	$zxtag = '';
        }else{
        	$zxtag = $zxtag_service->tagList();
        }        
        $this->content->tuijiantag = $zxtag;
        $zxarticle = new Service_News_Article();
        $zxapi = new Service_Api_Zixun();

        //右侧围观排行
        $newpaihang = $memcache->get($this->_new_arctile_paihang);
        if(!$newpaihang||arr::get($newpaihang,'paihang_ids_today')==404) {
            $paihang_ids_today = $zxapi->getPvNumDate(strtotime(date('Y-m-d'))-24*60*60,time());

            $paihang_ids_week  = $zxapi->getPvNumDate(strtotime(date('Y-m-d'))-7*24*60*60,time());
            $paihang_ids_month = $zxapi->getPvNumDate(strtotime(date('Y-m-d'))-30*24*60*60,time());

            $newpaihang = array(
                    'paihang_ids_today'=>$paihang_ids_today,
                    'paihang_ids_week'=>$paihang_ids_week,
                    'paihang_ids_month'=>$paihang_ids_month,
                    );
            $memcache->set($this->_new_arctile_paihang, $newpaihang, $this->_cache_get_index_time);
        }

        //echo "<pre>";
        //print_r($newpaihang);

        //24小时排行 放入缓存 节省数据库负荷 @author 花文刚
        $paihang_today = $memcache->get($this->_new_arctile_paihang_today);
        if(empty($paihang_today)){
            if($newpaihang['paihang_ids_today']){
                $paihang_today = $zxarticle->getBiList($newpaihang['paihang_ids_today']);
            }
            else{
                $paihang_today = $zxarticle->getListWithOnlooker(strtotime(date('Y-m-d'))-24*60*60,time());
            }
            $memcache->set($this->_new_arctile_paihang_today, $paihang_today, $this->_cache_get_index_time);
        }

        //7天排行 放入缓存 节省数据库负荷 @author 花文刚
        $paihang_week = $memcache->get($this->_new_arctile_paihang_week);
        if(empty($paihang_week)){
            if($newpaihang['paihang_ids_week']){
                $paihang_week = $zxarticle->getBiList($newpaihang['paihang_ids_week']);
            }
            else{
                $paihang_week = $zxarticle->getListWithOnlooker(strtotime(date('Y-m-d'))-7*24*60*60,time());
            }
            $memcache->set($this->_new_arctile_paihang_week, $paihang_week, $this->_cache_get_index_time);
        }

        //30天排行 放入缓存 节省数据库负荷 @author 花文刚
        $paihang_month = $memcache->get($this->_new_arctile_paihang_month);
        if(empty($paihang_month)){
            if($newpaihang['paihang_ids_month']){
                $paihang_month = $zxarticle->getBiList($newpaihang['paihang_ids_month']);
            }
            else{
                $paihang_month = $zxarticle->getListWithOnlooker(strtotime(date('Y-m-d'))-30*24*60*60,time());
            }
            $memcache->set($this->_new_arctile_paihang_month, $paihang_month, $this->_cache_get_index_time);
        }

        $this->content->paihang_today = $paihang_today;
        $this->content->paihang_week  = $paihang_week;
        $this->content->paihang_month = $paihang_month;
        if(strpos($con_method, "Index_industrynews") === 0 || strpos($con_method, "Info_industryinfo") === 0 || strpos($con_method, "Search_getHangYeNewsTagList") === 0){
        	$this->content->ishangyenews = 1;
        	$hangyePv_ids_1 = $zxapi->getHangYePvDate(strtotime(date('Y-m-d'))-7*24*60*60,time(), 18);
        	$hangyePv_ids_2 = $zxapi->getHangYePvDate(strtotime(date('Y-m-d'))-30*24*60*60,time(), 18);        	
        	$hangyePv_list_1 = array();
        	$list_1 = $memcache->get('hangyePv_list_1');
        	if($list_1){
        		$hangyePv_list_1 = $list_1;
        	}else{
        		$hangyePv_list_1 = $zxarticle->getHangYeNewsList($hangyePv_ids_1);
        		$memcache->set('hangyePv_list_1', $hangyePv_list_1,$this->_cache_get_index_time);
        	}
        	$hangyePv_list_2 = array();
        	$list_2 = $memcache->get('hangyePv_list_2');
        	if($list_2){
        		$hangyePv_list_2 = $list_2;
        	}else{
        		$hangyePv_list_2 = $zxarticle->getHangYeNewsList($hangyePv_ids_2);
        		$memcache->set('hangyePv_list_2', $hangyePv_list_2,$this->_cache_get_index_time);
        	}
        	$this->content->hangyePv_list_1 = $hangyePv_list_1;
        	$this->content->hangyePv_list_2 = $hangyePv_list_2;
        }
        $retrun_key=secure::secureInput(secure::secureUTF(($this->request->query("a"))));
        $this->content->keywords = $retrun_key;
        $this->content->set_global("verification_code",common::verification_code());

        //获取项目新闻的数量
        $rps= $zxarticle->getListByColumnId( 29,1,1 );
        $pzn= $rps['list']->as_array();
        $this->content->count_pzn= count($pzn);
        //echo count($pzn);
    }


    public function after(){
        parent::after();
    }
}