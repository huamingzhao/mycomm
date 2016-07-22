<?php defined('SYSPATH') or die('No direct script access.');
/**
 * flash上传图片调用接口
 * @author 潘宗磊
 *
 */
class Controller_Upload_Upload extends Controller{
    /**
    * 上传图片，单张图片上传
    * @author 潘宗磊
    */
    public function action_test(){
        //返回结果
        $result = array("name"=>"","org"=>"","thumb"=>"");
        $error_result = json_encode($result);
        $files= $_FILES;
        $keys=array_keys($files);
        //TODO 可以抛出各种错误代码
        if(empty($keys)){
            echo $error_result;
            return false;
        }
        if ($files[$keys[0]]['name']==""){//如果上传图片为空并且原有图片也为空
            echo $error_result;
            return false;
        }
        if($files[$keys[0]]['error']!==0){
            echo $error_result;
            return false;
        }
        //原图片名称
        $result['name'] = $files[$keys[0]]['name'];
        //原图长宽
        $size = getimagesize($files[$keys[0]]['tmp_name']);
        //长宽比
        $com_head = Kohana::$config->load("thumbnail.com_head");
        $thub_w = 150;
        $thub_h = 120;
        if($size[0]/$size[1] < 150/120){
          $thub_w = floor(120/$size[1] * $size[0]) ;
        }
        //大小图的长宽控制
        $config = array(
                array($size[0],$size[1]),//原图大小
                array($thub_w,$thub_h)//缩略图大小
        );
        try{
            $img = common::uploadPic($files,$keys[0],$config);
        }catch(Kohana_Exception $e){
            echo $error_result;
            return false;
        }
        if(Arr::get($img,'error')){
            $result = array("name"=>"","org"=>"","thumb"=>"","error"=>Arr::get($img,'error'));
            $error_result = json_encode($result);
            echo $error_result;
            return false;
        }
        $s_path = Arr::get($img,'path');
        $b_path = str_replace('s_','b_', $s_path);
        $result['org'] = $b_path;
        $result['thumb'] = $s_path;
        echo json_encode($result);
    }

    /**
     * 上传图片，单张图片上传
     * @author 潘宗磊
     */
    public function action_index(){    	
        //返回结果
        $result = array("name"=>"","org"=>"","thumb"=>"");
        $error_result = json_encode($result);
        $files= $_FILES;
        $keys=array_keys($files);
        //TODO 可以抛出各种错误代码
        if(empty($keys)){
            echo "Without permission";
            return false;
        }
        if ($files[$keys[0]]['name']==""){//如果上传图片为空并且原有图片也为空
            echo $error_result;
            return false;
        }
        if($files[$keys[0]]['error']!==0){
            echo $error_result;
            return false;
        }
        //原图片名称
        $result['name'] = $files[$keys[0]]['name'];
        //原图长宽
        $size = getimagesize($files[$keys[0]]['tmp_name']);
        $thub_w = 150;
        $thub_h = 120;
        if($size[0]/$size[1] < 150/120){
            $thub_w = floor(120/$size[1] * $size[0]) ;
        }
        //大小图的长宽控制
        $config = array(
                array($size[0],$size[1]),//原图大小
                array($thub_w,$thub_h)//缩略图大小
        );
        if($size[0]<150 and $size[1]<120){
            $config = array("w"=>$size[0],"h"=>$size[1]);
        }
        try{
            if(isset($_GET['debug_upload_img']) and $_GET['debug_upload_img']==1){
                $img = common::uploadPicTest($files,$keys[0],$config);
            }
            else{
                $img = common::uploadPic($files,$keys[0],$config);
            }
        }catch(Kohana_Exception $e){
            echo $error_result;
            return false;
        }
        if(Arr::get($img,'error')){
            $result = array("name"=>"","org"=>"","thumb"=>"","error"=>Arr::get($img,'error'));
            $error_result = json_encode($result);
            echo $error_result;
            return false;
        }
        $s_path = Arr::get($img,'path');
        $b_path = str_replace('s_','b_', $s_path);
        $result['org'] = $b_path;
        $result['thumb'] = $s_path;
        echo json_encode($result);
    }


