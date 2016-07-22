<?php
/**
 * 企业用户各种保障状态
 * @author 许晟玮
 *
 */
class comserver{

    /**
     *获取企业用户状态数组
     *@author许晟玮
     */
    public static function getComStatusArr(){
        $_tname= "guard";
        $array	= array(
            "all".$_tname=>"0",//所有
            "safe".$_tname=>"1",//安全保障
            "server".$_tname=>"2",//服务保障

            "base".$_tname=>"3",//基础保障
            "quality".$_tname>"4"//品质保障
        );

        return $array;
    }
    //end function

    /**
     * 传入类型名称,获取对应的值
     * @author许晟玮
     */
    public static function getComStatusRow( $name ){
        $_tname= "guard";
        $arr= self::getComStatusArr();
        $tn= $name.$_tname;
        foreach( $arr as $k=>$vs ){
            if( $k==$tn ){

                return $vs;
            }
        }


    }
    //end function

    /**
     *获取保证金的金额
     *@author许晟玮
     */
    public static function getUserSafeAccount(){
        return "50000";
    }
    //end function


}