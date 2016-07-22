<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 招商项目
 * @author 曹怀栋
 *
 */
class Service_User_Company_Project{
    /**
     * 判断用户还可以发多少项目的插件
     * @author 施磊
     */
    public function userProjectCount($user_id, $com_id) {
        $return = array('code' => 0, 'msg' => '', 'count' => 0);
        $project = $this->showProjectAndRenling($com_id);
        $projectCount = $project['page']->total_items;
        if($projectCount == 0) return $return;
        $return['count'] = $projectCount;
        #招商通的服务状态
        $seruser= new Service_User_Company_User();
        //是否已开通招通服务
        $cstStatus = $seruser->isPlatformServiceFee($com_id);
        if(!$cstStatus) {
            $return['code'] = 1;
            return $return;
        }
         //获取当前充值总金额
        $service_account = Service::factory("Account");
        $money = $service_account->getAccountTotalRecharge($user_id);
        if($money <10000 && $projectCount >= 3) {
            $return['code'] = 2;
        }elseif($money > 10000 && $money <20000 && $projectCount >= 6) {
            $return['code'] = 2;
        }elseif($money >=20000 && $projectCount >= 10) {
            $return['code'] = 2;
        }
        return $return;
    }

    /**
     * 通过user_id来取得企业id
     * @author 曹怀栋
     */
    public function getCompanyId($user_id){
        //取得企业id
        $serviceUser = new Service_User_Company_User();
        $comUser = $serviceUser->getCompanyInfo($user_id);
        if(empty($comUser->com_id)){
            return false;
        }else{
            return $comUser->com_id;
        }
    }

