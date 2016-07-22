<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生成奖池
 */
class jiangchi{
	private $start_date = '2013-09-01';
                private $end_date = '2013-09-28';
                #一个周期的天数
                private $cycle = 7;
                #一个周期奖池的长度
                private $cycle_count = 700;
                
                public function begin() {
                    $return = array();
                    for($i = strtotime($this->start_date); $i < strtotime($this->end_date);) {
                        $allStr = $this->doChoujiang();
                        for($j = 0; $j < 7; $j ++) {
                           $return[] =  array('date' => date('Y-m-d', $i+($j*86400)), 'jiangchi' => implode('', $allStr[$j]));
                        }
                        $i = $i + 86400*7;
                    }
                    #对于最后2天的特殊处理
                    $this->cycle = 2;
                    $this->cycle_count = 1000;
                    
                    $allStrTemp = $this->doChoujiang();
                    $return[] = array('date' => '2013-09-29', 'jiangchi' => implode('', $allStrTemp[0]));
                    $return[] = array('date' => '2013-09-30', 'jiangchi' => implode('', $allStrTemp[1]));
                    return $return;
                }
                
                public function doChoujiang() {
                	$draw = common::drawArr();
                    #ipod 在奖池中的id
                    $ipod_id = 2;
                    #一个周期产生ipod的数量
                    $ipad_num = $draw[$ipod_id];
                    
                    #u盘在奖池中的id
                    $u_pan_id = 3;
                    #u盘在奖池中的数量
                    $u_pan_num = $draw[$u_pan_id];
                    
                    #幸运奖id
                    $lucky_id = 4;
                    #幸运奖数量
                    $lucky_num = $draw[$lucky_id];
                    
                    #创业币id
                    $other_id = 1;
                    
                    $str = array();
                    #先定位ipod的位置
                    $str[rand(1, $this->cycle_count)] = 2;
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
