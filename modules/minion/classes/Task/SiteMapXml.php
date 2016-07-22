<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_SiteMapXml extends Minion_Task
{
    /**
     * Generates a help list for all tasks
     *  @author 嵇烨
     * @return null
     */
    protected function _execute(array $params){
        #php shell php minion --task=SiteMapXml
            $this->_createSiteMapXml();

    }
	protected function _createSiteMapXml(){
		$content='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";
		#头部的数据
		$data_array=array(array('loc'=>URL::website(""),'changefreq'=>'daily'),
						  array('loc'=>URL::website("").'touzikaocha/','changefreq'=>'daily'),
						  array('loc'=>URL::website("").'zixun/','changefreq'=>'daily'),
						  array('loc'=>URL::website("").'xiangdao/fenlei/','changefreq'=>'daily')
		
					);
		#脚步数据
		$arr_data_array=array(array('loc'=>URL::website("").'zixun/invest.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/fugle-a.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/guide.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/shop.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/people.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/dialys.html','changefreq'=>'daily'),
								array('loc'=>URL::website("").'zixun/kaocha.html','changefreq'=>'daily'),
		
		);
		#头部的数据
		foreach($data_array as $data){
			$content.=self::create_item($data);
		}
		#600个项目的项目
		$arr_project_id = self::getNewProjectLsit(600);
		foreach($arr_project_id as $val){
			$content.=self::create_project_item($val);
		}
		#50条资讯的
		$ar_zx_list = self::getNewZxArticle(50);
		foreach ($ar_zx_list as $key=>$val){
			$content.=self::create_zx_item($val);
		}
		#脚步数据处理
		foreach ($arr_data_array as $val ){
			$content.=self::create_item($val);
		}
		#行业
		for($i=1;$i<=67;$i++){
			$content.= "<url>\n<loc>".URL::website("")."xiangdao/fenlei/zhy".$i.".html\n"."</loc><changefreq>daily</changefreq>\n"."</url>\n";
		}
		#金额
		for($i=1;$i<=5;$i++){
			$content.= "<url>\n<loc>".URL::website("")."xiangdao/fenlei/m".$i.".html\n"."</loc><changefreq>daily</changefreq>\n"."</url>\n";
		}
		#招商形式
		$content.= "<url>\n<loc>".URL::website("")."xiangdao/fenlei/xs2.html\n"."</loc><changefreq>daily</changefreq>\n"."</url>\n";
		$content.= "<url>\n<loc>".URL::website("")."xiangdao/fenlei/xs1.html\n"."</loc><changefreq>daily</changefreq>\n"."</url>\n";
		$content.= "<url>\n<loc>".URL::website("")."xiangdao/fenlei/xs3.html\n"."</loc><changefreq>daily</changefreq>\n"."</url>\n";
		$content.='</urlset>';
		#打开文件
		$fp=fopen(URL::sitemap('')."sitemap.xml",'w+');
		#写进文件
		fwrite($fp,$content);
		#关闭文件
		fclose($fp);
		echo "OK";die;
	}
	
	/**
	 * 固顶url拼接
	 * @param unknown $data
	 * @return string
	 */
	protected  function create_item($data){
		$item="<url>\n";
		$item.="<loc>".$data['loc']."</loc>\n";
		$item.="<changefreq>".$data['changefreq']."</changefreq>\n";
		$item.="</url>\n";
		return $item;
	}
	
	/**
	 *项目url拼接
	 * @param unknown $data
	 * @return string
	 */
	protected  function create_project_item($data){
		$item="<url>\n";
		$item.="<loc>".urlbuilder::project($data)."</loc>\n";
		$item.="<changefreq>daily</changefreq>\n";
		$item.="</url>\n";
		return $item;
	}
	
	/**
	 *资讯url拼接
	 * @param unknown $data
	 * @return string
	 */
	protected  function create_zx_item($data){
		$item="<url>\n";
		$item.="<loc>".zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))."</loc>\n";
		$item.="<changefreq>daily</changefreq>\n";
		$item.="</url>\n";
		return $item;
	}
	/**
	 * 获取最新的项目
	 * @author 嵇烨
	 * return array
	 */
	
	protected function getNewProjectLsit($top = 100){
		$arr_return_data = array();
		#获取最新的项目
		$model = ORM::factory ( "Project" )->where ('project_status', '=', intval(2) )->order_by('project_passtime','DESC')->limit(intval($top))->find_all ();
		foreach ($model as $key=>$val){
		$arr_return_data [] = $val->project_id;
		}
		return $arr_return_data;
	}
	/**
	 * 获取最新的资讯
	 * @author 嵇烨
	 */
	protected function getNewZxArticle($top = 50){
		#获取最新的项目
		$model = ORM::factory ( "Zixun_Article" )->where ('article_status', '=', intval(2) )->order_by('article_addtime','DESC')->limit(intval($top))->find_all ();
		return $model;
		}
	
}
