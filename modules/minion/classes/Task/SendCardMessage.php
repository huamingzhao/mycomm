<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  一周内未登录，且收到新名片
 *
 * @package    Kohana
 * @category   SendCardMessage
 * @author     许晟玮
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_SendCardMessage extends Minion_Task
{
    /**
     * 一周内未登录，且收到新名片发送邮件及短信消息
     * @author许晟玮
     * @return null
     */
    protected function _execute(array $params)
    {

        try {
            /**
             * 获取一周内没有登录过的会员
             **/
            //一周前的时间unix
            $otime= time()-86400*7;
            $now_time= time();
            //获取一周内登录过的会员
            $query= DB::select()->from('user_login_log')->where('log_time','>=',$otime)->where('log_time','<=', $now_time );
            $query->group_by('user_id');
            $result= $query->execute();//resutl is array

            if( !empty( $result ) ){
                $new_array= array();
                foreach( $result as $vs ){
                    $user_id= $vs['user_id'];
                    //这些是一周内登录过的ID数组
                    $new_array[]= $user_id;
                }
                unset( $result );

                //排除这些ID，就是一周内没有登录过的会员了
                //多做一次验证
                if( !empty( $new_array ) ){
                    $query= DB::select()->from('user')->where('user_id','not in',$new_array);
                    $res= $query->execute();
                    if( !empty( $res ) ){
                        //是否有收到过名片
                        foreach ( $res as $ves ){
                            $user_id= $ves['user_id'];
                            //Minion_CLI::write($user_id);
                            //用户类型
                            $user_type= $ves['user_type'];
                            if( $user_type=="1" ){
                                //企业用户
                                $email_msg_type= 'email_company_receive_card';
                                $mobile_msg_type= 'mobile_company_receive_card';
                                $msg_subject= '一句话提醒——有人给您递送了新名片';
                                $name= $ves['user_name'];

                            }else{
                                //个人用户
                                $email_msg_type= 'email_person_receive_card';
                                $mobile_msg_type= 'mobile_person_receive_card';
                                $msg_subject= '一句话提醒——有人给您递送了新名片';
                                $user_service= new Service_User_Person_User();
                                $rsa= $user_service->getPerson($ves['user_id']);
                                $name= $rsa->per_realname;

                            }


                            $query= DB::select()->from('card_info')->where('to_user_id','=',$ves['user_id']);
                            $query->where('send_time', '>=', $otime);
                            $query->where('send_time', '<=', $now_time);
                            $rsc= $query->execute();
                            if( count($rsc)>0 ){
                                //这个一周内没有登录过的用户，在一周内有收到过名片
                                //email send
                                $service_user= new Service_User();
                                $obj_user= $service_user->getUserInfoById($user_id);
                                $email= $obj_user->email;
                                $mobile= $obj_user->mobile;
                                $valid_mobile= $obj_user->valid_mobile;


                                $smsg = Smsg::instance();
                                $smsg->register(
                                        $email_msg_type,
                                        Smsg::EMAIL,//类型
                                        array(
                                                "to_email"=>$email,
                                                "subject"=>$msg_subject
                                        ),
                                        array(
                                            "name"=>$name,
                                            "count"=>count($rsc)
                                        )
                                );
                                if( $mobile!='' && $valid_mobile=='1' ){
                                    //Minion_CLI::write($mobile);
                                    //mobile send
                                    $smsg = Smsg::instance();
                                    $smsg->register(
                                            $mobile_msg_type,
                                            Smsg::MOBILE,//类型
                                            array(
                                                    "receiver"=>$mobile
                                            ),
                                            array(
                                                    "count"=>count($rsc)
                                            )

                                    );
                                    sleep(1);
                                }

                            }else{
                            }
                            unset($rsc);
                        }

                    }else{
                    }
                    unset( $res );

                }else{

                }

            }else{
                //一周内没有一个会员登录过

            }


            //Minion_CLI::write('a');



        } catch (Exception $e) {
            //error
            Minion_CLI::write('error');

        }



    }
    //end function
}
