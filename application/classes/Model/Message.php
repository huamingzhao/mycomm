<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目留言
 *
 * @author 嵇烨
 *
*/
class Model_Message extends ORM {

	/**
	 * 数据表名czzs_quick_message
	 */
	protected $_table_name = 'quick_message';

	/**
	 * 主键名称
	 */
	protected $_primary_key = 'id';

	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model