<?php
class Controller_DemoEditor extends Controller{
    public function action_index(){
        $view = View::factory("editorDemo");
        echo $view->render();
    }
}