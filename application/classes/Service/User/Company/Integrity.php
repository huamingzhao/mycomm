<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户诚信指数service
 * @author 龚湧
 *
 */
class Service_User_Company_Integrity{

    /**
    * 更新总数
    * @author 龚湧
    * @param unknown_type $user_id
    * @param unknown_type $is_plus
    * @param unknown_type $value
    * @return boolean
    */
    protected function updateUserIty($user_id,$is_plus=1,$value=0){
        $user = ORM::factory("User",$user_id);
        if($is_plus){
            $user->total_integrity = $user->total_integrity + $value;
        }
        else{
            if(($user->total_integrity - $value) >0){
                $user->total_integrity = $user->total_integrity - $value;
            }
            //诚信点为负值了，不进行更新
            else{
                return false;
            }
        }
        try{
            $user->save();
        }catch (Kohana_Exception $e){
            return false;
        }
        return true;
    }

    /**
    * 更具别名获取类型详情
    * @author 龚湧
    * @param string $alias
    * @return boolean|Ambigous <ORM, Database_Result, Kohana_ORM, object, mixed, number, Database_Result_Cached, multitype:>
    */
    public function getIntegrityByName($alias){
        $Ity = ORM::factory("IntegrityType")
               ->where("alias","=",$alias)
               ->find();
        if(!$Ity->id){
            return false;
        }
        return $Ity;
    }

    /**
     * 一次性信用值  如邮件、手机等用过验证
     * @author 龚湧
     */
    public function getIntegrityOnce($user_id,$alias,$desc=""){
        $ity = $this->getIntegrityByName($alias);
        if(!$ity){
            return false;
        }

        // 检查是否已经获取了该类型诚信值
        $model = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id)
        ->where("type_id","=",$ity->id)
        ->find();
        if($model->id){
            return false;
        }

