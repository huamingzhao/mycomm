<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 清除用户 应删除
 * @author 龚湧
 *
 */
class Task_CleanUser extends Minion_Task
{
    protected $_options = array("onlyuser"=>0,"type");
    /**
     * Generates a help list for all tasks
     *
     * @return null
     */
    protected function _execute(array $params)
    {
        $service = new Service_Clean();
        if(Arr::get($params,"type") =='badreg'){
            $users = ORM::factory("User")
                       ->where("reg_time","=",0)
                       ->and_where("last_logintime","=",0)
                       ->find_all();
            //print_r($users);exit();
            if($service->delMultiRecords($users)){
                $service->printMsg("删除垃圾账户成功", "", "");
            }
            else{
                $service->printMsg("无注册时间为0的垃圾账户", "", "");
            }
            exit();
            /*
            if($users->count()){
                foreach ($users as $u){
                    if($u->email){
                        $delList[] = $u->email;
                    }
                    unset($u);
                }
                unset($users);
            }
            if(!isset($delList)){
                echo "no records \n";
                exit();
            }
            */
        }
        elseif(Arr::get($params,"type") =='badtemp'){
            $users = ORM::factory("User")
                    ->where("email","like","%.temp")
                    ->find_all();
            //print_r($users);exit();
            if($service->delMultiRecords($users)){
                $service->printMsg("删除.tmp垃圾账户成功", "", "");
            }
            else{
                $service->printMsg("无.tmp注册账垃圾账户", "", "");
            }
            exit();
        }
        elseif(Arr::get($params,"type") == 'filelist'){
            $delList = file_get_contents(APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."cleanlist.php", "rb");
            $delList = explode("\r\n", $delList);
        }
        else{
            echo "please set the '--type' \n";exit();
        }
        if($delList){
            $service->printMsg("====", "删除用户开始".microtime(true), date("Y m d H:i:s")."===");
            $couter = 0;
            foreach ($delList as $key=>$delUser){
                if(ob_get_level() == 0){
                    ob_start();
                }
                $service->HardDeleteUser(trim($delUser),Arr::get($options,"onlyuser",0));
                unset($delList[$key]);
                unset($delUser);
                ob_flush();
                flush();
                ob_end_clean();
                usleep(1000);echo "sleep 1000ms\n";
                echo "memery ".memory_get_usage()."\n";
                $couter++;
            }
            $service->printMsg("====", "删除用户结束,共计删了 {$couter} 条".microtime(true), date("Y m d H:i:s")."===");
            $service->printMsg("任务结束", "", "");
        }
    }
}
