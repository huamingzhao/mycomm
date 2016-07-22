<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 个人名片
 * @author：兔毛 2014-05-21
 */
class Service_QuickPublish_Card {
	
	/**
	 * 用户id：X发出/收到的名片总数
	 * param $user_id: 发出/收到的用户id
	 * param $from_or_to: 发出/收到的字段前缀 from或to
	 * @author：tumao 2014-05-21
	 */
	public function getFromOrToCardCount($user_id,$from_or_to){
		return ORM::factory('QuickCardinfo')
		->where($from_or_to.'_user_id','=',$user_id)//发送用户为当前登录用户id
		->where($from_or_to.'_del_status','=',0)//未删除状态
		->count_all();
	}
	
	
	
	/**
	 * 用户X+项目id，收到的名片数
	 * @param unknown_type $project_id：项目id
	 * author: 兔毛  2014-05-23
	 */
	public function get_to_user_card_count($project_id,$user_id=0){
		$result= ORM::factory('QuickCardinfo')
		->where('to_del_status','=',0)
		->where('to_user_id','=',$user_id)
		->where('to_project_id','=',$project_id)
		->count_all();
		return $result;
	}
	
	
	
	/**
	 * 判断A是否向B发过名片。字段：exchange_status
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * author: 兔毛  2014-05-21
	 */
	public function is_exchange($from_user_id,$to_user_id)
	{
		return ORM::factory('QuickCardinfo')
		->where('from_user_id','=',$from_user_id)
		->where('to_user_id','=',$to_user_id)
		->count_all();
	}


    /**
	 * 发送卡片信息
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * author: 兔毛  2014-05-22
	 */
	public function getCardInfo($from_user_id,$to_user_id)
	{
		return ORM::factory('QuickCardinfo')
		->where('from_user_id','=',$from_user_id)
		->where('to_user_id','=',$to_user_id)
		->find()->as_array();
	}
	
	/**
	 *【企业用户】收到的咨询/留言
	 * @param  [array] $search [post获取当前页面搜索条件]
	 * @param  [int] $userid [当前登录用户ID]
	 * @author：兔毛 2014-05-24
	 */
	public function searchReceiveCardInfo($search,$userid,$project_id=0){
		$model= ORM::factory('QuickCardinfo');
		//个人用户信息表、发送记录表 2张表左连接获取投资者所有信息
		if(empty($project_id))
		{
			$model->join('user_person','LEFT')->on('per_user_id','=','from_user_id')
			->where('to_user_id','=',$userid)//发送记录表的接收用户为当前登录用户id
			->where('to_del_status','=',0);//(我收到的名片 status=0)未删除的名片
			$model->join('user_personal_industry','LEFT')->on('user_id','=','from_user_id');
		}
		else
		{
			$model->join('user_person','LEFT')->on('per_user_id','=','from_user_id')
			->where('to_user_id','=',$userid)//发送记录表的接收用户为当前登录用户id
			->where('to_project_id','=',$project_id)
			->where('to_del_status','=',0);//(我收到的名片 status=0)未删除的名片
			$model->join('user_personal_industry','LEFT')->on('user_id','=','from_user_id');
		}
		return $this->addSearchCondition($model,$search,$userid,1);
	}
	 