    /**
     * 编辑器上传图片
     * @author 龚湧
     */
    public function action_editor(){
        $files = $_FILES;
        $filekey = "imgFile";
        try{
            $img = common::uploadPic($files,$filekey);
        }catch(Kohana_Exception $e){
            echo json_encode(array('error' => 1, 'message' => "上传失败"));
        }
        echo json_encode(array('error' => 0, 'url' => $img['path']));
    }

    /**
     * 上传海报图片
     * @author 潘宗磊
     */
    public function action_uploadPosterImg(){
        //返回结果
        $result = array("name"=>"","org"=>"","thumb"=>"");
        $error_result = json_encode($result);
        $files= $_FILES;
        $keys=array_keys($files);
        //TODO 可以抛出各种错误代码
        if(empty($keys)){
            echo $error_result;
            return false;
        }
        if ($files[$keys[0]]['name']==""){//如果上传图片为空并且原有图片也为空
            echo $error_result;
            return false;
        }
        if($files[$keys[0]]['error']!==0){
            echo $error_result;
            return false;
        }
        //原图片名称
        $result['name'] = $files[$keys[0]]['name'];
        try{
            $service = new Service_Platform_Poster();
            $img = $service->uploadPosterImg($files, $keys[0]);
        }catch(Kohana_Exception $e){
            echo $error_result;
            return false;
        }
        $result['org'] = $img;
        $result['thumb'] = "";
        echo json_encode($result);
    }
    /**
     * 上传视频
     * @author stone shi
     */
    public function action_uploadMovie() {
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 3600;//一个小时
        $service = new Service_Platform_BigPoster();
        /*返回结果
         * over : -1 未上传;0 上传了部分;1 文件已经存在;2 文件成功传完
         * unsuccessful : 没有传完的文件块编号
         */
        $result = array("piece"=>"","piece_num"=>"","error"=>"0","msg"=>"正常","over"=>"-1","file_path"=>"","unsuccessful"=>"");
        $raw_post_data = file_get_contents('php://input', 'r');
        //var_dump($raw_post_data);exit;
        //$raw_post_data = file_geet_contents('138439985256086077.txt');

        $separator = "|T|L|K|J|Y|J|H|";
        $data = explode($separator,$raw_post_data);
        echo json_encode($data);exit;
        if(current($data) == 'b'){
            if(count($data) == 6){
                $md5 = end($data);
                $suffix = $data[2];
                $piece_count = $data[3];
                $folder = $data[4];
                $file_name = $md5;
                //md5校验字符串写入缓存
                $memcache->set("big_poster_md5_".$md5,$md5,$_cache_get_time);
                //执行b段是，判断文件是不是已经存在
                $exist = $service->checkExistBigImg($file_name, $suffix, $folder);
                if($exist){
                    $result["error"] = "1";
                    $result["over"] = "1";
                    $result["msg"] = "海报文件已经存在";
                }
                else{
                    $block_num = $service->checkExistBlock($piece_count, $file_name, $folder);
                    if($block_num >=0){
                        $lost = array();
                        for($num = $block_num;$num < $piece_count;$num++){
                            $lost[] = $num;
                        }

                        $result["over"] = "0";
                        $result["unsuccessful"] = implode(",",$lost);
                        $success = $block_num -1;
                        $result["msg"] = "海报文件已经上传到".$success."段，请从".$block_num."段继续上传。";
                    }
                }
            }
            else{
                $result["error"] = "1";
                $result["msg"] = "b段格式有误";

            }
            $result["piece"] = "b";

        }
    }
    
