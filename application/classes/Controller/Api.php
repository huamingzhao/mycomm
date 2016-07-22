<?php
class Controller_Api extends Controller {
    public function action_ws(){
        try{
            $server = Sr_Soap_Server::instance()->excue();
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function action_test(){
        echo "ok";
    }
}