<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯收藏
 * @author 周进
 *
 */
class Service_News_Favorite{

    /**
    * 判断是否已经收藏
    * @author周进
    * @param int $user_id 当前用户
    * @param int $favorite_article_id 关联文章ID
    * @return bool
    */
    public function getFavoriteStatus($user_id,$favorite_article_id){
        $user_id = intval($user_id);
        $favorite_article_id = intval($favorite_article_id);
        $favorite = ORM::factory('Zixun_Zxfavorite');
        $data = $favorite->where('favorite_article_id', '=', $favorite_article_id)
        ->where('user_id','=',$user_id)->where('favorite_status','=','1')->count_all();
        if ($data==1){//已经存在收藏并且收藏状态为1
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    //end function

    /**
    * 添加收藏资讯文章
    * @param int $user_id 当前用户
    * @param int $favorite_article_id 关联文章ID
    * @return bool
    * @author周进
    */
    public function addFavorite($user_id,$favorite_article_id){
    	if ($user_id==0||$favorite_article_id==0)
    		return false;
    	$favorite = ORM::factory('Zixun_Zxfavorite');
        $data = $favorite->where('favorite_article_id', '=', $favorite_article_id)->where('user_id','=',$user_id)->find();
        if ($data->favorite_id!=""){
        	if ($data->favorite_status==1)
        		return true;
        	else{
        		$favorite = ORM::factory('Zxfavorite',$data->favorite_id);
        		$favorite->favorite_time = time();
        		$favorite->favorite_status = 1;
        		try {
        			$favorite->update();
        			return true;
        		} catch (Kohana_Exception $e) {
        			return false;
        		}
        	}
        }
        else{
        	$favorite = ORM::factory('Zixun_Zxfavorite');
        	$favorite->user_id = $user_id;
        	$favorite->favorite_article_id = $favorite_article_id;
        	$favorite->favorite_time = time();
        	$favorite->favorite_status = 1;
        	try {
        		$favorite->save();
        		return true;
        	} catch (Kohana_Exception $e) {
        		return false;
        	}
        }
    }

    /**
     * 添加收藏资讯文章
     * @param int $user_id 当前用户
     * @param int $favorite_article_id 关联文章ID
     * @return bool
     * @author周进
     */
    public function addFavoriteIndustry($user_id,$favorite_article_id){
        if ($user_id==0||$favorite_article_id==0)
            return false;
        $favorite = ORM::factory('Zixun_ZxIndustryfavorite');
        $data = $favorite->where('industry_article_id', '=', $favorite_article_id)->where('user_id','=',$user_id)->find();
        if ($data->favorite_id!=""){
            if ($data->favorite_status==1)
                return true;
            else{
                $favorite = ORM::factory('Zixun_ZxIndustryfavorite',$data->favorite_id);
                $favorite->favorite_time = time();
                $favorite->favorite_status = 1;
                try {
                    $favorite->update();
                    return true;
                } catch (Kohana_Exception $e) {
                    return false;
                }
            }
        }
        else{
            $favorite = ORM::factory('Zixun_ZxIndustryfavorite');
            $favorite->user_id = $user_id;
            $favorite->industry_article_id = $favorite_article_id;
            $favorite->favorite_time = time();
            $favorite->favorite_status = 1;
            try {
                $favorite->save();
                return true;
            } catch (Kohana_Exception $e) {
                return false;
            }
        }
    }
    
    /**
     * 取消收藏
     * @param int $user_id 当前用户
     * @param int $favorite_article_id 关联文章ID
     * @return bool
     * @author周进
     */
    public function cancelFavorite($user_id,$favorite_article_id){
    	if ($user_id==0||$favorite_article_id==0)
    		return false;
    	$favorite = ORM::factory('Zixun_Zxfavorite');
    	$data = $favorite->where('favorite_article_id', '=', $favorite_article_id)->where('user_id','=',$user_id)->find();
    	if ($data->favorite_id!=""){
    		if ($data->favorite_status==0)
    			return true;
    		else{
    			$favorite = ORM::factory('Zixun_Zxfavorite',$data->favorite_id);
    			$favorite->favorite_time = time();
    			$favorite->favorite_status = 0;
    			try {
    				$favorite->update();
    				return true;
    			} catch (Kohana_Exception $e) {
    				return false;
    			}
    		}
    	}
    	else{
    		return true;
    	}
    }

    /**
     * 判断行业新闻是否已经收藏
     * @author花文刚
     * @param int $user_id 当前用户
     * @param int $favorite_article_id 关联文章ID
     * @return bool
     */
    public function getFavoriteStatusIndustry($user_id,$favorite_article_id){
        $user_id = intval($user_id);
        $favorite_article_id = intval($favorite_article_id);
        $favorite = ORM::factory('Zixun_ZxIndustryFavorite');
        $data = $favorite->where('industry_article_id', '=', $favorite_article_id)
            ->where('user_id','=',$user_id)->where('favorite_status','=','1')->count_all();
        if ($data==1){//已经存在收藏并且收藏状态为1
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    //end function


}
