<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯--创业问题我知道
 * @author 钟涛
 *
 */
class Service_News_Ask{

    /**
     * 获取总共问题数量
     * @author钟涛
     */
    public function getAskCunt(){
        return ORM::factory('Zixun_AskTitle')->where('ask_status','=',1)->count_all();
    }
    //end function
    
    /**
     * 获取首页广告图片信息
     * @author钟涛
     */
    public function getAskImage(){
    	$model = ORM::factory('Advertising')->where('type','=',1)->order_by('add_time','desc')->limit(1)->find_all();
    	if(count($model)){
	    	foreach($model as $v){
	    		return $v->as_array();
	    	}
    	}else{
    		return array();
    	}
    }
    //end function
    
    /**
     * 获取首页广告问答信息
     * @author钟涛
     */
    public function getAskHeadInfo(){
    	$model = ORM::factory('Zixun_AskTitle')->where('ask_status','=',1)->where('ask_ordinary_type','=',2)->order_by('add_time','desc')->limit(1)->find_all();
    	if(count($model)){
    		foreach($model as $v){
    			$retrunarr=array();
    			$retrunarr['ask_id']=$v->ask_id;
    			$retrunarr['ask_name']=UTF8::substr($v->ask_name, 0,20);
    			$retrunarr['ask_describe']=UTF8::substr($v->ask_describe, 0,50).'...';
    			//推荐答案信息
    			$answer_model = ORM::factory('Zixun_AskAnswer')
    						->where('ask_id','=',$v->ask_id)
    						->where('ask_answer_adopt_type','=',1)
    						->find();
    			$retrunarr['answer_describe']=UTF8::substr($answer_model->ask_answer_describe, 0,50).'...';
    			return $retrunarr;
    		}
    	}else{
    		return array();
    	}
    }
    //end function
    
    /**
     * 获取pv值最高的4个项目
     * @author钟涛
     */
    public function getAskPvCountList(){
    	$model = ORM::factory('Zixun_AskTitle')
    			->where('ask_status','=',1)
    			->where('ask_ordinary_type','!=',2)
    			->order_by('ask_pv_count','desc')
    			->limit(4)
    			->find_all();
    	return $model;
    }
    //end function
    /**
     * 获取pv值最高的n（default 100）个项目【最近三个月】
     * @author 赵路生
     * 从100条数据中返回最大$num(<=100)条
     */
    public function getAskPvCountListLatest($num=100,$time=0){
    	$memcache = Cache::instance ( 'memcache' );
    	$return = array();
    	$num = $num >100 ? 100 :$num ;
    	$return_cache = $memcache->get('getAskPvCountListLatest'.$num);
    	if(count($return_cache)){
    		$return = $return_cache;
    	}else{
    		$result = ORM::factory('Zixun_AskTitle')->where('ask_status','=',1)->where('ask_ordinary_type','!=',2)->where('add_time','>=',$time)
    				->order_by('ask_pv_count','desc')->limit(100)->find_all()->as_array();
    		if(count($result)){
    			$return = $result;
    			$memcache->set('getAskPvCountListLatest'.$num,$result,86400);
    		}
    	}   	
    	shuffle($return);
		$return = count($return)>$num ? array_slice($return,0,$num) : $return;     	
    	return $return;
    }
    /**
     * 获取常用问题
     * @author钟涛
     */
    public function getAskUsedList($limit=10){
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getAskUsedList'.$limit);
    	if($memarr){
    		return $memarr;
    	}else{
	    	$model = ORM::factory('Zixun_AskTitle')
	    	->where('ask_status','=',1)
	    	->where('ask_ordinary_type','=',1)
	    	->order_by('add_time','desc')
	    	->limit($limit)
	    	->find_all();
	    	$retrunlist=array();
	    	foreach($model as $v){
	    		$retrunlist[$v->ask_id]['id']=$v->ask_id;
	    		$retrunlist[$v->ask_id]['ask_name']=$v->ask_name;
	    	}
	    	$memcache->set('getAskUsedList'.$limit,$retrunlist,$_cache_get_time);
	    	return $retrunlist;
    	}
    }
    //end function
    
    /**
     * 获取推荐最近新的资讯文章
     * @author钟涛
     */
    public function getAskZixunList($limit=10){
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getAskZixunList'.$limit);
    	if($memarr){
    		return $memarr;
    	}else{
    		$model = ORM::factory('Zixun_Article')
    		->where('article_status','=',2)
    		->order_by('article_intime','desc')
    		->limit($limit)
    		->find_all();
    		$retrunlist=array();
    		foreach($model as $v){
    			$retrunlist[$v->article_id]['id']=$v->article_id;
    			$retrunlist[$v->article_id]['article_name']=$v->article_name;
    			$retrunlist[$v->article_id]['article_addtime']=$v->article_intime;
    		}
    		$memcache->set('getAskZixunList'.$limit,$retrunlist,$_cache_get_time);
    		return $retrunlist;
    	}
    }
    //end function
    
