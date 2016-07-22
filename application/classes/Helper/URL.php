<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * 增加网站全局URL配置
 * @author 龚湧
 *
 */

class Helper_URL extends Kohana_URL {
  /**
   * 返回主站地址
   * @author 龚湧
   * @param string $path
   * @return string
   */
    public static function website($path){
        $base = Kohana::$config->load("site.website");
        $url = $base.ltrim($path,"/");
        return $url;
    }
    
    /**
     * 返回问答模块地址
     * @author 钟涛
     * @param string $path
     * @return string
     */
    public static function webwen($path){
    	$base = Kohana::$config->load("site.webwen");
    	$url = $base.ltrim($path,"/");
    	return $url;
    }
    
    /**
     * 返回静态目录
     * @author 沈鹏飞
     * @param string $path
     * @return string
     */
    public static function webstatic($path){
    	$base = Kohana::$config->load("site.webstatic");
    	$url = $base.ltrim($path,"/");
    	return $url;
    }    

    /**
     * css地址
     * @author 龚湧
     * @param string $path
     * @return string
     */
    public static function webcss($path){
        $base = Kohana::$config->load("site.webcss");
        $url = $base.ltrim($path,"/");

        // Set the stylesheet link
        $attributes['href'] = $url;

        // Set the stylesheet rel
        $attributes['rel'] = empty($attributes['rel']) ? 'stylesheet' : $attributes['rel'];

        // Set the stylesheet type
        $attributes['type'] = 'text/css';

        return '<link'.HTML::attributes($attributes).' />';

    }


    /**
     * js地址
     * @author 龚湧
     * @param string $path
     * @return string
     */
    public static function webjs($path){
        $base = Kohana::$config->load("site.webjs");
        $url = $base.ltrim($path,"/");

        // Set the script link
        $attributes['src'] = $url;

        // Set the script type
        $attributes['type'] = 'text/javascript';

        return '<script'.HTML::attributes($attributes).'></script>';

    }

    /**
     * 外部调用api地址
     * @author 施磊
     * @param string $path
     * @return string
     */
    public static function websapi($path){
        $base = Kohana::$config->load("site.websapi");
        $url = $base.ltrim($path,"/");
        return $url;
    }
    
    /**
     * 图片地址
     * @author 钟涛
     * @param string $path
     * @return string
     */
    public static function imgurl($path){
    	//判断图片地址是否已经包含 http
    	if(strpos($path, 'http://') === false){
	    	$base = Kohana::$config->load("site.imgurl");
	    	$url = $base.ltrim($path,"/");
	    	return $url;
    	}else{
    		return $path;
    	}
    }
    /**
     * 生成sitemap路径
     * @author 嵇烨
     * @param string $path
     * @return string
     */
    public static function sitemap($path){
        $base = Kohana::$config->load("site.sitemap");
        $url = $base.ltrim($path,"/");
        return $url;
    }
    /**
     * 视频地址
     * @author stone shi
     * @param string $path
     * @return string
     */
    public static function movieurl($path){
    	$base = Kohana::$config->load("site.siteMovie");
        $url = $base.ltrim($path,"/");
        return $url;
    }
    
}