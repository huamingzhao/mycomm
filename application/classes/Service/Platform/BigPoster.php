<?php
/**
 * 大后台海报管理service层
 * @author 龚湧
 *
 */
class Service_Platform_BigPoster{

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
                            "location"=>URL::imgurl("big_poster.php"),
                            "uri"=>"qutouziPicupload",
                            "encoding"=>"UTF-8"
                    )
            );
        }
        return self::$instance;
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