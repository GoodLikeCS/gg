
<?php

/**
* Класс ContactForm.
  * ContactForm - это структура данных для хранения
  * связь форма данные. Он используется действием «контакт» «SiteController».
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $verifyCode;

	/**
* Объявляет правила проверки.
	 */
	public function rules()
	{
		return array(
		// требуется имя, адрес электронной почты, тема и тело
			array('name, email, subject, body', 'required'),
		// email должен быть действительным адресом электронной почты
			array('email', 'email'),
		// проверочный код необходимо ввести правильно
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

		/**
		* Объявляет индивидуальные метки атрибутов.
		* Если здесь не указано, атрибут имеет метку, которая
		* то же, что и его имя с первой буквой в верхнем регистре.
		*/
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Введите код с картинки',
		);
	}
}