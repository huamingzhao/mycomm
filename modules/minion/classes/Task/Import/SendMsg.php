<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生成发送短信用户信息
 * @author 龚湧
 *
 */
class Task_Import_SendMsg extends Minion_Task
{
    /**
     * 未发送出去的
     * @var unknown
     * @author 龚湧
     */
    private $errorLog = "log/senderror.csv";

    /**
     * 成功发送出去的
     * @var unknown
     * @author 龚湧
     */
    private $sendedLog = "log/sended.csv";

    /**
     * 短信发送名单
     * @var unknown
     * @author 龚湧
     */
    private $msgList = "data/sendMessage.csv";

    /**
     * 执行参数
     * @var unknown
     * @author 龚湧
     */
    protected $_options = array();
    /**
     * 执行方法
     * (non-PHPdoc)
     * @see Kohana_Minion_Task::_execute()
     */
    protected function _execute(array $params){
        $msgList = $this->getMsgList();
        $count = 0;
        $suc = 0;//成功发送
        $fil = 0;//发送失败
        //var_dump($msgList);exit();
        $report = array(13585866096,13761062132);
        foreach($report as $rp){
            common::send_message($rp, "批量短信发送开始，发送时间是".date("Y m d H:i:s")."，task SendMsg", "online");
            sleep(1);
        }
        //短信发送内容
        if($msgList){
            foreach($msgList as $msg){
                //第一条
                //$msgBody = "快来创业赚钱选项目，为自己奋斗一次！把握机遇当老板，一句话的事。用你的手机号码{$msg['mobile']}登陆一句话网www.yijuhua.net，激活密码{$msg['password']}，选定火热好项目，开启财富人生。";
                //第二条
                //$msgBody = "创业当老板，就是一句话。一句话网，中国领先的投资创业平台，为您汇聚最火热赚钱项目，快来把握机遇迈向成功，用你的手机号码{$msg['mobile']}登录一句话网yijuhua.net，激活密码{$msg['password']}。";
                //第三条
                $msgBody = "创业当老板，给自己赚钱！为自己奋斗一次，就上一句话网www.yijuhua.net，中国领先的投资创业平台，为您汇聚最具前景好项目。抓住机遇，用你的手机号码{$msg['mobile']}登陆一句话网，激活密码{$msg['password']}。";
                $result = common::send_message($msg['mobile'], $msgBody, "online");
                $text = $msg['mobile'].",".date("Y m d H:i:s",time());
                if($result->retCode===0){
                    $this->writeLog($this->sendedLog,$text);
                    Minion_CLI::write($text."发送成功");
                    $suc++;
                }
                else{
                    $this->writeLog($this->errorLog,$text);
                    Minion_CLI::write($text."发送失败".$result->retCode."--".$result->retMsg);
                    $fil++;
                }
                Minion_CLI::wait(1,true);
                $count++;
                if($count>=2997){//TODO 限制改成3000
                    break;
                }
            }
        }
        foreach($report as $rp){
            common::send_message($rp, "批量短信发送结束，结束时间是".date("Y m d H:i:s")."。共计发送短信条数 {$count}，成功发送{$suc} 条", "online");
            sleep(1);
        }
        //exit();
        //$body = "长短信发送,200个字符.立秋，是二十四节气中的第13个节气，每年8月7、8或9日立秋。“秋”就是指暑去凉来，意味着秋天的开始。到了立秋，梧桐树必定开始落叶，因此才有“落一叶而知秋”的成语。从文字角度来看，“秋”字由禾与火字组成，是禾谷成熟的意思。立秋是秋季的第一个节气，而秋季又是由热转凉，再由凉转寒的过渡季节。";
        //var_dump(common::send_message(13916498334, $body, "online"));
    }

    /**
     * 发送消息列表
     * @return multitype:multitype:unknown Ambigous <>
     * @author 龚湧
     */
    protected function getMsgList(){
        $base_dir = APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;
        $string = file_get_contents($base_dir.$this->msgList);
        $list = array();
        if($string){
            $arrays = explode("\n", $string);
            foreach($arrays as $ul){
                if(trim($ul)){
                    $msg = explode(",", $ul);
                    $list[] = array('mobile'=>$msg[0],'email'=>$msg[1],'password'=>$msg[2]);
                }
            }
        }
        return $list;
    }

    /**
     * 写入日志 TODO 待完善
     * @param unknown $logFile
     * @param string $text
     * @author 龚湧
     */
    protected function writeLog($logFile,$text=""){
        $base_dir = APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;
        $handle = fopen($base_dir.$logFile, "ab+");
        fwrite($handle, $text.PHP_EOL);
        fclose($handle);
    }

}