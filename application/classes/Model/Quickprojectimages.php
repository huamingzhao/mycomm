<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 项目图片
 * 
 * @author 嵇烨
 *        
 *        
 */
class Model_Quickprojectimages extends ORM {
	
	/**
	 * 数据表名czzs_quick_project_images
	 */
	protected $_table_name = 'quick_project_images';
	
	/**
	 * 主键名称
	 */
	protected $_primary_key = 'project_image_id';
	
	/**
	 * 数据库连接配置
	 */
	protected $_db_group = 'default';
}//End Accountlog Model
