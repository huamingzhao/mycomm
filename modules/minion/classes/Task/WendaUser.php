<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_WendaUser extends Minion_Task
{
    protected $_options = array("type"=>1);
    protected function _execute(array $params){
        $type= isset($params['type'])?$params['type']:1;
        if( $type=='1' ){
            //第1片开始,第300结束
            $bg= 1;
        }elseif( $type=='2' ){
            //第301开始，800结束
            $bg= 301;
        }
        //header("Content-type: text/html; charset=gb2312");
        $dir = DOCROOT.'doc/sso'.$type.'.csv';
        $str= file_get_contents($dir);
        $str_gb= mb_convert_encoding($str,'utf-8', 'GBK');
        //街区字符串
        $str_str= mb_substr($str_gb, 6);
        $str_exp= explode('|', $str_str);
        $sso= Service_Sso_Client::instance();
        foreach( $str_exp as $kk=>$v ){

            $key= $kk+$bg;

            $v_exp= explode(';', $v);
            $name= $v_exp[0];
            if( $name!='' ){
                $email= $v_exp[1];
            }
            //判断email是否存在
            $rin= $sso->getUserInfoByEmail($email);
            if( $rin===false ){
                Minion_CLI::write('写入模式启动');
                //注册吧,没存在这个用户
                $userinfo = array(
                        'email' => $email,
                        'user_name' => trim($name),
                        'password' => 'tluser123',
                        'user_type' => 2,

                );

                $result= $sso->appRegister($userinfo);
                if($result){
                    if(Arr::get($result,"error") === false){
                        //同步www下的sso_user
                        $user = Arr::get($result,"return");
                        $user_id = $user['id'];
                        Minion_CLI::write($user_id);
                        //更改email状态
                        $sso->updateEmailInfoById($user_id, array('valid_status'=>'1','valid_time'=>time()));
                        //初始化 user==
                        try{
                            $basic = ORM::factory("User");
                            $basic->user_id = $user_id;
                            $basic->last_logintime = time();
                            $basic->last_login_ip = ip2long(Request::$client_ip);
                            $sid_md5	= arr::get($_COOKIE, 'Hm_lvqtz_sid');
                            $basic->sid = $sid_md5;
                            $aid= Cookie::get('cpa_aid','');
                            if( $aid!='' ){
                                $basic->aid = $aid;
                            }
                            $rbs= $basic->create();



                        }catch(Kohana_Exception $e){
                            //throw $e;
                            //抛出错误代码
                        }
                    }
                }
            }else{
                if( trim($name)!='' ){
                    Minion_CLI::write('修改模式启动');
                    $img_url= URL::webstatic("/images/wenda/{$key}.jpg");
                    //修改_name
                    $uid= $rin->id;
                    $sso->updateBasicInfoById($uid, array( 'user_name'=>trim($name),'from_type'=>3,'user_portrait'=>$img_url ));
                    Minion_CLI::write( '修改--'.$uid.'--'.trim($name).'--'.$img_url );
                    //增加personinfo
                    $persons= ORM::factory('Personinfo');
                    $persons->where('per_user_id', '=', $uid);
                    $cc= $persons->count_all();
                    if( $cc==0 ){
                        //insert
                        $persons= ORM::factory('Personinfo');
                        $persons->per_user_id= $uid;
                        $persons->per_realname= trim($name);
                        $persons->per_photo= $img_url;
                        $persons->create();
                    }else{
                        $persons= ORM::factory('Personinfo');
                        $persons->where('per_user_id', '=', $uid);
                        $ccv= $persons->find();
                        $persons= ORM::factory('Personinfo',$ccv->per_id);
                        $persons->per_photo= $img_url;
                        $persons->update();
                    }
                }
            }
        }

    }

}
