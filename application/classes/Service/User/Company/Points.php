<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户积分service
 * @author 龚湧
 *
 */
class Service_User_Company_Points extends Service_User_Points{
    /**
    * 获取一次性积分(每个账户只能获取一次积分,如用户注册)
    * @param $user_id 用户id
    * @param $points 积分类型详情
    * @author 龚湧
    */
    public function getPointsOnce($user_id,$type_name){
        $points = points_company_type::getByName($type_name);
        //积分类型配置
        if(!$points){
            return false;
        }
        //配置为一次性
        if($points['is_repeat'] != 0){
            return false;
        }
        // 检查是否已经获取了该类型积分
        $model = ORM::factory("Companypoints")
                 ->where("user_id","=",$user_id)
                 ->where("point_type","=",$points['id'])
                 ->find();
        //已经获取了改类型积分
        if($model->id){
            return false;
        }
        //创建新纪录
        $model->user_id = $user_id;
        $model->point_type = $points['id'];
        $model->is_repeat = $points['is_repeat'];
        $model->is_plus = $points['is_plus'];
        $model->add_time = time();
        $model->points = $points['points'];
        $model->points_desc = $points['points_desc'];
        try{
            $model->save();
        }catch(Kohana_Exception $e){
            throw $e;
        }
        return true;
    }

    /**
    * 按天获取积分(用户登录专用),置于before方法中
    * @author 龚湧
    */
    public function getPointsRepeatByDay($user_id,$type_name){
        $points = points_company_type::getByName($type_name);
        //积分类型配置
        if(!$points){
            return false;
        }
        //不为一次性
        if($points['is_repeat'] == 0){
            return false;
        }
        //当前时间段
        $date = getdate();
        $day_start = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']);
        $day_end = mktime(0,0,0,$date['mon'],$date['mday']+1,$date['year'])-1;
        //检查当天是否已经获取积分
        $model = ORM::factory("Companypoints")
                 ->where("user_id","=",$user_id)
                 ->where("point_type","=",$points['id'])
                 ->where("add_time",">=",$day_start)
                 ->where("add_time","<=",$day_end)
                 ->find();
        if($model->id){
            return false;
        }
        //创建新纪录
        $model->user_id = $user_id;
        $model->point_type = $points['id'];
        $model->is_repeat = $points['is_repeat'];
        $model->is_plus = $points['is_plus'];
        $model->add_time = time();
        $model->points = $points['points'];
        $model->points_desc = $points['points_desc'];
        try{
            $model->save();
        }catch(Kohana_Exception $e){
            throw $e;
        }
        return true;

    }

    /**
    * 普通积分获取,按照次数获取
    * @author 龚湧
    */
    public function getPointsTimes($user_id,$type_name,$cst_points=0,$cst_desc=''){
        $points = points_company_type::getByName($type_name);
        if(!$points){
            return false;
        }
        //不为一次性
        if($points['is_repeat'] == 0){
            return false;
        }

        //创建新纪录
        $model = ORM::factory("Companypoints");
        $model->user_id = $user_id;
        $model->point_type = $points['id'];
        $model->is_repeat = $points['is_repeat'];
        $model->is_plus = $points['is_plus'];
        $model->add_time = time();
        $model->points = $cst_points ? $cst_points : $points['points'];//积分自定义
        $model->points_desc = $cst_desc?$cst_desc:$points['points_desc']; //描述自定义
        try{
            $model->save();
        }catch(Kohana_Exception $e){
            throw $e;
        }
        return true;
    }

    /**
    * 消耗兑换的积分
    * @author 龚湧
    */
    public function exchangePoints(){
    }