    /**
     * 分割上传海报图片psd图片
     * @author 花文刚
     */
    public function action_uploadBigImg(){
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 3600;//一个小时
        $service = new Service_Platform_BigPoster();
        /*返回结果
         * over : -1 未上传;0 上传了部分;1 文件已经存在;2 文件成功传完
         * unsuccessful : 没有传完的文件块编号
         */
        $result = array("piece"=>"","piece_num"=>"","error"=>"0","msg"=>"正常","over"=>"-1","file_path"=>"","unsuccessful"=>"");
        $raw_post_data = file_get_contents('php://input', 'r');

        //$raw_post_data = file_get_contents('138439985256086077.txt');

        $separator = "|T|L|K|J|Y|J|H|";
        $data = explode($separator,$raw_post_data);

        if(current($data) == 'b'){
            if(count($data) == 6){
                $md5 = end($data);
                $suffix = $data[2];
                $piece_count = $data[3];
                $folder = $data[4];
                $file_name = $md5;
                //md5校验字符串写入缓存
                $memcache->set("big_poster_md5_".$md5,$md5,$_cache_get_time);
                //执行b段是，判断文件是不是已经存在
                $exist = $service->checkExistBigImg($file_name, $suffix, $folder);
                if($exist){
                    $result["error"] = "1";
                    $result["over"] = "1";
                    $result["msg"] = "海报文件已经存在";
                }
                else{
                    $block_num = $service->checkExistBlock($piece_count, $file_name, $folder);
                    if($block_num >=0){
                        $lost = array();
                        for($num = $block_num;$num < $piece_count;$num++){
                            $lost[] = $num;
                        }

                        $result["over"] = "0";
                        $result["unsuccessful"] = implode(",",$lost);
                        $success = $block_num -1;
                        $result["msg"] = "海报文件已经上传到".$success."段，请从".$block_num."段继续上传。";
                    }
                }
            }
            else{
                $result["error"] = "1";
                $result["msg"] = "b段格式有误";

            }
            $result["piece"] = "b";

        }

        if(current($data) == 'p'){
            $piece_num = $data[1];
            if(count($data) == 8){
                $piece = base64_encode(end($data));
                $suffix = $data[2];
                $folder = $data[3];
                $md5 = $data[4];
                $ready_size = $data[5];
                $size = $data[6];
                $md5_cache = $memcache->get("big_poster_md5_".$md5);
                $file_name = $md5;
                $block_name = $md5.'_'.$piece_num;

                $exist = $service->checkExistBigImg($file_name, $suffix, $folder);
                if($md5 != $md5_cache){
                    $result["error"] = "1";
                    $result["msg"] = "p段md5验证有误";
                }
                elseif($exist){
                    $result["error"] = "1";
                    $result["msg"] = "海报文件已经存在";
                }
                else{
                    if($service->generateFileBlock($piece, $block_name, $folder)){
                        $result["error"] = "0";
                        $result["msg"] = "p段写入成功";
                    }else{
                        $result["error"] = "1";
                        $result["msg"] = "p段写入有误";
                    }
                }
            }
            else{
                $result["error"] = "1";
                $result["msg"] = "p段格式有误";

            }
            $result["piece"] = "p";
            $result["piece_num"] = $piece_num;

        }

        if(current($data) == 'e'){
            if(count($data) == 6){
                $md5 = end($data);
                $size_total = $data[1];
                $suffix = $data[2];
                $piece_count = $data[3];
                $folder = $data[4];
                $md5_cache = $memcache->get("big_poster_md5_".$md5);
                $file_name = $md5;

                $exist = $service->checkExistBigImg($file_name, $suffix, $folder);
                $block_num = $service->checkExistBlock($piece_count, $file_name, $folder);
                $success = $block_num + 1;
                if($md5 != $md5_cache){
                    $result["error"] = "1";
                    $result["msg"] = "e段md5验证有误";
                }
                elseif($exist){
                    $result["error"] = "1";
                    $result["msg"] = "海报文件已经存在";
                }
                elseif($success != $piece_count){
                    $result["error"] = "1";
                    $result["msg"] = "已上传块数和总块数不一致";
                }
                else{
                    //合并文件块，生成大文件
                    $size = $service->uploadPosterBigImg($piece_count, $file_name, $suffix, $folder);
                    if($size == $size_total){
                        $result["over"] = "2";
                        $result["error"] = "0";
                        $result["msg"] = "e段通过验证，文件生成成功";
                        $result["file_path"] = 'upload_poster/'.$folder.'/'.$file_name.'.'.$suffix;
                    }
                    else{
                        $result["error"] = "1";
                        $result["msg"] = "生成文件大小有与原文件不符。";
                    }

                    //删除临时文件
                    $service->delTempBlock($piece_count, $file_name, $folder);
                }
            }
            else{
                $result["error"] = "1";
                $result["msg"] = "e段格式有误";

            }
            $result["piece"] = "e";

        }


        /*

                $name = time().rand();
                $file = fopen($name, 'w');
                fwrite($file, $raw_post_data);
        */

        echo json_encode($result);
    }
    
    /**
     * 上传视频
     * @author 郁政
     */
	public function action_uploadVideo(){		
		$res = uploadQiNiu::uploadFile(arr::get($_FILES, 'Filedata', array()));        
        echo json_encode($res);	exit;
	}
}