<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 投资保障
 * @author 许晟玮
 */
class Controller_User_Company_Guard extends Controller_User_Company_Template{
    /**
     *投资保障
     * @author许晟玮
     */
    public  function action_index(){

        $content= View::factory('user/company/comserver');
        $this->content->rightcontent = $content;
        $user_id= $this->userId();
        //获取用户投资保障状态
        $service_status= new Service_User_Company_ComStatus();
        $rs_all_server= $service_status->getCompanyStatusInfo($user_id,"all");

        $content->server= $rs_all_server;
        //判断企业用户是否完善了基本信息
        $service= new Service_User_Company_User();
        $eof= $service->is_complete_basic($user_id);
        if( $eof===true ){
            $content->complete= '1';
        }else{
            $content->complete= '2';
        }

    }
    //end function

    /**
    * 服务保障
    * @author 许晟玮
    */
    public function action_server(){
        $content= View::factory('user/company/comserverguard');

        $user= $this->userInfo();
        $user_id= $user->user_id;
        //如果这个保障已经审核通过了,则跳转至显示页面
        $service_status= new Service_User_Company_ComStatus();
        $server_guard= $service_status->getCompanyStatusInfo($user_id,'server');
        if( $server_guard=="1" ){
            self::redirect( '/company/member/guard' );
        }

        $invest= new Service_User_Person_Invest();
        //获取省份 城市
        $pro= $invest->getArea();
        //$all= array('cit_id' => 88,'cit_name' => '全国');
        //array_unshift($pro, $all);


        $content->mobile= $user->valid_mobile;
        $content->area= $pro;
        $this->content->rightcontent = $content;

        //post
        if($this->request->method()== HTTP_Request::POST){
            $post= Arr::map( "HTML::chars", $this->request->post() );

            //tel valid
            //判断手机号码是否为绑定的号码
            $receiver = Arr::get($post, "receiver");

            if( $receiver!="" ){
                $service_user = Service::factory("User");
                if($service_user->isMobileBinded($receiver)){
                }else{
                    $check_code = Arr::get($post,"check_code");
                    if( $service_user->bindMobile( $user_id,$receiver,$check_code ) ){
                        //手机绑定通过，增加积分
                        $points = Service::factory("User_Company_Points");
                        $points->getPointsTimes($user_id,"valid_mobile");
                        //手机绑定成功，增加诚信点
                        $integrity = Service::factory("User_Company_Integrity");
                        if($integrity->getIntegrityOnce($user_id,"valid_mobile")){
                            //手机号码通过验证，发送消息提醒
                            //$msg_service = new Service_User_Ucmsg();
                            //$msg_service->pushMsg($user->user_id, "company_integrity", "您已经验证手机号码，增加60点诚信指数。",URL::website("company/member/basic/integrity"));
                            $smsg = Smsg::instance();
                            //内部消息发送
                            $smsg->register(
                                    "tip_company_integrity",//我的诚信
                                    Smsg::TIP,//类型
                                    array(
                                            "to_user_id"=>$user->user_id,
                                            "msg_type_name"=>"company_integrity",
                                            "to_url"=>URL::website("company/member/basic/integrity")
                                    ),
                                    array(
                                            "code"=>"60",
                                            "type"=>"tel"

                                    )

                            );


                        }else{

                        }

                    }
                }
            }else{

            }

            //store number
            $store_num= Arr::get($post, "store_num",0);

            //edit user info
            $service_user= new Service_User_Company_User();
            $service_user->editComStoreNum( $user_id,ceil( $store_num ) );

            //edit store info
            $store_service= new Service_User_Company_ComStore();
            //area array
            $area_arr= Arr::get($post, "area");

            //city array
            $city_arr= Arr::get($post, "area_id");

            //address array
            $address_arr= Arr::get($post, "address");


            if( !empty( $area_arr ) ){
                //增加用户加盟店地址
                //删除这个用户的加盟店信息后重新加入
                $store_service->delUserStore($user_id);
                foreach( $area_arr as $key=>$vss ){
                    if( $vss!="" ){
                        $store_service->setStore($user_id, $vss, $city_arr[$key], $address_arr[$key]);
                    }

                }
            }else{

            }

            //add pic
            //img url array
            $img_arr= Arr::get($post, "img_src");
            if( !empty( $img_arr ) ){
                $store_service->delImages( $user_id );
                foreach( $img_arr as $img_vss ){
                    if( $img_vss!="" ){
                        $store_service->addImages( $img_vss,$user_id );
                    }
                }
            }else{

            }

            //edit server status

            $service_status->setComStatus($user_id, "server",0);
            $content= View::factory('user/company/comserver');
            $this->content->rightcontent = $content;
            $user_id= $this->userId();
            //获取用户投资保障状态
            $service_status= new Service_User_Company_ComStatus();
            $rs_all_server= $service_status->getCompanyStatusInfo($user_id,"all");

            $content->server= $rs_all_server;
            $content->server_ok= "1";

            //判断企业用户是否完善了基本信息
            $service= new Service_User_Company_User();
            $eof= $service->is_complete_basic($user_id);
            if( $eof===true ){
                $content->complete= '1';
            }else{
                $content->complete= '2';
            }

        }
        //end post



        //get info
        $service_user= new Service_User_Company_User();
        //获取加盟店数量
        $rs_user= $service_user->getCompanyInfo($user_id)->as_array();

        $content->store_num= ceil($rs_user['com_store']);
        //获取加盟店信息
        $service_store= new Service_User_Company_ComStore();
        $rs_store= $service_store->getUserStore( $user_id );
        $invest= new Service_User_Person_Invest();
        //获取省份 城市
        $pro= $invest->getArea();
        //$all= array('cit_id' => 88,'cit_name' => '全国');
        //array_unshift($pro, $all);
        $address_arr= array();
        if( !empty( $rs_store ) ){
            foreach( $rs_store as $k=>$vs_store ){
                $area_id= $vs_store->store_area;

                $city_id= $vs_store->store_city;
                $area   = array('pro_id'=>$area_id);
                $cityarea = common::arrArea($area)->as_array();
                $ct_arr= array();
                foreach ( $cityarea as $key=>$cv ){
                    $ct_arr[$key]["name"]= $cv->cit_name;
                    $ct_arr[$key]["id"]= $cv->cit_id;
                }
                $address= $vs_store->store_address;

                $address_arr[$k]['area_id']= $area_id;
                $address_arr[$k]['cityarea']= $ct_arr;
                $address_arr[$k]['address']= $address;
                $address_arr[$k]['city_id']= $city_id;
            }
        }else{

        }

        $content->address_arr= $address_arr;


        //获取图片
        $rs_image= $service_store->getImages($user_id);
        $img_arr= array();
        foreach( $rs_image as $key=>$iv ){
            $img_arr[$key]['url']= $iv->project_img;

        }

        $content->img= $img_arr;


    }
    //end function
    /**
     * 服务保障显示页面
     * @author许晟玮
     */
    public function action_serversave(){
        $content= View::factory('user/company/comserversave');
        $this->content->rightcontent = $content;

        $user_id= $this->userId();
        $service_status= new Service_User_Company_ComStatus();
        $server_guard= $service_status->getCompanyStatusInfo($user_id,'server');
        $content->server_status= $server_guard;
        $service_user= new Service_User_Company_User();
        //获取加盟店数量
        $rs_user= $service_user->getCompanyInfo($user_id)->as_array();

        $content->store_num= ceil($rs_user['com_store']);
        //获取加盟店信息
        $service_store= new Service_User_Company_ComStore();
        $rs_store= $service_store->getUserStore( $user_id );
        $invest= new Service_User_Person_Invest();
        //获取省份 城市
        $pro= $invest->getArea();
        //$all= array('cit_id' => 88,'cit_name' => '全国');
        //array_unshift($pro, $all);
        $address_arr= array();
        if( !empty( $rs_store ) ){
            foreach( $rs_store as $k=>$vs_store ){
                $area_id= $vs_store->store_area;
                foreach ( $pro as $pv ){
                    if( $pv['cit_id']==$area_id ){
                        $area_name= $pv['cit_name'];
                        break;
                    }else{

                    }
                }

                $city_id= $vs_store->store_city;

                $area   = array('pro_id'=>$area_id);
                $cityarea = common::arrArea($area)->as_array();
                foreach ( $cityarea as $cv ){
                    if( $cv->cit_id==$city_id){
                        $city_name= $cv->cit_name;
                        break;
                    }else{
                        $city_name= "";

                    }
                }

                $address= $vs_store->store_address;

                $address_arr[$k]['area_name']= $area_name;
                $address_arr[$k]['city_name']= $city_name;
                $address_arr[$k]['address']= $address;

            }
        }else{

        }
        $content->address= $address_arr;

        //获取图片
        $rs_image= $service_store->getImages($user_id);
        $img_arr= array();
        foreach( $rs_image as $key=>$iv ){
            $img_arr[$key]['url']= $iv->project_img;
            $img_arr[$key]['b_image'] = URL::imgurl(str_replace('/s_','/b_', $iv->project_img));
        }

        $content->img= $img_arr;

    }
    //end function

