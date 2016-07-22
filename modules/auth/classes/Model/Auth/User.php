<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default auth user
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model_Auth_User extends ORM {
    /**
     * 表名称
     */
    protected $_table_name = "ssouser";

    /**
     * 主键名称
     */
    protected $_primary_key = 'user_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     *
     * 对应关系
     */
    protected $_has_one = array(
            'user_company' => array(
                    'model'       => 'Companyinfo',
                    'foreign_key' => 'com_user_id',
            ),
            'user_person' => array(
                    'model'       => 'Personinfo',
                    'foreign_key' => 'per_user_id',
            ),
    );

    /**
     *
     * 对应关系
     */
    protected $_has_many = array(//对应关系 个人用户对应多个投资经验
            'user_experiences' => array(
                'model'       => 'Experience',
                'foreign_key' => 'exp_user_id',
            ),
            'valid_code' =>array(//验证码
                'model' =>'Validcode',
                'foreign_key' =>'user_id'
            ),
    );


    /**
     * 密码验证
     *
     * @param array $values
     * @return Validation
     */
    public static function get_password_validation($values)
    {
        return Validation::factory($values)
        ->rule('password', 'min_length', array(':value', 6))
        ->rule('password', 'max_length', array(':value', 20))
        ->rule('confirm', 'matches', array(':validation', ':field', 'password'));
    }


    /**
     * 做一些更新操作
     * Complete the login for a user by incrementing the logins and saving login timestamp
     *
     * @return void
     */
    public function complete_login()
    {
        if ($this->_loaded)
        {
            // Set the last login date
            $this->last_login = time();

            // Save the user
            $this->update();
        }
    }


    /**
     * Create a new user 用户注册
     *
     * Example usage:
     * ~~~
     * $user = ORM::factory('User')->create_user($_POST, array(
     *    'username',
     *    'password',
     *    'email',
     * );
     * 
     *
     * @param array $values
     * @param array $expected
     * @throws ORM_Validation_Exception
     */
    public function create_user($values, $expected)
    {
        // Validation for passwords
        $extra_validation = Model_User::get_password_validation($values)
            ->rule('password', 'not_empty');
        //$extra_validation->errors($file,)

        return $this->values($values, $expected)->create($extra_validation);
    }

    /**
     * Update an existing user
     *
     * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
     *
     * Example usage:
     * ~~~
     * $user = ORM::factory('User')
     *    ->where('username', '=', 'kiall')
     *    ->find()
     *    ->update_user($_POST, array(
     *        'username',
     *        'password',
     *        'email',
     *    );
     * ~~~
     *
     * @param array $values
     * @param array $expected
     * @throws ORM_Validation_Exception
     */
    public function update_user($values, $expected = NULL)
    {
    }


    /**
     * 取得用户基本信息
     */
    public function get_userinfo($user_id){
        return $this->where("user_id", "=", $user_id)->find();
    }
    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
    		'valid_mobile',//是否手机验证
    );

} // End Auth User Model
