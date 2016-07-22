<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人招商会管理
 * @author 潘宗磊
 *
 */
class Service_User_Person_Invest{

    /**
    * 获取招商会列表信息
    * @author 潘宗磊
    */
    public function myInvestment($userid){
        $model = ORM::factory('Applyinvest');
        //分页 此处需要设置reset(FALSE) 否则没有数据时默认会显示一页数据
        $count = $model->where('user_id', '=', $userid)->where('apply_status','!=',1)->order_by('apply_id','desc')->reset(false)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 6,
        ));
        $array=array();
        $apply = $model->limit($page->items_per_page)->offset($page->offset)->find_all()->as_array();
        $resault_array = array();
        foreach ($apply as $k=>$v){
           if($v->invest_id){
              $invest=ORM::factory('Projectinvest',$v->invest_id);
              $project = ORM::factory('Project',$invest->project_id);
              $province=ORM::factory('City',$invest->investment_province)->cit_name;
              $city=ORM::factory('City',$invest->investment_city)->cit_name;
              //距离招商会报名时间还剩多少天
              $now=time();
              if($invest->investment_start+24*60*60-$now>=0){
                $spantime=floor(($invest->investment_start+24*60*60-$now)/(24*3600));
              }else{
                    $spantime=-1;
              }
              $resault_array[$k]['apply_id'] = $v->apply_id;
              $resault_array[$k]['investment_id'] = $invest->investment_id;
              $resault_array[$k]['investment_name'] = $invest->investment_name;
              $resault_array[$k]['investment_status'] = $invest->investment_status;
              $resault_array[$k]['project_id'] = $invest->project_id;
              $resault_array[$k]['investment_address'] = $province.$city.$invest->investment_address;
              $resault_array[$k]['investment_start'] = date('Y.m.d',$invest->investment_start);
              $resault_array[$k]['investment_end'] = date('Y.m.d',$invest->investment_end);
              $resault_array[$k]['spantime'] = $spantime;
              $resault_array[$k]['investment_logo'] = URL::imgurl($invest->investment_logo);
              //招商简介
              $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($invest->investment_details, 0)));
              $details = mb_strimwidth(strip_tags($string), 0, 65, "......");
              $resault_array[$k]['investment_details'] = Text::limit_chars($details,60);
              $resault_array[$k]['investment_apply'] = $invest->investment_apply;//招商会报名人数
              }
          }
        $array['list']= $resault_array;
        $array['page']= $page;
        return $array;
    }

    /**
     * 个人搜索招商会
     * @author 潘宗磊
     * @return array
     */
    public function searchInvestment($form){
        $model = DB::select()->from('project_investment');
        $model->join('project','left')->on('project.project_id','=','project_investment.project_id');
        $model->join('project_industry','left')->on('project_industry.project_id','=','project.project_id');
        //对主表筛选
        $Projectinvestmodel= ORM::factory('Projectinvest');
        $search_row = $Projectinvestmodel->getSearchRow();
        if(!empty($form['investment_start'])){
            $form['investment_start']=strtotime($form['investment_start']);
        }
        if(!empty($form['investment_end'])){
            $form['investment_end']=strtotime($form['investment_end']);
        }
        foreach($search_row as $key => $value){
            if(isset($form[$key]) AND $form[$key] != ''){
                if($key=='investment_start'){
                    $model->where('investment_start', '>=', $form[$key]);
                }elseif($key=='investment_end'){
                    $model->where('investment_start', '<=', $form[$key]);
                }else{
                    $model->where($key, '=', $form[$key]);
                }
            }
        }
        if(!empty($form['project_industry_id'])){
            $model->where('project_industry.industry_id','=',$form['project_industry_id']);
        }
        elseif(!empty($form['parent_id'])){
            $model->where('project_industry.industry_id','=',$form['parent_id']);
        }

        $model->where('project.project_status','=',2);

        $now=time();
        $model->where('investment_start','>=',$now)->where('investment_status','=',1)->order_by('investment_start','asc')->group_by("project_investment.project_id");
        $count = $model->execute()->count();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 6,
        ));
        $array=array();
        $array['list'] = $model->limit($page->items_per_page)->offset($page->offset)->execute()->as_array();
        $array['page']= $page;
        $array['count']= $count;
        return $array;
    }

    /**
     * 返回所需要的格式输出
     * @author 潘宗磊
     */
    public function getResaultList($result,$userid=''){
        $resault_array = array();
        $spantime="";
        if(isset($result)){
        foreach ($result as $k=>$r){
            if($r['project_id']){
                $now=time();
                $city='';
                $city_array = array();
                $province_array = array();
                $start = array();
                $end = array();
                $investes = ORM::factory('Projectinvest')->where('project_id', '=', $r['project_id'])->where('investment_start','>=',$now)->where('investment_status','=',1)->find_all();
                foreach ($investes as $key=>$v){
                    $investment_province = ORM::factory("City",$v->investment_province)->cit_name;
                    //判断是否是直辖市
                    if($investment_province=='北京'||$investment_province=='上海'||$investment_province=='天津'||$investment_province=='重庆'||$investment_province=='香港'){
                        if(!in_array($investment_province,$province_array)){
                            $city.=$investment_province.'市  、';
                        }
                        $province_array[] = $investment_province;
                    }else{
                        if(!in_array($v->investment_city,$city_array)){
                            $str=ORM::factory("City",$v->investment_city)->cit_name;
                            //截取最后一个中文字符
                            $city.=$str.' 、';
                        }
                        $city_array[] = $v->investment_city;
                    }
                    $start[$key]=$v->investment_start;
                    $end[$key]=$v->investment_end;
                }
                //距离招商会报名时间还剩多少天
                $investNum = ORM::factory('Projectinvest')->where('project_id', '=', $r['project_id'])->where('investment_start','>=',$now)->where('investment_status','=',1)->count_all();
                if($investNum>1){
                    $start = empty($start)?0:min($start);
                    $resault_array[$k]['investment_address'] = mb_substr($city,0,mb_strlen($city,'utf-8')-1,'utf-8');
                    $resault_array[$k]['investment_start'] = date('Y.m.d',$start);
                    $resault_array[$k]['investment_end'] = date('Y.m.d',empty($end)?0:max($end));
                    $spantime=floor(($start+24*60*60-$now)/(24*3600));
                }else{
                    $province=ORM::factory('City',$r['investment_province'])->cit_name;
                    $city=ORM::factory('City',$r['investment_city'])->cit_name;
                    $resault_array[$k]['investment_address'] = $province.$city.$r['investment_address'];
                    $resault_array[$k]['investment_start'] = date('Y.m.d',$r['investment_start']);
                    $resault_array[$k]['investment_end'] = date('Y.m.d',$r['investment_end']);
                    $spantime=floor(($r['investment_start']+24*60*60-$now)/(24*3600));
                }
                if(!empty($userid)){
                    $invests = ORM::factory('Projectinvest')->where('investment_status','=',1)->where('project_id','=',$r['project_id'])->find_all();
                    $isapply = "";
                    foreach ($invests as $in){
                        $applyNum = ORM::factory('Applyinvest')->where('invest_id', '=', $in->investment_id)->where('user_id','=', $userid)->count_all();
                        if($applyNum > 0){
                            $isapply=1;
                            break;
                        }
                    }
                    $resault_array[$k]['is_apply'] = $isapply;
                }
                $resault_array[$k]['investment_id'] = $r['investment_id'];
                $resault_array[$k]['project_id'] = $r['project_id'];
                $resault_array[$k]['investment_name'] = $r['investment_name'];
                $resault_array[$k]['investment_logo'] = URL::imgurl($r['investment_logo']);
                $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($r['investment_details'], 0)));
                $resault_array[$k]['investment_details'] = mb_strimwidth(strip_tags($string), 0, 65, "......");
                $resault_array[$k]['spantime'] = $spantime;
            }
            }
        }
        return $resault_array;
    }

    /**
     * 读取省级地区列表
     * @author 潘宗磊
     */
    public function getArea($pro_id=0){
        $msg = array();
        $areas=ORM::factory("City")->where("pro_id","=",$pro_id)->find_all();
        foreach ($areas as $k=>$v){
            $msg[$k]['cit_id']=$v->cit_id;
            $msg[$k]['cit_name']=$v->cit_name;
        }
        return $msg;
    }

    /**
     * 读取地区信息
     * @author 潘宗磊
     */
    public function getAreaName($area_id){
        $msg = array();
        $areas=ORM::factory("City",$area_id);
        $msg['cit_id']=$areas->cit_id;
        $msg['cit_name']=$areas->cit_name;
        return $msg;
    }

    /**
     * @sso
     * 报名招商会
     * @author 潘宗磊
     */
    public function applyInvest($form){
        $form = arr::map(array("HTML::chars"), $form);
        $validation = Validation::factory($form)->rule("apply_name", "not_empty")->rule("apply_sex", "not_empty")->rule("apply_mobile", "not_empty")
        ->rule('apply_mobile', 'regex', array(':value', '/^(12|13|14|15|18|16|17|19)\d{9}$/'))->rule("invest_id", "not_empty")->rule("is_hotel", "not_empty");
        if (!$validation->check()){
            $result = array('error' => $validation->errors('project/invest.php'),'status'=>'-1');
            return $result;
        }
        $model=ORM::factory('Applyinvest');
        foreach ($form as $k=>$v){
            $model->$k=$v;
        }
        //招商会添加时间
        $model->apply_addtime=time();
        $result=$model->create();
        //如果报名成功，更新招商会报名人数
        if($result->apply_id>0){
            $invest=ORM::factory('Projectinvest',$form['invest_id']);
            $applyNum=$invest->investment_apply+1;
            $invest->investment_apply=$applyNum;
            $invest->update();
            //如果没有完善个人基本信息，完善个人基本信息
            $service=new Service_User_Person_User();
            //获得个人基本信息
            $personinfo = $service->getPersonInfo($form['user_id']);

            //@sso 花文刚 2013-11-12
            $client = Service_Sso_Client::instance();
            $user = $client->getUserInfoById($form['user_id']);
            if($user->user_type==2){//个人用户添加活跃度by钟涛
                $ser1=new Service_User_Person_Points();
                $ser1->addPoints($user->id, 'sign_up_investment');//报名投资考察会
            }
            if($personinfo['user_person']->per_user_id==''){
                $person = ORM::factory("Personinfo");
                $person -> per_gender = $form['apply_sex'];
                $person -> per_realname = $form['apply_name'];
                $person -> per_user_id = $form['user_id'];
                $person ->create();
                Service_Sso_Client::instance()->updateMobileInfoById($form['user_id'],$form['apply_mobile']);
                //$user -> mobile = $form['apply_mobile'];
                //$user ->update();
            }
        }else{
            return false;
        }
        return $result->apply_id;
    }

    /**
     * 获取当前报名招商会个数
     * @author 潘宗磊
     */
    public function investCount($userid){
        $model = ORM::factory('Applyinvest');
        $count = $model->where('user_id', '=', $userid)->count_all();
        return $count;
    }

    /**
     * 删除我报名的招商会
     * @author 潘宗磊
     */
    public function deleteApply($applyid){
        $model = ORM::factory('Applyinvest',$applyid);
        if($model->apply_id==""){
            return false;
        }else{
            $model->apply_status=1;
            $model->update();
        }
        return true;
    }
}