    /**
     * 安全保障第一页
     * @author 许晟玮
     */
    public function action_safeone(){
        $content= View::factory('user/company/comserversafe_one');
        $this->content->rightcontent = $content;
        $service_status= new Service_User_Company_ComStatus();
        $safe_guard= $service_status->getCompanyStatusInfo($this->userId(),'safe');
        if( $safe_guard=="1" ){
            self::redirect( '/company/member/guard' );
        }

    }
    //end function

    /**
     * 安全保障-保证金
     * @author许晟玮
     */
    public function action_safealipay(){
        $content= View::factory('user/company/comserversafe_alipay');
        $this->content->rightcontent = $content;
        $user_id= $this->userId();
        //获取用户余额
        $service_account= new Service_Account();
        $rs= $service_account->getAccount( $user_id );

        $content->account= sprintf("%01.2f", $rs['account']);

        //判断余额是否足够
        $safe_account= comserver::getUserSafeAccount();//定义的保证金数额
        $eof= $service_account->checkAccountNumber( $user_id,$safe_account );
        //判断是否被禁用
        $is_forbid= $service_account->checkAccountStatusNote( $user_id );
        $content->is_forbid= $is_forbid;
        if( $eof===false ){
            $content->status= "no";
        }else{
            $content->status= "yes";
        }
        $service_status= new Service_User_Company_ComStatus();
        $safe_guard= $service_status->getCompanyStatusInfo($user_id,'safe');
        if( $safe_guard=="1" ){
            self::redirect( '/company/member/guard' );
        }


        if($this->request->method()== HTTP_Request::POST){
            if( $eof===true ){
                $ef= $service_status->setSafeStatus($user_id);
                if( $ef===false ){
                    //false
                    self::redirect('/company/member/guard/safealipay');
                }
                //true

                $userinfo= $this->userInfo();

                //获取企业名称
                $comname= $userinfo->user_name;
                //如果手机是验证完的,则发送短信
                if( $userinfo->valid_mobile=="1" ){
                    $phone= common::encodeMoible( $userinfo->mobile );
                    $msgage= "{$comname}您好，您申请的安全保障服务保证金50000元人民币，已于".date("Y.m.d")." 在“一句话”网站账号中心进行冻结，请知悉。如有疑问请拨打免费热线电话400-1015-908。【一句话】www.yjh.com ";
                    $resultmsg= common::send_message($phone, $msgage,"online");
                    $service_user= new Service_User();
                    if( $resultmsg->retCode==1 ){
                        $service_user->messageLog($userinfo->mobile, $user_id, '5', $msgage, '0');
                    }else{
                       $service_user->messageLog($userinfo->mobile, $user_id, '5', $msgage, '1');
                    }
                }

                //发送邮件
                $content= "<p>尊敬的{$comname}：</p>";
                $content.= "<p>您好！</p>";
                $content.= "<p>您申请的安全保障服务保证金50000元人民币，已于".date("Y.m.d")." 在“一句话”网站账号中心进行冻结，请知悉。如有疑问请拨打免费热线电话400-1015-908。【一句话】www.yjh.com</p>";
                $content.= "<p>此为系统消息，请勿回复！</p>";

                common::sendemail('资金冻结通知', 'service@yijuhua.net', $userinfo->email, $content);

                $content= View::factory('user/company/comserver');
                $this->content->rightcontent = $content;
                $user_id= $this->userId();
                //获取用户投资保障状态
                $service_status= new Service_User_Company_ComStatus();
                $rs_all_server= $service_status->getCompanyStatusInfo($user_id,"all");

                $content->server= $rs_all_server;
                $content->safe_ok= "1";
                $content->complete= '1';

            }else{
                self::redirect( '/company/member/guard/safealipay' );
            }

        }


    }
    //end function




}