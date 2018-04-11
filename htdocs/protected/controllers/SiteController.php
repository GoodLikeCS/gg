<?php

class SiteController extends Controller
{
	public $layout='column1';

	/**
	 * Объявляет действия на основе классов.
	 */
	public function actions()
	{
		return array(
			// captcha action отображает изображение CAPTCHA, отображаемое на странице контактов
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action отображает «статические» страницы, хранящиеся в разделе  'protected/views/site/pages'
			// Доступ к ним можно получить через: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * Это действие для обработки внешних исключений.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Отображает страницу контактов
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Отображает страницу входа в систему
	 */
	public function actionLogin()
	{
		if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
			throw new CHttpException(500,"Это приложение требует, чтобы PHP был скомпилирован с поддержкой Blowfish для crypt ().");

		$model=new LoginForm;

		// если это запрос проверки ajax
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// собирать пользовательские входные данные
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// проверять ввод пользователя и перенаправлять на предыдущую страницу, если действительны
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// отобразить форму входа
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Выводит текущего пользователя и перенаправляет его на главную страницу.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
