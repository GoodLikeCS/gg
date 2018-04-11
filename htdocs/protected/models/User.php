<?php

/**
 * Ниже перечислены доступные столбцы в таблице.'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $profile
 */
class User extends CActiveRecord
{
	/**
* Возвращает статическую модель указанного класса AR.
	 * @return статический класс статической модели
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return введите имя связанной таблицы базы данных
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return правила проверки массива для атрибутов модели.
	 */
	public function rules()
	{
// ПРИМЕЧАНИЕ: вы должны определять правила для тех атрибутов, которые
// будет принимать пользовательские входы.
		return array(
			array('username, password, email', 'required'),
			array('username, password, email', 'length', 'max'=>128),
			array('profile', 'safe'),
		);
	}

	/**
	 * @return массивные реляционные правила.
	 */
	public function relations()
	{
// ПРИМЕЧАНИЕ: вам может потребоваться настроить имя отношения и связанные с ним
// имя класса для автоматически генерируемых ниже отношений.
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
		);
	}

	/**
	 * @return ярлыки атрибутов массива (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Имя пользователя',
			'password' => 'Пароль',
			'email' => 'Емаил',
			'profile' => 'Профиль',
		);
	}

	/**
* Проверяет правильность заданного пароля.
	 * @param введите пароль для проверки
	 * @return boolean, является ли пароль действительным
	 */
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}

	/**
* Генерирует хэш пароля.
	 * @param строковый пароль
	 * @return хеш-строка
	 */
	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
}
