<?php

/**
 * UserIdentity представляет данные, необходимые для идентификации пользователя.
 * Он содержит метод проверки подлинности, который проверяет, предоставлено ли.
 * данные могут идентифицировать пользователя.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * Аутентификация пользователя.
	 * @return boolean успешно ли выполняется проверка подлинности.
	 */
	public function authenticate()
	{
		$user=User::model()->find('LOWER(username)=?',array(strtolower($this->username)));
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return целочисленный идентификатор записи пользователя
	 */
	public function getId()
	{
		return $this->_id;
	}
}