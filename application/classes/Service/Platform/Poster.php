<?php
/**
 * 大后台海报管理service层
 * @author 龚湧
 *
 */
class Service_Platform_Poster{

    /**
    * soapclient实例
    */
    public static $instance;

    /**
     * @author 龚湧
     * @return SoapClient
     */
    public static function poster_instance(){
        if(!self::$instance){
            self::$instance = new SoapClient(
                    null,
                    array (
                            "location"=>URL::webstatic("poster.php"),
                            "uri"=>"qutouziPicupload",
                            "encoding"=>"UTF-8"
                    )
            );
        }
        return self::$instance;
    }

    /**
     * 判断外采海报是否存在
     * @param unknown $poster_id
     * @author 龚湧
     */
    public function isCollectPoster($poster_id){
        $obj = ORM::factory("PosterExits",$poster_id);
        if($obj->loaded()){
            if($obj->is_exits){
                return true;
            }
        }
        return false;
    }

    /**
     * 获取海报内容
     * @author 龚湧
     * @param unknown_type $project_id
     */
    public function get_poster($project_id){
        $client = self::poster_instance();
        return $client->get_files($project_id);
    }

    /**
     * 编辑项目海报
     * @author 龚湧
     * @param unknown_type $project_id
     * @param unknown_type $poster
     */
    public function save_poster($project_id,$poster){
        $client = self::poster_instance();
        return $client->save_files($project_id,$poster);
    }

    /**
     * 上传海报
     * @author 潘宗磊
     * @param array $files
     */
    public function uploadPosterImg($files,$filekey){
        $client = self::poster_instance();
        $files[$filekey]['tmp_name'] = base64_encode(file_get_contents($files[$filekey]['tmp_name']));
        $rtn = $client->uploadPosterImg($files,$filekey);
        return $rtn;
    }

    /**
     * 合并文件块，生成海报大文件
     * @author 花文刚
     * @date 2013-11-20
     */
    public function uploadPosterBigImg($piece_count, $file_name, $suffix, $folder){
        $client = self::poster_instance();

        $rtn = $client->uploadPosterBigImg($piece_count, $file_name, $suffix, $folder);
        return $rtn;
    }

    /**
     * 生成文件块
     * @author 花文刚
     * @date 2013-11-20
     */
    public function generateFileBlock($piece, $file_name, $folder){
        $client = self::poster_instance();

        $rtn = $client->generateFileBlock($piece, $file_name, $folder);
        return $rtn;
    }

    /**
     * 检查文件是否已经存在
     * @author 花文刚
     * @date 2013-11-20
     */
    public function checkExistBigImg($file_name, $suffix, $folder){
        $client = self::poster_instance();

        $rtn = $client->checkExistBigImg($file_name, $suffix, $folder);
        return $rtn;
    }

    /**
     * 检查已经上传了几个文件块
     * @author 花文刚
     * @date 2013-11-20
     */
    public function checkExistBlock($piece_count, $file_name, $folder){
        $client = self::poster_instance();

        $rtn = $client->checkExistBlock($piece_count, $file_name, $folder);
        return $rtn;
    }

    /**
     * 删除临时文件
     * @author 花文刚
     * @date 2013-11-20
     */
    public function delTempBlock($piece_count, $file_name, $folder){
        $client = self::poster_instance();

        $rtn = $client->delTempBlock($piece_count, $file_name, $folder);
        return $rtn;
    }

}