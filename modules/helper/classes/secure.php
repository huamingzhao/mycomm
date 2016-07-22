<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网站安全
 */
class secure{
    //屏蔽词库
    public static $senitiveWord = array('select', 'from', 'insert', 'values', 'delete', 'update', 'show', 'database', 'table', 'column');
    /*
     * 过滤表单数据
     * @author 施磊
     * @param string $string 需要过滤的字符串
     * @param array  $senitiveWord 需要过滤的敏感词 自定义
     * @return  过滤完的字符串
     */
    
    public static function secureInput($string, $senitiveWord = array()) {
        $string = trim($string);
        $string = strip_tags($string);
        if($senitiveWord) {
            foreach ($senitiveWord as $val) {
                $string = str_replace($val, '', $string);
            }
        }
        foreach (self::$senitiveWord as $valT) {
                $string = str_replace($valT,'', $string);
         }
         $string = self::secureHtml($string);
        return $string;
    }
    
    /**
     * 过滤字符集
     * @author 施磊
     */
    public static function secureUTF($string) {
        if(!$string) return $string;
        $code = mb_detect_encoding(urldecode($string), array('UTF-8', 'GBK'));
        $return = '';
        if($code == "UTF-8") {
            $return = urldecode($string);
        }else{
            $return = iconv('GBK', 'UTF-8', urldecode($string));
        }
        return $return;
    }
    /**
     * 过滤HTML;
     * @author 施磊
     */
   public static function secureHtml($str) {
	if (empty($str)) return false;
	//$str = htmlspecialchars($str);
	$str = str_replace( '/', "", $str);
	$str = str_replace("\\", "", $str);
	$str = str_replace("&gt", "", $str);
	$str = str_replace("&lt", "", $str);
	$str = str_replace("<SCRIPT>", "", $str);
	$str = str_replace("</SCRIPT>", "", $str);
	$str = str_replace("<script>", "", $str);
	$str = str_replace("</script>", "", $str);
	$str=str_replace("select","",$str);
	$str=str_replace("join","",$str);
	$str=str_replace("union","",$str);
	$str=str_replace("where","",$str);
	$str=str_replace("insert","",$str);
	$str=str_replace("delete","",$str);
	$str=str_replace("update","",$str);
	$str=str_replace("like","",$str);
	$str=str_replace("drop","",$str);
	$str=str_replace("create","",$str);
	$str=str_replace("modify","",$str);
	$str=str_replace("rename","",$str);
	$str=str_replace("alter","",$str);
	$str=str_replace("cas","",$str);
	$str=str_replace("&","",$str);
	$str=str_replace(">","",$str);
	$str=str_replace("<","",$str);
	$str=str_replace("&",chr(34),$str);
	$str=str_replace("'",chr(39),$str);
	$str=str_replace("<br />",chr(13),$str);
	$str=str_replace("''","'",$str);
	$str=str_replace("css","'",$str);
	$str=str_replace("CSS","'",$str);
	 
	return $str;
	 
	}
}