    /**
     * 获取一级分类对应规则【目前一级分类14个是固定的，可以从czzs_ask_industry表中获取】
     * @author 钟涛
     * @var array
     */
    public static function getAskIndustryOneArr() {
    	return array(
    			'daili' => '代理',
    			'jiameng' => '加盟',
    			'pifa' => '批发',
    			'kaidian' => '开店',
    			'chuangye' => '创业',
    			'xiaoshou' => '销售',
    			'touzi' => '投资',
    			'shangji' => '商机',
    			'shengyi' => '生意',
    			'zhuanqian' => '赚钱',
    			'maimai' => '买卖',
    			'zhifu' => '致富',
    			'facai' => '发财',
    			'xiangmu' => '项目',
    	);
    }
    /**
     * 获取一级分类对应规则【目前一级分类14个是固定的，可以从czzs_ask_industry表中获取】
     * @author 赵路生
     * @var array
     */
    public static function getAskIndustryOneArrById() {
    	return array(
    			'1' => 'daili',
    			'2' => 'jiameng',
    			'3' => 'pifa',
    			'4' => 'kaidian',
    			'5' => 'chuangye',
    			'6' => 'xiaoshou',
    			'7' => 'touzi',
    			'8' => 'shangji',
    			'9' => 'shengyi',
    			'10' => 'zhuanqian',
    			'11' => 'maimai',
    			'12' => 'zhifu',
    			'13' => 'facai',
    			'14' => 'xiangmu',
    	);
    }
    /**
     * 根据分类id获取他的1级分类和2级所有分类
     * @author 钟涛
     * @var array
     */
    public static function getAskIndustry($id=1) {
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getAskIndustryBy'.$id);
    	if($memarr){
    		return $memarr;
    	}else{
			if(!intval($id) || !$id){//非法值默认都为1
				$id=1;
			}
			$returnlist=array();
			$model = ORM::factory('Zixun_AskIndustry',$id);
			if(!$model->loaded()){//没找到 默认1
				$id=1;
				$model = ORM::factory('Zixun_AskIndustry',$id);
			}
			if($model->ask_parent_id==0){//就是一级的行业
				$returnlist['one_id']=$id;
				$returnlist['one_name']=$model->ask_industry_name;
				$onekeyid=$id;//1级id
			}else{//选择的是2级行业
				$returnlist['one_id']=$model->ask_parent_id;
				$returnlist['one_name']=ORM::factory('Zixun_AskIndustry',$model->ask_parent_id)->ask_industry_name;
				$onekeyid=$model->ask_parent_id;//1级id
			}
			//获取所以2级分类
			$model2 = ORM::factory('Zixun_AskIndustry')
					->where('ask_parent_id','=',$onekeyid)
					->where('ask_industry_status','=',1)
					->find_all();
			foreach($model2 as $v){
				$returnlist['two'][$v->ask_industry_id]['two_id']=$v->ask_industry_id;
				$returnlist['two'][$v->ask_industry_id]['two_name']=$v->ask_industry_name;
			}
			$memcache->set('getAskIndustryBy'.$id,$returnlist,$_cache_get_time);
			return  $returnlist;
    	}
    }
    
    /**
     * 根据分类id获取他的分类名称
     * @author 钟涛
     * @var array
     */
    public static function getAskIndustryName($id=1) {
    	if(!intval($id) || !$id){//非法值默认都为1
    		$id=1;
    	}
    	return ORM::factory('Zixun_AskIndustry',$id)->ask_industry_name;
    }
    
    /**
     * 根据分类id获取seo推荐内容
     * @author 钟涛
     * @var array
     */
    public function getAskIndustrySeoContent($id=1) {
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getAskIndustrySeoContent'.$id);
    	if($memarr){
    		return $memarr;
    	}else{
        	if(!intval($id) || !$id){//非法值默认都为1
    			$id=1;
    		}
    		$returnlist=array();
    		$model = ORM::factory('Zixun_AskIndustry',$id);
    		if(!$model->loaded()){//没找到 默认1
    			$id=1;
    			$model = ORM::factory('Zixun_AskIndustry',$id);
    		}
    		if($model->ask_parent_id==0){//就是一级的行业
    			$second_industry_arr = $this->getAskSecIndByFirInd($id);// 根据一级行业获取所有的二级行业
    			$second_industry = '';
    			foreach($second_industry_arr as $value){
    				$second_industry .= $value.'问题、';
    			}
    			// 如果一级分类没有二级分类的时候将使用二级分类
    			$second_industry = trim($second_industry,'、') ? trim($second_industry,'、') :$model->ask_industry_name.'问题';
				// $content=$model->ask_industry_name.'频道有大量的创业问答，我们为投资创业者解答各种'.$model->ask_industry_name.'难题、问题，提供最专业、最好的创业问答平台！'.$model->ask_industry_name.'问题上一句话创业问答网，总有一个'.$model->ask_industry_name.'问答是您需要的。';
    			$content = '一句话创业问答'.$model->ask_industry_name.'频道有大量的'.$model->ask_industry_name.'问题，所有'.$model->ask_industry_name.'问题是投资者在创业过程中遇到的问题。有大量的网友在创业过程中遇到了'.$second_industry.'都会选择一句话创业问答网求助，热心的网友都会积极回答遇到的各种'.$model->ask_industry_name.'问题。
总之，创业路上遇到'.$model->ask_industry_name.'问题上一句话创业问答网，总有一个'.$model->ask_industry_name.'问答是您需要的。';
    		}else{//选择的是2级行业
    			// $model2 = ORM::factory('Zixun_AskIndustry',$model->ask_parent_id);
				// $content=$model2->ask_industry_name.'频道有大量的创业问答，我们为投资创业者解答各种'.$model->ask_industry_name.'难题、问题，提供最专业、最好的创业问答平台！'.$model->ask_industry_name.'问题上一句话创业问答网，总有一个'.$model->ask_industry_name.'问答是您需要的。';
    			$content = '创业问答'.$model->ask_industry_name.'频道涵盖所有投资者创业路上遇到的各种'.$model->ask_industry_name.'问题，所有'.$model->ask_industry_name.'问题见证了创业者在创业路上的辛酸史。我们拥有庞大的网友选择该平台，一句话创业问答网专为创业者提供创业过程中较常遇到的'.$model->ask_industry_name.'疑难问题的解答，为广大'.$model->ask_industry_name.'行业的创业同路人能更好的交流、学习、分享提供了优质的互动平台。
欢迎创业者畅所欲言，分享自己对'.$model->ask_industry_name.'行业的独到见解、积极帮助对'.$model->ask_industry_name.'行业有所困惑的创业同路人！';
    		}
    		$memcache->set('getAskIndustrySeoContent'.$id,$content,$_cache_get_time);
    		return $content;
    	}
    }
    
