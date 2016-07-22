<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户中心名片公共部分 企业+个人名片信息
 * @author 钟涛
 *
 */
class Service_Card{
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
    * 判断当前用户的收藏名片总数
    * @author周进
    * @param int $user_id 当前用户
    * @return int
    */
    public function getFavoriteNums($user_id){
        $nums = ORM::factory('Favorite')->where('user_id', '=', intval($user_id))->where('favorite_status','=','1')->count_all();
        return $nums;
    }

    /**
    * 取消收藏（单个或则批量）实际更新数据库方法为updateExecuteFavorite
    * @param int $user_id 当前用户
    * @param int $type 取消类型 1为单个2为批量
    * @param int or array $data $type=1时对应int，为2时批量数组$favorite_id收藏表主键
    * @author周进
    */
    public function updateFavorite($user_id,$type=1,$data){
        $result = array('status'=>FALSE,'success'=>array(),'error'=>array());
        $status = TRUE;
        if ($type==1){//单个取消
            $result = $this->updateExecuteFavorite($user_id, $data);
        }
        elseif ($type==2){//批量取消
            foreach($data as $v){
                $source = $this->updateExecuteFavorite($user_id,$v);
                if ($source['status']==TRUE)
                    $result['success'][] = $source['success'];
                if ($source['status']==FALSE){
                    $result['error'][] = $source['error'];
                    $status = FALSE;
                }
            }
            $result['status'] =  $status==FALSE?FALSE:TRUE;
        }
        else
            $result = array('status'=>FALSE,'success'=>0,'error'=>$data);
        return $result;
    }

    /**
    * 取消收藏对数据库的处理先查询如果不存在则不作处理直接返回FALSE 存在则更新favorite_status=0返回TRUE
    * @param int $user_id 当前用户ID
    * @param int $favorite_user_id 收藏的用户ID
    * @return bool
    * @author周进
    */
    public function updateExecuteFavorite($user_id,$favorite_id){
        $result = array('status'=>TRUE,'success'=>$favorite_id,'error'=>0);
        $user_id = intval($user_id);
        $favorite_id = intval($favorite_id);
        $favorite = ORM::factory('Favorite');
        $data = $favorite->where('favorite_id', '=', $favorite_id)->where('user_id','=',$user_id)->find()->as_array();
        if ($data['favorite_id']!=""){
            $favorite->favorite_id = $favorite_id;
            $favorite->favorite_status = 0;
            $favorite->favorite_time = time();
            try {
                $favorite->update();
                //$this->addCardBehaviourInfo($user_id,$favorite_id,7);//取消收藏名片log
            }
            catch (Kohana_Exception $e){
                $result = array('status'=>FALSE,'success'=>0,'error'=>$favorite_id);
            }
        }
        else
            $result = array('status'=>FALSE,'success'=>0,'error'=>$favorite_id);
        return $result;
    }

    /**
    * 添加收藏（单个或则批量）实际更新数据库方法为addExecuteFavorite
    * @param int $user_id 当前用户
    * @param int $type 取消类型 1为单个2为批量
    * @param int or array $data $type=1时对应int，为2时批量数组 用户IDfavorite_user_id
    * @return array array('status'=>FALSE or TRUE,'success'=>id or array(),'error'=>id or array()
    * @author周进
    */
    public function addFavorite($user_id,$type=1,$data,$datacardid=0,$cardtype=0){
        $result = array('status'=>FALSE,'success'=>array(),'error'=>array());
        $status = TRUE;
        $card_service=new Service_User_Company_Card();
        if ($type==1){//单个添加
            $result = $this->addExecuteFavorite($user_id,$data);
            if($cardtype==1){//修改我收到的名片为已读
                $card_service->updateReceCardReadStatus($datacardid);
            }elseif($cardtype==2){//修改我递出的名片为已读
                $card_service->updateOutCardReadStatus($datacardid);
            }else{	}
        }
        elseif ($type==2){//批量添加
            foreach($data as $v){
                $source = $this->addExecuteFavorite($user_id,$v);
                if ($source['status']==TRUE)
                    $result['success'][] = $source['success'];
                if ($source['status']==FALSE){
                    $result['error'][] = $source['error'];
                    $status = FALSE;
                }
            }
            if($cardtype==1){//批量修改我收到的名片为已读
                $card_service->updateBatchReceCardReadStatus($datacardid);
            }elseif($cardtype==2){//批量修改我递出的名片为已读
                $card_service->updateBatchOutCardReadStatus($datacardid);
            }else{	}
            $result['status'] =  $status==FALSE?FALSE:TRUE;
        }
        else
            $result = array('status'=>FALSE,'success'=>0,'error'=>$data);
        return $result;
    }

    /**
    * 添加收藏对数据库的处理先查询如果已经存在则不作处理直接返回TRUE
    * @param int $user_id 当前用户ID
    * @param int $favorite_user_id 收藏的用户ID
    * @return array array('status'=>FALSE,'success'=>0,'error'=>0)是否成功，成功的id，失败的ID
    * @author周进
    */
    public function addExecuteFavorite($user_id,$favorite_user_id){
        $result = array('status'=>TRUE,'success'=>$favorite_user_id,'error'=>0);
        $user_id = intval($user_id);
        $favorite_user_id = intval($favorite_user_id);
        $favorite = ORM::factory('Favorite');
        $data = $favorite->where('favorite_user_id', '=', $favorite_user_id)->where('user_id','=',$user_id)->find()->as_array();
        $favorite->user_id = $user_id;
        $favorite->favorite_user_id = $favorite_user_id;
        $favorite->favorite_time = time();
        $favorite->favorite_status = 1;
        if ($data['favorite_id']!=""){//已经存在如果已取消收藏则更新那条数据位重新收藏
            if ($data['favorite_status']==0){
                $favorite->favorite_id = $data['favorite_id'];
                try {
                    $favorite->update();
                    $this->addCardBehaviourInfo($user_id,$favorite_user_id,5);//收藏名片log
                    //$this->addCardBehaviourInfo($favorite_user_id,$user_id,6);//被收藏名片log
                }
                catch (Kohana_Exception $e){
                    $result = array('status'=>FALSE,'success'=>0,'error'=>$favorite_user_id);
                }
            }
        }
        else{//没有的新增
            try {
                $favorite->create();
                $this->addCardBehaviourInfo($user_id,$favorite_user_id,5);//收藏名片log
                //$this->addCardBehaviourInfo($favorite_user_id,$user_id,6);//被收藏名片log
            }
            catch (Kohana_Exception $e){
                $result = array('status'=>FALSE,'success'=>0,'error'=>$favorite_user_id);
            }
        }
        return $result;
    }

    /**
     * sso
     * 重复递出名片
     * @author 钟涛
     * @param int $card_id 当前名片id
     * @return int
     */
    function addRepeatSendCard($card_id,$user_id,$data=''){//@赵路生
        //事务：SQL Transactions with Kohana 3.3
        $db = Database::instance();
        $db->begin();
        try{
            //1.更新已递出的名片次数+1
            $this->updateSendCardStatus($card_id);
            //2.添加一条发送名片记录表信息
            $this->insertSendCardLog($card_id);
            //更新当天发送名片次数
            $this->updateSendCardCountLog($user_id);
            //根据用户ID获取当天已经发送名片数据信息
            $sentcount=$this->getSendCardCountInfo($user_id);
            $otheruserid=$this->getOtherUserID($card_id,$user_id);

            //@ sso赵路生 2013-11-12
            $usertype = Service_Sso_Client::instance()->getUserInfoById($user_id)->user_type;
            if($usertype==2){
                $projectid=ORM::factory('Cardinfo',$card_id)->to_project_id;
                $otherusertype=1;
            }elseif($usertype==1){
                $otherusertype=2;
                $projectid=0;
            }else{
                $projectid=0;
                $otherusertype=0;
            }
            if($otheruserid || $projectid){
                if(isset($data['checked_val']) && $data['checked_val']){
                    $this->addLetter($user_id,$data); //发信 @赵路生
                }
                if($usertype==2){//个人必须有项目
                    if($projectid){
                        //递出名片log
                        $this->addCardBehaviourInfo($user_id,$otheruserid,2,$usertype,$projectid,1);
                        //收到名片log
                        $this->addCardBehaviourInfo($otheruserid,$user_id,3,$otherusertype,$projectid,1);
                    }
                }else{
                    //递出名片log
                    $this->addCardBehaviourInfo($user_id,$otheruserid,2,$usertype,$projectid);
                    //收到名片log
                    $this->addCardBehaviourInfo($otheruserid,$user_id,3,$otherusertype,$projectid);
                }
            }
            //当天还剩余发送名片数量
            $result['count'] = 30 - intval($sentcount['day_send_count']);
            $result['status'] = '100';
            $db->commit();
        }
        catch(Kohana_Exception $e){
            $db->rollback();
            $result['status'] = '0';
        }
        return $result;
    }

     /**
     * 根据名片id和主用户id 返回对应的操作用户id
     * @author 钟涛
     */
    public function getOtherUserID($card_id,$user_id){
        $model = ORM::factory('Cardinfo',$card_id);
        if($model->from_user_id==$user_id){
            return $model->to_user_id;
        }elseif($model->to_user_id==$user_id){
            return $model->from_user_id;
        }else{
            return false;
        }
    }

    /**
     * 重复递出名片步骤1
     * 更新已递出的名片次数+1
     * @author 钟涛
     * 2012.12.21
     */
    public function updateSendCardStatus($card_id){
        $model = ORM::factory('Cardinfo',$card_id);
        try{
             //更新已递出的名片次数+1
             $model->send_count = $model->send_count+1;
             $model->send_time = time();
             $model->update();
            }catch (Kohana_Exception $e){
               throw $e;
           }
    }

    /**
     * 重复递出名片步骤2
     * 添加一条发送名片记录表信息
     * @author 钟涛
     * 2012.12.21
     */
    public function insertSendCardLog($card_id){
        $model = ORM::factory('Cardinfolog');
        $cardinfo = array(
                'log_card_id' => $card_id,
                'log_send_time' => time()
        );
        try{
            $model->values($cardinfo)->create();
        }catch (Kohana_Exception $e){
            throw $e;
        }
    }

