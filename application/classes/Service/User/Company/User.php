<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户中心
 * @author 龚湧
 *
 */
class Service_User_Company_User extends Service_User{
    /**
     * 已验证状态(手机、邮箱、企业资质等)
     */
    const ENABLE_STATUS= 1;

    /**
     * 企业资质认证
     * @author 曹怀栋
     * @param array $files
     * @param string $filekey
     * @return multitype:|Ambigous <array('error'=>'','path'=>'','name'=>''), multitype:string , unknown>
     */
    public function uploadCertification($imgs,$filekey,$user_id,$company,$oldNum=0){
        //图片上传不能大于2张
        $count = ORM::factory("CommonImg")->where("field_name", "=",$filekey)->where("user_id", "=",$user_id)->count_all();
        if($count > 1) return false;
        if(is_array($imgs)){
            foreach ($imgs as $v){
                $org_pic = str_replace('/s_','/b_', $v);
                $common_img=ORM::factory("CommonImg");
                $common_img->user_id = $user_id;//对应用户id
                $common_img->table_name = 1;//1表示user_company表
                $common_img->field_name = $filekey;//字段名
                $common_img->url = common::getImgUrl($org_pic);//图片路径
                $common_img->save();
                $common_img->clear();

            }
        }
        $company->tax_certificate_status=0;
        $company->com_business_licence_status=0;
        $company->organization_credit_status=0;
        $company->com_auth_status=0;
        $company->com_auth_submit_time=time();
        $company->save();
        return true;
    }

    /**
     *企业基本信息是否完善 ，即判断是否有企业信息
     * @author 龚湧
     * @param int $user_id
     * @return bool
     */
    public function is_complete_basic($user_id){
        $company = ORM::factory('Companyinfo')->where('com_user_id','=',$user_id)->find();
        if( $company->com_id ){
            return true;
        }
        return false;
    }

    /**
     *企业是否已开通招商通服务[是否扣除平台服务费1500元]
     * @author 钟涛
     * @param int $com_id
     * @return bool
     */
    public function isPlatformServiceFee($com_id=0){
        if(intval($com_id)>0){
            $company = ORM::factory('Companyinfo',$com_id);
            if( $company->platform_service_fee_status == 1 ){
                return true;//已经开通
            }
        }
        return false;//未开通
    }

    /**
     * flash单个上传企业资质认证图片
     * @author 潘宗磊
     */
    public function uploadComCerts($user_id,$param){
        $field_name = "";
        $org_pic = str_replace('/s_','/b_', Arr::get($param, 'data'));
        $common_img=ORM::factory("CommonImg");
        if($param['id']==1){
            $field_name = "com_business_licence";
        }elseif($param['id']==2){
            $field_name = "organization_credit";
        }elseif($param['id']==3){
            $field_name = "tax_certificate";
        }
        $common_img->user_id = $user_id;//对应用户id
        $common_img->table_name = 1;//1表示user_company表
        $common_img->field_name = $field_name;//字段名
        $common_img->url = common::getImgUrl($org_pic);//图片路径
        $common_img->create();
    }

    /**
     * 删除单个企业资质认证图片
     * @author 曹怀栋
     */
    public function deleteCertification($id){
        $result=ORM::factory("CommonImg",$id);
        //当这条数据存在的情况下，这删除这个数据并删除相应的图片
        if(!empty($result->common_img_id) && $result->table_name == 1){
            if(!empty($result->url)){
                $model = ORM::factory("Companyinfo");
                $model->tax_certificate_status = 3;
                $model->com_business_licence_status = 3;
                $model->organization_credit_status = 3;
                $delete1 = common::deletePic(URL::imgurl($result->url));
                $delete = common::deletePic(URL::imgurl(str_replace('/b_','/s_', $result->url)));
            }
            $result->delete($id);
            return true;
        }
        return false;
    }

    /**
     * 上传企业其他认证信息
     * @author 龚湧
     * @param array $files
     * @param string $file_name
     * @param string $filekey
     * @param ORM $other
     * @param ORM $company
     * @return multitype:|Ambigous <array('error'=>'','path'=>'','name'=>''), multitype:string , unknown>
     */
    public function uploadOtherCertification($files,$file_name,$filekey,$other,$company){
        //图片上传错误
        if($files[$filekey]['error']){
            return array();
        }
        $org = getimagesize($files[$filekey]['tmp_name']);
        $org_w = $org[0];//原图宽
        $org_h = $org[1];//原图高
        $arr = array(array($org_w,$org_h),array(100,100));
        $result = common::uploadPic($files, $filekey,$arr);
        if(Arr::get($result, 'path')){
            $org_pic = str_replace('/s_','/b_', Arr::get($result, 'path'));
            //上传信息
            $other->com_id = $company->com_id;
            $other->cert_name = $file_name;
            $other->cert_picurl = $org_pic;
            $other->cert_addtime = time();
            $other->create();
            return $result;
        }
        else{
            //上传失败
            return array();
        }
    }

