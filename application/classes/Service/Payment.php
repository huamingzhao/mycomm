<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @brief 支付操作类
 * @author 周进
 * @date 2013/01/17
 * @version 1.0
 */
class Service_Payment{

    //支付插件名称(默认为alipay支付宝)新的支付接口从配置文件payment.php添加配置
    public $payName = 'alipay';
    //支付提交的地址
    public $submitUrl = 'https://mapi.alipay.com/gateway.do?';
    //支付插件所支持的货币单位
    public $supportCurrency = array("CNY"=>"01");
    //支付支持的地区
    public $supportArea =  array("AREA_CNY");
    //支付插件字符集
    public $charset = 'utf-8';
    //提交方式
    public $method = "get";
    //加密方式
    public $sign_type = 'MD5';
    //支付完成后，同步回调地址
    public $callbackUrl = '';
    //异步服务器接收地址
    public $serverCallbackUrl = '';

    public function __construct($payName="alipay")
    {
        $this->payName = $payName!=""?$payName:$this->payName;
        //预设的默认的接收返回地址http://www.czzs.com/ajaxcheck/returnPay ---需要指定http完全地址---
        $this->callbackUrl = $this->callbackUrl!=""?$this->callbackUrl:URL::website('/ajaxcheck/returnPay');
        $this->serverCallbackUrl = $this->serverCallbackUrl!=""?$this->serverCallbackUrl:URL::website('/ajaxcheck/notifyPay');
        $this->submitUrl = $this->submitUrl;
    }

    /**
    * @brief form提交事件
    * @param array $payment 订单的详细信息 参数包括下面的字段
    * 说明$payment = array('method'=>'post',		//表单提交方式
                'callbackUrl'=>'',				//返回通知URL http://地址
                'serverCallbackUrl'=>'',        //返回异步服务器通知URL http://地址
                'submitUrl'=>'',                //表单提交地址
                'out_trade_no'=>'122334',       //订单号
                'total_fee'=>'1.00',            //订单总额
                'subject'=>"测试支付",			//订单标题
                'body'=>'测试支付');				//订单详情
    * @param string $type 额外参数，支付宝时用来判断类型
    * @return html 返回支付需提交的表单页面
    */
    public function toSubmit($payment,$type=1)
    {
        //初始化表单数据返回值
        $return = array();
        //返回通知URL
        $this->callbackUrl = Arr::get($payment, 'callbackUrl',$this->callbackUrl);
        //返回异步服务器通知URL
        $this->serverCallbackUrl = Arr::get($payment, 'serverCallbackUrl',$this->callbackUrl);
        //form提交的地址
        $this->submitUrl = Arr::get($payment, 'submitUrl',$this->submitUrl);
        //支付接口配置信息
        $paymentConfig = Kohana::$config->load('payment.'.$this->payName);
        $exter_invoke_ip = Arr::get($_SERVER,'SERVER_ADDR',$_SERVER['HTTP_HOST']);
        //订单总金额
        if (strrpos(Arr::get($payment, 'total_fee'), "."))
            $total_fee = Arr::get($payment, 'total_fee')!=""?number_format(Arr::get($payment, 'total_fee'),2,".",""):'0.00';
        else
            $total_fee = Arr::get($payment, 'total_fee')!=""?Arr::get($payment, 'total_fee').".00":'0.00';

        //当为支付宝时，指定接口类型
        if($this->payName=='alipay'){
            switch ($type)
            {
                //使用标准双接口
                case '0':
                    $service_name = 'trade_create_by_buyer';
                    break;
                    //使用即时到帐交易接口
                case '1':
                    $service_name = 'create_direct_pay_by_user';
                    break;
                    //使用担保交易接口
                case '2':
                    $service_name = 'create_partner_trade_by_buyer';
                    break;
            }

            //构造要请求的参数数组
            $return = array(
                    "service" => $service_name,											//使用接口类型
                    "partner" => trim($paymentConfig['partner_id']),					//合作者身份(parterID)
                    "payment_type"	=> 1,												//支付类型,默认1商品购买不需要修改
                    "notify_url"	=> $this->serverCallbackUrl,						//服务器异步返回通知的地址
                    "return_url"	=> $this->callbackUrl,								//服务器同步返回通知的地址
                    "seller_email"	=> $paymentConfig['partner_name'],					//卖家支付宝帐户
                    "out_trade_no"	=> Arr::get($payment, 'out_trade_no',time()),		//订单号
                    "subject"	=> Arr::get($payment, 'subject','账户充值'),				//订单描述
                    "total_fee"	=> $total_fee,											//订单总额
                    "body"	=> Arr::get($payment, 'body','账户充值'),					//订单详情
                    "_input_charset"	=> trim(strtolower($this->charset)),			//表单编码
                    /**以下数据暂未使用后续需要可补上**/
//                     "show_url"	=> $this->callbackUrl,									//商品展示地址
//                     "anti_phishing_key"	=> Arr::get($payment, 'anti_phishing_key',''),	//防钓鱼时间
//                     "exter_invoke_ip"	=> Arr::get($payment, 'exter_invoke_ip','')		//防钓鱼IP
            );
            //整合签名加密
            ksort($return);
            reset($return);
            $mac= "";
            foreach($return as $k => $v)
            {
                $mac .= '&'.$k.'='.$v;
            }
            $mac = substr($mac,1);

            $return['sign'] = md5($mac.$paymentConfig['privateKey']);//验证信息
            $return['sign_type'] = strtoupper(trim($this->sign_type));//验证信息

            $html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n
                    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-US\" lang=\"en-US\" dir=\"ltr\">\n
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><head>\n</header><body><div>Redirecting...</div>";
            $html.= "<form id='alipaysubmit' name='alipaysubmit' action='".$this->submitUrl."' method='".$this->method."'>";
            foreach ($return as $k => $v)
            {
                $html .= "<input  type=\"hidden\" name=\"" . urldecode($k) . "\" value=\"" . htmlspecialchars ( $v ) . "\" />";
            }
            $html.="</form>";
            $html.="<script>document.forms['alipaysubmit'].submit();</script>";
        }
        return $html;
    }


