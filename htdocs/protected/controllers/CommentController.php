<?php

class CommentController extends Controller
{
	public $layout='column2';

	/**
	 * @var CActiveRecord текущий загруженный экземпляр модели данных.
	 */
	private $_model;

	/**
	 * @return фильтры действия массива
	 */
	public function filters()
	{
		return array(
			'accessControl', // выполнять контроль доступа для операций CRUD
		);
	}

	/**
	 * Определяет правила контроля доступа.
	 * Этот метод используется фильтром accessControl.
	 * @return правила управления доступом к массиву
	 */
	public function accessRules()
	{
		return array(
			array('allow', // разрешить пользователям, прошедшим проверку подлинности, доступ ко всем действиям
				'users'=>array('@'),
			),
			array('deny',  // отказывать всем пользователям
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Обновляет определенную модель.
	 * Если обновление выполнено успешно, браузер будет перенаправлен на страницу просмотра.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 *Удаляет определенную модель.
	 * Если удаление выполнено успешно, браузер будет перенаправлен на страницу «index».
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// мы разрешаем удаление только через запрос POST
			$this->loadModel()->delete();

			// если запрос AJAX (вызванный удалением через представление сетки администратора), мы не должны перенаправлять браузер
			if(!isset($_POST['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Неверный запрос. Повторите этот запрос еще раз.');
	}

	/**
	 * Перечисляет все модели.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Comment', array(
			'criteria'=>array(
				'with'=>'post',
				'order'=>'t.status, t.create_time DESC',
			),
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	* Утверждает конкретный комментарий.
	* Если утверждение будет успешным, браузер будет перенаправлен на страницу индекса комментариев.
	 */
	public function actionApprove()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$comment=$this->loadModel();
			$comment->approve();
			$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Неверный запрос. Повторите этот запрос еще раз.');
	}

	/**
* Возвращает модель данных на основе первичного ключа, указанного в переменной GET.
* Если модель данных не найдена, будет вызвано исключение HTTP.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Comment::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'Запрошенная страница не существует.');
		}
		return $this->_model;
	}
}
