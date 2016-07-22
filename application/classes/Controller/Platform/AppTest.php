<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Platform_AppTest extends Controller_Platform_Template{
	public function action_index(){
		$content = View::factory("platform/appTest");
        $this->template = $content ;
	}
}
?>