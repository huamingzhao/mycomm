<?php
/**
 * 构建url
 * @author 龚湧
 *
 */
class urlbuilder{
    /**
     * 随便逛逛 链接地址
     * @return string
     * @author 龚湧
     */
    public static function guangtouzi(){
        return URL::website("guangtouzi.html");
    }
    /**
     * 精准匹配链接地址
     * @return string
     * @author 龚湧
     */
    public static function zhuntouzi(){
        return URL::website("zhuntouzi.html");
    }
    
    /**
     * 网络展会项目官网
     * @author 郁政
     */
    public static function exhbProject($id){
    	return URL::website("/zhanhui/".$id.".html");
    }

    /**
     * 项目详情
     * @param unknown $id
     * @author 龚湧
     */
    public static function project($id , $type = 0){
    	if($type == 0){
    		$res = false;
                $memcache = Cache::instance ( 'memcache' );
                $key = 'urlbuilder_project'.$id;
                $res = $memcache->get($key);
                if(!$res) {
                    $service_1 = new Service_Platform_Project();		
                    //获得海报信息        
                    $res = $service_1->isPoster($id);
                    $res = !$res ? 2 : 1;
                    $memcache->set($key, $res, 86400);
                }
    		if($res == 1){
    			return URL::website("haibao/{$id}.html");
    		}else{
    			return URL::website("project/{$id}.html");
    		}
    	}
        if($type == 2) {
            $memcache = Cache::instance ( 'memcache' );
            $key = 'urlbuilder_project'.$id;
            $res = $memcache->get($key);
            if($res == 1){
    			return URL::website("haibao/{$id}.html");
    		}else{
    			return URL::website("project/{$id}.html");
            }
        }
        return URL::website("project/{$id}.html");
    }
    /**
     * 项目详情
     * @param unknown $id
     * @return string
     * @author 龚湧
     */
    public static function projectInfo($id){
    	return URL::website("project/{$id}.html");
        //return URL::website("project/projectinfo/{$id}.html");
    }
    /**
     * 项目海报
     * @param unknown $id
     * @author 龚湧
     */
    public static function projectPoster($id){
        return URL::website("haibao/{$id}.html");
    }
    /**
     * 项目图片
     * @param unknown $id
     * @return string
     * @author 龚湧
     */
    public static function projectImages($id){
        return URL::website("projectimg/{$id}.html");
    }
    /**
     * 项目资质
     * @param unknown $id
     * @author 龚湧
     */
    public static function projectCerts($id){
        return URL::website("projectzizhi/{$id}.html");
    }
    /**
     * 项目招商会
     * @param unknown $id
     * @author 龚湧
     */
    public static function projectInvest($id){
        return URL::website("touzikaocha/{$id}.html");
    }
    /**
     * 公司信息
     * @param unknown $id
     * @return string
     * @author 龚湧
     */
    public static function projectCompany($id){
        return URL::website("gongsi/{$id}.html");
    }

    /**
     * 项目封底
     * @param unknown $id
     * @return string
     * @author 龚湧
     */
    public static function projectEnd($id){
        return URL::website("project/projectend/{$id}.html");
    }
    /**
     * 帮助中心
     * @param unknown $name
     * @author 龚湧
     */
    public static function help($name){
        return URL::website("help/{$name}.html");
    }
    /**
     * 一级链接
     * @param unknown $name
     * @author 龚湧
     */
    public static function root($name){
        return URL::website($name.'.html');
    }
    /**
     * 一级链接
     * @param unknown $name
     * @author 龚湧
     */
    public static function rootDir($name){
        return URL::website($name."/");
    }
    /**
     * 用户注册
     * @author 龚湧
     * @param unknown $name
     * @return string
     */
    public static function register($name){
        if($name=="qiye"){
            return URL::website("qiye/zhuce.html");
        }
        elseif($name=="geren"){
            return URL::website("geren/zhuce.html");
        }
    }

    /**
     * 企业入口相关
     * @author 龚湧
     */
    public static function qiye($name){
    	if($name=='zhaotouzi'){
    		$url = "{$name}/";
    		return URL::website($url);
    	}
        return URL::website("qiye/{$name}.html");
    }

    /**
     * 找投资者
     * @author 钟涛
     */
    public static function zhaotouzi($name='zhaotouzi',$w = '', $page = '1',$a='',$b='',$c='') {
    	if($a || $b || $c){//走下拉筛选
    		if($page>1){
    			$url = "{$name}/?per_area_id={$a}&parent_id={$b}&per_amount={$c}&p={$page}";
    		}else{
    			$url = "{$name}/?per_area_id={$a}&parent_id={$b}&per_amount={$c}";
    		}
    	}else{//走输入框筛选
	    	if($w!='' && $page>1){
	    		$url = "{$name}/?w={$w}&p={$page}";
	    	}elseif($w!=''){
	    		$url = "{$name}/?w={$w}";
	    	}elseif($page>1){
	    		$url = "{$name}/p-{$page}.html";
	    	}else{
	    		$url = "{$name}/";
	    	}
    	}
    	return URL::website($url);
    }
    
