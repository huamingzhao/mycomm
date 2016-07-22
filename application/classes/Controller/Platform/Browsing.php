<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 随便逛逛controller
 * @author 曹怀栋
 *
*/
class Controller_Platform_Browsing extends Controller_Platform_Template{
    //随便逛逛项目
    private $_cache_key = 'showBrowing';
    //随便逛逛项目缓存 count
    private $_cache_key_count = 'showBrowingCount';
    //随便逛逛项目缓存 行业
    private $_cache_key_industry = 'showBrowing_industry';
    private $_cache_time = '3600';

    /**
     * 随便逛逛搜索页
     * @author 曹怀栋
     */
    public function action_showBrowsing(){
        $content = View::factory("platform/browsing/showbrowsing");
        $this->content->maincontent = $content ;
        $service = Service::factory("Platform_Browsing");
        $memcache = Cache::instance('memcache');
        $noCache = $this->request->query('nocache');
        $project_list = array();
        $count = array();
        $industry = array();
        try {
            if($noCache != 1){
                $project_list = $memcache->get($this->_cache_key);
                $industry = $memcache->get($this->_cache_key_industry);
                $count = $memcache->get($this->_cache_key_count);
            }
        }
        catch (Cache_Exception $e) {
             $project_list = array();
             $count = array();
             $industry = array();
        }

        if(!$project_list) {
            $project_list = $service->getProjectList();
            $memcache->set($this->_cache_key, $project_list, $this->_cache_time);
        }
        if(!$count) {
            $count = $service->getProjectCount();
            $memcache->set($this->_cache_key_count, $count, $this->_cache_time);
        }
        if(!$industry) {
            $industry = $service->getPrimaryIndustry();
            $memcache->set($this->_cache_key_industry, $industry, $this->_cache_time);
        }
        $content->industry_list = $industry;
        $content->project_count = $count;
        $content->project_list = $project_list;
        $this->template->title = '随便逛逛_各种创业项目月度热榜_一句话';
        $this->template->description = '随便逛逛，创业项目，一句话';
        $this->template->keywords = '这里有各种创业项目热榜，有餐饮娱乐月度热榜、连锁零售月度热榜、服饰箱包月度热榜、家居建材月度热榜、教育培训月度热榜、酒水饮料月度热榜等。';
    }

}