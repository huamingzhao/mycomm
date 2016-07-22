<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 工具api
 * @author 龚湧
 *
 */
class Service_Api_Tool extends Service_Api_Basic{
    public function getMoblieAttr($mobile){
        $url = 'http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi';
        $parma = array("chgmobile"=>$mobile);
        $get = Request::factory($url)
                       ->method(Request::GET)
                       ->query($parma)
                       ->execute();
        $objs = simplexml_load_string($get->body());
        //var_dump($objs);
        $obj = new stdClass();
        $obj->city = $objs->city;
        $obj->province = $objs->province;
        //var_dump($obj);
        return $obj;
    }
}