<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 自己调用api BI统计相关操作
 * @author 许晟玮
 *
 */
class Service_Api_Stat extends Service_Api_Basic{

    /**
    * 增加一条用户注册统计
    * @author 许晟玮
    * @return array
    */
    public function setUserRegStat( $user_id,$type,$date,$domain,$sid ){
        $post = array( 'user_type' => $type,'user_id'=>$user_id,'user_reg_ip'=>ip2long( Request::$client_ip ),'user_reg_time'=>$date,'sid'=>$sid,'fromdomain'=>$domain,'convert'=>common::convertip( Request::$client_ip ),'aid'=>Cookie::get('cpa_aid'),'campaignid'=>Arr::get($_COOKIE, 'campaignid',0),'adgroupid'=>Arr::get($_COOKIE, 'adgroupid',0),'keywordid'=>Arr::get($_COOKIE, 'keywordid',0)  );
        //print_r ($post);exit;

        $arr= $this->getApiReturn($this->apiUrl['setUserRegStat'],$post);
        if( Arr::get($arr, 'code','0')!='200' ){
            common::sendemail("注册bi返回非200", '', 'akirametero@gmail.com', '');
        }
        return $arr;
    }
    //end function

    /**
     * 增加一条统计数据
     * @author许晟玮
     */
    public function setVisit( $sid,$fromurl,$pageurl,$ip,$ipregion,$useragent,$typeid,$pnid ){
        $post= array();
        $post['sid']= $sid;
        $post['fromurl']= $fromurl;
        $post['pageurl']= $pageurl;
        $post['ip']= $ip;
        $post['ipregion']= $ipregion;
        $post['useragent']= $useragent;
        $post['typeid']= $typeid;
        $post['pnid']= $pnid;
        $arr= $this->getApiReturn($this->apiUrl['setVisit'],$post);
    }


    /**
     *获取项目PV数
     * @author许晟玮
     */
    public function getVisitPv( $typeid,$pnid,$begin,$end ){
        $post= array();
        $post['typeid']= $typeid;//用来刷新cache的参数
        $post['pnid']= $pnid;
        $post['begin']= $begin;
        $post['end']= $end;

        $arr= $this->getApiReturn($this->apiUrl['getVisitPv'],$post);
        return $arr;
    }
    //end function

    /**
     * 获取所以的来源
     * @author 许晟玮
     */
    public function getSourceAll(){
        $post= array();
        $arr= $this->getApiReturn($this->apiUrl['getSourceAll'],$post);
        return $arr;
    }
    //end function

}