	/**
	 * 添加筛选条件（我收到的、我递出的投资名片信息列表）
	 * @author 钟涛
	 */
	protected function addSearchCondition($model,$search,$userid,$tpye){
		//对发送名片记录表添加筛选条件(收到名片时间、名片显示)
		$logmodel= ORM::factory('QuickCardinfo');
		//获取搜索列的相关信息(具体哪些列为搜索的列在对应的Model中$_search_row定义)
		$cardlog_search_row = $logmodel->getSearchRow();
		foreach($cardlog_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				if($key=='send_time'){//对收到名片时间做筛选
					if($search[$key]=='10000') {//10000定义为收到名片时间：半年以上
						$model->where($key, '<=', time()-(180*24*60*60));
					}else{//其他的收到名片时间：1天、2天。。等
						$model->where($key, '>=', time()-($search[$key]*24*60*60));
					}
				}
				else {//对名片显示做筛选(不限、已查看名片、未查看名片)
					$model->where($key, '=', $search[$key]);
				}
			}
		}
		//对投资者个人信息表添加筛选条件(投资金额)
		$permodel= ORM::factory('Personinfo');
		$search_row = $permodel->getSearchRow();
		foreach($search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资金额）
				$model->where($key, '=', $search[$key]);
			}
		}
		//对投资者个人 投资行业 进行筛选
		$perindustrymodel= ORM::factory('UserPerIndustry');
		$perindustry_search_row = $perindustrymodel->getSearchRow();
		foreach($perindustry_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资行业）
				$model->where($key, '=', $search[$key]);
			}
		}
		if($tpye==1){
			$model->group_by('from_user_id');
		}else{
			$model->group_by('to_user_id');
		}
		$page_arr=$model->select('*')->reset(false)->find_all( );
		$page = Pagination::factory(array(
				'total_items'    => count($page_arr),
				'items_per_page' => 10,
		));
		$listArr=$model->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('send_time', 'DESC')->find_all( );
		//d($listArr);
		$userlist=array();
		$resultlist=array();
		$userservice=new Service_User_Company_User();
		$per_service = new Service_User_Person_User();
		foreach ($listArr as $list){
			$userlist['this_per_industry']=$per_service->getPersonalIndustryString($list->per_user_id);
			if($userservice->getExperienceCount($list->per_user_id)) {//已添加从业经验
				$userlist['per_experience_stutas']=1;
			}else{//未添加从业经验
				$userlist['per_experience_stutas']=0;
			}
			if($this->getFavoriteStatus($userid,$list->per_user_id)==TRUE){
				$userlist['favorite_status']=1;//已收藏此名片
			}else{
				$userlist['favorite_status']=0;//未收藏此名片
			}
			//判断是否有留言
			$userlist['isHasLetter']=$this->get_letter_count($list->per_user_id,$userid);
			$resultlist[] = array_merge($list->as_array(),$userlist);
		}
		return array(
				'page' => $page,
				'list' =>$resultlist,
		);
	}
	
	
	/**
	 * 判断是否已经收藏
	 * @author周进
	 * @param int $user_id 当前用户
	 * @param int $favorite_user_id 关联用户ID
	 * @return bool
	 */
	public function getFavoriteStatus($user_id,$favorite_user_id){
		$user_id = intval($user_id);
		$favorite_user_id = intval($favorite_user_id);
		$favorite = ORM::factory('Favorite');
		$data = $favorite->where('favorite_user_id', '=', $favorite_user_id)
		->where('user_id','=',$user_id)->where('favorite_status','=','1')->count_all();
		if ($data==1){//已经存在收藏并且收藏状态为1
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/**
	 * 新增发送名片记录
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * @param unknown_type $postdata: 其他数据参数
	 * author: 兔毛  2014-05-21
	 */
	public function addCardInfo($from_user_id,$to_user_id,$postdata){
		try{
			$data=ORM::factory('QuickCardinfo');
			$data->from_user_id=$from_user_id;
			$data->to_user_id=$to_user_id;
			$data->send_time=time();    //发送时间
            $data->send_count=isset($postdata['send_count'])? $postdata['send_count'] : 1;  //发送次数
			$data->exchange_status=isset($postdata['exchange_status']) ? $postdata['exchange_status'] :0;  //记录名片是否已交换（默认0未交换，1代表已交换）
			$data->exchange_time=time();      //名片交换时间
			$data->to_read_status=isset($postdata['to_read_status'])? $postdata['to_read_status'] :0 ;    //记录我收到的名片阅读状态（默认0未阅读，1代表已读）
			$data->to_read_time=isset($postdata['to_read_time'])?$postdata['to_read_time']: NULL;        //记录我收到的名片阅读时间
			//记录我递出的名片阅读状态（默认递出时为1：已读的状态，但接收者点击交换或者给我发送名片时，修改为未读状态）
			$data->from_read_status=isset($postdata['from_read_status'])?$postdata['from_read_status']: 1;
			$data->from_read_time=isset($postdata['from_read_time'])?$postdata['from_read_time']:NULL;    //记录我递出的名片阅读时间
			$data->to_del_status=isset($postdata['to_del_status'])?$postdata['to_del_status']:0;      //记录接收者删除名片记录（默认0未删除，1为已删除）
			$data->to_del_time=isset($postdata['to_del_time'])?$postdata['to_del_time']:NULL;          //记录接收者删除名片时间
			$data->from_del_status=isset($postdata['from_del_status'])?$postdata['from_del_status']:0;  //记录发送者删除名片记录（默认0未删除，1为已删除）
			$data->from_del_time=isset($postdata['from_del_time'])?$postdata['from_del_time']:NULL;      //记录发送者删除名片时间
			$data->to_project_id=isset($postdata['to_project_id'])?$postdata['to_project_id']:0;      //记录项目id[个人留言]
			$data->ip=isset($postdata['ip'])?$postdata['ip']:ip2long(Request::$client_ip);      //留言ip                       
			$data->card_type=isset($postdata['card_type'])?$postdata['card_type']:0;  //记录名片类型默认0：一句话pc端，1为手机端递送名片
			$data->create();
			return true;
		}catch(Kohana_Exception $e){
			return false;
		}
	}


    /**
	 * 更新发送名片记录
	 * @param unknown_type $card_id：记录id
	 * @param unknown_type $send_count：发送次数
	 * @param unknown_type $postdata: 其他数据参数（预留）
	 * author: 兔毛  2014-05-21
	 */
	public function updateCardInfo($card_id,$send_count,$postdata=null)
	{
		try{
               $data=ORM::factory('QuickCardinfo');
               $result=$data->where('card_id','=',$card_id)->find_all();
               if(count($result)){
	               foreach($result as $vs){
	                   $vs->send_count = $send_count;
	                   $vs->update();
	               }
               return true;
            }
		}catch(Kohana_Exception $e){
			return false;
		}
	}
	
	
	/**
	 * 判断A是否向B发过留言
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * author: 兔毛  2014-05-21
	 */
	public function get_letter_count($from_user_id,$to_user_id)
	{
		return ORM::factory('QuickUserletter')
		->where('user_id','=',$from_user_id)
		->where('to_user_id','=',$to_user_id)
		->count_all();
	}
	
	
	/**
	 * 根据项目id,得到项目留言数
	 * @param unknown_type $project_id：项目id
	 * author: 兔毛  2014-05-23
	 */
	public function getLetterByProjectid($project_id){
		return ORM::factory('QuickUserletter')
		->where('to_project_id','=',$project_id)
		->count_all();
	}
	
	
	/**
	 * 【个人】发出的留言个人中心--参考：获取历史咨询
	 * @param $user_id：用户id
	 * @return array
	 */
	public function getHistoryConsult($user_id=0,$project_id=0){
		$user_id = intval($user_id);
		$return = array();
		if($user_id){
			if(empty($project_id))
				$model = ORM::factory('QuickUserletter')->where('user_id','=',$user_id)->where('to_project_id','!=',0)->where('letter_status','=',1);
			else
				$model = ORM::factory('QuickUserletter')->where('user_id','=',$user_id)->where('to_project_id','=',$project_id)->where('letter_status','=',1);
			$page = Pagination::factory(array(
					'total_items'    => $model->reset(false)->count_all(),
					'items_per_page' => 10,
			));
			$return['list'] = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('add_time', 'DESC')->find_all();
			$return['page'] = $page;
		}
		return $return;
	}
	

	/**
	 * 个人递出名片[快速注册]
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * @param unknown_type $postdata: 其他数据参数
	 * author: 兔毛  2014-05-21
	 */
    public function addOutCardQuickRegister($from_user_id,$to_user_id, $postdata) 
	{
		try{
			/*if(!empty($from_user_id))
			{
				$send_count=1;
				$is_exchange_count=0;  
				$info=$this->getCardInfo($from_user_id,$to_user_id);
				if(!empty($to_user_id))
				{
					$send_count=isset($info['send_count'])?$info['send_count']*1+1:1;	
					$is_exchange_count=$this->is_exchange($to_user_id,$from_user_id); //判断A是否向B发过名片
				}
				if($send_count==1)
					$is_oper_card_info=$this->addCardInfo($from_user_id,$to_user_id,$postdata);
				else
				{
					$card_id=$info['card_id'];
					$is_oper_card_info=$this->updateCardInfo($card_id,$send_count,$postdata);
				}
			}*/
			$is_oper_user_letter_info=$this->addUserLetterInfo($from_user_id,$to_user_id,$postdata);
			if($is_oper_user_letter_info){
				return true;
			}
			else
				return false;
		}catch(Kohana_Exception $e){
			return false;
		}
    }


	/**
	 * 新增发送咨询记录
	 * @param unknown_type $from_user_id：发送人
	 * @param unknown_type $to_user_id：接收人
	 * @param unknown_type $postdata: 其他数据参数
	 * author: 兔毛  2014-05-21
	 */
	public function addUserLetterInfo($from_user_id,$to_user_id,$postdata){
		//echo "<pre>"; print_r($postdata);exit;
		try{
			$data=ORM::factory('Message');
		/*	echo $from_user_id;
			echo '--'.$to_user_id;
			print_r($postdata);exit;*/
			//print_r($postdata);exit;
			$data->from_user_id=$from_user_id;  //记录发送用户id
			$data->to_user_id=$to_user_id;
			$data->name=arr::get($postdata, "user_name");
			$data->email=arr::get($postdata, "email");
			$data->mobile=arr::get($postdata, "mobile");
			$data->content=isset($postdata['content'])?$postdata['content']:'';  //发信内容
			//$data->user_type=isset($postdata['user_type'])?$postdata['user_type']:1;  //发送用户类型【默认1：企业用户,2:个人用户】
			$data->status=isset($postdata['letter_status'])?$postdata['letter_status']:1;  //信息状态【默认1：启用,2:已删除】
			$data->add_time=time();  //添加时间
			//$data->letter_type=isset($postdata['letter_type'])? $postdata['letter_type']:1;  //个人用户留言类型1:我要咨询；2:索要资料；3:发送意向
			//$data->project_id=isset($postdata['to_project_id'])? $postdata['to_project_id']:0;      //记录项目id[个人留言]
			$data->project_id=isset($postdata['to_project_id'])? $postdata['to_project_id']:0;      //记录项目id[个人留言]
		//	$data->letter_from_type=isset($postdata['letter_from_type'])?$postdata['letter_from_type']:0;  //记录类型默认0：一句话pc端，1为手机端递送名片
			//$data->letter_ip=isset($postdata['letter_ip'])?$postdata['letter_ip']:ip2long(Request::$client_ip);      //留言ip
			//$data->fromdomain=isset($postdata['fromdomain'])?$postdata['fromdomain']:NULL;      //入口域名
			$data->create();
			return true;
		}catch(Kohana_Exception $e){
			//print_r($e);exit;
			return false;
		}
	}


	
	

	/**
	 * 更改我收到的名片为已删除状态
	 * @author 参考：钟涛
	 */
	public function updateReceiveDelStatus($cardid){
		$model= ORM::factory('QuickCardinfo',$cardid);
		//接收者删除名片记录--1为已删除
		$model->to_del_status =1;
		//接收者删除名片时间
		$model->to_del_time =time();
		if($model->save()) {
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * 批量更改我收到的名片为已删除状态
	 * @author 参考：钟涛
	 */
	public function updateBatchReceiveDelStatus($cardidarr){
		if(!empty($cardidarr)){
			foreach($cardidarr as $cardid){
				$model= ORM::factory('QuickCardinfo',$cardid);
				//接收者删除名片记录--1为已删除
				$model->to_del_status =1;
				//接收者删除名片时间
				$model->to_del_time =time();
				if($model->loaded()){
					$model->update();
				}
			}
		}
	}
	
    /**
     * 个人生成名片图片
     * @author 参考：钟涛
     */
    public function getPersonCardImage($user_id){
        $user_service=new Service_User_Person_User();
        //个人基本信息
        $personinfo=$user_service->getPersonInfo($user_id);
        $background_image=URL::webstatic("images/card_image/person_card_background.jpg");
        $myImage = imagecreatefromjpeg($background_image);
        $name_clore=ImageColorAllocate($myImage, 40, 96, 162);
        $name_clore2=ImageColorAllocate($myImage, 2, 65, 135);
        $phone_clore=ImageColorAllocate($myImage, 0, 162, 255);
        //黑色字体
        $black=ImageColorAllocate($myImage, 0, 0, 0);
        $username= mb_substr($personinfo['user_person']->per_realname,0,4,'UTF-8');
        $length=strlen($username);
        $x_length=178;
        if($length<=6){
            $x_length=148;
        }
        if($personinfo['user_person']->per_gender ==1){
            $usersex= '先生';
        }else{
            $usersex= '女士';
        }
        $phone= $personinfo['mobile'];
        $email= $personinfo['email'];
        //字体微软雅黑
        $font='modules/fonts/msyh.ttf';
        //宋体
        $font2='modules/fonts/simsun.ttc';
        //姓名
        imagettftext($myImage, 28, 0, 62, 70, $name_clore, $font,  $username);
        //先生or女士
        imagettftext($myImage, 16, 0, $x_length, 72, $name_clore, $font,  $usersex);
        //手机号
        imagettftext($myImage, 21, 0, $x_length+50, 68, $phone_clore, $font, $phone);
        //邮箱
        imagettftext($myImage, 16, 0, 64, 120, $phone_clore, $font, $email);
        //个人所在地
        imagettftext($myImage, 11, 0, 64, 200, $name_clore2, $font, '个人所在地：');
        imagettftext($myImage, 11, 0, 270, 200, $name_clore2, $font, '投资金额：');
        imagettftext($myImage, 11, 0, 64, 230, $name_clore2, $font, '投资行业：');
        imagettftext($myImage, 11, 0, 64, 260, $name_clore2, $font, '投资地区：');
        imagettftext($myImage, 11, 0, 64, 290, $name_clore2, $font, '我的标签：');
        imagettftext($myImage, 11, 0, 64, 320, $name_clore2, $font, '个性说明：');
        //个人所在地
        $adress_this = $user_service->getPerasonalAreaString($user_id);
        //投资金额
        $monarr= common::moneyArr();
        $mon_data= $personinfo['user_person']->per_amount== 0 ? '无': $monarr[$personinfo['user_person']->per_amount];
        //投资行业
        $industry=$user_service->getPersonalIndustryString($user_id);
        //获得个人地域信息
        $area = $user_service->getPersonalArea($user_id);
        //个性说明
        $remark=mb_substr($personinfo['user_person']->per_remark,0,32,'UTF-8');
        imagettftext($myImage, 10, 0, 154, 200, $black, $font, $adress_this);
        imagettftext($myImage, 10, 0, 346, 200, $black, $font, $mon_data);
        imagettftext($myImage, 10, 0, 145, 230, $black, $font, $industry);
        imagettftext($myImage, 10, 0, 145, 260, $black, $font, $area);
        imagettftext($myImage, 10, 0, 145, 290, $black, $font, $personinfo['user_person']->per_per_label);
        imagettftext($myImage, 10, 0, 145, 320, $black, $font, $remark.'...');
        //header("Content-type:image/jpeg");
        //ImagePng($myImage);
        $image_path='application/cache/card_image/mycardimage_'.$user_id.'.jpg';
        //添加到缓存
        imagejpeg($myImage,$image_path);
        //上传到服务器
        $files= array();
        $files['per_card_image']['tmp_name']=$image_path;
        $files['per_card_image']['size']=36613;
        $files['per_card_image']['name']='mycardimage_'.$user_id.'.jpg';
        $files['per_card_image']['type']='image/jpeg';
        $files['per_card_image']['error']='0';
        $size = getimagesize($files['per_card_image']['tmp_name']);
        //大小图的长宽
        $w=$size[0];$h=$size[1];
        $img = common::uploadPic($files,'per_card_image',array(array($w,$h)));
        if($img['error']==''){//添加成功
            $perinfo=  ORM::factory('Personinfo')->where('per_user_id','=',$user_id)->find();
            //删除之前的图片
            common::deletePic(URL::imgurl($perinfo->per_card_image));
            //企业基本信息表保存当前路径
            $s_path = Arr::get($img,'path');
            $perinfo->per_card_image=common::getImgUrl($s_path);
            $perinfo->save();
        }
        //释放资源
        ImageDestroy($myImage);
        //删除缓存的图片
        if (file_exists($image_path)) {
           unlink($image_path);
        }
    }
    
}
?>