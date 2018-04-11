<?php
/**
 * Ниже перечислены доступные столбцы в таблице.'tbl_post':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 */
class Post extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;

	private $_oldTags;

	/**
	 * Возвращает статическую модель указанного AR класса.
	 * @return статический класс статической модели.
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
		return '{{post}}';
	}

	/**
	 * @return правила проверки массива для атрибутов модели.
	 */
	public function rules()
	{
	// ПРИМЕЧАНИЕ: вы должны определять правила для тех атрибутов, которые
	// будет принимать пользовательские входы.
		return array(
			array('title, content, status', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>128),
			array('tags', 'match', 'pattern'=>'/^[\W\s,]+$/', 'message'=>'Теги могут содержать только русские буквы.'),
			array('tags', 'normalizeTags'),

			array('title, status', 'safe', 'on'=>'поиск'),
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
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time DESC'),
			'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),
		);
	}

	/**
	 * @return ярлыки атрибутов массива (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Заголовок',
			'content' => 'Текст',
			'tags' => 'Теги',
			'status' => 'Статус',
			'create_time' => 'Время создания',
			'update_time' => 'Время обновления',
			'author_id' => 'Автор',
		);
	}

	/**
	 * @return введите URL-адрес, который показывает деталь сообщения
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('post/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}

	/**
	 * @return array список ссылок, которые указывают на список сообщений, отфильтрованный каждым тегом этого сообщения
	 */
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=CHtml::link(CHtml::encode($tag), array('post/index', 'tag'=>$tag));
		return $links;
	}

	/**
	 * Нормализует введенные пользователем теги.
	 */
	public function normalizeTags($attribute,$params)
	{
		$this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
* Добавляет новый комментарий к этому сообщению.
* Этот метод соответственно установит статус и post_id комментария.
	 * @param Комментировать добавленный комментарий
	 * @return boolean, успешно сохранен ли комментарий
	 */
	public function addComment($comment)
	{
		if(Yii::app()->params['commentNeedApproval'])
			$comment->status=Comment::STATUS_PENDING;
		else
			$comment->status=Comment::STATUS_APPROVED;
		$comment->post_id=$this->id;
		return $comment->save();
	}

	/**
	 * Это вызывается, когда запись заполняется данными из find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldTags=$this->tags;
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
			{
				$this->create_time=$this->update_time=time();
				$this->author_id=Yii::app()->user->id;
			}
			else
				$this->update_time=time();
			return true;
		}
		else
			return false;
	}

	/**
	 * Это вызывается после сохранения записи.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		Tag::model()->updateFrequency($this->_oldTags, $this->tags);
	}

	/**
	 * Это вызывается после удаления записи.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Comment::model()->deleteAll('post_id='.$this->id);
		Tag::model()->updateFrequency($this->tags, '');
	}

	/**
* Получает список сообщений на основе текущих условий поиска / фильтрации.
	 * @return CActiveDataProvider поставщик данных, который может возвращать необходимые сообщения.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title,true);

		$criteria->compare('status',$this->status);

		return new CActiveDataProvider('Post', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'status, update_time DESC',
			),
		));
	}
}