    /**
     * 问答模块 分页
     * @author 钟涛
     */
    public static function wenda($oneid,$twoid=0,$page,$type) {
    	if($type==2){
    		$typecontent='y';//已解决
    	}elseif($type==3){
    		$typecontent='d';//待解决
    	}else{
    		$typecontent='all';//待解决
    	}
    	if($twoid==0){//一级分类
    		$url = "{$oneid}/{$typecontent}-p{$page}/";
    	}else{//2级分类
    		$url = "{$oneid}/{$twoid}-{$typecontent}-p{$page}/";
    	}
    	return URL::webwen($url);
    }
    
    /**
     * 个人入口相关
     * @author 龚湧
     * @param unknown $name
     * @return string
     */
    public static function geren($name){
        return URL::website("geren/{$name}.html");
    }
    
    /**
     * 项目向导 分类导航
     * @author 施磊
     */
    public static function fenleiCond($cond = array(), $nowCond = array(), $page = 1){
        $condParam = array('pid' => 'hy', 'inid' => 'zhy', 'atype' => 'm', 'pmodel' => 'xs');
        $url = 'xiangdao/fenlei/';
        $condStr = '';
        if($cond) {
            foreach($condParam as $key => $val) {
                $valCond = 0;
                if(isset($nowCond[$key]) || arr::get($nowCond, $key, 0)) {
                    $valCond = arr::get($nowCond, $key, 0);
                }else{
                    if(arr::get($cond, $key, 0)) {
                        $valCond = arr::get($cond, $key, 0);
                    }
                }
                if($valCond) {
                    $condStr .= $val.$valCond;
                }
            }
            if($page != 1){
            	$url .= $condStr ? $condStr.'-'.$page.'.html' : '';
            }else{
            	$url .= $condStr ? $condStr.'.html' : '';
            }            
        }else{
        	if($page != 1){
        		$url .= 'list-'.$page.'.html';
        	}      	       	
        }
        return URL::website($url);
    }
    
    /**
     * 项目向导 分类导航(默认排序使用)
     * @author 郁政
     */
    public static function fenleiOrder($num = 1,$page = 1){
    	$url = 'xiangdao/fenlei/';
    	switch($num){
    		case 1: $url .= 'index_1_1-'.$page.'.html';break;
    		case 2: $url .= 'index_1_2-'.$page.'.html';break;
    		case 3: $url .= 'index_2_1-'.$page.'.html';break;
    		case 4: $url .= 'index_2_2-'.$page.'.html';break;
    	}
    	return URL::website($url);
    }
    
    /**
     * sitemap 
     *  @author 施磊
     */
    public static function siteMap($type = 'A', $page = '1') {
        $url = "project_{$type}_{$page}.html";
        return URL::website($url);
    }
    
    /**
     * search (筛选)
     *  @author 郁政
     */    
	public static function search2($per_area_id = '',$parent_id = '',$per_amount = '', $page = '1') {
        $url = "search/?per_area_id={$per_area_id}&parent_id={$parent_id}&per_amount={$per_amount}&page={$page}";
        return URL::website($url);
    }
    
     /**
     * search
     *  @author 郁政
     */
	public static function search($w = '', $page = '1') {
        $url = "search/?w={$w}&page={$page}";
        return URL::website($url);
    }
    
    /**
     * 项目向导 二级向导
     * @author 施磊
     */
    public static function projectGuide($name){
    	if($name == 'fenlei') {
    		return  self::fenleiCond();
    	}else{
        	return URL::website("xiangdao/$name/");
    	}
    }
    /**
     * 项目向导 排行榜 详细
     * @author 施磊
     */
    public static function guideShowRanking($id = 1, $time = 1){
        return URL::website("xiangdao/top/{$id}_{$time}.html");
    }
    