    /**
     * 获取最新收到的名片
     * @author 龚湧
     * @param int $user_id
     * @param int $num
     * @return ORMs | boolean
     */
    public function getReceivedCardLimit($user_id,$num){
        $card = ORM::factory("Cardinfo")
                    ->where("to_user_id","=",$user_id)
                    ->where("to_del_status","=",0)
                    ->group_by("from_user_id")
                    ->order_by("send_time","desc")
                    ->limit($num)
                    ->find_all();
       if($card->count()){
            return $card;
       }
       else{
        return false;
       }
    }

    /**
     * 我递出的交换的名片数量(不包含删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getExchaneCardCountByTwoId($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->where('to_user_id','=',$per_user_id)//接收用户
        ->where('from_del_status','=',0)//未删除的
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 我递出的交换的名片数量(包含删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getExchaneCardCountByTwoIdAll($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->where('to_user_id','=',$per_user_id)//接收用户
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 根据当前ID获取给 投资者\企业 发送名片数量(不包含删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getOutCardCountByTwoId($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->where('to_user_id','=',$per_user_id)//接收用户
        ->where('from_del_status','=',0)//未删除的
        ->count_all();
    }

    /**
     * 根据当前ID获取给 投资者\企业 发送名片数量(包含删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getOutCardCountByTwoIdAll($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->where('to_user_id','=',$per_user_id)//接收用户
        ->count_all();
    }

    /**
     * 根据当前ID获取我收到的 投资者\企业 名片数量(不包含用户删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getReceiveCardCountByTwoId($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $per_user_id)//发送用户
        ->where('to_user_id','=',$user_id)//接收用户
        ->where('to_del_status','=',0)//当前用户未删除的
        ->count_all();
    }

    /**
     * 根据当前ID获取我收到的 投资者\企业 名片数量(包含用户删除的名片)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getReceiveCardCountByTwoIdAll($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $per_user_id)//发送用户
        ->where('to_user_id','=',$user_id)//接收用户
        ->count_all();
    }

    /**
     * 根据当前ID获取我收到的 投资者\企业 名片列表
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @param  [iny] $to_del_status 查询的名片状态 不传的话为
     * @author 施磊
     * @return array
     */
    public function getReceiveCardListByTwoId($per_user_id, $user_id, $to_del_status = 'all') {
        if($to_del_status == 'all') {
            return ORM::factory('Cardinfo')
            ->where('from_user_id', '=', $per_user_id)//发送用户
            ->where('to_user_id','=',$user_id)//接收用户
            ->find()->as_array();
        } else {
            return ORM::factory('Cardinfo')
            ->where('from_user_id', '=', $per_user_id)//发送用户
            ->where('to_user_id','=',$user_id)//接收用户
            ->where('to_del_status','=', intval($to_del_status))
            ->find()->as_array();
        }
     }

     /**
      * 根据当前ID获取我收到的 投资者\企业 名片列表
      * @param  [int] $per_user_id 接收用户
      * @param  [int] $user_id 发送用户(当前登录用户)
      * @author 钟涛
      * @return array
      */
     public function getReceiveCardByTwoId($per_user_id, $user_id) {
            return ORM::factory('Cardinfo')
             ->where('from_user_id', '=', $per_user_id)//发送用户
             ->where('to_user_id','=',$user_id)//接收用户
             ->find();
     }

     /**
      * 根据当前ID获取我收到的 投资者\企业 名片列表
      * @param  [int] $per_user_id 接收用户
      * @param  [int] $user_id 发送用户(当前登录用户)
      * @author 钟涛
      */
     public function getReceiveNew($per_user_id, $user_id,$projectid=0) {
             $result= ORM::factory('Cardinfo')
             ->where('from_user_id', '=', $per_user_id)//发送用户
             ->where('to_user_id','=',$user_id);//接收用户
             if($projectid){
                 $result->where('to_project_id','=',$projectid);//项目id
              }
             return $result->find();
     }

    /**
     * 批量更改我收到的名片状态为已读
     * @author 钟涛
     */
    public function updateBatchReceCardReadStatus($cardidarr){
        if(!empty($cardidarr)){
            foreach($cardidarr as $cardid){
                $tab = ORM::factory('Cardinfo');
                $count = $tab->where('card_id','=',$cardid)->find()->as_array();
                if ($count['to_read_status']==0){
                    $tab->to_read_status = 1;
                    $tab->to_read_time = time();
                    $tab->update();
                }
            }
        }
    }

    /**
     * 更改我递出的名片状态为已读
     * @author 钟涛
     */
    public function updateOutCardReadStatus($cardid){
        $tab = ORM::factory('Cardinfo');
        $count = $tab->where('card_id','=',$cardid)->find()->as_array();
        if ($count['from_read_status']==0){
            $tab->from_read_status = 1;
            $tab->from_read_time = time();
            $tab->update();
        }
    }

    /**
     * 批量更改我递出的名片状态为已读
     * @author 钟涛
     */
    public function updateBatchOutCardReadStatus($cardidarr){
        if(!empty($cardidarr)){
            foreach($cardidarr as $cardid){
                $tab = ORM::factory('Cardinfo');
                $count = $tab->where('card_id','=',$cardid)->find()->as_array();
                if ($count['from_read_status']==0){
                    $tab->from_read_status = 1;
                    $tab->from_read_time = time();
                    $tab->update();
                }
            }
        }
    }
    /**
     * SSO
     *  更新发送名片次数和状态
     *  @author 施磊
     *  @param $card_id 名片记录id
     *  @param $todo 是否修改接发送方是否删除判断
     */
    public function updateSendCountAndFromDelStatusById($card_id, $todo = FALSE) {
        $model = ORM::factory('Cardinfo', $card_id);
        $user_id=$model->from_user_id;
        //@sso 赵路生 2013-11-12
        $usertype = Service_Sso_Client::instance()->getUserInfoById($user_id)->user_type;
        if($usertype==2){
            $projectid=$model->to_project_id;
            $otherusertype=1;
        }elseif($usertype==1){
            $otherusertype=2;
            $projectid=0;
        }else{
            $projectid=0;
            $otherusertype=0;
        }
        //更新已递出的名片次数+1
        $model->send_count = $model->send_count + 1;

        //如果要更新状态 则更新为有效
        if ($todo)
            $model->from_del_status = 0;

        $model->send_time = time();
        $model->update();

        if($model->to_user_id || $projectid){
            if($usertype==2){//个人必须有项目
                if($projectid){
                    //递出名片log
                    $this->addCardBehaviourInfo($model->from_user_id,$model->to_user_id,2,$usertype,$projectid,1);
                    //收到名片log
                    $this->addCardBehaviourInfo($model->to_user_id,$model->from_user_id,3,$otherusertype,$projectid,1);
                }
            }else{
                //递出名片log
                $this->addCardBehaviourInfo($model->from_user_id,$model->to_user_id,2,$usertype,$projectid);
                //收到名片log
                $this->addCardBehaviourInfo($model->to_user_id,$model->from_user_id,3,$otherusertype,$projectid);
            }
        }
    }

    /**
     *  更新接受到的名字状态
     *  @author 施磊
     *  @param $card_id 名片记录id
     *  @param $type 1  只修改to_del_status 2 只修改exchange_status 3 2个都修改
     */
    public function updateToDelStatusById($card_id, $type = 1) {
        $model = ORM::factory('Cardinfo', $card_id);

        //如果要更新状态 则更新为有效
        switch ($type) {
            case 1:
                $model->to_del_status = 0;
                break;
            case 2:
                $model->exchange_status = 1;
                $model->exchange_time = time();
                break;
           case 3:
               $model->to_del_status = 0;
               $model->exchange_status = 1;
               $model->exchange_time = time();
               break;
        }

        $model->update();
    }

