<?php
/**
 * 第三方登录添加日志
 * @author 许晟玮
 */
class Service_Oauth_Log{
    /**
     * 添加第三方帐号的绑定日志
     * @author许晟玮
     */
    public function setOauthLog( $arr=array() ){
        if( empty( $arr ) ){
            return false;
        }
        // if info is alerdy insert
        $orm=  ORM::factory('OauthLog');
        $orm->where('oauth_id', '=', $arr['oauth_id']);
        $orm->where('act_userid', '=', ceil($arr['act_userid']) );
        $co= $orm->count_all();
        if( $co>0 ){
            return true;
        }
        $model= ORM::factory('OauthLog');
        $model->oauth_id= $arr['oauth_id'];
        $model->type= ceil( $arr['type'] );
        $model->act_time= time();
        $model->act_userid= ceil( $arr['act_userid'] );
        $re= $model->create();
        if( $re->id>0 ){
            return true;
        }else{
            return false;
        }
    }
    //end function

    /**
     * 传入第三方ID，获取信息
     * @author许晟玮
     */
    public function gtOauthInfo( $oauth_id ){
        $model= ORM::factory('OauthLog');
        $model->where('oauth_id', '=', $oauth_id);
        $result= $model->find();
        return $result;
    }
    //end function

    /**
     * 传入会员ID 查看第三方是否绑定过
     * @author许晟玮
     */
    public function getOauthEofByUid( $uid ){
        $model= ORM::factory('OauthLog');
        $model->where('act_userid', '=', $uid);
        $model->where('type', '=', '0');
        $result= $model->count_all();
        if( $result==0 ){
            //不存在
            return false;
        }else{
            //存在
            return true;
        }
    }
    //end function

    /**
     * 传入会员ID 获取这个家伙所有绑定的帐号
     * @author 许晟玮
     */
    public function getOauthUserList( $uid ){
        $model= ORM::factory('OauthLog');
        $model->where('act_userid', '=', $uid);
        $model->where('type', '=', '0');
        $result= $model->find_all()->as_array();
        return $result;
    }
    //end function

    /**
     * 解绑
     * @author 许晟玮
     */
    public function updateOauthType( $id ){
        $model= ORM::factory('OauthLog',$id);
        $res= $model->delete();
        return $res;
    }
    //end function

}