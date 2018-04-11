<ul>
	<li><?php echo CHtml::link('Создать новый пост',array('post/create')); ?></li>
	<li><?php echo CHtml::link('Управление постами',array('post/admin')); ?></li>
	<li><?php echo CHtml::link('Утвердить комментарии',array('comment/index')) . ' (' . Comment::model()->pendingCommentCount . ')'; ?></li>
	<li><?php echo CHtml::link('Выйти',array('site/logout')); ?></li>
</ul>