    /**
     * sso
     * 企业递出名片
     * @author 钟涛
     * 2012.12.14
     */
    public function addOutCard($user_id, $data) {
        $model = ORM::factory('Cardinfo');
        // 我收到的名片
        //$receivedCard = $this->getReceiveCardListByTwoId($data['to_user_id'], $user_id);
        //发出的名片
        //$outCard = $this->getReceiveCardListByTwoId($user_id, $data['to_user_id']);
        //我收到的名片
        $receivedCard = $this->getReceiveNew(arr::get($data,'to_user_id'),$user_id,0);
        //发出的名片
        $outCard = $this->getReceiveNew($user_id, arr::get($data,'to_user_id'),0);
        if ($receivedCard->card_id || $outCard->card_id) {
            //如果已存在我投递过的记录
            if ($outCard['card_id']) {
                //如果互相已经交换过了并且状态正常 那还提交个毛啊
                if (!$outCard['from_del_status'] && $outCard['exchange_status'])
                    return $result = array('status' => 0);
                //如果我还未删除 更新累计次数 并且我对方没有提交
                $this->updateSendCountAndFromDelStatusById(intval($outCard['card_id']), TRUE);
            }else {
                //如果互相已经交换过了并且状态正常 那还提交个毛啊
                if (!$receivedCard['to_del_status'] && $receivedCard['exchange_status'])
                    return $result = array('status' => 0);
                //如果别人发我名片 我删掉了 现在又想发回去 肿么办？！do this
                $this->updateToDelStatusById(intval($receivedCard['card_id']), 3);
            }
            //更新当天发送名片次数
            $this->updateSendCardCountLog($user_id);
            //根据用户ID获取当天已经发送名片数据信息
            $sentcount=$this->getSendCardCountInfo($user_id);
            //当天还剩余发送名片数量
            $result['count'] = 30 - intval($sentcount['day_send_count']);
            $result['status'] = '100';
            return $result;
        }else {
            if($user_id && $data['to_user_id']){
                $cardinfo = array(
                        'from_user_id' => $user_id,
                        'to_user_id' => $data['to_user_id'],
                        'send_time' => time(),
                        'exchange_status' =>0,
                        'exchange_time' =>time(),
                        'to_project_id' => arr::get($data,'projectid',0),
                        'ip' =>ip2long( Request::$client_ip )
                );
                try{
                    $addresult=$model->values($cardinfo)->create();
                    //更新当天发送名片次数
                    $this->updateSendCardCountLog($user_id);
                    //根据用户ID获取当天已经发送名片数据信息
                    $sentcount=$this->getSendCardCountInfo($user_id);
                    //当天还剩余发送名片数量
                    $result['count'] = 30 - intval($sentcount['day_send_count']);
                    $result['status'] = '100';
                }
                catch(Kohana_Exception $e){
                    $result['status'] = '0';
                }
            }else{
                $result['status'] = '0';
            }
        }
        if($result['status']=='100'){//名片记录日志信息
            //sso 赵路生 2013-11-12
            $usertype = Service_Sso_Client::instance()->getUserInfoById($user_id)->user_type;
            $projectid=arr::get($data,'projectid',0);
            if($usertype==2){
                $otherusertype=1;
            }elseif($usertype==1){
                $otherusertype=2;
            }else{
                $otherusertype=0;
            }
            if(arr::get($data,'to_user_id') || arr::get($data,'projectid')){
                if($usertype==2){//个人递出必须有项目
                    if(arr::get($data,'projectid')){
                        //递出名片log
                        $this->addCardBehaviourInfo($user_id,arr::get($data,'to_user_id'),2,$usertype,arr::get($data,'projectid'),1);
                        //收到名片log
                        $this->addCardBehaviourInfo(arr::get($data,'to_user_id'),$user_id,3,$otherusertype,arr::get($data,'projectid'),1);
                    }
                }else{
                    //递出名片log
                    $this->addCardBehaviourInfo($user_id,arr::get($data,'to_user_id'),2,$usertype,arr::get($data,'projectid'));
                    //收到名片log
                    $this->addCardBehaviourInfo(arr::get($data,'to_user_id'),$user_id,3,$otherusertype,arr::get($data,'projectid'));
                }
            }
            if(isset($data['checked_val']) && $data['checked_val']){
                $this->addLetter($user_id, $data);//@赵路生
            }
        }
        return $result;
    }

    /**
     * 判断是否已经咨询过
     * @author 钟涛
     * 2013.07.24
     */
    public function justIsSend($user_id, $data) {
        //先判断当天是否已经咨询过了
        $today=strtotime(date('Y-m-d 00:00:00',time()));
        $ser_letter = ORM::factory('UserLetter')
                    ->where('user_id','=',$user_id)
                    ->where('to_user_id','=',arr::get($data,'to_user_id'))
                    ->where('to_project_id','=',arr::get($data,'projectid'))
                    ->where('letter_type','=',arr::get($data,'type'))
                    ->where('add_time','>=',$today)
                    ->find();
        if($ser_letter->id){//已经咨询
            return true;
        }else{//判断当天是否已经递送超过10次
            $ser_letter_count = ORM::factory('UserLetter')
            ->where('user_id','=',$user_id)
            ->where('add_time','>=',$today)
            ->count_all();
            return $ser_letter_count;
        }
    }

    /**
     * 个人递出名片[个人咨询、索要资料、发送意向]
     * @author 钟涛
     * 2013.07.24
     */
    public function addOutCardByPerson($user_id, $data) {
        //先判断当天是否已经咨询过了
        $issend=$this->justIsSend($user_id, $data);
        if($issend===true){//已经咨询过
            return false;
        }elseif($issend>=10){
            return false;
        }else{	}
        $model = ORM::factory('Cardinfo');
        //我收到的名片
        $receivedCard = $this->getReceiveNew(arr::get($data,'to_user_id'),$user_id,arr::get($data,'projectid'));
        //发出的名片
        $outCard = $this->getReceiveNew($user_id, arr::get($data,'to_user_id'),arr::get($data,'projectid'));
        if ($receivedCard->card_id || $outCard->card_id) {
            if ($outCard->card_id) {//我已经递出--走下面
                //重复递出名片
                $outCard->send_count=$outCard->send_count+1;
                $outCard->ip=ip2long( Request::$client_ip );//只记录个人用户ip
                $outCard->send_time=time();
                $outCard->from_del_status=0;
                $outCard->to_del_status=0;
                $outCard->to_project_id = arr::get($data,'projectid',0);
                $resultdata=$outCard->update();
                if(arr::get($data,'projectid')){
                    //递出名片log
                    $this->addCardBehaviourInfo($resultdata->from_user_id,$resultdata->to_user_id,2,2,arr::get($data,'projectid'),arr::get($data,'type',1));
                    //收到名片log
                    $this->addCardBehaviourInfo($resultdata->to_user_id,$resultdata->from_user_id,3,1,arr::get($data,'projectid'),arr::get($data,'type',1));
                }
            }elseif($receivedCard->card_id) {//我已经收到--走下面
                //交换名片
                $receivedCard->exchange_status=1;
                $receivedCard->exchange_time=time();
                $receivedCard->to_del_status=0;
                $receivedCard->from_del_status=0;
                $receivedCard->to_project_id = arr::get($data,'projectid',0);
                $receivedCard->ip=ip2long( Request::$client_ip );//只记录个人用户ip
                $resultdata=$receivedCard->update();
                if(arr::get($data,'to_user_id')){
                    $this->addCardBehaviourInfo($user_id,$data['to_user_id'],4);//交换名片log
                }
            }else{	}
        }else {//我没收到也没有递出--走下面
            if($user_id ){
                $cardinfo = array(
                        'from_user_id' => $user_id,
                        'to_user_id' => arr::get($data,'to_user_id'),
                        'to_project_id' => arr::get($data,'projectid'),
                        'send_time' => time(),
                        'exchange_status' =>0,
                        'ip' =>ip2long( Request::$client_ip ),
                        'exchange_time' =>time()
                );
                try{
                    $model->values($cardinfo)->create();
                    if(arr::get($data,'projectid')){
                        $this->addCardBehaviourInfo($user_id,$data['to_user_id'],2,2,arr::get($data,'projectid'),arr::get($data,'type',1));//递出名片log
                        $this->addCardBehaviourInfo($data['to_user_id'],$user_id,3,1,arr::get($data,'projectid'),arr::get($data,'type',1));//收到名片log
                    }
                }
                catch(Kohana_Exception $e){
                }
            }
        }
        //添加发信记录
        $ser_letter = ORM::factory('UserLetter');
        $ser_letter->user_id = $user_id;
        $ser_letter->to_user_id = intval($data['to_user_id']);
        if(arr::get($data,'content','')=='undefined'){
            $ser_letter_content='';
        }else{
            $ser_letter_content=arr::get($data,'content','');
        }
        $ser_letter->content=$ser_letter_content ? $ser_letter_content :'你们的项目很好，请速速联系我详谈';
        $ser_letter->to_project_id = arr::get($data,'projectid');
        $ser_letter->user_type = 2;//个人用户
        $ser_letter->letter_status = 1;
        $letter_type_this=arr::get($data,'type',1);//个人用户留言类型1:我要咨询；2:索要资料；3:发送意向
        $ser_letter->letter_type = $letter_type_this;
        $ser_letter->sid = arr::get($_COOKIE, 'Hm_lvqtz_sid','');//sid
        $ser_letter->fromdomain = arr::get($_COOKIE, 'Hm_lvqtz_refer','');//入口域名
        $ser_letter->campaignid = arr::get($_COOKIE, 'campaignid',0);//计划id
        $ser_letter->adgroupid = arr::get($_COOKIE, 'adgroupid',0);//单元id
        $ser_letter->keywordid = arr::get($_COOKIE, 'keywordid',0);;//关键词id
        $ser_letter->letter_ip = ip2long( Request::$client_ip );//ip
        $ser_letter->add_time = time();
        $creatrreslut=$ser_letter->create();

        if($creatrreslut->id){//创建企业自动回复内容
            $replycontent=common::letter_reply();
            $sendletter_default_content=common::sendlettercontent();
            //默认的回复内容
            //$contentid=rand(1, 18);
            //$defalutcontent=arr::get($replycontent,$contentid,'你想创业吗？您还在为没有合适的好项目苦愁？我们来帮您，欢迎来电咨询详情！');
            $defalutcontent='';
            $t_project=ORM::factory('Project',arr::get($data,'projectid'));
            if($t_project->project_brand_name){
                $t_projectname=$t_project->project_brand_name;
            }else{
                $t_projectname='';
            }
            if($letter_type_this==1){//1:我要咨询
                if($ser_letter_content==$sendletter_default_content['1']){
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟，负责'.$t_projectname.'加盟的招商经理会在48小时内与您联系。';
                }elseif($ser_letter_content==$sendletter_default_content['2']){
                    $defalutcontent='您好，感谢您对'.$t_projectname.'加盟的关注，我们会尽快把'.$t_projectname.'加盟的相关资料发到您的提供的邮箱里。';
                }elseif($ser_letter_content==$sendletter_default_content['3']){
                    $project_amount_type=$t_project->project_amount_type;
                    $monarr=common::moneyArr();
                    $defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们的'.$t_projectname.'加盟费用是'.arr::get($monarr,$project_amount_type,'5-10万').'左右。';
                }elseif($ser_letter_content==$sendletter_default_content['4']){
                    $defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们将尽快与您电话联系。';
                }else{	}
            }elseif($letter_type_this==2){//2:索要资料
                if($ser_letter_content==$sendletter_default_content['5']){
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟信息，我们会尽快将'.$t_projectname.'加盟资料发给您。';
                }elseif($ser_letter_content==$sendletter_default_content['6']){
                    $project_amount_type=$t_project->project_amount_type;
                    $monarr=common::moneyArr();
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟信息，'.$t_projectname.'加盟费用在'.arr::get($monarr,$project_amount_type,'5-10万').'左右。';
                }elseif($ser_letter_content==$sendletter_default_content['7']){
                    $defalutcontent='感谢您对'.$t_projectname.'加盟的关注，正在招商的项目'.$t_projectname.'加盟后期有很多支持措施，从管理、技术、营销策略、货源等都给予充分的支持。';
                }elseif($ser_letter_content==$sendletter_default_content['8']){
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟信息，负责'.$t_projectname.'加盟的客服代表会尽快与您联系。';
                }else{	}
            }else{//3:发送意向
                if($ser_letter_content==$sendletter_default_content['9']){
                    $defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们会尽快将'.$t_projectname.'加盟流程发到您提供的邮箱，我们会尽快与您联系。';
                }elseif($ser_letter_content==$sendletter_default_content['10']){
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟信息，加盟'.$t_projectname.'随时都可以的，谢谢关注。';
                }elseif($ser_letter_content==$sendletter_default_content['11']){
                    $defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们会尽快将 '.$t_projectname.'加盟流程发到您提供的邮箱，以便您更好的了解'.$t_projectname.'加盟信息。';
                }elseif($ser_letter_content==$sendletter_default_content['12']){
                    $defalutcontent='感谢您关注'.$t_projectname.'加盟信息，在上海可以加盟'.$t_projectname.'。';
                }else{	}
            }
            if($defalutcontent){
                $reply_letter = ORM::factory('UserLetterReply');
                $reply_letter->letter_id=$creatrreslut->id;
                $reply_letter->content=$defalutcontent;
                $reply_letter->reply_type=1;//自动回复
                $reply_letter->add_time=time()+rand(100, 86400);//回复时间 1天内随机时间
                $reply_letter->create();
            }
        }
        return true;
    }

