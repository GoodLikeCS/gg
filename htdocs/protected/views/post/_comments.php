<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=>'Ссылка на комментарий',
	)); ?>

	<div class="author">
		<?php echo $comment->authorLink; ?> говорит:
	</div>

	<div class="time">
		<?php echo date('d/m/Y G:i:s',$comment->create_time); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>