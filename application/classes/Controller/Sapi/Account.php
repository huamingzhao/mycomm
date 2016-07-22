<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 账户
 * @author 赵路生
 *
 */
class Controller_Sapi_Account extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_Account();
    }
    
    /*
     * 提供给外部使用的api 后台账户--退款--调用前台资金冻结接口
     * @author 赵路生
     * @return array
     */
    public function action_updateAccountBlock() {   	
    	$post = $this->request->post();   
        $service = new Service_Account();
        if(!Arr::get($post, 'refund_user_id')) $this->setApiReturn(405);
        $return = array();
        try {
             $return = $service->blockedAccount(Arr::get($post, 'refund_user_id',0),intval(Arr::get($post, 'refund_amount',0)),Arr::get($post, 'type',0),'后台账户冻结资金');
        } catch(Kohana_Exception $e){
             $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

     /*
     * 扣费的api
     * @author 花文刚
     * @return array
     */
    public function action_deductExpenses() {
        $post = $this->request->post();
        $user_id = Arr::get($post, 'user_id');
        $type = Arr::get($post, 'type');
        $accountstatus = Arr::get($post, 'accountstatus');
        $note = Arr::get($post, 'note');

        $service = new Service_Account();
        if(!Arr::get($post, 'user_id')) $this->setApiReturn(405);
        $return = array();
        try {
            $return = $service->useAccount($user_id,$type,$accountstatus,$note);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /*
    * 获取账户余额
    * @author 花文刚
    * @return array
    */
    public function action_getAccount() {
        $post = $this->request->post();
        $user_id = Arr::get($post, 'user_id');

        $service = new Service_Account();
        if(!Arr::get($post, 'user_id')) $this->setApiReturn(405);
        $return = array();
        try {
            $return = $service->getAccount($user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

}