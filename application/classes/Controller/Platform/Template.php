<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 新平台前端页面模板
 * @author 钟涛
 */
class Controller_Platform_Template extends Controller_Template{
    /**
    * @var view
    */
    public $content;
    public $title;
    public $description;
    public $keywords;

    /**
     * 新平台前端
     * @var 二级模板,指定即可
     */
    private  $content_template = "platform/template";

    public function before(){
        parent::before();
        $this->template->content = $this->content = View::factory($this->content_template);
        $this->content->set_global("verification_code",common::verification_code());
        //设置默认值
        $this->template->title = '';
        $this->template->description = '';
        $this->template->keywords = '';
        $this->content->maincontent = '';
    }

    public function after(){
        parent::after();
    }
}