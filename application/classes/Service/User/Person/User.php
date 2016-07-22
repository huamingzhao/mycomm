<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 个人用户中心
 * @author 曹怀栋
 *
 */
class Service_User_Person_User extends Service_User {

    /**
     * sso
     * 个人用户快速注册
     *
     * @author 龚湧
     */
    public function personQuickReg($form){
        //检查手机是否已被绑定
        if($this->isMobileBinded(Arr::get($form, "mobile"))){
            return false;
        }
        $user_id = null;
        $valid = new Validation($form);
        $valid->rule("email", "not_empty");
        $valid->rule("email", "email");
        $valid->rule("mobile", "not_empty");
        $password = Arr::get($form,'password');
        if($valid->check()){
            //检查唯邮件唯一性
             //sso 赵路生 2013-11-14
            if(Service_Sso_Client::instance()->isRegNameValid($form['email'])){
                //SID md5
                $sid_md5    = arr::get($_COOKIE, 'Hm_lvqtz_sid');
                $userinfo = array(
                    'email' => Arr::get($form, 'email'),
                    'mobile'=>Arr::get($form,'mobile'),
                    'user_name' => Arr::get($form,'user_name'),
                    'password' => $password,
                    'user_type' => 2, //个人用户
                    'valid_mobile'=>1,
                );
                $client = Service_Sso_Client::instance();
                $result = $client->appRegister($userinfo,"EMAIL");
                if($result){
                    if(Arr::get($result,"error") === false){
                        $user = Arr::get($result,"return");
                        $user_id = $user['id'];
                        //初始化 user==
                        try{
                            $basic = ORM::factory("User");
                            $basic->user_id = $user_id;
                            $basic->last_logintime = time();
                            $basic->last_login_ip = ip2long(Request::$client_ip);
                            $basic->sid = $sid_md5;
                            $basic->aid= cookie::get ( 'cpa_aid', '' );
                            $basic->create();

                            $basic->user_person->per_user_id = $basic->user_id;
                            $basic->user_person->per_area = Arr::get($form,"area_id");
                            $basic->user_person->per_city = Arr::get($form,"city_id");
                            $basic->user_person->per_gender = Arr::get($form,"gender");
                            $basic->user_person->per_realname = Arr::get($form,"user_name");
                            $basic->user_person->create();
                        }catch(Kohana_Exception $e){
                            //抛出错误代码
                        }
                        //初始化本地结束==
                        //自动登录
                        $client->login($userinfo['email'], $userinfo['password']);
                        //返回用户信息
                        $return = $client->getUserInfoById($user_id);
                        $return->user_id = $return->id;
                        $user_ser = new Service_User();
                        $return->sid = $sid_md5;
                        return $return;
                    }
                }

            }
        }
        return false;
    }

//定义一个值 现在最多多少条地域
    private $limitNum = 5;
    /**
     * 个人名片公开度
     * @author 曹怀栋
     * @param string
     */
    public function cardOpenStutas($per_id, $type) {
        if ($type != 1 && $type != 2 && $type != 3 && $type != 4) {
            return false;
        }
        $update = ORM::factory("Personinfo", $per_id);
        if (empty($update->per_open_stutas)) {
            return false;
        }
        $update->per_open_stutas = $type;
        if ($update->update()) {
            return $type;
        } else {
            return false;
        }
    }

    /**
     * 个人名片公开度
     * @author 钟涛
     * @param string
     */
    public function cardOpenStutas2($user_id, $type) {
        $update = ORM::factory("Personinfo")->where('per_user_id','=',$user_id)->find();
        if ($update->loaded()) {
            $update->per_open_stutas = $type;
            $update->update();
            return $type;
        } else {
            return false;
        }
    }

    /**
     * @sso
     * 验证邮箱是否已通过验证
     * @author 周进
     */
    public function getEmailValidCount($userid) {
        //return ORM::factory('User')->where('user_id', '=', $userid)->where('valid_email', '=', self::ENABLE_STATUS)->count_all();
        $client = Service_Sso_Client::instance();
        $userinfo = $client->getUserInfoById($userid);
        if($userinfo){
            $valid = (bool)Arr::get((array)$userinfo,"valid_email");
            return $valid;
        }
        return false;
    }

    /**
     * 已验证状态(手机、邮箱、企业资质等)
     */

    const ENABLE_STATUS = 1;

    /**
     * 获取我的个人信息user_person表
     * @author 周进
     */
    public function getPerson($userid) {
        $user = ORM::factory('Personinfo')->where('per_user_id', '=', $userid)->find();
        return $user;
    }

    /**
     * @sso
     * 获取个人信息两张表
     * @author 周进
     */
    public function getPersonInfo($userid){
        $user = Service_Sso_Client::instance()->getUserInfoById($userid);
        $user_basic = ORM::factory('User', $userid);
        //对应关系获取
        $user->user_person = $user_basic->user_person;
        $result['mobile'] = $user->mobile;
        $result['email'] = $user->email;
        $result['last_logintime'] = $user_basic->last_logintime;
        $result['user_person'] = $user->user_person;
        $result['valid_mobile'] = $user->valid_mobile;
        $result['valid_email'] = $user->valid_email;
        $result['person'] = $this->projectInvestmentProups($user->user_person);//获取标签
        $result['tag_name'] =  $result['person']->per_per_label;//获取标签
        $result['per_birthday'] = $user->user_person->per_birthday;
        $result['per_education'] = $user->user_person->per_education;
        $result['per_connections'] = $user->user_person->per_connections;
        $result['per_investment_style'] = $user->user_person->per_investment_style;
        $result['per_amount'] = $user->user_person->per_amount;
        $result['per_realname'] = $user->user_person->per_realname;
        $result['per_gender'] = $user->user_person->per_gender;
        $result['per_area'] = $user->user_person->per_area;
        $result['per_qq'] = $user->user_person->per_qq;
        $result['per_card_id'] = $user->user_person->per_card_id;
        $result['per_remark'] = $user->user_person->per_remark;
        $result['per_auth_status'] = $user->user_person->per_auth_status; //获取身份证验证状态
        $result['per_photo'] = $user->user_person->per_photo; //获取头像
        //年龄
        $result['per_age']=0;//默认0岁
        if($result['per_birthday']){
            $thisyears=date("Y");
            $peryears=date("Y",$result['per_birthday']);
            $age=$thisyears-$peryears;
            if($age>0){
                $result['per_age'] = $age ;
            }
        }
        //学历
        if($result['per_education'] && $result['per_education']<=10){
            $edu_arr = common::getPersonEducation();
            $result['per_education'] = $edu_arr[$result['per_education']];
        }else{
            $result['per_education']='暂无信息';//默认空
        }
        //我的人脉关系
        if($result['per_connections'] && $result['per_connections']<=5){
            $con_arr = common::connectionsArr();
            $result['per_connections'] = $con_arr[$result['per_connections']];
        }else{
            $result['per_connections']='无';//默认空
        }
        //我的投资风格
        if($result['per_investment_style'] && $result['per_investment_style']<=2){
            $invest_arr = common::investmentStyle();
            $result['per_investment_style'] = $invest_arr[$result['per_investment_style']];
        }else{
            $result['per_investment_style']='不限';//不限
        }
        //意向投资金额
          $monarr= common::moneyArr();
        $per_amount=$result["per_amount"]== 0 ? '无': $monarr[$result["per_amount"]];
        $result['per_amount']=$per_amount;
        //是否有从业经验
        $result['ishasexperience'] = $this->getExperienceCount($userid);
        //最后登录时间
        $result['last_logintime'] = date('Y-m-d',$result['last_logintime'] );
        return $result;
    }

