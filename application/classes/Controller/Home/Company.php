<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 首页
 * @author fery.zhao
 * @create_date 2016-07-22
 */
class Controller_Home_Company extends Controller_Template{


    public function action_index()
    {
        $content= View::factory("home/company");
        $this->template->page = 'company';
        $this->template->title = mb_convert_encoding('一句话创业资讯_专注创业指导_一句话商机速配网创业资讯',"utf-8");
        $this->template->description = mb_convert_encoding('一句话创业资讯频道是最专业的创业指导平台，在1句话资讯平台上你可以学习如何找项目创业、如何投资项目、如何创业，并可以了解别人的创业历程及创业投资中遇到的问题及处理方法。',"utf-8");
        $this->template->keywords = mb_convert_encoding('一句话创业资讯,一句话学开店,创业指导,创业资讯,加盟资讯',"utf-8");
        $this->template->content = $content;
        file_put_contents('company.html',$this->template);
    }

}
