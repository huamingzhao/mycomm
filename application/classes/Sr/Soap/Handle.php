<?php
/**
 * soap 服务
 * @author gongyong
 *
 */
class Sr_Soap_Handle{

    /**
     * hello word
     * @author 许晟玮
     */
    public static function test(){
        return 'hello word';
    }
    //end function

    /**
     * 获取名片数据
     * @author 许晟玮
     */
    public static function getCardList( $d=0 ){
        $service= new Service_Card();
        $result= $service->getseminfolist( $d );
        $return= array();
        foreach ( $result as $key=>$vs ){
            $return[$key]['id']= $vs->id;
            $return[$key]['content']= $vs->content;
            $return[$key]['datetime']= date("Y-m-d H:i:s",$vs->add_time);
            $return[$key]['keywordid']= $vs->keywordid;
            $return[$key]['sid']= $vs->sid;
            $return[$key]['ip']= long2ip($vs->letter_ip);
            $return[$key]['url']= kohana::$config->load('site')->get('website').'project/'.$vs->to_project_id.'.html';
            $return[$key]['fromdomain']= $vs->fromdomain;
        }
        return $return;
    }
    //end function

    /**
    *获取项目统计数据，获取完毕写入临时文件备份
    *@author 许晟玮
    */
    public static function getProjectStat(){
        $orm= ORM::factory('ProjectStat');
        $result= $orm->find_all();
        $res= array();
        foreach ( $result as $k=>$v ){
            $res[$k]['project_id']= $v->project_id;
            $res[$k]['user_id']= $v->user_id;
            $res[$k]['action_ip']= $v->action_ip;
            $res[$k]['action_time']= $v->action_time;
            $res[$k]['sid']= $v->sid;
            $res[$k]['fromdomain']= $v->fromdomain;
            $res[$k]['convert']= $v->convert;
            $res[$k]['aid']= $v->aid;
            $res[$k]['campaignid']= $v->campaignid;
            $res[$k]['adgroupid']= $v->adgroupid;
            $res[$k]['keywordid']= $v->keywordid;
        }
        if( count($res)>0 ){
            //创建文件
            $filename= date("Y-m-d H:i:s")."_project_stat";
            $dir = APPPATH."cache/data/{$filename}.txt";
            $fp= @fopen($dir, "w+"); //打开文件指针，创建文件
            if ( !is_writable($dir) ){
                return false;
            }
           // echo serialize($res);exit;
            $rf= fwrite($fp,serialize($res));

            fclose($fp);  //关闭指针
            if( $rf===false ){
                return false;
            }else{
                //清空表
                foreach ( $result as $k=>$v ){
                    $orm= ORM::factory('ProjectStat',$v->id);
                    $orm->delete();
                }

                return $res;
            }
        }else{
            return false;
        }
    }
    //end function

    /**
    * 去重的手机数
    *@author 许晟玮
    */
    public static function  getPhoneCountByDay( $data ){
        $service= new Service_QuickPublish_Project();

        $count= $service->getPhoneCountByDay( $data );
        return $count;
    }

    //end function

    /**
    *根据条件，获取对应的免费生意类型的数量
    *@author 许晟玮
    */
    public static function getQuickProForBi( $data ){
        $service= new Service_QuickPublish_Project();
        $arr_count= $service->getQuickProForBi( $data );
        return $arr_count;
    }

    //end function


    /**
     * 聊天统计，时间度模式
     * @author 许晟玮
     */
    public static function getChatrecordStat( $begin,$end ){
        $date_cy= (strtotime( $end )-strtotime( $begin ))/86400;
        $result= array();
        for ( $i=0;$i<=$date_cy;$i++ ){
            $begin_time	= strtotime( $begin." 00:00:00" )+$i*86400;
            $end_time= strtotime( $begin." 23:59:59" )+$i*86400;
            //聊天的条数
            $orm= ORM::factory('Appchatrecord');
            $orm->where('creationDate', '>=', $begin_time);
            $orm->where('creationDate', '<=', $end_time);
            $count= $orm->count_all();

            //聊天的人数
            $orm= ORM::factory('Appchatrecord');
            $orm->where('creationDate', '>=', $begin_time);
            $orm->where('creationDate', '<=', $end_time);
            $orm->group_by('username');
            $cacpi= $orm->count_all();

            $result[$i]['data']= date( "Y-m-d",$begin_time );
            $result[$i]['count']= $count;
            $result[$i]['cacpi']= $cacpi;
        }
        return $result;

    }

    //end function
}