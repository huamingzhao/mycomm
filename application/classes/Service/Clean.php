<?php
class Service_Clean {

    /**
     * 硬删除用户基本信息 涉及表 User
     */
    public function hardDel(ORM $user){
        if($user->loaded()){
            $user->delete();
            return true;
        }
        return false;
    }

    /**
     * 软删除用户基本信息
     */
    public function softDel(ORM $user){
        if($user->loaded()){
            $user->user_status = 0;
            $user->update();
            return true;
        }
        return false;
    }



    //----------------------------------------------

    /**
     * 日志文件路径
     * @param unknown $path
     * @author 龚湧
     */
    private function logFile($path=""){
        if($path){
            return $path;
        }
        return APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."cleanlog.php";
    }
    /**
     * 硬删除用户信息
     * @param unknown $user_id
     * @author 龚湧
     */
    public function HardDeleteUser($email,$onlyuser=0){
        if(!$email){
            return false;
        }
        $user = ORM::factory("User")
                ->where("email","=",$email)
                ->find();

        //记录删除日志
        if($user->loaded()){
            $this->printMsg($user->email, "", "");
            $user_id = $user->user_id;
            //只删除用户基本信息
            if($onlyuser){
                //删除用户信息
                $user->delete();
                $this->printMsg($user->email,"-","-");
                $this->printMsg($user->user_id, "删除用户", "成功");
                unset($user);
                return true;
            }


            //资讯投稿删除
            $Zixun_Article = ORM::factory("Zixun_Article")
                            ->where("user_id","=",$user_id)
                            ->find_all();
            if($this->delMultiRecords($Zixun_Article)){
                $this->printMsg($user_id, "删除资讯投稿", "成功");
            }
            unset($Zixun_Article);
            //服务购买删除
            $Buyservice = ORM::factory("Buyservice")
                          ->where("user_id","=",$user_id)
                          ->find_all();
            if($this->delMultiRecords($Buyservice)){
                $this->printMsg($user_id, "删除购买服务", "成功");
            }
            unset($Buyservice);
            //名片删除
            $cards = ORM::factory("Cardinfo")
                  ->where("from_user_id", "=", $user_id)
                  ->or_where("to_user_id","=",$user_id)
                  ->find_all();
            if($this->delMultiRecords($cards)){
                $this->printMsg($user_id, "名片删除", "成功");
            }
            unset($cards);
            //用户中心消息删除
            $messages = ORM::factory("Ucmsg")
                        ->where("user_id","=",$user_id)
                        ->find_all();
            if($this->delMultiRecords($messages)){
                $this->printMsg($user_id, "用户消息删除", "成功");
            }
            unset($messages);
            //名片收藏删除
            $cardsFavorite = ORM::factory("Favorite")
                             ->where("user_id","=",$user_id)
                             ->find_all();
            if($this->delMultiRecords($cardsFavorite)){
                $this->printMsg($user_id, "名片收藏删除", "成功");
            }
            unset($cardsFavorite);
            //个人用户或企业用户信息删除
            if($user->user_type === '1'){//企业用户
                $user_company = $user->user_company;
                //删除企业诚信指数
                $CompanyIntegrity = ORM::factory("CompanyIntegrity")
                                    ->where("user_id", "=", $user_id)
                                    ->find_all();
                if($this->delMultiRecords($CompanyIntegrity)){
                    $this->printMsg($user_id, "删除企业诚信指数", "成功");
                }
                unset($CompanyIntegrity);
                //企业服务状态
                //CompanyStatusInfo
                $CompanyStatusInfo = ORM::factory("CompanyStatusInfo")
                                        ->where("user_id", "=", $user_id)
                                        ->find_all();
                if($this->delMultiRecords($CompanyStatusInfo)){
                    $this->printMsg($user_id, "删除企业服务状态", "成功");
                }
                unset($CompanyStatusInfo);
                //删除企业附属信息
                if($user_company->loaded()){
                    $user_company->delete();
                    $this->printMsg($user_id, "删除企业附属信息", "成功");
                }
                unset($user_company);
            }
            elseif($user->user_type === '2'){//个人用户
                $user_person = $user->user_person;
                //删除个人从业经验
                $user_experiences = $user->user_experiences->find_all();
                if($this->delMultiRecords($user_experiences)){
                    $this->printMsg($user_id, "删除从业经验", "成功");
                }
                unset($user_experiences);
                //删除个人意向投资行业
                $personal_industry = ORM::factory("UserPerIndustry")
                                     ->where("user_id","=",$user_id)
                                     ->find_all();
                if($this->delMultiRecords($personal_industry)){
                    $this->printMsg($user_id, "删除个人意向投资行业", "成功");
                }
                unset($personal_industry);
                //上传个人意向投资地区
                $PersonalArea = ORM::factory("PersonalArea")
                                ->where("per_id","=",$user_id)
                                ->find_all();
                if($this->delMultiRecords($PersonalArea)){
                    $this->printMsg($user_id, "删除个人意向投资地区", "成功");
                }
                unset($PersonalArea);
                //删除个人附属信息
                if($user_person->loaded()){
                    $user_person->delete();
                    $this->printMsg($user_id, "删除个人附属信息", "成功");
                }
                unset($user_person);
            }
        }
        //无此用户,删除失败log
        else{
            echo "user not existing \n";
            //$this->printMsg($email, "", "",APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."cleanFiallog.php");
        }
    }

    /**
     * ORM删除多个对象
     * @param unknown $objs
     * @author 龚湧
     */
    public function delMultiRecords($objs){
        if($objs->count()){
            foreach ($objs as $obj){
                $obj->delete();
                unset($obj);
            }
            unset($objs);
            return true;
        }
        return false;
    }

    /**
     * 打印和写入日志 删除消息
     * @param unknown $user_id
     * @param unknown $action
     * @param unknown $msg
     * @author 龚湧
     */
    public function printMsg($user_id,$action,$msg,$logFile=""){
        $msg =  $user_id.$action.$msg."\n";
        //写入日志
        $handle = fopen($this->logFile($logFile), "ab+");
        fwrite($handle, $msg);
        fclose($handle);
        echo mb_convert_encoding($msg, "gb2312");
        unset($msg);

    }
}