<?php
$this->pageTitle=Yii::app()->name . ' - Связаться с нами';
$this->breadcrumbs=array(
	'Контакты',
);?>

<h1>Связаться с нами</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
Если у вас есть деловые вопросы или другие вопросы, пожалуйста, заполните следующую форму, чтобы связаться с нами. Спасибо.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note">Поля с <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name',array('label'=>'Имя пользователя')); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email',array('label'=>'Е-маил')); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject',array('label'=>'Тема')); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body',array('label'=>'Тело письма')); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
	</div>
<script type="text/javascript" src="../../js/plupload.full.min.js"></script>

<div id="filelist">Ваш браузер не поддерживает Flash, Silverlight или HTML5.</div>
<br />

<div id="container">
    <a id="pickfiles" href="javascript:;">[Выбрать файлы]</a> 
    <a id="uploadfiles" href="javascript:;">[Загрузить файлы]</a>
</div>

<br />
<pre id="console"></pre>


<script type="text/javascript">
// Custom example logic

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass an id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : '../../../examples/upload.php',
	flash_swf_url : '../../js/Moxie.swf',
	silverlight_xap_url : '../../js/Moxie.xap',
	
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif"},
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});

uploader.init();

</script>
	<?php 


	 if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Пожалуйста, введите буквы, как показано на картинке выше..
		<br/>Буквы не чувствительны к регистру.</div>
	</div>
	<?php endif; ?>

	<div class="row submit">
		<?php echo CHtml::submitButton('Отправить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>