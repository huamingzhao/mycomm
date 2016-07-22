<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯专栏
 * @author 赵路生
 *
 */
class Service_News_Zhuanlan{

    /**
     * 获取资讯专栏
     * @param
     * @return array
     * @author 赵路生
     */
    public function getZhuanlanList(){
    	$result = array();
        $page_size= 10;
        $memcache = Cache::instance ( 'memcache' );
        $now_page = isset($_GET['page']) ? $_GET['page'] :1;
        $result_cache = $memcache->get('getZhuanlanList'.$now_page);
        if($result_cache){
        	$result = $result_cache;
        }else{
	        $orm= ORM::factory( 'Zixun_Zxzl' );
	        //count
	        $orm->where('zl_status', '=', '2')->reset(false);
	        $count= $orm->count_all();
	        $page = Pagination::factory(array(
	                'current_page'   => array('source' => 'zhuanlan', 'key' => 'page'),
	                'total_items'    => $count,
	                'items_per_page' => $page_size,
	                'view' => 'pagination/Simple',
	        ));
	        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('zl_shtime', 'DESC')->find_all( );
	        //获取相关关键字的文章
	        $api_search_service = new Service_Api_Search();
	        $zixun_service= new Service_News_Article();
	        $zl_array= $result_find = array();
	        $i= 0;
	         foreach( $list as $k=>$v){
	             // 需要显示的专栏信息
	            $zl_array[$k]['zl_title']= $v->zl_title;
	            $zl_array[$k]['zl_id']= $v->zl_id;
	            $zl_array[$k]['zl_pic']= $v->zl_pic;
	            $zl_array[$k]['zl_introduce']= $v->zl_introduce;
	            $zl_array[$k]['zl_key']= $v->zl_key;
	            $zl_array[$k]['zl_shtime']= $v->zl_shtime;
	            $zl_array[$k]['zl_tj'] = $v->zl_tj;
	            $zl_array[$k]['count'] = $i++;
	            $key_word = str_replace(',','',$v->zl_key);
	            $rs_search= $api_search_service->getSearchSpecialColumn($v->zl_key,$key_word,0,4);
	            // 获取关键字搜索到的咨询文章
	            if( !empty( $rs_search ) ){
	                $ids= $rs_search['matches'];
	                if( !empty( $ids ) ){
	                    foreach ( $ids as $key=>$xv ){
	                        //获取咨询
	                        $rs_zixun= $zixun_service->getInfoRow($xv);
	                        $zixun_title= $rs_zixun['article_name'];
	                        $zixun_id= $rs_zixun['article_id'];
	                        $zixun_time= $rs_zixun['article_intime'];
	
	                        $zl_array[$k][$key]['zixun_title']= $zixun_title;
	                        $zl_array[$k][$key]['zixun_id']= $zixun_id;
	                        $zl_array[$k][$key]['zixun_time']= $zixun_time;
	
	                    }
	                }
	            }	
	         }
	        $result['list'] = $zl_array;
	        $result['page'] = $page;
	        if(count($zl_array)){
	        	$memcache->set('getZhuanlanList'.$now_page, $result ,3200);
	        }        	
       }
        return $result;
    }
    //end function

    /**
     * 获取特别推荐的专栏
     * @author 许晟玮
     */
    public function getTjZl( $count='8' ){
        $orm= ORM::factory( 'Zixun_Zxzl' );
        $orm->where('zl_tj', '=', '1');
        $orm->where('zl_status', '=', '2');
        $orm->order_by('zl_edittime','desc');
        $orm->limit($count);
        $result= $orm->find_all();
        return $result;
    }
    //end function
    /**
     * 获取专栏单条文章数据
     * @author 赵路生
     */
    public function getZlInfoRow($id){
        if(!intval($id)){
            return false;
        }
        $orm= ORM::factory( 'Zixun_Zxzl',$id);
        if($orm->loaded() === true){
            return $orm;
        }else{
            return false;
        }

    }
    //end function
}
