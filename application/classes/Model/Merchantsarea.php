<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目
 *
 * @author 嵇烨
 *
*/
class Model_Merchantsarea extends ORM {

	/**
	 * 数据表名czzs_merchants_area
	 */
	protected $_table_name = 'merchants_area';

	/**
	 * 主键名称
	 */
	protected $_primary_key = 'merchants_area_id';

	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model