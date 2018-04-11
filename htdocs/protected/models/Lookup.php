<?php
/**
 * Ниже перечислены доступные столбцы в таблице 'tbl_lookup':
 * @property integer $id
 * @property string $object_type
 * @property integer $code
 * @property string $name_en
 * @property string $name_fr
 * @property integer $sequence
 * @property integer $status
 */
class Lookup extends CActiveRecord
{
	private static $_items=array();

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
		return '{{lookup}}';
	}

	/**
	 * Возвращает элементы для указанного типа.
	 * @param тип строкового объекта (e.g. 'PostStatus').
	 * @return имена элементов массива, индексированные по коду товара. Элементы упорядочены по их значениям позиции.
	 * Пустой массив возвращается, если тип элемента не существует.
*/
	public static function items($type)
	{
		if(!isset(self::$_items[$type]))
			self::loadItems($type);
		return self::$_items[$type];
	}

	/**
	*Возвращает имя элемента для указанного типа и кода.
	* @param строит тип элемента (например, «PostStatus»).
	* @param целое число кода элемента (соответствует значению столбца «code»)
	* @return строку имени элемента для указанного кода. False возвращается, если тип или код элемента не существует.
	*/
	public static function item($type,$code)
	{
		if(!isset(self::$_items[$type]))
			self::loadItems($type);
		return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
	}

	/**
	 *Загружает элементы поиска для указанного типа из базы данных.
	 * @param введите тип элемента
	 */
	private static function loadItems($type)
	{
		self::$_items[$type]=array();
		$models=self::model()->findAll(array(
			'condition'=>'type=:type',
			'params'=>array(':type'=>$type),
			'order'=>'position',
		));
		foreach($models as $model)
			self::$_items[$type][$model->code]=$model->name;
	}
}