    /**
     * 简洁版
     * @author 钟涛
     */
    public function getPersonInfoJianjie($userid){
        $user_person=ORM::factory('Personinfo')->where('per_user_id','=',$userid)->find();
        $result['person'] = $user_person;//获取标签
        return $result;
    }
    /**
     * 获取个人信息 新版
     * @author 赵路生
     */
    public function getPersonInfoNew($userid,$user_loginid){
        $userlist=array();
        $resultlist=array();
        $page = '';
        if(is_array($userid) && isset($userid) && $userid){
            $user_per = ORM::factory('Personinfo');
            $model = $user_per->where('per_user_id','in',$userid);
            //分页信息
            $page = Pagination::factory(array(
                    'total_items'    => $model->reset(false)->count_all(),
                    'items_per_page' => 10,
            ));
            $perinfo_list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('per_updatetime', 'DESC')->find_all( );

            $inves_service = new Service_Platform_SearchInvestor();
            $money_arr = common::moneyArr();
            $user = ORM::factory('User');
            foreach($perinfo_list as $list){
                $resultlist[] = $inves_service->addResultDataByCom2($list, $user_loginid);
            }
        }
        //按照活跃度从高到低排序
        return array(
                'resultlist'=>common::multi_array_sort($resultlist,'this_huoyuedu',SORT_DESC),
                'page'=>$page,
        );
    }
    /**
     * 获取我的名片模板信息
     * @param  [int] $com_id [企业用户信息表ID]
     * @author 周进
     */
    public function getCardStyleInfo($urlpage, $type = "person") {
        //获取名片模板数量(目前名片模板图片存在在数组中)
        $count = count(common::card_img_small($type));
        $page_size = 9; //分页大小
        $page = Pagination::factory(array(
                    'total_items' => $count,
                    'items_per_page' => $page_size,
                ));
        //以下对数组信息模拟分页情况
        $return_arr = array();
        $return_arr['list'] = array_slice(common::card_img_small($type), ($urlpage - 1) * $page_size, $page_size);
        $return_arr['page'] = $page;
        return $return_arr;
    }