    /**
     * 企业添加回复
     * @author 钟涛
     * 2013.12.19
     */
    public function addUserLetterReply($user_id, $to_user_id,$projectid,$content) {
        if(intval($user_id) && intval($to_user_id) && intval($to_user_id) && $content){
            $ser_letter = ORM::factory('UserLetter')
                        ->where('user_id','=',$user_id)//个人用户id
                        ->where('to_user_id','=',$to_user_id)//企业用户id
                        ->where('to_project_id','=',$projectid)
                        ->where('user_type','=',2)->find();
            if($ser_letter->id){
                $reply_letter = ORM::factory('UserLetterReply');
                $replydata=$reply_letter->where('letter_id','=',$ser_letter->id)->find();
                if($replydata->id){//修改
                    $replydata->content=$content;
                    $replydata->reply_type=2;//企业回复
                    $replydata->add_time=time();//回复时间
                    $replydata->update();
                }else{
                    $reply_letter->letter_id=$ser_letter->id;
                    $reply_letter->content=$content;
                    $reply_letter->reply_type=2;//企业回复
                    $reply_letter->add_time=time();//回复时间
                    $reply_letter->create();
                }
            }
        }
    }

    /**
     * 个人递出名片[快速注册]
     * @author 钟涛
     * 2013.08.1
     */
    public function addOutCardQuickRegister($user_id, $data) {
        $postidarr=explode("_",arr::get($data,'projectid'));
        if(isset($postidarr[0])){
            $proid=$postidarr[0];
        }else{
            $proid=arr::get($data,'projectid');
        }
        $model = ORM::factory('Cardinfo');
        if($user_id ){
            $cardinfo = array(
                    'from_user_id' => $user_id,
                    'to_user_id' => arr::get($data,'to_user_id'),
                    'to_project_id' => $proid,
                    'send_time' => time(),
                    'exchange_status' =>0,
                    'ip' =>ip2long( Request::$client_ip ),
                    'exchange_time' =>time()
                );
                try{
                    $model->values($cardinfo)->create();
                    if($proid){
                        $this->addCardBehaviourInfo($user_id,$data['to_user_id'],2,2,$proid,arr::get($data,'type',1));//递出名片log
                        $this->addCardBehaviourInfo($data['to_user_id'],$user_id,3,1,$proid,arr::get($data,'type',1));//收到名片log
                    }
                }
            catch(Kohana_Exception $e){
            }
        }
        //添加发信记录
        $ser_letter = ORM::factory('UserLetter');
        $ser_letter->user_id = $user_id;
        $ser_letter->to_user_id = intval($data['to_user_id']);
        if(arr::get($data,'content','')=='undefined'){
            $ser_letter->content = '';
        }else{
            $ser_letter->content = arr::get($data,'content','');
        }
        $ser_letter->to_project_id = $proid;
        $ser_letter->user_type = 2;//个人用户
        $ser_letter->letter_status = 1;
        $ser_letter->letter_type = arr::get($data,'type',1);
        $ser_letter->add_time = time();
        $ser_letter->sid = arr::get($_COOKIE, 'Hm_lvqtz_sid','');//sid
        $ser_letter->fromdomain = arr::get($_COOKIE, 'Hm_lvqtz_refer','');//入口域名
        $ser_letter->campaignid = arr::get($_COOKIE, 'campaignid',0);//计划id
        $ser_letter->adgroupid = arr::get($_COOKIE, 'adgroupid',0);//单元id
        $ser_letter->keywordid = arr::get($_COOKIE, 'keywordid',0);//关键词id
        $ser_letter->letter_ip = ip2long( Request::$client_ip );//ip
        $ser_letter->create();
        return true;
    }

    /**
     * 企业点击交换名片时 ：更新当前名片状态为已交换
     * @author 钟涛
     * 2012.11.27
     */
    public function editReceiveCard($data,$user_id){
        $model = ORM::factory('Cardinfo');
        $count = $model->where('card_id','=',$data['cardid'])->find()->as_array();
        if ($count['exchange_status']==0){
            try{
                //更新名片已交换的状态
                $model->exchange_status = 1;
                $model->exchange_time = time();
                //更新当前用户为已读的状态
                $model->to_read_status = 1;
                $model->to_read_time = time();
                //更新递出名片用户为未读的状态
                $model->from_read_status = 0;
                $model->update();

                //交换名片的同时新增一条递出的动作
                if($count['to_user_id'] && $count['from_user_id']){
                    $model2 = ORM::factory('Cardinfo');
                    $results = $model2->where('from_user_id','=',$count['to_user_id'])
                                ->where('to_user_id','=',$count['from_user_id'])
                                ->find();
                    if($results->loaded()){//已经存在 就修改
                        $results->exchange_status=1;
                        $results->exchange_time=time();
                        $results->from_del_status=0;
                        $results->send_time=time();
                        $results->send_count=$results->send_count+1;
                        $results->update();
                    }else{//不存在添加一条递出的名片
                        $results->to_user_id=$count['from_user_id'];
                        $results->from_user_id=$count['to_user_id'];
                        $results->from_del_status=0;
                        $results->exchange_status=1;
                        $results->exchange_time=time();
                        $results->from_del_status=0;
                        $results->send_time=time();
                        $results->send_count=1;
                        $results->create();
                    }
                }
                $result['status'] = '100';
                $result['user_name']='';
                //更新当天发送名片次数
                //$this->updateSendCardCountLog($user_id);
                //根据用户ID获取当天已经发送名片数据信息
                //$sentcount=$this->getSendCardCountInfo($user_id);
                //当天还剩余发送名片数量
                //$result['count'] = 30 - intval($sentcount['day_send_count']);
            }
            catch(Kohana_Exception $e){
                $result['status'] = '0';
            }
        }
        if($result['status']=='100'){//名片记录日志信息
            $this->addCardBehaviourInfo($user_id,$data['to_user_id'],4);//交换名片log
            if(isset($data['checked_val']) && $data['checked_val'] && $data['checked_val']!='undefined'){
                $this->addLetter($user_id, $data); //发信
            }
            //企业回复个人留言
            $this->addUserLetterReply($data['to_user_id'],$user_id,$data['to_project_id'],$data['checked_val']);
        }
        return $result;
    }

    /**
     * 个人点击交换名片时 ：更新当前名片状态为已交换
     * @author 钟涛
     * 2013.02.25
     */
    public function editReceiveCardCom($data,$user_id){
        $model = ORM::factory('Cardinfo');
        $count = $model->where('card_id','=',$data['cardid'])->find()->as_array();
        if ($count['exchange_status']==0){
            try{
                //更新名片已交换的状态
                $model->exchange_status = 1;
                $model->exchange_time = time();
                //更新当前用户为已读的状态
                $model->to_read_status = 1;
                $model->to_read_time = time();
                //更新递出名片用户为未读的状态
                $model->from_read_status = 0;
                $model->ip = ip2long( Request::$client_ip );
                $model->update();
                $result['status'] = '100';
            }
            catch(Kohana_Exception $e){
                $result['status'] = '0';
            }
        }
        if($result['status']=='100'){//名片记录日志信息
            $this->addCardBehaviourInfo($user_id,$data['to_user_id'],4);//交换名片log
        }
        return $result;
    }

    /**
     * 根据名片id数组获取未交换名片数量
     * @author 钟涛
     * 2012.12.19
     */
    public function getBatchReceiveCardCount($cardidarr){
        if(!empty($cardidarr)){
            return ORM::factory('Cardinfo')
            ->where('card_id','in',$cardidarr)//名片id
            ->where('exchange_status','=',0)//未交换状态
            ->where('to_del_status','=',0)//未删除状态
            ->count_all();
        }
        else{
            return 0;
        }
    }

    /**
     * 我收到的名片：根据名片id数组获取未读名片数量
     * @author 钟涛
     * 2012.12.19
     */
    public function getBatchReceiveReadCardCount($cardidarr){
        if(!empty($cardidarr)){
            return ORM::factory('Cardinfo')
            ->where('card_id','in',$cardidarr)//名片id
            ->where('to_read_status','=',0)//未读状态
            ->where('to_del_status','=',0)//未删除状态
            ->count_all();
        }
        else{
            return 0;
        }
    }

    /**
     * 我递出的名片：根据名片id数组获取未读名片数量
     * @author 钟涛
     * 2012.12.19
     */
    public function getBatchOutReadCardCount($cardidarr){
        if(!empty($cardidarr)){
            return ORM::factory('Cardinfo')
            ->where('card_id','in',$cardidarr)//名片id
            ->where('from_read_status','=',0)//未读状态
            ->where('from_del_status','=',0)//未删除状态
            ->where('exchange_status','=',1)//已交换状态
            ->count_all();
        }
        else{
            return 0;
        }
    }

