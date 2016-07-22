<?php
/**
 * 编辑器初始化
 * @author 龚湧
 */
class Kohana_Editor{
    /**
    * 在模板中使用方法
    * <?php echo Editor::factory("","simple",array("width"=>400));?>
    *
    * @author 龚湧
    * @param string $content 编辑器初始化内容
    * @param string $style 编辑器模板 可以自己添加 目前为default 和simple
    * @param array $config 编辑器配置，字段名称 默认为 editor_content ，编辑器宽度 800，高度 默认为400
    * @return string
    */
    public static function factory($content="",$style="default",$config=array("field_name"=>"editor_content","width"=>"800","height"=>"400")){
        $editor = View::factory("editor/{$style}");
        $editor->content = $content;
        $editor->field_name = Arr::get($config, "field_name")?Arr::get($config, "field_name"):"editor_content";
        $editor->height= Arr::get($config, "height")?Arr::get($config, "height"):"400";
        $editor->width = Arr::get($config, "width")?Arr::get($config, "width"):"800";
        return $editor->render();
    }
}