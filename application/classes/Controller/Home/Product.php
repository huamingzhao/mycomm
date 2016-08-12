<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 首页
 * @author fery.zhao
 * @create_date 2016-07-22
 */
class Controller_Home_Product extends Controller_Template
{

    /**
     * 产品列表
     */
    public function action_list()
    {
        $content= View::factory("home/productList");
        $list = array(
            array(
                'url_name' => 'jy',
                'pro_name' => '精油',
                'image_url'  => '/images/jyimages/01.jpg'
            ),
            array(
                'url_name' => 'nj',
                'pro_name' => '凝胶',
                'image_url'  => '/images/njimages/01.jpg'
            ),
            array(
                'url_name' => 'xm',
                'pro_name' => '胸膜',
                'image_url'  => '/images/hssimages/01.jpg'
            ),
        );
        $content->list = $list;



        $this->template->page = 'product';
        $this->template->title = mb_convert_encoding('一句话创业资讯_专注创业指导_一句话商机速配网创业资讯',"utf-8");
        $this->template->description = mb_convert_encoding('一句话创业资讯频道是最专业的创业指导平台，在1句话资讯平台上你可以学习如何找项目创业、如何投资项目、如何创业，并可以了解别人的创业历程及创业投资中遇到的问题及处理方法。',"utf-8");
        $this->template->keywords = mb_convert_encoding('一句话创业资讯,一句话学开店,创业指导,创业资讯,加盟资讯',"utf-8");
        $this->template->content = $content;
        file_put_contents('pro_list.html',$this->template);
    }

    /**
     * 产品详情页
     */
    public function action_info()
    {
        $arr = array('jy','nj','xm');
        foreach ($arr as $id) {
            $content= View::factory("home/product".$id);
            $this->template->page = 'product';
            $this->template->title = mb_convert_encoding('一句话创业资讯_专注创业指导_一句话商机速配网创业资讯',"utf-8");
            $this->template->description = mb_convert_encoding('一句话创业资讯频道是最专业的创业指导平台，在1句话资讯平台上你可以学习如何找项目创业、如何投资项目、如何创业，并可以了解别人的创业历程及创业投资中遇到的问题及处理方法。',"utf-8");
            $this->template->keywords = mb_convert_encoding('一句话创业资讯,一句话学开店,创业指导,创业资讯,加盟资讯',"utf-8");
            $this->template->content = $content;
            file_put_contents($id .'.html',$this->template);
        }
    }

}
