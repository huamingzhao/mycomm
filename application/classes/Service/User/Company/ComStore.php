<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 用户加盟店地址s
 *
 * @author 许晟玮
 *
 */
class Service_User_Company_ComStore extends Service_User_Company_User {

    /**
     * 增加加盟店地址
     *
     * @param $uid用户ID $area省份
     *        	$city市 $address地址
     * @author 许晟玮
     */
    public function setStore($uid, $area, $city, $address) {
        $orm = ORM::factory ( "Comstore" );
        $orm->store_area = $area;
        $orm->store_city = $city;
        $orm->store_address = $address;
        $orm->store_uid = $uid;
        $orm->create ();
    }
    // end function

    /**
     * 获取单条数据
     *
     * @param $id 自增
     * @author 许晟玮
     */
    public function getStoreRow($id) {
        $orm = ORM::factory ( "Comstore", $id );
        if ($orm->loaded ()) {
            return $orm;
        } else {
            return false;
        }
    }
    // end function

    /**
     * 修改单条记录
     *
     * @author 许晟玮
     */
    public function editStroe($id, $area, $city, $address) {
        $orm = ORM::factory ( "Comstore", $id );
        $orm->store_area = $area;
        $orm->store_city = $city;
        $orm->store_address = $address;
        $orm->update ();
    }
    // end function

    /**
     * 获取用户的店铺多条
     *
     * @return array
     * @author 许晟玮
     */
    public function getUserStore($uid) {
        $orm = ORM::factory ( "Comstore" );
        $orm->where ( "store_uid", "=", $uid );
        $result = $orm->find_all();
        return $result;
    }
    // end function

    /**
     * 删除对应用户的加盟店信息
     * @author许晟玮
     */
    public function delUserStore( $uid ){
        $result= self::getUserStore($uid);
        foreach ( $result as $vs ){
            if( $vs->store_id!="" ){
                //del
                $orm= ORM::factory( "Comstore",$vs->store_id );
                $orm->delete();
            }
        }

    }
    //end function

    /**
     * 添加图片信息
     *
     * @author 许晟玮
     */
    public function addImages($url,$uid) {
        $project = ORM::factory ( 'Projectcerts' );

        $project->project_img = common::getImgUrl ( $url );
        $project->project_type = 3;
        $project->project_id = $uid;
        $project->project_addtime = time ();
        $project->create ();

        return true;
    }
    //end function

    /**
     *获取用户上传的图片
     * @author许晟玮
     * @param  $uid
     */
    public function getImages( $uid ){
        $orm = ORM::factory ( 'Projectcerts' );
        $orm->where("project_type", "=", "3");
        $orm->where("project_id", "=", $uid);
        $result= $orm->find_all();
        return $result;

    }
    //end function

    /**
     * 删除图片信息
     *
     * @author许晟玮
     */
    public function delImages( $uid ){
        $result= self::getImages($uid);
        foreach( $result as $vs ){
            if( $vs->project_certs_id!="" ){
                $orm = ORM::factory ( 'Projectcerts',$vs->project_certs_id );
                $orm->delete();
            }

        }
    }
    //end function

}