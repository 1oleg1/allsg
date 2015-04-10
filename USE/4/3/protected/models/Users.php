<?php
/**
 * Модель для работы с пользователями
 * 
 * @author Vladimir Statsenko
 */
class Users extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'ygs_users':
	 * @var integer $u_id
	 * @var string $u_name
	 * @var string $u_login
	 * @var string $u_password
	 * @var string $u_xml
	 */
	public $u_pass_conf;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ygs_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('u_name','length','min'=>3,'max'=>50),
			array('u_login','length','min'=>3,'max'=>50),
			array('u_password','length','min'=>3,'max'=>70),
			array('u_pass_conf','length','min'=>3,'max'=>70),
			array('u_pass_conf', 'compare', 'compareAttribute'=>'u_password'),
			array('u_xml','length','max'=>255),
			array('u_name, u_login, u_password, u_pass_conf, u_xml', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'u_id' => 'ID',
			'u_name' => 'Имя',
			'u_login' => 'Логин',
			'u_password' => 'Пароль',
			'u_pass_conf' => 'Повторите пароль',
			'u_xml' => 'Feed Xml',
		);
	}

	public function safeAttributes() {
		return array('u_name','u_login','u_password','u_xml','u_pass_conf');
	}
	
	/**
	 * Преобразует пароль, введеный в форму в его md5 хеш
	 * @return boolean true - продолжаем сохранение
	 */
	public function beforeSave() {
		$this->u_password = md5($this->u_password);
		$this->u_pass_conf = md5($this->u_pass_conf);
		return true;
	}
}