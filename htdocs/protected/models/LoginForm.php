<?php

/**
	  * Класс LoginForm.
	  * LoginForm - это структура данных для хранения
	  * данные формы входа пользователя. Он используется действием «login» для SiteController.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	* Объявляет правила проверки.
	* В правилах указано, что требуется имя пользователя и пароль,
	* и пароль должен быть аутентифицирован.
	 */
	public function rules()
	{
		return array(
// требуется имя пользователя и пароль.
			array('username, password', 'required'),
// rememberMe должно быть логическим
			array('rememberMe', 'boolean'),
// пароль должен быть аутентифицирован
			array('password', 'authenticate'),
		);
	}

	/**
* Объявляет метки атрибутов.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Запомнить меня',
		);
	}

	/**
* Аутентификация пароля.
* Это аутентификатор проверки подлинности, как указано в правилах ().
	 * @param string $ attribute - имя атрибута, подлежащего проверке.
	 * @param дополнительные параметры массива $ params, переданные с правилом при выполнении.
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate())
			$this->addError('password','Неверное имя пользователя или пароль');
	}

	/**
* Регистрирует пользователя с использованием данного имени пользователя и пароля в модели.
	 * @return boolean, является ли логин успешным
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 дней
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