    /**
    * @brief 支付返回结果回调方法只针对验签
    * @param array	return	支付完（支付宝或其他支付方式）返回的数据
    * @return int 	支付状态
    */
    public function callback($return)
    {
        //支付接口配置信息
        $paymentConfig = Kohana::$config->load('payment.'.$this->payName);
        //接口为支付宝时
        if($this->payName=="alipay"){
            //对数组按照键名排序，保留键名到数据的关联。
            ksort($return);
            //检测参数合法性
            $temp = array();
            foreach($return as $k=>$v)
            {
                if($k!='sign'&&$k!='sign_type')
                {
                    $temp[] = $k.'='.$v;
                }
            }
            $testStr = implode('&',$temp).$paymentConfig['privateKey'];

            //验证返回数据是否正确
            if($return['sign']==md5($testStr))
            {
                //订单总价
                $money = $return['total_fee'];
                //订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
                $message = $return['body'];
                //订单编号
                $tradeno = $return['out_trade_no'];
                switch($return['trade_status'])
                {
                    //该判断表示买家已经确认收货，这笔交易完成
                    case 'TRADE_FINISHED':
                    case 'TRADE_SUCCESS':
                    case 'WAIT_SELLER_SEND_GOODS':
                    {
                        $this->logResult($return,'PAY_SUCCESS');
                        return 'PAY_SUCCESS';
                        break;
                    }
                    default:
                        $this->logResult($return,'PAY_FAILED');
                        return 'PAY_FAILED';
                        break;
                }
            }
            else
            {
               return $this->logResult($return,'PAY_ERROR');
                return 'PAY_ERROR';
            }
        }
    }

    /**
     * 操作日志记录 保存于czzs_pay_return_log
     */
    public function logResult($query='',$trade_status='PAY_ERROR') {
        $temp = '';
        foreach($query as $k=>$v)
        {
            if($k!='sign'&&$k!='sign_type')
            {
                $temp.= $k.'='.$v."&";
            }
        }
        $model = ORM::factory('Payreturnlog');
        $model->log_content = $temp;
        $model->log_status = $trade_status;
        $model->log_time = time();
        try {
            $model->create();
        }
        catch (Kohana_Exception $e){
        }
    }
}
