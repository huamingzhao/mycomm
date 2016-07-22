<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目
 * 
 * @author 嵇烨
 *        
 */
class Model_Quickproject extends ORM {
	
	/**
	 * 数据表名czzs_quick_project
	 */
	protected $_table_name = 'quick_project';
	
	/**
	 * 主键名称
	 */
	protected $_primary_key = 'project_id';
	
	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model