    /**
     * 上传项目资质认证信息
     * @author 潘宗磊
     * @param array $files
     * @param string $file_name
     * @param string $filekey
     * @param ORM $other
     * @param ORM $company
     * @return multitype:|Ambigous <array('error'=>'','path'=>'','name'=>''), multitype:string , unknown>
     */
    public function uploadProjectCertification($files, $certs_name,$filekey,$other,$company,$upnum){
        $result = common::uploadPics($files, $filekey,array(array(172,122)));
        if(!empty($result)){
            for($i=0;$i<count($certs_name);$i++){
                if($upnum+$i<12){
                    if(Arr::get($result[$i], 'path')){
                        $org_pic = str_replace('/s_','/b_', Arr::get($result[$i], 'path'));
                        //上传信息
                        $other->com_id = $company->com_id;
                        $other->project_certs_name= $certs_name[$i];
                        $other->project_certs_img = $org_pic;
                        $other->project_certs_addtime = time();
                        $other->save();
                        $other->clear();
                    }else{
                        //上传失败
                        return array();
                    }
                }
            }
        }
        return $result;
    }
    /**
     * 上传项目资质认证信息名称
     * @author 潘宗磊
     * @param certs_id,certs_name
     */
    public function editProjectCertName($certs_id,$certs_name){
        $projects=ORM::factory('Projectcerts',$certs_id);
        $projects->project_certs_name=$certs_name;
        if($projects->save()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除单个项目资质认证
     * @author 潘宗磊
     */
    public function deleteProjectCertification($cert_id){
        $Projectcerts=ORM::factory("Projectcerts");
        $result = $Projectcerts->where("project_certs_id", "=",$cert_id)->find();
        //当这条数据存在的情况下，这删除这个数据并删除相应的图片
        if(!empty($result->project_certs_id)){
            if(!empty($result->project_certs_img)){
                $delete = common::deletePic($result->project_certs_img);
                if($delete != 1) return false;
            }
            $Projectcerts->delete($cert_id);
            return true;
        }
        return false;
    }

    /**
     * @sso
     * 验证邮箱是否已通过验证
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function getEmailValidCount($userid){
        //return ORM::factory('User')->where('user_id', '=',$userid)->where('valid_email', '=',self::ENABLE_STATUS)->count_all();
        $client = Service_Sso_Client::instance();
        $userinfo = $client->getUserInfoById($userid);
        if($userinfo){
            $valid = (bool)Arr::get((array)$userinfo,"valid_email");
            return $valid;
        }
        return false;
    }

    /**
     * 验证企业资质是否已通过验证
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function getCompanyAuthCount($userid){
        return ORM::factory('Companyinfo')->where('com_user_id', '=' ,$userid )->where('com_auth_status', '=', self::ENABLE_STATUS)->count_all();
    }

    /**
     * 获取我的企业信息
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function getCompanyInfo($userid){
        return ORM::factory('Companyinfo')->where('com_user_id', '=', $userid)->find();
    }

    /**
     * 获取我的企业信息
     * @param  [int] $com_id [当前企业ID]
     * @author 钟涛
     */
    public function getCompanyInfoByComId($com_id){
        return ORM::factory('Companyinfo', $com_id)->as_array();
    }
    /**
     * 获取我的企业项目信息
     * @param  [int] $com_id [企业用户信息表ID]
     * @author 钟涛
     */
    public function findProjectInfo($com_id){
        return ORM::factory('Project')->where('com_id', '=', $com_id)->find_all();
    }

    /**
     * 获取投资者从业经验信息
     * @author 钟涛
     */
    public function getExperienceById($user_id){
        $service_user= new Service_User();
        $area_service = new Service_Public();
        //获取从业经验
        $resultlist = ORM::factory('Experience')->select('*')->where('exp_user_id','=',$user_id)->find_all();
        $resultarr=array();
        foreach ($resultlist as $key=>$value){
            $starttime = $value->exp_starttime;//开始时间
            $endtime = $value->exp_endtime;//结束时间
            if(empty($starttime)){
                $starttime="未知";
            }else{
                $starttime= substr($starttime, 0,4).'年'.substr($starttime, 4,2).'月';
            }if(empty($endtime)){
                $endtime="未知";
            }else{
                if( $endtime=='0' ){
                    $endtime= '今天';
                }else{
                    $endtime= substr($endtime, 0,4).'年'.substr($endtime, 4,2).'月';
                }
            }
            $pro_name= $area_service->getAreaName($value->pro_id);
            $area_name= $area_service->getAreaName($value->area_id);

            $resultarr[$key]['exp_starttime'] =$starttime;//开始时间
            $resultarr[$key]['exp_endtime'] = $endtime;//结束时间
            $resultarr[$key]['area']= $pro_name.$area_name;//工作地点
            $resultarr[$key]['exp_company_name']= $value->exp_company_name;//企业名称
            //企业性质
            foreach ( common::comnature_new() as $k=>$vs ){
                if( $k==$value->exp_nature ){
                    $resultarr[$key]['exp_nature']= $vs;
                }else{
                    $resultarr[$key]['exp_nature']= '';
                }
            }
            //企业规模
            foreach( common::comscale() as $k=>$vs ){
                if( $k==$value->exp_scale ){
                    $resultarr[$key]['exp_scale']= $vs;
                }else{
                    $resultarr[$key]['exp_scale']= '';
                }
            }

            //所在部门
            $resultarr[$key]['exp_department']= $value->exp_department;

            //行业类别
            $rs_profession= $service_user->getProfessionRow( $value->exp_industry_sort );

            $resultarr[$key]['exp_industry_sort_name']= $rs_profession['profession_name'];
            //职业类别
            $rs_pos= $service_user->getPositionRow( $value->exp_occupation_type );
            $resultarr[$key]['pos_name']= $rs_pos['position_name'];
            //职业名称
            if( $value->exp_occupation_name!='0' ){
                $rs_pos= $service_user->getPositionRow( $value->exp_occupation_name );
                $resultarr[$key]['occ_name']= $rs_pos['position_name'];
            }else{
                $resultarr[$key]['occ_name']= $value->exp_user_occupation_name;
            }

            $resultarr[$key]['exp_description'] = $value->exp_description; //从业描述

        }

        return $resultarr;
    }

    /**
     * @sso
     *企业基本信息管理(表中没有的新增，有的更新)
     *@author周进
     */
    public function updateCompanyBasic($post,$user_id,$com_name,$mobile="",$img=""){
        $result['status'] = 0;
        /*$validation = Validation::factory($post)->rule("com_adress", "not_empty")->rule("com_adress", 'max_length', array(':value', '80'))->rule("com_contact", "not_empty")->rule("com_contact", 'max_length', array(':value', '20'))->rule('branch_phone', 'numeric');
        if (!$validation->check()){
            $result = array('error' => $validation->errors(),'status'=>'-1');
            return $result;
        }*/
        $client = Service_Sso_Client::instance();
        if($post['com_site']) {
                $validationErr = Validation::factory($post)->rule("com_site", "url");
                if (!$validationErr->check()){
                $returnErr = array('error' => $validationErr->errors(),'status'=>'-1');
                return $returnErr;
            }
        }
        if(isset($post['mobile']) && $mobile!=$post['mobile']){//判断手机号码是否修改
            //$count=ORM::factory('user')->where('valid_mobile', '=', 1)->where('mobile','=',$post['mobile'])->count_all();
            $sr_user = new Service_User();
            $binded = $sr_user->isMobileBinded($post['mobile']);
            if(!$binded){//判断是否存在已经绑定的手机号码
                $serice = new Service_User_Company_User();
                //$serice -> unbindMobile($user_id);//解除手机绑定
                $tag = $client->setUserMobileById($user_id, $post['mobile']);
            }else{
                $result = array('error' => array('certmobile'=>'这个号码已经存在，并且已经绑定!'),'status'=>'-1');
                return $result;
            }
        }

        //手机注册的会员，更新邮箱
        if( isset( $post['per_mail'] ) && $post['per_mail']!='' ){
            Service_Sso_Client::instance()->setUserEmailById( $user_id,trim($post['per_mail']) );
        }

        $company = ORM::factory('Companyinfo');
        $result = $company->where('com_user_id', '=', $user_id)->find()->as_array();
           if ($post['com_logo']==""&&$result['com_logo']==""){//如果上传图片为空并且原有图片也为空
                $result = array('error' => array('com_logo'=>'图片上传不能为空!'),'status'=>'-1');
                return $result;
        }

        if(Arr::get($post, 'com_logo'))
          $company->com_logo = common::getImgUrl(Arr::get($post, 'com_logo'));
            //如果已经有图片，删除原有服务器上的图片
        if ($result['com_logo']!=""&&Arr::get($post, 'com_logo')){
            common::deletePic(URL::imgurl($result['com_logo']));
        }
        $company->com_user_id = $user_id;
        $company->com_name = $com_name;
        $company->com_adress = $post['com_adress'];
        $company->com_phone = $post['com_phone'];
        if(!empty($post['branch_phone'])){//判断分机号是否为空
            $company->com_phone.='+'.$post['branch_phone'];
        }
        $company->com_contact = $post['com_contact'];
        $company->com_nature = $post['com_nature'];
        $company->com_site = $post['com_site'];

        //add by 许晟玮 date 2013年4月24日
        //公司成立时间组成
        $company->com_founding_time = $post['com_founding_time_year'].$post['com_founding_time_month'];
        $company->com_registered_capital    = $post['com_registered_capital'];
        $company->com_desc    = $post['com_desc'];
        $company->com_area= $post['com_area'];
        $company->com_city= $post['com_city'];

        //end
        if ($result['com_id']>0){//更新

            $company->com_id = $result['com_id'];
            $res =$company->update();
            if($res)
               $result['status'] = 1;
        }
        else{//不存在新增Companyinfo表数据

            $res = $company->create();

            if($res){
               $result['com_id'] = $res->com_id;
               $result['status'] = 1;
            }
            //添加名片log、
            //$card_ser=new Service_Card();
            //$card_ser->addCardBehaviourInfo($res->com_user_id,0,1,1);
        }
        if($result['status']==1){//更新名片图片信息
            $service=new Service_User_Company_Card();
            $Project_model = ORM::factory('Project');
            $pro = $Project_model->where('com_id', '=', $res->com_id)->where('project_status', '=', 2)->find_all();
            $data = unserialize($res->com_card_config);
            $projectinfo = $service->getProjectByCompanyCard($data);
            $service->getComCardImage($res,$pro,$projectinfo['brand']);
        }
        return $result;
    }


    /**
     *添加招商项目时，没有完成企业基本信息的，先保存已经填写好的到session里，等完成基本信息后再写入数据库
     *@author曹怀栋
     */
    public function getSessionProject($data,$com_id,$user_id){
        $data['com_id'] = $com_id;
        $service = new Service_User_Company_Project();
        $service->addProject($data,$user_id);
        $session = Session::instance();
        $session->delete("addproject");
        return true;
    }

    /**
     *标签表的相关取值
     *@author周进
     *@param $data serialize(array()) 序列化标签ID串a:2:{i:0;s:1:"1";i:1;s:1:"2";}不传的话默认查所有tag_status=1的
     *@param project_industry_id 行业ID
     *@return ORM
     */
    public function findTag($data='',$project_industry_id=0){
        $tag = ORM::factory('Usertype');
        if ($data==''){
            $result = $tag->where('tag_status', '=', '1')->find_all()->as_array();
        }
        else{
            $ids = unserialize($data);
            $tag->or_where_open();
            foreach ($ids as $v){
                $orwhere = $tag->or_where('tag_id', '=', $v);
            }
            $tag->or_where_close();
            $result = $tag->and_where_open()->and_where('tag_status', '=','1')->and_where_close()->find_all()->as_array();
        }
        return $result;
    }


    /**
     * @sso
     * ajax 检查邮件验证的邮件是否发送成功
     * @author 周进 update @ 2012/12/6新增时效和重发的安全验证
     * @edit by 许晟玮 ,增加$editemail ，true:修改邮箱的验证右键 ;false:注册邮箱的右键验证;2:第三方email验证;      */
    public function updateCheckValidEmail($user_id,$email,$editemail=false){
        $msg['status'] = '-2';
        $user = Service_Sso_Client::instance()->getUserInfoById($user_id);
        if ($user->valid_email==1){
            return $msg;
        }
        $valid = ORM::factory("Validcode");
        if ($user_id!=""&&$email!=""){
            $userserice = new Service_User_Company_User();
            $valid_emailcode = $userserice->createValidCode($user_id,1);
            if($valid_emailcode!=false){
                if( $editemail===false ){
                    $url = "http://".$_SERVER['HTTP_HOST']."/member/checkvemail/?key=".$user_id."O".rand('10000', '99999')."&code=".md5($valid_emailcode);
                }elseif( $editemail=='2' ){
                    $url = "http://".$_SERVER['HTTP_HOST']."/member/checkvemail/?key=".$user_id."O".rand('10000', '99999')."&code=".md5($valid_emailcode)."&tzdf=".md5('2');
                }else{
                    //edit email valid url
                    $url = "http://".$_SERVER['HTTP_HOST']."/member/checkvemail/?key=".$user_id."O".rand('10000', '99999')."&code=".md5($valid_emailcode)."&es=".md5($user_id.'0');
                }

                $content = '<p>尊敬的一句话用户：</p><p>您好！</p><p>请于两个小时之内点击以下链接验证邮箱。</p><p><a href="'.$url.'">'.$url.'</a></p>
                            <p>如果上面的链接无法点击，您也可以复制链接，粘贴到您浏览器的地址栏内，然后按“回车”键打开预设页面，完成相应功能。</p><p>如果有其他问题，请联系我们：service@yijuhua.net 谢谢！</p><p>此为系统消息，请勿回复</p>';

                $content= "<body style='background-color:#f7f7f7;'>";
                $content.= "<div class='email_clum' style='width:629px; margin:0 auto;'>";
                $content.= "<p class='email_title' style='height:74px; margin:0;'><a href='".URL::website('/')."' target='_blank' style='float:left; margin:21px 0 0 22px;'><img src='".URL::webstatic('images/email_yz/logo_email.png')."' style='float:left; padding:0 0 0 23px; border:none;' /></a><img src='".URL::webstatic('images/email_yz/title_email.png')."' style='float:left; padding:0 0 0 23px; border:none;' /></p>";
                $content.= "<div class='email_cont' style='border:1px solid #c7c0c0; border-top:2px solid #dd4848; padding:0 54px; width:519px; height:auto!important; height:300px; min-height:300px; background-color:#fff; color:#333; font-family:微软雅黑; font-size:12px; line-height:20px;'>";
                $content.= "<p class='email_cont_title' style='padding-top:40px; font-weight:bold;  font-family:微软雅黑; font-size:14px; line-height:30px; height:30px;'>尊敬的会员：</p>";
                if( $editemail===false ){
                    $content.= "<span class='email_text01' style='display:block;padding-top:16px;'>欢迎加入一句话网站。投资赚钱好项目，一句话的事。您在一句话网站的基本注册信息如下：<br/>注册邮箱：<a href='mailto:".$email."'   style='color:#27528d; text-decoration:none;'>".$email."</a></span>";
                }else{
                    $content.= "<span class='email_text01' style='display:block;padding-top:16px;'>欢迎您进入一句话网站修改邮箱功能页面。投资赚钱好项目，一句话的事。您的新邮箱信息如下：<br/>新邮箱：<a href='mailto:".$email."'   style='color:#27528d; text-decoration:none;'>".$email."</a></span>";
                }
                $content.= "<span class='email_text02' style='display:block;padding-top:26px; height:30px; line-height:30px;'>请点击下方链接地址激活您的账号，或将地址复制至浏览器地址栏内回车并确认注册：</span>";
                $content.= "<div class='email_link' style='background-color:#eef8ff; border:1px solid #cee4f6;width:487px; padding:12px 15px 18px 15px; margin:0; color:#999;'>";
                $content.= "<a href='".$url."' target='_blank' style='color:#0049ae; text-decoration:underline; width:487px; word-break: break-all; word-wrap:break-word;'>".$url." </a>";
                $content.= "<br/><br/>";
                $content.= "如果通过此方法无法激活，您可以通过注册邮箱发送任意内容的邮件至<a href='mailto:kefu@yijuhua.net'  style='color:#0049ae; text-decoration:underline; width:487px; word-break: break-all; word-wrap:break-word;'>kefu@yijuhua.net</a>，<br/>
        或拨打我们的客服电话400 1015 908。";
                $content.= "</div>";
                if( $editemail===false ){
                    $content.= "<span class='email_text03' style='display:block;padding:35px 0 53px 0;'>注册激活一句话，激活成功机遇，激活财富人生。<br/>祝您使用一句话网站愉快。</span>";
                }else{
                    $content.= "<span class='email_text03' style='display:block;padding:35px 0 53px 0;'>激活一句话，激活成功机遇，激活财富人生。<br/>祝您使用一句话网站愉快。</span>";
                }
                $content.= "</div>";
                $content.= "<p class='email_bot' style='margin:22px 0 10px 0; height:14px; background-color:#eaeaea; padding:8px 0 6px 0; text-align:center;'><a href='".urlbuilder::help('aboutus')."' target='_blank' style='color:#333; font-size:12px; line-height:14px; font-family:微软雅黑; text-decoration:none; display:inline-block;'>关于我们</a><span style='display:inline-block;color:#333; font-size:12px; line-height:14px; font-family:微软雅黑; padding:0 8px;'>|</span><a href='".urlbuilder::help('lianxi')."' target='_blank' style='color:#333; font-size:12px; line-height:14px; font-family:微软雅黑; text-decoration:none; display:inline-block;'>联系我们</a></p>";
                $content.= "</div>";
                $content.= "</body>";
                //exit($content);
                $sendresult = false;
                $sendresult = common::sendemail("邮箱验证", 'service@yijuhua.net', $email, $content);
                if ($sendresult==1){
                        $msg['status'] = '1';
                }
            }
        }
        return $msg;
    }

    /**
     * 招商通服务申请
     * @author 曹怀栋
     */
    public function applyBusiness($data){
        $model = ORM::factory("Applybusiness");
        foreach ($data as $k=>$v){
            $model->$k = trim($v);
        }
        $model->business_addtime = time();
        $model->create();
        return true;
    }

    /**
     * 判断当前用户是否申请招商通服务
     * @author 潘宗磊
     */
    public function applyStatus($user_id){
       $count = ORM::factory('Applybusiness')->where('user_id','=',$user_id)->count_all();
       if($count>0){
            return false;
       }else{
            return true;
       }
    }

    /**
     * 获取用户招商通服务信息
     * @author 潘宗磊
     */
    public function getApplyBusiness($user_id){
        $model = ORM::factory('Applybusiness')->where('user_id','=',$user_id)->find();
        if($model->id>0){
            return $model;
        }else{
            return false;
        }
    }

    /**
     * 获取所有的企业用户
     * @auhtor 施磊
     * @param int $user_status 用户状态
     * @return $array 所有的企业用户
     */
    public function getCompanyUserList($user_status = 1) {
        $obj = ORM::factory('User')->select('*')->join('account', 'LEFT')->on('user_id', '=', 'account_user_id')->join('user_company', 'LEFT')->on('user_id', '=', 'com_user_id')->where('user_type', '=', 1)->where('user_status', '=', $user_status)->find_all();
        $return = array();
        foreach($obj as $val) {
            $return[] = $val->as_array();
        }
        return $return;
    }
    /**
     * @sso
     * 修改企业用户信息
     * @author 施磊
     * @param int $user_id 企业用户
     */
    public function editUser($user_id, $param) {
        if(!intval($user_id)) return FALSE;
        $ormModel = ORM::factory('User', intval($user_id));
        $ormModel->values($param)->check();
        $ormModel->update();
    }

    /**
     * 获得企业用户和企业基本信息
     * @author 施磊
     */
    public function getCompanyAndUserBasic($user_id) {
        if(!intval($user_id)) return FALSE;
        $obj = ORM::factory('User')->select('*')->join('account', 'LEFT')->on('user_id', '=', 'account_user_id')->join('user_company', 'LEFT')->on('user_id', '=', 'com_user_id')->where('user_id', '=' ,$user_id)->find()->as_array();
        return $obj;
    }
    /**
     * @sso
     * 重置密码
     * @author 施磊
     */
    public function resetPassWord($user_id) {
        if(!intval($user_id)) return array();
        $userInfo = $this->getUserInfoById($user_id);
        if($userInfo->user_id && $userInfo->mobile && $userInfo->email) {
            $pass = 'tonglukuaijian'.rand(0,9999);
            $param = array('password' => sha1($pass.$userInfo->email));
            try {
                $this->_sendNewPassEmailAndMsm($user_id,$userInfo->email, $userInfo->mobile, $pass);
                //$return = $this->editUser($user_id, $param);

            } catch(Kohana_Exception $e){
                return false;
            }
        }else {
            return FALSE;
        }
        //$this->_sendNewPassEmailAndMsm($user_id,$userInfo->email, $userInfo->mobile, $pass);
        $return= Service_Sso_Client::instance()->resetPassword( $userInfo->user_id,$pass );
        //$return = $this->editUser($user_id, $param);
        if( $return===false ){
            return false;
        }
    }

    /*
     * 后台api 新增企业用户
     * @author 施磊
     * @param array $param 后台新增进用户表的数据
     * @return int  $user_id 新增的用户id
     */
    public function addCompanyUser($param = array()) {
        if(!$param) return FALSE;
        $ormModel = ORM::factory('User');
        $ormModel->values($param)->create();
        return $ormModel->user_id;
    }

     /*
     * 后台api 新增企业信息
     * @author 施磊
     * @param array $param 后台新增进企业表的数据
     */
    public function addCompanyInfo($param = array()) {
        if(!$param) return FALSE;
        $ormModel = ORM::factory('Companyinfo');
        $ormModel->values($param)->create();
        return $ormModel->com_id;
    }

    /*
     * 后台api 新增企业认证图片信息
     * @author 施磊
     * @param array $param
     */
    public function addCompanyAuthImg($param = array()) {
        if(!$param) return FALSE;
        $ormModel = ORM::factory('CommonImg');
        $param['url']=common::getImgUrl($param['url']);
        $ormModel->values($param)->create();
        return $ormModel->com_id;
    }

    /**
     * @sso
     * 检查用户邮箱是非被注册过
     * @author 施磊
     * @param string $email
     */
    public function checkUserEmailOrId($email, $user_id = 0) {
        if(!$email) return FALSE;
        $service= Service_Sso_Client::instance();

        if($user_id){
            //判断用户是否修改过邮箱
            $info= $service->getUserInfoById($user_id);
            if( $info->email!=$email ){
                $r= $service->isRegNameValid($email);

                if($service->isRegNameValid($email)===true){
                    $allobj= 0;
                }else{
                    $allobj= 1;
                }
            }else{
                $allobj= 0;
            }

           // $allobj = $ormModel->where('email', '=', $email)->where('user_id', '!=' ,  intval($user_id))->count_all();

        }else {
            if($service->isRegNameValid($email)){
                //没有注册
                $allobj= 0;
            }else{
                $allobj= 1;
            }
           //$allobj = $ormModel->where('email', '=', $email)->count_all();

        }
        return $allobj;
    }

    /**
     * 检查企业名是非被注册过
     * @author 施磊
     * @param string $com_name
     */
    public function checkComNameOrId($com_name, $com_id = 0) {
        if(!$com_name) return FALSE;
        $ormModel = ORM::factory('Companyinfo');
        if($com_id){
            $allobj = $ormModel->where('com_name', '=', $com_name)->where('com_id', '!=' ,  intval($com_id))->count_all();
        }else {
           $allobj = $ormModel->where('com_name', '=', $com_name)->count_all();
        }
        return $allobj;
    }
    /**
     * 修改用户信息
     * @author 施磊
     */
    public function editCompanyUserByApi($user_id, $userParam) {
        if(!intval($user_id)) return FALSE;
        $ormModel = ORM::factory('User', intval($user_id));
        $ormModel->values($userParam)->check();
        $ormModel->update();
    }

    /**
     * 修改企业信息
     * @author 施磊
     */
    public function editCompanyInfoByApi($com_id, $arreditCompany) {
        if(!intval($com_id)) return FALSE;
        $ormModel = ORM::factory('Companyinfo', intval($com_id));
        $ormModel->values($arreditCompany)->check();
        $ormModel->update();
    }
    /**
     * @sso
     * 根据用户id取信息
     * @author 施磊
     */
    public function getUserInfoById($user_id,$clean=false) {
        $user = Service_Sso_Client::instance()->getUserinfoById($user_id);
        $user->user_id = $user->id;
        //对应关系兼容性调整
        if($clean){
            $basic = ORM::factory("User",$user->id);
            $user->basic = $basic;
            //企业用户
            if($user->user_type == 1){
                $user->user_company = $basic->user_company;
            }
            //个人用
            elseif($user->user_type == 2){
                $user->user_person = $basic->user_person;
            }
        }
        return $user;
        /*
        if(!intval($user_id)) return array();
        $obj = ORM::factory('User', $user_id);
        return $obj;
        */
    }
    /**
     * 获得最后一次充值记录
     * @author 施磊
     */
    public function getAccountLastLogById($user_id) {
        if(!intval($user_id)) return array();
        $obj = ORM::factory('Accountlog')->where('account_user_id','=', $user_id)->order_by('account_log_time', 'DESC')->find()->as_array();
        return $obj;
    }

    /**
     * @sso
     * 获得企业审核数据
     * @author 施磊
     * @return array
     */
    public function getCompanyCertification($cond = array()) {
        $obj = ORM::factory('User')->select('*')->join('user_company', 'LEFT')->on('user_id', '=', 'com_user_id');
        if(isset($cond['status'])) {
            $status = intval($cond['status']);
            $obj->where('com_business_licence_status', '=', $status)->where('organization_credit_status', '=', $status)->where('tax_certificate_status', '=', $status);
        }else {
            $obj->where('com_business_licence_status', '!=', 3)->where('organization_credit_status', '!=', 3)->where('tax_certificate_status', '!=', 3);
        }
        if(isset($cond['searchCond']) && isset($cond['searchCon']) && !empty($cond['searchCon'])) {
           $obj->where($cond['searchCond'], 'like', $cond['searchCon'].'%');
        }
        if(isset($cond['searchFrom']) && !empty($cond['searchFrom'])) {
           $obj->where('com_auth_submit_time', '>=', $cond['searchFrom']);
        }
        if(isset($cond['searchTo']) && !empty($cond['searchTo'])) {
           $obj->where('com_auth_submit_time', '<=', $cond['searchTo']);
        }
        $objArr = $obj->find_all();
        $arrayReturn = array();
        $list = array();
        foreach ($objArr as $val) {
           $list['companyInfo'] = $val->as_array();
           $list['commonImg'] = $this->getCommonImgByCompanyId($list['companyInfo']['com_id']);
           $arrayReturn[] = $list;
        }
        return $arrayReturn;
    }
    /**
     * 通过企业id获得企业上传的资历图片
     * @author 施磊
     */
    public function getCommonImgByCompanyId($company_id) {
        $company_id = intval($company_id);
        if(!$company_id) return array();
        $commonimg_list = ORM::factory("CommonImg")->where('table_name','=',1)->where('user_id','=',$company_id)->find_all();
        $return = array();
        foreach($commonimg_list as $val) {
            $this_val=$val->as_array();
            $this_val['url']=URL::imgurl($this_val['url']);
            $return[] = $this_val;
        }
        return $return;
    }
    /**
     * 重置密码的邮件和短信
     * @author 施磊
     */
    private function _sendNewPassEmailAndMsm($user_id,$email, $phone, $pass) {
        $phones=common::getCustomerPhone();
        $msgage = '您的“一句话”招商平台会员密码已经重置为 '.$pass.'，请尽快登录'.URL::website('').'修改密码，保障信息安全。如有问题，请致电'.$phones[1].' 。';
        if($phone) {
            $resultmsg = common::send_message($phone, $msgage,"online");
            $type=4;//重置密码
            //消息发送成功
            if($resultmsg->retCode === 0){
                $this->messageLog($phone,$user_id, $type,$msgage,1);
            }else{//发送失败
                $this->messageLog($phone,$user_id, $type,$msgage,0);
            }
        }
        if($email) {
            $sendresult = common::sendemail("密码重置邮件", 'service@yijuhua.net', $email, $msgage);
        }

    }

    /**
     * @author 施磊
     * 返回企业用户资质状态
     */
    public function checkCompanyCertificationStatus($com_id) {
        $comUser = $this->getCompanyInfoByComId($com_id);
        if(!$comUser['com_id']) return 3;
        $return = (arr::get($comUser, 'organization_credit_status', 0) != 3 && arr::get($comUser, 'tax_certificate_status', 0) != 3 && arr::get($comUser, 'com_business_licence_status', 0) != 3) ? 0 : 3;
        if(!$return) {
            return arr::get($comUser, 'com_auth_status', 0);
        }
        return $return;
    }

    /**
     * 获得企业用户logo
     * @author 施磊
     * @param int $com_id 企业id
     * @param array $com_info 企业数据 可不传
     * @return string 企业logo完整地址
     *
     */
    public function getCompanyLogo($com_id, $com_info = array()) {
        $com_id = intval($com_id);
        if(!$com_id) return '';
        $com_info = (!$com_info) ? $this->getCompanyInfoByComId($com_id) : $com_info;
        $projectMod = ORM::factory('Project')->where('com_id','=', $com_id)->where('project_source','!=', 1)->where('outside_id','!=', 0)->find()->as_array();
        if($projectMod['outside_id']) {
            return project::conversionCompanyImg($projectMod['outside_id'], $projectMod['project_source'], 'logo', $com_info);
        }else {
            return URL::imgurl($com_info['com_logo']);
        }
    }

    /**
     * 修改企业信息表
     * @author 施磊
     */
    public function editComUser($user_id, $param) {
        if (!intval($user_id))
            return FALSE;
        $ormModel = ORM::factory('Companyinfo')->where('com_user_id', '=', $user_id)->find();
        if($ormModel->com_id) {
            $ormModel->com_logo = common::getImgUrl($param);
            $ormModel->update();
        }else {
            $ormModel->com_user_id = $user_id;
            $ormModel->com_logo = common::getImgUrl($param);
            $ormModel->create();
        }

    }

    /**
     * 修改加盟店数量
     * @author许晟玮
     */
    public function editComStoreNum( $uid,$num=0 ){
        $ormModel = ORM::factory('Companyinfo')->where('com_user_id', '=', $uid)->find();
        if($ormModel->com_id) {
            $ormModel->com_store = $num;
            $ormModel->update();
        }else {
            $ormModel->com_user_id= $uid;
            $ormModel->com_store = $num;
            $ormModel->create();
        }
    }

    /**
     * 修改企业的企业营业执照编号
     * @author 许晟玮
     */
    public function editComBusinessLicenceNumber( $com_id,$value ){
        $orm = ORM::factory('Companyinfo',$com_id);
        $orm->com_business_licence_number= $value;
        $orm->update();
    }
    /**
     * 根据project表里面的out_com_id获取czzs_out_user_company里面的企业信息专用
     * @author 赵路生
     */
    public function getOutComInfoByOutComid($out_com_id){
    	$out_com_id = intval($out_com_id);
    	if($out_com_id){
    		$model = ORM::factory('OutComUser')->where('com_id','=',$out_com_id)->find();
    		if($model->loaded()){
    			return $model;
    		}
    	}
    	return false;
    }
    /**
     * 根据project表里面的out_com_id获取czzs_test_company里面的企业信息专用
     * @author 赵路生
     */
    public function getTestComInfoByOutComid($out_com_id){
    	$out_com_id = intval($out_com_id);
    	if($out_com_id){
    		$model = ORM::factory('TestCompany')->where('com_id','=',$out_com_id)->find();
    		if($model->loaded()){
    			return $model;
    		}
    	}
    	return false;
    }
    
    /**
     * 获取申请客服信息
     * @author 党中央
     */
    public function GetProjectUpgradeInfo($project_id = 0,$out_project_id = 0){
    	if($project_id != 0 && $out_project_id == 0){
    		return ORM::factory("ProjectUpgrade",intval($project_id))->as_array();
    	}elseif($out_project_id !=0 && $project_id == 0){
    		return ORM::factory("ProjectUpgrade")->where("out_project_id","=",intval($out_project_id))->find()->as_array();
    	}else{
    		return ORM::factory("ProjectUpgrade")->where("project_id","=",intval($project_id))->where("out_project_id","=",intval($out_project_id))->find()->as_array();
    	}
    	return array();
    }
    
    /**
     * 执行添加  或者  修改
     * @author 党中央
     */
    public function DoProjectUpgradeInfo($arr_data){
    	//echo "<pre>"; print_r($arr_data);exit;
    	$bool = false;
    	$model = ORM::factory("ProjectUpgrade");
    	if(arr::get($arr_data,"project_id") == 0){
    		//执行添加
    		unset($arr_data['project_id']);	
    		foreach ($arr_data as $key=>$val){
    			$model->$key = $val;
    		}
    		$obj = $model->save();
    		return $obj->project_id;
    	}else{
    		//执行修改
    		$new_model = $model->where("out_project_id","=",arr::get($arr_data,"out_project_id"))->where("project_id","=",arr::get($arr_data,"project_id"))->find();
    		if($new_model->loaded()){
    			unset($arr_data['project_id']);	
    			unset($arr_data['out_project_id']);
	    		foreach ($arr_data as $key=>$val){
	    			$new_model->$key = $val;
	    		}
	    		$obj = $new_model->update();
	    		return $obj->project_id;
    		}
    	}
    	return 0;
    }
}