    /**
     * 批量更新更新当前名片状态为已交换
     * @author 钟涛
     * 2012.12.18
     */
    public function editBatchReceiveCard($cardidarr,$user_id){
        if(!empty($cardidarr)){
            foreach($cardidarr as $cardid){
                $model = ORM::factory('Cardinfo',$cardid);
                if ($model->exchange_status==0){
                    //更新名片是否已交换的状态
                    $model->exchange_status = 1;
                    $model->exchange_time = time();
                    //更新名片为已读的状态
                    $model->to_read_status = 1;
                    $model->to_read_time = time();
                    if($model->loaded()){
                        $model->update();
                        //$this->updateSendCardCountLog($user_id);
                        $otheruserid=$this->getOtherUserID($cardid,$user_id);
                        if($otheruserid){
                            $this->addCardBehaviourInfo($user_id,$otheruserid,4);//交换名片log
                        }
                    }
                }
            }
        }
    }

    /**
     * 更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function updateReceiveDelStatus($cardid){
        $model= ORM::factory('Cardinfo',$cardid);
        //接收者删除名片记录--1为已删除
        $model->to_del_status =1;
        //接收者删除名片时间
        $model->to_del_time =time();
        if($model->save()) {
            //$this->addCardBehaviourInfo($model->to_user_id,$model->from_user_id,8);//删除名片log
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 批量更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function updateBatchReceiveDelStatus($cardidarr){
        if(!empty($cardidarr)){
            foreach($cardidarr as $cardid){
                $model= ORM::factory('Cardinfo',$cardid);
                //接收者删除名片记录--1为已删除
                $model->to_del_status =1;
                //接收者删除名片时间
                $model->to_del_time =time();
                if($model->loaded()){
                    //$this->addCardBehaviourInfo($model->to_user_id,$model->from_user_id,8);//删除名片log
                    $model->update();
                }
            }
        }
    }

    /**
     * 更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function updateOutDelStatus($cardid){
        $model= ORM::factory('Cardinfo',$cardid);
        //发送者删除名片记录--1为已删除
        $model->from_del_status = 1;
        //发送者删除名片时间
        $model->from_del_time =time();
        if($model->save()) {
            //$this->addCardBehaviourInfo($model->from_user_id,$model->to_user_id,8);//删除名片log
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 批量更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function updateBatchOutDelStatus($cardidarr){
        if(!empty($cardidarr)){
            foreach($cardidarr as $cardid){
                $model= ORM::factory('Cardinfo',$cardid);
                //发送者删除名片记录--1为已删除
                $model->from_del_status = 1;
                //发送者删除名片时间
                $model->from_del_time =time();
                if($model->loaded()){
                    //$this->addCardBehaviourInfo($model->from_user_id,$model->to_user_id,8);//删除名片log
                    $model->update();
                }
            }
        }
    }

    /**
     * 更改我收到的名片状态为已读
     * @author 钟涛
     */
    public function updateReceCardReadStatus($cardid){
    	try {
	        $tab = ORM::factory('Cardinfo');
	        $count = $tab->where('card_id','=',$cardid)->find()->as_array();
	        if ($count['to_read_status']==0){
	            $tab->to_read_status = 1;
	            $tab->to_read_time = time();
	            $tab->update();
	        }
    	}
    	catch(Exception $e)
    	{
    		return false;
    	}
    }

    /**
     * 我收到的交换的名片数量(不包含用户删除的)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getReceivedExchageCardCountByTwoId($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $per_user_id)//发送用户
        ->where('to_user_id','=',$user_id)//接收用户
        ->where('to_del_status','=',0)//未删除的
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 我收到的交换的名片数量(包含用户删除的)
     * @param  [int] $per_user_id 接收用户
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getReceivedExchageCardCountByTwoIdAll($per_user_id,$user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $per_user_id)//发送用户
        ->where('to_user_id','=',$user_id)//接收用户
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 企业\个人用户获取我收到的名片总数
     * @author 钟涛
     */
    public function getReceiveCardCount($user_id){
        return ORM::factory('Cardinfo')
        ->where('to_user_id','=',$user_id)//接收用户为当前登录用户id
        ->where('to_del_status','=',0)//未删除状态
        ->count_all();
    }

    /**
     * 企业\个人用户获取我收到的名片总数[包括删除的]
     * @author 钟涛
     */
    public function getReceiveCardCountAll($user_id){
        if(intval($user_id) && $user_id){
            return ORM::factory('Cardinfo')
            ->where('to_user_id','=',$user_id)//接收用户为当前登录用户id
            ->count_all();
        }else{
            return 0;
        }
    }

    /**
     * 项目获取申请加盟者的数量
     * @author 钟涛
     */
    public function getJiamengCountAll($projectid){
        if(intval($projectid) && $projectid){
            return ORM::factory('Cardinfo')
            ->where('to_project_id','=',$projectid)
            ->count_all();
        }else{
            return 0;
        }
    }

