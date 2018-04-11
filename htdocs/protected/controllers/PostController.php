<?php

class PostController extends Controller
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
* Указывает правила управления доступом.
* Этот метод используется фильтром accessControl.
	 * @return правила управления доступом к массиву
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // разрешать всем пользователям доступ к действиям'index' and 'view'.
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // разрешить пользователям, прошедшим проверку подлинности, доступ ко всем действиям
				'users'=>array('@'),
			),
			array('deny',  // Отказ всем пользователям
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Отображает определенную модель.
	 */
	public function actionView()
	{
		$post=$this->loadModel();
		$comment=$this->newComment($post);

		$this->render('view',array(
			'model'=>$post,
			'comment'=>$comment,
		));
	}

	/**
		* Создает новую модель.
		* Если создание будет успешным, браузер будет перенаправлен на страницу просмотра.
	 */
	public function actionCreate()
	{
		$model=new Post;
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Обновляет определенную модель.
	 * Если обновление выполнено успешно, браузер будет перенаправлен на страницу просмотра.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	* Удаляет определенную модель.
	* Если удаление выполнено успешно, браузер будет перенаправлен на страницу «index».
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// мы разрешаем удаление только через запрос POST
			$this->loadModel()->delete();

			// если запрос AJAX (вызванный удалением через представление сетки администратора), мы не должны перенаправлять браузер
			if(!isset($_GET['ajax']))
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
		$criteria=new CDbCriteria(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'with'=>'commentCount',
		));
		if(isset($_GET['tag']))
			$criteria->addSearchCondition('tags',$_GET['tag']);

		$dataProvider=new CActiveDataProvider('Post', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['postsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Управляет всеми моделями.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	* Предлагает теги на основе текущего ввода пользователя.
	* Это вызвано через AJAX, когда пользователь вводит ввод тэгов.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
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
			{
				if(Yii::app()->user->isGuest)
					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
				else
					$condition='';
				$this->_model=Post::model()->findByPk($_GET['id'], $condition);
			}
			if($this->_model===null)
				throw new CHttpException(404,'Запрошенная страница не существует.');
		}
		return $this->_model;
	}

	/**
	* Создает новый комментарий.
	* Этот метод пытается создать новый комментарий на основе ввода пользователя.
	* Если комментарий успешно создан, браузер будет перенаправлен
	* показать созданный комментарий.
	 * @param Опубликовать пост, что новый комментарий принадлежит
	 * @return Комментировать экземпляр комментария
	 */
	protected function newComment($post)
	{
		$comment=new Comment;
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($post->addComment($comment))
			{
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Спасибо за ваш комментарий. Ваш комментарий будет опубликован после его утверждения.');
				$this->refresh();
			}
		}
		return $comment;
	}
}
