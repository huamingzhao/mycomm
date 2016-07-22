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
class Task_SendNoJhMobileListMail extends Minion_Task
{
    private $_address= array('zhengxingquanD701@tonglukuaijian.com');
    private $_test_address= array( 'akirametero@gmail.com' );
    protected $_options = array("send"=>1,"type"=>1);
    /**
     *每周-发送邮件，统计aid不是空的那堆会员，哪些没有验证过手机，列出来ID
     *
     * @return null
     */
    protected function _execute(array $params){
        $type= isset($params['type'])?$params['type']:1;
        $send= isset($params['send'])?$params['send']:1;

        //统计一周的数据
        if( $send==1 ){
            $ft= date("Y-m-d").'00:00:00';
            $tt= date("Y-m-d")."23:59:59";
            $ag_ft= strtotime($ft)-86400*7;//开始时间
            $ag_tt= strtotime($tt)-86400;//结束时间
        }else{
            $ft= '2014-03-21 00:00:00';
            $tt= "2014-04-13 23:59:59";
            $ag_ft= strtotime($ft);//开始时间
            $ag_tt= strtotime($tt);//结束时间
        }


        //获取这个时间段内的注册会员ID,通过SSO
        $client = Service_Sso_Client::instance();
        $list= $client->getRegUserListBydate( $ag_ft,$ag_tt,0 );
        $ids= array();
        foreach( $list['return'] as $vs ){

            $ids[]= $vs['id'];
        }
        $html= '<table width=\"100%\" border=\"1\"><tr><td>日期</td><td>会员ID</td><td>AID</td></tr>';
        $orm= ORM::factory('User');
        //$orm->where( 'last_logintime','>=',$ag_ft );
        //$orm->where( 'last_logintime','<=',$ag_tt );
        $orm->where('aid', '!=', '');
        $orm->where('aid', '!=', 'undefined');
        $orm->where('user_id', 'in', $ids);
        $result= $orm->find_all()->as_array();
        $isno_mobile= 0;
        if( !empty( $result ) ){
            foreach( $result as $vss ){
                $uid= $vss->user_id;
                //判断这个会员的手机号，是否是验证了的
                $so= $client->getUserInfoById($uid);
                if( $so->valid_mobile!='1' ){
                    $aid= $vss->aid;
                    $time= date("Y-m-d H:i:s",$so->reg_time);
                    $isno_mobile++;
                    $html .= '<tr nowrap><td align=\"center\" nowrap>'.$time.'</td>';
                    $html .= '<td align=\"center\" nowrap>'.$uid.'</td>';
                    $html .= '<td align=\"center\" nowrap>'.$aid.'</td></tr>';
                }
            }
        }
        $html .='</table>';

        if( $send=='1' ){
            common::sendemail(date("Y-m-d",$ag_ft).'到'.date("Y-m-d",$ag_tt).'未验证手机号的会员', '', $this->_address, $html);
        }else{
            common::sendemail(date("Y-m-d",$ag_ft).'到'.date("Y-m-d",$ag_tt).'未验证手机号的会员', '', $this->_test_address, $html);
        }



    }
}