    /**
     * 根据项目id获取收到名片数量
     * @param int $projectid 项目id
     * @param int $day 时间[单位为天，如-1为昨天，0表示今天，30表示近30天收到的名片数量]
     * @author 钟涛
     */
    public function getCardCountByProject($projectid=0,$day=0){
        if(intval($projectid) && $projectid){
            $cardcont=ORM::factory('Cardinfo')->where('to_project_id','=',$projectid);
            if($day==-1){//昨天收到的名片数量
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()-86400));//昨天
                $nowtime2=strtotime(date('Y-m-d 00:00:00', time()));//当天零点时间
                $cardcont->where('send_time','<',$nowtime2);
            }elseif($day==30){//近30天收到的名片数量
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()-86400*30));//近30天
            }else{//默认今天收到的名片数量
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()));//当天零点时间
            }
            $cardcont->where('send_time','>=',$nowtime);
            return $cardcont->count_all();
        }else{
            return 0;
        }
    }

    /**
     * 企业\个人用户根据时间段获取名片数量
     * @author 龚湧
     * @param int $user_id 用户id
     * @param int $time 上次登录时间
     * @return integer
     */
    public function getReceiveCardFromTime($user_id,$time=0){
        $log = ORM::factory('Cardinfo')
        ->where('to_user_id','=',$user_id)//接收用户为当前登录用户id
        ->where('to_del_status','=',0)//未删除状态
        ->group_by('from_user_id');//去除重复的
        if($time){
            $result= $log
            ->where('send_time','>',$time)
            ->find_all();
        }
        else{
             $result=  $log
            ->find_all();
        }
        return count($result);
    }

    /**
     * 企业\个人用户获取我收到的新的投资者名片数量
     * @author 钟涛
     */
    public function getReceiveCardNewCount($user_id){
        $resultinfo= ORM::factory('Cardinfo')
        ->where('to_user_id','=',$user_id)//接收用户为当前登录用户id
        ->where('to_read_status','=',0)//未读状态
        ->where('to_del_status','=',0)//未删除状态
        ->group_by('from_user_id')//去除重复用户
        ->find_all();
        return count($resultinfo);
    }

    /**
     * 企业\个人用户获取我递出名片总数
     * @author 钟涛
     */
    public function getOutCardCount($user_id){
        $resultinfo= ORM::factory('Cardinfo')
        ->where('from_user_id','=',$user_id)//发送用户为当前登录用户id
        ->where('from_del_status','=',0)//未删除状态
        ->group_by('to_user_id')//去除重复用户
        ->find_all();
        return count($resultinfo);
    }

    /**
     * 企业\个人用户获取交换的的新的投资者名片数量
     * @author 钟涛
     */
    public function getExchangeCardNewCount($user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id','=',$user_id)//发送用户为当前登录用户id
        ->where('from_read_status','=',0)//未读状态
        ->where('from_del_status','=',0)//未删除状态
        ->where('exchange_status','=',1)//已经交换的(仅当我递出的被交换后才会出现未读的状态)
        ->count_all();
    }

    /**
     * 我收到的名片总数
     * @author 钟涛
     */
    public function getToCardCount($user_id){
        if($user_id){
            return ORM::factory('Cardinfo')
            ->where('to_user_id','=',$user_id)//接收用户为当前登录用户id
            ->count_all();
        }
        return 0;
    }

    /**
     *获取我收藏名片总数
     * @author 钟涛
     */
    public function getFavoriteCardCount($user_id){
        return ORM::factory('Favorite')
        ->where('user_id','=',$user_id)//发送用户为当前登录用户id
        ->where('favorite_status','=',1)//未删除状态
        ->count_all();
    }

    /**
     * 企业\个人用户获取 企业名片上面勾选的项目信息
     * @author 钟涛
     */
    public function getProjectByCompanyCard($data){
        $pro_service =new Service_User_Company_Project();
        //企业名片上面显示的logo
        $logo=0;//默认显示企业logo
        if($pro_service->isHasProjectById($data['logo'])){
            $logo = $data['logo'];
        }else{
            $logo = 0;//默认显示企业logo
        }
        //print_r(unserialize($data['brand']));
        $brand = $this->isHasCardComplete(unserialize($data['brand']));
        return array(
            'logo' => $logo,
            'brand' =>$brand,
        );
    }//funtion end

    /**
     * 根据项目ID判断项目是否已经存在(存在返回项目信息，不存在返回false)
     * @author 钟涛
     */
    public function isHasCardComplete($brand){
        $pro_service =new Service_User_Company_Project();
        if($brand){
            foreach ($brand as $k=>$j){
                if (!$pro_service->isHasProjectById($j)){
                    unset($brand[$k]);
                }
            }
            $brand = count($brand)==0 ? FALSE :$brand;
        }
        return $brand;
    }//funtion end

    /**
     * 处理封装因为展示需要把名片的电话号码+号改为-
     * @author 施磊
     */
    public function checkComPhone($comPhone = '') {
        if(!$comPhone) return $comPhone;

        //因为分机号数据里是用+号连接的 但是展示需要-
        $comPhoneArr = explode('+', $comPhone);

        //检查是否没有分机号
        if(isset($comPhoneArr[1])) {
            return $comPhoneArr[0].'-'.$comPhoneArr[1];
        }else{
            return $comPhoneArr[0];
        }
    }

    /**
     * 递出名片时根据用户ID更新当天发送名片次数(最多每天发送30次名片[包括交换])
     * @author 钟涛
     */
    public function updateSendCardCountLog($user_id){
        $data=ORM::factory('Cardsendcoutlog');
        $result=$data->select('*')->where('user_id','=',$user_id)->find()->as_array();
        if($result['user_id']!=""){//更新发送次数
            if(date("Ymd")>$result['last_sent_time']){
                $data->day_send_count = 1; //过了当天 即发送次数归1[当天又可以发送30次]
            }else{
                $data->day_send_count = $result['day_send_count']+1;
            }
            $data->last_sent_time = date("Ymd");
            $data->update();
        }else{//添加新的一条记录信息
            $data->user_id = $user_id;
            $data->day_send_count = 1;
            $data->last_sent_time = date("Ymd");
            $data->create();
        }
    }//funtion end

    /**
     * 根据用户ID获取当天已经发送名片数据信息
     * @author 钟涛
     */
    public function getSendCardCountInfo($user_id){
        $data=ORM::factory('Cardsendcoutlog');
        $result=$data->select('*')->where('user_id','=',$user_id)->find()->as_array();
        return $result;
    }//funtion end

    /**
     * 记录个人用户被企业所查看限制【30天内只能被5个企业所查看】
     * @author 钟涛
     */
    public function updateViewCardLog($user_id,$com_user_id){
        $data=ORM::factory('Cardviewcoutlog');
        $result=$data->select('*')->where('user_id','=',$user_id)->find()->as_array();
        if($result['user_id']!=""){//更新数据
            $oldtime=$result['first_add_time'] + (30 * 24 * 60 * 60);
            if($oldtime<time()){//已经超过30天，重新记录
                $data->view_notes = $com_user_id;
                $data->first_add_time = time();
            }else{//30天之内添加用户信息
                $data->view_notes = $result['view_notes'].'|'.$com_user_id;
            }
            $data->update();
        }else{//添加新的一条记录信息
            if($user_id && $com_user_id){
                $data->user_id = $user_id;
                $data->view_notes = $com_user_id;
                $data->first_add_time = time();
                $data->create();
            }
        }
    }//funtion end

    /**
     * 记录企业免费查看个人名片log【暂时1天最多只可免费查看1张名片】
     * 2013-06-08
     * @author 钟涛
     */
    public function updateFreeViewCardLog($user_id,$sec_user_id){
        $data=ORM::factory('Cardfreeviewcardlog');
        $result=$data->where('user_id','=',$user_id)->where('sec_user_id','=',$sec_user_id)->find();
        if($result->loaded()){//更新数据
             $result->add_time = time();//更新查看时间
             $result->update();
        }else{//添加新的一条记录信息
            if($user_id && $sec_user_id){
                $data->user_id = $user_id;
                $data->sec_user_id = $sec_user_id;
                $data->add_time = time();
                $data->create();
            }
        }
    }//funtion end

    /**
     * 获取当天已经免费查看名片的数量
     * 2013-06-08
     * @author 钟涛
     */
    public function getFreeViewCardLog($user_id){
        $data=ORM::factory('Cardfreeviewcardlog');
        $nowtime=strtotime(date('Y-m-d 00:00:00', time()));//当天零点时间
        $result=$data->where('user_id','=',$user_id)->where('add_time','>=',$nowtime)->count_all();
        return $result;
    }//funtion end

    /**
     * 获取留言数量
     * 2013-07-12
     * @author 钟涛
     */
    public function getUserLetterCount($user_id,$to_user_id){
        $data=ORM::factory('UserLetter');
        return $data->where('user_id','=',$user_id)
               ->where('to_user_id','=',$to_user_id)
               ->where('letter_status','=',1)
               ->where('content','!=','')->count_all();
    }//funtion end

    /**
     * 获取留言内容
     * 2013-07-12
     * @author 钟涛
     */
    public function getUserLetter($user_id,$to_user_id){
        $data=ORM::factory('UserLetter');
        return $data->where('user_id','=',$user_id)
        ->where('to_user_id','=',$to_user_id)->where('letter_status','=',1)->where('content','!=','')->find_all();
    }//funtion end

    /**
     * 根据用户ID获取用户名片被查看信息
     * @author 钟涛
     */
    public function getViewCardInfo($user_id){
        $data=ORM::factory('Cardviewcoutlog');
        $result=$data->select('*')->where('user_id','=',$user_id)->find()->as_array();
        return $result;
    }//funtion end


   /**
    * 时间段内获取收到的名片数量
    * @author 龚湧
    * @param int $user_id
    * @param int $from_time
    * @param int $to_time
    * @return int $count
    */
   public function getReceivedCardCountByTimeSlice($user_id,$from_time,$to_time){
        $card = ORM::factory('Cardinfo');
        $count = $card->where("to_user_id", "=", $user_id)
                      ->where("send_time",">=",$from_time)
                      ->where("send_time","<",$to_time)
                      ->where("to_del_status","=","0")
                      ->count_all();
        return $count;
   }

   /**
    * 时间段内获取交换名片的数量
    * @author 龚湧
    * @param int $user_id
    * @param int $from_time
    * @param int $to_time
    * @return array $count
    */
   public function getExchangeCardCountByTimeSlice($user_id,$from_time,$to_time){
        $card = ORM::factory('Cardinfo');
        $cards = $card->where("from_user_id", "=", $user_id)
                ->where("exchange_status","=",1)
                ->where("exchange_time",">=",$from_time)
                ->where("exchange_time","<",$to_time)
                ->where("from_del_status","=","0")
                ->order_by("exchange_time","desc")
                ->find_all();
        $count = $cards->count();
        return array(
            'cards' => $cards,
            'count' => $count
        );
   }

   /**
    * 根据当前用户id和个人用户id 判断个人用户是否有主动给我递送名片行为
    * @author 钟涛
    */
   public function getIsHasbehaviour($user_id,$sec_userid){
       //判断是否已经收到
       $data=ORM::factory('Cardinfo')
              ->where('from_user_id','=',$sec_userid)
              ->where('to_user_id','=',$user_id)
           ->count_all();
       //判断是否已经递出并且交换
       $data2=ORM::factory('Cardinfo')
               ->where('to_user_id','=',$sec_userid)
               ->where('from_user_id','=',$user_id)
               ->where('exchange_status','=',1)
               ->count_all();
       if($data>0 || $data2>0){
           return true;
       }else{
           return false;
       }
   }

   /**
    * sso
    * 添加名片记录日志表
    * @author 钟涛
    */
   public function addCardBehaviourInfo($user_id,$second_user_id,$status,$user_type=null,$projectid=0,$send_type=0){
       $data=ORM::factory('Cardinfobehaviour');
       if($user_type==null){
           //sso 赵路生 2013-11-12
           //$user =ORM::factory('User',$user_id);
              $user = Service_Sso_Client::instance()->getUserInfoById($user_id);
           $user_type=$user->user_type;
       }
       $datainfo = array(
               'user_id' => $user_id,
               'user_type' => $user_type,
               'second_user_id' => $second_user_id,
               'status' => $status,
               'project_id' => $projectid,
               'send_type' => $send_type,
               'add_time' => time()
       );
       if($status==2 && $user_type==2){//个人递出
               $statusgo=true;
               $issend=$this->getIsSendCardCount($user_id); //个人当天是否递出名片
               if($issend==0){//当天第一次递送时添加抽奖次数
                   $userser=new Service_User();
                   //添加抽奖次数
                   $userser->changeUserHuodongChance(1,$user_id,2);
               }
       }elseif($status==3 && $user_type==1){//企业收到
            $statusgo=true;
       }else{
             $statusgo=false;
       }
       if( $statusgo || ($second_user_id >0 && $user_id>0)){
           try{
               $data->values($datainfo)->create();
               $os=array(2,4);//个人递送与交换名片时添加活跃度
               if($user_type==2 && in_array($status,$os)){//个人用户操作的
                    $ser1=new Service_User_Person_Points();
                    $ser1->addPoints($user_id, 'send_card');//递送名片
                    //--------消息系统
                    //企业中心添加系统消息
                    $msg_service = new Service_User_Ucmsg();
                    $userinfo=$user = ORM::factory('Personinfo')->where('per_user_id', '=', $user_id)->find();

                    if($second_user_id){//企业收到短信
                        //发送短信
                        $qiye=$user  = Service_Sso_Client::instance()->getUserInfoById($second_user_id);
                        $qiyemobile=$qiye->mobile;
                        if($qiyemobile){
                            $companyinfo = ORM::factory('Companyinfo')->where('com_user_id','=',$second_user_id)->find();
                            $smsg = Smsg::instance();
                            $smsg->register(
                                    'mobile_company_receive_newcard',//企业收到名片短信提醒
                                    Smsg::MOBILE,//类型
                                    array(
                                            "receiver"=>$qiyemobile
                                    ),
                                    array(
                                            "companyname"=>$companyinfo->com_name,
                                            "personname"=>$userinfo->per_realname
                                    )
                            );
                        }
                    }
                    if($status==2 && $projectid){//递送名片
                    	
                    	// 这里添加调用好项目递送名片的方法 @赵路生 2014-5-7
                    	$this->addCardBehaviourInfoForSpePro($user_id, $projectid,1);
                    	
                        //企业中心系统消息[收到了一个新名片]
                        $projectname=ORM::factory('Project',$projectid)->project_brand_name;
                        $msgcontent='<a style="color: #0E71B4;" target="_blank" href="'.URL::website("platform/SearchInvestor/showInvestorProfile").'?userid='.$user_id.'">'.$userinfo->per_realname.'</a>投资者给您的'.$projectname.'项目递送了名片';

                        //$msg_service->pushMsg($second_user_id, "company_receive_card", $msgcontent,URL::website("company/member/account/cardservice"));
                        $smsg = Smsg::instance();
                        //内部消息发送
                        $smsg->register(
                                "tip_company_receive_card",//企业收到的名片
                                Smsg::TIP,//类型
                                array(
                                        "to_user_id"=>$second_user_id,
                                        "msg_type_name"=>"company_receive_card",
                                        "to_url"=>URL::website("company/member/card/receivecard")
                                ),
                                array(
                                        "url"=>URL::website("platform/SearchInvestor/showInvestorProfile").'?userid='.$user_id,
                                        "name"=>$userinfo->per_realname,
                                        "pname"=>$projectname

                                )
                        );

                    }elseif($status==4){//交换名片
                        //企业中心系统消息[收到了一个新的交换名片]
                        $msgcontent='<a style="color: #0E71B4;" target="_blank" href="'.URL::website("platform/SearchInvestor/showInvestorProfile").'?userid='.$user_id.'">'.$userinfo->per_realname.'</a>投资者与您交换了名片';

                        //$msg_service->pushMsg($second_user_id, "company_exchange_card", $msgcontent,URL::website("company/member/account/cardservice"));
                        $smsg = Smsg::instance();
                        //内部消息发送
                        $smsg->register(
                                "tip_company_exchange_card",//企业收到交换的名片
                                Smsg::TIP,//类型
                                array(
                                        "to_user_id"=>$second_user_id,
                                        "msg_type_name"=>"company_exchange_card",
                                        "to_url"=>URL::website("company/member/card/outcard")
                                ),
                                array(
                                        "url"=>URL::website("platform/SearchInvestor/showInvestorProfile").'?userid='.$user_id,
                                        "name"=>$userinfo->per_realname
                                )
                        );

                    }else{	}                   
               }elseif($user_type==1 && in_array($status,$os)){//企业用户操作的
                      //--------消息系统
                       //个人中心添加系统消息
                       $msg_service = new Service_User_Ucmsg();
                    $companyinfo = ORM::factory('Companyinfo')->where('com_user_id','=',$user_id)->find();
                    $projectinfo=ORM::factory('Project')->where('com_id','=',$companyinfo->com_id)->where('project_status','=',2)->find();
                       if($status==2){//递送名片
                           //个人中心系统消息[收到了一个新名片]
                           if($projectinfo->project_id){//此公司有项目
                               $msgcontent='<a style="color: #0E71B4;" target="_blank" href="'.URL::website("").'/gongsi/'.$projectinfo->project_id.'.html">'.$companyinfo->com_name.'</a>给您递送了名片';
                           }else{//没有项目
                               $msgcontent=$companyinfo->com_name.'给您递送了名片';
                           }
                           //$msg_service->pushMsg($second_user_id, "person_receive_card", $msgcontent,URL::website("person/member/card/receivecard"));
                           $smsg = Smsg::instance();
                           //内部消息发送
                           $smsg->register(
                                   "tip_person_receive_card",//企业收到的名片
                                   Smsg::TIP,//类型
                                   array(
                                           "to_user_id"=>$second_user_id,
                                           "msg_type_name"=>"person_receive_card",
                                           "to_url"=>URL::website("person/member/card/receivecard")
                                   ),
                                   array(
                                           "name"=>$companyinfo->com_name
                                   )
                           );

                       }elseif($status==4){//交换名片
                           //个人中心系统消息[收到了一个新的交换名片]
                           if($projectinfo->project_id){//此公司有项目
                               $msgcontent='<a style="color: #0E71B4;" target="_blank" href="'.URL::website("").'/gongsi/'.$projectinfo->project_id.'.html">'.$companyinfo->com_name.'</a>与您交换了名片';
                           }else{//没有项目
                               $msgcontent=$companyinfo->com_name.'与您交换了名片';
                           }
                           //$msg_service->pushMsg($second_user_id, "person_exchange_card", $msgcontent,URL::website("person/member/card/outcard"));
                           $smsg = Smsg::instance();
                           //内部消息发送
                           $smsg->register(
                                   "tip_person_exchange_card",//企业与个人交换名片
                                   Smsg::TIP,//类型
                                   array(
                                           "to_user_id"=>$second_user_id,
                                           "msg_type_name"=>"person_exchange_card",
                                           "to_url"=>URL::website("person/member/card/outcard")
                                   ),
                                   array(
                                           "name"=>$companyinfo->com_name
                                   )
                           );

                       }else{	}
               }
               else{	}
           }catch (Kohana_Exception $e){
               throw $e;
           }
       }
   }//funtion end

   /**
    * 获取名片日志表数据，返回是否已经查看过该用户
    * @author 钟涛
    */
   public function getCardinfoCountByid($user_id,$sec_userid){
           $data=ORM::factory('Cardinfobehaviour');
           $count = $data->where("user_id", "=", $user_id)
                   ->where("second_user_id","=",$sec_userid)
                   ->where("status","in",array(11,13))
                   ->count_all();
           return $count;
   }//funtion end

   /**
    * 获取名片日志表数据，返回是否已经查看过该用户或者已经[当天]免费查看过该同学(注意仅限当天)
    * @author 钟涛
    */
   public function getCardinfoCountByidNew($user_id,$sec_userid){
           $data=ORM::factory('Cardinfobehaviour');
           $count1 = $data->where("user_id", "=", $user_id)
           ->where("second_user_id","=",$sec_userid)
           ->where("status","=",11)
           ->count_all();
           $count2 = $data->where("user_id", "=", $user_id)
           ->where("second_user_id","=",$sec_userid)
           ->where("status","=",13)
           ->where("add_time",">=",strtotime(date("Y-m-d")))//(注意仅限当天)
           ->count_all();
           return $count1+$count2;
   }//funtion end

   /**
    * 获取免费查看过该同学(注意仅限当天)
    * @author 钟涛
    */
   public function getCardinfoCountByidNew2($user_id,$sec_userid){
       $data=ORM::factory('Cardinfobehaviour');
       $count = $data->where("user_id", "=", $user_id)
       ->where("second_user_id","=",$sec_userid)
       ->where("status","=",13)
       ->where("add_time",">=",strtotime(date("Y-m-d")))//(注意仅限当天)
       ->count_all();
       return $count;
   }//funtion end

   /**
    * 获取名片日志表当前递送名片数量
    * @author 钟涛
    */
   public function getIsSendCardCount($user_id=0){
           $today=strtotime(date('Y-m-d 00:00:00',time()));
        if(intval($user_id) && $user_id){
               $count=ORM::factory('Cardinfobehaviour')
                    ->where("user_id", "=", $user_id)
                    ->where("status", "=", 2)//递送名片
                    ->where("add_time", ">=", $today)//今天递出的名片
                    ->count_all();
               return $count;
        }else{
            return 0;
        }
   }//funtion end

   /**
    * 判断企业用户是否有权限操作此名片[查看、递出、重复递出](仅对个人名片设置为对意向投资行业公开的)
    * @author 钟涛
    */
   public function isHasPowerSeeCard($user_id,$sec_userid){
        $permodel= ORM::factory('Personinfo')->where('per_user_id','=',$sec_userid)->find();
        if($permodel->per_open_stutas==2){//公开度:意向投资行业用户
            //获取企业一级行业
            $arrProjectIndusty = ORM::factory('ProjectSearchCard')->where('user_id','=',$user_id)->where('project_status','=',2)->find_all();
            $returnProjectIndustyarr=array();//所有1级行业
            $returnProjectIndustyarr2=array();//所有2级行业
            foreach ($arrProjectIndusty as $v){
                $returnProjectIndustyarr[] = $v->parent_id;
                $returnProjectIndustyarr2[]=$v->project_industry_id;
            }
            //用户投资行业
            $this_perindustrymodel= ORM::factory('UserPerIndustry')->where('user_id','=',$sec_userid)->find_all();
            $t=1;$t_v='';
            //获取个人意向投资行业
            foreach ($this_perindustrymodel as $this_v){
                $per_i[$t]=$this_v->industry_id;
                $t++;
            }
            if(isset($per_i[1]) && isset($per_i[2]) && $per_i[1]>$per_i[2]){//有2级行业 获取较大的值为2级行业id
                $t_v=$per_i[1];
            }elseif(isset($per_i[1]) && isset($per_i[2]) && $per_i[1]<$per_i[2]){//有2级行业 获取较大的值为2级行业id
                $t_v=$per_i[2];
            }elseif(isset($per_i[1])){//没有2级行业
                $t_v=$per_i[1];
            }else{}
            //先判断2级
            if($t_v<8){//没有2级行业
                if(in_array($t_v, $returnProjectIndustyarr)){
                    return true;
                }else{
                    return false;//没有权限
                }
            }else{//有2级行业
                if(in_array($t_v, $returnProjectIndustyarr2)){
                    return true;
                }else{
                    return false;//没有权限
                }
            }
        }elseif($permodel->per_open_stutas==4){//只对VIP企业用户【招商通会员用户】
            $cominfo=ORM::factory('Companyinfo')->where('com_user_id', '=', $user_id)->find();
            if($cominfo->platform_service_fee_status==1){//有权限操作
                return true;
            }else{//没有权限操作
                return false;
            }
        }elseif($permodel->per_open_stutas==1){//对所以的都公开
            return true;
        }else{
            return false;
        }

   }//funtion end

   /**
    * 搜索投资者，发信功能
    * @author 赵路生
    * $login_userid 当前登录用户,$postdata里面有to_user_id 和content和$user_type
    */
   public function addLetter($login_userid,$postdata){
       $login_userid = intval($login_userid);
       //if($login_userid && $postdata){

           if($login_userid && isset($postdata['checked_val']) && $postdata['checked_val'] && $postdata['checked_val']!='undefined' && isset($postdata['to_user_id']) && $postdata['to_user_id']){

               try{
                   $ser_letter = ORM::factory('UserLetter');
                   $ser_letter->user_id = $login_userid;
                   $ser_letter->to_user_id = intval($postdata['to_user_id']);
                   $ser_letter->content = $postdata['checked_val']; //添加过滤字符功能
                   $ser_letter->user_type = intval($postdata['user_type']);
                   $ser_letter->letter_status = 1;
                   $ser_letter->add_time = time();
                   $ser_letter->sid = arr::get($_COOKIE, 'Hm_lvqtz_sid','');//sid
                   $ser_letter->fromdomain = arr::get($_COOKIE, 'Hm_lvqtz_refer','');//入口域名
                   $ser_letter->campaignid = arr::get($_COOKIE, 'campaignid',0);//计划id
                   $ser_letter->adgroupid = arr::get($_COOKIE, 'adgroupid',0);//单元id
                   $ser_letter->keywordid = arr::get($_COOKIE, 'keywordid',0);//关键词id
                   $ser_letter->letter_ip = ip2long( Request::$client_ip );//ip
                   $ser_letter->create();
               }catch (Kohana_Exception $e){
                   throw $e;
               }
             return $ser_letter;
           }
       //}
       //return false;
   }//function end

   /**
    *给sem的接口service，无keyworidid的不提供
    * @author 许晟玮
    */
   public function getseminfolist( $bt=0 ){

        $orm= ORM::factory('UserLetter');
        if( $bt==0 ){
            //当天
            $bt= date("Y-m-d").' 00:00:00';
        }else{
            //bt是时间
            $bt= strtotime($bt);
        }
        $orm->where('keywordid', '!=', 0);
        $orm->where('add_time', '>=', $bt);
        $result= $orm->find_all()->as_array();

        return $result;
   }
   /**
    * 给特定项目添加额外的发送名片记录
    * @param $userid 给这个项目发送名片的用户id
    * @param $projectid 接收名片的项目id
    * @param $type 1默认：给标示项目递送名片活10元话费
    * @author 赵路生
    */
   public function addCardBehaviourInfoForSpePro($userid,$projectid,$type=1){
   		$userid = intval($userid);
   		$projectid = intval($projectid);
   		$type = intval($type);
   		// 判断用户是不是在活动期间新注册的用户，但是老用户也可以获取
   		$setting = common::getSpecificProjectSetting();
   		// 添加自动结束时间
   		if(time()>=$setting['start_time'] && time()<=$setting['end_time']){
   			$userinfo = Service_Sso_Client::instance()->getUserInfoById($userid);	   		
	   		if($userinfo && $projectid && in_array($projectid ,$setting['project_ids'])){
	   			$model = ORM::factory('CardinfoSpecificProject');
	   			$model->userid = $userid;
	   			$model->projectid = $projectid;
	   			$model->type = $type;
	   			$model->addtime = time();
	   			// 在这个区间注册的用户给奖励，不是在这个区间注册的用户只做记录，不给奖励
	   			$model->status = ($userinfo->reg_time >= $setting['start_time'] && $userinfo->reg_time <= $setting['end_time']) ? 1: 0;
	   			try{
	   				$model->create();
	   			}catch (Kohana_Exception $e){
	   				throw $e;
	   			}
	   		}
   		}
   }
   /**
    * 获取好项目--获取金 银 铜 牌的用户
    * @author 赵路生
    */
   public function getUserTypeForSpePro(){
   		$return = array();
   		$setting = common::getSpecificProjectSetting();
   		if(time() > $setting['end_time']){
   			$memcache = Cache::instance ( 'memcache' );
   			//$memcache->delete('getUserTypeForSpePro');
   			$return_cache = $memcache->get('getUserTypeForSpePro');
   			 
   			if($return_cache){
   				$return = $return_cache;
   			}else{
   				$result = array();
   				$result = DB::select(array(DB::expr('COUNT(projectid)'), 'projectnum'),'projectid')
   				->from('card_info_specific_project')
   				->where('addtime', '>=', $setting['start_time'])
   				->where('addtime', '<=', $setting['end_time'])
   				->order_by('projectnum','DESC')->group_by('projectid')->execute()->as_array();
   				
	   			$temp = 0;
		    	$counter = 3; // 排名数设置
		    	foreach($result as $k=>$v){		    		
		    		$temp = ($temp==0) ? $v['projectnum'] : $temp;	
		    			    	
		    		if($v['projectnum']==$temp){
		    			$return['rank'][$counter][] = $v['projectid'];
		    		}elseif($v['projectnum']<$temp){
		    			if($counter == 1){
		    				break;
		    			}
		    			$return['rank'][$counter-1][] = $v['projectid'];
		    			$temp = $v['projectnum'];
		    			--$counter;
		    		}
		    		$return['protonum'][$v['projectid']] = $v['projectnum'];
		    		$return['proids'][$k] = $v['projectid'];
		    		$temp_m = ORM::factory('CardinfoSpecificProject')->where('projectid','=',$v['projectid'])->find_all()->as_array();
		    		if(count($temp_m)){
		    			foreach($temp_m as $k1=>$v1){
		    				if(!isset($return['users'][$v1->userid]) || $return['users'][$v1->userid]<$counter){
		    					$return['users'][$v1->userid] = $counter;
		    				}		    				
		    			}
		    		}		    		
		    	}		    	
   				// 添加缓存
   				$memcache->set('getUserTypeForSpePro',$return,86400);
   			}
   		}
   		return $return;
   }
   /*
    *  获取好项目-- 判断用户是否已经获得话费
    *  @param  $uid 用户id
    *  @author 赵路生
    *  @return boolean
    */
   public function getIsHavingForSpePro($uid){
   		$uid = intval($uid);
   		$return = false;
   		if($uid){
   			$memcache = Cache::instance ( 'memcache' );
   			$return_cache = $memcache->get('getIsHavingForSpePro'.$uid);
   			if($return_cache){
   				$return = $return_cache;
   			}else{
   				$result = ORM::factory('CardinfoSpecificProject')->where('userid', '=', $uid)->where('status','=',1)->order_by('addtime','ASC')->find_all();
   				if(count($result)){
   					$return = true;
   					$memcache->set('getIsHavingForSpePro'.$uid,$return,86400);
   				}  				
   			}  			
   		}
   		return $return;
   }
   /*
   *  获取好项目--获取播报的内容
   *  @author 赵路生
   *  @return json
   */
   public function getAnnouncementForSpePro(){
   		$setting = common::getSpecificProjectSetting();
   		$return = array();
   		$memcache = Cache::instance ( 'memcache' );
   		//$memcache->delete('getUserTypeForSpePro');
   		$return_cache = $memcache->get('getAnnouncementForSpePro');
   			 
   		if($return_cache){
   			$return = $return_cache;
   		}else{
   			$result = ORM::factory('CardinfoSpecificProject')->limit('50')->order_by('addtime','DESC')->find_all();
   			$userid = $projectid = $uid_proid = array();
   			foreach($result as $v){
   				$userid[] = $v->userid;
   				$projectid[] = $v->projectid;
   				$uid_proid[] = array('uid'=>$v->userid,'proid'=>$v->projectid);
   			}
   			// 批量获取用户信息
   			$usersinfo = $usersinfo_arr = $prosinfo = $prosinfo_arr = $temp_return = array();
   			if(!empty($userid)){
   				$usersinfo = Service_Sso_Client::instance()->getUserInfoByMoreUserId(array_unique($userid));
   				$usersinfo = $usersinfo ? $usersinfo : array();
   				foreach($usersinfo as $uv){
   					$usersinfo_arr[$uv['id']] = $uv['user_name'];
   				}
   			}
   			
   			
   			// 批量获取项目名称
   			$pro_ser = new Service_Platform_Project();
   			if(!empty($projectid)){
   				$prosinfo = $pro_ser->getProjectByProjectIds(array_unique($projectid));  				
   				foreach($prosinfo as $uv){
   					$prosinfo_arr[$uv['project_id']] = $uv['project_brand_name'];
   				}
   			}   			
   			
   			// 批量获取标志项目获得的名片数量
   			$proscount = $this->getCardCountByProjectIds(array_unique($projectid));   			
   			
   			// 组装信息
   			foreach($uid_proid as $k1=>$v1){
   				$temp_return[] = '<span>'.$usersinfo_arr[$v1['uid']].'</span>刚刚给意向加盟项目<a target="_blank" href="'.url::website('project/'.$v1['proid'].'.html').'">'.$prosinfo_arr[$v1['proid']].'</a>投递了名片';
   				$temp_return[] = '截至目前，<a target="_blank" href="'.url::website('project/'.$v1['proid'].'.html').'">'.$prosinfo_arr[$v1['proid']].'</a>项目赢得了第'.$proscount[$v1['proid']].'位投资者的亲睐';
   				$temp_return[] = '恭喜'.$usersinfo_arr[$v1['uid']].'投资者刚刚获得了10元话费奖励';
   			}
   			$return = array_unique($temp_return);
   			// 添加缓存,缓存二十分钟
   			$memcache->set('getAnnouncementForSpePro',$return,'1200');  			
   		}
   		shuffle($return);
   		$return = count($return)>6 ? array_slice($return,0,6) : $return;
   		return $return;
   }
   /*
    *  获取好项目--多个标示项目收到的投资者的总数,最多获取到50个
   *  @author 赵路生
   */
   public function getCardCountByProjectIds($projectids=array()){
   		$return = array();
   		$setting = common::getSpecificProjectSetting();
   		if(is_array($projectids) && !empty($projectids)){
   			$result = DB::select(array(DB::expr('COUNT(projectid)'), 'projectnum'),'projectid')
   			->from('card_info_specific_project')
   			->where('projectid', 'IN', $projectids)
   			->where('addtime', '>=', $setting['start_time'])
   			->where('addtime', '<=', $setting['end_time'])
   			->order_by('projectnum','ASC')->group_by('projectid')->limit(50)->execute()->as_array();
   			
   			foreach($result as $v){
   				$return[$v['projectid']] = $v['projectnum'];
   			}
   		}
   		return $return;  	
   }
}
