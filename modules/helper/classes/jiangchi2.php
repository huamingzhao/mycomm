<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生成奖池
 */
class jiangchi2{
	private $start_date = '2013-10-11';
                private $end_date = '2013-11-7';
                #一个周期的天数
                private $cycle = 7;
                #一个周期奖池的长度
                private $cycle_count = 700;
                
                public function begin() {
                    $return = array();
                    $zouqi = 1;
                    for($i = strtotime($this->start_date); $i < strtotime($this->end_date);) {
                        $allStr = $this->doChoujiang($zouqi);
                        for($j = 0; $j < 7; $j ++) {
                           $return[] =  array('date' => date('Y-m-d', $i+($j*86400)), 'jiangchi' => implode('', $allStr[$j]));
                        }
                        $i = $i + 86400*7;
                        $zouqi ++;
                    }
                    #对于最后3天的特殊处理                    
                    $arr = array();
                    for($i = 0; $i < 100 ; $i++){
                    	$arr[] = 1;
                    }
                    $return[] = array('date' => '2013-11-08', 'jiangchi' => implode('', $arr));
                    $return[] = array('date' => '2013-11-09', 'jiangchi' => implode('', $arr));
                    $return[] = array('date' => '2013-11-10', 'jiangchi' => implode('', $arr));
                    return $return;
                }
                
                public function doChoujiang($zouqi = 1) {                	
                    #ipod 在奖池中的id
                    $ipod_id = 2;
                    #一个周期产生ipod的数量
                    $ipad_num = 1;
                    
                    $chongdian_id = 3;
                    #一个周期产生移动充电器的数量
                    $chongdian_num = $zouqi > 2 ? 2 : 3;
                    
                    #u盘在奖池中的id
                    $u_pan_id = 4;
                    #u盘在奖池中的数量
                    $u_pan_num = 8;
                    
                    #幸运奖id
                    $lucky_id = 5;
                    #幸运奖数量
                    $lucky_num = 15;
                    
                    #创业币id
                    $other_id = 1;
                    
                    $str = array();
                    #先定位ipod的位置
                    $str[rand(1, $this->cycle_count)] = 2;
                	for($i = $chongdian_num; $i > 0; $i--) {
                        $str = $this->_rand($str, $chongdian_id);
                    }
                    for($i = $u_pan_num; $i > 0; $i--) {
                        $str = $this->_rand($str, $u_pan_id);
                    }
                    for($i = $lucky_num; $i > 0; $i--) {
                        $str = $this->_rand($str, $lucky_id);
                    }
                    for($i = 0; $i < $this->cycle_count; $i++) {
                        if(!isset($str[$i])) {
                            $str[$i] = $other_id;
                        }
                    }
                    ksort($str);
                    $allStr = array_chunk($str, $this->cycle_count/$this->cycle);
                    return $allStr;
                }
                
                private function _rand($str, $u_pan_id) {
                    $temp = rand(1, $this->cycle_count);
                    if(isset($str[$temp])) {
                        $str = $this->_rand($str, $u_pan_id);
                    } else{
                       $str[$temp] = $u_pan_id;
                       
                    }
                    return $str;
                    
                }
	}
?>
