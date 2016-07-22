<?php

defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * ajax验证
 *
 * @author 龚湧
 *
 */
class Controller_Ajaxcheck extends Controller {
    /**
     * ajax 检查验证码
     */
    public function action_checkvalidcode() {
        $msg = array ();
        $post = $this->request->post ();
        $get = $this->request->query();
        $callback = arr::get ( $get, 'callback' );
        $code = Arr::get($post, 'valid_code');
        if($callback){
            $code = Arr::get($get, 'valid_code');
        }
          if (Captcha::valid ( $code )) {
              if($callback){
                  echo $callback.'('.true.')';
              }else{
                  echo true;
              }
           } else {
               if($callback){
                  echo $callback.'('.false.')';
              }else{
                  echo false;
              }
          }

    }





    /*
     * ajax 检查手机号码是否已经被绑定 @author 施磊
     */
    public function action_checkMobile() {
        if ($this->request->is_ajax ()) {
            $service_user = Service::factory ( "User" );
            $receiver = Arr::get ( $this->request->post (), "mobile" );
            $receiver = trim ( $receiver );
            if (! $receiver)
                $this->jsonEnArr ( '501', '参数错误' );
            if ($service_user->isMobileBinded ( $receiver )) {
                $this->jsonEnArr ( '500', '手机号码已经被绑定' );
            } else {
                $this->jsonEnArr ( '200', 'ok' );
            }
        }
    }
    /**
     * ajax 检查email 完整验证
     *
     * @author 龚湧
     */
    public function action_checkemails() {
        if ($this->request->is_ajax ()) {
            $msg = array ();
            $user = ORM::factory ( "User" );
            $post = $this->request->post ();
            $email = $post ['email'];
            $valid = new Validation ( $post );
            $valid->rule ( 'email', 'not_empty' );
            $valid->rule ( 'email', 'email' );
            if (! $valid->check ()) {
                $error = $valid->errors ( 'user/user' );
                $msg ['error'] = $error ['email'];
            } else {
                if ($user->check_email ( $email )) {
                    $msg ['error'] = '';
                } else {
                    $msg ['error'] = '该邮箱已经被注册';
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * @sso
     * ajax 检查email 是否存在，只验证是否存在
     */
    public function action_checkemail() {
        if ($this->request->is_ajax ()) {
            $msg = array ();
            $client = Service_Sso_Client::instance ();
            $post = $this->request->post ();
            $email = Arr::get ( $post, "email" );
            $valid = new Validation ( $post );
            $valid->rule ( 'email', 'not_empty' );
            // $valid->rule('email', 'email');
            if ($client->isRegNameValid ( $email )) {
                // 邮箱莫注册
                echo 1;
            } else {
                // 邮箱已注册
                echo 0;
            }
        }
    }

    /**
     * @sso
     * ajax 验证密码
     *
     * @author 曹怀栋
     */
    public function action_verifyPassword() {
        $this->isLogin ();
        $id = $this->userId ();
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        $password = Arr::get ( $post, 'password_xg' );
        $client = Service_Sso_Client::instance ();
        echo $client->isPassowrdOk ( $id, $password );
    }

    /**
     * ajax 检查邮件验证的邮件是否发送成功
     *
     * @author 周进
     */
    public function action_checksendvemail() {
        $this->isLogin ();
        $service = new Service_User_Company_User ();
        $result ['status'] = '';
        $result = $service->updateCheckValidEmail ( $this->userInfo ()->user_id, $this->userInfo ()->email );
        switch ($result ['status']) {
            case '1' :
                $msg ['error'] = '';
                break;
            default :
                $msg ['error'] = '操作失败！';
                break;
        }
        echo json_encode ( $msg );
    }
    /**
     * 发送邀请邮箱
     *
     * @author 施磊
     */
    public function action_sendInviterEmail() {
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        $this->isLogin ();
        if (! arr::get ( $post, 'email', 0 ))
            $this->jsonEnArr ( 500, '邮箱不能为空' );
        $user_id = $this->userInfo ()->user_id;
        $email = $this->userInfo ()->email;
        $service = new Service_User ();
        $content = arr::get ( $post, 'content', 0 );
        $msg = $service->userInvitereEmail ( $user_id, $email, arr::get ( $post, 'email', 0 ), $content );
        if (arr::get ( $msg, 'status' )) {
            $this->jsonEnArr ( 200, 'ok' );
        } else {
            $this->jsonEnArr ( 500, '发送失败' );
        }
    }
    /**
     * ajax 检查邮件验证的邮件重发（未登陆状态下）邮箱验证地址过期重发的情况
     *
     * @author 周进
     */
    public function action_checkfailsendvemail() {
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        $service = new Service_User_Company_User ();
        $result ['status'] = '';
        $result = $service->updateCheckValidEmail ( Arr::get ( $post, 'user_id' ), Arr::get ( $post, 'email' ) );
        switch ($result ['status']) {
            case '1' :
                $msg ['error'] = '';
                break;
            default :
                $msg ['error'] = '操作失败！';
                break;
        }
        echo json_encode ( $msg );
    }

    /**
     * 给个人递出名片[递送名片前核查]
     *
     * @author 钟涛
     */
    public function action_checkaddoutcard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $postdata = Arr::map ( "HTML::chars", $this->request->post () );
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            $card_service = new Service_Card ();
            $companyresult = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ()->as_array ();
            if ($companyresult ['com_id'] == '' || empty ( $companyresult ['com_logo'] ) || empty ( $companyresult ['com_phone'] ) || empty ( $companyresult ['com_contact'] ) || empty ( $companyresult ['com_adress'] )) {
                $msg ['error'] = '<p><span style="font:15px/30px ' . "微软雅黑" . ';height:30px;display:block;color: #333;">很抱歉，请先完善您的企业基本信息再递送名片。<a target="_Blank" href="' . URL::website ( 'company/member/basic/editCompany/' ) . '?type=1" style="color:#0b73bb;cursor:pointer;">去完善信息</a></span></p>';
                echo json_encode ( $msg );
                exit ();
            }
            // 获取名片发送前 发送次数
            $sendcountinfo = $card_service->getSendCardCountInfo ( $user_id );
            if (isset ( $sendcountinfo ['day_send_count'] ) && $sendcountinfo ['day_send_count'] >= 30 && date ( "Ymd" ) == $sendcountinfo ['last_sent_time']) {
                $msg ['error'] = '很抱歉，您今天递出名片的次数已经满额，您可以收藏此投资者名片，明天再次递出。';
            } else {
                // 判断是否有权限操作当前名片[仅对个人名片设置为对意向投资行业公开的]
                $ispower = $card_service->isHasPowerSeeCard ( $user_id, $postdata ['to_user_id'] );
                if ($ispower == false) {
                    $msg ['error'] = '<p class="errorbox">抱歉，由于对方隐私设置，您没有权限操作这张名片。</p>';
                    echo json_encode ( $msg );
                    exit ();
                }
                $account = new Service_Account ();
                // $account_result = $account->checkCardAccount($user_id,7);
                $account_result = $account->useAccount ( $user_id, 7, 0, '给' . arr::get ( $postdata, 'user_name', '' ) . '递送名片' );
                if ($account_result ['status'] == FALSE) {
                    $account_result ['error'] = 'accout_error';
                    echo json_encode ( $account_result );
                    exit ();
                }
                if ($account_result ['status'] == TRUE) { // 服务已经扣除，直接弹出递出成功框
                                                     // 添加新的递出的名片
                    $result = $card_service->addOutCard ( $user_id, $postdata );
                    $result ['error'] = 'isshowbox';
                    echo json_encode ( $result );
                    exit ();
                }
                $msg ['error'] = '';
                // 弹出提示框 是否确定扣30元递出名片
                $msg ['check_accout'] = true;
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 给个人递出名片
     *
     * @author 钟涛
     */
    public function action_addoutcard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $postdata = $this->request->post ();
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            $card_service = new Service_Card ();
            $companyresult = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ()->as_array ();
            if ($companyresult ['com_id'] == '' || empty ( $companyresult ['com_logo'] ) || empty ( $companyresult ['com_phone'] ) || empty ( $companyresult ['com_contact'] ) || empty ( $companyresult ['com_adress'] )) {
                $msg ['error'] = '<p><span style="font:15px/30px ' . "微软雅黑" . ';height:30px;display:block;color: #333;">很抱歉，请先完善您的企业基本信息再递送名片。</span><a target="_Blank" href="' . URL::website ( 'company/member/basic/editCompany/' ) . '?type=1" style="margin:25px 0 0 140px;display:block; width:110px;height:40px;"><img src="' . URL::webstatic ( "images/platform/search_index/quwanshan2.jpg" ) . '"></a></p>';
                echo json_encode ( $msg );
                exit ();
            }
            // 获取名片发送前 发送次数
            $sendcountinfo = $card_service->getSendCardCountInfo ( $user_id );
            if (isset ( $sendcountinfo ['day_send_count'] ) && $sendcountinfo ['day_send_count'] >= 30 && date ( "Ymd" ) == $sendcountinfo ['last_sent_time']) {
                $msg ['error'] = '很抱歉，您今天递出名片的次数已经满额，您可以收藏此投资者名片，明天再次递出。';
            } else {
                $account = new Service_Account ();
                if (arr::get ( $postdata, 'to_user_id', 0 )) {
                    $perinfo = ORM::factory ( 'Personinfo' )->where ( 'per_user_id', '=', arr::get ( $postdata, 'to_user_id', 0 ) )->find ()->per_realname;
                } else {
                    $perinfo = '投资者';
                }
                $account_result = $account->useAccount ( $user_id, 7, 1, '给' . $perinfo . '递送名片' );
                if ($account_result ['status'] == FALSE) {
                    $account_result ['error'] = 'accout_error';
                    echo json_encode ( $account_result );
                    exit ();
                }
                // 添加新的递出的名片
                $result = $card_service->addOutCard ( $user_id, $postdata );
                switch ($result ['status']) {
                    case '100' :
                        $msg ['count'] = $result ['count'];
                        $msg ['error'] = '';
                        break;
                    default :
                        $msg ['count'] = '';
                        $msg ['error'] = '<img src="' . URL::webstatic ( '/images/search_tz/send_fail.png' ) . '" class="floleft" /> <p class="fail">很抱歉，递出名片失败，请重新递出您的企业名片！</p>';
                        break;
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 重复递出名片[递出名片前核查]
     *
     * @author 钟涛
     */
    public function action_checkaddRepeatSendCard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = Arr::map ( "HTML::chars", $this->request->post () );
            $user_id = $this->userInfo ()->user_id;
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            $card_service = new Service_Card ();
            // 获取名片发送前 发送次数
            $sendcountinfo = $card_service->getSendCardCountInfo ( $user_id );
            $msg ['error'] = '';
            if (isset ( $sendcountinfo ['day_send_count'] ) && $sendcountinfo ['day_send_count'] >= 30 && date ( "Ymd" ) == $sendcountinfo ['last_sent_time']) {
                $msg ['error'] = '很抱歉，您今天递出名片的次数已经满额，您可以收藏此投资者名片，明天再次递出。';
            } else {
                if ($this->userInfo ()->user_type == 1) {
                    // 判断是否有权限操作当前名片[仅对个人名片设置为对意向投资行业公开的]
                    $ispower = $card_service->isHasPowerSeeCard ( $user_id, $postdata ['to_user_id'] );
                    if ($ispower == false) {
                        $msg ['error'] = '<p class="errorbox">抱歉，由于对方隐私设置，您没有权限操作这张名片。</p>';
                        echo json_encode ( $msg );
                        exit ();
                    }
                    $account = new Service_Account ();
                    // $account_result = $account->checkCardAccount($user_id,7);
                    $account_result = $account->useAccount ( $user_id, 7, 0, '给' . arr::get ( $postdata, 'user_name', '' ) . '重复递送名片' );
                    if ($account_result ['status'] == FALSE) {
                        $account_result ['error'] = 'accout_error';
                        echo json_encode ( $account_result );
                        exit ();
                    }
                    if ($account_result ['status'] == TRUE) { // 服务已经扣除，直接弹出递出成功框
                                                         // 修改递出的名片次数
                        $result = $card_service->addRepeatSendCard ( $postdata ['cardid'], $user_id, $postdata ); // @赵路生
                        $result ['error'] = 'isshowbox';
                        echo json_encode ( $result );
                        exit ();
                    }
                }
                // $msg['error']='';
                // //弹出提示框 是否确定扣30元递出名片
                // $msg['check_accout']=true;
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 重复递出名片
     *
     * @author 钟涛
     */
    public function action_addRepeatSendCard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            $user_id = $this->userInfo ()->user_id;
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            if ($this->userInfo ()->user_type == 1) {
                $account = new Service_Account ();
                $account_result = $account->useAccount ( $user_id, 7, 1, '给' . arr::get ( $postdata, 'username', '' ) . '重复递送名片' );
                if ($account_result ['status'] == FALSE) {
                    $account_result ['error'] = 'accout_error';
                    echo json_encode ( $account_result );
                    exit ();
                }
            }
            $card_service = new Service_Card ();
            if ($postdata ['cardid']) {
                // 修改递出的名片次数
                $result = $card_service->addRepeatSendCard ( $postdata ['cardid'], $user_id, $postdata ); // @赵路生
                switch ($result ['status']) {
                    case '100' :
                        $msg ['count'] = $result ['count'];
                        $msg ['error'] = '';
                        break;
                    default :
                        $msg ['error'] = '<img src="' . URL::webstatic ( '/images/search_tz/send_fail.png' ) . '" class="floleft" /> <p class="fail">很抱歉，重复递出名片失败，请重新递出您的企业名片！</p>';
                        break;
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 与个人交换名片
     *
     * @author 钟涛
     */
    public function action_checkupdatereceivecard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $service = new Service_User_Company_Card ();
            $account = new Service_Account ();
            $postdata = $this->request->post ();
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            $account_result = $account->useAccount ( $user_id, 8, 0, '与' . arr::get ( $postdata, 'user_name', '' ) . '交换名片' );
            if ($account_result ['status'] == FALSE) {
                $account_result ['error'] = 'accout_error';
                $account_result ['message'] = $account_result ['message'];
                echo json_encode ( $account_result );
                exit ();
            }
            if ($account_result ['status'] == TRUE) { // 服务已经扣除，直接弹出交换成功框
                                                 // 更新记录名片为已交换
                $result = $service->editReceiveCard ( $postdata, $user_id );
                $result ['error'] = 'isshowbox';
                echo json_encode ( $result );
                exit ();
            }
            $msg ['error'] = '';
            // 弹出提示框 是否确定扣30元交换名片
            $msg ['check_accout'] = true;
            echo json_encode ( $msg );
        }
    }

    /**
     * 与个人交换名片
     *
     * @author 钟涛
     */
    public function action_updatereceivecard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $postdata ['user_type'] = $this->userInfo ()->user_type; // @赵路生
            $user_name = $this->userInfo ()->user_name;
            if ($postdata ['cardid']) { // 名片交换记录id
                $service = new Service_User_Company_Card ();
                $account = new Service_Account ();
                // $account_result = $account->manageAccount($user_id,8);
                $account_result = $account->useAccount ( $user_id, 8, 1, '与' . arr::get ( $postdata, 'user_name', '' ) . '交换名片' );
                if ($account_result ['status'] == FALSE) {
                    $account_result ['error'] = 'accout_error';
                    echo json_encode ( $account_result );
                    exit ();
                }
                // 更新记录名片为已交换
                $result = $service->editReceiveCard ( $postdata, $user_id );
                switch ($result ['status']) {
                    case '100' :
                        $msg ['error'] = '';
                        $msg ['user_name'] = $user_name;
                        break;
                    default :
                        $msg ['error'] = '<img src="/images/getcards/change_fail.png" class="floleft" /> <p class="fail">很抱歉，您交换名片失败，请重新交换！</p>!';
                        break;
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 与企业交换名片
     *
     * @author 钟涛
     */
    public function action_updatereceivecardcom() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            if ($postdata ['cardid']) { // 名片交换记录id
                $service = new Service_User_Company_Card ();
                // 更新记录名片为已交换
                $result = $service->editReceiveCardCom ( $postdata, $user_id );
                switch ($result ['status']) {
                    case '100' :
                        $msg ['error'] = '';
                        break;
                    default :
                        $msg ['error'] = '很抱歉，您交换名片失败，请重新交换！';
                        break;
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 获取留言内容
     *
     * @author 钟涛
     */
    public function action_getUserLetter() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = Arr::map ( "HTML::chars", $this->request->post () );
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $msg ['info'] = '';
            if ($postdata ['userid']) { // 名片交换记录id
                $service = new Service_User_Company_Card ();
                // 更新记录名片为已交换
                $result = $service->getUserLetter ( $postdata ['userid'], $user_id );
                if (count ( $result )) {
                    foreach ( $result as $v ) {
                        $msg ['info'] .= '<li><font>' . date ( "Y-m-j H:i", $v->add_time ) . '</font><span>留言如下：<br/>' . $v->content . '</span></li>';
                    }
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 批量交换我收到的名片
     *
     * @author 钟涛
     */
    public function action_batchUpdateReceiveCard() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            $cardidarr = array ();
            $msg = array ();
            if ($postdata ['cardidarr']) { // 获取选择的名片id
                $cardidarr = explode ( ",", $postdata ['cardidarr'] );
                $sendcount = count ( $cardidarr );
                // 获取登录user_id
                $user_id = $this->userInfo ()->user_id;
                $service = new Service_User_Company_Card ();
                // 未交换名片数量
                if ($service->getBatchReceiveCardCount ( $cardidarr )) { // 存在未交换名片的数量
                                                                    // 批量更新记录名片为已交换
                    $service->editBatchReceiveCard ( $cardidarr, $user_id );
                    $msg ['content'] = '您成功向所选中的投资者交换了您的企业名片，一句话预祝你们沟通顺畅，合作愉快~!';
                } else { // 选择的名片全部是已经交换的名片
                    $msg ['content'] = '您已经向所选中的投资者交换了您的名片，无需再次交换。';
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 查看联系方式[第二步点击查看联系方式触发事件]
     *
     * @author 钟涛
     */
    public function action_viewPersonInfo() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            $account = new Service_Account ();
            if ($postdata ['userid']) { // 获取投资者用户ID
                $postdata ['userid'] = intval ( $postdata ['userid'] );
                $service = new Service_User_Company_Card ();
                $card_service = new Service_Card ();
                // 是否已经给过钱查看过该同学或者已经[当天]免费查看过该同学(注意仅限当天)
                $is_pay = $card_service->getCardinfoCountByidNew ( $this->userId (), $postdata ['userid'] );
                // 判断该同学是否已经主动给我递送名片[包含交换]
                $is_send_me = $card_service->getIsHasbehaviour ( $this->userId (), $postdata ['userid'] );
                if ($is_pay == 0) { // 没有查看过该用户
                    if (! $is_send_me) { // 个人没有主动给我递送名片
                        $result_log = $card_service->getViewCardInfo ( $postdata ['userid'] );
                        $arr = array ();
                        if ($result_log ['id'] != '') {
                            $arr = explode ( '|', $result_log ['view_notes'] );
                        }
                        $oldtime = $result_log ['first_add_time'] + (30 * 24 * 60 * 60);
                        // 没有查看过此名片+查看人数已经为5个+第一查看的还没有超过30天
                        if (! in_array ( $this->userId (), $arr ) && count ( $arr ) > 4 && $oldtime > time ()) {
                            $result ['error'] = '<p>非常抱歉，该投资者名片的查看名额暂时已满，您可在' . date ( "Y-m-j H:i", $oldtime ) . '后再查看，建议您直接向该投资者递出名片!</p>';
                            echo json_encode ( $result );
                            exit ();
                        }
                        // 判断是否有权限操作当前名片[仅对个人名片设置为对意向投资行业公开的]
                        $ispower = $card_service->isHasPowerSeeCard ( $this->userId (), $postdata ['userid'] );
                        if ($ispower == false) {
                            $result ['error'] = '<p class="errorbox">抱歉，由于对方隐私设置，您没有权限操作这张名片。</p>';
                            echo json_encode ( $result );
                            exit ();
                        }
                    } else { // 个人已经主动给我递送名片【每天普通用户免费查看1次，招商通会员用户不能免费查看】
                        $company = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $this->userId () )->find ();
                        $freecout = $card_service->getFreeViewCardLog ( $this->userId () );
                        if ($freecout == 0 && $company->platform_service_fee_status == 0) { // 没有查看且不是招商通会员
                            $result = $service->getPersonCont ( $postdata ['userid'], 2 );
                            // 更新免费查看名片表记录
                            $service->updateFreeViewCardLog ( $this->userId (), $postdata ['userid'] );
                            $result ['error'] = 'isshowconnect';
                            $result ['check_accout'] = false;
                            echo json_encode ( $result );
                            exit ();
                        }
                        if ($freecout > 0 && $company->platform_service_fee_status == 0) { // 已经免费查看过了
                            $result ['error'] = '<p class="errorbox">您的免费查看名片次数已用完，如果想继续查看名片您可申请招商通会员并充值（详情请查阅资费说明，或咨询400 1015 908)</p><a href="/company/member/account/applyPlatformServiceFee" target="_blank">申请招商通会员</a>';
                            echo json_encode ( $result );
                            exit ();
                        }
                    }
                    // $account_result = $account->checkCardAccount($this->userId(),6);
                    $per = ORM::factory ( "Personinfo" )->where ( 'per_user_id', '=', $postdata ['userid'] )->find ();
                    if (! $is_send_me) { // 个人没有主动给我递送名片
                        $paytype = 6; // 支付15元
                    } else {
                        $paytype = 5; // 支付80元
                    }
                    $account_result = $account->useAccount ( $this->userId (), $paytype, 0, '查看' . $per->per_realname . '名片' );
                    if ($account_result ['status'] == FALSE) {
                        $account_result ['error'] = 'accout_error';
                        echo json_encode ( $account_result );
                        exit ();
                    }
                    if ($account_result ['status'] == TRUE) { // 服务已经扣除，直接弹出查看名片框
                        if (! $is_send_me) { // 个人没有主动给我递送名片
                            if (! in_array ( $this->userId (), $arr ) || $oldtime < time ()) {
                                $card_service->updateViewCardLog ( $postdata ['userid'], $this->userId () );
                            }
                        }
                        $result = $service->getPersonCont ( $postdata ['userid'] );
                        $result ['error'] = 'isshowconnect';
                        $result ['check_accout'] = false;
                        echo json_encode ( $result );
                        exit ();
                    }
                    $result ['error'] = '';
                    // 弹出提示框 是否确定扣100元查看名片
                    $result ['check_accout'] = true;
                } else { // 已经付过钱查看过该用户，直接查看
                    $isfree = $service->getCardinfoCountByidNew2 ( $this->userId (), $postdata ['userid'] );
                    $type_t = 1;
                    if ($isfree) {
                        $type_t = 2;
                    }
                    $result = $service->getPersonCont ( $postdata ['userid'], $type_t );
                    $result ['error'] = 'isshowconnect';
                    $result ['check_accout'] = false;
                }
            }
            echo json_encode ( $result );
        }
    }

    /**
     * 查看投资者名片[第一步-不显示手机号和邮箱其他都显示]
     *
     * @author 钟涛
     */
    public function action_checkReceivecardByID() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            $account = new Service_Account ();
            if ($postdata ['userid']) { // 获取投资者用户ID
                $postdata ['userid'] = intval ( $postdata ['userid'] );
                $service = new Service_User_Company_Card ();
                $card_service = new Service_Card ();
                // 是否已经给过钱查看过该同学
                $is_pay = $card_service->getCardinfoCountByid ( $this->userId (), $postdata ['userid'] );
                // 判断该同学是否已经主动给我递送名片[包含交换]
                $is_send_me = $card_service->getIsHasbehaviour ( $this->userId (), $postdata ['userid'] );
                if ($is_pay == 0) { // 没有查看过该用户
                    $result = $service->getReceivecardByID ( $postdata ['userid'], $postdata ['cardid'], $postdata ['type'], 1 );
                    $result ['ispay'] = true;
                    // if(!$is_send_me){//个人没有主动给我递送名片
                    // $result_log=$card_service->getViewCardInfo($postdata['userid']);
                    // $arr=array();
                    // if($result_log['id']!=''){
                    // $arr= explode('|', $result_log['view_notes']);
                    // }
                    // $oldtime=$result_log['first_add_time'] + (30 * 24 * 60 * 60);
                    // 没有查看过此名片+查看人数已经为5个+第一查看的还没有超过30天
                    // if(!in_array($this->userId(), $arr) && count($arr)>4 && $oldtime>time()){
                    // $result['error']='<p>非常抱歉，该投资者名片的查看名额暂时已满，您可在'.date("Y-m-j H:i",$oldtime).'后再查看，建议您直接向该投资者递出名片!</p>';
                    // echo json_encode($result);
                    // exit;
                    // }
                    // 判断是否有权限操作当前名片[仅对个人名片设置为对意向投资行业公开的]
                    // $ispower=$card_service->isHasPowerSeeCard($this->userId(),$postdata['userid']);
                    // if($ispower==false){
                    // $result['error']='<p class="errorbox">抱歉，由于对方隐私设置，您没有权限操作这张名片。</p>';
                    // echo json_encode($result);
                    // exit;
                    // }
                    // }
                    // $account_result = $account->checkCardAccount($this->userId(),6);
                    // $per= ORM::factory("Personinfo")->where('per_user_id','=',$postdata['userid'])->find();
                    // $account_result=$account->useAccount($this->userId(),6,0,'查看'.$per->per_realname.'名片');
                    // if($account_result['status']==FALSE){//弹出框【隐藏手机号码和邮箱】
                    // $result=$service->getReceivecardByID($postdata['userid'],$postdata['cardid'],$postdata['type'],1);
                    // $result['error'] = 'isshowbox';
                    // $result['check_accout']=false;
                    // echo json_encode($result);
                    // exit;
                    // $account_result['error']='accout_error';
                    // echo json_encode($account_result);
                    // exit;
                    // }
                    // if($account_result['status']==TRUE){//服务已经扣除，直接弹出查看名片框
                    // if(!$is_send_me){//个人没有主动给我递送名片
                    // if(!in_array($this->userId(), $arr) || $oldtime<time()){
                    // $card_service->updateViewCardLog($postdata['userid'],$this->userId());
                    // }
                    // }
                    // $result=$service->getReceivecardByID($postdata['userid'],$postdata['cardid'],$postdata['type'],2);
                    // $result['error'] = 'isshowbox';
                    // $result['check_accout']=false;
                    // echo json_encode($result);
                    // exit;
                    // }
                    // $result['error']='';
                    // 弹出提示框 是否确定扣100元查看名片
                    // $result['check_accout']=true;
                } else { // 已经付过钱查看过该用户，直接查看
                    $result = $service->getReceivecardByID ( $postdata ['userid'], $postdata ['cardid'], $postdata ['type'], 3 );
                    $result ['ispay'] = false;
                }
            }
            echo json_encode ( $result );
        }
    }

    /**
     * 查看投资者名片[第三步：点击直接扣除触发事件]
     *
     * @author 钟涛
     */
    public function action_getReceivecardByID() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            $postdata = $this->request->post ();
            $account = new Service_Account ();
            if ($postdata ['userid']) { // 获取投资者用户ID
                $service = new Service_User_Company_Card ();
                $card_service = new Service_Card ();
                // 是否已经给过钱查看过该同学
                $is_pay = $card_service->getCardinfoCountByid ( $this->userId (), $postdata ['userid'] );
                // 判断该同学是否已经主动给我递送名片[包含交换]
                $is_send_me = $card_service->getIsHasbehaviour ( $this->userId (), $postdata ['userid'] );
                if ($is_pay == 0) { // 没有查看过该用户
                    if (! $is_send_me) { // 个人没有主动给我递送名片
                        $result_log = $card_service->getViewCardInfo ( $postdata ['userid'] );
                        $arr = array ();
                        if ($result_log ['id'] != '') {
                            $arr = explode ( '|', $result_log ['view_notes'] );
                        }
                        $oldtime = $result_log ['first_add_time'] + (30 * 24 * 60 * 60);
                        // 没有查看过此名片+查看人数已经为5个+第一查看的还没有超过30天
                        if (! in_array ( $this->userId (), $arr ) && count ( $arr ) > 4 && $oldtime > time ()) {
                            $result ['error'] = '<p >非常抱歉，该投资者名片的查看名额暂时已满，您可在' . date ( "Y-m-j H:i", $oldtime ) . '后再查看，建议您直接向该投资者递出名片!</p>';
                            echo json_encode ( $result );
                            exit ();
                        }
                        // 判断是否有权限操作当前名片[仅对个人名片设置为对意向投资行业公开的]
                        $ispower = $card_service->isHasPowerSeeCard ( $this->userId (), $postdata ['userid'] );
                        if ($ispower == false) {
                            $result ['error'] = '<p class="errorbox">抱歉，由于对方隐私设置，您没有权限操作这张名片。</p>';
                            echo json_encode ( $result );
                            exit ();
                        }
                    }
                    $per = ORM::factory ( "Personinfo" )->where ( 'per_user_id', '=', $postdata ['userid'] )->find ();
                    if (! $is_send_me) { // 个人没有主动给我递送名片
                        $paytype = 6; // 支付15元
                        $paycontent = '查看' . $per->per_realname . '名片';
                    } else {
                        $paytype = 5; // 支付80元
                        $paycontent = '查看' . $per->per_realname . '主动递送的名片';
                    }
                    $account_result = $account->useAccount ( $this->userId (), $paytype, 1, $paycontent );
                    if ($account_result ['status'] == FALSE) {
                        $account_result ['error'] = 'accout_error';
                        echo json_encode ( $account_result );
                        exit ();
                    }
                    if (! $is_send_me) { // 个人没有主动给我递送名片
                        if (! in_array ( $this->userId (), $arr ) || $oldtime < time ()) {
                            $card_service->updateViewCardLog ( $postdata ['userid'], $this->userId () );
                        }
                    }
                }
                $result = $service->getPersonCont ( $postdata ['userid'] );
                $result ['error'] = '';
            }
            echo json_encode ( $result );
        }
    }

    /**
     * 批量删除投资者名片
     *
     * @author 钟涛
     */
    public function action_batchDeleteCard() {
        if ($this->request->is_ajax ()) {
            $postdata = $this->request->post ();
            $cardidarr = array ();
            $card_status = $postdata ['status']; // 1：收到的名片,2：递出的名片
            $msg = array ();
            if ($postdata ['cardidarr']) { // 获取选择的名片id
                $service = new Service_User_Company_Card ();
                $cardidarr = explode ( ",", $postdata ['cardidarr'] );
                // 未读名片数量
                if ($card_status == 1) { // 收到的名片
                    if ($service->getBatchReceiveReadCardCount ( $cardidarr )) { // 存在未读的名片
                        $msg ['status'] = 1;
                    } else { // 选择的名片全部是已读的名片
                        $msg ['status'] = 0;
                    }
                } elseif ($card_status == 2) { // 递出的名片
                    if ($service->getBatchOutReadCardCount ( $cardidarr )) { // 存在未读的名片
                        $msg ['status'] = 1;
                    } else { // 选择的名片全部是已读的名片
                        $msg ['status'] = 0;
                    }
                } else {
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 查看投资者从业经验
     *
     * @author 钟涛
     */
    public function action_getExperienceById() {
        if ($this->request->is_ajax ()) {
            $postdata = $this->request->post ();
            if ($postdata) { // 获取投资者用户ID
                $service = new Service_User_Company_User ();
                $result = $service->getExperienceById ( $postdata ['userid'] );
                if (count ( $result )) { // 更新名片为已读
                    $card_service = new Service_User_Company_Card ();
                    if ($postdata ['cardid']) {
                        if ($postdata ['type'] == 1) { // 我收到的名片
                            $card_service->updateReceCardReadStatus ( $postdata ['cardid'] );
                        } elseif ($postdata ['type'] == 2) { // 我递出的名片
                            $card_service->updateOutCardReadStatus ( $postdata ['cardid'] );
                        } else {
                        }
                    }
                }
            }
            echo json_encode ( $result );
        }
    }

    /**
     * 查看招商者名片
     *
     * @author 钟涛
     */
    public function action_getPersonBusinessCard() {
        if ($this->request->is_ajax ()) {
            $postdata = $this->request->post ();
            $serviceCode = new Service_User_Person_Card ();
            if ($postdata ['userid']) { // 获取投资者用户ID
                $service = new Service_User_Person_Card ();
                $result = $service->getPersonBusinessCard ( $postdata ['userid'], $postdata ['cardid'], $postdata ['type'] );
                // 处理公司名片电话
                $result ['com_phone'] = $serviceCode->checkComPhone ( $result ['com_phone'] );
            }
            echo json_encode ( $result );
        }
    }

    /**
     * 修改招商项目是否在企业主页中显示
     *
     * @author 潘宗磊
     */
    public function action_editProjectDisplay() {
        $gets = Arr::map ( "HTML::chars", $this->request->post () );
        $service = new Service_User_Company_Project ();
        // 读取当前id的数据
        $result = $service->updateProjectDisplay ( $gets );
        echo json_encode ( $result );
    }

    /**
     * 修改项目资质认证名称
     *
     * @author 潘宗磊
     */
    public function action_editProjectCerts() {
        $gets = Arr::map ( "HTML::chars", $this->request->post () );
        $service = new Service_User_Company_User ();
        // 读取当前id的数据
        $result = $service->editProjectCertName ( $gets );
        echo json_encode ( $result );
    }

    /**
     * 取得所属行业
     *
     * @author 曹怀栋
     *         调用方法如下(id表示读取某一个城市的信息,parent_id表示读取某个省中所有城市的信息)：
     *         arrArea(array('id'=>'','parent_id'=>1));
     */
    public function action_getarrArea() {
        $post = $this->request->post ();
        $result = common::arrArea ( $post );
        echo json_encode ( $result );
    }

    /**
     * 手机发送验证码
     *
     * @author 龚湧
     */
    public function action_sendMessage() {
        $this->isLogin ();
        if ($this->request->is_ajax ()) {
            // TODO 判断手机号码是否已经被绑定过
            $msg = array ();
            $receiver = Arr::get ( $this->request->post (), "receiver" );
            $service_user = Service::factory ( "User" );
            if ($service_user->isMobileBinded ( $receiver )) {
                $msg ['error'] = true;
                $msg ['msg'] = "手机号码已经被绑定";
            } else {
                $user_id = $this->userId ();
                $type = 2; // 发送手机验证码

                // 最后一次验证码发送时间间隔，防变态
                $space_time = time () - $service_user->getValidCode ( $user_id, 2 );
                $last_time = Kohana::$config->load ( "message.space_time.mobile" ) - $space_time;
                if ($last_time > 0) {
                    // 发送失败
                    $msg ['error'] = true;
                    $msg ['msg'] = "发送太频繁了,先喝口茶吧"; // "发送太频繁了,先喝口茶吧";
                    echo json_encode ( $msg );
                    exit ();
                }

                $cty = $this->request->post("type");//获取发送类型

                if ($service_user->sendValidCode ( $receiver, $user_id, $type ,$cty)) {
                    /* ----线下测试代码----- */
                    // $code = substr($service_user->getValidCode($user_id,2),-6);
                    /* ---------- */
                    // 发送成功
                    $msg ['error'] = false;
                    // $msg['msg'] = "发送成功(验证码为:{$code})";//线下测试代码
                    // $msg['msg'] = "发送成功";
                    $msg ['msg'] = "";
                    // 防止手机号码篡改
                    Cookie::set ( "valid_mb", md5 ( $receiver . Kohana::$config->load ( "message.space_time.mobile" ) ), Kohana::$config->load ( "message.expire.mobile" ) );
                } else {
                    // 发送失败
                    $msg ['error'] = true;
                    $msg ['msg'] = "发送失败";
                    // $msg['msg'] = "";
                }
            }
            echo json_encode ( $msg );
        }
    }

    /**
     * 检查用户名是否有效
     *
     * @author 龚湧
     */
    public function action_checkLogin() {
        if ($this->request->is_ajax ()) {
            $login_name = $this->request->post ( "email" );
            $service = Service::factory ( "User" );
            if ($service->checkLoginName ( $login_name )) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /**
     * 名片加入收藏(支持单个和批量)
     *
     * @author 周进
     */
    public function action_addFavorite() {
        $this->isLogin ();
        $post = $this->request->post ();
        $data = $post;
        $datacardid = $post;
        $type = 1;
        if ($post ['ids']) { // 获取选择的名片id批量或单个
            if (strlen ( strpos ( $post ['ids'], ',' ) )) {
                $data = explode ( ",", $post ['ids'] );
                $datacardid = explode ( ",", $post ['cardid'] );
                $type = 2;
            } else {
                $data = $data ['ids'];
                $datacardid = $datacardid ['cardid'];
            }
        }
        $service = new Service_Card ();
        $result = $service->addFavorite ( $this->userId (), $type, $data, $datacardid, $post ['cardtype'] );
        if ($result ['status'] == TRUE) {
            $msg ['status'] = 1;
            $msg ['content'] = '加入收藏成功！';
        } else {
            $msg ['status'] = 0;
            $msg ['content'] = '加入收藏失败！';
            if ($type == 2 && $result ['success'] != 0) {
                $msg ['status'] = 0;
                $msg ['content'] = '加入失败,其中' . implode ( ",", $result ['success'] ) . "添加成功！" . implode ( ",", $result ['error'] ) . "添加失败！";
            }
        }
        echo json_encode ( $msg );
    }

    /**
     * 认领项目时验证手机验证码
     *
     * @author 钟涛
     */
    public function action_sendMessageByRenling() {
        $msg = array ();
        $service_user = Service::factory ( "User" );
        $user = $this->userInfo ();
        // 判断手机号码是否为绑定的号码
        $receiver = Arr::get ( $this->request->post (), "receiver" );
        $error = '';
        if ($service_user->isMobileBinded ( $receiver ) && $user->mobile != $receiver) {
            $error = "{$receiver}已经被绑定";
        } 		// 验证验证码 绑定号码和更新状态
        else {
            $check_code = Arr::get ( $this->request->post (), "check_code" );
            if ($service_user->bindMobile ( $user->user_id, $receiver, $check_code )) {
                // 手机绑定通过，增加积分
                $points = Service::factory ( "User_Company_Points" );
                $points->getPointsTimes ( $user->user_id, "valid_mobile" );
                // 手机绑定成功，增加诚信点
                $integrity = Service::factory ( "User_Company_Integrity" );
                if ($integrity->getIntegrityOnce ( $user->user_id, "valid_mobile" )) {
                    // 手机号码通过验证，发送消息提醒
                    // $msg_service = new Service_User_Ucmsg();
                    // $msg_service->pushMsg($user->user_id, "company_integrity", "您已经验证手机号码，增加60点诚信指数。",URL::website("company/member/basic/integrity"));

                    $smsg = Smsg::instance ();
                    // 内部消息发送
                    $smsg->register ( "tip_company_integrity", 					// 我的诚信
                    Smsg::TIP, 					// 类型
                    array (
                            "to_user_id" => $user->user_id,
                            "msg_type_name" => "company_integrity",
                            "to_url" => URL::website ( "company/member/basic/integrity" )
                    ), array (
                            "code" => "60",
                            "type" => "tel"
                    )
                     )

                    ;
                }
                $error = '';
            } else {
                $error = "验证码错误";
            }
        }
        $msg ['error'] = $error;
        echo json_encode ( $msg );
    }

    /**
     * 名片取消收藏单个
     *
     * @author 周进
     */
    public function action_updateFavorite() {
        $this->isLogin ();
        $post = $this->request->post ();
        $data = $post;
        $service = new Service_Card ();
        $result = $service->updateFavorite ( $this->userId (), 1, $post ['favorite_id'] );
        if ($result ['status'] == TRUE) {
            $msg ['status'] = 1;
            $msg ['content'] = '取消收藏成功！';
        } else {
            $msg ['status'] = 0;
            $msg ['content'] = '取消收藏失败！';
        }
        if ($service->getFavoriteNums ( $this->userId () ) == 0 && isset ( $post ['from'] ) && $post ['from'] == 1)
            $msg ['url'] = "/person/member/card/favorite";
        elseif ($service->getFavoriteNums ( $this->userId () ) == 0)
            $msg ['url'] = "/company/member/card/favorite";
        echo json_encode ( $msg );
    }
    /**
     * 名片取消收藏批量
     *
     * @author 周进
     */
    public function action_delFavorite() {
        $this->isLogin ();
        $post = $this->request->query ();
        $data = $post;
        $datacardid = $post;
        $type = 1;
        if ($post ['ids']) { // 获取选择的名片id批量或单个
            if (strlen ( strpos ( $post ['ids'], ',' ) )) {
                $data = explode ( ",", $post ['ids'] );
                $type = 2;
            } else {
                $data = $data ['ids'];
            }
        }
        $service = new Service_Card ();
        $result = $service->updateFavorite ( $this->userId (), $type, $data );
        if ($result ['status'] == TRUE) {
            $msg ['status'] = 1;
            $msg ['content'] = '取消收藏成功！';
        } else {
            $msg ['status'] = 0;
            $msg ['content'] = '取消收藏失败！';
            if ($type == 2 && $result ['success'] != 0) {
                $msg ['status'] = 0;
                $msg ['content'] = '取消失败,其中' . implode ( ",", $result ['success'] ) . "取消成功！" . implode ( ",", $result ['error'] ) . "取消失败！";
            }
        }
        if ($post ['from'] == 1)
            self::redirect ( "/company/member/card/favorite" );
        else
            self::redirect ( "/person/member/card/favorite" );
    }
    /**
     * ajax 项目资质认证信息（图片）
     *
     * @author 曹怀栋
     */
    public function action_getProjectCerts() {
        $serviceUser = new Service_User_Company_User ();
        $comUser = $serviceUser->getCompanyInfo ( $this->userId () );
        $service = new Service_User_Company_Project ();
        // 取得项目资质认证信息（图片）
        $msg = $service->getProjectCerts ( $comUser->com_id, 1 );
        echo json_encode ( $msg );
    }

    /**
     * 返回ajax状态
     *
     * @author 施磊
     * @param int $code
     *        	状态码
     * @param
     *        	string or array $msg 提示信息
     * @param int $type
     *        	0 为 直接echo 1 是return
     * @return json
     */
    private function jsonEnArr($code, $msg, $type = 0) {
        $arr = array (
                'code' => $code,
                'msg' => $msg,
                'date' => time ()
        );
        $return = json_encode ( $arr );
        if ($type) {
            return $return;
        } else {
            echo $return;
            exit ();
        }
    }

    /**
     * 更新企业顶部信息提醒状态数量
     *
     * @author 龚湧
     */
    public function action_updateCompanyMsg() {
        if ($this->isLogins ()) {
            $result = array ();
            $ucmsg = Service::factory ( "User_Ucmsg" );
            // 生成新消息
            // $ucmsg->generateMsg($this->loginUserId(),$this->userInfo()->last_logintime,$this->userInfo()->user_type);//更新和创建消息
            // 未读消息总数
            $msg_total_count = $ucmsg->getMsgCount ( $this->userId () );
            $result ['msg_total_count'] = $msg_total_count ? $msg_total_count : 0;
            // 未读消息分类数量数组,更新顶部提示，各类消息
            $msg_tips = $ucmsg->updateCompanyMsgTips ( $this->userId () );
            $result ['sort_count'] = $msg_tips ['sort_count'];
            $result ['msg_show_flag'] = $msg_tips ['msg_show_flag'];
            echo json_encode ( $result );
        }
    }

    /**
     * 更新个人顶部信息提醒状态数量
     *
     * @author 龚湧
     */
    public function action_updatePersonMsg() {
        if ($this->isLogins ()) {
            $result = array ();
            $ucmsg = Service::factory ( "User_Ucmsg" );
            // 生成新消息
            // $ucmsg->generateMsg($this->loginUserId(),$this->userInfo()->last_logintime,$this->userInfo()->user_type);//更新和创建消息

            // 未读消息总数
            $msg_total_count = $ucmsg->getMsgCount ( $this->userId () );
            $result ['msg_total_count'] = $msg_total_count ? $msg_total_count : 0;
            // 未读消息分类数量数组,更新顶部提示，各类消息
            $msg_tips = $ucmsg->updatePersonMsgTips ( $this->userId () );

            $result ['sort_count'] = $msg_tips ['sort_count'];
            $result ['msg_show_flag'] = $msg_tips ['msg_show_flag'];
            echo json_encode ( $result );
        }
    }

    /**
     * 关闭提醒功能
     *
     * @author 龚湧
     */
    public function action_closeTips() {
        if ($this->isLogins ()) {
            $ucmsg = Service::factory ( "User_Ucmsg" );
            $ucmsg->closeTips ();
        }
    }

    /**
     * 读取指定行业的二级信息
     *
     * @author 潘宗磊
     */
    public function action_getIndustry() {
        if ($this->request->is_ajax ()) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            $msg = array ();
            if (isset ( $post ['parent_id'] ) && is_numeric ( $post ['parent_id'] )) {
                $msgs = common::primaryIndustry ( $post ['parent_id'] );
                foreach ( $msgs as $k => $v ) {
                    $msg [$k] ['industry_id'] = $v->industry_id;
                    $msg [$k] ['industry_name'] = $v->industry_name;
                }
                echo json_encode ( $msg );
            } else {
                echo json_encode ( $msg );
            }
        }
    }
    /**
     * 项目一级行业
     *
     * @author 曹怀栋
     */
    public function action_primaryFirstIndustry($parent_id, $industry_id = 0) {
        $model = ORM::factory ( "industry" );
        $arr_return_data = array ();
        if ($industry_id == 0) {
            $rtn = $model->where ( 'parent_id', '=', $parent_id )->order_by ( 'industry_order', 'DESC' )->find_all ();
        } else {
            $rtn = $model->where ( 'parent_id', '=', $parent_id )->and_where ( 'industry_id', '=', $industry_id )->order_by ( 'industry_order', 'DESC' )->find_all ();
        }
        foreach ( $rtn as $key => $val ) {
            $arr_return_data [] = $val->as_array ();
        }
        return json_encode ( $arr_return_data );
    }
    /**
     * 读取地区列表
     *
     * @author 潘宗磊
     */
    public function action_getArea() {
        if ($this->request->is_ajax ()) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            $msg = array ();
            if (isset ( $post ['parent_id'] ) && is_numeric ( $post ['parent_id'] )) {
                $areas = ORM::factory ( "City" )->where ( "pro_id", "=", $post ['parent_id'] )->find_all ();
                foreach ( $areas as $k => $v ) {
                    $msg [$k] ['cit_id'] = $v->cit_id;
                    $msg [$k] ['cit_name'] = $v->cit_name;
                }
            }
            echo json_encode ( $msg );
        }
    }
    /**
     * 支付返回结果验签方法
     * 支付返回结果验签方法(同步)
     *
     * @author 周进
     * @param array $return
     *        	支付接口返回的数据
     */
    public function action_returnPay() {
        $post = $this->request->post ();
        if (count ( $post ) == 0)
            $post = $this->request->query ();
        $payment = Service::factory ( 'Payment' );
        $result = $payment->callback ( $post );
        switch ($result) 		// 根据验签的结果处理对应的订单信息（未完全）
        {
            case 'PAY_SUCCESS' :
                $data = array (
                        'order_no' => Arr::get ( $post, 'out_trade_no', '' ),
                        'order_out_no' => Arr::get ( $post, 'trade_no', '' ), // 支付宝订单号
                        'order_code' => Arr::get ( $post, 'buyer_email', '' ),
                        'order_real_account' => Arr::get ( $post, 'total_fee', '' ),
                        'order_time' => strtotime ( Arr::get ( $post, 'notify_time', '' ) ), // 交易付款时间
                        'order_status' => '1'  // 交易成功
                                );
                $account = new Service_Account ();
                $return = $account->updateRecharge ( $data );
                if ($return != FALSE) {
                    echo 'success';
                    self::redirect ( "company/member/account/showRecharge?status=success&total_fee=" . $data ['order_real_account'] . "&order_out_no=" . $data ['order_out_no'] );
                }
                break;
            case 'PAY_FAILED' :
                echo 'fail';
                self::redirect ( "company/member/account/showRecharge?status=fail&" );
                break;
            case 'PAY_ERROR' :
                echo 'fail';
                self::redirect ( "company/member/account/showRecharge?status=fail&" );
                break;
            default :
                echo 'fail';
                self::redirect ( "company/member/account/showRecharge?status=fail&" );
                break;
        }
    }
    /**
     * 支付返回结果验签处理返回给支付宝信息(异步针对直接从服务器发送的通知)
     *
     * @author 周进
     * @param array $return
     *        	支付接口返回的数据
     * @return success/fail/error
     */
    public function action_notifyPay() {
        $post = $this->request->post ();
        if (count ( $post ) == 0)
            $post = $this->request->query ();
        $payment = Service::factory ( 'Payment' );
        $result = $payment->callback ( $post );
        switch ($result) 		// 根据验签的结果处理对应的订单信息（未完全）
        {
            case 'PAY_SUCCESS' :
                $data = array (
                        'order_no' => Arr::get ( $post, 'out_trade_no', '' ),
                        'order_out_no' => Arr::get ( $post, 'trade_no', '' ), // 支付宝订单号
                        'order_code' => Arr::get ( $post, 'buyer_email', '' ),
                        'order_real_account' => Arr::get ( $post, 'total_fee', '' ),
                        'order_time' => strtotime ( Arr::get ( $post, 'notify_time', '' ) ), // 交易付款时间
                        'order_status' => '1'  // 交易付款时间
                                );
                $return = Service::factory ( 'Account' )->updateRecharge ( $data );
                if ($return != FALSE)
                    echo 'success';
                break;
            case 'PAY_FAILED' :
                echo 'fail';
                break;
            case 'PAY_ERROR' :
                echo 'fail';
                break;
            default :
                echo 'fail';
                break;
        }
    }
    /**
     * 用户订阅邮件
     *
     * @author 施磊
     */
    public function action_subscriptionEmail() {
        $this->isLogin ();

        // 获取登录userId
        $userId = $this->userInfo ()->user_id;
        $postData = $this->request->post ();
        $status = $postData ['status'] ? 1 : 0;
        $investor_service = new Service_User_Company_Investor ();
        try {
            $investor_service->updateUserSubscriptionStatus ( $userId, $status );
        } catch ( Kohana_Exception $e ) {
            $this->jsonEnArr ( '500', $e->getMessage () );
        }

        $this->jsonEnArr ( '200', 'ok' );
    }

    /**
     * 获取地区
     */
    public function action_city() {
        $data = array ();
        $city_id = $this->request->query ( "city_id" );
        if ($city_id) {
            $url = URL::webstatic ( "js/data/city/" . $city_id . ".js" );
            try {
                $json = Request::factory ( $url, "", true )->execute ()->body ();
            } catch ( Kohana_Exception $e ) {
            }
            $json = str_replace ( array (
                    "arraCity=",
                    ";"
            ), "", $json );
            if (strpos ( $json, "[" ) === 0) {
                echo $json;
                exit ();
            }
        }
        echo json_encode ( $data );
    }

    /**
     * 获取oauth授权登录地址
     *
     * @author 周进
     */
    public function action_oauthLogin() {
        $result = array (
                'isError' => true,
                'message' => '请选择要登录的平台'
        );
        $post = Arr::map ( "HTML::chars", $this->request->query () );
        // 记录回调地址
        $callback = arr::get ( $post, 'callback' );
        Cookie::set ( 'callback', $callback, 0, Cookie::$path );
        if (arr::get ( $post, 'id' ) != "") {
            $session = Session::instance ();
            $oauthType = arr::get ( $post, 'id' );
            switch ($oauthType) {
                case '1' : // qq登录
                    $oauthObj = new Service_Oauth_Qq ( Kohana::$config->load ( 'oauth.qq' ) );
                    $result = array (
                            'isError' => false,
                            'url' => $oauthObj->getLoginUrl ()
                    );
                    $session->set ( 'oauth', $oauthType );
                    break;
                case '2' : // sina登录
                    $oauthObj = new Service_Oauth_Sina ( Kohana::$config->load ( 'oauth.sina' ) );
                    $result = array (
                            'isError' => false,
                            'url' => $oauthObj->getLoginUrl ()
                    );
                    $session->set ( 'oauth', $oauthType );
                    break;
                case '3' : // alipay登录
                    $oauthObj = new Service_Oauth_Alipay ( Kohana::$config->load ( 'oauth.alipay' ) );
                    $result = array (
                            'isError' => false,
                            'url' => $oauthObj->getLoginUrl ()
                    );
                    $session->set ( 'oauth', $oauthType );
                    break;
                default :
                    $result = array (
                            'isError' => true,
                            'message' => '请选择要登录的平台'
                    );
                    break;
            }
        }
        echo json_encode ( $result );
    }

    /**
     * @sso
     * oauth授权返回处理
     *
     * @author 周进
     */
    public function action_oauthCallback() {
        $query = Arr::map ( "HTML::chars", $this->request->query () );
        $session = Session::instance ();
        $oauthType = $session->get ( 'oauth' );
        switch ($oauthType) {
            case '1' : // 腾讯
                $oauthObj = new Service_Oauth_Qq ( Kohana::$config->load ( 'oauth.qq' ) );
                break;
            case '2' : // 新浪 接口未开发
                $oauthObj = new Service_Oauth_Sina ( Kohana::$config->load ( 'oauth.sina' ) );
                break;
            case '3' : // 支付宝
                $oauthObj = new Service_Oauth_Alipay ( Kohana::$config->load ( 'oauth.alipay' ) );
                break;
            default :
                self::redirect ( '/member/login' );
                break;
        }

        // 获取对应接口数据
        $result = $oauthObj->checkStatus ( $query );
        $client = Service_Sso_Client::instance ();
        if ($result === true) {
            // 获取令牌
            $oauthObj->getAccessToken ( $query );
            $userInfo = $oauthObj->getUserInfo ();
            if (isset ( $userInfo ['id'] ) && $userInfo ['id'] != '') {
                $session->set ( 'oauth_id', $oauthType );
                $session->set ( 'oauth_userInfo', $userInfo );

                // start 绑定账号相关处理（含已绑定过的和未绑定过的）

                if ($client->isTrdOuathExist ( $userInfo ['id'] )) { // 已经绑定账号
                                                               // 获取用户信息
                    $user_info = $client->getUserInfoByOauthId ( $userInfo ['id'] );
                    if ($user_info) {
                        $user_id = $user_info->id;
                        $client->autoLogin ( $user_id );
                        $this->userType ( $user_info->user_type );
                    }
                    self::redirect ( '/member/login' );
                } else { // 未绑定账号
                      // 引导绑定 绑定已有账号或者绑定新建账号 只操作个人账户
                    $session->set ( 'oauth_id', $oauthType );
                    $session->set ( 'oauth_userInfo', $userInfo );
                    // 对于QQ的单独处理
                    if ($oauthType == 1) {
                        self::redirect ( '/member/oauthQq' );
                    }
                    // 初始化第三方绑定
                    $ouarr = array (
                            "oauth_id" => $userInfo ['id'],
                            "oauth_type" => $session->get ( 'oauth_id' ),
                            "user_name" => $userInfo ['name']
                    );
                    $rs = $client->createTrdOauth ( $ouarr );
                    if ($rs ['error'] === false) {
                        // login
                        $user_info = $client->getUserInfoByOauthId ( $userInfo ['id'] );
                        if ($user_info) {
                            $user_id = $user_info->id;
                            $client->autoLogin ( $user_id );
                            $this->userType ( $user_info->user_type );
                        }
                    } else {
                    }
                    // 处理1延迟
                    sleep ( 1 );
                    self::redirect ( '/member/login' );
                }
            } else {
                self::redirect ( '/member/login' );
            }
        } else {
            self::redirect ( '/member/login' );
        }
    }

    /**
     * 检测邮箱是否存在（第三方）
     * @edit 许晟玮
     */
    public function action_checkOauthBindUser() {
        $result = array (
                'isError' => true,
                'message' => '新用户！'
        );
        /**
         * $post = Arr::map("HTML::chars",$this->request->post());
         * if (arr::get($post, 'email')!=""&&strlen(strpos(arr::get($post, 'email'), '@'))){
         * $oauthUser = ORM::factory('User')->where("email",'=',arr::get($post, 'email'))->find();
         * if ($oauthUser->user_id>0){
         * $result = array(
         * 'isError' => false,
         * 'message' => '已注册用户！',
         * );
         * }
         * }*
         */

        $msg = array ();
        $client = Service_Sso_Client::instance ();
        $post = $this->request->post ();
        $email = Arr::get ( $post, "email" );
        $valid = new Validation ( $post );
        $valid->rule ( 'email', 'not_empty' );

        if ($client->isRegNameValid ( $email )) {
            $result = array (
                    'isError' => true,
                    'message' => '新用户！'
            );
        } else {
            $result = array (
                    'isError' => false,
                    'message' => '已注册用户！'
            );
        }

        echo json_encode ( $result );
    }
    /**
     * oauth返回处理
     *
     * @author 周进
     */
    public function action_oauthBindUser() {
        $result = array (
                'isError' => true,
                'message' => '非法操作！'
        );
        // 引导绑定 绑定已有账号或者绑定新建账号
        $session = Session::instance ();
        $post = Arr::map ( "HTML::chars", $this->request->post () );

        $oauth_userInfo = $session->get ( 'oauth_userInfo' );
        if ($oauth_userInfo ['id'] == "" || $session->get ( 'oauth_id' ) == "") {
            $result = array (
                    'isError' => true,
                    'message' => '不合法的操作来源！禁止操作'
            );
            echo json_encode ( $result );
            exit ();
        }

        $user_oauth = new Service_User ();
        $email = Arr::get ( $post, 'email' );
        $mobile = Arr::get ( $post, 'mobile' );
        $client = Service_Sso_Client::instance ();
        if ($email != '') {
            $user = $client->getUserInfoByEmail ( $email );
        } else {
        }
        if ($mobile != '') {
            $user = $client->getUserInfoByMobile ( $mobile );
        } else {
        }

        // 用户已经注册过的 直接绑定账号
        if ($user) {
            if ($user->user_type == 1) {
                $result = array (
                        'isError' => true,
                        'message' => '目前仅支持绑定个人用户！'
                );
            } else {
                try {
                    // 判断密码
                    $psd = Arr::get ( $post, 'password' );
                    $rps = $client->isPassowrdOk ( $user->id, $psd );
                    if ($rps === false) {
                        $result = array (
                                'isError' => true,
                                'message' => '密码错误'
                        );
                    } else {
                        // 初始化绑定信息，增加oauth表信息即可
                        $binduser = $client->trdRegister ( $oauth_userInfo ['id'], $user->id, $session->get ( 'oauth_id' ) );

                        if ($binduser) {
                            if (Arr::get ( $binduser, "error" ) === false) {
                                // 绑定成功
                                $service_oauth_log = new Service_Oauth_Log ();
                                $oauth_arr = array ();
                                $oauth_arr ['oauth_id'] = $oauth_userInfo ['id'];
                                $oauth_arr ['type'] = 0;
                                $oauth_arr ['act_userid'] = $user->id;
                                $oauth_result = $service_oauth_log->setOauthLog ( $oauth_arr );

                                $client->autoLogin ( $user->id ); // 自动登录
                                $result = array (
                                        'isError' => false,
                                        'message' => '绑定账户成功！'
                                );
                            } else {
                                // 错误代码
                                $code = Arr::get ( $binduser, "code" );
                                if ($code == "003") {
                                    $msg = "该用户已经绑定";
                                } else {
                                    $msg = "绑定账户失败！";
                                }
                                $result = array (
                                        'isError' => true,
                                        'message' => $msg
                                );
                            }
                        } else {
                            $result = array (
                                    'isError' => true,
                                    'message' => '绑定失败'
                            );
                        }
                    }
                } catch ( Kohana_Exception $e ) {
                    throw $e;
                    $result = array (
                            'isError' => true,
                            'message' => '绑定账户失败！'
                    );
                }
            }
        } else {
            $result = array (
                    'isError' => true,
                    'message' => '绑定的账户不存在！'
            );
        }

        echo json_encode ( $result );
    }

    /**
     * 传入用户名获取用户类型
     *
     * @author 许晟玮
     *         @sso
     */
    public function action_getUserTypeByName() {
        $post = $this->request->post ();
        $get = $this->request->query();
        $username = arr::get ( $post, 'login_name' );
        $passwd = arr::get ( $post, 'login_psd' );
        $code = arr::get ( $post, 'valid_code' );
        $callback = arr::get ( $get, 'callback' );
        if($callback){
            $username = arr::get ( $get, 'login_name' );
            $passwd = arr::get ( $get, 'login_psd' );
            $code = arr::get ( $get, 'valid_code' );
        }
        $msg = array ();
        // 用户名
        if ($username != "") {
            $service = new Service_User ();
            $result = $service->checkLoginName ( $username );
            if ($result === false) {
                $msg ['type'] = 0;
            } else {
                $msg ['type'] = ceil ( $result->user_type );
            }
        } else {
            $msg ['type'] = 0;
        }
        // 密码
        if ($passwd != "") {
            $result = $service->checkLoginName ( $username );
            if ($result === false) {
                $msg ['psd_error'] = 1;
            } else {
                // 判断密码
                $res = Service_Sso_Client::instance ()->isPassowrdOk ( $result->id, $passwd );
                if ($res === false) {
                    $msg ['psd_error'] = 1;
                } else {
                    $msg ['psd_error'] = 2;
                }
            }
        } else {
            $msg ['psd_error'] = 0;
        }

        // 验证码
        if (Captcha::valid ( $code ) === true) {
            $msg ['code_error'] = 1;
        } else {
            $msg ['code_error'] = 2;
        }
        if($callback){
            echo  $callback ;
            echo  '(' ;
            echo json_encode ( $msg );
            echo  ')' ;
        }else{
            echo json_encode ( $msg );
        }
    }
    // end function

    /**
     * 不弹出修改邮件
     *
     * @author 许晟玮
     */
    public function action_setEmailShowCookie() {
        $service = new Service_User ();
        $userid = $this->userId ();
        $service->setEditEmailLog ( $userid, '0', '0' );
    }
    // end function

    /**
     * 手机发送验证码
     *
     * @param unknown $mobile
     * @author 龚湧
     */
    public function action_sendMobileCode() {
    	$msg = array();
        $post = Arr::map(array(array("HTML","chars")), $this->request->post());
        $get = Arr::map(array(array("HTML","chars")), $this->request->query());
        $type = NULL;
        $type = Arr::get($post,"type");        
        $callback = arr::get ( $get, 'callback' );
        if($callback){
        	$type = Arr::get($get,"type");
        }
        // 检测手机号码是否已绑定
        $mobile = HTML::chars ( $this->request->post ( "mobile" ) );
        if($callback){
        	$mobile = HTML::chars ( $this->request->query ( "mobile" ) );
        }
        $servicemobile = new Service_User ();
        //快速发送名片无需判断是否已经注册过了
        if ($servicemobile->isMobileBinded ( $mobile ) && $type!="mobile_card") {
            $msg = $this->jsonEnArr ( '400', "手机号码已注册" ,1);
        } else {
            $code = mt_rand ( 100000, 999999 );
            $servicesend = new Service_User_MobileCodeLog ();
            if (is_object ( $servicesend->setLogs ( $mobile, $code ) )) {

                //手机注册
                if ($type == "mobile_reg") { // 手机注册
                    $view = "msg/mobile/mobile_reg";
                }
                elseif($type=="mobile_card"){//发送名片并注册
                    $view = "msg/mobile/mobile_card";
                }

                if (isset ( $view )) {
                    $view = View::factory ( $view );
                    $view->valid_code = $code;
                    $content = $view->render();
                }

                // 默认处理方式
                if (! $type or ! isset ( $content )) {
                    $content = "您的验证码是" . $code;
                }

                $result = common::send_message ( $mobile, $content, 'online' );
                if ($result->retCode === 0) {
                    // 消息发送成功log
                    $servicemobile->messageLog($mobile,0,2,$content,1);
                    Cookie::set ( "valid_mb", md5 ( $mobile . Kohana::$config->load ( "message.space_time.mobile" ) ), Kohana::$config->load ( "message.expire.mobile" ) );
                    $msg = $this->jsonEnArr ( "200", "验证码已发送至您手机，请注意查收" ,1);
                } else {
                    $msg = $this->jsonEnArr ( "500", "短信发送失败" ,1);
                    // 消息发送失败log
                    // $servicemobile->messageLog($mobile,0,2,'短信发送失败',0);
                }
            } else {
                $msg = $this->jsonEnArr ( "500", "写入数据异常" ,1);
            }
        }
    	if($callback){
       		echo $callback.'('.$msg.')';exit;
    	}else{
          	echo $msg;exit;
      	}
    }
    public function action_isLogn() {
        //cookie

        if( isset( $_COOKIE['v_cpa_aid'] ) ){
            if( Cookie::get('alerdy_aid_set')!=1 ){
                Cookie::set('cpa_aid', $_COOKIE['v_cpa_aid']);
                Cookie::set('alerdy_aid_set', '1');
            }
        }

        // 判断是否登录
        $islogin = $this->loginUser ();
        $arr_return = array ();
        if ($islogin == true) {
            $username = $this->userInfo ();
            $arr_return ['status'] = true;
            if (isset ( $username->user_name ) && $username->user_name) {
                $arr_return ['user_name'] = $username->user_name;
                $arr_return ['is_have_name'] = true;
            } else {
                // #判断是什么时间段
                $arr_return ['user_name'] = common::getNowTime ();
                $arr_return ['is_have_name'] = false;
            }
            if ($username->user_type == intval ( 1 )) { // 去企业中心
                $arr_return ['user_type'] = true;
                $arr_return ['user_type_url'] = URL::website ( '/company/member/msg/msglist' );
            } elseif ($username->user_type == intval ( 2 )) { // 去个人中心
                $arr_return ['user_type'] = false;
                $arr_return ['user_type_url'] = URL::website ( 'person/member/msg/msglist' );
            }
            $arr_return ['url'] = ($username->user_type == 1) ? URL::website ( "/company/member" ) : URL::website ( "/person/member" );
            $ucmsg = Service::factory ( "User_Ucmsg" );
            $msg_total_count = $ucmsg->getMsgCount ( $this->userId () );
            $msg_total_count = $msg_total_count ? $msg_total_count : 0;
            $arr_return ['msg_total_count'] = $msg_total_count;
        } else {
            $arr_return ['status'] = false;
            $arr_return ['user_type'] = "";
            $arr_return ['user_type_url'] = "";
            $arr_return ['msg_total_count'] = intval ( 0 );
        }
        echo json_encode ( $arr_return );
    }

    /**
     * 更新消息状态
     *
     * @author 许晟玮
     */
    public function action_updatemsgstatus() {
        $post = $this->request->post ();
        $key = Arr::get ( $post, 'key' );
        $id = Arr::get ( $post, 'id' );
        $update = array (
                "message_state" => "2",
                "read_time" => time ()
        );
        $service = new Service_Redis_List ();
        try {
            $service->updateMsgFromList ( $key, $id, $update );
            return 'ok';
        } catch ( Exception $e ) {
            throw $e;
            // return false;
        }
    }

    /**
     * 项目一级行业
     *
     * @author 曹怀栋
     */
    public function action_primaryIndustry() {
        if ($this->request->is_ajax ()) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            $msg = array ();
            if (isset ( $post ['parent_id'] ) && is_numeric ( $post ['parent_id'] )) {
                if ($post ['parent_id'] == intval ( 0 )) {
                    $rtn = ORM::factory ( "industry" )->where ( 'parent_id', '=', $post ['parent_id'] )->order_by ( 'industry_order', 'DESC' )->find_all ();
                    foreach ( $rtn as $key => $val ) {
                        $arr_return_data [] = $val->as_array ();
                    }
                    $msg = $arr_return_data;
                } else {
                    $msgs = common::primaryIndustry ( $post ['parent_id'] );
                    foreach ( $msgs as $k => $v ) {
                        $msg [$k] ['industry_id'] = $v->industry_id;
                        $msg [$k] ['industry_name'] = $v->industry_name;
                    }
                }
                echo json_encode ( $msg );
            } else {
                echo json_encode ( $msg );
            }
        }
    }

    /**
     * 获取认领项目失败的原因
     *
     * @author 钟涛
     */
    public function action_getCausesFailure() {
        if ($this->request->is_ajax ()) {
            // 判断是否登录 没有登录即跳转到登录页
            $this->isLogin ();
            // 获取登录user_id
            $user_id = $this->userInfo ()->user_id;
            $postdata = Arr::map ( "HTML::chars", $this->request->post () );
            $comid = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ()->com_id;
            $renlingmodel = ORM::factory ( 'ProjectRenling' )->where ( 'com_id', '=', $comid )->where ( 'project_id', '=', arr::get ( $postdata, 'projectid' ) )->find ();
            if ($renlingmodel->loaded ()) {
                $msg ['error'] = 1;
                $msg ['content'] = $renlingmodel->causes_failure;
                echo json_encode ( $msg );
            } else {
                $msg ['error'] = 0;
                $msg ['content'] = '';
                echo json_encode ( $msg );
            }
        }
    }

    /**
     * 企业判断是否已经扣除平台服务费
     *
     * @author 钟涛
     */
    public function action_justisPlatformFree() {
        if (! $this->isLogins ()) {
            $msg ['error'] = '登录已过期，请重新登录';
            $msg ['type'] = 1;
            echo json_encode ( $msg );
            exit ();
        }
        // 判断用户类型
        $usertpye = $this->userInfo ()->user_type;
        if ($usertpye != 1) {
            $msg ['error'] = '个人用户无法操作';
            $msg ['type'] = 2;
        } else {
            $msg ['error'] = '';
            $user_id = $this->userInfo ()->user_id;
            $cominfo = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ();
            if ($cominfo->platform_service_fee_status == 1) {
                $msg ['type'] = 3; // 已经扣除服务费
            } else {
                $msg ['type'] = 4; // 没有扣除服务费
            }
        }
        echo json_encode ( $msg );
        exit ();
    }

    /**
     * 企业判断是否已经完善企业基本信息，是否已经完善企业资质
     *
     * @author 钟涛
     */
    public function action_isCompanyInfo() {
        $this->isLogin ();
        // 获取登录user_id
        $user_id = $this->userInfo ()->user_id;
        $postdata = Arr::map ( "HTML::chars", $this->request->post () );
        $companyresult = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ()->as_array ();
        if ($companyresult ['com_id'] == '' || empty ( $companyresult ['com_logo'] ) || empty ( $companyresult ['com_phone'] ) || empty ( $companyresult ['com_contact'] ) || empty ( $companyresult ['com_adress'] )) {
            $msg ['error'] = '您的基本信息还未完善，无法申请成为招商通会员，<a href="/company/member/basic/editCompany?type=1">去完善</a>';
            echo json_encode ( $msg );
            exit ();
        }

        $ser = new Service_User_Company_User ();
        $cominfo = ORM::factory ( 'Companyinfo' )->where ( 'com_user_id', '=', $user_id )->find ();
        $iscer = $ser->checkCompanyCertificationStatus ( $cominfo->com_id );
        if ($iscer != 1) {
            $msg ['error'] = '您企业资质还没有通过审核，无法申请成为招商通会员，<a href="/company/member/basic/comCertification/">去认证资质</a>';
            echo json_encode ( $msg );
            exit ();
        }

        $msg ['error'] = '';
        echo json_encode ( $msg );
        exit ();
    }

    /**
     * 生成登录浮层
     *
     * @author 许晟玮
     */
    public function action_setLoginFuInfo() {
        $post = $this->request->post ();
        $to_url = Arr::get ( $post, 'tourl' );
        $reg_fu_platform_num = Arr::get ( $post, 'reg_fu_platform_num_id' );
        $reg_fu_user_num = Arr::get ( $post, 'reg_fu_user_num_id' );

        $action_url = urlbuilder::geren ( "denglu" );
        $zhuce_url = urlbuilder::register ( "geren" );
        if ($to_url) {
            $action_url .= "?to_url=" . $to_url;
            $zhuce_url .= "?to_url=" . $to_url;
        }

        $html = "<div class=\"login_lk_right login_fc0515\" style=\"border-right:1px solid #e0e0e0; height:auto!important; height:380px; min-height:380px;\">";
        $html .= "<h4>已有一句话账号，马上登录</h4>";
        $html .= "<div class=\"login_lk_insert\">";
        $html .= "<form method=\"post\" action='" . $action_url . "' id=\"login_fc\">";
        $html .= "<p class=\"ajaxlogin\"><label class=\"checkerror\"></label><input name=\"email\" type=\"text\" placeholder=\"注册邮箱/已验证的手机号码\" class=\"login_lk_text05 validate required ismobilemail\" id=\"login_email_id\" /></p>";
        $html .= "<p class=\"ajaxlogin\"><input type=\"password\" name=\"password\" placeholder=\"密码\" class=\"password login_lk_text05 validate userpwd required\" id=\"loginPassword2\" /></p>";
        $html .= "<p class=\"ajaxlogin\"><input type=\"text\" placeholder=\"验证码\" class=\"login_lk_text06 validate remote\" name=\"valid_code\" id=\"loginVfCode\"/><img alt=\"验证码\" src=\"\" class=\"login_lk_yzm\" width=\"95\" height=\"37\" id=\"vfCodeImg2\"/><a href=\"javascript:void(0)\" class=\"login_lk_yzmtext\" id=\"changeCodeImg2\">看不清楚？换一张</a></p>";
        $html .= "<p class=\"login_lk_shadow\"></p>";
        $html .= "<p class=\"login_lk_wenzi\"><input type=\"checkbox\" name=\"remember\" id=\"cookietime\" tabindex=\"1\" value=\"1\" /><span>一周内自动登录</span><a href='" . URL::website ( '/member/forgetpassword' ) . "'>忘记密码？</a></p>";
        $html .= "<p class=\"login_lk_login\">";
        $html .= "<input type=\"image\" class=\"login_lk_login_btn\" src='" . url::webstatic ( 'images/platform/login_new/btn_login.jpg' ) . "'>";
        $oauth = Kohana::$config->load ( 'oauth' );
        if ($oauth ['app'] ['status'] == 1) {
            $html .= "<span class=\"login_lk_friendlink\" id=\"login_lk_friendlink_span\">";
            if ($oauth ['sina'] ['status'] == 1) {
                $html .= "<a href=\"javascript:oauthlogin('2');\" title=\"新浪微博\"><img alt=\"新浪微博\" src='" . URL::webstatic ( 'images/platform/login_new/icon_sina.jpg' ) . "' /></a>";
            }
            // if ($oauth['qq']['status']==1){
            // $html.= "<a href=\"javascript:oauthlogin('1');\" title=\"腾讯QQ\"><img alt=\"腾讯QQ\" src='".URL::webstatic('images/platform/login_new/icon_qq.jpg')."' /></a>";
            // }
            if ($oauth ['alipay'] ['status'] == 1) {
               // $html .= "<a href=\"javascript:oauthlogin('3');\" title=\"支付宝\" class=\"zfb\"><img alt=\"支付宝\" src='" . URL::webstatic ( 'images/platform/login_new/icon_zfb.jpg' ) . "' /></a>";
            }
            $html .= "</span>";
        }
        $html .= "</p><div class=\"clear\"></div></form></div><div class=\"clear\"></div></div>";
        $html .= "<div class=\"login_fc_left\">";
        $html .= "<span class=\"login_fc_freetext\">还没有一句话账号？</span><div class=\"clear\"></div>";
        $html .= "<p class=\"login_new_freebtn\"><a href='" . $zhuce_url . "'>免费注册</a></p><div class=\"clear\"></div>";
        $html .= "<p class=\"login_new_attention_num0515\">";
        $html .= "<label>目前已有<b>{$reg_fu_platform_num}</b>个项目，<b>{$reg_fu_user_num}</b>个用户加入一句话</label></p></div><input type=\"hidden\" id=\"loginHidden\" value=\"0\" /><div class=\"clear\"></div></div>";
        echo json_encode ( $html );
        exit ();
    }
    // end function

    /**
     * 判断企业营业执照编号是否存在
     *
     * @author 许晟玮
     */
    public function action_licencenumberEof() {
        $post = $this->request->post ();
        $com_business_licence_number = Arr::get ( $post, 'business_licence_number' );

        $user = ORM::factory ( "user", $this->userId () );
        // has_one 对应关系 公司基本信息
        $company = $user->user_company;
        $com_id = $company->com_id;
        $orm = ORM::factory ( 'Companyinfo' );
        $orm->where ( 'com_business_licence_number', '=', $com_business_licence_number );
        $orm->where ( 'com_id', '!=', $com_id );
        $count = $orm->count_all ();
        $msg = array ();
        if ($count > 0) {
            $msg ['result'] = '1';
        } else {
            $msg ['result'] = '0';
        }
        echo json_encode ( $msg );
        exit ();
    }
    // end function

    /**
     * mobile code log 中的验证码判断
     *
     * @author 许晟玮
     */
    public function action_getMobileCodeEof() {
        $post = $this->request->post ();
        $mobile = Arr::get ( $post, 'mobile' );
        $code = Arr::get ( $post, 'code' );
        if ($mobile != '' && $code != '') {

            $service = new Service_User_MobileCodeLog ();
            $res = $service->getCodeEof ( $mobile, $code );
            if ($res === false) {

                $this->jsonEnArr ( '300', '验证码错误' );
            } else {

                $this->jsonEnArr ( '200', 'ok' );
            }
        } else {
            $this->jsonEnArr ( '500', '参数错误' );
        }
    }
    // end function
    /**
     * 问答管理--根据一级分类id 获取 二级分类id
     *
     * @author 赵路生
     */
    public function action_getSecIndByFirInd() {
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        $ask_ser = new Service_News_Ask ();
        $result = $ask_ser->getAskSecIndByFirInd ( arr::get ( $post, 'ask_category_first', 1 ) );
        echo json_encode ( $result );
    }
     /**
     * 创业问答  -- 采纳答案  【这里将作为ajax来进行处理】
     * @return boolean
     */
    public function action_addAdopt(){
        // 判断是否登录
        if($this->isLogins()){
            $user_info = Service_Sso_Client::instance()->getUserInfo(cookie::get('authautologin'));
            $ask_ser = new Service_News_Ask();
            // 最后这个会通过ajax传递参数的
            $post = Arr::map("HTML::chars", $this->request->post());
            $title_id = intval(arr::get($post,'title_id',0));
            $answer_id = intval(arr::get($post,'answer_id',0));
            // 字符截取
            $comment = arr::get($post,'comment','');
            $user_status = $user_info->user_status ? $user_info->user_status :0 ;
            if($title_id && $answer_id && $comment && $user_status){
                // 判断问题是不是该用户的，判断该答案是不是这个问题的，判断问题是否被采纳了
                $title_result = $ask_ser->getAskTitleById($title_id);
                $answer_result = $ask_ser->getAskSingleAnswerById($answer_id);
                // 这里还可以进行不同类型错误的分解
                if($title_result && $title_result->ask_user_id == $user_info->id && $title_result->ask_adopt_type==0 && $answer_result && $answer_result->ask_answer_status==1 && $answer_result->ask_id == $title_result->ask_id){
                    $result = $ask_ser->addadopt($user_info->id, $title_id, $answer_id,$comment);
                    // 添加创业币
                    if($result){
                        if($answer_result->ask_answer_user_type == 2){
                            $ask_ser->updataAskcurrency($answer_result->ask_answer_user_id, 1, 6, 10);
                        }
                        $this->jsonEnArr('1', '操作成功');
                        exit;
                    }
                }
            }
            // 这个操作失败可以有更多类型分解
            $this->jsonEnArr('-2', '操作失败');
            exit;
        }else{
            $this->jsonEnArr('-1', '未登录');
            exit;
        }
    }// function end
    /**
     * 创业问答  -- 登陆用户 添加答案【通过ajax来提交数据】
     * @author 赵路生
     * @param
     */
    public function action_addMyAnswer(){
        // 判断登陆
        if($this->isLogins()){
            // 获取用户信息
            $user_info = Service_Sso_Client::instance()->getUserInfo(cookie::get('authautologin'));
            $post = Arr::map("HTML::chars", $this->request->post());
            $ask_ser = new Service_News_Ask();
            $post['ask_id'] = intval(arr::get($post,'title_id',0));
            $post['answer_id'] = intval(arr::get($post,'answer_id',0));
            if($post['ask_id'] && $user_info->id){
                $result = $ask_ser->getAskTitleById($post['ask_id']);
                // 检查问题是否能够回答的状态
                if($result && $result->ask_adopt_type == 0 && $result->ask_status==1 && $result->ask_user_id != $user_info->id ){
                        // 看该用户是否还能够对问题进行回答 或者修改
                        if($post['answer_id']){
                            $answer_result = $ask_ser->getAskSingleAnswerById($post['answer_id']);
                            // 判断问题是否是该用户所属
                            if(!($answer_result && $answer_result->ask_answer_user_id == $user_info->id)){
                                $this->jsonEnArr('-2', '操作失败');
                                exit;
                            }
                        }else{
                            $user_title_r = $ask_ser->getAskTitleUserRl($user_info->id,$result->ask_id);
                            if($user_title_r){
                                $this->jsonEnArr('-2', '操作失败');
                                exit;
                            }
                        }
                        // 获取用户的姓名
                        if(intval(arr::get($post,'anonymous',0))){
                            $post['ask_answer_user_name'] = '匿名用户';
                        }else{
                            if($user_info->user_name){
                                $post['ask_answer_user_name'] = $user_info->user_name;
                            }else{
                                // 对于接口获取不到用户姓名的 到个人用户表里面去获取数据
                                if($user_info->user_type == 2){
                                    $ser_user = new Service_User_Person_User();
                                    $user_model = $ser_user->getPerson($user_info->id);
                                    if($user_model->loaded()){
                                        $post['ask_answer_user_name'] = $user_model->per_realname;
                                    }else{
                                        $this->jsonEnArr('-2', '操作失败');
                                        exit;
                                    }
                                }else{
                                    $this->jsonEnArr('-2', '操作失败');
                                    exit;
                                }
                            }
                        }
                        $post['ask_answer_user_id'] = $user_info->id;
                        $post['ask_answer_user_type'] = $user_info->user_type;
                        $post['user_status'] = $user_info->user_status;
                        // 只是简单的获取ip
                        $post['ip'] = $_SERVER['REMOTE_ADDR']? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
                        // 提交数据
                        $result_id = $ask_ser->addMyAnswer($post);
                        // 个人用户 添加创业币
                        if($result_id){
                            if(isset($post['answer_id']) && $post['answer_id']){
                                $this->jsonEnArr('3', '修改成功');
                                exit;
                            }else{
                                if($user_info->user_type == 2){
                                    $result = $ask_ser->updataAskcurrency($user_info->id, 1, 5, 5);
                                    if($result){
                                        $this->jsonEnArr('1', '操作成功'); // 个人用户回答成功
                                    }else{
                                        $this->jsonEnArr('4', '操作成功'); // 个人用户回答成功
                                    }
                                    exit;
                                }
                                $this->jsonEnArr('2', '操作成功'); // 企业回答成功
                                exit;
                            }
                        }
                }
            }
            // 这个操作失败可以有更多类型分解
            $this->jsonEnArr('-2', '操作失败');
            exit;
        }else{
            $this->jsonEnArr('-1', '未登录');
            exit;
        }
    }// function end
    /**
     * 创业问答  -- 登陆用户 提交问题
     * @author 赵路生
     * @param
     */
    public function action_addMyAsk(){
        // 判断登陆
        if($this->isLogins()){
            $user_info = Service_Sso_Client::instance()->getUserInfo(cookie::get('authautologin'));
            $post = Arr::map("HTML::chars", $this->request->post());
            $ask_ser = new Service_News_Ask();
            if(!empty($post) && isset($post['submit'])){
                // 获取用户信息
                $post['uid'] = $user_info->id;
                $post['id'] = intval(arr::get($post,'id',0));
                if(!$post['id']){
                    unset($post['id']);
                }else{
                    $result = $ask_ser->getAskTitleById($post['id']);
                    if($result && $result->ask_user_id != $user_info->id && $result->ask_adopt_type == 1){
                        $this->jsonEnArr('-2', '操作失败');
                        exit;
                    }
                }
                // 数据处理
                // 获取用户的姓名
                if(intval(arr::get($post,'anonymous',0))){
                    $post['user_name'] = '匿名用户';
                }else{
                    if($user_info->user_name){
                        $post['user_name'] = $user_info->user_name;
                    }else{
                        // 对于接口获取不到用户姓名的 到个人用户表里面去获取数据
                        if($user_info->user_type == 2){
                            $ser_user = new Service_User_Person_User();
                            $user_model = $ser_user->getPerson($user_info->id);
                            if($user_model->loaded()){
                                $post['user_name'] = $user_model->per_realname;
                            }else{
                                $this->jsonEnArr('-2', '操作失败');
                                exit;
                            }
                        }else{
                            $this->jsonEnArr('-2', '操作失败');
                            exit;
                        }
                    }
                }
                $post['user_type'] = $user_info->user_type;
                $post['user_status'] = $user_info->user_status;
                // 只是通过简单的方式获取ip
                $post['ip'] = $_SERVER['REMOTE_ADDR']? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
                // 返回处理结果
                $result_id = $ask_ser->addMyAsk($post);
                // 添加创业币
                if($result_id){
                    if(!isset($post['id']) && $user_info->user_type == 2){
                        $return = $ask_ser->updataAskcurrency($user_info->id, 1, 4, 10);
                        if($return){
                            $this->jsonEnArr(1, $result_id); // 个人用户添加成功
                        }else{
                            $this->jsonEnArr(4, $result_id); // 操作成功
                        }
                        exit;
                    }
                    if(isset($post['id']) && $post['id']){
                        $this->jsonEnArr(3, $result_id); // 修改成功
                    }else{
                        $this->jsonEnArr(2, $result_id); // 企业用户添加成功
                    }
                    exit;
                }
                $this->jsonEnArr('-2', $result_id); // 操作失败
                exit;
            }
        }else{
            $this->jsonEnArr('-1', $result_id); // 未登录
            exit;
        }
    }// function end
    /**
     * ajax 检查验证码2
     * @author yamasa
     */
    public function action_checkvalidcode2() {
        $post = $this->request->post ();
        $code = Arr::get ( $post, 'valid_code' );
        if(!$code){
            exit;
        }
        if (Captcha::valid ( $code )) {
            echo true;
        } else {
            echo false;
        }

    }

    /**
     * 检查邮箱或手机是否已注册
     * @author yamasa
     */
    public function action_checkisused() {
        $post = $this->request->post ();
        $user = Arr::get ( $post, 'user' );
        if(!$user){
            exit;
        }
        if (strstr ( $user, "@" )) {
            $client = Service_Sso_Client::instance ();
            if ($client->isRegNameValid ( $user )) {
                // 邮箱莫注册
                echo 0;
            } else {
                // 邮箱已注册
                echo 1;
            }
        } else {
            $service_user = Service::factory ( "User" );
            if ($service_user->isMobileBinded ( $user )) {
                echo 1;
            } else {
                echo 0;
            }
        }

    }
    /**
     * 找回密码发送短信
     * @author yamasa
     */
    public function action_sendMobileCode2() {
        // 检测手机号码是否已绑定
        $mobile = HTML::chars ( $this->request->post ( "mobile" ) );
        if(!$mobile){
            exit;
        }
        $servicemobile = new Service_User ();
        if ($servicemobile->isMobileBinded ( $mobile )) {
            $code = mt_rand ( 100000, 999999 );
            $servicesend = new Service_User_MobileCodeLog ();
            if (is_object ( $servicesend->setLogs ( $mobile, $code ) )) {
                $result = common::send_message ( $mobile, "您的验证码是" . $code, 'online' );
                if ($result->retCode === 0) {
                    $this->jsonEnArr ( "200", "验证码已发送至您手机，请注意查收" );
                    // 消息发送成功log
                    // $servicemobile->messageLog($mobile,0,2,'验证码已发送至您手机，请注意查收',1);
                    Cookie::set ( "valid_mb", md5 ( $mobile . Kohana::$config->load ( "message.space_time.mobile" ) ), Kohana::$config->load ( "message.expire.mobile" ) );
                } else {
                    $this->jsonEnArr ( "500", "短信发送失败" );
                    // 消息发送失败log
                    // $servicemobile->messageLog($mobile,0,2,'短信发送失败',0);
                }
            } else {
                $this->jsonEnArr ( "500", "写入数据异常" );
            }

            // $this->jsonEnArr ( '400', "手机号码已注册" );
        } else {
        }
    }
    /**
     * mobile code log 中的验证码判断2，找回密码使用
     * @author yamasa
     */
    public function action_getMobileCodeEof2() {
        $post = $this->request->post ();
        $mobile = Arr::get ( $post, 'mobile' );
        $code = Arr::get ( $post, 'code' );
        if ($mobile != '' && $code != '') {
            $service = new Service_User_MobileCodeLog ();
            $res = $service->getCodeEof ( $mobile, $code );
            if ($res === false) {
                echo false;
            } else {
                $session = Session::instance ();
                $session->set ( 'sessionmobile', $mobile );
                echo true;
            }
        } else {

            $this->jsonEnArr ( '500', '参数错误' );
        }
    }
    /**
     * 重新发送邮件
     * @author yamasa
     */
    public function action_sendremailpass(){
        $post = $this->request->post ();
        $email =  Arr::get ( $post, 'email' );
        if(empty($email))exit;
        $service = new Service_User ();
        $result = $service->sendMailPassword ( $email );
        print_r($result);
    }
    /**
     * 删除875整合登录缓存
     */
    public function action_clearcache(){
        Cookie::delete('authautologin');
        Session::instance()->delete('authautologin');
    }
    // function end

    /**
     * ajax登录
     * @author 许晟玮
     */
    public function action_ajaxlogin(){
    	$msg = array();
        $post = $this->request->post();
        $get = $this->request->query();
        $callback = arr::get ( $get, 'callback' );
        $username= Arr::get($post, 'email');
        $password= Arr::get($post, 'password');
        $valid_code= Arr::get($post, 'valid_code');
    	if($callback){
            $username= Arr::get($get, 'email');
	        $password= Arr::get($get, 'password');
	        $valid_code= Arr::get($get, 'valid_code');
        }
        //$remember= Arr::get($post, 'remember');

        //$rem
        if( $username=='' || $password=='' || $valid_code==''  ){
            $msg = $this->jsonEnArr ( '500', '参数错误',1 );
        }else{
            //如果登录的cookie不存在，就删除sessioin
            $login_cookie= Cookie::get('authautologin');
            if( $login_cookie==null || $login_cookie=='' ){
                //删除session
                //echo 'a';
                Session::instance()->delete('authautologin');
            }
            $p_email = secure::secureInput ( secure::secureUTF ( $username ) );
            // 登录验证
            $service = new Service_User ();
            if($callback){
            	$result = $service->loginCaptcha ( $get );
            }else{
            	$result = $service->loginCaptcha ( $post );
            }            

            if ($result!==true) {
               $msg = $this->jsonEnArr ( '500', $result ,1);
            }else{
                $last_login_user_status = ORM::factory ( "User", $this->loginUserId () );
                $user ['user_id'] = $this->loginUserId ();
                $user ['last_logintime'] = time ();
                $user ['last_login_ip'] = ip2long ( Request::$client_ip );

                // 用户信息更新
                $client= Service_Sso_Client::instance();
                $rus= $client->getUserInfoById($this->loginUserId ());

                $session = Session::instance ();
                if( $rus->user_name!='' ){
                    $session->set ( "username", $rus->user_name );
                }
                if( $rus->user_name=='' && $rus->email!='' ){
                    $session->set ( "username", $rus->email );
                }
                if( $rus->user_name=='' && $rus->email=='' && $rus->mobile!='' ){
                    $session->set ( "username", $rus->mobile );
                }
                if( $rus->user_name=='' && $rus->email=='' && $rus->mobile=='' ){
                    $session->set ( "username", '尊敬的会员您好' );
                }

                $upldate = $service->updateUser ( $user );
                $usertype = $rus->user_type;

                $this->addUserLoginLog ( $user ['user_id'], $usertype );
                if ($usertype == 2) { // 个人用户添加活跃度by钟涛
                    $ser1 = new Service_User_Person_Points ();
                    $ser1->addPoints ( $user ['user_id'], 'login' ); // 每日用户登录
                    $info_service = new Service_User_Person_User();
                    $ipf= $info_service->getPerson($user ['user_id']);
                    if( $ipf->per_id!='' ){
                        if( $ipf->per_realname != $rus->user_name )	{
                            //update sso user_name
                            $client->updateBasicInfoById($user ['user_id'], array('user_name'=>$ipf->per_realname));
                        }
                    }

                }else{
                    $info_service = new Service_User_Company_User();
                    $icf= $info_service->getCompanyInfo($user ['user_id']);
                    if( $icf->com_id!='' ){
                        if( $icf->com_name!=$rus->user_name ){
                            $client->updateBasicInfoById($user ['user_id'], array('user_name'=>$icf->com_name));
                        }
                    }

                }
                $msg = $this->jsonEnArr ( '200', 'ok' ,1);
            }

        }
    	if($callback){
       		echo $callback.'('.$msg.')';exit;
    	}else{
          	echo $msg;exit;
      	}
    }

    //end function

}