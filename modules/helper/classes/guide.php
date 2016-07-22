<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 十个引导问题
 */
class guide{

    /**
     * 问题个数
     * @var int
     */
    public static $count = 10;

    /**
     * 获取十个引导问题
     * return array
     */
    public static function getAllQuestion(){
        $questions = array();
        for ($i=1; $i<=self::$count ;$i++){
            //获取问题名称
            if(self::is_static_exists('guide',"question".$i)){
                $questions[$i]['q'] = call_user_func(array("guide","question".$i));
                //获取问题属性
                if(self::is_static_exists('guide',"attr".$i)){
                    $questions[$i]['attr'] = call_user_func(array("guide","attr".$i));
                }
            }
        }
        return $questions;
    }

    /**
     * 问题1  您想做哪种生意?
     * @return string
     */
    public static function question1(){
        return "您想做哪种生意?";
    }

    /**
     * 问题2  您想在哪里做?
     * @return string
     */
    public static function question2(){
        return "您想在哪里做?";
    }

    /**
     * 问题3  您曾经做过什么?
     * @return string
     */
   /*  public static function question3(){
        return "您现在的职业?";
    } */

    /**
     * 问题4  您打算和谁一起做生意?
     * @return string
     */
    /* public static function question4(){
        return "您打算和谁一起做生意?";
    } */

    /**
     * 问题5  您有哪些人脉关系?
     * @return string
     */
    public static function question5(){
        return "您有哪些人脉关系?";
    }

    /**
     * 问题6  您想做什么行业?
     * @return string
     */
    public static function question6(){
        return "您想做什么行业?";
    }

    /**
     * 问题7  您准备了多少钱做生意?
     * @return string
     */
    public static function question7(){
        return "您准备投资多少钱?";
    }

    /**
     * 问题8  您希望年赚多少钱?
     * @return string
     */
    /* public static function question8(){
        return "您希望年回报率为多少?";
    } */

    /**
     * 问题9  您想什么时候做生意?
     * @return string
     */
    /* public static function question9(){
        return " 您想什么时候做生意?";
    } */

    /**
     * 问题10  您对生意的期盼?
     * @return string
     */
    public static function question10(){
        return "您对生意的预期?";
    }

    /**
     * 问题1属性
     * @return array
     */
    public static function attr1(){
        return array(
                '1' => "开店加盟",
                '2' => "批发代理",
                '3' => "网上销售",
        );
    }

    /**
     * 问题3属性
     * @return array
     */
    public static function attr3(){
        return array(
                '1' => "公务员",
                '2' => "上班族",
                '3' => "做生意1-3年的生意人",
                '4' => "做生意3年以上的生意人",
                '5' => "赋闲在家",
                '6'  => "其他职业"
        );
    }

    /**
     * 问题4属性
     * @return array
     */
    public static function attr4(){
        return array(
                '1' => "自己一个人",
                '2' => "和家人一起",
                '3' => "和朋友合伙",
        );
    }

    /**
     * 问题5属性
     * @return array
     */
    public static function attr5(){
        return array(
                '1' => "有企事业单位关系",
                '2' => "有政府关系",
                '3' => "有学校关系",
                '4' => "有医疗关系",
                '5' => "有团购客户",
                '6' => "无人脉关系"
        );
    }

    /**
     * 问题6属性
     * @return array
     */
    public static function attr6(){
        $industry = common::primaryIndustry(0);
        foreach ($industry as $indst){
            $types[$indst->industry_id] = $indst->industry_name;
        }
        return $types;
    }

    /**
     * 问题7属性
     * @return array
     */
    public static function attr7(){
        return array(
                '1' => "1万以下",
                '2' => "1-2万",
                '3' => "2-5万",
                '4' => "5-10万",
                '5' => "10万以上"
        );
    }

    /**
     * 问题8属性
     * @return array
     */
    public static function attr8(){
        return array(
                '1' => "10%",
                '2' => "10%-50%",
                '3' => "50%-100%",
                '4' => "100%以上"
        );
    }

    /**
     * 问题9属性
     * @return array
     */
    public static function attr9(){
        return array(
                '1' => "随时开始",
                '2' => "1-3个月开始",
                '3' => "3个月以后开始"
        );
    }

    /**
     * 问题10属性
     * @return array
     */
    public static function attr10(){
        return array(
                '1' => "低风险",
                '2' => "高回报"
        );
    }

    /**
     * 根据名称获取问题属性
     * @author 龚湧
     * @param string $name
     * @return mixed|multitype:
     */
    public static function getQuestionByName($name){
        $list = array(
            "business_type" =>1,// 您想做哪种生意?
            "work_type" =>3,// 您曾经做过什么?
            "partner_type" =>4, // 您打算和谁一起做生意?
            "contacts_type" =>5,// 您有哪些人脉关系?
            "industry_type" =>6,// 您想做什么行业?
            "capital_type" =>7,// 您准备了多少钱做生意?
            "profit_type" =>8,// 您希望年赚多少钱?
            "start_type" =>9,// 您想什么时候做生意?
            "intent_type" =>10 // 您对生意的期盼?
        );
        if(Arr::get($list, $name)){
            return call_user_func(array(__CLASS__,"attr".$list[$name]));
        }
        return array();
    }


    /**
     * 判断静态方法是否存在
     * @param string $class_name
     * @param string $static_method
     * @return boolean
     */
    public static function is_static_exists($class_name,$static_method){
        $class  = new ReflectionClass($class_name);
        if($class->hasMethod($static_method) and $class->getMethod($static_method)->isStatic()){
            return true;
        }
        return false;
    }

    /**
     * 加盟时限
     * @author 曹怀栋
     * @return array
     */
    public static function timeLimit(){
        return array(
                '1' => "随时可以",
                '2' => "3个月以内",
                '3' => "6个月以内",
                '4' => "1年以内",
                '5' => "2年以内"
        );
    }
}