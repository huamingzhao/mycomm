<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 清除用户 应删除
 * @author 龚湧
 *
 */
class Task_Import_User extends Minion_Task
{
    /**
     * 文件数据
     * @var unknown
     * @author 龚湧
     */
    private  function dataFiles(){
        return array(
                APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."3000.csv",
                APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."5000.csv"
        );
    }
    //详情日志
    protected $detailLog = "importlog.php";
    //错误日志
    protected $errorLog = "importerror.php";
    //合法参数列表
    protected $_options = array("harddel"=>0,"type");
    //生成的电子邮件列表
    protected $genEmalList = "genemaillist.php";
    /**
     * 交互性任务
     * (non-PHPdoc)
     * @see Kohana_Minion_Task::_execute()
     */
    protected function _execute(array $params){
        $service = New Service_Import_User();
        Minion_CLI::write(
            array(
                    '-----------------------------------------------------------',
                    '|    开始导入数据',
                    '-----------------------------------------------------------'
            )
        );
        //经过预处理数据
        $userList = $this->handleUserList($this->getContentList());
        Minion_CLI::write(memory_get_usage()/(1024*1024)."M");
        $service_import = new Service_Import_User();
        //print_r($userList[0]);
        foreach($userList as $lst){
            $service_import = new Service_Import_User();
            $result = $service_import->createUserBisc($lst);
            //if(is_string($result )){
                //print_r($result);
            //}
            gc_collect_cycles();
        }
        //$service_import->createUserBisc($userList[0]);
    }

    /**
     * 用户数据处理
     * 1. 剔除错误电话号码和重复电话号码
     * 2. 生成电子邮件
     * @param unknown $list
     * @author 龚湧
     */
    public function handleUserList($lists){
        //正确数据
        $true_data = array();
        //所有手机号码
        $all_mobile = array();
        //类型错误手机号
        $mobile_error_count = 0;
        //重复手机号
        $mobile_repeat_count = 0;
        //进一步处理
        foreach($lists as $list){
            //1.不正确手机号码
            if(strlen($list['mobile']) != 11){
                $text = $list['mobile']."#手机号码格式错误";
                Minion_CLI::write($text);
                $mobile_error_count++;
                $this->writeLog($this->errorLog,$text);
                continue;
            }
            //2.重复手机号码
            if(array_key_exists($list['mobile'], $all_mobile)){
                $text = $list['mobile']."#重复的手机号码";
                Minion_CLI::write($text);
                $mobile_repeat_count++;
                $this->writeLog($this->errorLog,$text);
                continue;
            }
            //3.生成电子邮件 #rules#
            if(!trim($list['email'])){
                $list['email'] = $list['mobile']."@".substr($list['mobile'],0,3).".com";
                Minion_CLI::write("生成了电子邮件".$list['email']);
                $this->writeLog($this->genEmalList,$list['email']);
            }

            $all_mobile[$list['mobile']] = $list['mobile'];//保存到以后的匹配中
            $true_data[] = $list;
        }
        //记录错误日志
        $text = "###手机号错误的数量有--".$mobile_error_count."--个";
        Minion_CLI::write($text);
        $this->writeLog($this->errorLog,$text);
        $text = "###手机号重复数量有--".$mobile_repeat_count."--个";
        Minion_CLI::write($text);
        $this->writeLog($this->errorLog,$text);
        unset($list);
        unset($all_mobile);
        unset($text);
        unset($lists);
        gc_collect_cycles();
        return $true_data;
    }

    /**
     * 获取转换的名单格式
     * @param unknown $file
     * @author 龚湧
     */
    public function getContentList(){
        $datas = $this->dataFiles();
        $list = array();
        $counter = 0;
        if(!empty($datas)){
            foreach($datas as $data){
                if(file_exists($data)){
                    $content = file_get_contents($data);
                    $array = explode("\r\n", $content);
                    if(!empty($array)){
                        foreach ($array as $user){
                            if(!empty($user)){
                                $ret = explode(",", $user);
                                $list[$counter]['full_name'] = $ret[0];//用户名
                                $list[$counter]['mobile'] = $ret[1];//电话号码
                                $list[$counter]['project_name'] = $ret[2];//项目名称
                                $list[$counter]['touzi_area'] = $ret[3];//投资地区
                                $list[$counter]['address'] = $ret[4];//常用住址
                                $list[$counter]['create_time'] = strtotime($ret[5]);//创建时间,转换成时间戳
                                $list[$counter]['email'] = $ret[6];//电子邮件
                                $list[$counter]['ist_industry'] = $ret[7];//感兴趣行业，模糊匹配
                                $counter++;
                            }
                        }
                    }
                }
            }
        }
        //echo date("Y m d H:i:s",strtotime("2012-12-25 12:22"));
        //print_r($list);
        //Minion_CLI::write(memory_get_usage());
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