    /**
     * @sso
     * 更新个人名片模板信息
     * @author 周进
     */
    public function updateCardStyleInfo($user_id, $cardstyle) {
        $model = ORM::factory('User', $user_id);
        $model->card_style = $cardstyle;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 上传个人身份证,更新数据库
     * @author 周进
     * @param $_FILES $files
     * @param string $filekey
     * @param int user_id
     * @return bool
     */
    public function uploadIdentification($files, $filekey, $userid) {
        if ($files[$filekey]['error'] != "")
            return false;
        $org = getimagesize($files[$filekey]['tmp_name']);
        $result = common::uploadPic($files, $filekey, array(array($org[0], $org[1]), array(300, 200)));
        if (Arr::get($result, 'path')) {
            $person = ORM::factory('Personinfo');
            $data = $person->where('per_user_id', '=', $userid)->find()->as_array();
            $person->per_id = $data['per_id'];
            if ($data['per_identification_photo'] == "")
                $person->per_identification_photo = str_replace('/s_', '/b_', Arr::get($result, 'path'));
            else
                $person->per_identification = str_replace('/s_', '/b_', Arr::get($result, 'path'));
            try {
                $person->update();
                return true;
            } catch (Kohana_Exception $e) {
                return false;
            }
        }
        else
            return false;
    }

    /**
     * 更新身份证状态 0为未操作，1为已审核，2为已上传待审核
     * @author 周进
     * @return bool
     */
    public function updateIdentification($status, $userid) {
        if ($status < 0 || $userid == "")
            return false;
        $person = ORM::factory('Personinfo');
        $data = $person->where('per_user_id', '=', $userid)->find()->as_array();
        $person->per_auth_status = $status;
        $person->per_auth_time = time();
        try {
            $person->update();
            return true;
        } catch (Kohana_Exception $e) {
            return false;
        }
    }

    /**
     * 删除身份证图片
     * @author 周进
     */
    public function deleteIdentification($type, $userid) {
        $person = ORM::factory('Personinfo');
        $data = $person->where('per_user_id', '=', $userid)->find()->as_array();
        if ($type == "identification") {
            //删除服务器上原有图片
            common::deletePic($data['per_identification']);
            $person->per_identification = "";
        } elseif ($type == "photo") {
            common::deletePic($data['per_identification_photo']);
            $person->per_identification_photo = "";
        }
        try {
            $person->update();
            return true;
        } catch (Kohana_Exception $e) {
            return false;
        }
    }

    /**
     * 根据用户id获取用户从业经验
     * @author 龚湧
     * @param int $user_id
     */
    public function listExperience($user_id) {
        $result = array();
        $experiences = ORM::factory("Experience")->where('exp_user_id','=',$user_id)->where("exp_status", "=", "1")->order_by("exp_starttime", "desc")->find_all();
        if (!$experiences->count()) {
            return false;
        }
        $area_service = new Service_Public();
        foreach ($experiences as $key => $experience) {
            $info = $experience->as_array();
            $result[$key] = $info;
            $result[$key]['pro_name'] = $area_service->getAreaName($info['pro_id']);
            $result[$key]['area_name'] = $area_service->getAreaName($info['area_id']);
        }
        return $result;
    }

    /**
     * 删除从业经验，更新从业经验状态
     * @author 龚湧
     * @param int $user_id
     * @param int $exp_id
     * @throws Kohana_Exception
     * @return boolean
     */
    public function delExperience($user_id, $exp_id) {
        $experience = ORM::factory("Experience", $exp_id);
        if ($experience->user->user_id == $user_id) {
            $experience->exp_status = 0;
            try {
                $experience->save();
            } catch (Kohana_Exception $e) {
                throw $e;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取一条从业经验记录
     * @author 龚湧
     * @param int $user_id
     * @param int $exp_id
     * @return ORM|boolean
     */
    public function getExperienceById($user_id, $exp_id) {
        $experience = ORM::factory("Experience")
                ->where("exp_user_id", "=", $user_id)
                ->where("exp_id", "=", $exp_id)
                ->where("exp_status", "=", 1)
                ->find();
        if ($experience->user->user_id == $user_id) {
            return $experience;
        }
        return false;
    }

    /**
     * @sso
     * 判断用户是否有从业经验
     * @author 龚湧
     * @param unknown $user_id
     * 2013-11-5 下午2:10:54
     * @return boolean
     */
    public function isExperienceExist($user_id){
        $exp = ORM::factory("Experience")
                    ->where("exp_user_id","=",$user_id)
                    ->where("exp_status","=","1")->count_all();
        return (bool)$exp;
    }

    /**
     * 个人基本信息判断
     * @author 曹怀栋
     */
    public function personCheck($form) {
        //表单验证开始
        $valid = new Validation($form);
        $valid->rule("per_realname", "not_empty");
        $valid->rule("per_gender", "not_empty");
        $valid->rule("per_gender", "digit");
        $valid->rule("per_phone", "not_empty");
        $valid->rule("per_phone", "digit");
        $valid->rule("per_amount", "not_empty");
        $valid->rule("per_amount", "digit");
        $valid->rule("per_industry", "not_empty");
        $valid->rule("per_industry", "digit");
        $valid->rule("per_area", "not_empty");
        $valid->rule("per_area", "digit");
        $valid->rule("per_remark", "not_empty");
        if (!$valid->check()) {
            return false;
        }
        return true;
    }

    /**
     * @sso
     * 更新个人用户基本信息
     * @author 曹怀栋
     */
    public function updatePerson($form) {
        $user = ORM::factory('user', $form['user_id']);
        $user_id = $form['user_id'];
        $mobile = $user->mobile;
        $per_phone = trim($form['per_phone']);
        if (isset($per_phone) && $mobile != $per_phone) {//判断手机号码是否修改
            $count = ORM::factory('user')->where('valid_mobile', '=', 1)->where('mobile', '=', $per_phone)->count_all();
            if ($count == 0) {//判断是否存在已经绑定的手机号码
                $serice = new Service_User_Company_User();
                $serice->unbindMobile($form['user_id']); //解除手机绑定
                $user->mobile = $per_phone;
                $user->save();
            } else {
                $result = array('mobile_error' => '这个号码已经存在，并且已经绑定!', 'status' => -1);
                return $result;
            }
        }
        $persons = ORM::factory('Personinfo', arr::get($form, 'per_id'));
        if (empty($persons->per_id)) {
            return false;
        }
        //修改我的标签信息
        $datagroups = isset($form['Investment_groups']) ? $form['Investment_groups'] : array();
        unset($form['Investment_groups']);
        $res_crowd = $this->updatePersonCrowd($user_id, $datagroups);
        if ($res_crowd != true)
            return false;

        $form['per_updatetime'] = time();
        if(isset($form['per_photo']) && $form['per_photo']){
            $form['per_photo'] = common::getImgUrl($form['per_photo']);
        }
        unset($form['user_id']);
        unset($form['per_photo_old']);
        unset($form['per_phone']);
        $per_industry = $form['per_industry'];
        $per_industry_child = $form['per_industry_child'];
        $this->deletePerIndustryByUid($user_id);
        //添加个人投资地域的临时写法
        $data = array('industry_id' => $per_industry, 'parent_id' => $per_industry, 'user_id' => $user_id);
        $this->addPerIndustryList($data);
        if ($per_industry_child != 0) {
            $dataChild = array('industry_id' => $per_industry_child, 'parent_id' => $per_industry, 'user_id' => $user_id);
            $this->addPerIndustryList($dataChild);
        }
        //end
        unset($form['per_industry']);
        unset($form['per_industry_child']);
        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }
        $persons->update();
        return true;
    }


    /**
     * @sso
    * 更新个人用户基本信息( 新版本 )
    * @author 许晟玮
    */
    public function updatePersonNew( $form ){

        $user               = Service_Sso_Client::instance()->getUserInfoById(Arr::get($form,"user_id"));
        $user_id            = $form['user_id'];
        $mobile             = $user->mobile;
        $per_phone          = trim( $form['per_phone'] );
        //判断手机号码是否修改
        if ( isset( $per_phone ) && $mobile != $per_phone ){
            if ( $user->valid_mobile=='1' ) {//判断是否存在已经绑定的手机号码
                $result = array('mobile_error' => '这个号码已经存在，并且已经绑定!', 'status' => -1);
                return $result;

            } else {
                $serice = new Service_User_Company_User();

                //更新手机号码
                $result= Service_Sso_Client::instance()->updateMobileInfoById($user_id,array("mobile"=>$per_phone));

                if( $result===false ){
                    $result= Service_Sso_Client::instance()->setUserMobileById($user_id,$per_phone);
                }
            }
        }
        //end

        $persons                = ORM::factory('Personinfo', arr::get( $form, 'per_id') );
        if ( empty( $persons->per_id ) ) {
            return false;
        }


        $form['per_updatetime'] = time();

        unset($form['user_id']);
        unset($form['per_phone']);

        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }
        //不存在头像，给个默认
        $per_photo= $persons->per_photo;

        if( $per_photo=='' || $per_photo==null ){
            $per_phone = $persons->per_photo= "/user_icon/plant/default_icon_".rand(1,25).".jpg";
            //同步到基础信息库
            Service_Sso_Client::instance()->updateBasicInfoById($user_id,array("user_portrait"=>URL::imgurl($per_phone)));
        }

        //同步用户名称和性别 TODO 以后做统一处理
        $client = Service_Sso_Client::instance();
        $info = array();
        $user_name = Arr::get($form,"per_realname");
        if($user_name != $user->user_name){
            $info['user_name'] = $user_name;
        }
        $user_gender = Arr::get($form,"per_gender");
        if($user_gender != $user->user_gender){
            $info['user_gender'] = $user_gender;
        }
        //同步到总库中
        if($info){
            $client->updateBasicInfoById($user_id, $info);
        }

        $persons->update();

        return true;

    }

    /**
    * 更新意向投资信息
    * @author 许晟玮
    */
    public function updatePersonInvest( $form ){
        $user                   = ORM::factory('user', $form['user_id']);
        $user_id                = $form['user_id'];


        $persons                = ORM::factory('Personinfo', arr::get($form, 'per_id'));

        if (empty($persons->per_id)) {
            return false;
        }

        //修改我的标签信息
        $datagroups             = isset($form['Investment_groups']) ? $form['Investment_groups'] : array();

        unset($form['Investment_groups']);
        $res_crowd              = $this->updatePersonCrowd($user_id, $datagroups);
        if ($res_crowd != true){
            return false;
        }
        $form['per_updatetime'] = time();

        unset($form['user_id']);

        $per_industry           = $form['per_industry'];
        $per_industry_child     = $form['per_industry_child'];
        $this->deletePerIndustryByUid($user_id);
        //添加个人投资地域的临时写法
        $data                   = array('industry_id' => $per_industry, 'parent_id' => $per_industry, 'user_id' => $user_id);
        $this->addPerIndustryList($data);
        if ($per_industry_child != 0) {
            $dataChild          = array('industry_id' => $per_industry_child, 'parent_id' => $per_industry, 'user_id' => $user_id);
            $this->addPerIndustryList($dataChild);
        }
        //end
        unset($form['per_industry']);
        unset($form['per_industry_child']);

        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }
        $persons->update();
        return true;


    }


    /**
     * @sso
     * 添加个人用户基本信息
     * @author 曹怀栋
     * @edit 许晟玮
     */
    public function addPerson($form) {
        $user_id = $form['user_id'];
        $update_user = Service_Sso_Client::instance()->getUserInfoById( $user_id );
        //邮箱注册的会员，更新手机
        if( isset( $form['per_phone'] ) && $form['per_phone']!='' ){
            //更新手机信息
            if ($update_user->valid_mobile == 0) {//只有未验证的手机号码才可以更新
                Service_Sso_Client::instance()->setUserMobileById( $user_id,trim($form['per_phone']) );
            }
        }

        //手机注册的会员，更新邮箱
        if( isset( $form['per_mail'] ) && $form['per_mail']!='' ){
            Service_Sso_Client::instance()->setUserEmailById( $user_id,trim($form['per_mail']) );
        }



        //添加我的标签信息
        $datagroups = isset($form['Investment_groups']) ? $form['Investment_groups'] : array();
        unset($form['Investment_groups']);
        $res_crowd = $this->updatePersonCrowd($form['user_id'], $datagroups);
        if ($res_crowd != true)
            return false;

        $persons = ORM::factory('Personinfo');
        $per_industry = $form['per_industry'];
        $per_industry_child = $form['per_industry_child'];
        $form['per_createtime'] = time();
        if(isset($form['per_photo']) && $form['per_photo']){
            $form['per_photo'] = common::getImgUrl($form['per_photo']);
        }
        unset($form['user_id']);
        unset($form['per_photo_old']);
        unset($form['per_phone']);
        unset($form['per_id']);
        unset($form['per_industry']);
        unset($form['per_industry_child']);
        unset($form['per_mail']);

        //下面是所要插入数据的对应信息
        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }
        //同步用户名称和性别 TODO 以后做统一处理
        $client = Service_Sso_Client::instance();
        $info = array();
        $user_name = Arr::get($form,"per_realname");
        $info['user_name'] = $user_name;
        $user_gender = Arr::get($form,"per_gender");
        $info['user_gender'] = $user_gender;

        //同步到总库中
        if($info){
            $client->updateBasicInfoById($user_id, $info);
        }

        $person = $persons->save();

        $this->deletePerIndustryByUid($user_id);
        //添加个人投资地域的临时写法
        $data = array('industry_id' => $per_industry, 'parent_id' => $per_industry, 'user_id' => $user_id);
        $this->addPerIndustryList($data);
        if ($per_industry_child != 0) {
            $dataChild = array('industry_id' => $per_industry_child, 'parent_id' => $per_industry, 'user_id' => $user_id);
            $this->addPerIndustryList($dataChild);
        }
        //添加名片log
        //$card_ser=new Service_Card();
        //$card_ser->addCardBehaviourInfo($person->per_user_id,0,1,2);
        //end
        return $person->per_id;
    }