    /**
     * 项目向导 人群 
     * @author 施磊
     */
    public static function guideCorwd($id = 1 , $page = 1){
    	if($id == ''){
    		if($page == 1){
    			return URL::website("xiangdao/people/");
	    	}else{
	    		return URL::website("xiangdao/people/list-".$page.".html");
	    	}        
    	}else{
    		if($page == 1){
    			return URL::website("xiangdao/people/$id.html");
	    	}else{
	    		return URL::website("xiangdao/people/".$id."-".$page.".html");
	    	}   
    	}    	     
    }
     /**
     * 项目向导 地区 
     * @author 施磊
     */
    public static function guideArea($id = 1 , $page = 1){
        if($id == ''){
    		if($page == 1){
    			return URL::website("xiangdao/diqu/");
	    	}else{
	    		return URL::website("xiangdao/diqu/list-".$page.".html");
	    	}        
    	}else{
    		if($page == 1){
    			return URL::website("xiangdao/diqu/$id.html");
	    	}else{
	    		return URL::website("xiangdao/diqu/".$id."-".$page.".html");
	    	}   
    	}    
    }
    
    /**
     * 投资考察
     * @author 花文刚
     */
    public static function touzikaocha($page = 1,$cond = array()){
        $url = 'touzikaocha/';
        if($cond['time'] || $cond['calendar'] || $cond['from'] || $cond['in_id'] || $cond['areaid'] || $cond['monthly']){
            $url = 'touzikaocha/?time='.$cond['time'].'&calendar='.$cond['calendar'].'&from='.$cond['from']
                .'&in_id='.$cond['in_id'].'&areaid='.$cond['areaid'].'&monthly='.$cond['monthly'].'&p='.$page;
        }
        else{
            if($page == 1){
                $url = "touzikaocha/";
            }else{
                $url = "touzikaocha/p-".$page.".html";
            }

        }
        return URL::website($url);
    }
    
	/**
     * 历史投资考察
     * @author 郁政
     */
    public static function lishizhaoshang($page = 1){
    	if($page == 1){
    		return URL::website("lishizhaoshang/");
    	}else{
    		return URL::website("lishizhaoshang/p-".$page.".html");
    	}    	
    }
    /**
     * 企业中心--账户--消费列表
     * @return string
     * @author 赵路生
     */
    public static function siteaccountlist($page){
    	return URL::website("company/member/account/accountlist?page=".$page);
    }
    
    /**
     * 展会栏目页
     * @author jiye
     */
    public static function exhibitionid($int_exhibition_id,$int_type = null,$page){
    	if($int_type == null){
    		if($page == 1){
    			return URL::website("/zhanhui/".$int_exhibition_id."/");
    		}else{
    			return URL::website("/zhanhui/".$int_exhibition_id."/p".$page.".html");
    		}    		
    	}else{ 
    		if($page == 1){
    			return URL::website("/zhanhui/".$int_exhibition_id."-t".$int_type."/");
    		}else{
    			return URL::website("/zhanhui/".$int_exhibition_id."-t".$int_type."/p".$page.".html");
    		}
    	}
    }
    /**
     * 展会子类页分页
     * @author jiye
     */
    public static function catalogid($int_exhibition_id,$catalog_id,$int_type = null,$page){
    	if($int_type == null){
    		if($page == 1){
    			return URL::website("/zhanhui/".$int_exhibition_id."-".$catalog_id."/");
    		}else{
    			return URL::website("/zhanhui/".$int_exhibition_id."-".$catalog_id."/p".$page.".html");
    		}    		
    	}else{ 
    		if($page == 1){
    			return URL::website("/zhanhui/".$int_exhibition_id."-".$catalog_id."-t".$int_type."/");
    		}else{
    			return URL::website("/zhanhui/".$int_exhibition_id."-".$catalog_id."-t".$int_type."/p".$page.".html");
    		}
    	}
    }
    
