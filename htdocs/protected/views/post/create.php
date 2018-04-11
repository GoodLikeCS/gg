<?php
$this->breadcrumbs=array(
	'Создать пост',
);
?>
<h1>Создать пост</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>