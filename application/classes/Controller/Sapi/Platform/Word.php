<?php defined('SYSPATH') or die('No direct script access.');
/**
 * api 分词操作
 * @author 许晟玮
 *
 */
class Controller_Sapi_Platform_Word extends Controller_Sapi_Basic{



    /**
     * test service
     * @author许晟玮
     */
    public function action_test(){
        $mobile= '18688554777,18608715777,13565854559,15549782788,13466902189,13881966201,15096063502,15635070186,15807930755,15826344211,18059027776,18605738017,18655149866,13410591535,13523373896,13576163697';
        $mobile_arr= explode(',',$mobile);
        if( !empty( $mobile_arr ) ){
            foreach( $mobile_arr as $v ){
                $orm= ORM::factory('User');
                $orm->where('mobile','=',common::encodeMoible($v));
                $result= $orm->find()->as_array();
                $uid= ceil( $result['user_id'] );

                //获取登录次数及最后登录时间
                $o= ORM::factory('UserLoginLog');
                $o->where('user_id', '=', $uid);
                $o->where('log_time', '>=', strtotime('2013-09-13 00:00:00') );
                $o->where('log_time', '<=', strtotime('2013-09-13 23:59:59') );
                $count= $o->count_all();

                //最后登录时间
                $oa= ORM::factory('UserLoginLog');
                $oa->where('user_id', '=', $uid);
                $oa->where('log_time', '>=', strtotime('2013-09-13 00:00:00') );
                $oa->where('log_time', '<=', strtotime('2013-09-13 23:59:59') );
                $oa->order_by('log_time','desc');
                $res= $oa->find()->as_array();

                //print_r ();exit;

                echo "用户:".$v."登录次数".$count."&nbsp最后登录时间是：".date("Y-m-d H:i:s",$res['log_time']);echo "<br>";

            }
        }


    }
    //end function


}