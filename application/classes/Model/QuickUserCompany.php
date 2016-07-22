<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 快速发布项目
 *
 * @author 嵇烨
 *
*/
class Model_QuickUserCompany extends ORM {
	/**
	 * 加密手机号码
	 * (non-PHPdoc)
	 * @see Kohana_ORM::filters()
	 */
	public function filters()
	{
		return array(
				'com_phone' => array(
						array("common::encodeMoible",array(":value")),
				)
		);
	}
	
	public function deFileters(){
		return array(
				'com_phone' => array(
						array("common::decodeMoible",array(":value")),
				)
		);
	}
	
	/**
	 * 数据表名 czzs_quick_user_company
	 */
	protected $_table_name = 'quick_user_company';

	/**
	 * 主键名称
	 */
	protected $_primary_key = 'com_id';

	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model