    /**
     * 获取分类问题列表
     * @author 钟涛
     * @var array
     */
    public static function getFenLeiList($industryid=1,$type=0,$fenlei_type=0,$oldpage=1,$fenlei_pagetype=1) {
    	if($fenlei_type==0){
    		$_GET ['page']=1;
    	}else{
    		$_GET ['page']=$oldpage;
    	}
    	$model = ORM::factory('Zixun_AskIndustry',$industryid);
    	$industrytype=1;
    	if(!$model->loaded()){//没找到 默认1
    		$industryid=1;
    	}else{
    		if($model->ask_parent_id>0){//2级分类
    			$industrytype=2;
    		}
    	}
    	$asktitlemodel = ORM::factory('Zixun_AskTitle')->where('ask_status','=',1);
    	if($industrytype==2){//2级分类
    		$asktitlemodel->where('ask_category_second','=',$industryid);
    	}else{
    		$asktitlemodel->where('ask_category_first','=',$industryid);
    	}
    	//是否已经采纳的
    	if($type==1){//已经被采纳
    		$asktitlemodel->where('ask_adopt_type','=',1);
    	}elseif($type==2){//未被采纳
    		$asktitlemodel->where('ask_adopt_type','=',0);
    	}else{	}
    	$key ='page';
    	if($industrytype==2){//2级分类
			$source='wendasec'.$fenlei_pagetype;
			$_GET ['oneid']=$model->ask_parent_id;
			$_GET ['twoid']=$industryid;
		}else{
			$source='wendafirst'.$fenlei_pagetype;
		}
    	$page = Pagination::factory(array(
    			'total_items' => $asktitlemodel->reset(false)->count_all(),
    			'view' => 'pagination/Simple',
    			'current_page' => array('source' => $source, 'key' => $key),
    			'items_per_page' => 23,
    	));
    	$listArr=$asktitlemodel->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('add_time', 'DESC')->find_all( );
    	return array(
    			'page' => $page,
    			'list' =>$listArr,
    	);
    }
    
    /**
     * 根据一级行业名称查找10个项目
     * @author钟涛
     */
    public function getProjecByOneindustry($id=1,$name='赚钱'){
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getProjecByOneindustry'.$id);
    	if($memarr){
    		$arrkey = array_rand($memarr,1);//随机获取1条数据
    		return $memarr[$arrkey];
    	}else{
	    	$Search = new Service_Api_Search();
	    	$searchresult = $Search->getSearch($name, '', 0, '');
	        $matches = arr::get($searchresult, 'matches', array());
	        $total = 0;
	        if(isset($searchresult['total'])) {
	            $total = $searchresult['total'];
	        }
	        $result_list=array();
			if($total<1){//没找到？
				$arrprojectinfo = ORM::factory('Project')
								->where('project_source','=',2)
								->where('project_status','=',2)
								->order_by('project_passtime','desc')
								->limit(5)->find_all();
				foreach($arrprojectinfo as $v){
					if($v->project_source != 1) {//项目logo图片
						$tpurl=project::conversionProjectImg($v->project_source, 'logo', $v->as_array());
						if(!project::checkProLogo($tpurl)){
							$tpurl=URL::webstatic('images/common/company_default.jpg');;
						}
					}else{
						$tpurl=URL::imgurl($v->project_logo);
						if(!project::checkProLogo($tpurl)){
							$tpurl=URL::webstatic('images/common/company_default.jpg');
						}
					}
					$result_list[] = array($v->project_id,$v->project_brand_name,$tpurl);
				}
			}else{
		    	//获取项目详细信息
		    	foreach($matches as $value){
		    		$projectinfo = ORM::factory('Project',$value['id']);
		    		if($projectinfo->project_source != 1) {//项目logo图片
		    			$tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
		    			if(!project::checkProLogo($tpurl)){
		    				$tpurl=URL::webstatic('images/common/company_default.jpg');;
		    			}
		    		}else{
		    			$tpurl=URL::imgurl($projectinfo->project_logo);
		    			if(!project::checkProLogo($tpurl)){
		    				$tpurl=URL::webstatic('images/common/company_default.jpg');
		    			}
		    		}
		    		$result_list[] = array($projectinfo->project_id,$projectinfo->project_brand_name,$tpurl);
		    	}
			}
			$memcache->set('getProjecByOneindustry'.$id,$result_list,$_cache_get_time);
			$arrkey = array_rand($result_list,1);//随机获取1条数据
			return $result_list[$arrkey];
    	}
    }
    //end function
    
    /**
     * 获取1周内收到名片最多的5个项目
     * @author 钟涛
     * @var array
     */
    public static function getTop5ProjectList() {
    	//做缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getTop5ProjectListByAsk1');
    	if($memarr){
    		return $memarr;
    	}else{
    		$acttime = time()-7*24*60*60;
    		$query1 = DB::select(array(DB::expr('COUNT(to_project_id)'), 'cardcount'),'to_project_id')
    		->from('card_info')
    		->where('send_time', '>=', $acttime)//7天内
    		->where('to_project_id', '>', 0)
    		->group_by('to_project_id')
    		->limit(5);
    		$result = $query1->execute()->as_array();
    		$returnarr=array();
    		foreach ($result as $v){
    			$projectinfo=ORM::factory('Project',$v['to_project_id']);
    			$returnarr[$v['to_project_id']]['projectid']=$v['to_project_id'];
    			$returnarr[$v['to_project_id']]['projectname']=$projectinfo->project_brand_name;
    			if($projectinfo->project_source != 1) {//项目logo图片
    				$tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
    				if(!project::checkProLogo($tpurl)){
    					$tpurl=URL::webstatic('images/common/company_default.jpg');;
    				}
    			}
    			else{
    				$tpurl=URL::imgurl($projectinfo->project_logo);
    				if(!project::checkProLogo($tpurl)){
    					$tpurl=URL::webstatic('images/common/company_default.jpg');
    				}
    			}
    			$returnarr[$v['to_project_id']]['projectimage']=$tpurl;
    		}
    		$memcache->set('getTop5ProjectListByAsk1',$returnarr,$_cache_get_time);
    		return $returnarr;
    	}
    }
    
