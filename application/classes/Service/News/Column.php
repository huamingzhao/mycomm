<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯栏目
 * @author 周进
 *
 */
class Service_News_Column{

    /**
     * 获取栏目名称获取相关信息
     * @param string $column_name
     * @return array
     * @author周进
     */
    public function getColumnByName( $column_name ){
        $result = array();
        $result = ORM::factory( 'Zixun_Column')->where('column_name', '=', $column_name)->find()->as_array();
        return $result;
    }
    //end function

    /**
     * 根据栏目名称 获取关联的所有下级的栏目数据
     * @author许晟玮
     */
    public function getParInfo( $name ){
        $rs= self::getColumnByName( $name );

        $id= ceil($rs['column_id']);
        if( $id==0 ){
            return false;
        }
        $orm= ORM::factory('Zixun_Column');
        $rs=$orm->where('parent_id', '=', $id)->where('column_status','=','1')->order_by('column_order','ASC')->find_all()->as_array();
        return $rs;
    }
    //end function
    /**
     * 生成模板页面左侧导航内容
     * @return array
     * @author周进
     */
    public function getColumnMenu(){
        $result = array();
        $result = ORM::factory( 'Zixun_Column')->where("column_type","=","0")->where('parent_id', '=', 0)->where('column_status','=','1')->order_by('column_order','ASC')->find_all()->as_array();
        return $result;
    }
    //end function

    /**
     * 根据栏目ID 获取关联的所有下级的栏目数据
     */
    public function getParInfoById( $id=0 ){
        $parent_id = ORM::factory('Zixun_Column')->where('column_id', '=', $id)->find();
        if ($parent_id->parent_id==0)
            return ORM::factory('Zixun_Column')->where('parent_id', '=', $id)->where('column_status','=','1')->order_by('column_order','ASC')->find_all()->as_array();
        else
            return ORM::factory('Zixun_Column')->where('parent_id', '=', $parent_id->parent_id)->where('column_status','=','1')->order_by('column_order','ASC')->find_all()->as_array();
    }
    //end function

    /**
     * 根据ID获取对应的数据
     * @author许晟玮
     * @param $id
     */
    public function getCloInfo( $id ){
        $orm = ORM::factory( 'Zixun_Column',$id);
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm->as_array();
        }

    }
    //end function
}
