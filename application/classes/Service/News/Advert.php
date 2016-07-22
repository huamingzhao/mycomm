<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 咨询广告
 * @author 许晟玮
 *
 */
class Service_News_Advert{

    /**
     * 获取资讯广告管理的数据
     * @author许晟玮
     */
    public function getAdvertMore( $num=1 ){
        $orm= ORM::factory('Zixun_Advertising')->where('type','=','0');
        $orm->order_by('ad_order','asc');
        $orm->limit( $num );
        $result= $orm->find_all()->as_array();
        return $result;

    }
    //end function

    /**
     * 获取指定的单条广告数据
     * @author许晟玮
     */
    public function getAdvertRow( $id ){
        $orm= ORM::factory('Zixun_Advertising',$id);
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm->as_array();
        }
    }
    //end function


}
