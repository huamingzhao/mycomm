<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @帮助中心
 * @author 钟涛
 *
 */
class Service_Platform_Help{
    /**
     * 帮助中心留言
     */
    public function liuyan($post){
         $name=arr::get($post, 'name','');
         $email=arr::get($post, 'email','');
         $content=arr::get($post, 'content','');
         $valid_code=arr::get($post, 'valid_code','');
         $content=strip_tags(htmlspecialchars_decode($content));
         $name=HTML::chars($name);
         $email=HTML::chars($email);
         $content=HTML::chars($content);
         $ip=Request::$client_ip;
         $liuyan_count=$this->getliuyanCount($ip);
         if($content==''){
             return array('success'=>1,'message'=>'请填写意见');
         }elseif( $liuyan_count>2 && !Captcha::valid($valid_code)){
         	return array('success'=>3,'message'=>'验证码错误');
         }elseif(strlen($content)>2000){
         	return array('success'=>2,'message'=>'您输入的留言过长，请重新输入');
         }else{
             $data=ORM::factory('Feedback');
             $datainfo = array(
                     'name' => $name,
                     'email' => $email,
                     'title' => '',
                     'coment' => $content,
                     'add_date' => time(),
                     'ip'=>$ip,
             		 'type'=>1, // 提交反馈的来源 1表示平台提交 2表示移动端提交
             		 'updatetime' =>time(),
             );
             try{
                 $data->values($datainfo)->create();
                 return array('success'=>0,'message'=>'留言成功');
             }catch (Kohana_Exception $e){
                 return array('success'=>5,'message'=>'留言失败');
             }
         }
    }
    
    /**
     * 根据ip获取当天留言次数
     */
    public function getliuyanCount($ip){
    	$data=date("Y-m-d"); 
		return ORM::factory('Feedback')
		->where('add_date','>=',strtotime($data))
		->where('add_date','<=',strtotime($data)+24*60*60-1)
		->where('ip','=',$ip)
		->count_all();
    }
}
