<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 清除用户 应删除
 * @author 龚湧
 *
 */
class Task_Clean_User extends Minion_Task
{
    //详情日志
    protected $detailLog = "cleanlog.php";
    //错误日志
    protected $errorLog = "cleanerror.php";
    //合法参数列表
    protected $_options = array("harddel"=>0,"type");
    /**
     * 交互性任务
     * (non-PHPdoc)
     * @see Kohana_Minion_Task::_execute()
     */
    protected function _execute(array $params){
        Minion_CLI::write(
            array(
                    '-----------------------------------------------------------',
                    '|    --harddel = 1 硬删除,默认 0 为软删除',
                    '|    --type=badreg 为清除注册时间和上次登录时间都为0的用户',
                    '|    --type=badtemp 为清除 邮箱后缀为.temp 的用户',
                    '-----------------------------------------------------------'
            )
        );
        $harddel = Arr::get($params,"harddel");
        //---判断硬删除和软删除,手动操作，定时任务可以省掉这段
        if($harddel){
            $todo = Minion_CLI::read("这是个危险操作,你确定要硬删除这些账户吗？",array("y","n"));
            if($todo == 'y'){
                Minion_CLI::write("硬删除账号开始...,5s后开始删除,紧急取消Ctrl+C...");
                Minion_CLI::wait(5,true);
            }
        }
        else{
            $todo = Minion_CLI::read("你确定要软删除这些账户吗？",array("y","n"));
            if($todo == 'y'){
                Minion_CLI::write("软删除账号开始...,5s后开始删除,紧急取消Ctrl+C...");
                Minion_CLI::wait(5,true);
            }
        }
        if($todo == "n"){
            Minion_CLI::write("您取消了删除操作");
            exit();
        }
        //------------------------

        $service = new Service_Clean();
        //清除注册时间和上次登录时间都为0的用户
        if(Arr::get($params,"type") =='badreg'){
            $users = ORM::factory("User")
                        ->where("reg_time","=",0)
                        ->and_where("last_logintime","=",0)
                        ->find_all();
        }
        //为清除 邮箱后缀为.temp 的用户
        elseif(Arr::get($params,"type") =='badtemp'){
            $users = ORM::factory("User")
                    ->where("email","like","%.temp")
                    ->find_all();
        }
        else{
            $users = false;
            Minion_CLI::write("    无此类型，请使用参数 --type={类型}选择类型，确认后再输入");
        }

        //删除用户的数据
        if($users){
            if($users->count()){
                foreach($users as $user){
                    //硬删除
                    if($harddel){
                        if ($service->hardDel ( $user )) {
                            $text = "[".date("Y m d H:i:s")."]--".$user->user_id . "--" . $user->email . "--硬删除基本信息删除成功";
                            Minion_CLI::write ( $text );
                            $this->writeLog ( $this->detailLog, $text );
                        }
                    }
                    //软删除
                    else{
                        if ($service->softDel ( $user )) {
                            $text = "[".date("Y m d H:i:s")."]--".$user->user_id . "--" . $user->email . "--软删除基本信息删除成功";
                            Minion_CLI::write ( $text );
                            $this->writeLog ( $this->detailLog, $text );
                        }
                    }
                    usleep(1000);
                    Minion_CLI::write("内存使用了".memory_get_usage()/(1024*1024)."M ,休息 1000ms");
                }
                $text = "[".date("Y m d H:i:s")."]--任务结束";
                $this->writeLog($this->detailLog,$text);
                Minion_CLI::write($text);
            }
            else{
                Minion_CLI::write("无符合条件的用户账号");
            }
        }
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