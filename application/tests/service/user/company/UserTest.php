<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 钟涛
 * TestUser test case.
 */
class Service_User_Company_UserTest extends Unittest_TestCase {

    protected  $service;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service=new Service_User_Company_User();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 测试用户id=36返回0个项目信息（数据库中用户id36没有通过的项目）
     * @author 钟涛
     */
    public function test_findProjectInfo(){
        //测试用户id=36
        $userid=36;
        $result=$this->service->findProjectInfo($userid)->get('com_id');
        //返回0个项目
        $this->assertEquals(0,count($result));
    }

    /**
     * 测试我的企业项目是否返回null
     * @author 钟涛
     */
    public function test_findProjectInfo_isnull(){
        //测试用户id=36
        $userid=36;
        $result=$this->service->findProjectInfo($userid);
        //返回结果是否为null
        $this->assertNotNull($result);
    }

    /**
     * 测试用户已通过邮箱验证情况
     * @author 钟涛
     */
    public function test_getEmailValidCount(){
        //测试用户id=36（此账号邮箱已通过验证）
        $userid=36;
        $result=$this->service->getEmailValidCount($userid);
        //预期结果1 与返回结果想对比
        $this->assertEquals(1,$result);
    }

    /**
     * 测试邮箱验证方法是否返回null
     * @author 钟涛
     */
    public function test_getEmailValidCount_notnull(){
        //测试用户id=36
        $userid=36;
        $result=$this->service->getEmailValidCount($userid);
        //返回结果是否为null
        $this->assertNotNull($result);
    }

    /**
     * 测试用户邮箱验证情况
     * @author 钟涛
     */
    public function test_getEmailValidCount_no(){
        //测试用户id=35
        $userid=35;
        $result=$this->service->getEmailValidCount($userid);
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual(0,$result);
    }

    /**
     * 测试企业资质验证方法是否返回null
     * @author 钟涛
     */
    public function test_getCompanyAuthCount_notnull(){
        //测试用户id=36
        $userid=36;
        $result=$this->service->getCompanyAuthCount($userid);
        //返回结果是否为null
        $this->assertNotNull($result);
    }

    /**
     * 测试用户没有通过企业资质验证情况
     * @author 钟涛
     */
    public function test_getCompanyAuthCount_no(){
        //测试用户id=36（此账号企业资质未通过验证）
        $userid=36;
        $result=$this->service->getCompanyAuthCount($userid);
        //预期结果 0 与返回结果想对比
        $this->assertEquals(0,$result);
    }

    /**
     * 检查邮件验证的邮件是否发送成功
     * @author 周进
     * 断言邮箱不传时返回为-2
     */
    public function test_updateCheckValidEmail(){
        $user = $this->service;
        $result = $user->updateCheckValidEmail('34','');
        $this->assertEquals('-2', $result['status']);
    }

    /**
     * 企业基本信息管理
     * @author 周进
     * 断言$post数据不全是返回-1，
     * 数据正确时返回为1
     */
    public function test_updateCompanyBasic(){
        $user = $this->service;
        $files['com_logo']['name']='';
        $post = array('com_nature'=>'2');
        $result = $user->updateCompanyBasic($files, $post, '34','测试公司名');
        $this->assertEquals(-1, $result['status']);
        $post = array('com_nature'=>'2','com_site'=>url::website(''),
                'com_phone'=>'15000834760','com_adress'=>'联行路','com_contact'=>'张先生');
        $result = $user->updateCompanyBasic($files, $post, '34','测试公司名');
        $this->assertEquals(1, $result['status']);
    }

    /**
     * 标签表的相关取值，传入序列化数据
     * @author 周进
     * 断言返回的是ORM类型,返回数据位2条
     */
    public function test_findTag(){
        $user = $this->service;
        $result = $user->findTag('a:2:{i:0;s:1:"1";i:1;s:1:"2";}');
        $this->assertGreaterThanOrEqual ('ORM', $result);
        $this->assertEquals (2, count($result));
    }

    /**
     * 断言返回结果大于或等于2
     * @author 曹怀栋
     * 招商通服务申请（1未申请，2申请中，3申请通过）
     */
    public function test_applyStatus(){
        $user = $this->service;
        $result = $user->applyStatus(1);
        //断言返回结果大于或等于2
        $this->assertGreaterThanOrEqual(2,$result);
    }

    /**
     * 测试获取投资者从业经验信息
     * 根据用户id获取用户从业经验详细信息
     * @author 钟涛
     */
    public function test_getExperienceById(){
        //测试用户ID=6
        $userid=6;
        $user = $this->service;
        $result=$user->getExperienceById(6);
        //断言用户id=6有两条从业经验
        $this->assertEquals(2,count($result));
        //断言有返回数组有键值exp_starttime 从业开始时间
        $this->assertArrayHasKey('exp_starttime', $result[0]);
        //断言有返回数组有键值exp_starttime 从业结束时间
        $this->assertArrayHasKey('exp_endtime', $result[0]);
    }

    /**
     * 测试获取投资者从业经验信息
     * 根据用户id获取用户从业经验详细信息
     * @author 钟涛
     */
    public function test_getExperienceById2(){
        //测试用户ID=5
        $userid=5;
        $user = $this->service;
        $result=$user->getExperienceById(5);
        //断言用户id=0没有从业经验
        $this->assertEquals(0,count($result));
    }


    /**
     * 测试获取企业用户邮件通知内容
     * @author 钟涛
     */
//     public function test_getCompanyCardEmailContent(){
//         $user = $this->service;
//         $per_card=new Service_User_Company_Card();
//         $personcardlist = $per_card->getReceivedCardLimit(36,2);
//         $result=$user->getCompanyCardEmailContent(36,1646466,$personcardlist,10);
//         echo $result;
//     }

    /**
     * 获取一周未登陆并且有新收到名片的企业用户
     * @author 钟涛
     */
    public function test_getNotLoginUserEmail(){
        $user = $this->service;
        $result=$user->getNotLoginUserEmail(1);
//         foreach($result as $v)
//         {
//             print_r($v->as_array()) ;
//         }
    }

    /**
     * 获取一周未登陆并且有新收到名片的企业用户
     * @author 钟涛
     */
    public function test_sendEmailNewCardInfo(){
        $user = $this->service;
        //$result=$user->sendEmailNewCardInfo(1);
        //断言发送成功
        //$this->assertEquals(1,$result[2]['status']);
    }

    /**
     * 测试获取个人用户邮件通知内容
     * @author 钟涛
     */
    public function test_getPersonCardEmailContent(){
        $user = new Service_User();
        $per_card=new Service_User_Person_Card();
        $return_arr=$per_card->twoReceiveCard(644,2);
        $cardlist= $per_card->getSerializeArrayList($return_arr['list']);

        //获取个人用户邮件通知内容
        $result = $user->getSendEmailCardContent(2,644,1646466,$cardlist,10,array(),array());
        //echo $result ;
    }

    /**
     * 测试获取企业用户邮件通知内容
     * @author 钟涛
     */
    public function test_getCompanyCardEmailContent(){
        $user = new Service_User();
        $per_card=new Service_User_Company_Card();
        $personcardlist = $per_card->getReceivedCardLimit(36,2);

        $money_list = common::moneyArr();//投资金额
        //投资行业
        $allindustry = common::primaryIndustry(0);
        $industry=array();
        foreach ($allindustry as $key=>$lv){
            $industry[$lv->industry_id] = $lv->industry_name;
        }

        //获取企业用户邮件通知内容
        $result = $user->getSendEmailCardContent(1,36,1646466,$personcardlist,10,$money_list,$industry);
        //echo $result ;
    }
}

