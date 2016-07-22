<?php

/**
 * Default common
 *
 * @package    Kohana/common
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class common {
	
	/**
	 * 截取指定长度的字数
	 * @param string $string        	
	 */
	public static function truncateStr($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
		if ($length == 0)
			return '';
		if (mb_strlen ( $string, 'utf8' ) > $length) {
			$length -= mb_strlen ( $etc, 'utf8' );
			if (! $break_words && ! $middle) {
				$string = preg_replace ( '/\s+?(\w+)?$/', '', mb_substr ( $string, 0, $length + 1, 'utf8' ) );
			}
			if (! $middle) {
				return mb_substr ( $string, 0, $length, 'utf8' ) . $etc;
			} else {
				return mb_substr ( $string, 0, $length / 2, 'utf8' ) . $etc . mb_substr ( $string, - $length / 2 );
			}
		} else {
			return $string;
		}
	}

	/**
	* 快速发布经营模式
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-20
	* @return int/bool/object/array
	*/
	
	public static function puickProjectModel(){
		return array('1'=>"经销",
					'2'=>"批发",
					"3"=>"网售",
					"4"=>"代理",
					"5"=>"加盟",
					"6"=>"直营",
					"7"=>"其他");
	}
	
	/**
	* 快速发布经营模式(搜索页用)
	* @author 郁政
	* @param 	
	*/
	
	public static function puickProjectModel2(){
		return array(
					'2'=>"批发",					
					"4"=>"代理",
					"5"=>"加盟",
					);
	}
	
	/**
	* 品牌历史
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-13
	* @return int/bool/object/array
	*/
	
	public static function projectHistory(){
		return  array(	'1'=>'1年以内',
						'2'=>'1-5年',
						'3'=>'5-10年',
						'4'=>'10年以上');
	}
	
	/**
	 * 难推的招商外包项目
	 * @author郁政
	 */
	public static function projectListHard(){
		return array(
			'55507' => 55507,
			'55931' => 55931,
			'54865' => 54865,
			'54843' => 54843,
			'33081' => 33081,
			'55803' => 55803,
			'54841' => 54841,
			'55509' => 55509,
			'55453' => 55453,
			'55195' => 55195,
			'55503' => 55503,
			'55057' => 55057,
			'55187' => 55187,
			'55925' => 55925,
			'55069' => 55069,
			'55101' => 55101,
			'55941' => 55941,
			'55387' => 55387,
			'17203' => 17203,
			'55251' => 55251,
			'55937' => 55937,
			'17229' => 17229,
			'55651' => 55651,
			'54845' => 54845,
			'17511' => 17511,
			'55933' => 55933,
			'55749' => 55749,
			'55389' => 55389,
			'55019' => 55019,
			'17349' => 17349,
			'55917' => 55917,
			'55281' => 55281,
			'55081' => 55081,
			'54765' => 54765,
			'55151' => 55151,
			'55377' => 55377,
			'55701' => 55701,
			'17281' => 17281,
			'55099' => 55099,
			'55499' => 55499,
			'55587' => 55587,
			'55185' => 55185,
			'17297' => 17297,
			'17321' => 17321,
			'54899' => 54899,
			'55211' => 55211,
			'17291' => 17291,
			'17207' => 17207,
			'55303' => 55303,
			'55067' => 55067	
			
		);		
	}
	
	/**
	 * 重点广告项目
	 * @author郁政
	 */
	public static function projectListImpAd(){
		return array(
			'40599' => 40599,
			'55829' => 55829,
			'55695' => 55695,
			'55683' => 55683,
			'55693' => 55693,
			'35119' => 35119,
			'55215' => 55215,
			'55197' => 55197,
			'35009' => 35009,
			'55525' => 55525,
			'55631' => 55631,
			'55903' => 55903,
			'55899' => 55899,
			'55867' => 55867,
			'55871' => 55871,
			'35009' => 35009,
			'55877' => 55877,
			'55895' => 55895,
			'55887' => 55887,
			'55881' => 55881,
			'55865' => 55865,
			'55901' => 55901,
			'55875' => 55875		
		
		);		
	}
	
	/**
	 * 首页大家都喜欢的创业项目临时处理方案
	 * 
	 */
	public static function projectListLin(){
		return array('6857'=>6857,
					'55197'=>55197,
					'53939'=>53939,
					'55695'=>55695,
					'55697'=>55697,
					'40599'=>40599,
					'55693'=>55693,
					'55683'=>55683,
					'54857'=>54857
		);
	}

    /**
     * 首页您可能喜欢的项目ID配置
     * @author嵇烨
     */
    public static function projectIdListIndex() {
            return array("7429"=>7429,
                "54991"=>54991,
                "54971"=>54971,
                "55049"=>55049,
                "30241"=>30241,
                "54967"=>54967,
                "9629"=>9629,
                "55357"=>55357,
                "49403"=>49403,
                "55039"=>55039,
                "54921"=>54921,
                "54915"=>54915,
                "54731"=>54731,
                "6848"=>6848,
                "55285"=>55285,
                "54943"=>54943,
                "54715"=>54715,
                "54923"=>54923,
                "54917"=>54917);
    }

    /**
     * 项目ID配置
     * @author嵇烨
     */
    public static function projectIdlist() {
        return array("8268"=>8268,
                    "54917"=>54917,
                    "9627"=>9627,
                    "30595"=>30595,
                    "55263"=>55263,
                    "54741"=>54741,
                    "54759"=>54759,
                    "6993"=>6993,
                    "55191"=>55191,
                    "8213"=>8213,
                    "55043"=>55043,
                    "55189"=>55189,
                    "55225"=>55225,
                    "54927"=>54927,
                    "55015"=>55015,
                    "8724"=>8724,
                    "55167"=>55167,
                    "55289"=>55289,
                    "7948"=>7948,
                    "55257"=>55257);
    }

    /**
     * 投资金额
     * @author 曹怀栋
     * @var array
     */
    public static function moneyArr() {
        return array(
            '1' => '1万以下',
            '2' => '1-2万',
            '3' => '2-5万',
            '4' => '5-10万',
            '5' => '10万以上',
        );
    }
    
 	/**
     * 投资金额(这都不是事)
     * @author 郁政
     * @var array
     */
    public static function moneyArr1() {
        return array(
            '1' => '5',
            '2' => '5-10',
            '3' => '10-20',
            '4' => '20-50',
            '5' => '50',
        );
    }

    /**
     * 投资者身份
     * @author 钟涛
     * @var array
     */
    public static function perIdentityArr() {
        return array(
            '1' => '公务员',
            '2' => '上班族',
            '3' => '做生意1-3年',
            '4' => '做生意3年以上的',
            '5' => '赋闲在家',
        );
    }
    /**
     * 投资者从业经验职业
     * @author 赵路生
     * @var array
     */
    public static function perExpOccupationArr() {
        return array(
                "0" => "请选择",
                "1" => "公务员",
                "2" =>"做生意",
                "3" =>"上班族"
        );
    }
    /**
     * 项目官网-举报类别
     * @author 赵路生
     * @var array
     */
    public static function projectReportClassArr() {
        return array(
                "0" => "不实内容",
                "1" => "冒充或抄袭我的内容",
                "2" =>"敏感或色情内容",
                "3" =>"其他"
        );
    }
    /**
     * 投资者加盟项目方式
     * @author 钟涛
     * @var array
     */
    public static function joinProjectArr() {
        return array(
            '1' => '自己一个人加盟',
            '2' => '和家人一起加盟',
            '3' => '和朋友合伙加盟',
        );
    }

    /**
     * 人脉关系
     * @author 钟涛
     * @var array
     */
    public static function connectionsArr() {
        return array(
            '1' => '有企事业单位关系',
            '2' => '有政府关系',
            '3' => '有学校关系',
            '4' => '有医疗关系',
            '5' => '有团购客户',
        );
    }

    /**
     * 个人投资风格
     * @author 钟涛
     * @var array
     */
    public static function investmentStyle() {
        return array(
            '1' => '低风险',
            '2' => '高回报',
        );
    }

    /**
     * 招商形式
     * @author 曹怀栋
     * @var array
     */
    public static function businessForm() {
        return array(
            '1' => '开店加盟',
            '2' => '批发代理',
            '3' => '网上销售',
        );
    }

    /**
     * 名片筛选时间
     * @var array
     */
    public static function receivetime() {
        return array(
            '1' => '近一天',
            '2' => '近两天',
            '3' => '近三天',
            '7' => '近一周',
            '14' => '近两周',
            '30' => '近一个月',
            '60' => '近两个月',
            '180' => '近半年',
            '10000' => '半年以上',
        );
    }

    /**
     * 名片重复收到次数
     * @var array
     */
    public static function card_number_of_times() {
        return array(
            '1' => '一次',
            '2' => '两次',
            '3' => '三次',
            '4' => '三次以上',
        );
    }

    /**
     * 名片模板中大图片存储
     * @var array
     * @update by周进新增个人类型对应的模板
     */
    public static function card_img_big($type = '') {
        if ($type == "person") {
            return array(
                '1' => '/images/person_card/person_card_bg_0.png',
                '2' => '/images/person_card/person_card_bg_1.png',
            );
        }
        return array(
            '1' => '/images/mycard/mycard2_box_bg_0.png',
            '2' => '/images/mycard/mycard2_box_bg_1.png',
        );
    }

    /**
     * 名片模板中小图片存储
     * @var array
     * * @update by周进新增个人类型对应的模板
     */
    public static function card_img_small($type = '') {
        if ($type == "person") {
            return array(
                '1' => '/images/person_card/person_card_bg_0.png',
                '2' => '/images/person_card/person_card_bg_1.png',
            );
        }
        return array(
            '1' => '/images/mycard/mycard2_box_bg_0.png',
            '2' => '/images/mycard/mycard2_box_bg_1.png',
        );
    }

    /**
     * 公司性质
     * @author 周进
     * @var array
     */
    public static function comnature() {
        return array(
            '0' => '外资企业',
            '1' => '国有企业',
            '2' => '私营企业',
            '3' => '个体经营',
            '4' => '事业单位',
        );
    }

    /**
     * 公司规模
     * @author 钟涛
     * @var array
     */
    public static function comscale() {
        return array(
            '1' => '少于50人',
            '2' => '50-150人',
            '3' => '150-500人',
            '4' => '500-1000人',
            '5' => '1000人以上',
        );
    }

    /**
     * 咨询关键字[注意咯：字数多的必须在前面]
     * @author 钟涛
     * @var array
     */
    public static function zixunkey() {
        return array(
            '创业最新排行榜'=> 'http://www.yjh.com/xiangdao/top/',
            '服饰箱包加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy3.html',
            '教育培训加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy5.html',
            '酒水饮料加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy6.html',
            '开店装修技巧'=> 'http://www.yjh.com/zixun/guide/zhuangxiujiqiao.html',
            '连锁零售加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy2.html',
            '珠宝饰品加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy119.html',
            '创业排行榜'=> 'http://www.yjh.com/xiangdao/top/',            
            '找赚钱项目'=> 'http://www.yjh.com/xiangdao/',
            '创业投资'=> 'http://www.yjh.com',
            '创业项目'=> 'http://www.yjh.com',
            '创业资讯'=> 'http://www.yjh.com/zixun/',
            '行业透视'=> 'http://www.yjh.com/zixun/dialys.html',
            '餐饮加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy1.html',
            '美容加盟'=> 'http://www.yjh.com/xiangdao/fenlei/zhy80.html',            
            '开店管理'=> 'http://www.yjh.com/zixun/shop.html',
            '开店指导'=> 'http://www.yjh.com/zixun/guide.html',
            '考察报告'=> 'http://www.yjh.com/zixun/kaocha.html',            
            '投资项目'=> 'http://www.yjh.com/xiangdao/',            
            '学开店'=> 'http://www.yjh.com/zixun/',
            '一句话'=> 'http://www.yjh.com',
            '找项目'=> 'http://www.yjh.com/xiangdao/',
            '好项目'=> 'http://www.yjh.com/xiangdao/',
            '创业'=> 'http://www.yjh.com',
            '加盟'=> 'http://www.yjh.com',
            '开店'=> 'http://www.yjh.com/zixun/',
        );
    }

    /**
     * 名片行为动作
     * 默认1:新增名片；2:递出名片；3:收到名片；4:交换名片；5:收藏名片;6:被收藏名片；7:取消收藏名片;'8' => '删除名片'
     * @var array
     */
    public static function card_behaviour() {
        return array(
                '1' => '新增名片',
                '2' => '递出名片',
                '3' => '收到名片',
                '4' => '交换名片',
                '5' => '收藏名片',
                '6' => '被收藏名片',
                '7' => '取消收藏名片',
                '8' => '删除名片',
                '9' => '查看名片',
                '10' => '被查看名片',
                '11' => '查看联系方式',
                '12' => '被查看联系方式',
                '13' => '免费查看联系方式',
                '14' => '被免费查看联系方式',
        );
    }

    /**
     * 咨询项目默认内容
     * @var array
     */
    public static function sendlettercontent() {
    	return array(
    			'1' => '你们的项目很好，请速速联系我详谈',
    			'2' => '我想了解更多你们的项目，请给我相关的加盟资料',
    			'3' => '加盟你们的项目需要多少费用？',
    			'4' => '请问我所在的地区有加盟商了吗？',
    			'5' => '我想了解更多情况，请给我相关的加盟资料。',
    			'6' => '我想了解你们项目的加盟费是多少？',
    			'7' => '我想了解你们项目后期有什么支持？',
    			'8' => '我想了解你们项目的加盟电话？',
    			'9' => '我想了解怎样/如何加盟你们项目？',
    			'10' => '我想了解可以随时加盟吗？',
    			'11' => '请问你们项目的加盟流程是怎么样的？',
    			'12' => '我想了解上海市可以加盟你们项目吗？',
    	);
    }
    
    /*
     * 企业自动回复内容
     * @var array
     */
    public static function letter_reply() {
        return array(
                '1' => '你想创业吗？您还在为没有合适的好项目苦愁？我们来帮您，欢迎来电咨询详情！',
                '2' => '以小博大，赚钱其实没那么难，经销代理一样能够玩转！',
                '3' => '还在为那份低薄的收入而苦恼吗？想赚钱坐等可不行，自己当老板才是王道！',
                '4' => '人生总要有些追求，让自己让家庭过的更舒心，大胆开启你的创业之路吧！',
                '5' => '你的行业经验很适合运作我们的项目，欢迎来电详谈。',
                '6' => '我查看了您的资料信息，我公司的项目挺适合您的，小投资，大回报，希望我们能有一个合作的机会。',
                '7' => '想你的钱生钱吗？让我们满足您的愿望吧，欢迎来电咨询详情！',
                '8' => '刚毕业找不到满意的工作，投资做生意吧！买车买房，并不是那么遥远！',
                '9' => '有权有钱是人们一生的追求，您还在等什么呢？',
                '10' => '有钱有时间的您，想坐等收钱环游世界吗？',
                '11' => '想让家人过的更舒适吗？让小投资大回报帮您实现愿望吧！',
                '12' => '这个项目很适合你，要不试试？盼您回复。',
                '13' => '年赚20万的项目为你量身定制，还在等什么呢？',
                '14' => '打工不如自己创业当老板，好项目等您来投资。',
                '15' => '那些不为人知的暴利行业项目，你想了解吗，快拿起电话咨询我们吧！',
                '16' => '咸鱼翻身不是梦，机会就在举手之间，快拿起电话咨询我们吧！',
                '17' => '想追求财富吗？财富不等人哦，赶紧来电咨询详情！',
                '18' => '小项目、大财富，机会就在眼前，创业项目等您来咨询。',
        );
    }
    /**
     * 项目一级行业
     * @author 曹怀栋
     */
    public static function primaryIndustry($parent_id, $industry_id = 0) {
        $model = ORM::factory("industry");
        if ($industry_id == 0) {
            $rtn = $model->where('parent_id', '=', $parent_id)->order_by('industry_order', 'DESC')->find_all();
        } else {
            $rtn = $model->where('parent_id', '=', $parent_id)->and_where('industry_id', '=', $industry_id)->order_by('industry_order', 'DESC')->find_all();
        }
        return $rtn;
    }

    /**
     * 地区（城市）
     * 调用方法如下(id表示读取某一个城市的信息,pro_id表示读取某个省中所有城市的信息)：
     * common::arrArea(array('id'=>'','pro_id'=>1));
     * 两个值必须一个为空，一个有值
     * @author 曹怀栋
     */
    public static function arrArea($array) {
        $model = ORM::factory("City");
        if (!isset($array['id'])) {
            $rtn = $model->where('pro_id', '=', $array['pro_id'])->order_by('cit_id', 'ASC')->find_all();
        } else {
            $rtn = $model->where('cit_id', '=', $array['id'])->find();
        }
        return $rtn;
    }

    /**
     * 上传图片错误信息
     * @author 曹怀栋
     * @return string
     */
    public static function imgOrror($code) {
        switch ($code) {
            case 'UPLOAD_ERR_OK':
                return "上传成功";
                break;
            case 'UPLOAD_ERR_INI_SIZE':
                return "上传的图片太大"; //上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。
                break;
            case 'UPLOAD_ERR_FORM_SIZE':
                return "上传的图片太大"; //上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
                break;
            case 'UPLOAD_ERR_PARTIAL':
                return "图片只有部分被上传";
                break;
            case 'UPLOAD_ERR_NO_FILE':
                return "没有图片被上传";
                break;
            case 'UPLOAD_ERR_NO_TMP_DIR':
                return "找不到临时文件夹";
                break;
            default:
                return "图片写入失败";
                break;
        }
    }

    /**
     * 上传图片到图片服务器
     * @author 曹怀栋
     * @param array $_FILES
     * @param string file key
     * @param array $arr
     * 单张图片：$arr = array('w'=>120, 'h'=>90); 或 $arr = array(array(120,90));
     * 大小图片，即两张图片：$arr = array(array(400,300),array(120,90)); array(400,300)为大图宽高，array(120,90)为小图宽高
     * 大中小图，即三张图片：$arr = array(array(1200,900),array(400,300),array(120,90)); 依次为大中小图的尺寸大小
     * @return array('error'=>'','path'=>'','name'=>'')
     * 当为多张图片时，则只返回最小图片,最小图片名称以 s_ 前缀。
     * 大图以 b_ 前缀
     * 中图以 m_ 前缀，大图和中图 需要根据小图地址进行转化而得到，如：str_replace('s_','b_', $smallpic);
     */
    public static function uploadPic($files, $filekey, $arr = array()) {
        static $client;
        if (empty($files[$filekey]['tmp_name'])) {
            return array('error' => 'error', 'path' => '', 'name' => '');
        }
        //start 类型安全性 add by 周进
        $mimetypes = array("image/gif", "image/pjpeg", "image/jpeg", 'image/png', 'image/bmp', 'application/octet-stream');
        if (!in_array(strtolower($files[$filekey]['type']), $mimetypes))
            return array('error' => 'error', 'path' => '', 'name' => '');
        //end
        $files[$filekey]['tmp_name'] = base64_encode(file_get_contents($files[$filekey]['tmp_name']));
        if (!($client instanceof SoapClient)) {
            $client = new SoapClient(null, array('location' => kohana::$config->load('image')->get('upload_domain'), 'uri' => kohana::$config->load('image')->get('uri'), 'encoding' => 'utf8'));
        }
        $rtn = $client->uploadPic($files, $filekey, kohana::$config->load('image')->get('call_server_keys'), $arr);
        return $rtn;
    }

    /**
     * 测试图片服务器
     * @param unknown $files
     * @param unknown $filekey
     * @param unknown $arr
     * @return multitype:string |unknown
     * @author 龚湧
     */
    public static function uploadPicTest($files, $filekey, $arr = array()) {
        static $client;
        if (empty($files[$filekey]['tmp_name'])) {
            return array('error' => '临时文件写入失败--客户端', 'path' => '', 'name' => '');
        }
        //start 类型安全性 add by 周进
        $mimetypes = array("image/gif", "image/pjpeg", "image/jpeg", 'image/png', 'image/bmp', 'application/octet-stream');
        if (!in_array(strtolower($files[$filekey]['type']), $mimetypes))
            return array('error' => '图片类型错误--客户端', 'path' => '', 'name' => '');
        //end
        $files[$filekey]['tmp_name'] = base64_encode(file_get_contents($files[$filekey]['tmp_name']));
        if (!($client instanceof SoapClient)) {
            $client = new SoapClient(null, array('location' => kohana::$config->load('image')->get('upload_domain'), 'uri' => kohana::$config->load('image')->get('uri'), 'encoding' => 'utf8'));
        }
        $rtn = $client->uploadPic($files, $filekey, kohana::$config->load('image')->get('call_server_keys'), $arr);
        return $rtn;
    }

    /**
     * 上传多张图片到图片服务器
     * @author 潘宗磊
     * @param array $_FILES
     * @param string file key
     * @param array $arr
     * 大小图片，即两张图片：$arr = array(array(120,90)); array(120,90)为小图宽高
     * 大中小图，即三张图片：$arr = array(array(1200,900),array(400,300)); 依次为中小图的尺寸大小
     * @return array(array('error'=>'','path'=>'','name'=>''))
     * 当为多张图片时，则只返回最小图片,最小图片名称以 s_ 前缀。
     * 大图以 b_ 前缀
     * 中图以 m_ 前缀，大图和中图 需要根据小图地址进行转化而得到，如：str_replace('s_','b_', $smallpic);
     */
    public static function uploadPics($files, $filekey, $arr = array()) {
        static $client;
        $upfiles = array();
        $rtnImages = array();
        $files = $files[$filekey];
        if (!($client instanceof SoapClient)) {
            $client = new SoapClient(null, array('location' => kohana::$config->load('image')->get('upload_domain'), 'uri' => kohana::$config->load('image')->get('uri'), 'encoding' => 'utf8'));
        }
        for ($i = 0; $i < count($files['name']); $i++) {//把多张图片重新以一张图片形式存储
            if (!empty($files['tmp_name'][$i])) {
                $upfiles[] = array($filekey => array('name' => $files['name'][$i], 'type' => $files['type'][$i], 'type' => $files['type'][$i], 'tmp_name' => $files['tmp_name'][$i], 'error' => $files['error'][$i], 'size' => $files['size'][$i]));
            }
        }
        if (!empty($upfiles)) {
            for ($i = 0; $i < count($upfiles); $i++) {//循环上传图片，并保存调返回内容
                $org = getimagesize($upfiles[$i][$filekey]['tmp_name']);
                $org_w = $org[0]; //原图宽
                $org_h = $org[1]; //原图高
                $asize = array(array($org_w, $org_h));
                if (!empty($arr[0])) {//中图的宽度和高度
                    $asize[] = $arr[0];
                }
                if (!empty($arr[1])) {//小图的宽度和高度
                    $asize[] = $arr[1];
                }
                $upfiles[$i][$filekey]['tmp_name'] = base64_encode(file_get_contents($upfiles[$i][$filekey]['tmp_name']));
                $rtn = $client->uploadPic($upfiles[$i], $filekey, kohana::$config->load('image')->get('call_server_keys'), $asize);
                $rtnImages[] = $rtn;
            }
        }
        return $rtnImages;
    }

    /**
     *
     * @param string $picurl
     * @return int 1 | -1 | -2
     * 1为正确，
     * -1路径不正确
     * -2为删除失败
     */
    public static function deletePic($picurl) {
        static $client;
        if (!($client instanceof SoapClient)) {
            $client = new SoapClient(null, array('location' => kohana::$config->load('image')->get('upload_domain'), 'uri' => kohana::$config->load('image')->get('uri'), 'encoding' => 'utf8'));
        }
        $rtn = $client->deletePic($picurl, kohana::$config->load('image')->get('call_server_keys'));
        return $rtn;
    }

    /**
     * 发送邮件
     * @author 周进
     * @param string $subject 邮件主题
     * @param string $from_eamil
     * @param string $to_eamil
     * @param string $content
     * @param string $from_eamil
     * @param string $bodytype default text/html
     */
    public static function sendemail($subject, $from_email, $to_email, $content, $bodytype = 'text/html') {
        $config_group = "online".mt_rand(1, 10);
        $config = Kohana::$config->load('swift')->get($config_group);
        if(!$config){
            return false;
        }
        $from_email = $config['transport']['options']['username'];
        $result = FALSE;
        try {
            Email::factory($config_group)->send(Email::message($subject, $from_email, $to_email)->setBody($content, $bodytype));
            $result = 1;
        } catch (Swift_TransportException $e) {
            if(isset($_GET['debug']) and $_GET['debug'] === '1'){
                throw $e;
            }
            $result = FALSE;
        }
        return $result;
    }

    /**
     * 判断是否已经收藏
     * @author周进
     * @param int $user_id 当前用户
     * @param int $favorite_user_id 关联用户ID
     * @return bool
     */
    public function getFavoriteStatus($user_id, $favorite_user_id) {
        $user_id = intval($user_id);
        $favorite_user_id = intval($favorite_user_id);
        $favorite = ORM::factory('Favorite');
        $data = $favorite->where('favorite_user_id', '=', $favorite_user_id)
                        ->where('user_id', '=', $user_id)->where('favorite_status', '=', '1')->count_all();
        if ($data == 1) {//已经存在收藏并且收藏状态为1
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 生成验证码 url
     * @return string
     * @author 龚湧
     */
    public static function verification_code() {
        $src = URL::site("captcha");
        $src = URL::website('captcha');
        $src_rand = $src . "?" . mt_rand(10000000, 99999999);
        return $src_rand;
    }

    /**
     *
     * @author 龚湧
     * @param int $receiver 手机号码
     * @param string $msg  消息主体
     * @param string 账号类型 本地测试为local ,服务器测试环境为online
     * @return object (public retCode, public retMsg)
     */
    public static function send_message($receiver, $msg, $type) {
        $url = Kohana::$config->load("message.url");
        $send = array(
            'acode' => Kohana::$config->load("message.$type.acode"), //用户名
            'token' => Kohana::$config->load("message.$type.token"), //密码
            //'batid' => "", //批次号(可选)
            'msg' => $msg, //消息主体
            'receiver' => $receiver, //手机接收号码
                //'schdtm' =>'',//预发送时间(可选)
                //'expiretm' => ''//失效时间(可选)
        );

        //获取返回结果
        try {
            $msg = Request::factory($url)
                    ->method(HTTP_Request::POST)
                    ->post($send)
                    ->execute()
                    ->body();
            $msg = json_decode($msg);
            if (!$msg) {
                $msg = new stdClass;
                $msg->retCode = 1;
            }
        } catch (Kohana_Exception $e) {
            $msg = new stdClass;
            $msg->retCode = 1;
            return $msg;
        }
        return $msg;
    }

    /**
     * 用于账户变更记录表的account_class字段解析
     * @author 周进
     * @param string $account默认金额
     * @param int $account_class 操作对应符号0,1为+2为-
     * @param string $account_change_amount改变金额
     * @return string "+"or"-"
     */
    public static function getMark($account, $account_class, $account_change_amount) {
        switch ($account_class) {
            case 0:
                return $account + $account_change_amount;
                break;
            case 1:
                return $account + $account_change_amount;
                break;
            case 2:
                return ($account - $account_change_amount >= 0) ? $account - $account_change_amount : '0';
                break;
        }
    }

    /**
     * 用于账户变更记录表的account_type字段解析/操作类型
     * @author 周进
     * @modified by 赵路生 2013-8-23
     * @param int $code 操作类型-
     * @return string 对应说明内容
     *
     * 更多的类型还有待添加，注意这个是和$this->getCountDetailClass()相对应的，修改谨慎啊~~~~(>_<)~~~
     */
    public static function getAccountType($code) {
        switch ($code) {
            case '1':
                return "充值";
                break;
            case '2':
                return "购买服务";
                break;
            case '3':
                return "充值成功返回";
                break;
            case '4':
                return "后台确定线下汇款";
                break;
            case '5':
                return "查看报名招商会投资者";
                break;
            case '6':
                return "查看名片";
                break;
            case '7':
                return "递出名片";
                break;
            case '8':
                return "交换名片";
                break;
            case '9':
                return "报名招商会";
                break;
            case '10':
                return "发布项目";
                break;
           case '11':
                return "发布招商会";
                break;
           case '12':
                return "发布软文章";
                break;
           case '13':
                return "平台服务费用";
                break;
            default:
                return "充值";
                break;
        }
    }

    /**
     * 根据类型获取购买所需的价格
     * @author 周进
     * @return string 对应价格
     */
    public static function getAccount() {
        return array(
            '5' => array(
                'price' => '80',
                'remarks' => '查看投资者递送的名片',
            ),
            '6' => array(
                'price' => '15',
                'remarks' => '查看名片',
            ),
            '7' => array(
                'price' => '3',
                'remarks' => '递出名片',
            ),
            '8' => array(
                'price' => '3',
                'remarks' => '交换名片',
            ),
            '9' => array(
                'price' => '60',
                'remarks' => '报名招商会',
            ),
            '10' => array(
                'price' => '500',
                'remarks' => '发布项目',
            ),
            '11' => array(
                'price' => '500',
                'remarks' => '发布招商会',
            ),
            '12' => array(
                'price' => '300',
                'remarks' => '发布软文章',
            ),
            '13' => array(
                'price' => '1500',
                'remarks' => '平台服务费',
            ),
        );
    }

    /**
     * 支付方式和类型
     * @author 周进
     * @return array
     */
    public static function getPayType($code) {
        switch ($code) {
            case '1':
                return "充值";
                break;
            case '2':
                return "余额支付";
                break;
            default:
                return "余额付款";
                break;
        }
    }

    /**
     * 根据充值金额返回赠送金额
     * @author 钟涛
     * @param int $account充值金额
     * @return 赠送金额
     */
    public static function getCostFree($account=0){
        if($account<5000){
            return 0;
        }elseif($account>=5000 && $account<10000){
            return $account * 0.1;
        }elseif($account>=10000 && $account<20000){
            return $account * 0.15;
        }elseif($account>=20000){
            return $account * 0.2;
        }else{
            return 0;
        }
    }

    /**
     * 调用购买服务相关的类型
     * @author 周进
     * @return array
     */
    public static function getServiceType($code) {
        switch ($code) {
            case '1':
                return "查看名片服务";
                break;
            case '2':
                return "递出名片服务";
                break;
            case '3':
                return "查看招商会服务";
                break;
            default:
                break;
        }
    }

    /**
     * 购买服务类型及对应类型条件
     * @author 周进
     * @return array
     */
    public static function getBuyType() {
        return array(
            '1' => array(
                '1' => array('price' => '1000', 'describe' => '12个月', 'time' => 12 * 30 * 24 * 60 * 60),
                '2' => array('price' => '700', 'describe' => '6个月', 'time' => 6 * 30 * 24 * 60 * 60),
                '3' => array('price' => '400', 'describe' => '3个月', 'time' => 3 * 30 * 24 * 60 * 60),
                '4' => array('price' => '200', 'describe' => '1个月', 'time' => 30 * 24 * 60 * 60),
                'remarks' => '查看名片包月',
            ),
            '2' => array(
                '1' => array('price' => '980', 'describe' => '10张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '10'),
                '2' => array('price' => '1900', 'describe' => '20张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '20'),
                '3' => array('price' => '4500', 'describe' => '50张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '50'),
                '4' => array('price' => '8000', 'describe' => '100张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '100'),
                'remarks' => '名片放大镜',
            ),
            '3' => array(
                '1' => array('price' => '1000', 'describe' => '12个月', 'time' => 12 * 30 * 24 * 60 * 60),
                '2' => array('price' => '700', 'describe' => '6个月', 'time' => 6 * 30 * 24 * 60 * 60),
                '3' => array('price' => '400', 'describe' => '3个月', 'time' => 3 * 30 * 24 * 60 * 60),
                '4' => array('price' => '200', 'describe' => '1个月', 'time' => 1 * 30 * 24 * 60 * 60),
                'remarks' => '递送名片包月',
            ),
            '4' => array(
                '1' => array('price' => '294', 'describe' => '10张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '10'),
                '2' => array('price' => '570', 'describe' => '20张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '20'),
                '3' => array('price' => '1350', 'describe' => '50张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '50'),
                '4' => array('price' => '2400', 'describe' => '100张', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '100'),
                'remarks' => '名片邮票',
            ),
            '5' => array(
                '1' => array('price' => '42500', 'describe' => '500人次', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '500'),
                '2' => array('price' => '8900', 'describe' => '100人次', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '100'),
                '3' => array('price' => '1900', 'describe' => '20人次', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '20'),
                '4' => array('price' => '990', 'describe' => '10人次', 'time' => 12 * 30 * 24 * 60 * 60, 'num' => '10'),
                'remarks' => '查看报名招商会投资者',
            ),
        );
    }

    /**
     * 根据ip获取城市名
     * get city from ipdata file
     * @return String: city and Lan
     */
    public static function convertip_tiny($ip, $ipdatafile) {
        static $fp = NULL, $offset = array(), $index = NULL;
        $ipdot = explode('.', $ip);
        $ip = pack('N', ip2long($ip));

        $ipdot[0] = (int) $ipdot[0];
        $ipdot[1] = (int) $ipdot[1];

        if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
            $offset = unpack('Nlen', fread($fp, 4));
            $index = fread($fp, $offset['len'] - 4);
        } elseif ($fp == FALSE) {
            return '- 无效IP地址文件';
        }

        $length = $offset['len'] - 1028;
        $start = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

        for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

            if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
                $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
                $index_length = unpack('Clen', $index{$start + 7});
                break;
            }
        }
        fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
        if ($index_length['len']) {

            return '- ' . fread($fp, $index_length['len']);
        } else {
            return '- 未知IP';
        }
    }

    /**
     * 根据ip获取城市名
     * get city and Lan from ip address
     * @return String: city and Lan
     */
    public static function convertip($ip) {

        $return = '';

        if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {

            $iparray = explode('.', $ip);

            if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
                $return = '- 本地';
            } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
                $return = '- 无效IP地址';
            } else {
                $tinyipfile = MODPATH . 'helper/classes/cityname/tinyipdata.dat';
                if (@file_exists($tinyipfile)) {
                    $return = common::convertip_tiny($ip, $tinyipfile);
                } else {
                    $return = '- IP地址文件不存在';
                }
            }
        }
        return $return;
    }

    /**
     * 获取个人用户中心SEO Title标题
     * @author 钟涛
     * @param string $code contrller_方法名
     * @return string 返回对应Title标题
     */
    public static function getPersonSEOTitle($code) {
        switch ($code) {
            case 'Basic_person' :
            case 'Basic_personupdate' :
                return "基本信息";
                break;
            case 'Basic_experience' :
                return "我的从业经验";
                break;
            case 'Card_mycard' :
            case 'Card_cardStyle' :
                return "我的名片";
                break;
            case 'Card_receivecard' :
                return "我收到的名片";
                break;
            case 'Card_outcard' :
                return "我递出的名片";
                break;
            case 'Card_favorite' :
                return "我收藏的名片";
                break;
            case 'Valid_mobile' :
                return "手机验证";
                break;
            case 'Basic_identificationcard' :
                return "身份验证";
                break;
            case 'Project_searchProject' :
                return "搜索项目";
                break;
            case 'Project_searchWatchProjectList' :
                return "搜索项目历史记录";
                break;
            case 'Project_watchProject' :
                return "我收藏的项目";
                break;
            case 'Invest_myInvest' :
                return "我报名的投资考察会";
                break;
            case 'Invest_searchInvest' :
                return "搜索投资考察会";
                break;
            case 'Msg_index' :
                return "我的消息";
                break;
            case 'Basic_modifypassword' :
                return "修改密码";
                break;
            default :
                return "";
                break;
        }
    }

    /**
     * 获取企业用户中心SEO Title标题
     * @author 钟涛
     * @param string $codecontrller_方法名
     * @return string 返回对应Title标题
     */
    public static function getCompanySEOTitle($code) {
        switch ($code) {
            case 'Basic_company' :
            case 'Basic_editCompany' :
                return "企业基本信息管理";
                break;
            case 'Basic_integrity' :
                return "我的诚信";
                break;
            case 'Project_myinvestment' :
                return "我的投资考察会";
                break;
            case 'Valid_mobile' :
                return "手机号码验证";
                break;
            case 'Basic_comCertification' :
            case 'Basic_uploadCertification' :
                return "企业资质认证";
                break;
            case 'Card_completecard' :
            case 'Card_cardstyle' :
            case 'Card_mycard' :
                return "我的名片";
                break;
            case 'Card_receivecard' :
                return "我收到的名片";
                break;
            case 'Card_outcard' :
                return "我递出的名片";
                break;
            case 'Card_favorite' :
                return "我收藏的名片";
                break;
            case 'Basic_modifypassword' :
                return "修改密码";
                break;
            case 'Account_accountindex' :
                return "我的账户";
                break;
            case 'Account_accountlist' :
                return "明细查询";
                break;
            case 'Merchants_applyBusiness' :
                return "申请招商通服务";
                break;
            case 'Investor_search' :
                return "搜索投资者";
                break;
            case 'Investor_searchConditionsList' :
                return "搜索投资者历史记录";
                break;
            case 'Investor_searchSubscription' :
                return "订阅投资者";
                break;
            case 'Msg_index' :
                return "我的消息";
                break;
            case 'Project_addproject' :
            case 'Project_updateproject' :
            case 'Project_addproimg' :
            case 'Project_addprocertsimg' :
            case 'Project_addproplaybill' :
            case 'Project_proinvestment' :
            case 'Project_viewProInvestment' :
            case 'Project_addproinvestment' :
            case 'Project_addpropublish' :
            case 'Project_showproject' :
                return "我的项目";
                break;
            default :
                return "";
                break;
        }
    }

    /**
     * 获得当前时间断
     * @author 施磊
     */
    public static function getNowTime() {
        $time = time();
        $date = date('H', $time);
        if ($date >= 5 && $date < 11) {
            return "早上好";
        } else if ($date >= 11 && $date < 13) {
            return "中午好";
        } else if ($date >= 13 && $date < 18) {
            return "下午好";
        } else if ($date >= 18 || $date < 5) {
            return "晚上好";
        }
    }

    /**
     * 手机加密
     * @param unknown $mobile
     * @return string
     * @author 龚湧
     */
    public static function encodeMoible($mobile) {
        $encrypt = Des::instance();
        return $encrypt->encode($mobile);
    }

    /**
     * 手机解密
     * @param unknown $mobile
     * @return string
     * @author 龚湧
     */
    public static function decodeMoible($mobile) {
        $encrypt = Des::instance();
        return $encrypt->decode($mobile);
    }

    /**
     * 客服电话号码配置
     * @author 钟涛
     * @var array
     */
    public static function getCustomerPhone() {
        return array(
            '1' => '400 1015 908', //客服电话
            '2' => '021-33233439', //广告投放电话
            '3' => '400-0606-875', //找项目咨询
            '4' => '400-058-7988', //企业合作咨询
        );
    }
    /**
     * 账户交易类型 明细对照表
     * @author 赵路生
     * @var array
     */
    public static function getCountDetailClass() {
        //更多的类型还有待添加，注意这个是和$this->getAccountType()相对应的，修改谨慎啊~~~~(>_<)~~~~
        return array(
                '0' => '全部',
                '3' => '充值成功返回',
                '4' => '后台确认线下汇款',
                '5' => '查看报名招商会投资者',
                '6' => '查看名片',
                '7' => '递出名片/重复递出名片',
                '8' => '交换名片',
                '9' => '报名招商会',
                '10' => '发布项目',
                '11' => '发布招商会',
                '12' => '发布软文章',
                '13' => '平台服务费',
                '14' => '充值赠送金额'
        );
    }
    /**
     * 获取新图片地址 去除前面域名'http://pic.czzs.com/'
     * @author 钟涛
     * @var string
     */
    public static function getImgUrl($path) {
        $websiteurl = URL::imgurl('');
        //如果图片地址为空则不替换
        if ($path == '' || $path == null) {
            return $path;
        }
        //如果图片地址中包含http://pic.czzs.com/ 则进行替换
        if (strpos($path, $websiteurl) !== false) {
            return @str_replace($websiteurl, '', $path);
        } else {
            return $path;
        }
    }

    /**
     * 个人基本信息-学历
     * @author 许晟玮
     * @var array
     */
    public static function getPersonEducation() {

        return array(
            '1' => '初中',
            '2' => '高中',
            '3' => '中技',
            '4' => '中专',
            '5' => '大专',
            '6' => '本科',
            '7' => 'MBA',
            '8' => '硕士',
            '9' => '博士',
            '10' => '其他',
        );
    }

    /**
     * 获取行业
     * @param $cache = FALSE  就去数据库拿去   $cache = TRUE   去缓存去取
     * @author 嵇烨
     */
    public static function getIndustryList($cache = TRUE) {
        $model = ORM::factory("industry");
        $industryList = $model->find_all();
        $returnList = array();
        $returnList1 = array();
        $returnList2 = array();
        $cache_key = 'IndustryList';
        $cache_time = '86400';
        $memcache = Cache::instance('memcache');
        $return = array();
        //如果是 1就去数据库拿去 否则去缓存去取
        if ($cache == TRUE) {
            try {
                $returnList = $memcache->get($cache_key);
                if (empty($returnList)) {
                    $returnList = self::getIndustryList(FALSE);
                }
            } catch (Cache_Exception $e) {
                $returnList = self::getIndustryList(FALSE);
            }
        } else {
            foreach ($industryList as $k => $v) {
                if ($v->parent_id == 0) {
                    $returnList1[$v->industry_id] = $v->industry_name;
                } else {
                    $returnList2[$v->parent_id][$v->industry_id] = $v->industry_name;
                }
            }

            //行业的数组拼装
            foreach($returnList1 as $key=>$val){
                $returnList[$key]['first_name'] = $val;
                foreach($returnList2 as $k=>$v){
                    if($key == $k){
                        $returnList[$key]['secord'] = $v;
                        break;
                    }else{
                        $returnList[$key]['secord'] = array();
                    }
                }
            }
            /*
            foreach ($returnList2 as $k => $v) {
                $returnList[$k]['first_name'] = $returnList1[$k];
                foreach ($v as $kk => $vv) {
                    $returnList[$k]['secord'][$kk] = $vv;
                }
            }
            */
            //添加缓存
            $memcache->set($cache_key, $returnList, $cache_time);
        }
        return $returnList;
    }

    /**
     * 获取地区数据
     * @author 嵇烨
     */
    public static function getAreaList($cache = TRUE) {
        $model = ORM::factory("City");
        $areaList = $model->find_all();
        $returnList = array();
        $areaList1 = array();
        $areaList2 = array();
        $cache_key = 'AreaList';
        $cache_time = '86400';
        $memcache = Cache::instance("memcache");
        if ($cache == TRUE) {
            try {
                $returnList = $memcache->get($cache_key);
                if (empty($returnList)) {
                    $returnList = self::getAreaList(false);
                }
            } catch (Cache_Exception $e) {
                $returnList = self::getAreaList(false);
            }
        } else {
            //地区循环
            $returnList['88'] = array('first_name' => '全国', "secord" => array());
            foreach ($areaList as $k => $v) {
                if ($v->pro_id == 0 || $v->pro_id == 88) {
                    $areaList1[$v->cit_id] = $v->cit_name;
                } else {
                    $areaList2[$v->pro_id][$v->cit_id] = $v->cit_name;
                }
            }
            //地区的数组拼装
            foreach ($areaList2 as $k => $v) {
                $returnList[$k]['first_name'] = $areaList1[$k];
                foreach ($v as $kk => $vv) {
                    $returnList[$k]['secord'][$kk] = $vv;
                }
            }
            //添加缓存
            $memcache->set($cache_key, $returnList, $cache_time);
        }
        return $returnList;
    }

    /**
     * 对于标签的特别方法
     * @author 施磊
     */
    public static function searchUrlSpecal($allTag, $keyList, $qKey, $qval) {
        $urlArr = array();
        $urlCondList = array();
        $stop = 0;
        $urlCond = array('1' => 'pmodel', 2 => 'areaid', 6 => 'inid', 7 => 'atype', 10 => 'risk');
        $otherCond = array('sort', 'istatus','w');
        if (arr::get($keyList, 'allow', array())) {
            foreach ($keyList['allow'] as $key => $val) {

                if ($val && !$stop) {
                    foreach ($val as $keyT => $valT) {

                        $urlArr[$key] = $keyT;
                        if($keyT == $qKey && $qval == $valT) {
                            $stop = 1;
                            break;
                        }
                    }
                }
            }
        }
        if($urlArr) {
            foreach($urlArr as $key => $val) {
                $urlCondList[] = $urlCond[$key].'='.$val;
            }
        }
        foreach($otherCond as $val) {
            if(arr::get($allTag,$val,0)) {
                $urlCondList[] = $val.'='.$allTag[$val];
            }
        }
        $urlReturn = $urlCondList ? '?' . implode('&', $urlCondList) : '';
        return $urlReturn;
    }

    /**
     * 生成直搜页面连接
     * @author 施磊
     */
    public static function SearchUrl($qKey, $qval, $allTag = array(), $type = 'push') {
        $urlArr = array();
        if ($allTag) {
            foreach ($allTag as $key => $val) {
                if ($key != 'unallow') {
                    if ($key != $qKey) {
                        $urlArr[] = "{$key}={$val}";
                    } else {

                        if ($type == 'push') {
                            $urlArr[] = "{$qKey}={$qval}";
                        }
                    }
                } else {
                    if ($type == 'unset' && $val) {
                        foreach ($val as $keyT => $valT) {
                            if ($valT == $qval) {
                                unset($val[$keyT]);
                            }
                        }
                        $getVal = implode('|', $val);
                        $urlArr[] = "{$qKey}={$getVal}";
                    } else {
                        $getVal = implode('|', $val);
                        $urlArr[] = "unallow={$getVal}";
                    }
                }
            }
        } else {
            if ($key != 'unallow') {
                $urlArr[] = "{$qKey}={$qval}";
            } else {
                $getVal = implode('|', $val);
                $urlArr[] = "{$qKey}={$getVal}";
            }
        }
        if (!isset($allTag[$qKey]) && $qKey && $qval)
            $urlArr[] = "{$qKey}={$qval}";
        $urlReturn = $urlArr ? '?' . implode('&', $urlArr) : '';
        return $urlReturn;
    }
    /**
     * 取得资讯栏目名
     */
    public static function getcolumnname($id){
        $result = ORM::factory('Zixun_Column')->where('column_id', '=', $id)->find();
        return $result->column_name;
    }

    /**
     * 资讯栏目URL相关配置
     * @return multitype:multitype:string multitype:  multitype:string multitype:string
     */
    public static function column(){
        return array(
                'invest'    =>array('parent'=>'投资前沿','children'=>array()),
                'fugle-a'        =>array('parent'=>'项目向导',
                        'children'=>array(
                                'touzihangye-a'=>'投资行业向导',
                                'touzirenqun-a'=>'投资人群向导',
                                'touzileixing-a'=>'项目类型向导',
                                'touzijine-a'=>'投资金额向导'
                        ),
                ),
                'guide'        =>array('parent'=>'开店指导',
                        'children'=>array(
                                'kaidianbidu'=>'开店必读',
                                'jiamengchoubei'=>'加盟筹备',
                                'xuanzhimijue'=>'选址秘诀',
                                'shichangdingwei'=>'市场定位',
                                'zhuangxiujiqiao'=>'装修技巧',
                                'touziyusuan'=>'投资预算',
                                'kaidianliucheng'=>'开店流程'
                        ),
                ),
                'shop'        =>array('parent'=>'开店管理',
                        'children'=>array(
                                'jiqiao'=>'经营技巧',
                                'cuxiao'=>'推广促销',
                                'renyuan'=>'人员管理',
                                'guke'=>'顾客管理',
                                'yingye'=>'如何提高营业额'
                        ),
                ),
                'people'    =>array('parent'=>'财富人物',
                        'children'=>array(
                                'tongluren'=>'创业同路人',
                                'qiyejiashuo'=>'企业家说',
                        ),
                ),
                'dialys'        =>array('parent'=>'行业透视','children'=>array()),
                'kaocha'    =>array('parent'=>'考察报告','children'=>array()),
                'hot'    =>array('parent'=>'专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;栏','children'=>array()),
                'productinfo' =>array(
                        'parent'=>"项目新闻",'children'=>array()
                ),
                'industryinfo-a' =>array(
                    'parent'=>"行业新闻",'children'=>array()
                ),
        );
    }

    /**
     * 资讯栏目根据URL获取汉子
     * @return multitype:multitype:string multitype:  multitype:string multitype:string
     */
    public static function getcolumnnames($word){
        $list = self::column();
        foreach ($list as $k=>$v){
            if ($k==$word){
                return $v['parent'];
            }
            else{
                foreach ($v['children'] as $j=>$h){
                    if ($j==$word)
                        return $h;
                }
            }
        }
    }

    /**
     * 公司性质(优化改版)
     * @author 周进
     * @var array
     */
    public static function comnature_new() {
        return array(
                '0' => '国企',
                '1' => '外商独资',
                '2' => '合资',
                '3' => '民营',
                '4' => '股份制企业',
                '5' => '上市公司',
                '6' => '国家机关',
                '7' => '事业单位',
                '8' => '代表处',
                '10'=>'自主创业',
                '9' => '其他'

        );
    }
     /**
     * 多维数组排序
     * @param unknown $multi_array(数组)
     * @param unknown $sort_key(排序的字段)
     * @param string $sort SORT_ASC(升序)  SORT_DESC(降序)
     * @return boolean|unknown
     */
    public static function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
        if(is_array($multi_array)){
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        @array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }

    /**
     * 多维多列数组排序
     * @param unknown $multi_array(数组)
     * @param unknown $sort_key(排序的字段)
     * @param string $sort SORT_ASC(升序)  SORT_DESC(降序)
     * @return boolean|unknown
     */
    public static function multi_array_sort2($multi_array,$sort_key,$sort=SORT_ASC,$sort_key2,$sor2=SORT_ASC){
    	if(is_array($multi_array)){
    		foreach ($multi_array as $row_array){
    			if(is_array($row_array)){
    				$key_array[] = $row_array[$sort_key];
    				$key_array2[] = $row_array[$sort_key2];
    			}else{
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}
    	@array_multisort($key_array, $sort, $key_array2, $sor2, $multi_array);
    	return $multi_array;
    }
    /**
     * 获取项目统计的PV总数
     *$pnid 项目
     * @author许晟玮
     */
    public static function getProjectStatPvNum( $pnid,$begin,$end ){
        $memcache = Cache::instance('memcache');
        $cache_name= "getProjectStatPvNum_".$pnid."_".$begin."_".$end;
        $result= $memcache->get($cache_name);
        if( empty( $result ) ){
            $pro_pv_num= 0;
            $pro_web_pv_num= 0;

            $stat_service= new Service_Api_Stat();
            $stat_project_pv= $stat_service->getVisitPv( '1',$pnid,$begin,$end );
            $code= $stat_project_pv['code'];
            if( $code=='200' ){
                $data= $stat_project_pv['data'];
                if( !empty($data) ){
                    foreach ( $data as $k=>$v ){
                        $pro_pv_num= $pro_pv_num+ceil($v['pv']);
                        $pro_web_pv_num= $pro_web_pv_num+ceil($v['compv']);
                    }
                }else{
                    //data is null
                }
            }else{
            }
            $result= array();
            $result['pro_pv']= ceil($pro_pv_num);
            $result['pro_web_pv']= ceil($pro_web_pv_num);
            $memcache->set($cache_name, $result);
        }
        return $result;

    }
    //end function

    /**
     * 圆盘抽奖奖品9月
     * @author 郁政
     * ipod 在奖池中的id为2，数量为1
     * U盘      在奖池中的id为3，数量为5
     * 幸运奖 在奖池中的id为4，数量为31
     * 创业币 在奖池中的id为1
     */
    public static function drawArr(){
        return array(
            '1' => '',
            '2' => '1',
            '3' => '5',
            '4' => '31'
        );
    }


    /**
     * 圆盘抽奖活动时间2013年9月
     * @author 郁政
     */
    public static function chouJiangTime(){
        return array(
            'start_time' => '2013-9-1',
            'end_time' => '2013-9-30 16:00:00',
            'game_id' => 1
        );
    }

    /**
     * 圆盘抽奖活动时间2013年10月
     * @author 郁政
     */
    public static function chouJiangTime2(){
        return array(
            'start_time' => '2013-10-11 9:00:00',
            'end_time' => '2013-11-11 16:00:00',
            'game_id' => 2
        );
    }

    /**
     * 圆盘抽奖活动2013年9月奖品对应编号
     * @author 郁政
     */
    public static function getPrizeName1(){
        return array(
            '0' => '',
            '1' => 'ipod',
            '2' => '(8G)U盘',
            '3' => '一句话T恤',
            '4' => '100创业币',
            '5' => '80创业币',
            '6' => '50创业币',
            '7' => '20创业币',
            '8' => '10创业币',
            '9' => '1创业币'
        );
    }

    /**
     * 圆盘抽奖活动2013年10月奖品对应编号
     * @author 郁政
     */
    public static function getPrizeName2(){
        return array(
            '0' => '',
            '1' => 'ipod',
            '2' => '移动电源',
            '3' => '(8G)U盘',
            '4' => '一句话T恤',
            '5' => '100创业币',
            '6' => '80创业币',
            '7' => '50创业币',
            '8' => '20创业币',
            '9' => '10创业币'
        );
    }

    /**
     * 圆盘抽奖活动2013年11月奖品对应编号
     * @author 郁政
     */
    public static function getPrizeName3(){
        return array(
            '0' => '',
            '1' => 'iPod',
            '2' => '电脑双肩包',
            '3' => '(8G)U盘',
            '4' => '20元话费',
            '5' => '10元话费',
            '6' => '100创业币',
            '7' => '80创业币',
            '8' => '50创业币',
            '9' => '20创业币',
            '10' => '10创业币'
        );
    }
    
 	/**
     * 圆盘抽奖活动第四期奖品对应编号
     * @author 郁政
     */
    public static function getPrizeName4(){
        return array(
            '0' => '',
            '1' => '小虫手机',
            '2' => '电脑双肩包',
            '3' => '20元话费',
            '4' => '10元话费',            
            '5' => '100创业币',
            '6' => '80创业币',
            '7' => '50创业币',
            '8' => '20创业币',
            '9' => '10创业币'
        );
    }

    /**
     * 获取奖品名称
     * @author 郁政
     */
    public static function getPrizeName(){
        return array(
            '1' => array(
                '0' => '',
                '1' => 'ipod 一个',
                '2' => 'U盘  一个',
                '3' => 'T恤  一件',
                '4' => '100创业币',
                '5' => '80创业币',
                '6' => '50创业币',
                '7' => '20创业币',
                '8' => '10创业币',
                '9' => '1创业币'
            ),
            '2' => array(
                '0' => '',
                '1' => 'ipod 一个',
                '2' => '移动充电器  一个',
                '3' => 'U盘  一个',
                '4' => 'T恤  一件',
                '5' => '100创业币',
                '6' => '80创业币',
                '7' => '50创业币',
                '8' => '20创业币',
                '9' => '10创业币'
            ),
            '3' => array(
                '0' => '',
                '1' => 'iPod 一个',
                '2' => '电脑双肩包 一个',
                '3' => 'U盘  一个',
                '4' => '20元话费',
                '5' => '10元话费',
                '6' => '100创业币',
                '7' => '80创业币',
                '8' => '50创业币',
                '9' => '20创业币',
                '10' => '10创业币'
            ),
            '4' => array(
                '0' => '',
                '1' => '小虫手机 一部',
                '2' => '电脑双肩包 一个',                
                '3' => '20元话费',
                '4' => '10元话费',
                '5' => '100创业币',
                '6' => '80创业币',
                '7' => '50创业币',
                '8' => '20创业币',
                '9' => '10创业币'
            )
        );
    }

    /**
     * 获取活动名称
     * @author 郁政
     */
    public static function getGameName(){
        return array(
            '1' => '第一期',
            '2' => '第二期',
            '3' => '第三期',
        	'4' => '第四期'
        );
    }

    /**
     * 创业币类型来源
     * @author 郁政
     */
    public static function getChuangYeBiSource(){
        return array(
            '1' => '抽奖所中',
            '2' => '活跃度兑换',  
        );
    }


    /**
     * 加密用户的手机号,用* 替代当中的数字
     * @author 许晟玮
     */
    public static function decodeUserMobile($mobile){
        $len= strlen($mobile);
        $mb= '';
        $arr= array();
        for( $i=1;$i<=$len;$i++ ){
            $m= substr($mobile, $i-1,1);
            if( $i>3 && $i<8 ){
                $arr[]= '*';
            }else{
                $arr[]= $m;
            }
        }
        foreach( $arr as $v ){
            $mb= $mb.$v;
        }
        return $mb;

    }
    //end function

    /**
     * 验证输入的手机号码
     *
     * @access  public
     * @param   string      $user_mobile      需要验证的手机号码
     *
     * @return bool
     */
    public static function is_mobile($user_mobile)
    {
        $chars = "/^((\(\d{2,3}\))|(\d{3}\-))?1(3|4|5|8|9)\d{9}$/";

        if (preg_match($chars, $user_mobile))
        {
            return true;
        }else
        {
            return false;
        }
    }

    /**
     * 友情链接配置
     * @author 郁政
     */
    public static function getPageName(){
        return array(
            'index' => 1,
            'youqing' => 2,
            'search' => 3,
            'touzikaocha' => 4,
            'zixun' => 5,
            'searchinvestor' => 7,
            'wenda' => 9
        );
    }

    /**
     *传入手机号获取手机号所在地址
     * @author 许晟玮
     * @param int $receiver 手机号码
     * @return int $city_id
     */
    public static function getMessageArea( $mobile ) {
        //获取手机所在地
        $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile."&t=".time();

        //获取返回结果
        try {
            $msg = Request::factory($url)
            ->execute()
            ->body();
            $province = substr($msg, "56", "4");  //截取字符串
            $province= iconv( 'gbk','utf-8',$province );//格式转换
            if( trim($province)!='' ){
                $city = ORM::factory('City');
                $arr = $city->where('cit_name', '=', trim($province))->find()->as_array();
                $city_id= ceil( $arr['cit_id'] );
                return ceil($city_id);
            }else{
                return false;
            }

        } catch (Kohana_Exception $e) {
            return false;

        }

    }
    /**
     * 创业币-获取创业币的类型
     * 看是不是要在数据库建立一张表，先暂时这么用着
     * @author 赵路生
     * @var array
     */
    public static function getScoreType() {
    	// 更多类型期待添加
    	return array(
    			'1' => '抽奖',
    			'2' => '活跃度兑换',
    			'3' => '管理员后台操作',
    			'4' => '提问问题',
    			'5' => '回答问题',
    			'6' => '被采纳为最佳答案',
    			'7' => '被采纳为最佳答案',
    			'8' => '管理员删除提问',
    			'9' => '管理员删除回答',
    			'10'=> '管理员恢复提问',
    			'11'=> '管理员恢复回答',
    			'12'=> '管理员取消采纳为最佳答案',
    			'13' => '用户注册',
	        	'14' => '邮箱验证',
		        '15' => '手机号码验证',
		        '16' => '身份证认证',
		        '17' => '完善基本信息',
		        '18' => '完善意向投资信息',
		        '19' => '完善从业经验',
		        '20' => '用户登录',
		        '21' => '查看项目',
		        '22' => '递送名片',
		        '23' => '报名投资考察会',
		        '24' => '收藏项目',
	        	'25' => '资讯投稿',
	        	'26' => '分享项目&资讯',
		        '27' => '分享资讯',
		        '28' => '抽奖所中',
    			'29' => '好项目活动',
    	
    	);
    }
    
    /**
     * 最佳创业项目
     * @author 郁政
     */
    public static function getZuiJiaChuangYePro(){
    	return array(
	    	'55495' => 55495,
			'55497' => 55497,
			'55505' => 55505,
			'55585' => 55585,
			'35961' => 35961,
			'55367' => 55367,
			'17179' => 17179,
			'17225' => 17225,
			'17291' => 17291,
			'55057' => 55057,
			'55083' => 55083,
			'17357' => 17357,
			'17389' => 17389,
			'55651' => 55651,
			'17427' => 17427,
			'33081' => 33081,
			'55749' => 55749,
			'55931' => 55931,
			'55281' => 55281,
			'54903' => 54903,
			'17183' => 17183,
			'55103' => 55103,
			'55149' => 55149,
			'17349' => 17349,
			'55143' => 55143,
			'55689' => 55689,
			'55933' => 55933,
			'17475' => 17475,
			'55161' => 55161,
			'55381' => 55381,
			'45375' => 45375,
			'17493' => 17493,
			'55095' => 55095,
			'17265' => 17265,
			'55131' => 55131,
			'55387' => 55387,
			'55377' => 55377,
			'54765' => 54765,
			'55383' => 55383,
			'17211' => 17211,
			'17297' => 17297,
			'55105' => 55105,
			'55803' => 55803,
			'54749' => 54749,
			'17453' => 17453,
			'54841' => 54841,
			'55163' => 55163,
			'55211' => 55211,
			'54823' => 54823 	
    	);    	
    }
    
	/**
     * 创业首选项目
     * @author 郁政
     */
    public static function getChuangYeShouXuanPro(){
    	return array(
	    	'55129' => 55129,
			'17329' => 17329,
			'55139' => 55139,
			'54801' => 54801,
			'17305' => 17305,
			'55453' => 55453,
			'55147' => 55147,
			'55115' => 55115,
			'55503' => 55503,
			'17435' => 17435,
			'17419' => 17419,
			'17203' => 17203,
			'55081' => 55081,
			'55385' => 55385,
			'17511' => 17511,
			'55127' => 55127,
			'54861' => 54861,
			'55151' => 55151,
			'55351' => 55351,
			'55251' => 55251,
			'55051' => 55051,
			'54987' => 54987,
			'55217' => 55217,
			'55261' => 55261,
			'55173' => 55173,
			'55207' => 55207,
			'55501' => 55501,
			'55353' => 55353,
			'55279' => 55279,
			'55499' => 55499
    	);
    }
    
	/**
     * 最新商机项目
     * @author 郁政
     */
    public static function getZuiXinShangJiPro(){
    	return array(
	    	'17467' => 17467,
			'17217' => 17217,
			'17241' => 17241,
			'54829' => 54829,
			'54769' => 54769,
			'54865' => 54865,
			'17313' => 17313,
			'55195' => 55195,
			'55449' => 55449,
			'55145' => 55145,
			'17531' => 17531,
			'55509' => 55509,
			'17207' => 17207,
			'55135' => 55135,
			'55447' => 55447,
			'17215' => 17215,
			'54867' => 54867,
			'17459' => 17459,
			'54873' => 54873,
			'55827' => 55827,
			'54889' => 54889,
			'55019' => 55019,
			'55087' => 55087,
			'55077' => 55077,
			'55079' => 55079,
			'55093' => 55093,
			'55379' => 55379,
			'55097' => 55097,
			'55583' => 55583,
			'55437' => 55437,
			'55113' => 55113,
			'55067' => 55067,
			'17189' => 17189,
			'55137' => 55137,
			'17229' => 17229,
			'55033' => 55033,
			'55389' => 55389,
			'54885' => 54885  	
    	);
    }
    
	/**
     * 精品推荐项目
     * @author 郁政
     */
    public static function getJingPinTuiJianPro(){
    	return array(
	    	'55917' => 55917,
			'55925' => 55925,
			'55875' => 55875,
			'55941' => 55941,
			'55231' => 55231,
			'55089' => 55089,
			'54843' => 54843,
			'55069' => 55069,
			'55185' => 55185,
			'54853' => 54853,
			'17181' => 17181,
			'55303' => 55303,
			'55101' => 55101,
			'55187' => 55187,
			'55107' => 55107,
			'55075' => 55075,
			'55587' => 55587,
			'17421' => 17421,
			'55883' => 55883,
			'55891' => 55891,
			'17339' => 17339,
			'17341' => 17341,
			'17439' => 17439,
			'17367' => 17367,
			'55125' => 55125,
			'40599' => 40599,
			'17257' => 17257,
			'55937' => 55937,
			'17295' => 17295,
			'17529' => 17529,
			'17479' => 17479,
			'17487' => 17487,
			'17447' => 17447,
			'17255' => 17255
    	);
    }
    
	/**
     * 知名品牌项目
     * @author 郁政
     */
    public static function getZhiMingPinPaiPro(){
    	return array(
	    	'55873' => 55873,
			'17407' => 17407,
			'17259' => 17259,
			'17281' => 17281,
			'17251' => 17251,
			'17521' => 17521,
			'55399' => 55399,
			'55507' => 55507,
			'17395' => 17395,
			'54913' => 54913,
			'55755' => 55755,
			'54845' => 54845,
			'54857' => 54857,
			'35009' => 35009,
			'55865' => 55865,
			'55201' => 55201,
			'54899' => 54899,
			'55023' => 55023,
			'55393' => 55393,
			'54989' => 54989,
			'55435' => 55435,
			'55687' => 55687,
			'55117' => 55117,
			'17223' => 17223,
			'54773' => 54773,
			'17355' => 17355,
    	);
    }
    
	/**
     * 商家推荐项目
     * @author 郁政
     */
    public static function getShangJiaTuiJianPro(){
    	return array(
	    	'55631' => 55631,
			'35119' => 35119,
			'55899' => 55899,
			'55683' => 55683,
			'55695' => 55695,
			'55901' => 55901,
			'55829' => 55829,
			'55841' => 55841,
			'56053' => 56053,
			'56055' => 56055,
			'55451' => 55451,
			'55215' => 55215,
			'55197' => 55197,
			'55867' => 55867,
			'55871' => 55871,
			'55877' => 55877,
			'56057' => 56057,
			'55525' => 55525,
			'55881' => 55881,
			'55887' => 55887,
			'55895' => 55895,
    	);    	
    }
    
    /**
     * 内网测试用
     * @author 郁政
     */
    public static function getAlphaTest(){
    	return array(
    		'6846' => 6846,
    		'6847' => 6847,
    		'6848' => 6848,
    		'6849' => 6849,	
    		'6850' => 6850,
    		'6852' => 6852,
    		'6853' => 6853,
    		'6854' => 6854,
    		'6855' => 6855,
    		'6856' => 6856,
    		'6857' => 6857,
    		'6858' => 6858,
    		'6859' => 6859,
    		'6860' => 6860,
    		'6861' => 6861,
    		'6862' => 6862,
    		'6863' => 6863,
    		'6865' => 6865,
    		'6867' => 6867,
    		'6871' => 6871    		
    	);
    }
    /**
     * 给好项目递送名片记录日志表设计 时间配置
     * @author 赵路生
     * @var array
     */
    public static function getSpecificProjectSetting() {
    	return array(
    			'start_time'=>'1398614400',// 2014.4.28 00:00:00
    			'end_time'=>'1463155200', // 2016.5.14 00:00:00 //注释掉
    			'project_ids'=>array_unique(array_merge(common::getZuiJiaChuangYePro(),common::getChuangYeShouXuanPro(),common::getZuiXinShangJiPro(),common::getJingPinTuiJianPro(),common::getZhiMingPinPaiPro(),common::getShangJiaTuiJianPro())),
    			'medal'=>array('铜牌','银牌','金牌'),
    			// 用户显示配置文件
    			'medal_class'=>array('1'=>'tongspan','2'=>'yinspan','3'=>'jinspan'),
    	);
    }
    
    /**
     * 获取地区全拼
     * @author 郁政
     */
    public static function getAreaPinYin($id = 0){
    
    	$area_arr =  array(    		
    		'1' => 'guangdong',
    		'2' => 'beijing',
    		'3' => 'tianjin',
    		'4' => 'hebei',
    		'5' => 'shanxi',
    		'6' => 'neimenggu',
    		'7' => 'liaoning',
    		'8' => 'jilin',
    		'9' => 'heilongjiang',
    		'10' => 'shanghai',
    		'11' => 'jiangsu',
    		'12' => 'zhejiang',
    		'13' => 'anhui',
    		'14' => 'fujian',
    		'15' => 'jiangxi',
    		'16' => 'shandong',
    		'17' => 'henan',
    		'18' => 'hubei',
    		'19' => 'hunan',
    		'20' => 'guangxi',
    		'21' => 'hunan',
    		'22' => 'chongqing',
    		'23' => 'sichuan',
    		'24' => 'guizhou',
    		'25' => 'yunnan',
    		'26' => 'xizang',
    		'27' => 'sanxi',
    		'28' => 'gansu',
    		'29' => 'qinghai',
    		'30' => 'ningxia',
    		'31' => 'xinjiang',
    		'32' => 'taiwan',
    		'33' => 'xianggang',
    		'34' => 'aomen'
    	);
    	return $id ? arr::get($area_arr, $id, '') : $area_arr;
    }
    
    /**
     * 快速发布向导首页热门城市配置
     * @author 郁政
     */
    public static function getXiangDaoCityConfig(){
    	return array(
    		'88' => '全国',
    		'10' => '上海',
    		'2' => '北京',
    		'1' => '广东',
    		'3' => '天津',
    		'4' => '河北',
    		'7' => '辽宁',
    		'11' => '江苏',
    		'12' => '浙江',
    		'13' => '安徽',
    		'14' => '福建',
    		'15' => '江西',
    		'17' => '河南',
    		'18' => '湖北',
    		'22' => '重庆',
    		'23' => '四川',
    		'29' => '青海',
    		'19' => '湖南',
    		'24' => '贵州',
    		'20' => '广西'
    	);
    }

    //判断远程图片或文件是否存在 @花文刚
    public static function check_remote_file_exists($url)
    {
        $curl = curl_init($url);
        //不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //发送请求
        $result = curl_exec($curl);
        $found = false;
        if ($result !== false) {
            //检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $found = true;
            }
        }
        curl_close($curl);
        return $found;

    }
}

?>