<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<div class="author">
		Пост от <?php echo $data->author->username . '  ' . date('d/m/Y',$data->create_time); ?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->content;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Теги:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<br/>
		<?php echo CHtml::link('Ссылка', $data->url); ?> |
		<?php echo CHtml::link("Комментарий ({$data->commentCount})",$data->url.'#comments'); ?> |
		Последнее обновление <?php echo date('d/m/Y',$data->update_time); ?>
	</div>
</div>
