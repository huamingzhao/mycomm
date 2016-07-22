<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Platform_Plugin extends Controller_Platform_Template{
	public function action_index(){
		$content = View::factory('plugin');
		$this->content->maincontent = $content;
	}
}