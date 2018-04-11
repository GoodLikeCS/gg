<?php
/**
 * Ниже перечислены доступные столбцы в таблице  'tbl_comment':
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property string $author
 * @property string $email
 * @property string $url
 * @property integer $post_id
 */
class Comment extends CActiveRecord
{
	const STATUS_PENDING=1;
	const STATUS_APPROVED=2;

	/**
	 * Возвращает статическую модель указанного AR класса.
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
		return '{{comment}}';
	}

	/**
	 * @return правила проверки массива для атрибутов модели.
	 */
	public function rules()
	{
		// ПРИМЕЧАНИЕ: вы должны определять правила для тех атрибутов, которые
		// будет принимать пользовательские входы.
		return array(
			array('content, author, email', 'required'),
			array('author, email, url', 'length', 'max'=>128),
			array('email','email'),
			array('url','url'),
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
			'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return массива с метками атрибутов (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'content' => 'Комментарий',
			'status' => 'Статус',
			'create_time' => 'Время создания',
			'author' => 'Имя',
			'email' => 'Е-маил',
			'url' => 'Веб-сайт',
			'post_id' => 'Post',
		);
	}

	/**
	 * Утверждает комментарий.
	 */
	public function approve()
	{
		$this->status=Comment::STATUS_APPROVED;
		$this->update(array('status'));
	}

	/**
	 * @param Опубликовать сообщение, которому принадлежит этот комментарий. Если значение null, в  метод.
		* будет запрашивать сообщение.
	 * @return Введите URL-адрес для этой ссылки
	 */
	public function getUrl($post=null)
	{
		if($post===null)
			$post=$this->post;
		return $post->url.'#c'.$this->id;
	}

	/**
	 * @return строка отображения гиперссылки для автора текущего комментария
	 */
	public function getAuthorLink()
	{
		if(!empty($this->url))
			return CHtml::link(CHtml::encode($this->author),$this->url);
		else
			return CHtml::encode($this->author);
	}

	/**
	 * @return целое число комментариев, ожидающих утверждения
	 */
	public function getPendingCommentCount()
	{
		return $this->count('status='.self::STATUS_PENDING);
	}

	/**
	 * @param целое число максимальное количество комментариев, которые должны быть возвращены
	 * @return массив последних добавленных комментариев
	 */
	public function findRecentComments($limit=10)
	{
		return $this->with('post')->findAll(array(
			'condition'=>'t.status='.self::STATUS_APPROVED,
			'order'=>'t.create_time DESC',
			'limit'=>$limit,
		));
	}

	/**
	 * Это вызывается до сохранения записи.
	 * @return boolean, следует ли сохранять запись.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
				$this->create_time=time();
			return true;
		}
		else
			return false;
	}
}