    /**
     * 获取已经解决+未解决的问题
     * @author钟涛
     */
    public function getIsAnswerList($type=0){
    	//缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 600;// 缓存10分钟
    	$memarr=$memcache->get('getIsAnswerListByAsk'.$type);
    	$memarr = false;
    	if($memarr){
    		return $memarr;
    	}else{
	    	$model = ORM::factory('Zixun_AskTitle')->where('ask_status','=',1)->where('ask_adopt_type','=',$type)//默认0未被采纳
			    	->order_by('update_time','desc')
			    	->limit(12)
			    	->find_all();
	    	$list=array();
	    	$retrunlist=array();
	    	$nowtime=time();
	    	foreach($model as $v){
	    		$arr['id']=$v->ask_id;
	    		$arr['ask_name']=$v->ask_name;
	    		$list['time']=self::getTimeSection($nowtime-$v->update_time);
	    		$answercount=$this->getAskAnswerAmountById($v->ask_id);
	    		if($answercount<=0){
	    			$list['content']='暂无人回答';
	    		}elseif($answercount==1){//1人回答
	    			$username=ORM::factory('Zixun_AskAnswer')
	    					->where('ask_id','=',$v->ask_id)
	    					->where('ask_answer_status','=',1)
	    					->find()->ask_answer_user_name;
	    			if(!$username){
	    				$username='匿名者';
	    			}
	    			$list['content']=$username.' 1人参与了回答';
	    		}else{//2人回答
	    			$usernamelist=ORM::factory('Zixun_AskAnswer')
			    			->where('ask_id','=',$v->ask_id)
			    			->where('ask_answer_status','=',1)
			    			->limit(2)
			    			->find_all();
	    			$t=1;
	    			$username='';
	    			foreach($usernamelist as $v){
	    				if($t==1){
	    					if($v->ask_answer_user_name){
	    						$username=$v->ask_answer_user_name;
	    					}else{
	    						$username='匿名者';
	    					}
	    				}else{
	    					$username=$username.'、';
	    					if($v->ask_answer_user_name){
	    						$username .=$v->ask_answer_user_name;
	    					}else{
	    						$username.='匿名者';
	    					}
	    				}
	    				$t++;
	    			}
	    			$eg='';
	    			if($answercount>2){
	    				$eg='等';
	    			}
	    			$list['content']=$username.$eg.' 2人参与了回答';
	    		}
	    		$resultlist[] = array_merge($arr,$list);
	    	}
	    	$memcache->set('getIsAnswerListByAsk'.$type,$resultlist,$_cache_get_time);
	    	return $resultlist;
    	}
    }
    //end function
    
