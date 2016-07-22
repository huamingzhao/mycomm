<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 重载ORM，加载需要的方法，因此USER模块需要在ORM之前加载
 *
 * @author  钟涛
 */
class ORM extends Kohana_ORM {


    protected $_search_row = array();
    protected $_list_row = array();

    /**
     * 获取搜索列的相关信息
     * Enter description here ...
     */
    public function getSearchRow(){
        return array_intersect_key($this->_table_columns, array_fill_keys($this->_search_row, null));
    }

    /**
     * 获取显示在列表页列
     */
    public function getListRow(){
        if(empty($this->_list_row)){
            return $this->_table_columns;
        } else {
            return array_intersect_key($this->_table_columns, array_fill_keys($this->_list_row, null));
        }

    }


    /**
     * @static
     * 检查某一列是否可以模糊查询
     */
    public static function isFuzzyQuery(array $column){
        return $column['type'] === 'string';
    }

}
