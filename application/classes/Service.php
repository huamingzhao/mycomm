<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 服务层
 * @author 龚湧
 */
class Service{
    /**
    * $service=Service::factory('user');
    * $service = Service::factory(array('users'=>'user'));
    * @author 龚湧
    * return $serives
    */
    public static function factory($service){
        $services = new stdClass;
        if(is_array($service)){
            foreach ($service as $key=>$srv){
                $srv = "Service_".ucfirst($srv);
                $services->$key = new $srv;
            }
        }
        elseif(is_string($service)){
            $service = "Service_".ucfirst($service);
            $services = new $service;
        }
        return $services;
    }
}