    /**
     * 时间
     * @author钟涛
     */
    public function getTimeSection($time_section){
    	if($time_section < 60){
    		return $time_section.' 秒前';
    	}elseif($time_section >= 60 && $time_section < 3600){
    		return floor($time_section/60).' 分钟前';//60
    	}elseif($time_section >= 3600 && $time_section < 86400){
    		return floor($time_section/3600).' 小时前';//24
    	}elseif($time_section >= 86400 && $time_section < 604800){
    		return floor($time_section/86400).' 天前';//7
    	}elseif($time_section >= 604800 && $time_section <2592000){
    		return floor($time_section/604800).' 周前';//4
    	}elseif($time_section >= 2592000 && $time_section <31536000){
    		return floor($time_section/2592000).' 个月前';//12
    	}elseif($time_section >= 31536000 && $time_section <3*31536000){
    		return floor($time_section/31536000).' 年前';//3
    	}else{
    		return '很久以前';
    	}
    }
    /**
     * 问答管理--获取问答一级分类
     * @author 赵路生
     * @param
     * @return array
     */
    public function getAskFirstIndustry(){
    	$model = ORM::factory('Zixun_AskIndustry');
    	$ask_first_industry = array();
    	$redis = Cache::instance("redis");
    	$check_result = $redis->get('ask_first_industry_clear');
    	$check_result = false;
    	if($check_result){
    		$ask_first_industry = $check_result;
    	}else{
    		//一级行业
    		$ask_first_industry = $model->where('ask_parent_id','=',0)->where('ask_industry_status','=',1)->order_by('ask_industry_order','ASC')->find_all()->as_array();
    		$redis->set('ask_first_industry_clear',$ask_first_industry,86400);
    	}
    	return $ask_first_industry;
    }
    /**
     * 问答管理--根据一级分类 获取 二级分类
     * @author 赵路生
     * @param $first_industry 一级分类
     * @return array
     * @默认值 使用的 1
     */
    public function getAskSecIndByFirInd($first_industry_id){
    	$first_industry_id = intval($first_industry_id);
    	$second_industry = array();
    	if(!$first_industry_id){
    		return $second_industry;
    	}
    	$redis = Cache::instance("redis");
    	$check_result = $redis->get('ask_second_industry_clear'.$first_industry_id);
    	if($check_result){
    		$second_industry = $check_result;
    	}else{
    		$model = ORM::factory('Zixun_AskIndustry',$first_industry_id);
    		if($model->loaded()){
    			//检查传入进来的是不是一级行业
    			if($model->ask_parent_id != 0){
    				return $second_industry;
    			}else{
    				$model = ORM::factory('Zixun_AskIndustry');
    				$second_industry_temp = $model->where('ask_parent_id','=',$first_industry_id)->where('ask_industry_status','=',1)->order_by('ask_industry_order','ASC')->find_all()->as_array();
    				foreach($second_industry_temp as $key=>$value){
    					$second_industry[$value->ask_industry_id] = $value->ask_industry_name;
    				}
    				$redis->set('ask_second_industry_clear'.$first_industry_id,$second_industry,86400);
    				return $second_industry;
    			}
    		}
    	}
    	return $second_industry;
    }
    /**
     * 添加问题提问
     * @author 赵路生
     * @param  $post
     * @return boolean
     */
    public function addMyAsk($post=array()){
    	$return = false;
    	// 需要更加严格的过滤 需要修改
    	$id = intval(arr::get($post,'id',0));
    	$ask_category_first = intval(arr::get($post,'ask_category_first',0));
    	$ask_category_second = intval(arr::get($post,'ask_category_second',0));
    	$ask_title =  mb_substr(trim(arr::get($post,'ask_title','')),0,30,'UTF-8');
    	$ask_description = mb_substr(nl2br(strip_tags(trim(arr::get($post,'ask_description','')))),0,300,'UTF-8');
		$uid = intval(arr::get($post,'uid',0));
		$user_name = mb_substr(trim(arr::get($post,'user_name','')),0,30,'UTF-8');
		$user_type = intval(arr::get($post,'user_type',0));
		$user_status = intval(arr::get($post,'user_status',0));
		$ip = ip2long(arr::get($post,'ip','')); 	
    	if($ask_category_first && $ask_title && $ask_description && $uid && $user_name && $user_type && $user_status && $ip){
    		if($id){
    			$model = ORM::factory('Zixun_AskTitle',$id);
    			$model->update_time = time();
    		}else{
    			$model = ORM::factory('Zixun_AskTitle');
    			$model->ask_user_id = $uid;
    			$model->ask_user_type = $user_type;
    			$model->ask_ordinary_type = 0;
    			$model->ask_adopt_type = 0;
    			$model->ask_pv_count = 0;
    			$model->ask_answer_count = 0;
    			$model->ask_status = 1;
    			$model->add_time = $model->update_time = time();
    		}    		
    		$model->ask_name = $ask_title;
    		$model->ask_describe = $ask_description;
    		$model->ask_user_name = $user_name;
    		$model->ask_category_first = $ask_category_first;
    		$model->ask_category_second = $ask_category_second;   		
    		$model->ip = $ip;	
    		try {
    			$model = $id ? $model->update() : $model->create();
    			$return = $model->ask_id;
    		}catch (Kohana_Exception $e){
    			$return = false;
    		} 		
    	}
    	return $return;
    }
    /**
     * 添加问题--答案
     * @author 赵路生
     * @param  $post
     * @return boolean
     */
    public function addMyAnswer($post=array()){
    	$return = false;
    	$id = intval(arr::get($post,'answer_id',0));
    	$ask_id = intval(arr::get($post,'ask_id',0));
    	$ask_answer_user_id = intval(arr::get($post,'ask_answer_user_id',0));
    	$ask_answer_user_type = intval(arr::get($post,'ask_answer_user_type',0));
    	$answer_description =  mb_substr(nl2br(strip_tags(trim(arr::get($post,'answer_description','')))),0,300,'UTF-8');
    	$ask_answer_user_name = mb_substr(trim(arr::get($post,'ask_answer_user_name','')),0,30,'UTF-8');
    	$ip = ip2long(arr::get($post,'ip','')); 
    	$user_status = arr::get($post,'user_status',0);
    	if($ask_id && $ask_answer_user_id && $ask_answer_user_type && $answer_description && $ask_answer_user_name && $ip && $user_status){
    		if($id){
    			$model = ORM::factory('Zixun_AskAnswer',$id);
    			$model->ask_answer_updatetime = time();
    		}else{
    			$model = ORM::factory('Zixun_AskAnswer');
    			$model->ask_id = $ask_id;
    			$model->ask_answer_user_id = $ask_answer_user_id;
    			$model->ask_answer_user_type = $ask_answer_user_type;    			
    			$model->ask_answer_status = 1;
    			$model->ask_answer_adopt_type = 0;
    			$model->add_answer_time = $model->ask_answer_updatetime = time();
    		}
    		$model->ask_answer_describe = $answer_description ;
    		$model->ask_answer_user_name = $ask_answer_user_name;
    		$model->ip = $ip ;    	
    		try {
    			$result = $id ? $model->update() : $model->create();
    			$return = $result->ask_id;
    			// [问答优化体验] 添加答案和修改答案的时候修改问题的修改时间
    			if($return){
    				$m_title = ORM::factory('Zixun_AskTitle',$ask_id);
    				if($m_title->loaded()){
    					$m_title->update_time = time();
    					$m_title->update();
    				}
    			}
    		}catch (Kohana_Exception $e){
    			$return = false;
    		} 		
    	}
    	return $return;
    }
    /**
     * 问题--采纳
     * 注意 这个只负责写数据，具体的数据处理以及判断规则 放在了controller层里面
     * @author赵路生
     */
    public function addadopt($uid=0,$title_id=0,$answer_id=0,$comment=''){
    	$return = false;
    	$uid = intval($uid); 
    	$title_id = intval($title_id);
    	$answer_id = intval($answer_id);
    	$comment = mb_substr(nl2br(strip_tags(trim($comment))),0,100,'UTF-8');
    	if($uid && $title_id && $answer_id && $comment){
    		$title_model = ORM::factory('Zixun_AskTitle',$title_id);
    		$answer_model = ORM::factory('Zixun_AskAnswer',$answer_id);
    		// 更新问题提问数据
    		$title_model->ask_adopt_type = 1;
    		$title_model->ask_title_adopt_people = 1;
    		$title_model->ask_title_adopt_comment = $comment;
    		// 更新问题回答数据
    		$answer_model->ask_answer_adopt_type = 1;
    		$answer_model->ask_answer_updatetime = time();
    		try {
    			$title_model->update();
    			$answer_model->update();
    			$return = true;
    		}catch (Kohana_Exception $e){
    			$return = false;
    		}
    	}
    	return  $return;
    }
    /**
     * 问答管理--添加创业币
     * @author 赵路生
     * @param  $user_id(个人用户id),$score_operating(加减类型),$score_type(抽奖，活跃度类型),$score(数量)
     * @return boolean
     */
    public function updataAskcurrency($user_id,$score_operating,$score_type,$score){
    	$arg_list = func_get_args();
    	$arg_new_list = array_map ('intval',$arg_list);
    	// 添加限制条件 （提问：10创业币，total 100） （回答：5创业币，total 100） （采纳：10创业币）   	
    	if($arg_new_list[0]){
    		$model = ORM::factory('Usercurrency');
    		$model->user_id = $arg_new_list[0];
    		$model->score_operating = $arg_new_list[1];    	
    		if($score_type == 4){
    			// 获取今日提问的总数量
    			$title_count = $model->where('user_id','=',$arg_new_list[0])->where('score_type','=',4)->where('date','BETWEEN',array(strtotime('today'),time()))->count_all();
    			if($title_count >= 10){
    				return false;
    			}
    		}
    		if($score_type == 5){
    			// 获取今日回答的总数量
    			$answer_count = $model->where('user_id','=',$arg_new_list[0])->where('score_type','=',5)->where('date','BETWEEN',array(strtotime('today'),time()))->count_all();
    			if($answer_count >=20 ){
    				return false;
    			}
    		}   		
    		$model->score_type = $arg_new_list[2];
    		$model->score = $arg_new_list[3];
    		$model->date = time();
    		try {
    			$model->create();
    			return true;
    		}catch (Kohana_Exception $e){
    			return false;
    		}
    	}
    	return false;	   
    }
    /**
     * 问答管理-- 获取用户的提问总数
     * @author 赵路生
     * @param  $user_id
     * @return int
     */
    public function getMyAskTitleTotal($user_id){
    	$user_id = intval($user_id);
    	if($user_id){
    		return ORM::factory('Zixun_AskTitle')->where('ask_user_id', '=', $user_id)->count_all();  		
    	}
    	return 0;
    }
    /**
     * 问答管理-- 获取用户的回答总数
     * @author 赵路生
     * @param  $user_id
     * @return int
     */
    public function getMyAskAnswerTotal($user_id){
    	$user_id = intval($user_id);
    	if($user_id){
    		return ORM::factory('Zixun_AskAnswer')->where('ask_answer_user_id', '=', $user_id)->where('ask_answer_status','=',1)->count_all();
    	}
    	return 0;
    }
    /**
     * 添加浏览记录
     * @author赵路生
     */
    public function addBrowseRecord($id){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskTitle')->where('ask_id','=',$id)->where('ask_status','=',1)->find();
    		if($model->loaded()){
    			$model->ask_pv_count = $model->ask_pv_count+1;
    			try {
    				$model->update();
    				return true;
    			}catch (Kohana_Exception $e){
    				return false;
    			}
    		}
    	}
    	return false;
    }
    //end function
    /**
     * 根据问题ID获取[未删除]的问题详情
     * @author 赵路生
     */
    public function getAskTitleById($id=0){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskTitle')->where('ask_id','=',$id)->where('ask_status','=',1)->find();
    		if($model->loaded()){
    			return $model;
    		}
    	}
    	return false;
    }
    /**
     * 根据问题industry_id 获取 分类名字
     * @author 赵路生
     */
    public function getAskIndustryById($ind_id=0){
    	$ind_id = intval($ind_id);
    	if($ind_id){
    		$model = ORM::factory('Zixun_AskIndustry')->where('ask_industry_id','=',$ind_id)->find();
    		if($model->loaded()){
    			return $model->ask_industry_name;
    		}
    	}
    	return '';
    }
    /**
     * 根据问题ID获取问题【全部】-答案
     * @author 赵路生
     */
    public function getAskAnswerById($id=0){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_id','=',$id)->where('ask_answer_status','=',1)->order_by('add_answer_time','ASC')->find_all();
    		if(count($model)){
    			return $model->as_array();
    		}
    	}
    	return false;
    }
    /**
     * 根据答案ID获取【一条】-答案
     * @author 赵路生
     */
    public function getAskSingleAnswerById($id=0){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_answer_id','=',$id)->where('ask_answer_status','=',1)->find();
    		if($model->loaded()){
    			return $model;
    		}
    	}
    	return false;
    }
    /**
     * 根据问题ID获取问题最先回答的-答案
     * @author 赵路生
     */
    public function getAskEarliestAnswerById($id=0){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_id','=',$id)->where('ask_answer_status','=',1)->order_by('add_answer_time','ASC')->find();
    		if(count($model)){
    			return $model->ask_answer_describe;
    		}
    	}
    	return false;
    }
    /**
     * 根据问题ID获取问题【全部】答案-数量
     * @author 赵路生
     */
    public function getAskAnswerAmountById($id=0){
    	$id = intval($id);
    	if($id){
    		return ORM::factory('Zixun_AskAnswer')->where('ask_id','=',$id)->where('ask_answer_status','=',1)->count_all();
    	}
    	return 0;
    }
    /**
     * 根据问题ID获取问题【已采纳】答案
     * @author 赵路生
     */
    public function getAskAdoptAnswerById($id=0){
    	$id = intval($id);
    	if($id){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_id','=',$id)->where('ask_answer_adopt_type','=',1)->find();
    		if($model->loaded()){
    			return $model;
    		}
    	}
    	return false;
    }
    /**
     * 问题详情-- 我的提问  获取一个用户的所有提问
     * @author 赵路生
     * @param $userid 用户id信息
     * @return array()
     */
    public function getMyAskByUid($userid){
    	$result = $result['page_title'] = $result['list_title'] = array();
    	$userid = intval($userid);
    	if($userid){
    		$model = ORM::factory('Zixun_AskTitle')->where('ask_user_id','=',$userid);
    		//分页
    		$page = Pagination::factory(array(
    				'total_items'    => $model->reset(false)->count_all(),
    				'items_per_page' => 10,
    				'current_page'   => array('source' => 'wenda_user', 'key' => 'page_title'),
    		));
    		$list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('add_time', 'DESC')->find_all()->as_array();
    		$result['page_title'] =  $page;
    		$result['list_title'] =  $list;
    	}   	
    	return $result;
    }
    /**
     * 问题详情-- 我的提问  获取一个用户的所有回答
     * @author 赵路生
     * @param $userid 用户id信息
     * @return array()
     */
    public function getMyAnswerByUid($userid){
    	$result = $result['page_answer'] = $result['list_answer'] = array();
    	$userid = intval($userid);
    	if($userid){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_answer_user_id','=',$userid)->where('ask_answer_status','=',1);
    		//分页
    		$page = Pagination::factory(array(
    				'total_items'    => $model->reset(false)->count_all(),
    				'items_per_page' => 10,
    				'current_page'   => array('source' => 'wenda_user', 'key' => 'page_answer'),
    		));
    		$list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('add_answer_time', 'DESC')->find_all()->as_array();
    		$result['page_answer'] =  $page;
    		$result['list_answer'] =  $list;
    	}    	
    	return $result;
    }
    /**
     * 返回一个用户是否已经对该问题进行了回答
     * @author 赵路生
     * @return boolean
     */
    public function getAskTitleUserRl($user_id,$title_id){
    	$return = false;
    	$user_id = intval($user_id);
    	$title_id = intval($title_id);
    	if($user_id && $title_id){
    		$model = ORM::factory('Zixun_AskAnswer')->where('ask_answer_user_id','=',$user_id)->where('ask_id','=',$title_id)->find_all();
    		if(count($model)){
    			$return = true;
    		}
    	}  	
    	return $return;
    }
    /**
     * 问题详情--相关提问
     * @author 赵路生
     * @param $ask_id,$ask_category_second二级分类id,$ask_category_first一级分类id,$amount随机取几条数据
     * @return array()
     */
    public function getAskRelateTitleById($ask_id,$ask_category_second,$ask_category_first,$amount=5){
    	$ask_id = intval($ask_id);
    	$ask_category_second = intval($ask_category_second);
    	$ask_category_first = intval($ask_category_first);

    	//兼容 如果只是有一级分类的问题
    	if($ask_category_second == 0){
    		$ask_category_second = $ask_category_first;
    	}
    	$list = array();
    	$redis = Cache::instance('redis');
    	$red_result = $redis->get('get_ask_relate_title_by_id'.$ask_id);
    	if($red_result){
    		$list = $red_result;
    	}else{
    		if($ask_id && $ask_category_second && $ask_category_first){
    			$model =  ORM::factory('Zixun_AskTitle');
    			//获取相关分类问题 需要排除自己
    			$second_count = $model->where('ask_id','!=',$ask_id)->where('ask_category_second','=',$ask_category_second)->where('ask_status','=',1)->reset(false)->count_all();
    
    			if($second_count < $amount){
    				//【1】需要一级分类和二级分类共同组合的
    				if($second_count){
    					$second_model = $model->select("*")->order_by(DB::expr('rand()'))->limit($second_count)->find_all();
    					foreach($second_model as $value){
    						$list[] = array($value->ask_id,$value->ask_name,$value->ask_answer_count,$value->add_time);
    						$list['ask_id'][] = $value->ask_id;
    					}
    
    					//还要排除 已经被选中的
    					$list['ask_id']['self_id'] = $ask_id;
    					$model->reset();
    					$first_model = $model->where('ask_id','NOT IN',$list['ask_id'])->where('ask_category_first','=',$ask_category_first)->where('ask_status','=',1)->order_by(DB::expr('rand()'))->limit($amount - $second_count)->find_all();
    					unset($list['ask_id']);
    
    				}else{//【2】全部获取一级分类
    					$model->reset();
    					$first_model = $model->where('ask_id','!=',$ask_id)->where('ask_category_first','=',$ask_category_first)->where('ask_status','=',1)->order_by(DB::expr('rand()'))->limit($amount)->find_all();
    				}
    					
    				foreach($first_model as $value){
    					$list[] = array($value->ask_id,$value->ask_name,$value->ask_answer_count,$value->add_time);
    				}
    
    			}else{//【3】全部获取二级分类
    				$second_model = $model->select("*")->order_by(DB::expr('rand()'))->limit($amount)->find_all();
    				foreach($second_model as $value){
    					$list[] = array($value->ask_id,$value->ask_name,$value->ask_answer_count,$value->add_time);
    				}
    			}
    			$redis->set('get_ask_relate_title_by_id'.$ask_id, $list,86400);
    		}   		
    	}
    	return $list;
    }//end function
    /**
     * 问题详情--相关热门提问
     * @author 赵路生
     * @param $ask_id,$ask_category_second二级分类id,$ask_category_first一级分类id,$amount获取几条数据,$time时间范围604800为一周
     * @return array()
     */
