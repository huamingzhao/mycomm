<?php defined('SYSPATH') or die('No direct script access.');

class uploadQiNiu{
    private static $_bucket;
    private static $_accessKey;
    private static $_secretKey;

    public static function before(){
         self::$_bucket = Kohana::$config->load('conf.bucket');
         self::$_accessKey = Kohana::$config->load('conf.QINIU_ACCESS_KEY');
         self::$_secretKey = Kohana::$config->load('conf.QINIU_SECRET_KEY');
         Qiniu_SetKeys(self::$_accessKey, self::$_secretKey);
     }
     
    public static function fileInfo($fileName) {
        if(!$fileName) return FALSE; 
        self::before();
        $client = new Qiniu_MacHttpClient(null);
        list($ret, $err) = Qiniu_RS_Stat($client, self::$_bucket, $fileName);
        if ($err !== null) {
           return false;
        } else {
            // array(4) { ["fsize"]=> int(1565598) ["hash"]=> string(28) "FvSiPicSDWOS938EtNM1EWMSXfMz" ["mimeType"]=> string(11) "video/x-flv" ["putTime"]=> int(13969360272134379)
            return $ret;
        }
    } 
    
    public  static function deleteFile($fileName) {
        if(!$fileName) return FALSE; 
        self::before();
        $client = new Qiniu_MacHttpClient(null);
        $err = Qiniu_RS_Delete($client, self::$_bucket, $fileName);
        echo "====> Qiniu_RS_Delete result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            echo "Success!";
        }
    }
    
    /**
     * @param type $file 
     * @param type $fileName
     * @return boolean
     */
    public static function uploadFile($file, $fileName = '') {
        if(!$file) return FALSE; 
        if(!$file['tmp_name']) return FALSE; 
        $temp_ext = explode(".", $file['name']);
        $ext = strtolower(array_pop($temp_ext));
        $fileName = $fileName ? $fileName : time().rand(0,999).'.'.$ext;
        self::before();
        $putPolicy = new Qiniu_RS_PutPolicy(self::$_bucket);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $fileName, $file['tmp_name'], $putExtra);
        if ($err !== null) {
            return false;
        } else {
            //array(2) { ["hash"]=> string(28) "FvSiPicSDWOS938EtNM1EWMSXfMz" ["key"]=> string(17) "1396936023410.flv" }
            return $ret;
        }
    }
}