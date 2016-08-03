<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Controller_Template extends Controller{

    /**
    * @var  View  page template
    */
    public $template = 'template';

    /**
    * @var  boolean  auto render template
    **/
    public $auto_render = TRUE;

    /**
    * Loads the template [View] object.
    */
    public function before()
    {

        parent::before();

        $path = $_SERVER['REQUEST_URI'];
        $path = strtolower($path);
        $get = $this->request->query();
        if(arr::get(Kohana::$config->load("staticpage.ALL_PATH"), $path) && Kohana::$config->load("staticpage.STATIC_STATUS")) {
            $key = arr::get(Kohana::$config->load("staticpage.ALL_PATH"), $path);
            $filename = Kohana::$config->load("staticpage.{$key}");
            $content = file_get_contents($filename);
            if($content) {
                echo $content;
                exit;
            }
        }

        if ($this->auto_render === TRUE)
        {
            // Load the template
            $this->template = View::factory($this->template);

            //记录前一个url start
            /*<?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>*/
            $black_list = array("member/register","member/login");//特殊处理
            if(in_array($this->request->uri(),$black_list)){
                $last_uri = $this->request->query("to_url");
                if($last_uri){
                    $to_url = urldecode($last_uri);//上一个的url
                }
                else{
                    $to_url = "";
                }
            }
            else{
                $re_uri=$_SERVER['REQUEST_URI'];
                $host_url=$_SERVER['HTTP_HOST'];
                if(@stristr($host_url,'wen') ===FALSE){
                    $to_url = Helper_URL::website($re_uri);
                }else{
                    $to_url =Helper_URL::webwen($re_uri);
                }
            }

            //检验url的合法性
            if($to_url){
                if(strpos(urldecode($to_url),Helper_URL::website(""))!==0){//www是否合法
                    if(strpos(urldecode($to_url),Helper_URL::webwen(""))!==0){//wen是否合法
                        $to_url = '';
                    }
                }
                $to_url = urlencode($to_url);
            }

            View::bind_global("to_url",$to_url);

        }
    }

    /**
    * Assigns the template [View] as the request response.
    */
    public function after()
    {
        if ($this->auto_render === TRUE)
        {
            $this->response->body($this->template->render());
        }

        parent::after();
    }
}
