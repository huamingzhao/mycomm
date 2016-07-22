<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目 操作日志
 * @author 郁政
 *        
 */
class Model_QuickProjectOperateLog extends ORM {
	
	/**
	 * 数据表名czzs_quick_project_operate_log
	 */
	protected $_table_name = 'quick_project_operate_log';
	
	/**
	 * 主键名称
	 */
	protected $_primary_key = 'id';
	
	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End QuickProjectOperateLog Model
?>