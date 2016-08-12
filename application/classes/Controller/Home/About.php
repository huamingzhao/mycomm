<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 首页
 * @author fery.zhao
 * @create_date 2016-07-26
 */
class Controller_Home_About extends Controller_Template{


    public function action_index()
    {
        $content= View::factory("home/about");
        $this->template->page = 'about';
        $this->template->title = mb_convert_encoding('关于我们',"utf-8");
        $this->template->description = mb_convert_encoding('关于我们页面',"utf-8");
        $this->template->keywords = mb_convert_encoding('关于我们',"utf-8");
        $this->template->content = $content;
        file_put_contents('about.html',$this->template);
    }

}