        //创建新纪录
        $model->user_id = $user_id;
        $model->type_id = $ity->id;
        $model->is_repeat = 0;
        $model->is_plus = 1;
        $model->add_time = time();
        $model->value = $ity->value;
        $model->desc = $ity->desc;
        try{
            $model->save();
            $this->updateUserIty($user_id,1,$ity->value);
        }catch(Kohana_Exception $e){
            throw $e;
        }
        return true;
    }

    /**
     * 特殊规则
     * 充值 获取诚信点 累计规则，充值
     * @author 龚湧
     */
    public function getIntegrityByCharge($user_id,$desc=''){
        $ity = $this->getIntegrityByName("charge");
        $id = $ity->id;
        if(!$ity){
            return false;
        }
        //当前充值获取的总诚信点数
        $model = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id)
        ->where("type_id","=",$id)
        ->find_all();
        $total_count = 0;
        if($model->count()){
            foreach($model as $itys){
                $total_count += $itys->value;
            }
        }
        //获取当前充值总金额,是否增加诚信点
        $service_account = Service::factory("Account");
        $total_recharge = $service_account->getAccountTotalRecharge($user_id);
        //规则
        $get = 0;
        if($total_recharge>=1000 and $total_recharge<5000){
            $get = 200;
            $get = $get-$total_count;
        }
        elseif($total_recharge>=5000 and $total_recharge<20000){
            $get = 400;
            $get = $get-$total_count;
        }
        elseif($total_recharge>=20000 and $total_recharge<=100000){
            $get = 600 + (floor(($total_recharge-20000)/20000))*200;
            $get = $get-$total_count;
        }
        elseif($total_recharge>100000){
            $get = 1400 + (floor(($total_recharge-100000)/50000))*200;
            $get = $get-$total_count;
        }
        //增加累计诚信点
        if($get > 0){
            $model = ORM::factory("CompanyIntegrity");
            $model->user_id = $user_id;
            $model->type_id = $id;
            $model->is_repeat = 1;
            $model->is_plus = 1;
            $model->add_time = time();
            $model->value = $get;
            $model->desc = $ity->desc;
            try{
                $model->save();
                $this->updateUserIty($user_id,1,$get);
            }catch(Kohana_Exception $e){
                //throw $e;
                return false;
            }
            return $get;
        }
        else{
            return false;
        }
    }

    /**
     * 特殊规则
     * 完善项目图片 30点封顶
     * @author 龚湧
     */
    public function getIntegrityByProjectImage($user_id,$desc){
        $ity = $this->getIntegrityByName("valid_project_image");
        $id = $ity->id;
        if(!$ity){
            return false;
        }

        //获取项目获取图片的点数
        $model = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id)
        ->where("type_id","=",$id)
        ->find_all();
        $total_count = 0;
        if($model->count()){
            foreach($model as $itys){
                $total_count += $itys->value;
            }
        }
        //超过了最高值
        if($total_count >= 30){
            return false;
        }
        else{
            //创建新纪录
            $model = ORM::factory("CompanyIntegrity");
            $model->user_id = $user_id;
            $model->type_id = $id;
            $model->is_repeat = 1;
            $model->is_plus = 1;
            $model->add_time = time();
            $model->value = $ity->value;
            $model->desc = $desc;
            try{
                $model->save();
                $this->updateUserIty($user_id,1,$ity->value);
            }catch(Kohana_Exception $e){
                throw $e;
            }
            return true;
        }
    }

    /**
     * //特殊规则
     * 完善项目资质图片 60点封顶
     * @author 龚湧
     */
    public function getIntegrityByQualificationImage($user_id,$desc){
        $ity = $this->getIntegrityByName("pass_qualification");
        $id = $ity->id;
        if(!$ity){
            return false;
        }

        //获取项目资质图片的点数
        $model = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id)
        ->where("type_id","=",$id)
        ->find_all();
        $total_count = 0;
        if($model->count()){
            foreach($model as $itys){
                $total_count += $itys->value;
            }
        }
        //超过了最高值
        if($total_count >= 60){
            return false;
        }
        else{
            //创建新纪录
            $model = ORM::factory("CompanyIntegrity");
            $model->user_id = $user_id;
            $model->type_id = $id;
            $model->is_repeat = 1;
            $model->is_plus = 1;
            $model->add_time = time();
            $model->value = $ity->value;
            $model->desc = $desc;
            try{
                $model->save();
                $this->updateUserIty($user_id,1,$ity->value);
            }catch(Kohana_Exception $e){
                throw $e;
            }
            return true;
        }
    }

    /**
     * 时间段内诚信点数
     * @author 龚湧
     * @param unknown_type $user_id
     * @param unknown_type $add_time
     */
    public function getIntegrityByTime($user_id,$id=0,$time=0){
        //默认时间为当前时间
        if($time === 0){
            $time = time();
        }
        //用户当前所有积分类型
        $itys = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id)
        ->find_all();
        //有记录
        $total_points = 0;
        if($itys->count()){
            foreach($itys as $ity){
                if($ity->add_time < $time){//判断与上次时间点是否相同
                    //增加诚信点
                    if($ity->is_plus){
                        $total_points += (int)$ity->value;
                    }
                    //扣除诚信点
                    else{
                        $total_points -= (int)$ity->value;
                    }
                }
                else{
                    continue;
                }
            }
            //补丁
            if($id !== 0){
	            foreach($itys as $ity){
	            	if($ity->id <= $id and $ity->add_time == $time){//判断与上次时间点是否相同
	            		//增加诚信点
	            		if($ity->is_plus){
	            			$total_points += (int)$ity->value;
	            		}
	            		//扣除诚信点
	            		else{
	            			$total_points -= (int)$ity->value;
	            		}
	            	}
	            	else{
	            		continue;
	            	}
	            }
            }
        }
        return $total_points;
    }

    public function getIntegrityLevel($user_id){
        //总诚信值
        $total_itys = $this->getIntegrityByTime($user_id);
        //诚信等特殊级规则
        $itys_rule_p = array (
                1 => array (
                        'min' => 0,
                        'max' => 149
                ),
                2 => array (
                        'min' => 150,
                        'max' => 349
                ),
                3 => array (
                        'min' => 350,
                        'max' => 574
                )
        );
        //1级到3级
        if($total_itys<=574){
            foreach($itys_rule_p as $level=>$rule_p){
                if($rule_p['min']<=$total_itys and $rule_p['max']>=$total_itys){
                    break;
                }
            }
            if($level==3){
                $next_short = 575 - $total_itys;
            }
            else{
                $next_short = $itys_rule_p[$level+1]['min'] - $total_itys;
            }
        }
        //最高等级9级
        elseif($total_itys>=1950){
            $level = 9;
            $next_short = null;
        }
        //公式计算其他等级
        else{
            $level = (int)floor(sqrt(($total_itys-350)/25)+1);
            $next_short = 25*($level*$level) - $total_itys + 350;
        }
        return array("level"=>$level,"next_short"=>$next_short);
    }

    /**
     * 诚信获取来源列表
     * @author 龚湧
     * @param int $user_id
     * @param array $search 筛选参数
     */
    public function getList($user_id,$search){
        //筛选条件
        $list = ORM::factory("CompanyIntegrity")
        ->where("user_id","=",$user_id);
        //时间点筛选
        if(Arr::get($search, "from_year") and Arr::get($search, "from_month") and Arr::get($search, "to_year") and Arr::get($search, "to_month")){
            $from_time = mktime(0,0,0,$search['from_month'],1,$search['from_year']);
            $to_time = mktime(0,0,0,$search['to_month']+1,0,$search['to_year']);
            $list = $list->where("add_time",">=",$from_time)
            ->where("add_time","<=",$to_time);
        }

        //总记录数量
        $total_count = $list->reset(false)->count_all();
        //分页条件
        $page = Pagination::factory(
                array(
                        'total_items'    =>$total_count,
                        'items_per_page' => 10,
                )
        );
        $list_obj = $list->limit($page->items_per_page)
        ->offset($page->offset)
        ->order_by("id","DESC")
        ->find_all();
        //转换显示结果
        $list = array();
        if($total_count){
            foreach($list_obj as $key=>$l){
                $list[$key] = $l->as_array();
                $list[$key]['total_count'] = $this->getIntegrityByTime($l->user_id,$l->id,$l->add_time);
            }
        }
        return array(
                'page'=>$page,
                'list'=>$list,
                'total_count'=>$total_count
        );
    }
}