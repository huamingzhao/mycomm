<?php

/**
 * 通用验证类
 * @author：兔毛 2014-06-1
 */
class validations {
	
	/**
	 * 截取指定长度的字数
	 * @param string $string        	
	 */
	public static function truncateStr($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
		if ($length == 0)
			return '';
		if (mb_strlen ( $string, 'utf8' ) > $length) {
			$length -= mb_strlen ( $etc, 'utf8' );
			if (! $break_words && ! $middle) {
				$string = preg_replace ( '/\s+?(\w+)?$/', '', mb_substr ( $string, 0, $length + 1, 'utf8' ) );
			}
			if (! $middle) {
				return mb_substr ( $string, 0, $length, 'utf8' ) . $etc;
			} else {
				return mb_substr ( $string, 0, $length / 2, 'utf8' ) . $etc . mb_substr ( $string, - $length / 2 );
			}
		} else {
			return $string;
		}
	}
	
	/**
	 * 判断字符串是否设置了值
	 * @param unknown_type $str_value：字符串
	 * @param unknown_type $is_null：默认值
	 */
	public static function is_set_value($str_value,$is_null)
	{
		$str=isset($str_value) ? $str_value : $is_null;
		return $str;
	}

	/**
	 * 验证是否是数字
	 * @param unknown_type $strValue: 值
	 * @param unknown_type $is_null: 默认0
	 * @return boolean：是数字：返回参数值，反之，返回默认值
	 */
	public static function check_match($strValue,$is_null=0)
	{
		if(preg_match("/[^\d-., ]/",$strValue))
			return is_null;
		return $strValue;
	}
	
	
	/**
	 * 时间差计算
	 * @param unknown_type $begin_time：时间
	 */
	public static function get_pub_time($begin_time)
	{
		$msg='';
		$begin_time = intval($begin_time);
		$end_time = time();
		if(self::check_match($begin_time)==false || empty($begin_time) || $begin_time==$end_time) 
			$msg='现在';
        else
        {
			if($begin_time < $end_time){
				$starttime = $begin_time;
				$endtime = $end_time;
			}
			else{
				$starttime = $end_time;
				$endtime = $begin_time;
			}
			$timediff = $endtime-$starttime;
			$days = intval($timediff/86400);
			$remain = $timediff%86400;
			$hours = intval($remain/3600);
			$remain = $remain%3600;
			$mins = intval($remain/60);
			$secs = $remain%60;
			if(!empty($days)) $msg.=$days.'天';
			if(!empty($hours)) $msg.=$hours.'小时';
			if(!empty($mins)) $msg.=$mins.'分';
			if(!empty($secs)) $msg.=$secs.'秒';
			$msg.='前';
			//$res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
        }
        return $msg;
	}
	
	
    /**
     * 去除html标签
     * @param unknown_type $content：内容
     */
	public static function strip_html_conent($content)
	{
		if(is_numeric($content) || empty($content))
			return $content;
		$content = preg_replace("/<input[^>]*>/i",'', $content);
		$content = preg_replace("/<\/input>/i", '', $content);
		$content = preg_replace("/<a[^>]*>/i",'', $content);
		$content = preg_replace("/<\/a>/i", '', $content);
		$content = preg_replace("/<div[^>]*>/i",'', $content);
		$content = preg_replace("/<\/div>/i",'', $content);
		$content = preg_replace("/<font[^>]*>/i",'', $content);
		$content = preg_replace("/<\/font>/i",'', $content);
		$content = preg_replace("/<p[^>]*>/i",'', $content);
		$content = preg_replace("/<\/p>/i",'', $content);
		$content = preg_replace("/<span[^>]*>/i",'', $content);
		$content = preg_replace("/<\/span>/i",'', $content);
		$content = preg_replace("/<\?xml[^>]*>/i",'', $content);
		$content = preg_replace("/<\/\?xml>/i",'', $content);
		$content = preg_replace("/<o:p[^>]*>/i",'', $content);
		$content = preg_replace("/<\/o:p>/i",'', $content);
		$content = preg_replace("/<u[^>]*>/i",'', $content);
		$content = preg_replace("/<\/u>/i",'', $content);
		$content = preg_replace("/<b[^>]*>/i",'', $content);
		$content = preg_replace("/<\/b>/i",'', $content);
		$content = preg_replace("/<meta[^>]*>/i",'', $content);
		$content = preg_replace("/<\/meta>/i",'', $content);
		$content = preg_replace("/<script[^>]*>/i",'', $content);
		$content = preg_replace("/<\/script>/i",'', $content);
		$content = preg_replace("/<!--[^>]*-->/i",'', $content);
		$content = preg_replace("/<p[^>]*-->/i",'', $content);
		$content = preg_replace("/style=.+?['|\"]/i",'',$content);//
		$content = preg_replace("/class=.+?['|\"]/i",'',$content);//去除样式
		$content = preg_replace("/id=.+?['|\"]/i",'',$content);// 去除样式
	    $content = preg_replace("/lang=.+?['|\"]/i",'',$content);//去除样式
	    $content = preg_replace("/width=.+?['|\"]/i",'',$content);// 去除样式
	    $content = preg_replace("/height=.+?['|\"]/i",'',$content);//
		$content = preg_replace("/border=.+?['|\"]/i",'',$content);
		$content = preg_replace("/face=.+?['|\"]/i",'',$content);
		$content = preg_replace("/face=.+?['|\"]/",'',$content);
		$content = preg_replace("/face=.+?['|\"]/",'',$content);
		$content=str_replace( "&nbsp;","",$content);
	    return $content;
	 }
	
	 
	 
    /**
     * 判断参数名称是否缺少
     * @param unknown_type $check_param：参数名称（数组）
     * @return string：报错信息
     */
	 public  static function check_param_name($check_param)
	 {
	 	$msg='';
	    foreach ($check_param as $key => $value) {
		 	if(!array_key_exists($value, $_REQUEST))
		 		$msg.= "参数{$value}缺少; ";
		 }
	 	return $msg;
	 }


	 /**
	  * 判断参数值是否赋值
	  * @param unknown_type $check_param：参数名称（数组）
	  * @return string：报错信息
	  */
	 public static function check_value_set($check_param)
	 {
	 	$msg='';
	 	foreach ($check_param as $key => $value) {
	 		if(!isset($value)||$value=='')
	 			$msg.= "参数{$key}不能为空; ";
	 	}
	 	return $msg;
	 }
	 
	 
	 /**
	  * 給予字段赋值 
	  * @param unknown_type $name：参数名称
	  * @param unknown_type $is_null：默认为空
	  * @param unknown_type $type：int类型，正则验证
	  * @return string：报错信息
	  */
	 public  static function get_param_value($name, $is_null='',$type='')
	 {
	 	if(isset($_REQUEST[$name]) && !empty($_REQUEST[$name])){
	 		if(empty($type))
	 			return trim($_REQUEST[$name]);
	 		if($type=='int')
	 		{
	 			return validations::check_match($_REQUEST[$name],$is_null);
	 		}
	 	}
	 	return $is_null;
	 }

}

?>