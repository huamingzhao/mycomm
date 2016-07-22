<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 搜索分词操作
 * @author 许晟玮
 *
 */
class Controller_Sapi_Platform_Search extends Controller_Sapi_Basic{

    /**
    * 分解获取分词
    * @author 许晟玮
    */
    public function action_getParticiple() {
        $service = new Service_Api_Search();
        $post = $this->request->post();
        $word= Arr::get($post, 'word');
        try{
            $return = $service->getSpecialColumnParticiple($word);
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }


    /**
     * 获取咨询的搜索结果
     * @author许晟玮
     */
    public function action_zixunsearch(){
        $service = new Service_Api_Search();
        $post = $this->request->post();
        $word= Arr::get($post, 'word');
        try{
            //去除当中的, 靠搜索引擎自行分词处理
            $key= str_replace(',','',$word);
            $return= $service->getSearchSpecialColumn($word,$key);

        }catch ( Kohana_Exception $e ){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);

    }
    //end function

}
