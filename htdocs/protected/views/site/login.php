<?php
$this->pageTitle=Yii::app()->name . ' - Авторизация';
$this->breadcrumbs=array(
	'Авторизация',
);
?>

<h1>Авторизация</h1>

<p>Пожалуйста, заполните следующую форму с вашими учетными данными:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
));
?>

	<p class="note">Поля с <span class="required">*</span> обязательны для заполнения.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username',array('label'=>'Имя пользователя')); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password',array('label'=>'Пароль')); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Подсказка: вы можете войти в систему с <tt>demo/demo</tt>.
		</p>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Авторизоваться'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
