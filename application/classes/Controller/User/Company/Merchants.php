<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 招商通服务
 * @author 龚湧
 */
class Controller_User_Company_Merchants extends Controller_User_Company_Template{
    /**
     * @sso
     * 招商通服务申请状态
     * @author 曹怀栋
     */
    public function action_applyBusiness(){
        $Service_Company=new Service_User_Company_User();
        $result = $Service_Company->applyStatus($this->userId());
        if($result){
            $content = View::factory("user/company/apply");
            $this->content->rightcontent = $content;
        }else{
            $content = View::factory("user/company/applystatus");
            $this->content->rightcontent = $content;
            $applyinfo = $Service_Company->getApplyBusiness($this->userId());
            if(!empty($applyinfo->business_phone)){
                $com_phone=explode('+', $applyinfo->business_phone);
                if(!empty($com_phone[1])){//判断座机号码是否为空
                    $content->phone = $com_phone[0].'-'.$com_phone[1];
                }else{
                    $content->phone = $com_phone[0];
                }
            }else{
                $content->phone = "";
            }
            $content->applyinfo = $applyinfo;
               //$user = ORM::factory('User',$this->userId());
               $user = Service_Sso_Client::instance()->getUserInfoById( $this->userid() );
            $content->user = $user;
            //sso 赵路生 2013-11-12
            $content->companyinfo = $Service_Company->getCompanyInfo($this->userid());
        }
    }

    /**
     * @sso
     * 招商通服务
     * @author 潘宗磊
     */
    public function action_applyService(){
        $content = View::factory("user/company/applyservice");
        $this->content->rightcontent = $content;
        //$user = ORM::factory('User',$this->userId());
        //@sso 赵路生 2013-11-12
        $user = Service_Sso_Client::instance()->getUserInfoById( $this->userid() );
        $service = new Service_User_Company_User();
        $user_company = $service->getCompanyInfo($this->userid());
        $content->user = $user;
        $content->companyinfo = $user_company;
        if(!empty($user_company->com_phone)){
          $com_phone=explode('+', $user_company->com_phone);
          if(!empty($com_phone[1])){//判断座机号码是否为空
           $content->com_phone = $com_phone[0];
           $content->branch_phone = $com_phone[1];
          }else{
             $content->com_phone = $user_company->com_phone;
             $content->branch_phone = '';
          }
          }else{
                $content->com_phone = "";
                $content->branch_phone = '';
        }
        $Service_Company=new Service_User_Company_User();
        if($this->request->method()== HTTP_Request::POST){
           $form = Arr::map("HTML::chars", $this->request->post());
           $form['business_phone'] = $form['business_phone'].'+'.$form['branch_phone'];
           unset($form['branch_phone']);
           unset($form['x']);
           unset($form['y']);
           $form = arr::map(array("HTML::chars"), $form);
           $result = $Service_Company->applyBusiness($form);
           if($result){
                self::redirect('/company/member/merchants/applyBusiness');
           }
        }
    }
}