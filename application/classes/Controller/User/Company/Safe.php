<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业安全中心
 * @author 龚湧
 */
class Controller_User_Company_Safe extends Controller_User_Company_Template{
    private $_can_edit_email_num= 2;

    /**
    * 企业安全中心首页
    * @author 许晟玮
    */
    public function action_index(){
        $content 			= View::factory('user/company/safe');
        $user 				= $this->userInfo();

        //获取用户的邮箱地址
        $usermail			= $user->email;
        //转换邮箱格式，取前后各一位
        $mail_arr			= explode('@',$usermail );
        $mail_name			= $mail_arr[0];
        //获取名称长度
        $mail_len			= mb_strlen( $mail_name,'utf8' );
        //取第一位
        $mail_first_name	= mb_substr( $mail_name,0,1,'utf8' );
        //取最后一位
        $mail_last_name		= mb_substr( $mail_name,-1,1,'utf8' );
        //需要补充的星号
        $mail_centre_len	= ceil( $mail_len-2 ) ;
        $mail_center	= '';
        if( $mail_centre_len>0 ){
            for( $i=0;$i<$mail_centre_len;$i++ ){
                $mail_center= $mail_center.'*';
            }
        }else{
        }


        //已经填写基本信息 或者 验证过手机号，显示转换后的手机号
        if( $this->is_complete_basic( $user->user_id )===true || $user->valid_mobile ){

            //转换显示的手机号
            $mobile_first			= substr( $user->mobile,0,3 );
            $mobile_last			= substr( $user->mobile,-3,3 );

            $mobile_len				= ceil( strlen( $user->mobile )-6 );
            $mobile_center			= '';
            for( $i=0;$i<$mobile_len;$i++ ){
                $mobile_center		= $mobile_center.'*';
            }
            $content->usermobile	= $mobile_first.$mobile_center.$mobile_last;
        }else {

        }

        //邮箱是否验证
        $service					= new Service_User_Company_User();
        $is_complete_mail			= $service->getEmailValidCount( $user->user_id );
        $content->valid_mail		= $is_complete_mail;
        if( $usermail!='' ){
        //邮箱地址显示
            $content->usermail			= $mail_first_name.$mail_center.$mail_last_name.'@'.$mail_arr[1];
        }
        //个人信息是否完善
        $content->is_complete_basic = $this->is_complete_basic( $user->user_id );

        $content->valid_mobile		= $user->valid_mobile;
        $content->valid_email		= $user->valid_email;
        $this->content->rightcontent = $content;

        //判断会员修改邮箱次数是否满了
        $service = new Service_User();
        $edit_count= $service->getEditEmailCount( $this->userId() );
        //修改次数到达设定值，显示提示，反之隐藏
        if( $edit_count>=$this->_can_edit_email_num ){
            $content->show_edit_tishi= true;
        }else{
            $content->show_edit_tishi= false;
        }


    }
}