    /**
     * 获取项目列表信息
     * @author 曹怀栋
     */
    public function showProject($com_id){
        $model = ORM::factory('Project');
        //分页 此处需要设置reset(FALSE) 否则没有数据时默认会显示一页数据
        $count = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->reset(FALSE)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 10,
        ));
        $array=array();
        $array['list'] = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->limit($page->items_per_page)->offset($page->offset)->order_by('project_addtime', 'DESC')->find_all()->as_array();
        $array['page']= $page;
        return $array;
    }

    /**
     * 获取项目列表信息[包括认领通过项目]
     * @author 钟涛
     */
    public function showProjectAndRenling($com_id){
        $model = ORM::factory('Project');
        $renlingmodel = ORM::factory ( 'ProjectRenling' )->select ( '*' )->where ( 'com_id', '=', $com_id )->where('project_status','=',1)->find_all();
        $rengling_id_arr=array();
        foreach($renlingmodel as $v_renling){
            array_push($rengling_id_arr,$v_renling->project_id);
        }
        if(!empty($rengling_id_arr)){//是否包含认领外采项目
            $count = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->or_where('project_id','in',$rengling_id_arr)->reset(FALSE)->count_all();
        }else{
            $count = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->reset(FALSE)->count_all();
        }
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 10,
        ));
        $array=array();
        if(!empty($rengling_id_arr)){//是否包含认领外采项目
            $array['list'] = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->or_where('project_id','in',$rengling_id_arr)->limit($page->items_per_page)->offset($page->offset)->order_by('project_addtime', 'DESC')->find_all()->as_array();
        }else{
            $array['list'] = $model->where('com_id', '=', $com_id)->where('project_status', '<', 4)->where('project_status', '>', 0)->limit($page->items_per_page)->offset($page->offset)->order_by('project_addtime', 'DESC')->find_all()->as_array();
        }
        $array['page']= $page;
        return $array;
    }
    /**
     * 获取认领项目列表信息[只有未通过审核和正在审核的]
     * @author 钟涛
     */
    public function showProjectRenling($com_id){
        $model = ORM::factory('Project');
        $renlingmodel = ORM::factory ( 'ProjectRenling' )->where( 'com_id', '=', $com_id )->find_all();
        $rengling_id_arr=array();
        foreach($renlingmodel as $v_renling){
            array_push($rengling_id_arr,$v_renling->project_id);
        }
        if(!empty($rengling_id_arr)){
            $listcount = $model->where('project_id','in',$rengling_id_arr)->reset(false)->count_all();
        }else{
            $listcount = 0;
        }
        $page = Pagination::factory(array(
                'total_items'    => $listcount,
                'items_per_page' => 10,
        ));
        $array=array();
        if(!empty($rengling_id_arr)){
            $array['list'] = $model->where('project_id','in',$rengling_id_arr)->limit($page->items_per_page)->offset($page->offset)->order_by('project_addtime', 'DESC')->find_all()->as_array();
        }else{
            $array['list'] = array();
        }
        $array['page']= $page;
        return $array;
    }

    /**
     * 根据输入的条件搜索项目[根据项目名称和企业名称搜索]
     * @author 钟涛
     */
    public function searchProjectRenling($inputcontent){
		$inputvalue =  secure::secureInput(secure::secureUTF($inputcontent));
		$project_id_list=isset($searchresult['matches'])?$searchresult['matches']:array();
		$Search = new Service_Api_Search();
		$limit = 10;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset = ($page - 1) * $limit;
		if($inputcontent=='' || $inputcontent=="请输入您要搜索的公司名称或者项目名称"){
			$searchresult=$Search->getSearch('*%3A*', '', $offset, '');
		}else{
			$searchresult=$Search->getSearch('comProjectName:'.$inputcontent, '', $offset, '');
		}
    	return $searchresult;
    }

    /**
     * 添加招商会信息判断
     * @author 潘宗磊
     */
    public function addInvestCheck($form){
        //表单验证开始
        $valid = new Validation($form);
        $valid->rule("investment_name", "not_empty")->rule("investment_name",'max_length',array(':value', 20));
        $valid->rule("com_name", "not_empty");
        $valid->rule("com_phone", "not_empty")->rule('com_phone', 'regex', array(':value', '/^\d{3,4}-?\d{3,11}-?\d{0,5}$/'));
        //$valid->rule("investment_address", "not_empty")->rule("investment_address", "max_length",array(':value', 30));
        $valid->rule("investment_start", "not_empty");
        $valid->rule("investment_end", "not_empty");
        $valid->rule("investment_province", "not_empty");
        $valid->rule("investment_city", "not_empty");
        $valid->rule("investment_agenda", "not_empty");
        $valid->rule("investment_details", "not_empty");
        $valid->rule("putup_type", "not_empty");
        $valid->rule("investment_logo", "not_empty");
        if(!$valid->check()){
            $error = $valid->errors("project/invest");
            return $error;
        }
        return true;
    }

    /**
     * 添加或更新指定项目的招商会信息
     * @author 曹怀栋
     */
    public function updateProjectInvest($data,$user_id, $com_id = 0){
        $project = ORM::factory("Project",$data['project_id']);
        if($project->project_id =="") return false;
        if(isset($data['investment_id'])){
            $invest = ORM::factory('Projectinvest',$data['investment_id']);

            if($invest->investment_id=="") return false;
        }else{
            $invest = ORM::factory('Projectinvest');
        }
        //添加或更新的招商会信息
        foreach ($data as $k=>$v){
            if($k == "investment_start"||$k == "investment_end"){
                $v = strtotime($v);
            }
            if(is_array($v) === false){
                $invest->$k = trim($v);
            }
        }

//        涛哥说 czzs_project_search_card表暂时不用了，所以不需要调用此方法更新
//        $form_inv = $this->getInventesByProId($data['project_id']);
//        $industry_text=array();
//        $industry_text[0]=$form_inv[0]['industry_id'];
//        $industry_text[1]=$form_inv[1]['industry_id'];
//        $this->updateProjectSearchCard($project->as_array(),$industry_text,$user_id);

        $project->project_investment_status = 1;
        $project->update();

        //添加或更新信息
        if(isset($data['investment_id'])){
            //如果是已审核状态，不直接更新，放入备份表 @花文刚
            if($invest->investment_status == 1){
                $bak_invest = array();
                foreach ($data as $k=>$v){
                    if($k == "investment_start"||$k == "investment_end"){
                        $v = strtotime($v);
                    }
                    if(is_array($v) === false){
                        $bak_invest[$k] = trim($v);
                    }
                }

                $invest_bak = ORM::factory("Investbakup",$invest->investment_id);
                if($invest_bak->invest_id){
                    $now_bak = unserialize($invest_bak->content);
                    //如果再次编辑，且更换了图片，则删除旧图片 @花文刚
                    if($now_bak['investment_logo'] != $bak_invest['investment_logo']){
                        common::deletePic(URL::imgurl($now_bak['investment_logo']));
                    }

                    $bakup = ORM::factory("Investbakup",$invest_bak->invest_id);
                    $bakup->content = serialize($bak_invest);
                    $bakup->edit_status = 0;
                    $res = $bakup->update();
                }
                else{
                    $bakup = ORM::factory("Investbakup");
                    $bakup->invest_id = $bak_invest['investment_id'];
                    $bakup->content = serialize($bak_invest);
                    $res = $bakup->create();
                }
            }
            else{
                //如果编辑，换了图片，则删除旧图片 @花文刚
                $img_url_before = ORM::factory('Projectinvest',$invest->investment_id)->investment_logo;
                if($img_url_before != $invest->investment_logo){
                    common::deletePic(URL::imgurl($img_url_before));
                }

                //如果为【审核不通过】的，修改后变为【待审核】
                if($invest->investment_status == 2){
                    $invest->investment_status = 0;
                }
               $res = $invest->update();
            }

        }else{
            $invest->investment_addtime = time();
            $invest->investment_status = 0;
            $seruser = new Service_User_Company_User();
            $is_invest_status = FALSE;
            //是否已开通招通服务
            if($com_id) {
                $is_invest_status = $seruser->isPlatformServiceFee($com_id);
            }
            $investBCount = $is_invest_status ? 3 : 1;
            $investCount = $this->getInvertByProjectId($data['project_id']);
            $jian =  ($investBCount-count($investCount)) ? ($investBCount-count($investCount)) : 0;
            $account = new Service_Account();
            $account_result = true;
            if($jian <= 0) {
                //发布招商会第一步确定是否扣除？
                $account_result = $account->useAccount($user_id,11,0,'发布'.$invest->investment_name.'投资考察会');
            }
            if($account_result) {
                $res = $invest->create();
                if($jian <= 0) {
                    $account_result = $account->useAccount($user_id,11,1,'发布'.$invest->investment_name.'投资考察会');
                }
            }else{
                return false;
            }
        }
        return $res;
    }
    /**
     * 获得项目id 下的所有招商会会
     * @author 施磊
     */
    public function getInvertByProjectId($project_id, $is_obj = FALSE) {
        $project_id = intval($project_id);
        if(!$project_id) return array();
        $project_invests = ORM::factory("Projectinvest")->where("project_id",'=',$project_id)->find_all();
        $return = array();
        if(!$is_obj) {
            foreach($project_invests as $val) {
                $return[] = $val->as_array();
            }
            return $return;
        }else {
            return $project_invests;
        }
    }
    /**
     * 通过项目id来判断招商会信息是否存在
     * @author 曹怀栋
     */
    public function getInvestProjectid($project_id){
        $project_invest = ORM::factory('Projectinvest')->where("project_id", "=", $project_id)->find();
        $project_invests = ORM::factory("Projectinvest")->where("project_id",'=',$project_id)->find_all();
        foreach ($project_invests as $v){
            $v->investment_isadd = 1;
            $v->update();
        }
        if($project_invest->project_id ==""){
            return false;
        }else{
            return $project_invest;
        }
    }
    /**
     * 判断企业是否已经发布项目信息[审核通过的项目]
     * @author 钟涛
     */
    public function isHasProject($com_id){
        $model = ORM::factory('Project');
        $result = $model->where('com_id', '=', $com_id)->where('project_status', '=', 2)->count_all();
        return $result;
    }

    /**
     * 获取我的项目[审核通过的项目]
     * @author 钟涛
     */
    public function getALLProject($com_id){
        $model = ORM::factory('Project');
        $result = $model->where('com_id', '=', $com_id)->where('project_status', '=', 2)->find_all();
        return $result;
    }
    /**
     * 通过项目id找到该项目对应的递送名片的用户id
     * @author 赵路生
     * @param array
     * @return  array
     */
    public function getCardInfoByProID($project_id,$person_conds=''){
        $person_ids = array();
        $model = ORM::factory('Cardinfo');
        $start_time = $person_conds['start_time'];
        $end_time  = $person_conds['end_time'];
        $invester_name = $person_conds['invester_name'];
        if(!$start_time){
            $start_time = 0;
        }
        if(!$end_time){
            $end_time = time();
        }
        //判断传入的是不是时间戳类型
        if(!( is_numeric ($start_time) && $start_time <= 2147483647)){
            $start_time = 0;
        }
        if(!( is_numeric ($end_time) && $end_time <= 2147483647)){
            $end_time = time();
        }
        //还要对传进来的时间戳进行处理
        if($start_time > $end_time){
            $start_time = 0;
            $end_time = time();
        }
        if($project_id){
            if($invester_name){
                $result = $model->where('to_project_id','=',$project_id)->where('send_time','>=',$start_time)->where('send_time','<=',$end_time)->join('user_person','LEFT')->on('from_user_id','=','per_user_id')->where('per_realname','LIKE','%'.$invester_name.'%')->find_all();
            }else{
                $result = $model->where('to_project_id','=',$project_id)->where('send_time','>=',$start_time)->where('send_time','<=',$end_time)->find_all();
            }
            foreach($result as $v){
                $person_ids[]= $v->from_user_id;
            }
        }
        return array_unique($person_ids);
    }
    /**
     * 判断企业是否已经发布项目信息[审核中的项目]
     * @author 钟涛
     */
    public function isHasProjectSheng($com_id){
        $model = ORM::factory('Project');
        $result = $model->where('com_id', '=', $com_id)->where('project_status', '=', 1)->count_all();
        return $result;
    }

    /**
     * 获取新项目数量[审核通过的项目]（以点击生成名片时间为基准，之后新建的项目均为新项目）
     * @author 钟涛
     */
    public function getNewProjectCount($time,$com_id){
        if($time==0){ //判断是否是第一次设置名片信息
            return 0;
        }
        $model = ORM::factory('Project');
        $result = $model->where('com_id', '=', $com_id)->where('project_status', '=', 2)->where('project_passtime','>',$time)->count_all();
        return $result;
    }

    /**
     * 根据ID判断项目是否已经存在并且已经通过审核
     * @author 钟涛
     */
    public function isHasProjectById($id){
        $model = ORM::factory('Project');
        $result = $model->where('project_id', '=', $id)->where('project_status', '=', 2)->count_all();
        return $result;
    }


    /**
     *标签以三个一组返回
     * @author 曹怀栋
     */
    public function findTag(){
        $tag = ORM::factory('Usertype');
        $result = $tag->where('tag_status', '=', '1')->find_all();
        foreach ($result as $k=>$v){
            $msg[$v->tag_id]['tag_id']=$v->tag_id;
            $msg[$v->tag_id]['tag_name']=$v->tag_name;
         }
        $res = array_chunk($msg, 3);
        return $res;
    }

    /**
     *项目标签以三个一组返回
     * @author 曹怀栋
     */
    public function findProjectTag($int_num = null){
        $tag = ORM::factory('Tag');
        $result = $tag->where('tag_type', '=', '1')->where('tag_status', '=', 0)->find_all();
        foreach ($result as $k=>$v){
            $msg[$v->tag_id]['tag_id']=$v->tag_id;
            $msg[$v->tag_id]['tag_name']=$v->tag_name;
         }

        if($int_num !=null){
            $arr_data = array_rand($msg,intval($int_num));
            foreach ($arr_data as $val){
                $msg1[]=  $msg[$val];
            }
            $res = $msg1;
        }else{
            $res = array_chunk($msg, 3);
        }
        return $res;
    }

    /**
     * 添加项目信息判断
     * @author 曹怀栋
     */
    public function addProjectCheck($form){
        //表单验证开始
        $valid = new Validation($form);
        $valid->rule("project_brand_name", "not_empty");
        $valid->rule("project_amount_type", "not_empty");
        $valid->rule("project_joining_fee", "not_empty");
        $valid->rule("project_joining_fee", "digit");
        $valid->rule("project_principal_products", "not_empty");
        $valid->rule("project_phone", "not_empty");
        $valid->rule("project_join_conditions", "not_empty");
        $valid->rule("project_summary", "not_empty");
        if(!$valid->check()){
            $error = $valid->errors("project/project");
            return $error;
        }
        return true;
    }
    /**
     * 数组改成字符串(用来插入数据库的)
     * @author 曹怀栋
     */
    public function arrayToString($form){
        unset($form['x']);
        unset($form['y']);
        if(isset($form['label'])){
            $form['project_groups_label'] = serialize($form['label']);
            unset($form['label']);
        }else{
            $form['project_groups_label'] = "";
        }
        return $form;
    }

    /**
     * 添加项目的标签
     * @author 施磊
     */
    public function addProjectTag($project_id, $tagArr = array()) {
            $project_id = intval($project_id);
            if(!$project_id) return array();
            if(!$tagArr) return array();
            $tagMod = ORM::factory('Tag');
            $allTag = $tagMod->where('tag_type', '=', '1')->find_all();
            $allTagArr = array();
            $lastTag = array();
            foreach ($allTag as $k=>$v){
                $allTagArr[$v->tag_name] = $v->tag_id;
            }
            if($tagArr) {
                //添加之前先做删除
                $this->deleteProjectTag($project_id);
                foreach($tagArr as $val) {
                        if(!empty($val)){
                            //如果已经存在在TAG表里
                            if(isset($allTagArr[$val])) {
                                $this->addProjectTagList($project_id, $allTagArr[$val]);
                                $lastTag[] = $val;
                            }else {
                                $newTagId = $this->addFreeTag($val);
                                if(!$newTagId) continue;
                                $this->addProjectTagList($project_id, $newTagId);
                                $lastTag[] = $val;
                            }
                    }
                }
            }
            return $lastTag;
    }
    /**
     * 添加自定义的项目标签
     * @author 施磊
     */
    public function addFreeTag($tag_name, $tag_type = 1) {
        $tag_name = trim($tag_name);
        $tag_type = intval($tag_type);
        if(!$tag_name || !$tag_type) return FALSE;
        $data = array('tag_name' => $tag_name, 'tag_type' => $tag_type);
        $ormModel = ORM::factory('Tag');
        try{
             $ormModel->values($data)->create();
        }catch(Kohana_Exception $e){
            return false;
        }
        return $ormModel->tag_id;
    }
    /**
     * 添加单条项目标签
     * @author 施磊
     */
    public function addProjectTagList($project_id, $tag_id) {
        $project_id = intval($project_id);
        $tag_id = intval($tag_id);
        if(!$tag_id || !$project_id) return FALSE;
        $data = array('project_id' => $project_id, 'tag_id' => $tag_id);
        $ormModel = ORM::factory('Projecttag');
        try{
            $ormModel->values($data)->create();
        }catch(Kohana_Exception $e){
            return false;
        }

    }
    /**
     * 获得项目下的所有标签
     * @author 施磊
     */
    public function getProjectTagByProjectId($project_id) {
        $ormModel = ORM::factory('Projecttag')->where('project_id', '=', $project_id)->find_all();
        $tagModel = ORM::factory('tag')->find_all();
        $return = array();
        $temp = array();
        foreach ($tagModel as $val) {
            $temp[$val->tag_id] = $val->tag_name;
        }
        foreach ($ormModel as $val) {
            $return[] = arr::get($temp, $val->tag_id);
        }
        return $return;

    }
    /**
     * 删除项目标签
     * @author 施磊
     */
    public function deleteProjectTag($project_id){
        $tagMod = ORM::factory('Projecttag');
        $result = $tagMod->where("project_id", "=", $project_id)->find();
        if(!empty($result->pt_id)){
            $tagMod = ORM::factory('Projecttag');
            $return = $tagMod->where("project_id", "=", $project_id)->find_all();
            foreach($return as $val) {
                $tagModTemp = ORM::factory('Projecttag', $val->pt_id);
                $tagModTemp->delete();
            }
            return true;
        }
        return false;
    }

    /**
     * 添加单个项目信息
     * @author 曹怀栋
     */
    public function addProject($form,$user_id){
        $projects = ORM::factory('Project');
        if(!empty($form['projcet_founding_time'])){
            $form['projcet_founding_time'] = strtotime($form['projcet_founding_time']."0101");
        }else{
            $form['projcet_founding_time'] = 0;
        }
        $form['project_advert'] = arr::get($form, "project_advert") ? trim(arr::get($form, "project_advert")) : "";
        $form['project_phone'] = ($form['project_phone']) ? ($form['project_phone'].'+'.$form['phone_fj']) : $form['project_phone'];
        unset($form['phone_fj']);
        //取得项目名称和7个查询条件的值，处理并放入项目表中的project_tags字段
        $form['project_tags'] = $this->getProjectTag($form);
        //项目添加时间
        $form['project_addtime'] = time();
        //项目修改时间
        $form['project_updatetime'] = isset($form['project_addtime']) ? $form['project_addtime'] : time();
        $project_tag = $form['project_tag'] ? explode(' ', $form['project_tag']) : array();
        unset($form['project_tag']);
        $form['project_logo'] = common::getImgUrl($form['project_logo']);
        #项目宣传照片
        $project_xuanchuan_da_logo = isset($form['project_xuanchuan_da_logo']) ? @str_replace("/s_","/b_",$form['project_xuanchuan_da_logo']) :"";
        $project_xuanchuan_xiao_logo = isset($form['project_xuanchuan_xiao_logo']) ? $form['project_xuanchuan_xiao_logo'] : "";
        unset($form['project_xuanchuan_da_logo']);
        unset($form['project_xuanchuan_xiao_logo']);
        $form['project_status'] = intval(1);
        $form['rate_return'] = isset($form['rate_return']) ? $form['rate_return'] : intval(0);
       	#拼音处理  
        $form['project_pinyin'] = pinyin::getinitial(arr::get($form,"project_brand_name"));
        //下面是所要插入到招商项目表中的信息
        foreach ($form as $k=>$v){
            if(is_array($v) === false){
                $projects->$k = trim($v);
            }
        }
        //事务
        $db = Database::instance();
        $db->begin();
        $project = $projects->create();
        //echo "<pre>"; print_R($form);exit;
        //项目属性条添加数据
        $this->updateProjectSearchCard($project->as_array(),$form['project_industry_id'],$user_id);
       //下面是所要插入到招商地区表中的信息
       if(isset($project->project_id) && ($project->project_id > 0) && (count($form['project_industry_id']) > 0) && (count($form['project_co_model']) > 0)  && (count($form['project_city']) > 0)){
               #添加项目宣传图
               $arr_image_xuanchuan = array();
           if($project_xuanchuan_da_logo){
                   $arr_image ['project_id'] = $project->project_id;
                   $arr_image ['type'] = intval(4);
                   $arr_image['img'][] = $project_xuanchuan_da_logo;
                   $arr_image_xuanchuan [] = $arr_image;
           }
           if($project_xuanchuan_xiao_logo){
                   $arr_image_small ['project_id'] = $project->project_id;
                   $arr_image_small ['type'] = intval(5);
                   $arr_image_small['img'][] = $project_xuanchuan_xiao_logo;
                   $arr_image_xuanchuan [] = $arr_image_small;
           }
           if(is_array($arr_image_xuanchuan) && !empty($arr_image_xuanchuan)){
                   foreach ($arr_image_xuanchuan as $key=>$val){
                       $this->addProjectXuanChuanImages($val, $val['type']);
                   }
           }
            //添加地区
            $res_area = $this->addProjectArea($project->project_id,$form['project_city']);
            //添加招商形式
            $res_model = $this->addProjectModel($project->project_id,$form['project_co_model']);
            //添加招商行业
            $res_industry = $this->addProjectIndustry($project->project_id,$form['project_industry_id']);
            //添加人脉关系
            if(isset($form['connection'])){
                $res_connection = $this->addProjectConnection($project->project_id,$form['connection']);
            }
            //添加投资人群
            if(isset($form['Investment_groups']) && count($form['Investment_groups'])> 0){
             $res_groups = $this->addProjectCrowd($project->project_id,$form['Investment_groups']);
            }
            if($res_area == true && $res_model == true && $res_industry == true){
            //插入到关联表中的信息成功后，返回项目id
                if(($project->project_id) > 0){
                    $db->commit();
                    $this->addProjectTag($project->project_id, $project_tag);
                    return $project->project_id;
                }else{
                    $db->rollback();
                    return false;
                }
            }else{
                $db->rollback();
                return false;
            }
       }else{
            $db->rollback();
            return false;
       }
    }

    /**
     * 更新单个项目信息
     * @author 曹怀栋
     */
    public function updateProject($form,$user_id){
        $projects = ORM::factory('Project',arr::get($form,'project_id'));
        if($projects->project_id =="") return false;
        //取得项目名称和7个查询条件的值，处理并放入项目表中的project_tags字段
        $form['project_tags'] = $this->getProjectTag($form);
        if(!empty($form['projcet_founding_time'])){
            $form['projcet_founding_time'] = strtotime($form['projcet_founding_time']."0101");
        }else{
            $form['projcet_founding_time'] = 0;
        }
        unset($form['project_logo_old']);
        $project_tag = $form['project_tag'] ? explode(' ', $form['project_tag']) : array();
        unset($form['project_tag']);
        $form['project_phone'] = ($form['phone_fj']) ? ($form['project_phone'].'+'.$form['phone_fj']) : $form['project_phone'];
        unset($form['phone_fj']);
        $form['project_logo'] = common::getImgUrl($form['project_logo']);
        #项目修改时间
        $form['project_updatetime'] = time();
        #项目宣传照片
        $project_xuanchuan_logo = $form['project_xuanchuan_logo'];
        unset($form['project_xuanchuan_logo']);
        //下面是所要更新数据的对应信息
        foreach ($form as $k=>$v){
            if(is_array($v) === false){
                /*
                if($projects->project_status != 2) {
                    $projects->project_status = 0;
                }*/
                $projects->project_display = 0;
                if($v !=""){
                    $projects->$k = trim($v);
                }
            }
        }
        //事务
        $db = Database::instance();
        $db->begin();
        $project=$projects->update();
        //项目属性条添加数据
        $this->updateProjectSearchCard($project->as_array(),$form['project_industry_id'],$user_id);
       //下面是所要插入到招商地区表中的信息
       if(isset($project->project_id) && ($project->project_id > 0) && (count($form['project_industry_id']) > 0) && (count($form['project_co_model']) > 0) && (count($form['connection']) > 0) && (count($form['project_city']) > 0)){
                   #宣化照片处理
                   if($project_xuanchuan_logo){
                       $new_project_xuanchuan_logo = @str_replace("/s_","/b_",$project_xuanchuan_logo);
                       #添加宣化照片
                       $this->insertImageXuanChuan($new_project_xuanchuan_logo,$project->project_id);
                   }
            //添加地区
            $res_area = $this->updateProjectArea($project->project_id,$form['project_city']);
            //添加招商形式
            $res_model = $this->updateProjectModel($project->project_id,$form['project_co_model']);
            //添加招商行业
            $res_industry = $this->updateProjectIndustry($project->project_id,$form['project_industry_id']);
            //添加人脉关系
            $res_connection = $this->updateProjectConnection($project->project_id,$form['connection']);
            //添加投资人群
            $res_groups = $this->updateProjectCrowd($project->project_id,$form['Investment_groups']);

            if($res_area == true && $res_model == true && $res_industry == true && $res_connection == true && $res_groups == true){//插入到关联表中的信息成功后，返回项目id
                if(($project->project_id) > 0){
                    $db->commit();
                    $this->addProjectTag($project->project_id, $project_tag);
                    return true;
                }else{
                    $db->rollback();
                    return false;
                }
            }else{
                $db->rollback();
                return false;
            }
       }else{
            $db->rollback();
            return false;
       }
    }

    /**
     * 取得项目名称和7个查询条件的值，处理并放入项目表中的project_tags字段
     * @author 曹怀栋
     */
    public function getProjectTag($form){
        //项目名称
        $project_tags = $form['project_brand_name'].",";
        //项目地区
        if(count($form['project_city']) > 0){
            foreach ($form['project_city'] as $v){
                $city = ORM::factory('city',$v);
                $project_tags .= $city->cit_name.",";
            }
        }
        //招商形式表
        if(count($form['project_co_model']) > 0){
            $lst = common::businessForm();
            foreach ($lst as $k=>$v){
                if(isset($form['project_co_model'][$k])){
                    $project_tags .= $v.",";
                }
            }
        }
        //人脉关系
        if(isset($form['connection'])){
            if(count($form['connection']) > 0){
                $lst = guide::attr5();
                foreach ($lst as $k=>$v){
                    if(isset($form['connection'][$k])){
                        $project_tags .= $v.",";
                    }
                }
            }
        }
        //招商行业
        if(count($form['project_industry_id']) > 0){
            foreach ($form['project_industry_id'] as $v){
                $pc= ORM::factory("industry",$v);
                if($pc->industry_name != ""){
                    $project_tags .= $pc->industry_name.",";
                }
            }
        }
        /*
        //投资风险
        $risk = guide::attr10();

        if($form['risk'] == 1){
            $project_tags .= $risk[1].",";
        }else{
            $project_tags .= $risk[2].",";
        }*/
        //投资金额
        if(isset($form['project_amount_type']) &&  is_numeric($form['project_amount_type'])){
            $amount_type = common::moneyArr();
            foreach ($amount_type as $k=>$v){
                if($form['project_amount_type'] == $k){
                    $project_tags .= $v;
                }
            }
        }
        //投资回报率
       /* if(isset($form['rate_return']) &&  is_numeric($form['rate_return'])){
            $ratereturn = guide::attr8();
            foreach ($ratereturn as $k=>$v){
                if($form['rate_return'] == $k){
                    $project_tags .= $v;
                }
            }
        }*/

        //项目标签
        if(isset($form['project_tag']) && $form['project_tag']){
            $project_tag = $form['project_tag'] ? explode(' ', $form['project_tag']) : array();
         foreach ($project_tag as $key=>$val){
             if(!empty($val)){
                 $project_tags .=",".$val;
             }
         }
//             if($project_tag) {
//                 $project_tags .= ',';
//                 $project_tag = implode(',', $project_tag);
//                 $project_tags .= $project_tag;
//             }
        }
        return $project_tags;
    }
    /**
     * 删除项目图片或删除项目资质信息
     * 这个方法有三个参数：project_id项目id,user_id用户id,cert_id图片id
     * @author 曹怀栋
     */
    public function deleteProjectImages($project_id,$user_id,$cert_id){
        $result = ORM::factory("Project")->where("project_id", "=",$project_id)->find();
        if($result->com_id == "") return false;
        $companyinfo = ORM::factory("Companyinfo")->where("com_user_id","=",$user_id)->find();
        if($result->com_id == "") return false;
        if($result->com_id != $companyinfo->com_id){
            return false;
        }
        $Projectcerts=ORM::factory("Projectcerts");
        $result = $Projectcerts->where("project_certs_id", "=",$cert_id)->find();
        //当这条数据存在的情况下，这删除这个数据并删除相应的图片
        if($result->project_certs_id !=""){
            if(!empty($result->project_img)){
                $de_imge=URL::imgurl($result->project_img);
                if(@stristr($de_imge,'poster/html') ===FALSE){
                    $delete = common::deletePic($de_imge);
                    $Projectcerts->delete($cert_id);
                }else{
                     $Projectcerts->delete($cert_id);
                     $delete = 1;
                }
                if($delete != 1) return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 取得指定项目的所有图片信息
     * @author 曹怀栋
     */
    public function getProjectImag($project_id,$type){
        $model = ORM::factory('Projectcerts');
        //分页 此处需要设置reset(FALSE) 否则没有数据时默认会显示一页数据
        $count = $model->where('project_id', '=', $project_id)->where('project_type', '=', $type)->reset(FALSE)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 9,
        ));
        $array=array();
        $array['list'] = $model->where('project_id', '=', $project_id)->where('project_type', '=', $type)->limit($page->items_per_page)->offset($page->offset)->order_by('project_certs_id', 'DESC')->find_all()->as_array();
        $array['page']= $page;
        $array['count'] = $count;
        return $array;
    }

    /**
     * 取得认领项目的所有图片信息
     * @author 钟涛
     */
    public function getRenlingProjectImagAll($project_id,$com_id){
        $model = ORM::factory('ProjectRenlingImage');
        $count = $model->where('project_id', '=', $project_id)->where('com_id','=',$com_id)->reset(FALSE)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 16,
        ));
        $array=array();
        $array['list'] = $model->where('project_id', '=', $project_id)->where('com_id','=',$com_id)->limit($page->items_per_page)->offset($page->offset)->order_by('addtime', 'DESC')->find_all()->as_array();
        $array['page']= $page;
        return $array;
    }

    /**
     * 获取项目图片
     * @author 龚湧
     * @param int $project_id
     */
    public function getProjectImg($project_id){
        $model = ORM::factory('Projectcerts')
                 ->where('project_id', '=', $project_id)
                 ->where('project_type', '=', 1);
        $count = $model->reset(FALSE)->count_all();
        if(!$count){
            return false;
        }
        $images = $model->find_all();
        return $images;
    }

    /**
     * 获取项目图片
     * @author 施磊
     * @param int $project_id
     * @param int $type
     */
    public function getProjectImgByType($project_id, $type = 1){
        $model = ORM::factory('Projectcerts')
                 ->where('project_id', '=', $project_id)
                 ->where('project_type', '=', $type);
        $count = $model->reset(FALSE)->count_all();
        if(!$count){
            return false;
        }
        $images = $model->find_all();
        $return = array();
        if(count($images) > 0){
            foreach($images as $val) {
                $return[] = $val->as_array();
            }
        }
        return $return;
    }

    /**
     * 添加项目宣传图片
     * @author 嵇烨
     */
    public function addProjectXuanChuanImages($data,$type){
        $bool = false;
        $project_model=ORM::factory('Projectcerts')->where('project_id',"=",intval($data['project_id']))->where("project_type","=",intval($type))->find()->as_array();
        if($project_model['project_certs_id'] >0 && $project_model['project_certs_id']){
            #删除
            $model = ORM::factory("Projectcerts",intval($project_model['project_certs_id']));
            $model->delete();
        }
        if(count($data['img']) > 0){
                foreach ($data['img'] as $k=>$v){
                    $project = ORM::factory('Projectcerts');
                    $project->project_img = common::getImgUrl($v);
                    $project->project_type = $type;
                    $project->project_id = $data['project_id'];
                    $project->project_addtime = time();
                    $project->create();
                }
                $bool = true;
            }
        return $bool;
    }


    /**
     * 添加图片信息
     * @author 曹怀栋
     */
    public function addProjectImages($data,$type){
        $projectid=ORM::factory('Project',$data['project_id']);
        if($projectid->project_id ==""){
            return false;
        }
        if(count($data['img']) > 0){
            foreach ($data['img'] as $k=>$v){
                $project = ORM::factory('Projectcerts');
                if($type == 2 && $data['name'][$k] !=""){//项目资质要写入
                    $project->project_imgname = trim($data['name'][$k]);
                }
                $project->project_img = common::getImgUrl($v);
                $project->project_type = $type;
                $project->project_id = $data['project_id'];
                $project->project_addtime = time();
                $project->create();
                //只要编辑招商会信息，并且项目已经审核通过的，就要修改审核状态为提交审核
                /*
                if($projectid->project_status == 2){
                    $projectid->project_status = 1;
                    $projectid->update();
                }
                 *
                 */
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 更新单个项目是否在主页显示
     * @author 潘宗磊
     */
    public function updateProjectDisplay($gets){
        $projects=ORM::factory('Project',arr::get($gets, 'project_id'));
        $projects->project_display=arr::get($gets, 'project_display');
        if($projects->save()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 取得单个项目信息
     * @author 曹怀栋
     */
    public function getProject($id){
        $result = ORM::factory('Project',$id)->as_array();
        $result =$this->transformationData($result);
        return $result;
    }

    /**
     * 软删除单个项目信息
     * @author 曹怀栋
     */
    public function deleteProject($project_id,$user_id){
        $project_id = intval($project_id);
        $orm=ORM::factory("Project",$project_id);
        $companyinfo = ORM::factory("Companyinfo")->where("com_user_id","=",$user_id)->find();
        if($orm->com_id != $companyinfo->com_id){
            return false;
        }
        //5表示软删除此项目
        $orm->project_status = 5;
        //事务
        $db = Database::instance();
        $db->begin();
        $project=$orm->update();

//        涛哥说 czzs_project_search_card表暂时不用了，所以不需要调用此方法更新
//        $form_inv = $this->getInventesByProId($project_id);
//        $industry_text=array();
//        $industry_text[0]=$form_inv[0]['industry_id'];
//        $industry_text[1]=$form_inv[1]['industry_id'];
//        $this->updateProjectSearchCard($project->as_array(),$industry_text,$user_id);
        //下面是所要插入到招商地区表中的信息
        if(isset($project->project_id) && ($project->project_id > 0)){
            //软删除地区
            $res_area = $this->softDeleteProjectArea($project->project_id);
            //软删除招商形式
            $res_model = $this->softDeleteProjectModel($project->project_id);
            //软删除招商行业
            $res_industry = $this->softDeleteProjectIndustry($project->project_id);
            //软删除人脉关系
            $res_connection = $this->softDeleteProjectConnection($project->project_id);
            //软删除招商会
            $res_invest = $this->deleteInvestByProjectId($project->project_id);
            if($res_area == true && $res_model == true && $res_industry == true && $res_connection == true && $res_invest == true){
                if(($project->project_id) > 0){
                    $db->commit();
                    return true;
                }else{
                    $db->rollback();
                    return false;
                }
            }else{
                $db->rollback();
                return false;
            }
        }else{
            $db->rollback();
            return false;
        }
    }
    /**
     * 软删除指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function softDeleteProjectConnection($project_id){
        $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $project_id)->find_all();
        //软删除以前的数据
        if(count($project_connection) > 0){
            foreach ($project_connection as $k => $v){
                $model = ORM::factory('Projectconnection',$v->pc_id);
                //下面是所要更新数据的对应信息
                $model->status = 5;
                $model->update();
            }
            return true;
        }
        return false;
    }
    /**
     * 软删除指定项目的招商行业信息
     * @author 曹怀栋
     */
    public function softDeleteProjectIndustry($project_id){
        $project_model = ORM::factory('Projectindustry')->where("project_id", "=", $project_id)->find_all();
        //软删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $model = ORM::factory('Projectindustry',$v->pi_id);
                //下面是所要更新数据的对应信息
                $model->status = 5;
                $model->update();
            }
            return true;
        }
        return false;
    }
    /**
     * 通过项目的id 来获取行业的id
     * @author  嵇烨
     *
     */
    public  function  getInventesByProId($pro_id){
        $p_area = array();
        if($pro_id){
            $project_area = ORM::factory("Projectindustry")->where("project_id","=",intval($pro_id))->find_all();
            foreach($project_area as $vv){
                $p_area[]['industry_id'] = ORM::factory("Projectindustry",$vv->pi_id)->industry_id;
            }
            return  $p_area;
        }
        return false;
    }

    /**
     * 通过项目的id数组 来获取所有行业的id[去除了重复的]
     * @author 钟涛
     *
     */
    public  function  getInventesByProArr($pro_id_arr){
        $p_area = array();
        if(count($pro_id_arr)){
            $project_area = ORM::factory("Projectindustry")->where("project_id","in",$pro_id_arr)->find_all();
            foreach($project_area as $vv){
                $perinfo=ORM::factory("Projectindustry",$vv->pi_id);
                $industryinfo=ORM::factory("industry",$perinfo->industry_id);
                if($industryinfo->parent_id>0){//2级行业
                    $p_area['two'][] = $perinfo->industry_id;
                }else{//1级行业
                    $p_area['one'][] = $perinfo->industry_id;
                }
            }
            //去除重复值
            if(isset($p_area['one']) && count($p_area['one'])){
               $p_area['one']=@array_unique($p_area['one']);
            }
            if(isset($p_area['two']) && count($p_area['two'])){
              $p_area['two']=  @array_unique($p_area['two']);
            }
            return $p_area;
        }
        return array();
    }

    /**
     * 软删除指定项目的招商形式信息
     * @author 曹怀栋
     */
    public function softDeleteProjectModel($project_id){
        $project_model = ORM::factory('Projectmodel')->where("project_id", "=", $project_id)->find_all();
        //软删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $model = ORM::factory('Projectmodel',$v->project_model_id);
                //下面是所要更新数据的对应信息
                $model->status = 5;
                $model->update();
            }
            return true;
        }
        return false;
    }

    /**
     * 软删除指定项目的地区信息
     * @author 曹怀栋
     */
    public function softDeleteProjectArea($project_id){
        $project_area = ORM::factory('Projectarea')->where("project_id", "=", $project_id)->find_all();
        //软删除以前的数据
        if(count($project_area) > 0){
            foreach ($project_area as $k => $v){
                $area = ORM::factory('Projectarea',$v->project_area_id);
                //下面是所要更新数据的对应信息
                $area->status = 5;
                $area->update();
            }
            return true;
        }
        return false;
    }
    /**
     * 硬删除单个项目信息
     * @author 曹怀栋
     */
    public function deleteHardProject($project_id,$user_id){
        $orm=ORM::factory("Project");
        $result = $orm->where("project_id", "=",$project_id)->find();
        $companyinfo = ORM::factory("Companyinfo")->where("com_user_id","=",$user_id)->find();
        if($result->com_id != $companyinfo->com_id){
            return false;
        }
        //删除项目图片，如果项目图片为认证图片则把认证图片字段为空
        if(!empty($result->project_id)){
            $projectCert = ORM::factory("Projectcerts");
            $projectImg = $projectCert->where("project_id", "=",$project_id)->where("project_type", "=",2)->find_all()->as_array();
            if(count($projectImg)>0){
                foreach ($projectImg as $v){
                     if(!empty($v->project_certs_img)){
                        $delete = common::deletePic($v->project_certs_img);
                    }
                    $v->delete();
                }
            }
        }
        //当这条数据存在的情况下，这删除这个数据并删除相应的图片
        if(!empty($result->project_id)){
            //删除项目属性关联表
            $this->deleteHardProjectSearchCard($project_id,$user_id);
            if($result->project_logo !=""){
                $de_url=URL::imgurl($result->project_logo);
                $delete = common::deletePic($de_url);
                if($delete != true) return false;
            }
            $db = Database::instance();
            $db->begin();
            if($orm->delete($project_id)){
                //删除地区信息
                $res_area = $this->deleteProjectArea1($project_id);
                if($res_area != true){
                    $db->rollback();
                    return false;
                }
                //删除人脉关系信息
                $res_connection= $this->deleteProjectConnection1($project_id);
                if($res_connection != true){
                    $db->rollback();
                    return false;
                }

                //删除招商形式信息
                $res_model = $this->deleteProjectModel1($project_id);
                if($res_model != true){
                    $db->rollback();
                    return false;
                }
                //删除招商会信息
                $res_invest = $this->deleteProjectInvest($project_id);
                if($res_invest != true){
                    $db->rollback();
                    return false;
                }
                //删除招商行业信息
                $res_industry = $this->deleteProjectIndustry1($project_id);
                if($res_industry != true){
                    $db->rollback();
                    return false;
                }
            }else{
                return false;
            }
            $db->commit();
            return true;
        }
        return false;
    }

    /**
     * 删除指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function deleteProjectConnection1($project_id){
        $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_connection) > 0){
            foreach ($project_connection as $k => $v){
                $this->deleteProjectConnection($v->pc_id);
            }
        }
        return true;
    }
    /**
     * 通过project_id删除招商行业信息
     * @author 曹怀栋
     */
    public function deleteProjectIndustry1($project_id){
        $pi = ORM::factory('Projectindustry')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($pi) > 0){
            foreach ($pi as $k => $v){
                $this->deleteProjectIndustry($v->pi_id);
            }
        }
        return true;
    }
    /**
     * 删除指定项目的投资人群信息
     * @author 曹怀栋
     */
    public function deleteProjectCrowd1($project_id){
        $project_crowd = ORM::factory('Projectcrowd')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_crowd) > 0){
            foreach ($project_crowd as $k => $v){
                $this->deleteProjectCrowd($v->crowd_id);
            }
        }
        return true;
    }
    /**
     * 删除指定项目的地区信息
     * @author 曹怀栋
     */
    public function deleteProjectArea1($project_id){
        $project_area = ORM::factory('ProjectArea')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_area) > 0){
            foreach ($project_area as $k => $v){
                $this->deleteProjectArea($v->project_area_id);
            }
        }
        return true;
    }
    /**
     * 删除指定项目的招商形式信息
     * @author 曹怀栋
     */
    public function deleteProjectModel1($project_id){
        $project_model = ORM::factory('Projectmodel')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectModel($v->project_model_id);
            }
        }
        return true;
    }
    /**
     * 除理从数据库中读取的数据，按照指定的数据输出
     * @author 曹怀栋
     */
    public function transformationData($result){

        //招商形式从数据库中取出
        $project_model = ORM::factory('Projectmodel')->where("project_id", "=", $result['project_id'])->find_all();
        if(count($project_model) > 0){
            foreach ($project_model as $k=>$v){
                $project_co_model[$v->type_id] = $v->type_id;
            }
        }else{
            $project_co_model = "";
        }
        $result['project_co_model'] = $project_co_model;
        //投资人群（类型）取得tag表的name
        $res_crowd = ORM::factory('Projectcrowd')->where("project_id", "=",$result['project_id'])->find_all();

        if(count($res_crowd) > 0){
            foreach ($res_crowd as $k=>$v){
                $tag = ORM::factory('Usertype',$v->tag_id);
                $project_investment_groups[$k]['tag_id'] = $tag->tag_id;
                $project_investment_groups[$k]['tag_name'] = $tag->tag_name;
            }
            $result['project_investment_groups'] = $project_investment_groups;
        }else{
            $result['project_investment_groups'] = array();
        }
        //投资人群（自行添加的）
        if(!empty($result['project_groups_label'])){
            $result['project_groups_label'] = unserialize($result['project_groups_label']);
        }else{
            $result['project_groups_label'] = array();
        }
        //返回时间格式
        if(!empty($result['projcet_founding_time'])){
            $result['projcet_founding_time'] = date("Y",$result['projcet_founding_time']);
        }
        //读取地区id和名称
        $result['area'] = $this->getAreaIdName($result['project_id']);
        return $result;
    }
    /**
     * 更新投资人群信息
     * @author 曹怀栋
     */
    public function updateProjectCrowd($project_id,$data){
        $project_crowd = ORM::factory('Projectcrowd')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_crowd) > 0){
            foreach ($project_crowd as $k => $v){
                $this->deleteProjectCrowd($v->crowd_id);
            }
        }
        //添加招商形式信息
        $this->addProjectCrowd($project_id,$data);
        return true;
    }

    /**
     * 删除指定项目的招商会信息
     * @author 潘宗磊
     */
    public function deleteProjectInvest($project_id){
        $project_invest = ORM::factory('Projectinvest')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_invest) > 0){
            foreach ($project_invest as $k => $v){
                $v->delete();
            }
        }
        return true;
    }

    /**
     * 添加指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function addProjectConnection($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_connection = ORM::factory('Projectconnection');
                $project_connection->project_id = $project_id;
                $project_connection->connection_id = intval($v);
                if($projectInfo->project_status == 2){
                    $project_connection->status = $projectInfo->project_status;
                }
                $project_connection->save();
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * 取得指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function getProjectConnection($project_id){
        $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $project_id)->find_all();
        $array = array();
        if(count($project_connection) > 0){
            foreach ($project_connection as $k => $v){
                $array[$v->connection_id] = $v->connection_id;
            }
        }
        return $array;
    }
    /**
     * 更新指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function updateProjectConnection($project_id,$data){

        $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_connection) > 0){
            foreach ($project_connection as $k => $v){
                $this->deleteProjectConnection($v->pc_id);
            }
        }
        //添加指定项目的人脉关系信息
        $this->addProjectConnection($project_id,$data);
        return true;
    }
    /**
     * 删除指定项目的人脉关系信息
     * @author 曹怀栋
     */
    public function deleteProjectConnection($pc_id){
        $project_connection = ORM::factory('Projectconnection');
        $result = $project_connection->where("pc_id", "=",$pc_id)->find();
        if(!empty($result->pc_id)){
            $project_connection->delete($result->pc_id);
            return true;
        }
        return false;
    }

    /**
     * 添加投资人群信息
     * @author 曹怀栋
     */
    public function addProjectCrowd($project_id,$data){
        if(count($data) > 0){
            foreach ($data as $v){
                $project_crowd = ORM::factory('Projectcrowd');
                $project_crowd->project_id = $project_id;
                $project_crowd->tag_id = intval($v);
                $project_crowd->save();
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * 删除投资人群信息
     * @author 曹怀栋
     */
    public function deleteProjectCrowd($project_crowd_id){
        $project_crowd = ORM::factory('Projectcrowd');
        $result = $project_crowd->where("crowd_id", "=",$project_crowd_id)->find();
        if(!empty($result->crowd_id)){
            $project_crowd->delete($result->crowd_id);
            return true;
        }
        return false;
    }
    /**
     * 更新招商行业信息
     * @author 曹怀栋
     */
    public function updateProjectIndustry($project_id,$data){
        $project_model = ORM::factory('Projectindustry')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectIndustry($v->pi_id);
            }
        }
        //添加招商形式信息
        $this->addProjectIndustry($project_id,$data);
        return true;
    }
    /**
     * 添加招商行业信息
     * @author 曹怀栋
     */
    public function addProjectIndustry($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_model = ORM::factory('Projectindustry');
                $project_model->project_id = $project_id;
                $project_model->industry_id = intval($v);
                if($projectInfo->project_status == 2){
                    $project_model->status = $projectInfo->project_status;
                }
                $project_model->save();
            }
            return true;
        }else{
            return false;
        }

    }
    /**
     * 删除招商行业信息
     * @author 曹怀栋
     */
    public function deleteProjectIndustry($pi_id){
        $project_model = ORM::factory('Projectindustry');
        $result = $project_model->where("pi_id", "=",$pi_id)->find();
        if(!empty($result->pi_id)){
            $project_model->delete($result->pi_id);
            return true;
        }
        return false;
    }
    /**
     * 更新招商形式信息
     * @author 曹怀栋
     */
    public function updateProjectModel($project_id,$data){
        $project_model = ORM::factory('Projectmodel')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectModel($v->project_model_id);
            }
        }
        //添加招商形式信息
        $this->addProjectModel($project_id,$data);
        return true;
    }
    /**
     * 添加招商形式信息
     * @author 曹怀栋
     */
    public function addProjectModel($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_model = ORM::factory('Projectmodel');
                $project_model->project_id = $project_id;
                $project_model->type_id = intval($v);
                if($projectInfo->project_status == 2){
                    $project_model->status = $projectInfo->project_status;
                }
                $project_model->save();
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * 删除招商形式信息
     * @author 曹怀栋
     */
    public function deleteProjectModel($project_model_id){
        $project_model = ORM::factory('Projectmodel');
        $result = $project_model->where("project_model_id", "=",$project_model_id)->find();
        if(!empty($result->project_model_id)){
            $project_model->delete($project_model_id);
            return true;
        }
        return false;
    }
    /**
     * 添加项目地区信息
     * @author 曹怀栋
    */
    public function addProjectArea($project_id,$data){
        $projectInfo = $this->getProjectData($project_id);
        foreach ($data as $v){
            $project_area = ORM::factory('Projectarea');
            $project_area->project_id = $project_id;
            $city = ORM::factory('city',intval($v));
            $project_area->area_id = intval($v);
            if($projectInfo->project_status == 2){
                $project_area->status = $projectInfo->project_status;
            }
            if(intval($v) > 35){//只写入市级信息
                $project_area->pro_id = intval($city->pro_id);
            }else{
                $project_area->pro_id = intval($v);
            }
            $project_area->save();
        }
        return true;
    }

    /**
     * 删除项目地区信息
     * @author 曹怀栋
     */
    public function deleteProjectArea($project_area_id){
        $project_area = ORM::factory('Projectarea');
        $result = $project_area->where("project_area_id", "=",$project_area_id)->find();
        if(!empty($result->project_area_id)){
            $project_area->delete($project_area_id);
            return true;
        }
        return false;
    }
    /**
     * 更新指定项目的地区信息
     * @author 曹怀栋
     */
    public function updateProjectArea($project_id,$data){
        $project_area = ORM::factory('Projectarea')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_area) > 0){
            foreach ($project_area as $k => $v){
                $this->deleteProjectArea($v->project_area_id);
            }
        }
        //添加项目地区信息
        $this->addProjectArea($project_id,$data);
        return true;
    }

    /**
     * 读取地区id和名称
     * @author 曹怀栋
     */
    public function getAreaIdName($project_id){
        $res = ORM::factory('Projectarea')->where("project_id", "=",$project_id)->find_all();
        if(count($res) > 0){
            //把市级地区id和名称以三维数组输出，省级放在二维里
            foreach ($res as $k=>$v){
                if($v->area_id != $v->pro_id){
                    $city = ORM::factory('city',$v->area_id);
                    $pro  = ORM::factory('city',$v->pro_id);
                    $re[$v->pro_id]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['pro_name'] = $pro->cit_name;
                    $re[$v->pro_id]['data'][$k]['area_id'] = $v->area_id;
                    $re[$v->pro_id]['data'][$k]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['data'][$k]['area_name'] = $city->cit_name;
                    $result = $re;
                }elseif($v->area_id == $v->pro_id && $v->pro_id == 88){
                    $re[$v->pro_id]['pro_id'] = 88;
                    $re[$v->pro_id]['pro_name'] = '全国';
                    $result = $re;
                }elseif($v->area_id == $v->pro_id && $v->pro_id < 36){
                    $pro  = ORM::factory('city',$v->area_id);
                    $re[$v->pro_id]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['pro_name'] = $pro->cit_name;
                    $result = $re;
                }
            }
        }else{
            $result = array();
        }
        return $result;
    }

    /**
     * 根据企业id获取项目数量
     * @author 龚湧
     * @param int $com_id
     * @return number
     */
    public function getProjectCount($com_id){
        $project = ORM::factory("Project");
        $project_renling = ORM::factory("ProjectRenling");
        $count_project = $project->where("com_id","=",$com_id)->where('project_status','<',4)->where('project_status','>',0)->where('isrenling_project','=','0')->count_all();
        $count_renling = $project_renling->where("com_id","=",$com_id)->where('project_status','=',1)->count_all();
        $count = $count_project + $count_renling;
        return (int)$count;
    }
    /**
     * 获得有效的项目数
     * @author stone shi
     */
    public function getEffectiveProjectCount($com_id){
        $project = ORM::factory("Project");
        $project_renling = ORM::factory("ProjectRenling");
        $count_project = $project->where("com_id","=",$com_id)->where('project_status','<',3)->where('project_status','>=',0)->where('isrenling_project','=','0')->count_all();
        $count_renling = $project_renling->where("com_id","=",$com_id)->where('project_status','=',1)->count_all();
        $count = $count_project + $count_renling;
        return (int)$count;
    }
    /**
     * 按条件获取项目条数
     * @author 龚湧
     * @param int $com_id
     * @param int $num
     * @param int $time
     * @return ORM object | boolean
     */
    public function getProjectLimit($com_id,$num,$time=0){
        $project = ORM::factory("Project")
                   ->where("com_id","=",$com_id);
        if($time){
            $project = $project
            ->where("project_addtime",">",$time);
        }
        $project = $project->where("project_status","<",4)->where("project_status",">",0)->limit($num)->find_all();
        if($this->getProjectCount($com_id)){
            return $project;
        }
        return false;
    }

    /**
     * 取得项目资质认证信息（图片）
     * @author 曹怀栋
     * @param int $com_id
     * @return array
     */
    public function getProjectCerts($com_id,$type){
        $project = ORM::factory("Projectcerts")->where("com_id","=",$com_id)->where("project_type","=",$type)->find_all()->as_array();
        if(count($project) > 0){
        foreach ($project as $k=>$v):
         $array[$k]['project_certs_img'] = str_replace('/b_','/s_', $v->project_certs_img);
         $array[$k]['project_certs_id'] = $v->project_certs_id;
         $array[$k]['project_certs_name'] = $v->project_certs_name;
        endforeach;
        }else{
            $array = array();
        }
        return $array;
    }

    /**
     * 根据user_id来取得企业id中项目数量
     * @author 曹怀栋
     * @return number
     */
    public function getTemplateProjectCount($user_id){
        $companyinfo = ORM::factory("Companyinfo")->where("com_user_id","=",$user_id)->find();
        //取得企业id
        if(empty($companyinfo->com_id)){
            return 0;
        }
        //取得项目总量
        $project = ORM::factory("Project");
        $count = $project->where("com_id","=",$companyinfo->com_id)->count_all();
        $count_renling = ORM::factory('ProjectRenling')->where('com_id','=',$companyinfo->com_id)->count_all();
        return (int)($count+$count_renling);
    }

    /**
     * 取得上传项目资质认证信息总数
     * @author 曹怀栋
     */
    public function getProjectcertsCount($com_id){
        $project = ORM::factory("Projectcerts");
        $count = $project->where("com_id","=",$com_id)->count_all();
        return (int)$count;
    }
    /**
     * 取得上传项目资质认证信息总数
     * @author 曹怀栋
     */
    public function getProjectcertsCountByProId($project_id){
        $project = ORM::factory("Projectcerts");
        $count = $project->where("project_id","=",$project_id)->where('project_type','=',2)->count_all();
        return (int)$count;
    }
    /**
     * 我的投资考察会
     * @author 施磊
     */
    public function checkProInvestment($project_id) {
        $project_id = intval($project_id);
        if(!$project_id) return false;
        $data = ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','in',array(0,1,2,3))->count_all();
        return $data;
    }
    /**
     * 判断信息的完整度
     * @author 程序员之死
     */
    public function  checkProInfoComplete($pro_obj) {
        $return = array('basicStatus' => TRUE, 'moreStatus' => TRUE,'moreAllStatus' => TRUE, 'contactStatus' => TRUE, 'percentage' => 100);
        #CNM 本来我想写的简洁一点。。看了需求以后我放弃了 WTF
        if(!arr::get($pro_obj, 'projcet_founding_time', false)) {
            $return['basicStatus'] = false;
            $return['percentage'] = $return['percentage']-2;
        }
        #检查推广信息
        $spreadFiled = array('project_advert', 'project_advert_big', 'project_advert_small', 'project_summary', 'project_tags');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $spreadFiled, $return['percentage'], 3);
        if($tempPercentage != $return['percentage'])
        $return['percentage'] = $tempPercentage;

        #检查项目联系人信息
        $contactFiled = array('project_contact_people', 'project_position', 'project_handset', 'project_phone');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $contactFiled, $return['percentage'], 1);
        if($tempPercentage != $return['percentage']) {
            $return['contactStatus'] = false;
        }
        $return['percentage'] = $tempPercentage;

        #检查更多项目信息
        $moreFiled = array('project_principal_products', 'project_joining_fee', 'project_security_deposit', 'rate_return', 'project_connection', 'product_features', 'project_join_conditions');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $moreFiled, $return['percentage'], 2);
        if($tempPercentage != $return['percentage']) {
            $return['moreStatus'] = false;
        }
        if($tempPercentage == $return['percentage'] - 14) {
            $return['moreAllStatus'] = false;
        }
        $return['percentage'] = $tempPercentage;
        #项目海报
        if(!arr::get(arr::get($pro_obj,'project_post'), 'project_id', 0)) {
            $return['percentage'] = $return['percentage'] - 12;
        }
        #项目图片
        if(!arr::get($pro_obj,'project_img', array())) {
            $return['percentage'] = $return['percentage'] - 12;
        }else{
            switch (count(arr::get($pro_obj,'project_img', array()))) {
                case 1:
                    $return['percentage'] = $return['percentage'] - 8;

                    break;
                case 2:
                    $return['percentage'] = $return['percentage'] - 4;

                    break;

                default:
                    $return['percentage'] = $return['percentage'] - 0;
                    break;
            }
        }
        #项目图片
        if(!arr::get($pro_obj,'project_auth', array())) {
            $return['percentage'] = $return['percentage'] - 12;
        }else{
            switch (count(arr::get($pro_obj,'project_auth', array()))) {
                case 1:
                    $return['percentage'] = $return['percentage'] - 8;

                    break;
                case 2:
                    $return['percentage'] = $return['percentage'] - 4;

                    break;

                default:
                    $return['percentage'] = $return['percentage'] - 0;
                    break;
            }
        }
        return $return;
    }
    /*
     * 我有一头CNM重来也不骑
     * @author 程序员之死
     * 私有的。你们没用 只是一个辅助函数
     */
    private function _justCheckPercentage($pro_obj, $filed, $nowPercentage, $percentage = 0) {
        if($filed) {
            foreach($filed as $val) {
                if(!arr::get($pro_obj, $val, false)) $nowPercentage = $nowPercentage - $percentage;
            }
        }
        return $nowPercentage;
    }
    /**
     * 返回所需要的格式输出
     * @author 施磊
     */
    public function checkProtList($result,$comid='') {
        $cardService = new Service_Card();
        $platformPro = new Service_Platform_Project();
        $resault_array = array ();
        $redis = Cache::instance("redis");
        $arr_project_basic_list = array();
        $arr_project_tuiguang_list = array();
        $arr_project_images = array();
        $arr_project_zizhi_images = array();
        $arr_project_content_list = array();
        $arr_project_more_jiben_list = array();
        $str_project_status_des = "";
        $str_project_des = "";
        if (isset($result)) {
            foreach($result as $k => $v) {
                #查找项目信息
                $json_project_basic_list = $redis->get($v->project_id."_project_basic_list");
                #查找产品图片
                $json_project_images = $redis->get($v->project_id."_project_images");
                #查找资质图片
                $json_project_zizhi_images = $redis->get($v->project_id."_project_zizhi_images");
                #查找更多的项目信息
                $json_project_more_jiben_list = $redis->get($v->project_id."_project_more_jiben_list");
                #查找推广项目信息
                $json_project_tuiguang_list = $redis->get($v->project_id."_project_tuiguang_list");
                #海报
                $json_project_haihao = $redis->get($v->project_id."_project_haibao");
                $int_project_haihao = $redis->get($v->project_id."_project_haibao_status");
                #联系人
                $json_project_content_list = $redis->get($v->project_id."_project_content_list");
                #项目状态
                $json_project_status = $redis->get($v."_project_status");
                #项目二次审核状态
                $int_project_two_pass_status = $redis->get($v."_project_two_pass_status");
                #审核不通过原因
                $json_project_pro_des = $redis->get($v."_project_pro_des");
                if($json_project_basic_list){
                    $arr_project_basic_list = (array)json_decode($json_project_basic_list);
                    //echo "<pre>"; print_r($arr_project_basic_list);exit;
                    #项目名称
                    $resault_array [$k] ['project_brand_name'] =arr::get($arr_project_basic_list, "project_brand_name","");
                    #项目id
                    $resault_array [$k] ['project_id'] = arr::get($arr_project_basic_list, "project_id","");
                    #项目logo
                    $resault_array [$k] ['project_logo'] = URL::imgurl (arr::get($arr_project_basic_list, "project_logo",""));
                    #品牌发源地
                    $resault_array [$k] ['project_brand_birthplace'] = $this->getAreaNameByAreaId(arr::get($arr_project_basic_list, "per_area_id"," "));
                    #招商行业
                    $name = $this->getIndustryNameByIndustryId($arr_project_basic_list['industry_id1']);
                    $name .= ",".$this->getIndustryNameByIndustryId($arr_project_basic_list['industry_id2']);
                    #所属行业
                    $resault_array [$k] ['project_industry_id'] =  isset($name) ? $name : "";
                    #招商地区
                    $city_name = "";
                    foreach ($arr_project_basic_list['project_city'] as $key=>$val){
                        $city_name .= $this->getAreaNameByAreaId($val)." ";
                    }
                    $resault_array [$k] ['project_merchants_region'] = $city_name;
                    #成立时间
                    $resault_array [$k] ['projcet_founding_time'] = arr::get($arr_project_basic_list, "projcet_founding_time","");
                    #投资回报率
                    $resault_array [$k] ['project_amount_type'] = arr::get($arr_project_basic_list,"project_amount_type","");
                }else{
                    $resault_array [$k] ['project_brand_name'] = $v->project_brand_name;
                    $resault_array [$k] ['projcet_founding_time'] = $v->projcet_founding_time;
                    $resault_array [$k] ['project_id'] = $v->project_id;
                    $resault_array [$k] ['project_logo'] = URL::imgurl ( $v->project_logo );
                    $resault_array [$k] ['project_brand_birthplace'] = $v->project_brand_birthplace;
                    // 所属行业
                    $resault_array [$k] ['project_industry_id'] = $this->getProjectindustry ( $v->project_id );
                    // 取得地区
                    $resault_array [$k] ['project_merchants_region'] = $this->getProArea ( $v->project_id );
                    $resault_array [$k] ['project_amount_type'] = $v->project_amount_type;
                }
                #推广信息
                if($json_project_tuiguang_list){
                    $arr_project_tuiguang_list = (array)json_decode($json_project_tuiguang_list);
                    #推广语
                    $resault_array [$k] ['project_advert'] = arr::get($arr_project_tuiguang_list, "project_advert", "");
                    #推广小图
                    if(isset($arr_project_tuiguang_list['project_xuanchuan_xiao_logo']) && !empty($arr_project_tuiguang_list['project_xuanchuan_xiao_logo'])){
                        $resault_array [$k] ['project_advert_small'] = array(array(arr::get($arr_project_tuiguang_list, "project_xuanchuan_xiao_logo")));
                    }
                    #推广大图
                    if(isset($arr_project_tuiguang_list['project_xuanchuan_da_logo']) && !empty($arr_project_tuiguang_list['project_xuanchuan_da_logo'])){
                        $resault_array [$k] ['project_advert_big'] = array(array(arr::get($arr_project_tuiguang_list, "project_xuanchuan_da_logo")));
                    }

                    #项目详情
                    $resault_array [$k] ['project_summary'] = arr::get($arr_project_tuiguang_list, "project_summary", "");
                    #项目标签
                    $resault_array [$k] ['project_tags'] = arr::get($arr_project_tuiguang_list, "project_tag", "");
                }else{
                    #广告语
                    $resault_array [$k] ['project_advert'] = $v->project_advert;
                    #广告小图
                    $resault_array [$k] ['project_advert_small'] = $this->getProjectImgByType($v->project_id, 5);
                    #广告大图
                    $resault_array [$k] ['project_advert_big'] = $this->getProjectImgByType($v->project_id, 4);
                    #项目详情
                    $resault_array [$k] ['project_summary'] = $v->project_summary;
                    #项目标签
                    $resault_array [$k] ['project_tags'] = $this->getProjectTagByProjectId($v->project_id);
                }
                #项目图片
                if($json_project_images){
                    $arr_project_images = (array)json_decode($json_project_images);
                    $image = $this->get_Cache_And_Database_Images($v->project_id,$arr_project_images,1);
                    $resault_array [$k] ['project_img'] = arr::get($image, "list","");
                }else{
                    #项目图片
                    $resault_array [$k] ['project_img'] = $this->getProjectImgByType($v->project_id, 1);
                }
                #项目资质图片
                if($json_project_zizhi_images){
                    $arr_project_zizhi_images = (array)json_decode($json_project_zizhi_images);
                    $image_zizhi = $this->get_Cache_And_Database_Images($v->project_id,$arr_project_zizhi_images,2);
                    $resault_array [$k] ['project_auth'] = arr::get($image_zizhi, "list","");
                }else{
                    #资历图片
                    $resault_array [$k] ['project_auth'] = $this->getProjectImgByType($v->project_id, 2);
                }
                #项目基本信息
                if($json_project_more_jiben_list){
                    $arr_project_more_jiben_list = (array)json_decode($json_project_more_jiben_list);
                    #主营产品
                    $resault_array [$k] ['project_principal_products'] = arr::get($arr_project_more_jiben_list, "project_principal_products", '');
                    #加盟费
                    $resault_array [$k] ['project_joining_fee'] = arr::get($arr_project_more_jiben_list, "project_joining_fee", '');
                    #保证金
                    $resault_array [$k] ['project_security_deposit'] = arr::get($arr_project_more_jiben_list, "project_security_deposit", '');
                    #投资回报率
                    $resault_array [$k] ['rate_return'] = arr::get($arr_project_more_jiben_list, "rate_return", '');
                    #人脉关系
                    $resault_array [$k] ['project_connection'] = arr::get($arr_project_more_jiben_list, "connection", '');
                    #产品特点
                    $resault_array [$k] ['product_features'] = arr::get($arr_project_more_jiben_list, "product_features", '');
                    #加盟详情
                    $resault_array [$k] ['project_join_conditions'] = arr::get($arr_project_more_jiben_list, "project_join_conditions", '');
                }else{
                    #主营产品
                    $resault_array [$k] ['project_principal_products'] = $v->project_principal_products;
                    #加盟费
                    $resault_array [$k] ['project_joining_fee'] = $v->project_joining_fee;
                    #保证金
                    $resault_array [$k] ['project_security_deposit'] = $v->project_security_deposit;
                    #投资回报率
                    $resault_array [$k] ['rate_return'] = $v->rate_return;
                    #人脉关系
                    $resault_array [$k] ['project_connection'] = $this->getProjectConnection($v->project_id);
                    #产品特点
                    $resault_array [$k] ['product_features'] = $v->product_features;
                    #加盟详情
                    $resault_array [$k] ['project_join_conditions'] = $v->project_join_conditions;
                }
                #联系人信息
                if($json_project_content_list){
                    $arr_project_content_list = (array)json_decode($json_project_content_list);
                    //echo "<pre>"; print_R($arr_project_content_list);exit;
                    #项目联系人
                    $resault_array [$k] ['project_contact_people'] = arr::get($arr_project_content_list, "project_contact_people"," ");
                    #联系人职位
                    $resault_array [$k] ['project_position'] = arr::get($arr_project_content_list, "project_position"," ");
                    #联系人手机
                    $resault_array [$k] ['project_handset'] = arr::get($arr_project_content_list, "project_handset"," ");
                    #公司座机号码
                    $resault_array [$k] ['project_phone'] = arr::get($arr_project_content_list, "project_phone"," ");
                }else{
                    #项目联系人
                    $resault_array [$k] ['project_contact_people'] = $v->project_contact_people;
                    #联系人职位
                    $resault_array [$k] ['project_position'] = $v->project_position;
                    #联系人手机
                    $resault_array [$k] ['project_handset'] = $v->project_handset;
                    #公司座机号码
                    $resault_array [$k] ['project_phone'] = $v->project_phone;
                }
// 				#项目海报
                 if($json_project_haihao){

                     $resault_array [$k] ['project_post'] = $this->getProjectPoster($v->project_id)->as_array();
                 }else{
// 					#项目海报
                    $resault_array [$k] ['project_post'] = $this->getProjectPoster($v->project_id)->as_array();
                    $resault_array [$k] ['project_poster_status'] = "";
                 }

                 $resault_array [$k] ['project_poster_status'] = $int_project_haihao ? $int_project_haihao : "";

                #$int_project_haihao

                $arr_haibao_data = $this->getProjectPoster($v->project_id)->as_array();
                if($int_project_haihao && $v->project_status == 2 && arr::get($arr_haibao_data,'poster_status') == 2){
                    $resault_array [$k] ['project_post_status'] = $int_project_haihao;
                }else{
                    // #项目海报
                    $resault_array [$k] ['project_post_status'] = arr::get($arr_haibao_data,'poster_status');
                }
                #查看缓存中的数据是不是审核通过
                $resault_array [$k]['panduan_status'] = $v->project_status;
                if($json_project_status){
                    $resault_array [$k] ['str_project_status_des'] = json_decode($json_project_status);
                }else{
                    $resault_array [$k] ['str_project_status_des'] = 1;
                }
                if($json_project_basic_list || $json_project_images || $json_project_zizhi_images || $json_project_more_jiben_list || $json_project_tuiguang_list || $json_project_content_list){
                    #表示项目修改信息审核中
                    $resault_array [$k] ['project_status'] = 8;
                }else{
                    $resault_array [$k] ['project_status'] = $v->project_status;
                }
                $resault_array [$k] ['project_temp_status'] =  $v->project_temp_status;

                if($int_project_two_pass_status == 2){
                    $resault_array [$k] ['project_pass_status'] = 2;
                }else{
                    $resault_array [$k] ['project_pass_status'] = 1;
                }
                #项目审核不通过的原因
                $resault_array [$k] ['project_reason'] = $v->project_reason;

                $resault_array [$k] ['project_source'] = $v->project_source;
                #项目投资考察会
                $resault_array [$k] ['project_invesrment'] = $this->checkProInvestment($v->project_id);
                #项目昨天的名片
                $resault_array [$k] ['project_card_yesterday'] = $cardService->getCardCountByProject($v->project_id, -1);
                #项目今天的名片
                $resault_array [$k] ['project_card_today'] = $cardService->getCardCountByProject($v->project_id, 0);
                #项目30天的名片
                $resault_array [$k] ['project_card_month'] = $cardService->getCardCountByProject($v->project_id, 30);
                #昨天的PV
                $resault_array [$k] ['project_pv_yesterday'] = $platformPro->getPvCountByProject($v->project_id, 0);
                #30的PV
                $resault_array [$k] ['project_pv_month'] = $platformPro->getPvCountByProject($v->project_id, 30);
                #判断
                $resault_array [$k] ['infoComplete'] = $this->checkProInfoComplete($resault_array [$k]);
                $int_status =  ORM::factory("ProjectUpgrade")->where("out_project_id","=",$v->project_id)->find()->status;
                 $resault_array [$k] ['project_upgrade_status'] = $int_status ? $int_status : 0;
            }
        }
        return $resault_array;
    }
    /**
     * 返回所需要的格式输出
     *
     * @author 曹怀栋
     */
    public function getResaultList($result,$comid='') {
        $resault_array = array ();
        if (isset ( $result )) {
            foreach ( $result as $k => $v ) {
                $resault_array [$k] ['project_brand_name'] = $v->project_brand_name;
                $resault_array [$k] ['project_id'] = $v->project_id;
                $resault_array [$k] ['com_id'] = $v->com_id;
                $resault_array [$k] ['project_logo'] = URL::imgurl ( $v->project_logo );
                $resault_array [$k] ['project_brand_birthplace'] = $v->project_brand_birthplace;
                $resault_array [$k] ['project_principal_products'] = $v->project_principal_products;
                $resault_array [$k] ['projcet_founding_time'] = $v->projcet_founding_time;
                $resault_array [$k] ['project_amount_type'] = $v->project_amount_type;
                $resault_array [$k] ['project_joining_fee'] = $v->project_joining_fee;
                $resault_array [$k] ['project_security_deposit'] = $v->project_security_deposit;
                $resault_array [$k] ['project_status'] = $v->project_status;
                $resault_array [$k] ['outside_id'] = $v->outside_id;
                $resault_array [$k] ['project_source'] = $v->project_source;
                $resault_array [$k] ['project_display'] = $v->project_display;
                $resault_array [$k] ['risk'] = $v->risk;
                $resault_array [$k] ['rate_return'] = $v->rate_return;
                // 人脉关系
                $resault_array [$k] ['connection'] = $this->getProjectConnection ( $v->project_id );
                // 取得人群
                $group_text = $this->getProjectCrowd ( $v->project_id, $v->project_groups_label );
                if ($group_text == ",") {
                    $resault_array [$k] ['project_investment_groups'] = "";
                } else {
                    $resault_array [$k] ['project_investment_groups'] = $group_text;
                }
                // 取得地区
                $resault_array [$k] ['project_merchants_region'] = $this->getProArea ( $v->project_id );
                // 招商形式从数据库中取出
                $resault_array [$k] ['project_co_model'] = $this->getProjectCoModel ( $v->project_id );
                $resault_array [$k] ['project_phone'] = $v->project_phone;
                $resault_array [$k] ['project_join_conditions'] = $v->project_join_conditions;
                $resault_array [$k] ['project_summary'] = $v->project_summary;
                // 所属行业
                $resault_array [$k] ['project_industry_id'] = $this->getProjectindustry ( $v->project_id );
                // 认领项目
                $resault_array [$k] ['isrenling_project'] = $v->isrenling_project; // 是否是认领项目
                if ($v->isrenling_project == 1) {
                    $renlingmodel = ORM::factory ( 'ProjectRenling' )->select ( '*' )->where ( 'project_id', '=', $v->project_id )->where ( 'com_id', '=', $comid )->find ();
                    $resault_array [$k] ['isrenling_project_status'] = $renlingmodel->project_status;
                } else {
                    $resault_array [$k] ['isrenling_project_status'] = 0;
                }
            }
        }
        return $resault_array;
    }

    /**
     * 返回所需要的格式输出
     *
     * @author 曹怀栋
     */
    public function getResaultListArr($result,$comid='') {
        $resault_array = array ();
        if (isset ( $result )) {
            foreach ( $result as $k => $v ) {
                $projectinfo=ORM::factory('Project',$v['id']);
                $resault_array [$k] ['project_brand_name'] = $projectinfo->project_brand_name;
                $resault_array [$k] ['project_id'] = $v['id'];
                $resault_array [$k] ['project_logo'] = $projectinfo->project_logo;
                $resault_array [$k] ['project_brand_birthplace'] = $projectinfo->project_brand_birthplace;
                $resault_array [$k] ['project_principal_products'] = $projectinfo->project_principal_products;
                $resault_array [$k] ['projcet_founding_time'] = $projectinfo->projcet_founding_time;
                $resault_array [$k] ['project_amount_type'] = $projectinfo->project_amount_type;
                $resault_array [$k] ['project_joining_fee'] = $projectinfo->project_joining_fee;
                $resault_array [$k] ['project_security_deposit'] = $projectinfo->project_security_deposit;
                $resault_array [$k] ['project_status'] = $projectinfo->project_status;
                $resault_array [$k] ['outside_id'] = $projectinfo->outside_id;
                $resault_array [$k] ['project_source'] = $projectinfo->project_source;
                $resault_array [$k] ['project_display'] = $projectinfo->project_display;
                $resault_array [$k] ['risk'] = $projectinfo->risk;
                $resault_array [$k] ['rate_return'] = $projectinfo->rate_return;
                // 人脉关系
                $resault_array [$k] ['connection'] = $this->getProjectConnection ( $projectinfo->project_id );
                // 取得人群
                $group_text = arr::get($v['val'],'renqun','');

                // 取得地区
                $resault_array [$k] ['project_merchants_region'] = arr::get($v['val'],'areaPid','');
                // 招商形式从数据库中取出
                $resault_array [$k] ['project_co_model'] = arr::get($v['val'],'pmodel','');
                $resault_array [$k] ['project_phone'] = $projectinfo->project_phone;
                $resault_array [$k] ['project_join_conditions'] = $projectinfo->project_join_conditions;
                $resault_array [$k] ['project_summary'] = $projectinfo->project_summary;
                // 所属行业
                $resault_array [$k] ['project_industry_id'] = $this->getProjectindustry ( $projectinfo->project_id );
                // 认领项目
                $resault_array [$k] ['isrenling_project'] = $projectinfo->isrenling_project; // 是否是认领项目
                $renlingmodel = ORM::factory ( 'ProjectRenling' )->select ( '*' )->where ( 'project_id', '=', $projectinfo->project_id )->where ( 'com_id', '=', $comid )->find ();
                if($renlingmodel->loaded()){//我已经认领了
                    $resault_array [$k] ['isrenling_project_status'] = $renlingmodel->project_status;
                }else{//我没有认领
                    $renlingcount = ORM::factory ( 'ProjectRenling' )->where ( 'project_id', '=', $projectinfo->project_id )->where( 'project_status', '=', 1 )->count_all();
                    if($projectinfo && ($projectinfo->project_source==4 || $projectinfo->project_source==5) && $renlingcount==0){//是中国加盟网的项目显示认领图标
                        //显示去认领
                        $resault_array [$k] ['isrenling_project_status'] = 3;
                    }else{
                        //显示已被其他公司认领
                        $resault_array [$k] ['isrenling_project_status'] = 4;
                    }
                }
            }
        }
        return $resault_array;
    }

    /**
     * 根据项目ID获取对应行业信息
     * @author 曹怀栋
     */
    public function getProjectindustry($project_id){
        $pi= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->order_by('pi_id', 'ASC')->find_all();
        if(count($pi) > 0){
            $industry_name = "";
            foreach ($pi as $key=>$value){
                $pc= ORM::factory("industry")->where("industry_id", "=",$value->industry_id)->find();
                $industry_name .= $pc->industry_name.",";
            }
            return substr($industry_name,0,-1);
        }else{
           return "";
        }
    }

    /**
     * 根据项目ID获取对应行业信息[包括id信息]
     * @author 钟涛
     */
    public function getProjectindustryAndId($project_id){
        $pi= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->order_by('pi_id', 'ASC')->find_all();
        $industry = array();
        if(count($pi) > 0){
            $industry_name = "";
            foreach ($pi as $key=>$value){
                $pc= ORM::factory("industry",$value->industry_id);
                if($pc->parent_id==0){//1级行业
                    $industry['one_id']=$pc->industry_id;
                    $industry['one_name']=$pc->industry_name;
                }else{//2级行业
                    $industry['two_id']=$pc->industry_id;
                    $industry['two_name']=$pc->industry_name;
                }
            }
            return $industry;
        }
        return $industry;
    }

    /**
     * 根据项目ID获取意向投资人数
     * @author 钟涛
     */
    public function getPerCountByIndustry($project_id){
        $pi= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->order_by('pi_id', 'ASC')->find_all();
        if(count($pi) > 0){
            $industryidone=array();
            $industryidtwo='';
            foreach ($pi as $key=>$value){
                $pc= ORM::factory("industry")->where("industry_id", "=",$value->industry_id)->find();
                if($pc->parent_id>0){//为2级行业
                    $industryidone[]=$pc->industry_id;
                }else{//1级行业
                    $industryidtwo=$pc->industry_id;
                }
            }
            if(count($industryidone)){//有2级行业
                $ormcount= ORM::factory('UserPerIndustry')
                            ->where('industry_id','in',$industryidone)
                            ->group_by('user_id')
                            ->find_all();
                return count($ormcount);//返回意向人数
            }else{//只有一级行业
                $ormcount= ORM::factory('UserPerIndustry')
                ->where('parent_id','=',$industryidtwo)
                ->group_by('user_id')
                ->find_all();
                return count($ormcount);//返回意向人数
            }
        }
        return 0;
    }

    /**
     * 根据项目ID返回浏览量
     * @author 钟涛
     */
    public function getPvCountByProjectid($project_id){
        if(intval($project_id) && $project_id){
            $projects = ORM::factory('Project',$project_id);
            if($projects->project_pv_count){
                return $projects->project_pv_count;
            }
        }
        return 0;
    }

    /**
     * 根据项目ID获取对应行业信息(搜索用)
     * @author 郁政
     */
    public function getProjectindustry2($project_id){
        $pi= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->order_by('pi_id', 'ASC')->find_all();
        $industry_arr = array();
        if(count($pi) > 0){
            foreach ($pi as $key=>$value){
                if($value->industry_id != 0){
                    $pc= ORM::factory("industry")->where("industry_id", "=",$value->industry_id)->find();
                    $industry_arr[$pc->industry_id] = $pc->industry_name;
                }
            }
        }
        return $industry_arr;
    }
    /**
     * 获得地区字符串
     * @author 施磊
     *
     */
    public function getProArea($project_id, $separator = '，') {
        $areaIdName = $this->getAreaIdName ( $project_id );
        $area_city = array();
        if (count ( $areaIdName ) > 0) {
            foreach ( $areaIdName as $key => $value ) {
                if (isset ( $value ['data'] ) && count ( $value ['data'] ) > 0) {
                    foreach ( $value ['data'] as $ke => $va ) {
                        $area_city[] = $va ['area_name'];
                    }
                }else{
                    $area_city[] = $value['pro_name'];
                }
            }
            return implode($separator, $area_city);
        }else {
            return "全国";
        }
    }
    /**
     * 根据项目ID获取对应地区信息
     * @author 曹怀栋
     */
    public function getProjectArea($project_id) {
        $areaIdName = $this->getAreaIdName ( $project_id );
        if (count ( $areaIdName ) > 0) {
            foreach ( $areaIdName as $key => $value ) {
                $area_city = $value ['pro_name'] . ":";
                if (isset ( $value ['data'] ) && count ( $value ['data'] ) > 0) {
                    foreach ( $value ['data'] as $ke => $va ) :
                        $area_city .= $va ['area_name'] . "+";
                    endforeach
                    ;
                    $area_city = substr ( $area_city, 0, - 1 );
                } else {
                    $area_city = $value ['pro_name'];
                }
                $area [$key] = $area_city;
            }
            return $area;
        } else {
            return "全国";
        }
    }

    /**
     * 根据项目ID获取对应人群
     * @author 曹怀栋
     */
    public function getProjectCrowd($project_id,$project_groups_label) {
            $group = "";
            $res_crowd = ORM::factory('Projectcrowd')->where("project_id", "=",$project_id)->find_all();
            if(count($res_crowd) > 0){
                foreach ($res_crowd as $kee=>$vee){
                    $tag = ORM::factory('Usertype',$vee->tag_id);
                    $group .= $tag->tag_name.",";
                }
            }
            //除理人群输出数据
            if(!empty($v->project_groups_label)){
               return $group.implode(",", unserialize($project_groups_label));
            }else{
                return substr($group,0,-1);
            }
    }

    /**
     * 根据项目ID获取对应人群[返回的数据包括id信息]
     * @author 钟涛
     */
    public function getProjectCrowdAndId($project_id,$project_groups_label) {
        $group = array();
        $res_crowd = ORM::factory('Projectcrowd')->where("project_id", "=",$project_id)->find_all();
        if(count($res_crowd) > 0){
            foreach ($res_crowd as $kee=>$vee){
                $tag = ORM::factory('Usertype',$vee->tag_id);
                $group[$vee->tag_id]=$tag->tag_name;
            }
        }
        return $group;
    }

    /**
     * 根据项目ID获取招商形式从数据库中取出
     * @author 曹怀栋
     */
    public function getProjectCoModel($project_id) {
          $project_model = ORM::factory('Projectmodel')->where("project_id", "=", $project_id)->find_all();
            if(count($project_model) > 0){
                foreach ($project_model as $ke=>$ve){
                    $project_co_model[$ve->type_id] = $ve->type_id;
                }
                return $project_co_model;
            }else{
                return array();
            }
    }

    /**
     * 更新项目时读取行业信息（一级和二级的）
     * @author 曹怀栋
     */
    public function getIndustry($project_id){
        $pi= ORM::factory('Industry')->join('project_industry','left')->on('industry.industry_id','=','project_industry.industry_id')->where("project_id", "=",$project_id)->find_all();
        $msg = array();
        if(count($pi) > 0){
            //读取一级行业信息，并确定选择的一级行业
            $msgs= common::primaryIndustry(0);
            foreach ($msgs as $ke=>$ve){
                $msg[0][$ke]['industry_id']=$ve->industry_id;
                $msg[0][$ke]['industry_name']=$ve->industry_name;
                foreach ($pi as $k=>$v){
                    //只取一级行业信息
                    if($v->parent_id == 0){
                        if($v->industry_id == $ve->industry_id){
                            //1表示选定的一级行业
                            $msg[0][$ke]['status']=1;
                        }else{
                            $msg[0][$ke]['status']=0;
                        }
                    }
                }
            }
            //读取二级行业信息，并确定选择的二级行业
            if(isset($pi[0]->industry_id) && $pi[0]->parent_id == 0){
                //根据一级行业id来取的二级行业
                $pc= ORM::factory("industry")->where("parent_id", "=",$pi[0]->industry_id)->find_all();
                foreach ($pc as $k=>$v){
                    $msg[1][$k]['industry_id']=$v->industry_id;
                    $msg[1][$k]['industry_name']=$v->industry_name;
                    if(isset($pi[1]->industry_id)){
                        if($v->industry_id == $pi[1]->industry_id){
                            //1表示选定的二级行业
                            $msg[1][$k]['status']=1;
                        }else{
                            $msg[1][$k]['status']=0;
                        }
                    }else{
                        $msg[1][$k]['status']=0;
                    }
                }
            }else{
                //根据一级行业id来取的二级行业
                $pc= ORM::factory("industry")->where("parent_id", "=",$pi[1]->industry_id)->find_all();
                foreach ($pc as $k=>$v){
                    $msg[1][$k]['industry_id']=$v->industry_id;
                    $msg[1][$k]['industry_name']=$v->industry_name;
                    if(isset($pi[0]->industry_id)){
                        if($v->industry_id == $pi[0]->industry_id){
                            //1表示选定的二级行业
                            $msg[1][$k]['status']=1;
                        }else{
                            $msg[1][$k]['status']=0;
                        }
                    }else{
                        $msg[1][$k]['status']=0;
                    }
                }
            }
            //echo "<pre>";print_r($msg);exit;
        }
        return $msg;
    }

    /**
     * 更新项目时读取行业信息-新（一级和二级的）
     *
     * @author 郁政
     */
    public function getIndustryNew($project_id){
        $pi = ORM::factory('Industry')->where('parent_id', '=',0)->find_all();
        $sind = ORM::factory('Industry')->join('project_industry','left')->on('industry.industry_id','=','project_industry.industry_id')->where('project_id', '=',$project_id)->where('parent_id','=',0)->find_all();
        $res = array ();
        //一级行业
        foreach($pi as $k => $v){
            $res[0][$k]['industry_id'] = $v->industry_id;
            $res[0][$k]['industry_name'] = $v->industry_name;
            if(isset($sind[0]) && $v->industry_id == $sind[0]->industry_id){
                $res[0][$k]['status'] = 1;
            }else{
                $res[0][$k]['status'] = 0;
            }
        }
        //二级行业
        if(!isset($sind[0])){
            $pi2 = ORM::factory('Industry')->where('parent_id', '=',1)->find_all();
        }else{
            $pi2 = ORM::factory('Industry')->where('parent_id', '=',$sind[0]->industry_id)->find_all();
        }
        $sind2 = ORM::factory('Industry')->join('project_industry','left')->on('industry.industry_id','=','project_industry.industry_id')->where('project_id', '=',$project_id)->where('parent_id','<>',0)->find_all();
        foreach($pi2 as $key => $val){
            $res[1][$key]['industry_id'] = $val->industry_id;
            $res[1][$key]['industry_name'] = $val->industry_name;
            foreach($sind2 as $keyT => $valT){
                if($val->industry_id == $valT->industry_id){
                    $res[1][$key]['status'] = 1;
                }else{
                    $res[1][$key]['status'] = 0;
                }
            }
        }
        return $res;
    }

    /**
     * 更新单个项目信息
     * @author 曹怀栋
     */
    public function updateProjectPublish($form){
        $projects = ORM::factory('Project',arr::get($form,'project_id'));
        if($projects->project_id =="") return false;
        //下面是所要更新数据的对应信息
        if(is_numeric(arr::get($form,'project_template'))){
            $projects->project_template = arr::get($form,'project_template');
            if($this->checkProInfo($projects->project_id , $projects->com_id)) {
                if($projects->project_status != 2) {
                    $projects->project_status = 1;
                }
            }else{
                    $projects->project_status = 0;
            }
            $projects->update();
            return true;
        }else{
            return false;
        }
    }
    /**
     * 发布项目
     * @author 施磊
     */
    public function submitProject($project_id){
        $projects = ORM::factory('Project', $project_id);
        if($projects->project_id =="") return false;
        //下面是所要更新数据的对应信息
            if($this->checkProInfo($projects->project_id , $projects->com_id)) {
                if($projects->project_status != 2) {
                    $projects->project_status = 1;
                }
            }else{
                    $projects->project_status = 0;
            }
            $projects->update();
            return true;
    }

    /**
     * 获取项目logo
     * @author 钟涛
     */
    public function getProjectLogo($projectinfo){
        $logo=URL::webstatic('images/common/company_default.jpg');//默认的logo
        if($projectinfo->project_source != 1) {
            $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
            if(project::checkProLogo($tpurl)){
                $logo= $tpurl;
            }
        }else {
            $tpurl=URL::imgurl($projectinfo->project_logo);
            if(project::checkProLogo($tpurl)){
                $logo= $tpurl;
            }
        }
        return $logo;
    }

    /**
     * 读取一级行业和二级行业和名称关合并
     * @author 曹怀栋
     */
    public function getIndustryName($pc_id){
        $pc= ORM::factory("industry")->where("industry_id", "=",$pc_id)->find();
        if($pc->parent_id !=""){
            if($pc->parent_id == 0){
                $industry = $pc->industry_name;
            }else{
                $msgs= ORM::factory("industry")->where("industry_id", "=",$pc->parent_id)->find();
                $industry = $msgs->industry_name."->".$pc->industry_name;
            }
        }else{
            $industry ="";
        }
        return $industry;
    }

    /**
     * 根据2级行业id返回1级行业id
     * @author 钟涛
     */
    public function getParentid($pc_id){
        $pc= ORM::factory("industry")->where("industry_id", "=",$pc_id)->find();
        return $pc->parent_id;
    }

    /**
     * 根据项目id返回1级行业id
     * @author 钟涛
     */
    public function getParentidByPorjectid($projectid){
        $projectindustry=ORM::factory('Projectindustry')->where('project_id', '=', $projectid)->find_all();
        if(count($projectindustry) > 0){
            foreach ($projectindustry as $v){
                if($v->industry_id < 8){
                   return $v->industry_id;
                }
            }
        }else{
            return 0;
        }
    }

    /**
     * 更新项目属性表(此表用于-个人中心：收到的&递出的&收藏的名片筛选条件)
     * @author 钟涛
     */
    public function updateProjectSearchCard($data,$project_industry_id,$user_id){
        $model=ORM::factory('ProjectSearchCard');
        $result = $model->select('*')->where('user_id','=',$user_id)->where('project_id','=',$data['project_id'])->find()->as_array();
        if( isset($project_industry_id[0]) && $project_industry_id[0] < 8){
            $model->parent_id = intval($project_industry_id[0]);
            $model->project_industry_id=intval($project_industry_id[1]);
        }else{
            $model->parent_id = intval($project_industry_id[1]);
            $model->project_industry_id=intval($project_industry_id[0]);
        }
        $model->project_amount_type=$data['project_amount_type'];
        $model->project_status=$data['project_status'];
        if($result['project_id']!=""){//更新项目属性
            $model->updatetime = time();
            $model->update();
        }else{//添加新的项目属性
            $model->project_status = $data['project_status'];//新建的项目初始为提交审核中
            $model->project_id=$data['project_id'];
            $model->user_id = $user_id;
            $model->addtime = time();
            $model->updatetime = time();
            $model->create();
        }
    }//funtion end

    /**
     * 软删除项目属性表
     * @author 钟涛
     */
    public function deleteProjectSearchCard($project_id,$user_id){
        $orm=ORM::factory("ProjectSearchCard")->where('user_id','=',$user_id)->where("project_id","=",$project_id)->find();
        //5表示软删除项目属性表
        $orm->project_status = 5;
        if($orm->loaded()){
            $orm->update();
        }
    }

    
    /**
     * 根据项目的2级行业获取点击量top10的项目id与项目名
     * @author 钟涛
     */
    public function getTopByIndustry($project_id){
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	if(intval($project_id) && $project_id){
    		$p_industry= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->find_all();
    		if(count($p_industry)){
    			$one_name='';//1级行业name
    			$two_name='';//2级行业name
    			$two_id=0;
    			$one_id=0;
    			foreach ($p_industry as $ve){
    				$industry= ORM::factory("industry",$ve->industry_id);
    				if($industry->parent_id>0){//2级行业
    					$two_name=$industry->industry_name;
    					$two_id=$industry->industry_id;
    					//@modified by 赵路生 2013-11-11
    					if($two_name == '其他'){
    						$two_name = '';
    					}
    				}else{//1级行业
    					$one_name=$industry->industry_name;
    					$one_id=$industry->industry_id;
    				}
    				if($two_name){//如果已经找到，终止循环
    					break;
    				}
    			}
    			if($two_name){//存在2级行业[从2级行业中找10个点击量最多的项目]
    				$memcahcename='projectinfoTops10byzhongtao'.$two_id;
    				if($memcache->get($memcahcename)){
    					return $memcache->get($memcahcename);
    				}else{
    					$total10 = ORM::factory('Project')->where('project_status','=',2)->where('project_tags','like','%'.$two_name.'%')
    					->order_by('project_pv_count','desc')
    					->limit(10)
    					->find_all();
    					$totallist=array();
    					foreach($total10 as $v){
    						$totallist[$v->project_id]=$v->project_brand_name;
    					}
    					$memcache->set($memcahcename,$totallist,$_cache_get_time);
    					return $totallist;
    				}
    			}elseif($one_name){//存在1级行业[从1级行业中找10个点击量最多的项目]
    				$memcahcename='projectinfoTops10byzhongtao'.$one_id;
    				if($memcache->get($memcahcename)){
    					return $memcache->get($memcahcename);
    				}else{
    					$total10 = ORM::factory('Project')->where('project_status','=',2)->where('project_tags','like','%'.$one_name.'%')
    					->order_by('project_pv_count','desc')
    					->limit(10)
    					->find_all();
    					$totallist=array();
    					foreach($total10 as $v){
    						$totallist[$v->project_id]=$v->project_brand_name;
    					}
    					$memcache->set($memcahcename,$totallist,$_cache_get_time);
    					return $totallist;
    				}
    			}else{	}
    		}
    	}
    	//默认返回前10项目
    	$memcahcename='projectinfoTops10byzhongtao';
    	if($memcache->get($memcahcename)){
    		return $memcache->get($memcahcename);
    	}else{
    		$total10 = ORM::factory('Project')->where('project_status','=',2)
    		->order_by('project_pv_count','desc')
    		->limit(10)
    		->find_all();
    		$totallist=array();
    		foreach($total10 as $v){
    			$totallist[$v->project_id]=$v->project_brand_name;
    		}
    		$memcache->set($memcahcename,$totallist,$_cache_get_time);
    		return $totallist;
    	}
    }

    /**
     * 根据项目的2级行业获取最新通过审核10个项目
     * @author 钟涛
     */
    public function getTopNew10ByIndustry($project_id){
    	$memcache = Cache::instance ( 'memcache' );
    	$_cache_get_time = 86400;//一天
    	if(intval($project_id) && $project_id){
    		$p_industry= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->find_all();
    		if(count($p_industry)){//当前项目有行业
    			$two_id=0;
    			$one_id=0;
    			foreach ($p_industry as $ve){
    				$industry= ORM::factory("industry",$ve->industry_id);
    				if($industry->parent_id>0){//2级行业
    					$two_id=$industry->industry_id;
    				}else{//1级行业
    					$one_id=$industry->industry_id;
    				}
    				if($two_id){//如果已经找到，终止循环
    					break;
    				}
    			}
    			if($two_id){
    				$thisid=$two_id;
    			}elseif($one_id){
    				$thisid=$one_id;
    			}else{
    				$thisid=0;//没行业
    			}
    			if($thisid){//有行业
	    		    $memcahcename='getTopNew10ByIndustry'.$thisid;
	    			if($memcache->get($memcahcename)){
	    				return $memcache->get($memcahcename);
	    			}else{
	    				$newtotal10 = ORM::factory('Projectindustry')
	    							 ->where('status','=',2)
	    							 ->where('industry_id','=',$thisid)
	    							 ->group_by('project_id')
	    							->order_by('project_id','desc')
				    				->limit(10)
				    				->find_all();
	    				$totallist=array();
	    				foreach($newtotal10 as $v){
	    					$proinfo=ORM::factory('Project',$v->project_id);
	    					if($proinfo->project_status==2){
	    						$totallist[$v->project_id]=$proinfo->project_brand_name;
	    					}
	    				}
	    				$memcache->set($memcahcename,$totallist,$_cache_get_time);
	    				return $totallist;
	    			}
    			}
    		}
    	}
    	//默认返回前10项目[当前项目没有行业]
    	$memcahcename='getTopNew10ByIndustry';
    	if($memcache->get($memcahcename)){
    		return $memcache->get($memcahcename);
    	}else{
    		$total10 = ORM::factory('Project')->where('project_status','=',2)
    		->order_by('project_passtime','desc')
    		->limit(10)
    		->find_all();
    		$totallist=array();
    		foreach($total10 as $v){
    			$totallist[$v->project_id]=$v->project_brand_name;
    		}
    		$memcache->set($memcahcename,$totallist,$_cache_get_time);
    		return $totallist;
    	}
    }
    
    /**
     * 根据id获得项目列表
     * @author 施磊
     */
    public function getCompanyProjectBasicList($com_id) {
        if(!intval($com_id)) return array();
        $obj = ORM::factory('Project')->where('com_id', '=', $com_id)->find_all();
        $return = array();
        foreach($obj as $val) {
            $tempArr = $val->as_array();
            $tempArr['project_name'] = '';
            if($tempArr['project_id']) {
                $industry_id = $this->getParentidByPorjectid($tempArr['project_id']);
                $tempArr['industry_name'] = $this->getIndustryName($industry_id);
            }
            $return[] = $tempArr;
        }
        return $return;
    }


    /**
     * 删除项目属性表
     * @author 钟涛
     */
    public function deleteHardProjectSearchCard($project_id,$user_id){
        $orm=ORM::factory("ProjectSearchCard")->where('user_id','=',$user_id)->where("project_id","=",$project_id)->find();
        if($orm->loaded()){
            $orm->delete();
        }
    }

    /**
     * 获得单个项目信息
     * @author 曹怀栋
     * 返回值：false或是项目信息
     */
    public function getOneProject($project_id,$user_id){
        $project=ORM::factory("Project",$project_id);
        if($project->project_id =="" || $project->user_company->com_user_id !=$user_id){
          return false;
        }else{
            return $project;
        }
    }

    /**
     * 判断项目是否存在
     * @author 龚湧
     * @param int $user_id
     * @param int $project_id
     * @return bool
     */
    public function isProjectExists($user_id,$project_id){
        $project = ORM::factory("Project",$project_id);
        if($project->project_id and $project->project_status !=4 and $project->project_status !=5){ //TODO 项目状态待定
            $com_user_id = $project->user_company->com_user_id;
            if($com_user_id == $user_id){
                return true;
            }
        }
        return false;
    }

    /**
     * 根据项目ID删除招商会(软删除)
     * @author 潘宗磊
     */
    public function deleteInvestByProjectId($projectId){
       $invests = ORM::factory("Projectinvest",$projectId)->where('project_id','=',$projectId)->find_all();
       foreach ($invests as $v){
           $v->investment_status=4;
           $v->update();
        }
      return true;
    }

    /**
     * 获取我的招商会列表
     * @author 潘宗磊
     */
    public function getInvestment($com_id,$project_id = 0){
        $model = ORM::factory('Projectinvest');
        if($project_id){
            $count = $model->where('project_id','=',$project_id)->where('investment_status','<',3)->order_by('investment_id','desc')->reset(false)->count_all();
        }
        else{
            $count = $model->where('com_id','=',$com_id)->where('investment_status','<',3)->order_by('investment_id','desc')->reset(false)->count_all();
        }

        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 6,
        ));
        $array = array();
        $invest_array = array();
        $invests = $model->limit($page->items_per_page)->offset($page->offset)->find_all()->as_array();
        foreach ($invests as $k=>$v){
            $province=ORM::factory('City',$v->investment_province)->cit_name;
            $city=ORM::factory('City',$v->investment_city)->cit_name;
            $projectModel = ORM::factory("Project",$v->project_id);
            if($projectModel->project_status==0){
                $invest_array[$k]['investment_status']=3;
            }else{
                $invest_array[$k]['investment_status']=$v->investment_status;
            }
            $bobao = ORM::factory("Bobao",$v->investment_id);
            $invest_array[$k]['investment_id']=$v->investment_id;
            $invest_array[$k]['investment_logo']=URL::imgurl($v->investment_logo);
            $invest_array[$k]['investment_name']=$v->investment_name;
            $invest_array[$k]['project_id']=$v->project_id;
            $invest_array[$k]['investment_start']=$v->investment_start;
            $invest_array[$k]['investment_end']=$v->investment_end;
            $invest_array[$k]['investment_address']=$province.$city.$v->investment_address;
            $invest_array[$k]['investment_details']=$v->investment_details;
            $invest_array[$k]['project_name']=$projectModel->project_brand_name;
            $invest_array[$k]['bobao_status']=$bobao->bobao_status;
            $invest_array[$k]['bobao_num']=$bobao->bobao_num;
            $invest_array[$k]['bobao_sign']=$bobao->bobao_sign;
            $invest_array[$k]['have_watch_investment_num']  = $this->getInvestmentHaveWatch($v->investment_id);
            $apply = ORM::factory('Applyinvest')->where('invest_id','=',$v->investment_id)->count_all();
            //企业用户要看 真实的数据 @花文刚
            $invest_array[$k]['investment_apply'] = $apply;

            $bobao_img = explode('|',$bobao->bobao_img);
            if($bobao_img[0]){
                $invest_array[$k]['bobao_have_img'] = 1;
            }
            else{
                $invest_array[$k]['bobao_have_img'] = 0;
            }

            $bakup = ORM::factory("Investbakup",$v->investment_id);
            if($bakup->invest_id){
                $bak_content = unserialize($bakup->content);

                $invest_array[$k]['investment_logo']=URL::imgurl($bak_content['investment_logo']);
                $invest_array[$k]['investment_name']=$bak_content['investment_name'];
                $invest_array[$k]['investment_start']=$bak_content['investment_start'];
                $invest_array[$k]['investment_end']=$bak_content['investment_end'];
                $province=ORM::factory('City',$bak_content['investment_province'])->cit_name;
                $city=ORM::factory('City',$bak_content['investment_city'])->cit_name;
                $invest_array[$k]['investment_address']=$province.$city.$bak_content['investment_address'];

                if($bakup->edit_status == '0'){
                    $invest_array[$k]['wait_edit_check']=0;
                }

                if($bakup->edit_status == '2'){
                    $invest_array[$k]['wait_edit_check']=2;
                }
            }
            else{
                $invest_array[$k]['wait_edit_check']="";
            }


        }
        $array['list'] = $invest_array;
        $array['page'] = $page;
        $array['count'] = $count;
        return $array;
    }


    /**
     * 获取招商会总的浏览次数
     * @author  嵇烨
     * return int
     */
    public function  getInvestmentHaveWatch($investment_id,$time = null){
        $int_num  = 0;
        if($investment_id){
            if($time){
                $int_num  = ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($investment_id))->where('add_time','>=',intval($time))->where("add_time",'<=',time())->reset( false )->count_all();
            }else{
                #浏览的总数
                $int_num  = ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($investment_id))->where("operate_type",'=',intval(2))->reset( false )->count_all();
            }

        }
        return  $int_num;
    }
    /**
     * @sso
     *获取招商会的个人查看的次数
     *@author 嵇烨
     *return int
     */
    public function getInvestmentHaveWatchByPerson($Investment_id,$time = null){
        $int_num = 0;
        #进行分组
        if($time){
            $arr_list =  ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->where('user_id','>',intval(0))->where("add_time",">=",intval($time))->where("add_time","<=",time())->group_by('user_id')->order_by('add_time','DESC')->find_all();
        }else{
            $arr_list =  ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->where('user_id','>',intval(0))->group_by('user_id')->order_by('add_time','DESC')->find_all();
        }
        $arr_user_id = array();
        if(count($arr_list) > 0){
            #判断是不是个人用户
            foreach ($arr_list as $key=>$val){
                $arr_user_data  = Service_Sso_Client::instance()->getUserInfoById( $this->userid() )->as_array();
                //$arr_user_data = ORM::factory('User',$val->user_id)->as_array();
                if($arr_user_data['user_id'] > 0 && $arr_user_data['user_type'] == intval(2)){
                    $arr_user_id[] = $arr_user_data['user_id'];
                }
            }
            #获取个人参看的数量
            if($arr_user_id){
                foreach ($arr_user_id as $key=>$val){
                    $int_num += ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->where('user_id','=',intval($val))->reset( false )->count_all();
                }
            }
        }
        return $int_num;
    }

    /**
     * 获取当前招商会报名列表
     * @author 潘宗磊
     */
    public function getApplyinvest($invest_id){
        $model = ORM::factory('Applyinvest');
        $count = $model->where('invest_id','=',$invest_id)->order_by('apply_id','desc')->reset(false)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 10,
        ));
        $array=array();
        $bobao = ORM::factory("Bobao",$invest_id);
        $array['list'] = $model->limit($page->items_per_page)->offset($page->offset)->find_all()->as_array();
        $array['page']= $page;
        $array['bobao']= $bobao;
        $array['count']= $count;
        return $array;
    }

    /**
     * 导出当前招商会报名列表
     * @author 潘宗磊
     */
    function downloadApplyXls($invest_id) {
        $xls[] = "<html><meta http-equiv=content-type content=\"text/html; charset=UTF-8\"><body><table border='1'>";
        $xls[] = "<tr><td>姓名</td><td>性别</td><td>报名时间</td><td>联系方式</td><td>是否需要公司安排统一安排住宿</td></tr>";
        $apply = ORM::factory('Applyinvest')->where('invest_id','=',$invest_id)->find_all();
        $invest = ORM::factory('Projectinvest',$invest_id)->investment_name;
        foreach ($apply as $v){
            if($v->apply_sex==1){
                $sex='男';
            }elseif($v->apply_sex==2){
                $sex='女';
            }
            if($v->is_hotel==1){
                $is_hotel="是";
            }elseif($v->is_hotel==2){
                $is_hotel="否";
            }
            //如果没有购买服务，就会隐藏中间几位
            if($v->apply_status==2){
                $mobile = $v->apply_mobile;
            }else{
                $mobile = substr($v->apply_mobile,0,4)."****".substr($v->apply_mobile,-3);
            }
            $xls[] = "<tr><td>".$v->apply_name."</td><td>".$sex."</td><td>".date('Y-m-d',$v->apply_addtime)."</td><td>".$mobile."</td><td>".$is_hotel."</td></tr>";
        }
        $xls[] = '</table></body></html>';
        $xls = join("\r\n", $xls);
        header('Content-Disposition: attachment; filename="'.iconv('utf-8', 'gb2312', $invest).'.xls"');
        die(mb_convert_encoding($xls,'UTF-8','UTF-8'));
    }

    /**
     * 新增项目基本信息
     * @author 施磊
     */
    public function addProjectBasic($param) {
        if(!$param) return FALSE;
        $ormModel = ORM::factory('Project');
        $ormModel->values($param)->create();
        return $ormModel->project_id;
    }
     /**
     * 新增项目海报
     * @author 施磊
     */
    public function addProjectposterContent($param) {
        if(!$param) return FALSE;
        $ormModel = ORM::factory('ProjectposterContent');
        $ormModel->values($param)->create();
        return $ormModel->project_id;
    }

    /**
     * @sso
     * 判断是否完善项目信息
     * @author 施磊
     */
    public function checkProInfo($project_id) {
        $ormModel = ORM::factory('Project', $project_id)->as_array();
        if(!$ormModel || !arr::get($ormModel, 'com_id', 0)|| !arr::get($ormModel, 'project_brand_name', 0)) return FALSE;
        $com_id = $ormModel['com_id'];
        $serviceUser = new Service_User_Company_User();
        $comUser = $serviceUser->getCompanyInfoByComId($com_id);
        if(!$comUser['com_user_id']) return false;
        $user_id = $comUser['com_user_id'];
        $userInfo = $serviceUser->getUserInfoById($user_id,true);
        if(!$userInfo->valid_mobile || arr::get($comUser, 'organization_credit_status', 3) == 3  || arr::get($comUser, 'com_business_licence_status', 3) == 3) return FALSE;
        return TRUE;
    }
    /**
     * 判断项目信息
     * @author 施磊
     */
    public function checkComInfo($com_id, $user_id = '') {
        if(!$user_id) {
            $serviceUser = new Service_User_Company_User();
            $comUser = $serviceUser->getCompanyInfoByComId($com_id);
            if(!$comUser['com_user_id']) return false;
            $user_id = $comUser['com_user_id'];
            $userInfo = $serviceUser->getUserInfoById($user_id);
            $return['comStatus'] = ($userInfo->valid_mobile) ? TRUE : FALSE;
            $return['authStatus'] = ($comUser['com_id'] && ((arr::get($comUser, 'organization_credit_status', 3) != 3 && arr::get($comUser, 'com_business_licence_status', 3) != 3) || arr::get($comUser, 'com_auth_status', 0))) ? TRUE : FALSE;
            return $return;
        }else {
            $serviceUser = new Service_User_Company_User();
            $comUser = $serviceUser->getCompanyInfoByComId($com_id);
            $userInfo = $serviceUser->getUserInfoById($user_id);
            $return['comStatus'] = ($userInfo->valid_mobile) ? TRUE : FALSE;


            $return['authStatus'] = ($comUser['com_id'] && ((arr::get($comUser, 'organization_credit_status') != 3  && arr::get($comUser, 'com_business_licence_status') != 3) || arr::get($comUser, 'com_auth_status', 0))) ? TRUE : FALSE;
            return $return;
        }
    }
    /**
     判断是否完善项目信息
     * @author 施磊
     */
    public function checkProAllInfo($project_id) {
        $proImgStatus = $this->getProjectImg($project_id) ? TRUE : FALSE;
        $projectcertsStatus = $this->getProjectcertsCountByProId($project_id) ? TRUE : FALSE;
        $status = ORM::factory('Projectinvest')->where("project_id", "=", $project_id)->where('investment_end','>=',time())->where('investment_status','in',array(3,1))->find();
        $proInvestStatus = $status->project_id ? TRUE : FALSE;
        $return = array('proImgStatus' => $proImgStatus, 'projectcertsStatus' => $projectcertsStatus, 'proInvestStatus' => $proInvestStatus);
        return $return;
    }
    /**
     * 取得项目信息
     * @author 施磊
     */
    public function getProjectInfo($project_id) {
        return $ormModel = ORM::factory('Project', $project_id)->as_array();
    }
    /**
     * 检查用户权限
     * @author 施磊
     * @param int $project_id 项目id
     * @param int $com_id 公司id
     * return  bool
     */
    public function checkProjectPermission($project_id, $com_id) {
        $project_id = intval($project_id);
        $com_id = intval($com_id);
        if(!$project_id || !$com_id) return FALSE;
        $ormModel = ORM::factory('Project')->where('project_id', '=', $project_id)->where('com_id', '=', $com_id)->find()->as_array();
        if(isset($ormModel['project_id']) && $ormModel['project_id']) return TRUE;
        return False;
    }

    /**
     * 检查招商会的权限
     * @author 潘宗磊
     */
    public function checkInvestPermission($invest_id, $com_id) {
        $invest_id = intval($invest_id);
        $com_id = intval($com_id);
        if(!$invest_id || !$com_id) return FALSE;
        $ormModel = ORM::factory('Projectinvest')->where('investment_id', '=', $invest_id)->where('com_id', '=', $com_id)->find()->as_array();
        if(isset($ormModel['investment_id']) && $ormModel['investment_id']) return TRUE;
        return False;
    }
    /**
     * 项目详情
     * @author 嵇烨
     */
    public function  getProjectData($project_id){
        if($project_id){
            $projects = ORM::factory('Project',$project_id);
            if($projects->loaded()){
                return $projects;
            }
            return false;
        }
        return false;
    }
    /**
     * 企业上传海报
     * @author 嵇烨
     */
    public function uploadProjectPoster($project_id,$img){
        $postercontent = ORM::factory("ProjectposterContent",$project_id);
        if($postercontent->project_id>0){
            //更新前先删除旧的海报文件 @花文刚
            common::deletePic(URL::imgurl($postercontent->upload_img));

            $postercontent->upload_img = common::getImgUrl($img);
            $postercontent->content = "";
            $postercontent->update();
        }else{
            $postercontent->upload_img = common::getImgUrl($img);
            $postercontent->project_id = $project_id;
            $postercontent->create();
        }
        $poster = ORM::factory("Projectposter",$project_id);
        if($poster->project_id>0){
            $poster->last_edittime = time();
            $poster->add_time = time();
            $poster->poster_status = 1;
            $poster->poster_cut = 0;
            $poster->poster_type = 2;
            $poster->template_id = 0;
            $poster->update();
        }else{
            $poster->project_id = $project_id;
            $poster->poster_type = 2;
            $poster->template_id = 0;
            $poster->add_time = time();
            $poster->last_edittime = time();
            $poster->poster_temp_status = 2;
            $poster->create();
        }
        return true;
    }

    /**
     * 获取海报信息
     * @author 潘宗磊
     */
    public function getProjectPoster($project_id){
        $poster = ORM::factory("Projectposter",$project_id);
        return $poster;
    }

    /**
     * 添加或修改招商会播报
     * @author 潘宗磊
     */
    public function addBobao($form){
        $valid = new Validation($form);
        $valid->rule("invest_id", "not_empty")->rule("invest_id", "digit");
        $valid->rule("bobao_num", "not_empty")->rule("bobao_num", "digit");
        $valid->rule("bobao_sign", "not_empty")->rule("bobao_sign", "digit");

        if(!$valid->check()){
            return false;
        }
        $bobao = ORM::factory("Bobao",arr::get($form, 'invest_id'));
        if($bobao->invest_id>0){
            $bobao->bobao_num = arr::get($form, 'bobao_num');
            $bobao->bobao_sign = arr::get($form, 'bobao_sign');
            $bobao->bobao_status = 1;
            $bobao->update();
        }else{
            $bobao->invest_id = arr::get($form, 'invest_id');
            $bobao->bobao_num = arr::get($form, 'bobao_num');
            $bobao->bobao_sign = arr::get($form, 'bobao_sign');
            $bobao->bobao_status = 1;
            $bobao->create();
        }
        return true;
    }

    /**
     * 添加或修改招商会播报
     * @author 潘宗磊
     */
    public function addBobaoImg($form){
        $valid = new Validation($form);
        $valid->rule("invest_id", "not_empty")->rule("invest_id", "digit");
        $valid->rule("bobao_img", "not_empty");

        if(!$valid->check()){
            return false;
        }
        $bobao = ORM::factory("Bobao",arr::get($form, 'invest_id'));
        if($bobao->invest_id>0){
            $bobao->bobao_img = arr::get($form, 'bobao_img');
            $bobao->update();
        }
        return true;
    }

    /**
     * 获取招商会播报
     * @author 潘宗磊
     */
    public function getBobao($invest_id){
        $bobao = ORM::factory("Bobao",$invest_id);
        $img = array();
        $info = array();
        if(!empty($bobao->bobao_img)){
            $bobao_img = explode('|',$bobao->bobao_img);
            foreach ($bobao_img as $v){
                if(!empty($v)){
                    $img[] = URL::imgurl($v);
                }
            }
        }
        $info['img'] = $img;
        $info['bobao_num'] = $bobao->bobao_num;
        $info['bobao_sign'] = $bobao->bobao_sign;
        $info['bobao_status'] = $bobao->bobao_status;
        return $info;
    }

    /**
     * 判断招商会播报条件是否符合
     * @author 潘宗磊
     */
    public function isBobao($invest_id){
        $invest = ORM::factory("Projectinvest",$invest_id);
        if(($invest->investment_status==1)&&($invest->investment_start-time()<0)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 修改项目信息
     * @author 施磊
     */
    public function updateProjectByParam($project_id, $param) {
        $project_id = intval($project_id);
        if(!$project_id) return false;
        $project = ORM::factory ( "Project", $project_id);
        if($param)  {
            foreach($param as $key => $val) {
                $project->$key = $val;
            }
            $project->update();
        }
        $this->updateProjectTime($project_id);
    }
    /**
     * 修改项目修改时间
     * @author 嵇烨
     */
    public function updateProjectTime($int_project_id){
        $bool = false;
        if($int_project_id){
            $project = ORM::factory ( "Project", intval ( $int_project_id ) );
            if ($project->project_id > 0) {
                $project->project_updatetime = time ();
                $project->update ();
                $bool = true;
            }
        }
        return $bool;
    }

    /**
     * 修改project_temp_status状态
     * @author 郁政
     */
    public function updateProjectTempStatus($project_id){
        $bool = false;
        if($project_id){
            $project = ORM::factory ( "Project", intval ( $project_id ) );
            if ($project->project_id > 0) {
                $project->project_temp_status = 1;
                $project->update ();
                $bool = true;
            }
        }
        return $bool;
    }

    /**
     * 修改项目修改时间
     * @author 嵇烨
     */
    public function updateProjectStatus($int_project_id,$int_status){
        $bool = false;
        if($int_project_id){
            $project = ORM::factory ( "Project", intval ( $int_project_id ) );
            if ($project->project_id > 0) {
                $project->project_status = intval($int_status);
                $project->update ();
                $bool = true;
            }
        }
        return $bool;
    }

    /**
     * 验证项目名称
     * @author 嵇烨
     */
    public function changeProjectName($user_id,$project_name){
        $bool = 0;
        if($user_id && $project_name){
            #通过会员的id获取企业的id
            $company_id = $this->getCompanyId($user_id);
            #通过企业的id去找发布的所有的项目名称
            $model = ORM::factory("Project")->where("com_id","=", $company_id)->where("project_status",">=", intval(1))->where("project_status","<", intval(3))->find_all();
			if(count($model) > 0){
				$arr_data = array();
				foreach ($model as $key=>$val){
					$arr_data[] = $val->project_brand_name;
				}
				if(in_array($project_name,$arr_data)){
					$bool = 2;
				}else{
					$int_model = ORM::factory("Project")->where("project_brand_name","=", trim($project_name))->where("project_status",">=", intval(1))->where("project_status","<", intval(3))->count_all();
					if($int_model > 0){
						$bool = 3;
					}else{
						$bool = 1;
					}
				}
			}else{
				$bool = 1;
			}
        }
        return $bool;
    }
    
    /**
     * 获取地区名称
     * @author 嵇烨
     * return array
     */
    public function getAreaName($str_name){
        $arr_data = array(array('cit_id'=>0,'cit_name'=>"不限","pro_id"=>0,"area"=>""));
        if($str_name){
            #找去以及行业名称
            $model = ORM::factory("City")->where("cit_name",'=',$str_name)->where("pro_id",'=',intval(0))->find()->as_array();
            if($model['cit_id'] > 0 && $model['cit_id'] !=""){
                #找去属于这个地区的二级行业
                $model2 =ORM::factory("City")->where("pro_id",'=',intval($model['cit_id']))->find_all();
            }
            if(!empty($model2) && count($model2) >0){
                foreach ($model2 as $key=>$val){
                    $arr_data[] = $val->as_array();
                }
            }
        }
        return $arr_data;
    }
    
    /**
     * 添加项目宣传图片
     * @author 嵇烨
     */
    public function insertImageXuanChuan($str_data,$int_project_id){
        $bool = false;
        if(!empty($str_data) && $int_project_id){
            $model = ORM::factory('Projectcerts');
            if(isset($str_data['big']) && isset($str_data['small'])){
                $arr_data = $model->where("project_id","=",intval($int_project_id))->and_where_open()->where("project_type","=",intval(4))->or_where("project_type","=",intval(5))->and_where_close()->find_all()->as_array();

            }elseif(isset($str_data['big'])){
                $arr_data = $model->where("project_id","=",intval($int_project_id))->where("project_type","=",intval(4))->find_all()->as_array();
            }elseif (isset($str_data['small'])){
                $arr_data = $model->where("project_id","=",intval($int_project_id))->where("project_type","=",intval(5))->find_all()->as_array();
            }
            //var_dump($arr_data);exit;
            #添加
            if(empty($arr_data['project_certs_id']) && empty($arr_data)){
                    foreach ($str_data as $key=>$val){
                        $models = ORM::factory('Projectcerts');
                        $models->project_id = intval($int_project_id);
                        $models ->project_type = $val['type'];
                        $models->project_img = common::getImgUrl($val['image_url']);
                        $models->project_addtime = time();
                        $models->create();
                        $models->clear();
                    }
                    $bool = true;
            }else{
                foreach ($arr_data as $key=>$val){
                    $arr_datas [] = $val->as_array();
                }
                foreach ($arr_datas as $key=>$val){
                    #先删除
                    $model = ORM::factory('Projectcerts')->where("project_certs_id","=",$val['project_certs_id'])->find();
                    $model->delete();
                }
                #再次插入
                    foreach ($str_data as $key=>$val){
                        $models = ORM::factory('Projectcerts');
                        $models->project_id = intval($int_project_id);
                        $models ->project_type = $val['type'];
                        $models->project_img = common::getImgUrl($val['image_url']);
                        $models->project_addtime = time();
                        $models->create();
                        $models->clear();
                    }
                $bool = true;
            }
        }
        return $bool;
    }
    /**
     * 获取项目的宣传照片
     * @author 嵇烨
     */
    public function  getXuanChuanPic($int_project_id){
        $str_date = array();
        if($int_project_id){
            $model = ORM::factory('Projectcerts')->where("project_id","=",intval($int_project_id))->and_where_open()->where("project_type","=",intval(4))->or_where("project_type","=",intval(5))->and_where_close()->find_all();
            if(count($model) > 0){
                foreach ($model as $key=>$val){
                    $str_date [] = $val->as_array();
                }
            }
        }
        return  $str_date;
    }
    /**
     * 获取地区名称
     * @author 嵇烨
     * return string
     */
    public function getAreaNameByAreaId($int_id){
        $str_data = "";
        if($int_id){
            $mdoel = ORM::factory("City",intval($int_id));
            if($mdoel->cit_id> 0){
                $str_data = $mdoel->cit_name;
            }
        }elseif($int_id == 0){
        	$str_data = '国外';
        }
        return $str_data;

    }

    /**
     * 根据地区名称获取地区id
     * @author 郁政
     */
    public function getAreaIdByAreaName($cit_name){
        $cit_id = 0;
        if($cit_name){
            $model = ORM::factory("City")->where('cit_name','=',$cit_name)->find();
            if($model->cit_id != ''){
                $cit_id = $model->cit_id;
            }
        }
        return $cit_id;
    }

    /**
     * 获取海报图片url
     * @author 郁政
     */
    public function getPosterContent($project_id){
        $project_id = intval($project_id);
        $url = "";
        $ormModel = ORM::factory('ProjectposterContent',$project_id);
        if($ormModel->project_id != ''){
            $url = $ormModel->upload_img;
        }
        return $url;
    }

    /**
     * 根据行业id返回行业名称
     * @author 郁政
     */
    public function getIndustryNameByIndustryId($industry_id){
        $industry_id = intval($industry_id);
        $industry_name = "";
        $ormModel = ORM::factory('Industry',$industry_id);
        if($ormModel->industry_id != ''){
            $industry_name = $ormModel->industry_name;
        }
        return $industry_name;
    }
    /**
     * 判断项目是否被收藏
     * @author 嵇烨
     * return  array
     */
    public function  projectIsWatch($int_project_id){
        $arr_data = array();
        if($int_project_id){
            $model = ORM::factory("Projectwatch")->where("watch_project_id","=",intval($int_project_id))->where('watch_status','=',intval(1))->find_all();
            if(count($model) > 0){
                foreach ($model as $key=>$val){
                    $arr_data [] = $val->as_array();
                }
            }
        }
        return $arr_data;
    }
    /**
     * 通过投资考察会获取信息
     * @author 嵇烨
     * return array
     */
    public function  getInvestmentById($investment_id){
        $arr_return_data = array();
        if($investment_id){
             $arr_return_data = ORM::factory('Projectinvest',intval($investment_id))->as_array();
        }
        return $arr_return_data;
    }

    /**
     * 查找投资考察会浏览日志
     * @author 花文刚
     */
    public function searchViewLog($form){
        $where=array();
        if(!empty($form['invest_id'])){
            $where[] = array("operate_id", "=",$form['invest_id']);
        }
        if(!empty($form['start'])){
            $where[] = array("add_time", ">=",strtotime($form['start']));
        }
        if(!empty($form['end'])){
            $where[] = array("add_time", "<=",strtotime($form['end'])+24*60*60);
        }


        if(isset($form['user_type'])){
            if($form['user_type']==1){
                $where[]=array('user_id', '!=', '0');
            }
            if($form['user_type']==2){
                $where[]=array('user_id', '=', '0');
            }
        }
        return $this->showInvestViewLog($where);
    }

    /**
     * 获取招商会浏览日志信息
     * @author 花文刚
     */
    public function showInvestViewLog($search_row=array()){
        $model = ORM::factory('UserViewProjectLog');
        if(!empty($search_row)){//根据查询条件查询

            foreach($search_row as $value){
                if(!empty($value[0])){
                    $model->where($value[0], $value[1], $value[2]);
                }
            }
        }

        //分页 此处需要设置reset(FALSE) 否则没有数据时默认会显示一页数据
        $count = $model->order_by('add_time','DESC')->reset(false)->count_all();
        $page = Pagination::factory(array(
            'total_items'    => $count,
            'items_per_page' => 10,
        ));
        $array=array();
        $array['list'] = $model->limit($page->items_per_page)->offset($page->offset)->find_all();

        $array['page']= $page;
        return $array;
    }

    /**
     * 获取招商会浏览日志信息
     * @author 花文刚
     */
    public function getViewLogResaultList($result){
        $resault_array = array();
        $reg ='~(\d+)\.(\d+)\.(\d+)\.(\d+)~';
        if(isset($result)):
            foreach ($result as $k=>$v){
                $resault_array[$k]['add_time'] = date('Y.m.d H:i:s',$v->add_time);
                $resault_array[$k]['user_id'] = $v->user_id;
                if($v->user_id == 0){
                    $resault_array[$k]['user_name'] = "匿名";
                }
                else{
                    //@sso 花文刚 2013-12-3
                    $client = Service_Sso_Client::instance();
                    $user = $client->getUserInfoById($v->user_id);
                    if($user->user_type == "1"){
                        $resault_array[$k]['user_name'] = $user->user_name;
                    }
                    else{
                        $person = ORM::factory('Personinfo')->where('per_user_id','=',$v->user_id)->find();
                        if($person->per_id){
                            $resault_array[$k]['user_name'] = $person->per_realname;
                        }
                        else{
                            $resault_array[$k]['user_name'] =  substr_replace($user->email,"***",1,3);
                        }
                    }

                }

                $resault_array[$k]['ip'] = preg_replace($reg,"$1.$2.*.*",long2ip($v->ip)) ;
                $resault_array[$k]['address'] = common::convertip(long2ip($v->ip));
            }
        endif;
        return $resault_array;
    }


    /**
     * 获取浏览投资考察会用户信息
     * @author嵇烨
     * return array
     */
    public  function  getLiuLanInvestmentList($Investment_id,$int_time_data = null){
        $arr_return_data = array();
        $arr_new_data = array();
        #获取投资考察总数
        if($int_time_data == null){
            $Investment_liulan_num  = ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->group_by('user_id')->order_by('add_time','DESC')->count_all();
        }else{
            $Investment_liulan_num  = ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where('add_time',">=",intval($int_time_data))->where("add_time",'<=',time())->where("operate_type",'=',intval(2))->group_by('user_id')->order_by('add_time','DESC')->count_all();
        }
        #分页开始
        $page = Pagination::factory(array(
                'total_items'    => $Investment_liulan_num,
                'items_per_page' => 7,
        ));
          $arr_data = array();
          if($int_time_data == null){
              $arr_list =  ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->limit($page->items_per_page)->offset($page->offset)->order_by('add_time','DESC')->group_by('user_id')->find_all();
          }else{
              $arr_list =  ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where('add_time',">=",intval($int_time_data))->where("add_time",'<=',time())->where("operate_type",'=',intval(2))->limit($page->items_per_page)->offset($page->offset)->order_by('add_time','DESC')->group_by('user_id')->find_all();
          }
        #数据过度
        foreach ($arr_list as $key=>$val){
            $arr_new_data[] = $val->as_array();
        }
        $data = array();
        #拿取信息
        if(!empty($arr_new_data)){
            $service = new Service_User_Person_User();
            foreach ($arr_new_data as $key=>$val){
                #判断用户有没有的登录
                if($val['user_id'] > intval(0)){
                    #判断用户是不是个人用户   还是企业用户
                    $arr_user_data = ORM::factory('User',$val['user_id'])->as_array();
                    if($arr_user_data['user_id'] > 0 && $arr_user_data['user_type'] == intval(2)){
                        #获取用户最后一个时间查看
                        $obj =  ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->where('user_id','=',intval($val['user_id']))->order_by("add_time","DESC")->find_all();
                        //var_dump($obj);exit;
                        if(count($obj) > 0){
                            foreach ($obj as $k=>$v){
                                $arr_new_data[$key]['add_new_time'] = $v->add_time;
                                break;
                            }
                        }
                        //echo $arr_new_data[$key]['add_new_time'];exit;
                        #收集用户信息
                        $arr_user_person_data = DB::select()->from("user_person")->where("per_user_id","=", $arr_user_data['user_id'])->execute()->as_array();
                        $user_name = isset($arr_user_person_data[0]['per_realname']) ? $arr_user_person_data[0]['per_realname'] : $arr_user_data['user_name'];
                        #$user_id = $arr_user_person_data[0]['per_user_id'];
                        #获取地区
                        $adress = common::convertip(long2ip($val['ip']));
                        $arr_new_data[$key]['user_name'] = $user_name ? $user_name : $arr_user_data['email'];
                        #$arr_new_data[$key]['user_id'] = $user_id;
                        $arr_new_data[$key]['address'] = $adress;
                        #浏览的次数
                        $arr_new_data[$key]['wacth_num'] = ORM::factory("UserViewProjectLog")->where('operate_id','=',intval($Investment_id))->where("operate_type",'=',intval(2))->where('user_id','=',intval($val['user_id']))->reset( false )->count_all();
                        #个人投资金额
                        $arr_new_data[$key]['per_amount'] = isset($arr_user_person_data[0]['per_amount']) ? $arr_user_person_data[0]['per_amount'] : 0;
                        #获取个人的投资行业
                        $arr_new_data[$key]['per_industry_string'] = $service->getPersonalIndustryString($arr_user_data['user_id']);
                    }else{
                        unset($arr_new_data[$key]);
                    }
                }else {
                    unset($arr_new_data[$key]);
                }
            }
        }
        if(!empty($arr_new_data)){
            $arr_return_data['list'] = common::multi_array_sort($arr_new_data,'wacth_num',SORT_DESC);
            $arr_return_data['page'] = $page;
        }
        //echo "<pre>"; print_R($arr_return_data);exit;
        return $arr_return_data;
    }

    /**
     * 取得项目的状态
     * @author 嵇烨
     */
    public function get_project_status($int_project_id){
        $int_num = 0;
        if($int_project_id){
            $int_num = ORM::factory('Project',intval($int_project_id))->project_status;
        }
        return $int_num;
    }
    /**
     * 获取投资人群
     * @author 嵇烨
     * return array()
     */
    public function  get_project_investment_groups($int_project_investment_groups_id){
        $arr_return = array();
        if($int_project_investment_groups_id){
            $tag = ORM::factory('Usertype',intval($int_project_investment_groups_id));
            $arr_return['tag_id'] = $tag->tag_id;
            $arr_return['tag_name'] = $tag->tag_name;
        }
        return $arr_return;

    }
    /**
     * 获取缓存中的图片 和数据库中的图片并合并
     * @author嵇烨
     * @param return array()
     */
    public function get_Cache_And_Database_Images($int_project_id , $arr_data,$image_type){
        $arr_return_data = array();
        $model = ORM::factory('Projectcerts');
        $arr_image = array();
        $arr_data_images = array();
        $arr_arr_data = array();
        if($int_project_id && $arr_data && $image_type){
            if($image_type == 1){
                foreach ($arr_data as $key=>$val){
                    $arr_data_images[$key]['project_img'] = $val;
                }
            }else{
                foreach ($arr_data as $key=>$val){
                    $arr_data_images[] = (array)$val;
                }
            }
            #获取图片
            $obj_project_image_list = $model->where("project_id","=",intval($int_project_id))->where("project_type","=",intval($image_type))->limit(5)->find_all();
            if(count($obj_project_image_list) > 0){
                foreach ($obj_project_image_list as $key=>$val){
                    $arr_image [$key]['project_img'] = URL::imgurl($val->project_img);
                    if($image_type == 2){
                        $arr_image [$key]['project_imgname'] =  $val->project_imgname;
                    }
                }
                #合并数组
                foreach($arr_image as $key=>$val){
                    array_push($arr_data_images,$val);
                }
            }
            $arr_return_data['list'] = $arr_data_images;
            $arr_return_data['count'] = count($arr_data_images);
        }

        return $arr_return_data;
    }
    /**
     * 处理数组
     * @author 嵇烨
     * @param return array();
     */
    public  function Do_arr_list($arr_data){
        $arr_return = array();
        if($arr_data){
            foreach ($arr_data as $key=>$val){
                $arr_return [] = $val->as_array();
            }
        }
        return $arr_return;
    }
    /**
     * 招商地区
     * @author 嵇烨
     * return array()
     */
    public function Do_arr_area_list($arr_data){
        $arr_return = array();
        if(is_array($arr_data) && count($arr_data) > 0){
            $arr = array();
            foreach ($arr_data as $key=>$val){
                $arr[$val]['pro_id'] = $val;
                $arr[$val]['pro_name'] = $this->getAreaNameByAreaId($val);
                $arr_return = $arr;
            }
        }
        return $arr_return;

    }
    /**
     * 替换项目的数据
     * @author 嵇烨
     * @param return array
     */
    public function do_replace_project_list($arr_data){
        $arr_return_data = array();
        $redis = Cache::instance("redis");
        $arr_project_basic_list = array();
        $arr_project_tuiguang_list = array();
        $arr_project_images = array();
        $arr_project_zizhi_images = array();
        if($arr_data){
            if(count($arr_data) > 0){
                foreach ($arr_data as $key=>$val){
                    #查找项目信息
                    $json_project_basic_list = $redis->get($val['project_id']."_project_basic_list");
                    #查找产品图片
                    $json_project_images = $redis->get($val['project_id']."_project_images");
                    #查找资质图片
                    $json_project_zizhi_images = $redis->get($val['project_id']."_project_zizhi_images");
                    #查找更多的项目信息
                    $json_project_more_jiben_list = $redis->get($val['project_id']."_project_more_jiben_list");
                    #查找推广项目信息
                    $json_project_tuiguang_list = $redis->get($val['project_id']."_project_tuiguang_list");
                    #海报
                    $json_project_haihao = $redis->get($val['project_id']."_project_haibao");

                    if($json_project_basic_list){
                        $arr_project_basic_list = (array)json_decode($json_project_basic_list);
                        #项目名称
                        $val['project_brand_name'] = arr::get($arr_project_basic_list, "project_brand_name","");
                        #项目logo
                        $val['project_logo'] = arr::get($arr_project_basic_list, "project_logo","");
                        #品牌发源地
                        $city_child_name = $this->getAreaNameByAreaId(arr::get($arr_project_basic_list, "per_area_id"," "));
                        $val['project_brand_birthplace'] =$city_child_name;
                        #招商行业
                        $name = $this->getIndustryNameByIndustryId($arr_project_basic_list['industry_id1']);
                        $name .= ",".$this->getIndustryNameByIndustryId($arr_project_basic_list['industry_id2']);
                        $val['project_industry_id'] = isset($name) ? $name  : "";

                        //var_dump($arr_project_basic_list); exit;
                        #招商地区
                        $city_name = "";
                        foreach ($arr_project_basic_list['project_city'] as $k=>$v){
                            $city_name .= $this->getAreaNameByAreaId($v)." ";
                        }
                        $val['project_merchants_region'] = $city_name;
                        $val['project_amount_type'] = arr::get($arr_project_basic_list, "project_amount_type");
                        //echo "<pre>";var_dump($val);
                    }
                    #推广信息
                    if($json_project_tuiguang_list){
                        $arr_project_tuiguang_list = (array)json_decode($json_project_tuiguang_list);
                        //echo "<pre>";var_dump($arr_project_tuiguang_list);
                        $val['project_advert'] = arr::get($arr_project_tuiguang_list, "project_advert");
                        #推广大图
                        if(isset($arr_project_tuiguang_list['project_xuanchuan_da_logo']) && $arr_project_tuiguang_list['project_xuanchuan_da_logo']){
                            $val['project_advert_big'] = array(array(arr::get($arr_project_tuiguang_list, "project_xuanchuan_da_logo")));
                        }
                        #推广小图
                        if(isset($arr_project_tuiguang_list['project_xuanchuan_xiao_logo']) && $arr_project_tuiguang_list['project_xuanchuan_xiao_logo']){
                            $val['project_advert_small'] = array(array(arr::get($arr_project_tuiguang_list, "project_xuanchuan_da_logo")));
                        }
                    }
                    #项目图片
                    if($json_project_images){
                        $arr_project_images = (array)json_decode($json_project_images);
                        $image = $this->get_Cache_And_Database_Images($val['project_id'],$arr_project_images,1);
                        $val['project_img'] = $image['list'];
                    }
                    #项目资质图片
                    if($json_project_zizhi_images){
                        $arr_project_zizhi_images = (array)json_decode($json_project_zizhi_images);
                        $image_zizhi = $this->get_Cache_And_Database_Images($val['project_id'],$arr_project_zizhi_images,2);
                        $val['project_auth'] = $image_zizhi["list"];
                    }
                    if($json_project_basic_list || $json_project_images || $json_project_zizhi_images || $json_project_more_jiben_list || $json_project_tuiguang_list || $json_project_haihao){
                        $val['project_status'] = 1;
                    }
                    $arr_return_data [] = $val;
                }
            }
        }
        return $arr_return_data;
    }
    /**
     * 删除缓存状态   私用
     * @author 嵇烨
     * @param return  true or false;
     */
    public function delect_redis_status($int_project_id){
        $redis = Cache::instance ('redis');
        $bool = false;
        if($int_project_id){
             $redis->delete($int_project_id."_project_status");
             $bool = true;
        }
        return $bool;
    }
    /**
     * 检查项目名称
     * @author jiye
     * @param $str_project_name 项目名称
     * @date 2013/11/1
     * return true or false
     */
    public function check_project_name($str_project_name){
        if($str_project_name){
            $int_project_name_num = ORM::factory("Project")->where("project_brand_name","=",trim($str_project_name))->count_all();
            if($int_project_name_num > 0){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }
    /**
     * 检查项目推广语
     * @author jiye
     * @param $str_project_advert 项目名称
     * @date 2013/11/1
     * return true or false
     */
    public function check_project_advert($str_project_advert,$user_id){
    	if($str_project_advert && $user_id){
			$company_id = $this->getCompanyId($user_id);
            #通过企业的id去找发布的所有的项目名称
            $model = ORM::factory("Project")->where("com_id","=", $company_id)->where("project_status",">=", intval(1))->where("project_status","<=", intval(3))->find_all();
			if(count($model) > 0){
				$arr_data = array();
				foreach ($model as $key=>$val){
					$arr_data[] = $val->project_advert;
				}
				if(in_array(trim($str_project_advert),$arr_data)){
					return 2;
				}else{
				    $int_project_advert_num = ORM::factory("Project")->where("project_advert","=",trim($str_project_advert))->count_all();
					if($int_project_advert_num > 0){
						return 3;
					}else{
						return 1;
					}
				}
			}else{
				return 1;
			}
    	}
    }

    /**
     *  获取审核不通过原因
     * @author 花文刚
     */
    public function getReason($investment_id){
        //审核失败原因插入原因表 @花文刚
        $reason = ORM::factory('Reason')->where('source_id','=',$investment_id)->order_by('add_time','DESC')->find();
        return $reason->reason;
    }

    /**
     * 删除播报的现场图片
     * @author 花文刚
     * @date 2013/11/18
     */
    public function delBobaoImg($invest_id,$img_url){
        $bobao = ORM::factory("Bobao",$invest_id);
        $bobao_img = explode("|",$bobao->bobao_img);

        foreach($bobao_img as $k=>$v){
            if(trim($v) == $img_url){
                unset($bobao_img[$k]);
                common::deletePic(URL::imgurl($img_url));
            }
        }

        $bobao->bobao_img = implode("|",$bobao_img);
        return $bobao->update();

    }

}