    /**
     * 展览会栏目页
     */
    public static function exhbColumn($id){
    	return URL::website("/zhanhui/".$id."/");
    }
    /**
     * 展会单页
     */
    public static function exhbInfo($id = '') {
        $id = intval($id);
        if($id) {
            return URL::website("/zhanhui/zh".$id."/");
        }else {
            return URL::website("/zhanhui/");
        }
    }
    
    
    /**
     * 快速发布项目页
     * 
     */
    public static function qucikAddPro($type=1) {
    	return URL::website("/quick/FastReleaseProject/ShowAddFastReleaseProject?type=".$type);
        
    }
    /**
     * 快速发布管理页
     */
    public static function qucikProManage() {
        return URL::website('/quick/project/showProject');
    }
    /**
     * 快速发布修改项目页
     */
    public static function qucikEditPro($project_id) {
    	$service = new Service_QuickPublish_Project();
        return URL::website('/quick/project/showProjectDetail?project_id='.$project_id);
    }
    /**
     * 快速发布项目官网
     */
    public static function qucikProHome($project_id) {
    	
        $cache = Cache::instance('memcache');
       // $date = array();
        $date = $cache->get('quick_projectinfo_'.$project_id);
        if($date){
        	$industry_id = $date;
        }else{
        	$service = new Service_QuickPublish_Project();
        	$res = $service->getIndustryId($project_id);
        	//var_dump($res);exit;
    		$industry_id = (isset($res['second_industry_id']) && $res['second_industry_id']) ? $res['second_industry_id'] : arr::get($res,"first_industry_id",1);
        	$cache->set('quick_projectinfo_'.$project_id,$industry_id,86400);
        }    	
    	return URL::website('hy'.$industry_id.'/'.$project_id.'.html');
    }
    /**
     * 项目向导 首页
     * @author 施磊
     */
    public static function quickSearchIndex() {
        return URL::website("zs/");
    }
    /**
     * 搜索结果页
     * @author 施磊
     */
    public static function quickSearchWord($w = '', $page = 1) {
    	if($page == 1){
    		if($w){
    			$url = "zs/?w={$w}";
    		}else{
    			$url = "zs/";
    		}    		
    	}else{
    		if($w){
    			$url = "zs/?w={$w}&page={$page}";
    		}else{
    			$url = "zs/?page={$page}";
    		}    		
    	}   	
        return URL::website($url);    	
    } 
    /**
     * 广告跳转页
     * @author stone 
     * 
     */
    public static function quickAdvert($id) {
         $url ='advert/'.$id.'.html';           
        return URL::website($url);    	
    }
    /**
     * 项目向导 分类导航
     * @author 施磊
     */
    public static function quickSearchCond($cond = array(), $nowCond = array(), $page = 1){
    	$condParamUrl = array('area_id' => '', 'industry_id' => 'hy',  'atype' => 'm', 'pmodel' => 'xs');
        $condParam = array('industry_id', 'atype', 'area_id', 'pmodel');
        $url = 'zs';
        $condStr = '';
        $tempCond = array('industry_id' => arr::get($nowCond, 'industry_id', 0), 'atype' => arr::get($nowCond, 'atype', 0) , 'area_id'=> arr::get($nowCond, 'area_id', 0), 'pmodel'=> arr::get($nowCond, 'pmodel', 0));        
       
        if($cond) {
             foreach($cond as $key => $val) {
                 if(in_array($key, $condParam)) {
                    $tempCond[$key] = $val;
                 }
             }
         }
         if($condParamUrl) {
             foreach($condParamUrl as $key => $val) {
                 //if($val) {
                 if(arr::get($tempCond, $key)) {
                 	if($key == 'area_id' && $tempCond['area_id'] && ($tempCond['industry_id'] || $tempCond['atype'] || $tempCond['pmodel'])){
                 		$condStr .= $val.common::getAreaPinYin(arr::get($tempCond, $key)).'-';
                 	}elseif($key == 'area_id' && $tempCond['area_id'] && !$tempCond['industry_id'] && !$tempCond['atype'] && !$tempCond['pmodel']){
                 		$condStr .= $val.common::getAreaPinYin(arr::get($tempCond, $key));                 		
                 	}else{
	                    $condStr .= $val.arr::get($tempCond, $key);
	                }
                }
            }
             //}
         }
        if($condStr){
        	$url .= '/'.$condStr.'/';
        }else{
        	$url .= '/';
        }               
        if($page && $page > 1) {
        	$url .= 'p'.$page.'.html';
        }
        return URL::website($url);
    }
    /**
    * 留言管理页
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-6-4
    * @return int/bool/object/array
    */
    public static function QuickMessage($int_project_id = ""){
    	if($int_project_id){
    		return URL::website('quick/User/UserMessage?project_id='.$int_project_id);
    	}else{
    		return URL::website('quick/User/UserMessage');
    	}
    	
    }
    
	/**
     * 快速发布推广指南
     * 郁政
     */
    public static function qucikTuiGuangGuide() {
    	return URL::website("tuiguang.html");
        
    }
    
    /**
    * 版规说明
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-6-11
    * @return int/bool/object/array
    */
    public static function ruleDescription(){
    	return URL::website('help.html');
    }
    
    /**
     * 生意帮：首页(话题的类别)
     * @param unknown_type $talk_id：话题id
     */
    public static function business_index($talk_id=0)
    {
    	$url=URL::webwen('');
    	if(!empty($talk_id))
    		$url = URL::webwen('sort-'.$talk_id.'/');
    	return $url;
    }
    
    /**
     * 生意帮：用户主页
     * @param unknown_type $user_id：用户id
     */
    public static function business_userinfo($user_id)
    {
    	return URL::webwen('user/'.$user_id);
    }
    
    
    /**
     * 生意帮：问题详情
     * @param unknown_type $user_id：用户id
     */
    public static function business_detail($question_id)
    {
    	return URL::webwen('question/'.$question_id.'.shtml');
    }
    
}
