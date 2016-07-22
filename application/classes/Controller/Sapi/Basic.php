<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 基类
 * @author 施磊
 *
 */
class Controller_Sapi_Basic extends Controller{
    //状态码
    static $CODE = array(
            '200' => 'ok',
            '403' => '没有权限',
            '404' => '未找到对应方法',
            '405' => '参数不完整',
            '500' => '服务器错误',
            '501' => '参数错误'
        );
     /**
     * 返回ajax状态
     * @author 施磊
     * @param int $code 状态码
     * @param string  $msg 提示信息
     * @param  array $data 返回的数据集
     * @param int $type 0 为 直接echo 1 是return
     * @return json
     */
    public function setApiReturn($code, $msg = '', $data = array(), $type = 0) {
        $arr = array('code' => $code, 'msg' => Arr::get(self::$CODE, $code, $msg), 'data' => $data, 'date' => time());
        $return = json_encode($arr);
        if($type) {
          return $return;
        }else{
          echo $return;exit;
        }
    }
    /**
     * index
     * @auhtor 施磊
     */
    public function action_index() {
        echo 123;
    }
}