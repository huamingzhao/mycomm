<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 自己调用api 基类
 * @author 施磊
 *
 */
class Service_Api_Basic {
    //放置各种api请求的数组
    protected $apiUrl = array();
    public function __construct() {
        $this->apiUrl = array(
            'getProjectInvestid' => $this->_apiUrl('GetProjectInvestId/getprojectinvestid'),//获得企业用户
            'getViewsByInvestId' => $this->_apiUrl('GetProjectInvestId/GetViewsByInvestId'),//获取招商会浏览数
            'getZixunPvNum'=> $this->_apiUrl('Zixun/getzixunPvNum'),//获取一个资讯的PV数
            'getZixunPvDate'=> $this->_apiUrl('Zixun/getPvNumByDate'),//获取一个资讯的PV数
            'getSearchtParticiple' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_index_search')."/analysis/field"),
            'getSearch' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_index_search')."/select"),
            'getSearchWord' => $this->_searchUrl("analyzer/service/w"),
            'getSearchInvestorAnalysis' => $this->_searchUrlInvestor("solr/".Kohana::$config->load('search.solr_investor_search')."/analysis/field"),//搜索投资者获取分词
            'getSearchSpecialColumn'=>$this->_searchUrlInvestor("solr/article/analysis/field"),//资讯专栏分词
            'getSearchInvestor' => $this->_searchUrlInvestor("solr/".Kohana::$config->load('search.solr_investor_search')."/select"),//搜索投资者获取数据
            'getSearchZixun' => $this->_searchUrlInvestor("solr/".Kohana::$config->load('search.solr_zixun_search')."/select"),//搜索投资者获取数据
            'setUserRegStat'=>$this->_apiUrl('Stat/setUserRegStat'),//增加一条用户注册统计
            'setVisit'=>$this->_apiUrl('Stat/setVisit'),//增加一条注册统计数据
            'getVisitPv'=>$this->_apiUrl('Stat/getStatPv'),//获取统计到的PV的数
            'getSuggest' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_index_search')."/suggest"),
            'getSearchCorrect' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_index_search')."/spell"),
            'getQucikSearchCorrect' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_quick_search')."/select"),
            'getSourceAll'=>$this->_apiUrl('Stat/getSourceAll'),//获取所有的来源info
            'getHangYeTagList'=>$this->_searchUrl("solr/alpha_news/select"),//获取行业新闻中标签搜索数据
            'getHangYePvDate'=>$this->_apiUrl("Zixun/getHyPvNumbyDate"),//获取行业新闻中热门项目
            'reflashIndex' => $this->_searchUrl("solrSearch/quickPro/addIndex"),
        	'delSearchIndex' => $this->_searchUrl("solrSearch/quickPro/deleteIndex"),
        	'processMingGanWords' => $this->_searchUrl("solrSearch/mingan/processMingan"),//判断内容中是否存在敏感词
            'solrQuestion' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_question')."/select"),//生意帮的搜索
            'solrUser' => $this->_searchUrl("solr/".Kohana::$config->load('search.solr_user')."/select"),//生意帮用户的搜索
            'updateWenIndex' => $this->_searchUrl("solrSearch/question/addIndex"),
            'delWenIndex' => $this->_searchUrl("solrSearch/question/deleteIndex"),
        );
    }
    /**
     * 请求外部api返回值
     * @author 施磊
     * @param $url api的路径
     * @param $decode 默认返回数组 FLASE 返回json
     * @param string $post post的值
     */
    public function getApiReturn($url, $post = false, $decode = TRUE) {
        if(!$url) return array();
        $post = $post ? $post : array('date' => time());
        try{
            $outPut = Request::factory($url)
            ->method(HTTP_Request::POST)
            ->post($post)
            ->execute()
            ->body();
        }
        catch(Kohana_Exception $e){
            $outPut  = $e;
            common::sendemail("api接口错误", '', 'akirametero@gmail.com', $outPut);
        }
        $outPut = $decode ? json_decode($outPut, TRUE) : $outPut;
        return $outPut;
      }

      /**
     * 请求外部api返回值 由于之前设计的问题 导致没有兼容get模式
     * @author 施磊
     * @param $url api的路径
     * @param $decode 默认返回数组 FLASE 返回json
     * @param string $post post的值
     */
    public function getApiReturnByGet($url, $get = array(), $decode = TRUE) {
        if(!$url) return array();
        $get = $get ? $get : array('date' => time());
        try{
            $outPut = Request::factory($url)
            ->method(HTTP_Request::GET)
            ->query($get)
            ->execute()
            ->body();
        }
        catch(Kohana_Exception $e){
            $outPut  = $e;
        }
        $outPut = $decode ? json_decode($outPut, TRUE) : $outPut;
        return $outPut;
      }

      /**
       * 处理api接口路径
       * @auhtor 施磊
       */
      private  function _apiUrl($url) {
          if(!$url) return '';
          return Kohana::$config->load("apiget.BI_API").$url;
      }

      /**
       * search 的接口
       * @auhtor 施磊
       */
      private function _searchUrl($url) {
          if(!$url) return '';
          return Kohana::$config->load("apiget.SEARCH_API").$url;
      }

      /**
       * search 的接口[搜索投资者]
       * @auhtor 钟涛
       */
      private function _searchUrlInvestor($url) {
          if(!$url) return '';
          return Kohana::$config->load("apiget.INVESTOR_API").$url;
      }

}