<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生成发送短信用户信息
 * @author 龚湧
 *
 */
class Task_Import_GenlistID extends Minion_Task
{
    /**
     * 发送短信格式
     * @var unknown
     * @author 龚湧
     */
    private $genList = "data/sendMessageId.csv";

    /**
     * 密码文件
     * @var unknown
     * @author 龚湧
     */
    private $password = "data/password.csv";

    /**
     * 执行
     * (non-PHPdoc)
     * @see Kohana_Minion_Task::_execute()
     */
    protected function _execute(array $params){
        $this->getUserList();
    }

    /**
     * 获取导入用户
     *
     * @author 龚湧
     */
    private function getUserList(){
        $base_dir = APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;
        $passwords = file_get_contents($base_dir.$this->password);

        $email_list = array();
        $password_list = explode("\n", $passwords);
        if($password_list){
            foreach($password_list as $ul){
                if(trim($ul)){
                    $li = explode(",", $ul);
                    $email_list[$li[0]] = $li[1];
                }
            }
        }

        $userList = ORM::factory("User")
                            ->where("source_type","=",1)
                            ->find_all();
        if($userList->count()){
            foreach($userList as $user){
                $log = $user->user_id.",".$user->mobile.",".$user->email.",".$user->last_logintime;
                $this->writeLog($this->genList,$log);
                Minion_CLI::write($log);
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