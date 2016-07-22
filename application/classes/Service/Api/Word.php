<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 调用controller
 * @author 许晟玮
 *
 */
class Service_Api_Word extends Service_Api_Basic{
    protected $_runurl= 'http://www.yijuhua.net/sapi/platform/word/';
    //protected $_runurl= 'http://www.myczzs.com/sapi/platform/word/';

    /**
     * 传入字符串,进行分词操作
     * @author许晟玮
     */
    public function rungetParticiple($word){

        $result= file_get_contents( $this->_runurl."getParticiple?word=".$word );


        return $result;
    }
    //end function

    /**
     * 传入项目名称,获取对应的所有信息(模糊搜索)
     * @author许晟玮
     */
    public function rungetProjectInfoLikeName($name){
        $result= file_get_contents( $this->_runurl."getProjectInfoLikeName?name=".urlencode($name) );

        return $result;
    }
    //end function

    /**
     * 通过分词搜索关键词表,获取对应的行业信息(ID)
     * @author许晟玮
     */
    public function rungetindustryid($word){
        $result= file_get_contents( $this->_runurl."getindustryid?word=".urlencode($word) );

        return $result;
    }
    //end function

    /**
     * 地区获取ID
     * @author许晟玮
     */
    public function rungetProAreaInfo($address,$tel){
        $result= file_get_contents( $this->_runurl."getProAreaInfo?address=".urlencode($address)."&tel=".$tel );

        return $result;
    }
    //end function

    /**
     * 通过姓名返回类型 1-男(先生) 2-女(小姐 女士)
     * @author许晟玮
     * @param unknown $name
     */
    public function getGenderType($name){
        $result= strpos($name,'先生');
        if( $result===false ){
            $result= strpos($name,'女士');
            if( $result===false ){
                $result= strpos($name,'小姐');
                if( $result===false ){
                    return '1';
                }else{
                    return '2';
                }
            }else{
                return '2';
            }
        }else{
            return '1';
        }

    }
    //end function

    /**
     * 根据项目名称返回项目信息( 模糊搜索 )
     * @author 许晟玮
     */
    public function getPidLikePname($project_brand_name){
        $project = ORM::factory('Project')->where('project_brand_name','like','%'.$project_brand_name.'%')->where('project_status','=',2)->find_all();

        $num = ORM::factory('Project')->where('project_brand_name','like','%'.$project_brand_name.'%')->where('project_status','=',2)->count_all();

        $info= array();
        $info['info']= $project;
        $info['num']= $num;
        return $info;
    }

    /**
     * 个人添加活跃度[邮箱和手机]
     * @author 钟涛
     */
    public function addHuoyuedu($userid){
        $userid=intval($userid);
        if($userid){
             $ser=new Service_User_Person_Points();
             $ser->addPoints($userid, 'valid_email');//邮箱通过验证--添加活跃度
             $ser->addPoints($userid, 'valid_mobile');//手机通过验证--添加活跃度
        }
    }
}
