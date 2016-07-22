<?php
/**
 * 导入留言数据
 * @author 龚湧
 *
 */
class Service_Import_User{

    //详情日志
    protected $detailLog = "importlog.php";
    //错误日志
    protected $errorLog = "importerror.php";

    //项目属性为空
    protected $emptyPorject = "emptyProject.php";
    //意向投资地区为空
    protected $emptyArea = "emptyArea.php";
    //意向投资行业为空
    protected $emptyIndustry = "emptyIndustry.php";

    /**
     * 编辑整理的项目对应金额
     * @var unknown
     * @author 龚湧
     */
    protected $extendProject = "data/extendproject.csv";

    /**
     * user model
     * @var unknown
     * @author 龚湧
     */
    private $modelUser=null;

    /**
     * model user_person
     * @var unknown
     * @author 龚湧
     */
    private $modelUserPerson=null;

    /**
     * 个人意向投资行业
     * @var unknown
     * @author 龚湧
     */
    private $usePersonalIndustry=null;

    /**
     * 个人投资意向地区
     */
    private $userPersonalArea = null;

    public function __construct(){
        $this->modelUser = ORM::factory("User");
        $this->modelUserPerson = $this->modelUser->user_person;
        $this->usePersonalIndustry = ORM::factory("UserPerIndustry");
        $this->userPersonalArea = ORM::factory("PersonalArea");
    }

