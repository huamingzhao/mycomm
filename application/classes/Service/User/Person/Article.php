<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人中心文章管理
 * @author 潘宗磊
 *
 */
class Service_User_Person_Article{
	/**
	 * 获取个人中心收藏文章列表
	 * @author 潘宗磊
	 */
	public function getListFavorite($user_id){
		$model = ORM::factory("Zixun_Zxfavorite");
		$model->join('zx_article','left')->on('zixun_zxfavorite.favorite_article_id','=','zx_article.article_id');
		$count=$model->where('favorite_status','=',1)->where('zixun_zxfavorite.user_id', '=', $user_id)->where('article_status','<',4)->reset(false)->count_all();
		$page = Pagination::factory ( array (
				'total_items' => $count,
				'items_per_page' => 7
		) );
		$array = array ();
		$list = $model->limit($page->items_per_page )->offset ( $page->offset )->find_all ();
		foreach ($list as $k=>$v){
			$article = ORM::factory("Zixun_Article",$v->favorite_article_id);
			$array[$k]['article_id']=$v->favorite_article_id;
			$array[$k]['article_name']=$article->article_name;
			$array[$k]['article_img']=$article->article_img;
			$array[$k]['article_content']=$article->article_content;
			$array[$k]['article_tag']=$article->article_tag;
			$array[$k]['add_time']=$article->article_addtime;
            $array[$k]['article_intime']=$article->article_intime;
		}
		$array ['list'] = $array;
		$array ['page'] = $page;
		$array ['count'] = $count;
		return $array;
	}

	/**
	 * 获取个人中心投稿文章列表
	 * @author 潘宗磊
	 */
	public function getListApply($user_id){
		$model = ORM::factory("Zixun_Article");
		$model->where('user_id','=',$user_id);
		$count=$model->where('article_status','<',4)->reset(false)->count_all();
		$page = Pagination::factory ( array (
				'total_items' => $count,
				'items_per_page' => 7
		) );
		$array = array ();
		$list = $model->limit($page->items_per_page )->offset ( $page->offset )->find_all ();
		foreach ($list as $k=>$v){
			$array[$k]['article_id']=$v->article_id;
			$array[$k]['article_name']=$v->article_name;
			$array[$k]['article_img']=$v->article_img;
			$array[$k]['article_content']=$v->article_content;
			$array[$k]['article_status']=$v->article_status;
			$array[$k]['article_tag']=$v->article_tag;
			$array[$k]['parent_name']=ORM::factory("Zixun_Column",$v->parent_id)->column_name;
			$array[$k]['column_name']=ORM::factory("Zixun_Column",$v->column_id)->column_name;
			$array[$k]['add_time']=$v->article_addtime;
		}
		$array ['list'] = $array;
		$array ['page'] = $page;
		$array ['count'] = $count;
		return $array;
	}
}