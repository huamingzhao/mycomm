<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 自己调用api 获取资讯相关
 * @author 许晟玮
 *
 */
class Service_Api_Zixun extends Service_Api_Basic{

    /**
    * 获取对应的资讯的pv数
    * @author 许晟玮
    * @return array
    */
    public function getPvNum($id) {
        $post = array('id' => $id);
        $arr= $this->getApiReturn($this->apiUrl['getZixunPvNum'],$post);

        return $arr;
    }
    //end function

    /**
     * 获取一个时间段中的资讯数量
     * @author 许晟玮
     */
    public function getPvNumDate( $begin,$end ){
        $post = array('begin' => $begin,'end'=>$end );
        $arr= $this->getApiReturn($this->apiUrl['getZixunPvDate'],$post);
        return $arr;
    }
    //end function
    
    /**
     * 获取行业新闻下热门项目
     * @author 郁政
     */
    public function getHangYePvDate( $begin,$end,$num ){
        $post = array('begin' => $begin,'end'=>$end,'num'=>$num );
        $arr= $this->getApiReturn($this->apiUrl['getHangYePvDate'],$post);
        return $arr;
    }
    //end function
}
