<div class="row-fluid row-comment">
	<button type="button" class="close" value="<?php echo $comment->comment_id?>" data-dismiss="alert">&times;</button>
	<a href="<?php echo base_url('user/'.$comment->user_id)?>" class="thumbnail span1half" style="margin:0px 20px 0px 0px">
		<img src="<?php echo base_url('img/avatar/'.$comment->avatar_url)?>" class="row-fluid">
	</a>
	<strong><?php echo anchor('user/'.$comment->user_id, $comment->user_name, '')?> on <?php echo date("M, j Y h:i A", strtotime($comment->comment_date))?></strong> 
	<p class="span10" style="margin:5px 0px"><?php echo $comment->comment?></p>
</div>