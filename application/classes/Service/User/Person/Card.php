<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人名片逻辑相关处理
 * @author 钟涛
 *
 */
class Service_User_Person_Card extends Service_Card{
    /**
     * 个人名片公开度
     * @author 曹怀栋
     * @param string
     */
    public function cardOpenStutas($per_id,$type){
        if( $type != 1 && $type != 2 && $type != 3  && $type != 4 ){
            return false;
        }
        $update = ORM::factory("Personinfo",$per_id);
        if( empty($update->per_open_stutas) ){
            return false;
        }
        $update->per_open_stutas = $type;
        if($update->update()) {
            return $type;
        }else{
            return false;
        }
    }
    /**
     * 获取我的名片模板信息
     * @param  [int] $com_id [企业用户信息表ID]
     * @author 周进
     */
    public function getCardStyleInfo($urlpage,$type="person"){
        //获取名片模板数量(目前名片模板图片存在在数组中)
        $count = count(common::card_img_small($type));
        $page_size = 9; //分页大小
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => $page_size,
        ));
        //以下对数组信息模拟分页情况
        $return_arr=array();
        $return_arr['list'] = array_slice(common::card_img_small($type), ($urlpage-1)*$page_size, $page_size);
        $return_arr['page'] = $page;
        return $return_arr;
    }
    /**
     * @sso
     * 更新个人名片模板信息
     * @author 周进
     */
    public function updateCardStyleInfo($user_id,$cardstyle){
        /**$model = ORM::factory('User',$user_id);
        $model->card_style =$cardstyle;
        if($model->save()) {
            return true;
        }
        else{
            return false;
        }
        **/
        $result= Service_Sso_Client::instance()->updateBasicInfoById($user_id,'card_style');
        if( $result===false ){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 个人收到的名片2张信息列表
     * @author 周进
     * @param int $user_id 当前登录用户ID
     * @param int $type = 1表示当前个人用户,2表示当前企业用户
     */
    public function twoReceiveCard($user_id,$num){
        $user_id = intval($user_id);
        //个人用户 关联的表包括（名片信息表、企业信息表、收藏表）
        $cardinfo= ORM::factory('Cardinfo');
        $cardinfo->join('user_company','LEFT')->on('com_user_id','=','from_user_id')
        ->where('to_user_id','=',$user_id)->where('to_del_status','=',0)->limit($num);
        return $this->installSearchCondition($cardinfo,array(),$user_id,1);
    }

    /**
     * 个人收到的名片信息列表
     * @author 钟涛
     * @param int $user_id 当前登录用户ID
     * @param int $type = 1表示当前个人用户,2表示当前企业用户
     */
    public function searchReceiveCard($search,$user_id){
        $user_id = intval($user_id);
        //个人用户 关联的表包括（名片信息表、企业信息表、收藏表）
        $cardinfo= ORM::factory('Cardinfo');
        if(arr::get($search,'parent_id','')=='' && arr::get($search,'project_amount_type','')==''){
            $tpye=1;//筛选条件不包含项目属性
            $cardinfo->join('user_company','LEFT')->on('com_user_id','=','from_user_id')
            ->where('to_user_id','=',$user_id)->where('to_del_status','=',0);
            return $this->installSearchCondition($cardinfo,$search,$user_id,$tpye);
        }else{
            $tpye=2;//筛选条件包含项目属性
            $cardinfo->join('user_company','LEFT')->on('com_user_id','=','from_user_id')
            ->join('project_search_card','LEFT')->on('user_id','=','from_user_id')
            ->where('to_user_id','=',$user_id)->where('to_del_status','=',0);
            return $this->installSearchCondition($cardinfo,$search,$user_id,$tpye);
        }
    }

    /**
     * 个人递出名片信息列表
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function searchSendCard($search,$user_id){
        $cardinfo= ORM::factory('Cardinfo');
         if(arr::get($search,'parent_id','')=='' && arr::get($search,'project_amount_type','')==''){
            $tpye=1;//筛选条件不包含项目属性
            //个人用户关联的表包括（名片信息表、企业信息表、收藏表）
            $cardinfo->join('user_company','LEFT')->on('com_user_id','=','to_user_id')
            ->where('from_user_id','=',$user_id)//发送记录表的接收用户为当前登录用户id
            ->where('from_del_status','=',0);//(我收到的名片 status=0)未删除的名片
            return $this->installSearchCondition($cardinfo,$search,$user_id,$tpye);
        }else{
            $tpye=2;//筛选条件包含项目属性
            //个人用户关联的表包括（名片信息表、企业信息表、收藏表）
            $cardinfo->join('user_company','LEFT')->on('com_user_id','=','to_user_id')
                    ->join('project_search_card','LEFT')->on('project_id','=','to_project_id')
            ->where('from_user_id','=',$user_id)//发送记录表的接收用户为当前登录用户id
            ->where('from_del_status','=',0);//(我收到的名片 status=0)未删除的名片
            return $this->installSearchCondition($cardinfo,$search,$user_id,$tpye);
        }
    }

    /**
     * 组装筛选名片条件（当前个人用户登录）
     * @author 钟涛
     */
    protected function installSearchCondition($cardinfo,$search,$userid,$type){
        $card_search_row = ORM::factory('Cardinfo')->getSearchRow();
        $cardinfo->where('from_user_id', '>', 0);
        //$cardinfo->group_by('to_user_id');
        //$cardinfo->where('to_user_id', '>', 0);
        foreach($card_search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                if($key=='send_time'){//对收到名片时间做筛选
                    if($search[$key]=='10000') {//10000定义为收到名片时间：半年以上
                        $cardinfo->where($key, '<=', time()-(180*24*60*60));
                    }else{//其他的收到名片时间：1天、2天。。等
                        $cardinfo->where($key, '>=', time()-($search[$key]*24*60*60));
                    }
                }elseif($key=='send_count'){//对收到名片次数做筛选
                    if($search[$key]=='4') {//重复收到名片次数：3天以上
                        $cardinfo->where($key, '>=', 4);
                    }else{//重复收到名片次数：1天、2天、3天
                        $cardinfo->where($key, '=', $search[$key]);
                    }
                }
                else {//对名片显示做筛选(不限、已查看名片、未查看名片)
                    $cardinfo->where($key, '=', $search[$key]);
                }
            }
        }
        $listArr=array();
        if($type==2){ //对项目属性表进行筛选(1级行业，2级行业，投资金额)
            $pro_model= ORM::factory('ProjectSearchCard');
            $search_row = $pro_model->getSearchRow();
            $thisaddstatus=false;
            foreach($search_row as $key => $value){
                if(isset($search[$key]) AND $search[$key] != ''){
                    $thisaddstatus=true;
                    //筛选条件（1级行业，2级行业，投资金额）
                    $cardinfo->where($key, '=', $search[$key]);
                }
            }
            $cardinfo->where('project_status', '=', 2);//2为通过审核的项目
            $total_count=$cardinfo->reset(false)->select("*")->group_by('card_id')->find_all( )->as_array();
            $page = Pagination::factory(
                    array(
                    'total_items'    => count($total_count),
                    'items_per_page' => 10,
            ));
            $listArr=$cardinfo->select("*")->group_by('card_id')->limit($page->items_per_page)->offset($page->offset)->order_by('send_time', 'DESC')->find_all( );
        }else{
            $page = Pagination::factory(array(
                    'total_items'    => $cardinfo->reset(false)->count_all(),
                    'items_per_page' => 10,
            ));
            $listArr=$cardinfo->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('send_time', 'DESC')->find_all( );
        }
        $userlist=array();
        $resultlist=array();
        $ser=new Service_User_Company_User();
        $card_service=new Service_Card();
        foreach ($listArr as $list){
            //名片上面序列化保存的项目信息
            $data = unserialize($list->com_card_config);
            $projectinfo = $card_service->getProjectByCompanyCard($data);
            //当选择项目图片的时候要显示项目图片
            if($projectinfo['logo'] != 0){
                $project =ORM::factory('Project',$data['logo']);
                if($project->project_source != 1) {
                     $list->com_logo= project::conversionProjectImg($project->project_source, 'logo', $project->as_array());
                }else{
                     $list->com_logo = URL::imgurl($project->project_logo);
                }
            }else{
                 $list->com_logo=$ser->getCompanyLogo($list->com_id);
            }
            if($this->getFavoriteStatus($userid,$list->com_user_id)==TRUE){
                $userlist['favorite_status']=1;//已收藏此名片
            }else{
                $userlist['favorite_status']=0;//未收藏此名片
            }
            $projectservice=new Service_User_Company_Project();

            //项目属性显示
            //招商地区
            $pro_area=$projectservice->getProjectArea($list->to_project_id);
            //招商地区[纯中文显示]
            $area_zhong='';
            if(count($pro_area)&& is_array($pro_area)){
                $area='';
                foreach ($pro_area as $v){
                    $area=$area.$v.',';
                }
                $area= substr($area,0,-1);
                if(mb_strlen($area)>16){
                    $area_zhong= mb_substr($area,0,16,'UTF-8').'...';
                }
                else{
                    $area_zhong= $area;
                }
            }else{
                $area_zhong= $pro_area;
            }
            $proinfo=ORM::factory('Project',$list->to_project_id);
            $monarr= common::moneyArr();
            $userlist['projectname']=$proinfo->project_brand_name;//项目名称
            $userlist['projectmonney']=arr::get($monarr,$proinfo->project_amount_type,'无');//项目金额
            $userlist['projectarea']=$area_zhong;//项目地区
            $userlist['projectlogo']=$projectservice->getProjectLogo($proinfo);//项目logo

            $userlist['liuyan']=$this->getUserLetterCount($list->com_user_id,$userid);//是否有留言
            $resultlist[] = array_merge($list->as_array(),$userlist);
        }
        return array(
                'page' => $page,
                'list' =>$resultlist,
        );
    }

    /**
     *获取我递出名片总数
     * @author 曹怀栋
     */
    public function getOutCardCount($user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id','=',$user_id)//发送用户为当前登录用户id
        ->where('from_del_status','=',0)//未删除状态
        ->count_all();
    }

    /**
     * 获取企业名片上面的勾选项目信息
     * @author 曹怀栋
     */
    public function getSerializeArrayList($array_list){
        foreach ($array_list as $k => $v):
        //把值给新数组
        $array_list[$k] = $v;
        $card_service =new Service_User_Person_Card();
        //名片上面序列化保存的项目信息
        $data = unserialize($v['com_card_config']);
        $projectinfo = $card_service->getProjectByCompanyCard($data);
        //当选择项目图片的时候要显示项目图片
        if($projectinfo['logo'] != 0){
            $project =ORM::factory('Project',$data['logo']);
            if($project->project_source != 1) {
                $array_list[$k]['com_logo'] = $project->project_logo;
            }else{
                $array_list[$k]['com_logo'] = URL::imgurl($project->project_logo);
            }
        }
        $projectids = $projectinfo['brand'];//名片上面项目
        $project_brand_name ="";
        $project_amount_type ="";
        if(isset($projectids) && $projectids !=""){
            foreach($projectids as $key => $value):
            //读取表名和投资金额
            $projectinfo= ORM::factory('Project',$value);
            $project_brand_name .= $projectinfo->project_brand_name."/";
            $money = common::moneyArr();
            $money = isset($money[$projectinfo->project_amount_type]) ? $money[$projectinfo->project_amount_type] : "";
            $array_list[$k]['project_amount_type'] =$money;
            $project_amount_type .= $money."/";
            endforeach;
            //去掉最后面的“/”
            $project_brand_name =substr($project_brand_name,0,-1);
            $project_amount_type =substr($project_amount_type,0,-1);
        }
        $array_list[$k]['project_brand_name'] = $project_brand_name;
        $array_list[$k]['project_amount_type'] = $project_amount_type;
        endforeach;
        return $array_list;
    }

    /**
     * 获取企业的所有项目信息
     * @author 钟涛
     */
    public function getAllSerializeArrayList($array_list){
        if(count($array_list)){
            foreach ($array_list as $k => $v){
                //把值给新数组
                $array_list[$k] = $v;
                $project_brand_name ="";
                $project_amount_type ="";
                $projectinfo= ORM::factory('Project')->where('com_id', '=', $v['com_id'])->where('project_status', '=', 2)->order_by('project_addtime','DESC')->limit(5)->find_all();
                $p_money = common::moneyArr();
                foreach($projectinfo as $p_value){
                    $a_value='<a style="color: #000000;" target="_blank" href="'.urlbuilder::project($p_value->project_id).'">'.$p_value->project_brand_name.'</a>';
                    $project_brand_name .= $a_value."/";
                    $money = isset($p_money[$p_value->project_amount_type]) ? $p_money[$p_value->project_amount_type] : "";
                    $project_amount_type .= $money."/";
                }
                //去掉最后面的“/”
                $project_brand_name =substr($project_brand_name,0,-1);
                $project_amount_type =substr($project_amount_type,0,-1);
                $array_list[$k]['project_brand_name'] = $project_brand_name;
                $array_list[$k]['project_amount_type'] = $project_amount_type;
            }
        }
        return $array_list;
    }

    /**
     * 个人收藏名片搜索
     * @author 周进
     */
    public function searchFavorite($search='',$user_id){
        $search = Arr::map(array("HTML::chars"), $search);
        $user_id=intval($user_id);
        $add = '';
        $order = " order by favorite_time desc";
        if ($search['exchange_status']==0&&$search['from_read_status']=='-1'){
            $queryresult = DB::select()->from('favorite')->join('user_company', 'LEFT')->on('com_user_id', '=', 'favorite_user_id');
        }else{
            $queryresult = DB::select()->from('card_info','favorite')->join('user_company', 'LEFT')->on('com_user_id', '=', 'favorite_user_id');
        }

        //1级行业筛选+2级行业筛选+投资金额筛选
        $project_sql=' ';
        if(arr::get($search,'project_industry_id','')!='' || arr::get($search,'parent_id','')!='' || arr::get($search,'project_amount_type','')!=''){
            $queryresult=$queryresult->join('project_search_card', 'LEFT')->on('project_search_card.user_id', '=', 'favorite.favorite_user_id')->and_where('project_status','=',2);
            //$project_sql = " LEFT JOIN czzs_project_search_card on czzs_project_search_card.user_id=czzs_favorite.favorite_user_id  where project_status=2 and ";
            if(arr::get($search,'project_amount_type','')!=''){
                $queryresult=$queryresult->and_where('project_amount_type','=',$search['project_amount_type']);
                //$project_sql.=" project_amount_type=".$search['project_amount_type'].' and ';//投资金额
            }
            if(arr::get($search,'project_industry_id','')!=''){//2级行业
                $queryresult=$queryresult->and_where('project_industry_id','=',$search['project_industry_id']);
                //$project_sql.=" project_industry_id=".$search['project_industry_id'].' and ';//2级行业
            }elseif(arr::get($search,'parent_id','')!=''){//1级行业
                $queryresult=$queryresult->and_where('parent_id','=',$search['parent_id']);
                //$project_sql.=" parent_id=".$search['parent_id'].' and ';
            }else{    }
        }else{
            //$project_sql =' where ';
        }

        //不限名片，不限状态搜索
        if ($search['exchange_status']==0&&$search['from_read_status']=='-1'){
            $queryresult=$queryresult->where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
            //$sql = " FROM czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
        }
        elseif ($search['exchange_status']==0&&$search['from_read_status']!='-1'){//递出收到交换状态不限，有是否查看条件限制
            if($search['from_read_status']=='0'){//未查看（包括的情况只有已经交换未查看）
                $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('com_user_id'))->and_where('to_user_id','=',$user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->or_where_close()->where_close()->and_where('from_read_status','=',0)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('exchange_status','=',1)->and_where('favorite.user_id','=',$user_id);
                //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." ((from_user_id = com_user_id and to_user_id = ".$user_id.") or (to_user_id = com_user_id and from_user_id = ".$user_id." )) and from_read_status=0 and from_del_status=0 and to_del_status=0 and favorite_status=1 and exchange_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
            }elseif($search['from_read_status']=='1'){//已查看（包括的情况有已经交换的查看、未交换递出、已交换递出）
                $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('com_user_id'))->and_where('to_user_id','=',$user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->and_where_open()->where('exchange_status','=',0)->or_where_open()->where('from_read_status','=',1)->and_where('exchange_status','=',1)->or_where_close()->and_where_close()->where_close()->where_close()->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
                //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." ((from_user_id = com_user_id and to_user_id = ".$user_id.") or (to_user_id = com_user_id and from_user_id = ".$user_id." and (exchange_status=0 or (from_read_status=1 and exchange_status=1)))) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
            }else{	}
        }
        elseif ($search['exchange_status']!=0){//有递出收到交换条件限制的
            if ($search['exchange_status']=='1'){//交换的
                //$add.=" and exchange_status=1";
                if ($search['from_read_status']=='0'){//已交换未读
                    $queryresult=$queryresult->and_where('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_read_status','=',0)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." to_user_id = com_user_id and from_user_id = ".$user_id." and from_read_status=0 and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }elseif ($search['from_read_status']=='1'){//已交换已查看
                    $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('com_user_id'))->and_where('to_user_id','=',$user_id)->where_close()->or_where_open('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_read_status','=',1)->or_where_close()->where_close()->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." ((from_user_id = com_user_id and to_user_id = ".$user_id.") or (to_user_id = com_user_id and from_user_id = ".$user_id." and from_read_status=1)) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else{//不限查看状态
                    $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('com_user_id'))->and_where('to_user_id','=',$user_id)->where_close()->or_where_open('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->or_where_close()->where_close()->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." ((from_user_id = com_user_id and to_user_id = ".$user_id.") or (to_user_id = com_user_id and from_user_id = ".$user_id.")) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }
                $queryresult=$queryresult->and_where('exchange_status','=',1);
            }
            elseif ($search['exchange_status']=='2'){//已收到
                //$add.=" and exchange_status=0";
                if($search['from_read_status']=='0'){//不存在
                    $queryresult=$queryresult->and_where('from_del_status','=',100);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." to_user_id = com_user_id and from_user_id = ".$user_id." and from_del_status=100 and to_del_status=100 and favorite_status=100 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else{//我为from_user_id递出的已查看不存在应该放入交换中，我为to_user_id时都是
                    $queryresult=$queryresult->and_where('from_user_id','=',DB::expr('com_user_id'))->and_where('to_user_id','=',$user_id)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('to_read_status','=',1)->and_where('favorite.user_id','=',$user_id);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." from_user_id = com_user_id and to_user_id = ".$user_id." and from_del_status=0 and to_del_status=0 and favorite_status=1 and to_read_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                   }
                   $queryresult=$queryresult->and_where('exchange_status','=',0);
            }
            elseif ($search['exchange_status']=='3'){//已递出
                //$add.=" and exchange_status=0";
                if($search['from_read_status']=='0'){//不存在
                    $queryresult=$queryresult->and_where('from_del_status','=',100);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." to_user_id = com_user_id and from_user_id = ".$user_id." and from_del_status=100 and to_del_status=100 and favorite_status=100 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else{
                    $queryresult=$queryresult->and_where('to_user_id','=',DB::expr('com_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id);
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_company on com_user_id=favorite_user_id ".$project_sql." to_user_id = com_user_id and from_user_id = ".$user_id." and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }
                $queryresult=$queryresult->and_where('exchange_status','=',0);
            }
        }
        if (isset($search['favorite_time'])&&$search['favorite_time']!=""){//收藏时间
            if($search['favorite_time']=='10000'){//10000定义为收到名片时间：半年以上
                $queryresult=$queryresult->and_where('favorite_time','<=',(time()-(180*24*60*60)));
                //$add.=" and favorite_time<=".(time()-(180*24*60*60));
            }else{//其他的收到名片时间：1天、2天。。等
                $queryresult=$queryresult->and_where('favorite_time','>=',(time()-($search['favorite_time']*24*60*60)));
                //$add.=" and favorite_time>=".(time()-($search['favorite_time']*24*60*60));
            }
        }
        $queryresult=$queryresult->group_by('favorite_user_id')->order_by('favorite_time', 'DESC');
        //执行查询
        $num = $queryresult->execute()->as_array();
        $page = Pagination::factory(array(
                'total_items'    => count($num),
                'items_per_page' => 8,
        ));
        $queryresult=$queryresult->limit($page->items_per_page)->offset($page->offset);
        //$limit = " limit ".$page->offset.",".$page->items_per_page;
        $result = $queryresult->execute()->as_array();
        return array(
                'page' => $page,
                'list' => $this->addResultData($result,$user_id),
                'total_count'=>count($num),
        );
    }

    /**
     * 组合筛选（收藏列表相关）
     * @author 周进
     */
    function addResultData($personlist,$userid){
        $userid=intval($userid);
        $userlist=array();
        $result = array();
        $resultlist=array();
        $user_service=new Service_User_Person_User();
        $card_service=new Service_User_Person_Card();
        $ser=new Service_User_Company_User();
        foreach ($personlist as $list){
            //名片上面序列化保存的项目信息
            $data = unserialize($list["com_card_config"]);
            $projectinfo = $card_service->getProjectByCompanyCard($data);
            //当选择项目图片的时候要显示项目图片
            if($projectinfo['logo'] != 0){
                $project =ORM::factory('Project',$data['logo']);
                if($project->project_source != 1) {
                     $list["com_logo"]= project::conversionProjectImg($project->project_source, 'logo', $project->as_array());
                }else{
                     $list["com_logo"] = URL::imgurl($project->project_logo);
                }
            }else{
                 $list["com_logo"]=$ser->getCompanyLogo($list["com_id"]);
            }
            //判断是否已交换名片
            if($card_service->getExchaneCardCountByTwoIdAll($list['com_user_id'], $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list['com_user_id'], $userid)){
                $userlist['exchangecardcount'] = 1;//已经与此用户交换名片
                $query1 = DB::select()->from('card_info')->where_open()->where_open()->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list['com_user_id'])->where_close()->where_close()->or_where_open()->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list['com_user_id'])->or_where_close()->and_where('exchange_status', '=', 1);
                //$sql = " FROM czzs_card_info where ((from_user_id = ".$userid." and to_user_id = ".$list['com_user_id'].") or (to_user_id = ".$userid." and from_user_id = ".$list['com_user_id'].")) and exchange_status=1 ";
                $result = $query1->execute();
                foreach ($result as $v){
                    $userlist['cardinfo']=$v;
                    if ($v['from_user_id']==$userid)
                        $userlist['cardinfo']['card_type']=2;
                    else
                        $userlist['cardinfo']['card_type']=1;
                }
            }
            elseif($card_service->getOutCardCountByTwoIdAll($list['com_user_id'], $userid)){
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 1;//已经给此用户发送名片
                $userlist['receivedcardcount'] = 0;//没有收到
                $query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list['com_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where from_user_id = ".$userid." and to_user_id = ".$list['com_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                foreach ($result as $v){
                    $userlist['cardinfo']=$v;
                    $userlist['cardinfo']['card_type']=2;
                }
            }elseif($card_service->getReceiveCardCountByTwoIdAll($list['com_user_id'], $userid)){//当前用户是否收到此投资者发送名片
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 0;
                $userlist['receivedcardcount'] = 1;//已经收到
                $query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list['com_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where to_user_id = ".$userid." and from_user_id = ".$list['com_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                foreach ($result as $v){
                    $userlist['cardinfo']=$v;
                    $userlist['cardinfo']['card_type']=1;
                }
            }else{
                $userlist['cardinfo']=array();
                $userlist['receivedcardcount'] = 0;//没有收到
                $userlist['outcardcount'] = 0;//没有递出
                $userlist['exchangecardcount'] = 0;//没有与此用户交换名片
            }
            //判断用户是否添加从业经验
            if($user_service->getExperienceCount($list['com_user_id'])) {
                $userlist['per_experience_stutas']=1;//此用户已经添加从业经验
            }else{
                $userlist['per_experience_stutas']=0;//此用户没有添加从业经验
            }
            $userlist['liuyan']=$this->getUserLetterCount($list['com_user_id'],$userid);//是否有留言
            //判断收藏表中对应的数据
            if ($card_service->getFavoriteStatus($userid,$list['com_user_id'])==TRUE)
                $userlist['favorite_status']=1;//已存在对应收藏关系
            else
                $userlist['favorite_status']=0;//无收藏
            $resultlist[] = array_merge($list,$userlist);
        }
        return $resultlist;
    }
    /**
     * @sso
     * 获取单个招商者名片信息
     * @author 钟涛
     */
    public function getPersonBusinessCard($user_id,$cardid=0,$tpye=0){
        //$user = ORM::factory('User',$user_id);
        //sso 赵路生 2013-11-12
        $user= Service_Sso_Client::instance()->getUserInfoById( $user_id );
        $card_service=new Service_User_Person_Card();
        $com_user_ser = new Service_User_Company_User();
        $company = $com_user_ser->getCompanyInfo($user_id);
        $user_array = array();
        foreach($user as $k=>$v){
        	$user_array[$k] = $v;
        }
        $result = array_merge($user_array,$company->as_array());
        unset($result['user_company']);
        $result['com_contact'] = mb_substr($result['com_contact'],0,4,'UTF-8');
        $result['com_name'] = mb_substr($result['com_name'],0,17,'UTF-8');
        $result['com_adress'] = mb_substr($result['com_adress'],0,26,'UTF-8');
        if(!$result['com_phone']){
            $result['com_phone']='';
        }
        //名片上面序列化保存的项目信息
        $data = unserialize($result['com_card_config']);
        $projectinfo = $card_service->getProjectByCompanyCard($data);
        $ser=new Service_User_Company_User();
        //当选择项目图片的时候要显示项目图片
        if($projectinfo['logo'] != 0){
            $project =ORM::factory('Project',$data['logo']);
            if($project->project_source != 1) {
                 $result['com_logo']= project::conversionProjectImg($project->project_source, 'logo', $project->as_array());
            }else{
                $result['com_logo'] = URL::imgurl($project->project_logo);
            }
        }else{
            $result['com_logo']=$ser->getCompanyLogo($result['com_id']);
        }
        //$result['com_logo']=$ser->getCompanyLogo($result['com_id']);
        $projectids = $projectinfo['brand'];//名片上面项目
        if(isset($projectids) && $projectids !=""){
            foreach($projectids as $key => $value):
            //读取表名和投资金额
            $projectinfo= ORM::factory('Project',$value);
            $result['project'][$key]['name'] = mb_substr($projectinfo->project_brand_name,0,10,'UTF-8');
            $sumary_text=htmlspecialchars_decode($projectinfo->project_summary);
            $result['project'][$key]['content'] = mb_substr(strip_tags($sumary_text),0,38,'UTF-8');
            $result['project'][$key]['url'] = urlbuilder::project($projectinfo->project_id);
            endforeach;
        }else{
            $result['project'] = array();
        }
        //获取地址
        if( ceil($result['com_area'])>0 ){
        	$area_arr= array('id'=>$result['com_area']);
        	$rs_area= common::arrArea($area_arr);
        	$area_name= $rs_area->cit_name;
        }else{
        	$area_name= '';
        }
        
        if( ceil($result['com_city'])>0 ){
        	$city_arr= array('id'=>$result['com_city']);
        	$rs_city= common::arrArea($city_arr);
        	$city_name= $rs_city->cit_name;
        }else{
        	$city_name= '';
        }
        $result['com_adress']=$area_name.$city_name.$result['com_adress'];
        //更名片为已读
        if ($cardid!=0){
            if($tpye==1) {//我收到的名片
                $card_service->updateReceCardReadStatus($cardid);
            }elseif($tpye==2) {//我递出的名片
                $card_service->updateOutCardReadStatus($cardid);
            }else{    }
        }
        //当前用户id
        $this_user_id=Cookie::get("user_id");
        //查看名片log
        $this->addCardBehaviourInfo($this_user_id,$user_id,9);
        //被查看名片log
        //$this->addCardBehaviourInfo($user_id,$this_user_id,10);
        unset($result['user_id']);
        unset($result['email']);
        unset($result['password']);
        unset($result['mobile']);
        unset($result['last_logintime']);
        unset($result['reg_time']);
        unset($result['last_login_ip']);
        unset($result['com_id']);
        unset($result['com_user_id']);
        unset($result['user_type']);
        unset($result['valid_email']);
        unset($result['week_send_mobile']);
        unset($result['week_send_email']);
        unset($result['user_status']);
        unset($result['valid_mobile']);
        unset($result['total_integrity']);
        return $result;
    }

    /**
     * 个人生成名片图片
     * @author 钟涛
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
    /**
     * 个人中心--获取历史咨询
     * @author 赵路生
     * @param $user_id当前登录用户id
     * @return array
     */
    public function getHistoryConsult($user_id=0){
        $user_id = intval($user_id);
        $return = array();
        if($user_id){
            $model = ORM::factory('UserLetter')->where('user_id','=',$user_id)->where('to_project_id','!=',0)->where('letter_status','=',1);
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
     * 个人中心--获取历史咨询 2条记录
     * @author 赵路生
     * @param $user_id当前登录用户id
     * @return array
     */
    public function getHistoryConsultArray($user_id=0){
    	$user_id = intval($user_id);
    	$return = array();
    	if($user_id){
    		$return  = ORM::factory('UserLetter')->where('user_id','=',$user_id)->where('to_project_id','!=',0)->where('letter_status','=',1)->limit(3)->order_by('add_time', 'DESC')->find_all()->as_array();
    	}
    	return $return;
    }
}
?>