    /**
     * 创建用户基本信息和基本附属信息表，重要信息，投资金额
     * @return unknown
     * @author 龚湧
     */
    public function createUserBisc($user){
        //1.检测用户电子邮件是否已经注册
        if(!$this->modelUser->check_email($user['email'])){
            $text = $user['email']."#已注册";
            $this->writeLog($this->errorLog,$text);
            Minion_CLI::write($text);
            return "email";
        }
        //2.检测手机号码是否已经绑定
        $this->modelUser->where("mobile", "=", common::encodeMoible($user['mobile']))
                ->where("valid_mobile" ,"=" ,1)
                ->find();
         if($this->modelUser->mobile){
             $text = $user['mobile']."#已绑定";
            $this->writeLog($this->errorLog,$text);
            Minion_CLI::write($text);
            return 'mobile';
         }

         //导入正确的基本信息
         $obj = $this->modelUser;
         $obj->user_name = mb_convert_encoding($user['full_name'],"UTF-8","gb2312");//用户名称
         $obj->mobile = $user['mobile'];//手机号码
         $obj->user_type = 2;//用户类型，为个人用户
         $obj->email = $user['email'];//用户邮件
         $obj->valid_email = 1;//通过邮件验证
         $obj->valid_mobile = 1;//通过手机验证
         $obj->reg_time = $user['create_time'];//用户注册时间
         $obj->source_type = 1;//用户类型，从留言导入
         // 写入文件记录，保存用户名和密码
         $password = mt_rand(100000, 999999);//六位数密码
         $logFile = "data".DIRECTORY_SEPARATOR."password.csv";
         $newLogFile = "data".DIRECTORY_SEPARATOR."new_password.csv";
         $obj->password = sha1($password.$user['email']);//用户默认密码

         //初始化线上信息
         $api = new Service_Api_Word();
         //更具用户名称，判断是男 是女
         $user_name = mb_convert_encoding($user['full_name'],"UTF-8","gb2312");
         $gender = $api->getGenderType($user_name);
         Minion_CLI::write("性别".$gender);

         //根据项目名称获取项目信息   进行一些替换操作
         $project_name = mb_convert_encoding($user['project_name'],"UTF-8","gb2312");
         $project_name = str_replace("_", "", $project_name);
         $project_name = str_replace(array(1,2,3,4,5,6), "", $project_name);
         $project_name = str_replace("项目", "", $project_name);
         $project_info = $api->rungetProjectInfoLikeName($project_name);
         $project_info = json_decode($project_info,true);
         $project_data = Arr::get($project_info,"data");

         //TODO 项目金额，从列表中获取
         $array_project_list = array();
         $base_dir = APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;
         $extendproject = $base_dir.$this->extendProject;
         $extends = file_get_contents($extendproject);
         $extends = explode("\r\n", $extends);
         foreach ($extends as $extend){
             if(trim($extend)){
                 $ud = explode(",", $extend);
                 $array_project_list[$ud[0]] = $ud[1];
             }
         }
         unset($extends);
         //print_r($array_project_list);exit();

         //3.模糊搜索项目，获取项目属性  {投资金额}
         Minion_CLI::write("项目名称-".$project_name);
         if(!empty($project_data)){
             $per_amount = $project_data['amount_type'];//投资区间
         }
         //从列表中获取
         elseif(array_key_exists($user['project_name'], $array_project_list)){
             $per_amount = $array_project_list[$user['project_name']];
         }
         else{
             //记录错误日志
             $text = $user['email']."#获取项目属性为空";
             $this->writeLog($this->emptyPorject,$user['mobile'].",".$user['email'].",".$user['project_name']);
             Minion_CLI::write($text."--".memory_get_usage()/(1024*1024));
             return "emptyProject";
         }

         //更具行业，获取意向投资行业id
         $ist_industry = mb_convert_encoding($user['ist_industry'],"UTF-8","gb2312");
         $industies = $api->rungetindustryid($ist_industry);
         $industies = json_decode($industies,true);
         $industies_data = Arr::get($industies,"data");
         //4.分词获取行业，返回行业id
         if(!empty($industies_data)){
         }else{
             $text = $user['email']."#意向投资行业为空";
             $this->writeLog($this->emptyIndustry,$user['mobile'].",".$user['email'].",".$user['project_name']);
             Minion_CLI::write($text."--".memory_get_usage()/(1024*1024));
             return "emptyIndustry";
         }

         //更具电话和 地址信息，获取意向投资地区
         $user_adress = mb_convert_encoding($user['address'],"UTF-8","gb2312");
         $areas = $api->rungetProAreaInfo($user_adress,$user['mobile']);
         $areas = json_decode($areas,true);
         $areas_data = Arr::get($areas,"data");
         //5.意向投资地区信息
         if($areas_data){
             //var_dump($areas_data);
         }
         else{
             $text = $user['email']."#意向投资地区为空";
             $this->writeLog($this->emptyArea,$user['mobile'].",".$user['email'].",".$user['project_name']);
             Minion_CLI::write($text."--".memory_get_usage()/(1024*1024));
             return "emptyArea";
         }

         try{
             $obj->create();
             //记录日志
             $this->writeLog($logFile,$user['email'].",".$password);
             //新导入的2000多条
             $this->writeLog($newLogFile,$user['email'].",".$password);
             //初始化附属信息
             $objBasic = $obj->user_person;
             $objBasic->per_user_id = $obj->user_id;//用户id
             $objBasic->per_gender = $gender;//用户性别 接口获取 [TODO]
             $objBasic->per_realname = mb_convert_encoding($user['full_name'],"UTF-8","gb2312");//用户真实姓名
             $objBasic->per_adress = mb_convert_encoding($user['address'],"UTF-8","gb2312");//用户联系地址
             $objBasic->per_photo = "upload/person/default/".mt_rand(1, 50).".jpg";//用户默认头像
             $objBasic->per_open_stutas = 1;//名片公开所有
             $objBasic->per_amount = $per_amount;//TODO 投资金额接口
             try{
                 $objBasic->create();
                // 1.导入意向投资地区信息 TODO
                $touziMobel = ORM::factory ( "PersonalArea" );
                $touziMobel->per_id = ( int ) $obj->user_id;
                $touziMobel->area_id = $areas_data['city_id'];
                $touziMobel->pro_id = $areas_data['pro_id'];
                try {
                    $touziMobel->create ();
                } catch ( Kohana_Exception $e ) {
                    print_r ( $e->getMessage () ); // exit();
                }
                 //2.导入意向投资行业 //TODO
                 foreach($industies_data as $dit){
                     $touziInsutry = ORM::factory("UserPerIndustry");
                     $touziInsutry->user_id = $obj->user_id;
                     $touziInsutry->industry_id = $dit['industry_id'];
                     $touziInsutry->parent_id = $dit['parent_id'];
                     try{
                         $touziInsutry->create();
                     }
                     catch(Kohana_Exception $e){
                         print_r($e->getMessage());//exit();
                     }
                 }
                 //3.初始化活跃度
                 $api->addHuoyuedu($obj->user_id);

                 $text = $user['email']."写入成功";
                 $this->writeLog($this->detailLog,$text);
                 Minion_CLI::write($text."--".memory_get_usage()/(1024*1024));
             }catch(Kohana_Exception $e){
                 //echo $e->getMessage();
                 return "basic";//附属信息出错
             }
         }catch(Kohana_Exception $e){
             return "user";//用户基本信息
         }
         unset($objBasic);
         return $obj;
    }

    /**
     * 个人投资行业基本信息
     * @param ORM $user
     * @author 龚湧
     */
    public function createUserIndustry($user_id){
    }

    /**
     * 个人投资意向地区
     * @param unknown $user_id
     * @author 龚湧
     */
    public function createUserArea($user_id){

    }

    /**
     * 写入日志 TODO 待完善
     * @param unknown $logFile
     * @param string $text
     * @author 龚湧
     */
    protected function writeLog($logFile,$text=""){
        $base_dir = APPPATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;
        $handle = fopen($base_dir.$logFile, "ab+");
        fwrite($handle, $text.PHP_EOL);
        fclose($handle);
    }
}