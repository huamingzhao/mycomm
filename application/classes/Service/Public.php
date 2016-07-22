<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 基础服务
 * @author 曹怀栋
 *
 */
class Service_Public{
    /**
     * 通过城市id取得省份id
     * @author 钟涛
     * @return string
     */
    public function getProidByCityid($cityid){//88表示全国
        $area = ORM::factory('city')->where('cit_id','=',$cityid)->find();
        if($area->cit_id !=""){
            return $area->pro_id;//返回省份id
        }else{
            return false;
        }
    }

    /**
     * 通过地区id取得地区名
     * @author 曹怀栋
     * @param int $user_id
     * @return string
     */
    public function getAreaName($area_id=88){//88表示全国
        $area = ORM::factory('city',intval($area_id));
        if($area->cit_name !=""){
            return $area->cit_name;
        }else{
            return false;
        }
    }
    /**
     * 通过输入的金额确定其金额的范围
     * @author 曹怀栋
     *'1' => '0-5万',
     *'2' => '5-10万',
     *'3' => '10-20万',
     *'4' => '20-30万',
     *'5' => '30-50万',
     *'6' => '50万以上',
     * @return int
     */
    public function getMoneyRange($str){
        if(is_numeric($str)){//数字
            if($str < 50000){
                return 1;
            }elseif($str >= 50000 && $str <= 100000){
                return 2;
            }elseif($str > 100000 && $str <= 200000){
                return 3;
            }elseif($str > 200000 && $str <= 300000){
                return 4;
            }elseif($str > 300000 && $str <= 500000){
                return 5;
            }else{
                return 6;
            }
        }else{//字符串
            $sus = strpos($str, "万元");
            if($sus !== false){
                if($str < 5){
                    return 1;
                }elseif($str >= 5 && $str <= 10){
                    return 2;
                }elseif($str > 10 && $str <= 20){
                    return 3;
                }elseif($str > 20 && $str <= 30){
                    return 4;
                }elseif($str > 30 && $str <= 50){
                    return 5;
                }else{
                    return 6;
                }
            }
            $suss = strpos($str, "万");
            if($suss !== false){
                if($str < 5){
                    return 1;
                }elseif($str >= 5 && $str <= 10){
                    return 2;
                }elseif($str > 10 && $str <= 20){
                    return 3;
                }elseif($str > 20 && $str <= 30){
                    return 4;
                }elseif($str > 30 && $str <= 50){
                    return 5;
                }else{
                    return 6;
                }
            }
            $sussec = strpos($str, "元");
            if($sussec !== false){
                if($str < 50000){
                    return 1;
                }elseif($str >= 50000 && $str <= 100000){
                    return 2;
                }elseif($str > 100000 && $str <= 200000){
                    return 3;
                }elseif($str > 200000 && $str <= 300000){
                    return 4;
                }elseif($str > 300000 && $str <= 500000){
                    return 5;
                }else{
                    return 6;
                }
            }
        }
    }

    /**
     * 根据 id获取行业名称
     * @param unknown $id
     * @author 龚湧
     */
    public function getIndustryNameById($id){
        if(!is_numeric($id)){
            return false;
        }
        $obj = ORM::factory("Industry",$id);
        if($obj){
            return $obj->industry_name;
        }
        return false;
    }

    /**
     * 取二级行业兄弟,顶级行业返回为空
     * @param unknown $id
     * @author 龚湧
     */
    public function getIndustryBrother($id){
        $brothers = array();
        if(!is_numeric($id)){
            return false;
        }
        $obj = ORM::factory("Industry",$id);
        if($obj->loaded()){
            if($obj->parent_id != 0){
                $objs = ORM::factory("Industry")
                            ->where("parent_id", "=", $obj->parent_id)
                            ->find_all();
                foreach($objs as $brother){
                    $brothers[$brother->industry_id] = $brother->industry_name;
                }
            }
        }
        return $brothers;
    }

    /**
     * 更具招商会id 获取访问总次数   一个小时更新一次 TODO 后期跑脚本
     * @author 龚湧
     * @param unknown $invest_id
     */
    public function getInvestVisitCount($invest_id,$hour=1){
        //上一个小时
        $tody = getdate();
        //当前时间前一个小时
        $lasthour = mktime($tody['hours']-$hour,0,0,$tody['mon'],$tody['mday'],$tody['year']);

        try{
            $cache_id = "InvestVisitCount_".$invest_id."_".$lasthour;
            $cache = Cache::instance("memcache");
            if($cache->get($cache_id)){
                return (int)$cache->get($cache_id);
            }
            else{
                $count = ORM::factory("BiVisitList")
                        ->where("pnid","=",$invest_id)
                        ->where("typeid","=",2)
                        ->count_all();
                $cache->set($cache_id, $count);
                return $count;
            }
        }
        catch (Kohana_Exception $e){
            return 0;
        }
    }

    /**
     * 获取倒计时 输入秒数换算成多少天/多少小时/多少分/的字符串
     * @author 花文刚
     * @param $second 秒
     */
    public function getCountdown($second, $begin ='', $end = ''){
        $begin = $begin ? $begin : '<span class="black">';
        $end = $end ? $end : '</span>';
        $day = floor($second/(3600*24));
        $second = $second%(3600*24);//除去整天之后剩余的时间
        $hour = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        $second = $second%60;
        $arr = array(
            $day, '天', $hour, '时', $minute, '分'
        );
        $return = '';
        foreach($arr as $key => $val) {
            if($key == 0 || $key ==2 || $key == 4){
                $return .= $begin.$val.$end;
            }else{
                $return .= $val;
            }
        }
        return $return;
    }

}