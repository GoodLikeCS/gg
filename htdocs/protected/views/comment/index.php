<?php
$this->breadcrumbs=array(
	'Комментарии',
);
?>

<h1>Комментарии</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
