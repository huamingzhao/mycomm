<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 项目
 */
class project {
    public static function checkProLogo($url) {
//        $curl = curl_init($url); // 不取回数据
//	    curl_setopt($curl, CURLOPT_NOBODY, true);
//	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // 发送请求
//	    $result = curl_exec($curl);
//	    $found = false; // 如果请求没有发送失败
//	    if ($result !== false) {
//	 
//	        /** 再检查http响应码是否为200 */
//	        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//	        if ($statusCode == 200) {
//	            $found = true;
//	        }
//	    }
//	    curl_close($curl);
	 
//	    return $found;
		return true;
        
    }
    public static function conversionProjectImg($project_source = 1, $imgType = 'logo', $projectInfo = array()) {
        if (! $projectInfo)
            return '';
        switch ($imgType) {
            case 'logo' :
                return self::conversionProjectLogo ( $project_source, $projectInfo );
                break;
            case 'images' :
                return self::conversionProjectLogo ( $project_source, $projectInfo );
                break;
        }
    }
    private static function conversionProjectLogo($project_source = 1, $projectInfo = array()) {
        if (! $projectInfo)
            return '';
        $outside_id = isset ( $projectInfo ['outside_id'] ) ? $projectInfo ['outside_id'] : '';
        $project_logo = isset ( $projectInfo ['project_logo'] ) ? $projectInfo ['project_logo'] : '';
        if ($project_source == 4 || $project_source == 5) {
            if (self::checkImgHttp ( $project_logo )) {
                return URL::imgurl ($projectInfo ['project_logo']);
            } else {
                if ($outside_id && $project_logo) {
                    //去除logo地址前面 'http://pic.czzs.com'
                    //$project_logo=common::getImgUrl($project_logo);
                    //$logo = "poster/html/ps_" . $outside_id . "/" . $project_logo;
                    //return URL::webstatic ( $logo );
                    return URL::imgurl($project_logo);
                } else {
                    return '';
                }
            }
        } else {
            return URL::imgurl ($projectInfo ['project_logo']);
        }
    }
    public static function conversionCompanyImg($outside_id = 0, $project_source = 4, $imgType = 'logo', $company_info = array()) {
        if (! $company_info)
            return '';
        switch ($imgType) {
            case 'logo' :
                return self::conversionCompanyLogo ( $outside_id, $project_source, $company_info );
                break;
        }
    }
    private static function conversionCompanyLogo($outside_id, $project_source, $company_info) {
        if (! $company_info)
            return '';
        $com_logo = isset ( $company_info ['com_logo'] ) ? $company_info ['com_logo'] : '';
        if ($project_source == 4 || $project_source == 5) {
            if (self::checkImgHttp ( $com_logo )) {
                return URL::imgurl ($com_logo);
            } else {
                if ($outside_id && $com_logo) {
// 					$logo = "poster/html/ps_" . $outside_id . "/" . $com_logo;
// 					return URL::webstatic ( $logo );
                    return URL::imgurl($com_logo);
                } else {
                    return '';
                }
            }
        } else {
            return URL::imgurl ($com_logo);
        }
    }
    private static function checkImgHttp($src) {
        return is_int ( strpos ( $src, 'upload' ) );
    }
    
    public static function creatPhoneImg($num) {
        if(!$num) return false;
        static $client;
        $en_text = 'Test';

        //要输出的字
        $en_font = str_replace('\\', '/', realpath(MODPATH.'captcha/fonts/')).'/'.'DejaVuSerif.ttf';

        //字库的文件名。建议中文字体和E文字体用不一样的，因为宋体、黑体字库里的英文实在不咋滴
        //这里用的都是TTF字体。懒得上网找就到 C:\WINDOWS\Fonts 下，Copy几个字体到程序目录下就好了
        // 创建一个真彩的图片背景，参数是 X长 ,Y宽。
        $im = imagecreatetruecolor(150, 30);

        //或者可以调用已有的PNG图片等 ： $im = imagecreatefrompng('background.png');
        // 定义几个色彩
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        $red = imagecolorallocate($im, 255, 0, 0);
        //填充背景为白色
        imagefill($im, 0, 0, $white);

        //以上两行是创建透明图片的关键！
        //很多人反应， imagecreatetruecolor创建后的图片，背景颜色是黑的，无论怎么修改都不能变成白色的
        //我先将背景填充为白色，再做透明处理就可以了！在Firefox3和IE7下测试成功！
        // 这是一个很简单的产生阴影的效果。
        //先在原本要输出的位置的偏左下角一点点输出灰色的字体，接着再用黑色字体写上去就有阴影效果了
        //下次再提供点别的花样~
        //imagettftext($im, 20, 0, 11, 21, $grey, $en_font, $en_text);

        // 写入字体
        imagettftext($im, 15, 0, 5, 22, $red, $en_font, $num);
        //imagepng($im);
        //echo $im;
        //echo imagepng($im, 'a.png');
        //header("Content-type:image/png");
        //imagepng($im, 'a.png');
        //file_get_contents('a.png');
        ob_start();
        imagepng($im);
        $imagevariable = ob_get_contents();
        ob_end_clean();
        $files['test']['tmp_name'] =  base64_encode($imagevariable);
        $files['test']['type'] = 'image/png';
        $files['test']['error'] = '0';
        $files['test']['name'] = 'a.png';
        $files['test']['size'] = '1';
        $files['test']['width'] = '150';
        $files['test']['height'] = '30';
        //$file = array('test' => base64_encode($im));
        if (!($client instanceof SoapClient)) {
            $client = new SoapClient(null, array('location' => kohana::$config->load('image')->get('upload_domain'), 'uri' => kohana::$config->load('image')->get('uri'), 'encoding' => 'utf8'));
        }
        //var_dump($files);exit;
        $rtn = $client->uploadPic($files, 'test', kohana::$config->load('image')->get('call_server_keys'), array());
        imagedestroy($im);
        return $rtn;
        //$img = common::uploadPic(base64_encode($im), 'test');
        //imagedestroy($im);
    }
}