/**
     * 问题详情--相关热门提问
     * @author 赵路生
     * @param $ask_id,$ask_category_second二级分类id,$ask_category_first一级分类id,$amount获取几条数据,$time时间范围604800为一周
     * @return array()
     */
    public function getAskRelTopTitleById($ask_id,$ask_category_second,$ask_category_first,$amount=10,$time=604800){
    	$ask_id = intval($ask_id);
    	$ask_category_second = intval($ask_category_second);
    	$ask_category_first = intval($ask_category_first);
    	
    	//兼容只有一级分类的问题
    	if($ask_category_second == 0){
    		$ask_category_second = $ask_category_first;
    	}
    	$start_time = time()-$time;
    	$list = array();
    	$redis = Cache::instance('redis');
    	$red_result = $redis->get('get_ask_rel_top_title_by_id'.$ask_id);
    	if($red_result){
    		$list = $red_result;
    	}else{
    		if($ask_id && $ask_category_second && $ask_category_first){
    			$model =  ORM::factory('Zixun_AskTitle');
    			//获取该分类的数量
    			$second_count = $model->where('ask_category_second','=',$ask_category_second)->where('ask_status','=',1)->where('add_time','>=',$start_time)->reset(false)->count_all();
    			//如果一周内没有数据，进行时间倒退，目前倒退后是没有时间限制，【需要时间段倒退可以修改】
    			if($second_count < $amount){
    				$start_time = 0;//全部范围
    				$second_count = $model->where('ask_category_second','=',$ask_category_second)->where('ask_status','=',1)->where('add_time','>=',$start_time)->reset(false)->count_all();
    			}
    			
    			//进行数据组合
    			if($second_count < $amount){
    				//【1】需要一级分类和二级分类共同组合的
    				if($second_count){
    					$second_model = $model->select("*")->order_by('ask_answer_count','DESC')->limit($second_count)->find_all();
    					foreach($second_model as $value){
    						$list[] = array($value->ask_id,$value->ask_name);
    						$list['ask_id'][] = $value->ask_id;
    					}
    
    					//还要排除 已经被选中的二级分类的ask_id
    					$model->reset();
    					$first_model = $model->where('ask_id','NOT IN',$list['ask_id'])->where('ask_category_first','=',$ask_category_first)->where('ask_status','=',1)->where('add_time','>=',$start_time)->order_by('ask_answer_count','DESC')->limit($amount - $second_count)->find_all();
    					unset($list['ask_id']);
    				}else{//【2】全部获取一级分类
    					$model->reset();
    					$first_model = $model->where('ask_category_first','=',$ask_category_first)->where('ask_status','=',1)->where('add_time','>=',$start_time)->order_by('ask_answer_count','DESC')->limit($amount)->find_all();
    				}
    
    				foreach($first_model as $value){
    					$list[] = array($value->ask_id,$value->ask_name);
    				}
    					
    			}else{//【3】全部获取二级分类
    				$second_model = $model->select("*")->order_by('ask_answer_count','DESC')->limit($amount)->find_all();
    				foreach($second_model as $value){
    					$list[] = array($value->ask_id,$value->ask_name);
    				}
    			}
    			$redis->set('get_ask_rel_top_title_by_id'.$ask_id, $list,86400);
    		}
    	}
    	return $list;
    }//end function
    /**
     * 根据问答二级或者一级分类 匹配项目
     * @author 赵路生
     * @param $ask_category_second 二级分类名,$ask_category_first 一级分类名 ,$amount数量($amount如果大于10需要修改)
     * @return array()
     */
    public function searchQuestionMatchProject($ask_category_second,$ask_category_first='',$amount=5){
    	$result_list = array();
    	//获取二级和一级分类名字
    	$ask_category_second =  secure::secureInput(secure::secureUTF($this->getAskIndustryById($ask_category_second)));
    	$ask_category_first =  secure::secureInput(secure::secureUTF($this->getAskIndustryById($ask_category_first)));
    	//缓存
    	$redis = Cache::instance('redis');
    	$red_result = $redis->get('search_question_match_project'.$ask_category_second.$ask_category_first);
    	if($red_result){
    		return $result_list = $red_result;
    	}else{
    		$Search = new Service_Api_Search();
    		$searchresult_second = $Search->getSearch($ask_category_second, '', 0, '');
    		$matches_second = arr::get($searchresult_second, 'matches', array());
    		$total_second = count($matches_second);//注意这里返回的数量【最大】是 搜索接口分页显示默认的数量10
    		if($total_second<$amount){
    			$matches_first = array();
    			$searchresult_first = $Search->getSearch($ask_category_first, '', 0, '');
    			$matches_first = arr::get($searchresult_first, 'matches', array());
    		}
    		//对结果数组进行处理
    		$matches =  (isset($matches_first) && !empty($matches_first)) ? (empty($matches_second) ? $matches_first : $matches_second+$matches_first) : $matches_second;
    		$matches = count($matches)<=$amount?$matches:array_slice($matches,0,$amount);
    		//获取项目详细信息
    		foreach($matches as $value){
    			$projectinfo = ORM::factory('Project',$value['id']);
    
    			if($projectinfo->project_source != 1) {//项目logo图片
    				$tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
    				if(!project::checkProLogo($tpurl)){
    					$tpurl=URL::webstatic('images/common/company_default.jpg');;
    				}
    			}
    			else{
    				$tpurl=URL::imgurl($projectinfo->project_logo);
    				if(!project::checkProLogo($tpurl)){
    					$tpurl=URL::webstatic('images/common/company_default.jpg');
    				}
    			}
    			$result_list[] = array($projectinfo->project_id,$projectinfo->project_brand_name,$tpurl);
    		}
    		$redis->set('search_question_match_project'.$ask_category_second.$ask_category_first,$result_list,86400);
    	}
    	return  $result_list;
    }//end function
    /**
     * 问题分类--右侧问题推荐
     * @author 赵路生
     * @param $fenlei_id获取的分类id,$amount获取几条，$time是时间段范围
     * @return array()
     */
    public function getAskRelPushTitleById($id,$amount=10,$time=604800){
    	if(!intval($id) || !$id){//非法值默认都为1
    		$id=1;
    	}
    	$return=array();
    	$redis = Cache::instance('redis');
    	$red_result = $redis->get('get_ask_rel_push_title_by_id'.$id);
    	if($red_result){
    		$return = $red_result;
    	}else{
    		$model = ORM::factory('Zixun_AskIndustry',$id);
    		if(!$model->loaded()){//没找到 默认1
    			$id=1;
    			$model = ORM::factory('Zixun_AskIndustry',$id);
    		}
    		$return['industry_name'] = $model->ask_industry_name;
    		$askTitlemodel =  ORM::factory('Zixun_AskTitle');
    		if($model->ask_parent_id==0){//【1】一级分类--全部一级分类
    			$first_result = $askTitlemodel->where('ask_category_first','=',$id)->where('ask_status','=',1)->where('add_time','>=',time()-$time)->order_by('ask_pv_count','DESC')->limit($amount)->find_all();
    			foreach($first_result as $value){
    				$return['list'][] = array($value->ask_id,$value->ask_name);
    			}
    		}else{//选择的是2级行业
    			$second_count = $askTitlemodel->where('ask_category_second','=',$id)->where('ask_status','=',1)->where('add_time','>=',time()-$time)->reset(false)->count_all();
    			if($second_count < $amount){
    				if($second_count){//【2】二级分类--一级分类二级分类组合
    					$second_result = $askTitlemodel->select("*")->order_by('ask_pv_count','DESC')->find_all();
    						
    					foreach($second_result as $value){
    						//print_r($value->ask_id);print_r($value->ask_name);exit;
    						$return['list'][] = array($value->ask_id , $value->ask_name);
    						$return['list']['ask_id'][] = $value->ask_id;
    					}
    					$first_result = $askTitlemodel->where('ask_id','NOT IN',$return['list']['ask_id'])->where('ask_category_first','=',$model->ask_parent_id)->where('ask_status','=',1)->where('add_time','>=',time()-$time)->order_by('ask_pv_count','DESC')->limit($amount-$second_count)->find_all();
    					unset($return['list']['ask_id']);
    				}else{//【3】二级分类--全部使用一级分类
    					$askTitlemodel->reset();
    					$first_result = $askTitlemodel->where('ask_category_first','=',$model->ask_parent_id)->where('ask_status','=',1)->where('add_time','>=',time()-$time)->order_by('ask_pv_count','DESC')->limit($amount)->find_all();
    				}   				
    				foreach($first_result as $value){
    					$return['list'][] = array($value->ask_id,$value->ask_name);
    				}   				
    			}else{//【4】二级分类--全部使用二级分类
    				$second_result = $askTitlemodel->select("*")->order_by('ask_pv_count','DESC')->limit($amount)->find_all();
    				foreach($second_result as $value){
    					$return['list'][] = array($value->ask_id,$value->ask_name);
    				}
    			}
    		}
    		$redis->set('get_ask_rel_push_title_by_id'.$id,$return,86400);
    	} 	
    	
    	return  $return;

    }//end function
    
    /**
     * 问题分类--右侧知识推荐
     * @author 钟涛
     * @return array()
     */
    public function getAskZhishiById($id){
    	if(!intval($id) || !$id){//非法值默认都为1
    		$id=1;
    	}
    	//缓存
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	$memarr=$memcache->get('getAskZhishiById'.$id);
    	if($memarr){
    		return $memarr;
    	}else{
	    	$return=array();
	    	$model = ORM::factory('Zixun_AskIndustry',$id);
	    	if(!$model->loaded()){//没找到 默认1
	    		$id=1;
	    		$model = ORM::factory('Zixun_AskIndustry',$id);
	    	}
	    	$name = $model->ask_industry_name;
	    	$service_search_api = new Service_Api_Search();
	    	//solr接口返回搜索结果
	    	$solr_result = $service_search_api->getSearchZixun($name,0,10,"");
	    	$matches = Arr::get($solr_result,"matches");
	    	$returnarr=array();
	    	if(count($matches)){
	    		foreach($matches as $v){
	    			$ac=ORM::factory("Zixun_Article",$v);
	    			$returnarr[$v]['id']=$v;
	    			$returnarr[$v]['article_addtime']=$ac->article_intime;//文章添加时间
	    			$returnarr[$v]['name']=$ac->article_name;
	    		}
	    	}
	    	$memcache->set('getAskZhishiById'.$id,$returnarr,$_cache_get_time);
	    	return  $returnarr;
    	}
    }//end function
}