    /**
     * 计算用户等级使用
     */
    public function getUserLevel($user_id){
        //获取用户累计积分所有积分
        $points = ORM::factory("Companypoints")
                ->where("user_id","=",$user_id)
                ->where("is_plus","=",1)
                ->find_all();
        $total_points = 0;
        if($points->count()){
            foreach($points as $point){
                $total_points += (int)$point->points;
            }
        }

        //等级规则
        $level_rule = array(
                1=>array(
                        'name'=>'heart',
                        'min'=>0,
                        'max'=>50,
                        'grade'=>1
                ),
                2=>array(
                        'name'=>'heart',
                        'min'=>51,
                        'max'=>100,
                        'grade'=>2
                ),
                3=>array(
                        'name'=>'heart',
                        'min'=>101,
                        'max'=>150,
                        'grade'=>3
                ),
                4=>array(
                        'name'=>'heart',
                        'min'=>151,
                        'max'=>200,
                        'grade'=>4
                ),
                5=>array(
                        'name'=>'star',
                        'min'=>201,
                        'max'=>400,
                        'grade'=>1
                ),
                6=>array(
                        'name'=>'star',
                        'min'=>401,
                        'max'=>600,
                        'grade'=>2
                ),
                7=>array(
                        'name'=>'star',
                        'min'=>601,
                        'max'=>800,
                        'grade'=>3
                ),
                8=>array(
                        'name'=>'star',
                        'min'=>801,
                        'max'=>1000,
                        'grade'=>4
                ),
                9=>array(
                        'name'=>'diamond',
                        'min'=>1001,
                        'max'=>2000,
                        'grade'=>1
                ),
                10=>array(
                        'name'=>'diamond',
                        'min'=>2001,
                        'max'=>3000,
                        'grade'=>2,
                ),
                11=>array(
                        'name'=>'diamond',
                        'min'=>3001,
                        'max'=>4000,
                        'grade'=>3
                ),
                12=>array(
                        'name'=>'diamond',
                        'min'=>4001,
                        'max'=>5000,
                        'grade'=>4
                ),
                13=>array(
                        'name'=>'hat',
                        'min'=>5001,
                        'max'=>10000,
                        'grade'=>1
                ),
                14=>array(
                        'name'=>'hat',
                        'min'=>10001,
                        'max'=>15000,
                        'grade'=>2
                ),
                15=>array(
                        'name'=>'hat',
                        'min'=>15001,
                        'max'=>20000,
                        'grade'=>3
                ),
                16=>array(
                        'name'=>'hat',
                        'min'=>20001,
                        'max'=>25000,
                        'grade'=>4
                ),
                17=>array(
                        'name'=>'king',
                        'min'=>'25001',
                        'grade'=>1
                )
        );

        foreach($level_rule as $level=>$rule){
            //最高等级做限制
            if($rule['min'] >= 25000){
                return array(
                        'level'=>17,
                        'rule'=>$level_rule[17],
                        'total_points'=>$total_points,
                        'next_level'=>null //无下一等级
                );
            }
            else{
                if($rule['min']<=$total_points and $rule['max']>=$total_points){
                    break;
                }
            }
        }
        $next_level = $level_rule[$level+1];
        return array(
                'level'=>$level,
                'rule'=>$rule,
                'total_points'=>$total_points,
                'next_level'=>$next_level
        );
    }

    /**
     * 获取用户可以积分,时间起点内
     * @return int
     * @author 龚湧
     */
    public function getUsablePointsByTime($user_id,$time=0){
        //默认时间为当前时间
        if($time === 0){
            $time = time();
        }
        //用户当前所有积分类型
        $points = ORM::factory("Companypoints")
        ->where("user_id","=",$user_id)
        ->find_all();
        //有记录
        $total_points = 0;
        if($points->count()){
            foreach($points as $point){
                if($point->add_time <= $time){
                    //增加积分
                    if($point->is_plus){
                        $total_points += (int)$point->points;
                    }
                    //消耗积分
                    else{
                        $total_points -= (int)$point->points;
                    }
                }
                else{
                    continue;
                }
            }
        }
        return $total_points;
    }


    /**
     * 积分列表
     * @author 龚湧
     * @param int $user_id
     * @param array $search 筛选参数
     */
    public function getList($user_id,$search){
        //筛选条件
        $list = ORM::factory("Companypoints")
                 ->where("user_id","=",$user_id);
        //积分类型筛选
        if(Arr::get($search, "point_type")){
            //账户充值类型id
            $point_type = points_company_type::getByName("account_recharge");
            $point_type = $point_type['id'];
            if($search['point_type'] == 1){//账户充值
                $list = $list->where("point_type","=",$point_type);
            }
            elseif($search['point_type'] == 2){//其他
                $list = $list->where("point_type","!=",$point_type);
            }
         }
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
                      ->order_by("add_time","DESC")
                      ->find_all();
         //装换显示结果
         $list = array();
         if($total_count){
            foreach($list_obj as $key=>$l){
                $list[$key] = $l->as_array();
                $list[$key]['total_count'] = $this->getUsablePointsByTime($l->user_id,$l->add_time);
            }
         }
        return array(
                'page'=>$page,
                'list'=>$list,
                'total_count'=>$total_count
        );
    }
}