    /**
     * @sso
     * 添加个人用户基本信息（新）
     * @author 许晟玮
     */
    public function addPerson_new($form) {
        $update_user    = ORM::factory("User", $form['user_id']);
        $user_id        = $form['user_id'];
        //更新手机信息
        $sso_user= Service_Sso_Client::instance()->getUserInfoById( $user_id );
        if ($sso_user->valid_mobile == 0) {//只有未验证的手机号码才可以更新
            Service_Sso_Client::instance()->setUserMobileById( $user_id,trim($form['per_phone']) );
        }


        $persons = ORM::factory('Personinfo');
        $form['per_createtime'] = time();

        unset($form['user_id']);
        unset($form['per_phone']);
        unset($form['per_id']);

        //下面是所要插入数据的对应信息
        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }

        $photo= $persons->per_photo= "/user_icon/plant/default_icon_".rand(1,25).".jpg";

        //同步用户名称和性别 TODO 以后做统一处理
        $client = Service_Sso_Client::instance();
        $info = array();
        $user_name = Arr::get($form,"per_realname");
        $info['user_name'] = $user_name;
        $user_gender = Arr::get($form,"per_gender");
        $info['user_gender'] = $user_gender;
        $info['user_portrait'] = URL::imgurl($photo);
        //同步到总库中
        if($info){
            $client->updateBasicInfoById($user_id, $info);
        }


        $person = $persons->save();

