<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	'Обновить комментарии #'.$model->id,
);
?>

<h1>Обновить комментарии #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>