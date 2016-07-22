<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目 赞表
 * 
 * @author stone shi
 *        
 */
class Model_QuickApproingLogs extends ORM {
	
	protected $_table_name = 'quick_approing_logs';
	
	/**
	 * 主键名称
	 */
	protected $_primary_key = 'id';
	
	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model