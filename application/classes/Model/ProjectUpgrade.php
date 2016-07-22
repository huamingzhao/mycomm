<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 申请招商外包项目
 * @author 周进
 *
*/
class Model_ProjectUpgrade extends ORM{

	/**
	 * 数据表名czzs_project_upgrade
	 */
	protected $_table_name  = 'project_upgrade';

	/**
	 * 主键名称
	 */
	protected $_primary_key = 'project_id';

	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';

}//End Account Model