        //添加名片log
        //$card_ser=new Service_Card();
        //$card_ser->addCardBehaviourInfo($person->per_user_id,0,1,2);
        //end
        return $person->per_id;
    }



     /**
     * 添加个人用户投资信息
     * @author 许晟玮
     */
    public function addPersonInvest($form) {
        $update_user = ORM::factory("User", $form['user_id']);
        $user_id                = $form['user_id'];

        //添加我的标签信息
        $datagroups             = isset($form['Investment_groups']) ? $form['Investment_groups'] : array();
        unset($form['Investment_groups']);
        $res_crowd              = $this->updatePersonCrowd($form['user_id'], $datagroups);
        if ($res_crowd != true)
            return false;
        $persons                = ORM::factory('Personinfo');
        $per_industry           = $form['per_industry'];
        $per_industry_child     = $form['per_industry_child'];
        $form['per_createtime'] = time();

        unset($form['user_id']);
        unset($form['per_id']);
        unset($form['per_industry']);
        unset($form['per_industry_child']);
        //下面是所要插入数据的对应信息
        foreach ($form as $k => $v) {
            $persons->$k = trim($v);
        }

        $person                 = $persons->save();

        $this->deletePerIndustryByUid($user_id);
        //添加个人投资地域的临时写法
        $data = array('industry_id' => $per_industry, 'parent_id' => $per_industry, 'user_id' => $user_id);
        $this->addPerIndustryList($data);
        if ($per_industry_child != 0) {
            $dataChild          = array('industry_id' => $per_industry_child, 'parent_id' => $per_industry, 'user_id' => $user_id);
            $this->addPerIndustryList($dataChild);
        }
        //添加名片log
        //$card_ser=new Service_Card();
        //$card_ser->addCardBehaviourInfo($person->per_user_id,0,1,2);
        //end
        return $person->per_id;
    }


    /**
     * 更新个人我的标签信息
     * @author 钟涛
     */
    public function updatePersonCrowd($user_id, $data) {
        $user_crowd = ORM::factory('Personcrowd')->where("user_id", "=", $user_id)->find_all();
        //删除以前的标签信息
        if (count($user_crowd) > 0) {
            foreach ($user_crowd as $k => $v) {
                $this->deletePersonCrowd($v->crowd_id);
            }
        }
        //添加我的标签信息
        $this->addPersonCrowd($user_id, $data);
        return true;
    }

    /**
     * 添加我的标签
     * @author 钟涛
     */
    public function addPersonCrowd($user_id, $data) {
        if (count($data) > 0) {
            foreach ($data as $v) {
                $person_crowd = ORM::factory('Personcrowd');
                $person_crowd->user_id = $user_id;
                $person_crowd->tag_id = intval($v);
                $person_crowd->save();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除投资人群信息
     * @author 钟涛
     */
    public function deletePersonCrowd($project_crowd_id) {
        $person_crowd = ORM::factory('Personcrowd');
        $result = $person_crowd->where("crowd_id", "=", $project_crowd_id)->find();
        if (!empty($result->crowd_id)) {
            $person_crowd->delete($result->crowd_id);
            return true;
        }
        return false;
    }

    /**
     * @sso
     * ajax 检查邮件验证的邮件是否发送成功
     * @author 周进
     */
    public function updateCheckValidEmail($user_id, $email) {
        $msg['status'] = '-2';
        $user = Service_Sso_Client::instance()->getUserInfoById($user_id);
        if ($user->valid_email == 1) {
            return $msg;
        }
        $valid = ORM::factory("Validcode");
        if ($user_id != "" && $email != "") {
            $userserice = new Service_User_Person_User();
            $valid_emailcode = $userserice->createValidCode($user_id, 1);
            if ($valid_emailcode != false) {
                $url = "http://" . $_SERVER['HTTP_HOST'] . "/member/checkvemail/?key=" . $user_id . "O" . rand('10000', '99999') . "&code=" . md5($valid_emailcode);
                $content = '<p>尊敬的一句话平台用户：</p><p>您好！</p><p>请于两个小时之内点击以下链接验证邮箱。</p><p><a href="' . $url . '">' . $url . '</a></p>
                            <p>如果上面的链接无法点击，您也可以复制链接，粘贴到您浏览器的地址栏内，然后按“回车”键打开预设页面，完成相应功能。</p><p>如果有其他问题，请联系我们：service@yijuhua.net 谢谢！</p><p>此为系统消息，请勿回复</p>';
                $sendresult = false;
                $sendresult = common::sendemail("邮箱验证", 'service@yijuhua.net', $email, $content);
                if ($sendresult == 1) {
                    $msg['status'] = '1';
                }
            }
        }
        return $msg;
    }

    /**
     * 数组改成序列化(用来插入数据库的)
     * @author 曹怀栋
     */
    public function arrayToString($form) {
        unset($form['name_x']);
        unset($form['name_y']);
        //序列化
//         if(isset($form['per_labels'])){
//             $form['per_label'] = serialize($form['per_labels']);
//             unset($form['per_labels']);
//         }else{
//             $form['per_label'] = "";
//         }
        if (isset($form['per_per_labels'])) {
            $form['per_per_label'] = serialize($form['per_per_labels']);
            unset($form['per_per_labels']);
        } else {
            $form['per_per_label'] = "";
        }
        return $form;
    }

    /**
     * 除理从数据库中读取的数据，按照指定的数据输出
     * @author 曹怀栋
     */
    public function transformationData($result) {
        $res = array();
        $groups = "";
        //投资人群（类型）取得tag表的name
        $res_crowd = ORM::factory('Personcrowd')->select('*')->where("user_id", "=", $result->per_user_id)->find_all();
        if (count($res_crowd) > 0) {
            foreach ($res_crowd as $k => $v) {
                $tag = ORM::factory('Usertype', $v->tag_id);
                $per_label[$k]['tag_id'] = $tag->tag_id;
                $per_label[$k]['tag_name'] = $tag->tag_name;
            }
            $result->per_label = $per_label;
        } else {
            $result->per_label = array();
        }
        //投资人群（自行添加的）
        if ($result->per_per_label != "") {
            $result->per_per_label = unserialize($result->per_per_label);
        } else {
            $result->per_per_label = array();
        }
        return $result;
    }

    /**
     * 投资人群以逗号分割并返回
     * @author 曹怀栋
     */
    public function projectInvestmentProups($result) {
        $res = array();
        $groups = "";
        //投资人群（类型）取得tag表的name
        $res_crowd = ORM::factory('Personcrowd')->select('*')->where("user_id", "=", $result->per_user_id)->find_all();
        if (count($res_crowd) > 0) {
            foreach ($res_crowd as $k => $v) {
                $tag = ORM::factory('Usertype', $v->tag_id);
                $groups .= $tag->tag_name . ",";
            }
        }
        if ($result->per_per_label != "") {
            $result->per_per_label = $groups . implode(",", unserialize($result->per_per_label));
        } else {
            $result->per_per_label = substr($groups, 0, -1);
        }
        if ($result->per_per_label == ",") {
            $result->per_per_label = "";
        }
        return $result;
    }

    /**
     * 添加个人从业经验
     * @author 龚湧
     * @param array $post
     * @param int $user_id
     * @throws Kohana_Exception
     * @return boolean
     */
    public function createExperience($post, $user_id) {
        $post['exp_user_id'] = $user_id;
        $post['exp_createtime'] = time();
        $expected = array(
            "exp_user_id",
            "exp_createtime",

            "exp_starttime",
            "exp_endtime",
            "pro_id",
            "area_id",
            "exp_company_name",
            "exp_nature",
            "exp_scale",
            "exp_industry_sort",
            "exp_department",
            "exp_occupation_type",
            "exp_occupation_name",
            "exp_user_occupation_name",
            "exp_description",

        );


        //组装年月
        $post['exp_starttime'] = str_replace("-","",$post['exp_starttime']);
        $post['exp_endtime'] = str_replace("-","",$post['exp_endtime']);

            try {
                $new_experience = ORM::factory("Experience")->values($post, $expected)->create();
                //个人用户添加活跃度by钟涛
                $ser1=new Service_User_Person_Points();
                $ser1->addPoints($user_id, 'per_perfect_three');//完善从业经验
                return true;
            } catch (Kohana_Exception $e) {
                throw $e;
                return false;
            }


    }

    /**
     * 编辑个人从业经验
     * @author 龚湧
     * @param array $post
     * @param  ORM $edit
     * @return ORM|boolean
     */
    public function editExperience($post, $edit) {
        $post['exp_starttime'] = str_replace("-","",$post['exp_starttime']);
        $post['exp_endtime'] = str_replace("-","",$post['exp_endtime']);
        //编辑条目
        $edit->exp_updatetime = time();

        $edit->exp_starttime = $post['exp_starttime'];
        $edit->exp_endtime = $post['exp_endtime'];
        $edit->pro_id= $post['pro_id'];
        $edit->area_id= $post['area_id'];
        $edit->exp_company_name= $post['exp_company_name'];
        $edit->exp_nature= $post['exp_nature'];
        $edit->exp_scale= $post['exp_scale'];
        $edit->exp_industry_sort= $post['exp_industry_sort'];
        $edit->exp_department= $post['exp_department'];
        $edit->exp_occupation_type= $post['exp_occupation_type'];
        $edit->exp_occupation_name= $post['exp_occupation_name'];
        $edit->exp_user_occupation_name= $post['exp_user_occupation_name'];
        $edit->exp_description= $post['exp_description'];
        try {
            $edit = $edit->update();
            return $edit;
        } catch (Kohana_Exception $e) {
            //TODO 编辑失败
            return false;
        }
    }

    /**
     * 获取所有的企业用户
     * @auhtor 施磊
     * @param int $user_status 用户状态
     * @return $array 所有的企业用户
     */
    public function getPersonalUserList($user_status = 1) {
        $obj = ORM::factory('User')->select('*')->join('account', 'LEFT')->on('user_id', '=', 'account_user_id')->where('user_type', '=', 2)->where('user_status', '=', $user_status)->count_all();
        $array=array();
        $page = Pagination::factory(array(
                'total_items'    => $obj,
                'items_per_page' => 20,
        ));
        $array['list'] = ORM::factory('User')->select('*')->join('account', 'LEFT')->on('user_id', '=', 'account_user_id')->where('user_type', '=', 2)->where('user_status', '=', $user_status)->limit($page->items_per_page)->offset($page->offset)->order_by('user_id', 'DESC')->find_all();
        $array['page']= $page;
        $return = array();
        foreach ($array['list'] as $val) {
            $user = $val->as_array();
            $user['personal'] = $this->getPerson($user['user_id'])->as_array();
            $return[] = $user;
        }
        $array['list'] = $return;
        return $array;
    }

    /**
     * 根据条件获得数据
     * @author 施磊
     */
    public function getPersonalCertification($cond) {
        $obj = ORM::factory('Personinfo');
        if (isset($cond['status']) && !empty($cond['status'])) {
            $status = intval($cond['status']);
            $obj->where('per_auth_status', '=', $status);
        } else {
            $obj->where('per_auth_status', '!=', 0);
        }
        if (isset($cond['searchCond']) && isset($cond['searchCon']) && !empty($cond['searchCon'])) {
            $obj->where($cond['searchCond'], 'like', $cond['searchCon'] . '%');
        }
        if (isset($cond['searchFrom']) && !empty($cond['searchFrom'])) {
            $obj->where('per_auth_time', '>=', $cond['searchFrom']);
        }
        if (isset($cond['searchTo']) && !empty($cond['searchTo'])) {
            $obj->where('per_auth_time', '<=', $cond['searchTo']);
        }
        $returnObj = $obj->find_all();
        $return = array();
        $presonalArr = array();
        foreach ($returnObj as $val) {
            $presonalArr['personal'] = $val->as_array();
            $presonalArr['user'] = $this->getUserById($presonalArr['personal']['per_user_id']);
            $return[] = $presonalArr;
        }
        return $return;
    }

    /*
     * 获得用户信息 根据id
     * @author 施磊
     */

    public function getUserById($user_id) {
        $user_id = intval($user_id);
        if (!$user_id)
            return array();
        return $obj = ORM::factory('User', $user_id)->as_array();
    }

    /**
     * 新建个人用户表信息
     * @author 施磊
     *
     */
    public function addPersonalUser($data) {
        if (!$data)
            return FALSE;
        $ormModel = ORM::factory('Personinfo');
        $ormModel->values($data)->create();
    }

    /**
     * 获得单个用户信息
     * @author 施磊
     */
    public function getPersonalInfo($user_id) {
        if (!intval($user_id))
            return FALSE;
        $user = ORM::factory('User')->select('*')->join('account', 'LEFT')->on('user_id', '=', 'account_user_id')->where('user_id', '=', $user_id)->find();
        $personinfo = $this->transformationData($user->user_person);
        $return = $personinfo->as_array();
        $return['userInfo'] = $user->as_array();
        return $return;
    }

    /**
     * @sso
     * 修改个人信息表
     * @author 施磊
     */
    public function editPersonalUser($user_id, $param) {
        if (!intval($user_id))
            return FALSE;
        $ormModel = ORM::factory('Personinfo')->where('per_user_id', '=', $user_id)->find();
        if(Arr::get($param, 'per_photo', '')) {
            $org_url = $param['per_photo'];
            $param['per_photo']=common::getImgUrl($param['per_photo']);
            //同步个人用户头像
            $client = Service_Sso_Client::instance();
            $client->updateBasicInfoById($user_id, array("user_portrait"=>URL::imgurl( $org_url )));
        }

        if($ormModel->per_user_id) {
            $ormModel->values($param)->check();
            $ormModel->update();
        }else {
            $param['per_user_id'] = $user_id;
            $ormModel->values($param)->check();
            $ormModel->create();
        }

    }

    /**
     * 获得个人地域信息
     * @author 施磊
     */
    public function selectPersonalAreaByUserId($per_id) {
        $per_id = intval($per_id);
        if (!$per_id)
            return array();
        $ormModel = ORM::factory('PersonalArea')->select("*")->join('city', 'LEFT')->on('area_id', '=', 'cit_id')->where('per_id', '=', $per_id)->find_all();
        $return = array();
        foreach ($ormModel as $val) {
            $return[] = $val->as_array();
        }
        return $return;
    }

    /**
     * 新增地域信息
     * @author 施磊
     */
    public function insertPersonalArea($per_id, $data) {
        $per_id = intval($per_id);
        if (!$data && !$per_id)
            return FALSE;
        $this->deletePersonalAreaByUserId($per_id);
        if ($data) {
            foreach ($data as $v) {
                $ormModel = ORM::factory('PersonalArea');
                $ormModel->per_id = $per_id;
                $city = ORM::factory('city', intval($v));
                $ormModel->area_id = intval($v);
                if (intval($v) > 35) {//只写入市级信息
                    $ormModel->pro_id = intval($city->pro_id);
                } else {
                    $ormModel->pro_id = intval($v);
                }
                $ormModel->save();
            }
        }
        return true;
    }

    /**
     *  删除个人地域信息
     *  @author 施磊
     */
    public function deletePersonalAreaByUserId($per_id) {
        $per_id = intval($per_id);
        if (!$per_id)
            return FALSE;
        $ormModel = ORM::factory('PersonalArea')->where('per_id', '=', $per_id)->find_all();
        foreach ($ormModel as $val) {
            $val->delete();
        }
        return TRUE;
    }

    /**
     * 获得个人地域信息
     * @author 施磊
     * @param int user_id 用户id
     * @param bool arr TRUE的话 返回数组 否则返回对象
     */
    public function getPerIndustryByUserId($user_id, $arr = TRUE) {
        $user_id = intval($user_id);
        if (!$user_id)
            return array();
        $ormModel = ORM::factory('UserPerIndustry')->where('user_id', '=', $user_id)->find_all();
        if ($arr) {
            $return = array();
            foreach ($ormModel as $val) {
                $return[] = $val->as_array();
            }
        } else {
            $return = $ormModel;
        }
        return $return;
    }

    /**
     * 获得所有地域信息
     * @author 施磊
     */
    public function getAllIndustryByPid($parent_id, $arr = TRUE) {
        $parent_id = intval($parent_id);
        if (!$parent_id)
            return array();
        $pc = ORM::factory("industry")->where("parent_id", "=", $parent_id)->find_all();
        if ($arr) {
            $return = array();
            foreach ($pc as $val) {
                $return[$val->industry_id] = $val->as_array();
            }
        } else {
            $return = $pc;
        }
        return $return;
    }

    /**
     * 添加单条个人投资行业
     * @author 施磊
     */
    public function addPerIndustryList($data = array()) {
        if (!$data)
            return FALSE;
        $ormModel = ORM::factory('UserPerIndustry');
        $ormModel->values($data)->create();
    }

    /**
     * 删除个人投资行业
     * @author 施磊
     */
    public function deletePerIndustryByUid($user_id) {
        $user_id = intval($user_id);
        if (!$user_id)
            return array();
        $ormMod = ORM::factory('UserPerIndustry');
        $result = $ormMod->where("user_id", "=", $user_id)->find_all();
        foreach ($result as $val) {
            $temp = ORM::factory('UserPerIndustry', $val->per_industry_id);
            $temp->loaded();
            $temp->delete();
        }

        return true;
        return false;
    }

    /**
     * 获得个人投资行业接口
     * @author 施磊
     */
    public function getPersonalIndustryString($user_id) {
        $user_id = intval($user_id);
        if (!$user_id)
            return '';
        $userIndusty = $this->getPerIndustryByUserId($user_id);
        $return = '';
        if ($userIndusty) {
            $temp = '';
            foreach ($userIndusty as $val) {
                if ($val['industry_id'] == $val['parent_id'] || $val['parent_id']==0) {
                    $temp['parent_id'] = $val['industry_id'];
                } else {
                    $temp['industry_arr'] = $this->getAllIndustryByPid($val['parent_id']);
                    $temp['industry_id'] = $val['industry_id'];
                    $temp['industry_name'] = isset($temp['industry_arr'][$val['industry_id']]['industry_name']) ? $temp['industry_arr'][$val['industry_id']]['industry_name'] : '';
                }
            }
            if(isset($temp['parent_id'])) {
                $industrylist = common::primaryIndustry(0, $temp['parent_id']);
                if(count($industrylist[0])){
                        $return = $industrylist[0]->industry_name;
                }else{
                    $return ='';
                }
                $return .= isset($temp['industry_name']) ? ' ' . $temp['industry_name'] : '';
            }
        }
        return $return;
    }
    /**
     * 获得个人投资行业-一级行业接口
     * $type=1时返回id和name,其他则只返回name
     * @author 赵路生
     */
    public function getPerasonalParentIndustry($user_id,$type=1){
        $user_id = intval($user_id);
        if(!$user_id){
            return '';
        }
        $per_ind_ser= ORM::factory('UserPerIndustry')->where('user_id','=',$user_id)->group_by('parent_id')->find_all();
        if(count($per_ind_ser)==0){
            return '';
        }
        $ind_service= array();
        $tid = array();
        foreach($per_ind_ser as $per_ind){
            if($per_ind->parent_id==0){
                if($type==1){
                    $tid[$per_ind->industry_id]=ORM::factory('Industry',$per_ind->industry_id)->industry_name;
                }else{
                    return ORM::factory('Industry',$per_ind->industry_id)->industry_name;
                }
            }else{
                if($type==1){
                    $tid[$per_ind->parent_id]=ORM::factory('Industry',$per_ind->parent_id)->industry_name;
                }else{
                    return ORM::factory('Industry',$per_ind->parent_id)->industry_name;
                }
            }
        }
        return $tid;
    }
    /**
     * 获得个人地区接口
     * @author 施磊
     */
    public function getPerasonalAreaString($user_id) {
        $user_id = intval($user_id);
        if (!$user_id)
            return '';
        $invest = new Service_User_Person_Invest();
        //$result=$this->getPersonInfo($user_id);
        $result=$this->getPersonInfoJianjie($user_id);
        $pro = $invest->getArea();
        $all = array('cit_id' => 88, 'cit_name' => '全国');
        array_unshift($pro, $all);
        $areaIds = $result['person']->per_city;
        //获取城市地区
        $pro_id = $result['person']->per_area;
        if ($pro_id != '' && $pro_id != '88') {
            $area = array('pro_id' => $pro_id);
            $cityarea = common::arrArea($area);
        } else {
            $cityarea = array();
        }
        $return = '';
        if($pro){
            foreach($pro as $val) {
                if($val['cit_id']==$pro_id) {
                    $return = $val['cit_name'];
                }
            }
        }
        if($areaIds!='0' && !empty($cityarea)){
            if($cityarea){
                foreach ($cityarea as $v){
                    if($v->cit_id == $areaIds) {
                        $return .= ' '.$v->cit_name;
                     }
                }
            }

        }
        return $return;

    }
    /**
     * 获得个人地区接口【只获取省份】
     * @author 施磊
     */
    public function getPerasonalAreaStringOnlyPro($user_id) {
        $user_id = intval($user_id);
        if (!$user_id)
            return '';
        $invest = new Service_User_Person_Invest();
        $result=$this->getPersonInfoJianjie($user_id);
        $pro = $invest->getArea();
        $all = array('cit_id' => 88, 'cit_name' => '全国');
        array_unshift($pro, $all);
        $areaIds = $result['person']->per_city;
        //获取城市地区
        $pro_id = $result['person']->per_area;
        if ($pro_id != '' && $pro_id != '88') {
            $area = array('pro_id' => $pro_id);
            $cityarea = common::arrArea($area);
        } else {
            $cityarea = array();
        }
        $return = '';
        if($pro){
            foreach($pro as $val) {
                if($val['cit_id']==$pro_id) {
                    $return = $val['cit_name'];
                }
            }
        }
        return $return;

    }
    /**
     * 获得个人地域信息
     * @author 施磊
     * @param int $user_id
     */
    public function getPersonalArea($user_id) {
        $user_id = intval($user_id);
        $this->limitNum = 5;
        if(!$user_id) return array();
        $personalArea = $this->selectPersonalAreaByUserId($user_id);
        $allTempArea = array();
        if($personalArea) {
            foreach($personalArea as $value) {
                if($value['pro_id'] == $value['area_id']) {
                    $allTempArea[$value['pro_id']]['name'] =  $value['cit_name'];
                }else{
                    $allTempArea[$value['pro_id']]['data'][$value['area_id']] = $value['cit_name'];
                }
             }
        }
        $return  = '';
        $lastArea = array();
        if($allTempArea) {
            foreach($allTempArea as $key => $val) {
                if(!isset($val['data'])) {
                   if($this->limitNum > 0) {
                       $lastArea[$key]['name'] = arr::get($val,'name','');
                       $this->limitNum--;
                   }
               }else {
                   if($this->limitNum > 0) {
                       $lastArea[$key]['name'] = arr::get($val,'name','');
                       foreach($val['data'] as $keyT => $valT) {
                           if($this->limitNum > 0) {
                                $lastArea[$key]['data'][$keyT] = $valT;
                                $this->limitNum--;
                           }
                       }
                   }
               }
            }
           foreach($lastArea as $val) {
               if(!isset($val['data'])) {
                   $return[] = arr::get($val,'name','');
               }else {
                   $return[] = arr::get($val,'name','').' '.implode(',', $val['data']);
               }
           }
           if($return){
                 $return = implode(',', $return);
           }else{
                 $return='';
           }
        }
        return $return;
    }

    /**
     * sso
     * 个人用户快速注册(领红包，优惠劵)
     *
     * @author 郁政
     */
    public function personQuickRegForExhb($form){
        //检查手机是否已被绑定
        if($this->isMobileBinded(Arr::get($form, "mobile"))){
            return false;
        }
        $user_id = null;
        $valid = new Validation($form);
        $valid->rule("mobile", "not_empty");
        $password = Arr::get($form,'password');
        if($valid->check()){
            //SID md5
            $sid_md5    = arr::get($_COOKIE, 'Hm_lvqtz_sid');
            $userinfo = array(
                    'email' => '',
                    'mobile'=>Arr::get($form,'mobile'),
                    'user_name' => Arr::get($form,'user_name'),
                    'password' => $password,
                    'user_type' => 2, //个人用户
                    'valid_mobile'=>1,
            );
            $client = Service_Sso_Client::instance();
            $result = $client->appRegister($userinfo,"MOBILE");
            if($result){
                if(Arr::get($result,"error") === false){
                    $user = Arr::get($result,"return");
                    $user_id = $user['id'];
                    //初始化 user==
                      try{
                          $basic = ORM::factory("User");
                           $basic->user_id = $user_id;
                          $basic->last_logintime = time();
                         $basic->last_login_ip = ip2long(Request::$client_ip);
                         $basic->sid = $sid_md5;
                         $basic->aid= Cookie::get('cpa_aid','');
                         $basic->create();

                           $basic->user_person->per_user_id = $basic->user_id;
                           $basic->user_person->per_realname = Arr::get($form,"user_name");
                         $basic->user_person->create();
                        }catch(Kohana_Exception $e){
                            //抛出错误代码
                        }
                        //初始化本地结束==
                        //自动登录
                        $client->login($userinfo['mobile'], $userinfo['password']);
                        //返回用户信息
                        $return = $client->getUserInfoById($user_id);
                        $return->user_id = $return->id;
                        $user_ser = new Service_User();
                        $return->sid = $sid_md5;
                        return $return;
                    }
                }
            }
            return false;
        }

    /**
     * 获取最近登录的投资者 875用
     * @author 花文刚
     */
    public function getRecentlyLoginUser(){

        $recent=array();
        //每个取50个 万一图片不存在
        $num = 50;

        //最近登录的9个投资者
        $log =  ORM::factory('UserLoginLog')
            ->where('user_type','=',2)
            ->group_by('user_id')
            ->order_by('log_id', 'DESC')
            ->limit($num)
            ->find_all();

        $nine_now = array();
        foreach($log as $u){
            $user= Service_Sso_Client::instance()->getUserInfoById($u->user_id);
            if(common::check_remote_file_exists($user->user_portrait)){
                $nine_now[] = $user;
            }
            if(count($nine_now) == 9){
                break;
            }
        }
        $recent[] = $nine_now;

        //一个月前登录的9个投资者
        $log =  ORM::factory('UserLoginLog')
            ->where('user_type','=',2)
            ->and_where('log_time','<',strtotime('-1 month'))
            ->group_by('user_id')
            ->order_by('log_id', 'DESC')
            ->limit($num)
            ->find_all();

        $nine_month = array();
        foreach($log as $u){
            $user= Service_Sso_Client::instance()->getUserInfoById($u->user_id);
            if(common::check_remote_file_exists($user->user_portrait)){
                $nine_month[] = $user;
            }
            if(count($nine_month) == 9){
                break;
            }
        }
        $recent[] = $nine_month;

        return $recent;

    }

}

?>