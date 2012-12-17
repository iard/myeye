<?php if ($own_profile === TRUE) { ?>
<script>
	$(document).ready( function() {
		var rmLikeBtn = $('.add-rm-like');
		rmLikeBtn.button('toggle');
		rmLikeBtn.hide();
		$('.thumbnail').hover( function() {
			$('.add-rm-like', this).fadeIn('fast');
		}, function() {
			$('.add-rm-like', this).fadeOut('fast');
		});
		rmLikeBtn.click( function() { 
			var likeBtn = $(this);
			$.post("<?php echo base_url('watch/add_rm_like')?>", {"video_id" : likeBtn.val()}, function(data) {
				if (data.like_status == true) {
					likeBtn.button('toggle');
					// pesan berhasil
				} else {
					// pesan gagal
				}
			}, "json");
			return false;
		});
	});
</script>
<?php } ?>
<?php 
if ($user_like === FALSE) {
	echo ("<p>No video</p>");
} else {
	echo '<ul class="thumbnails">';
	foreach ($user_like as $row): ?>
		<li class="span3">
			<div class="thumbnail">
				<?php if ($own_profile === TRUE) { ?>
				<button class="btn btn-small btn-primary add-rm-like" value="<?php echo $row->video_id?>" title="Like this video">
					<i class="icon-heart icon-white"></i> Like
				</button>
				<?php } ?>
				<?php echo anchor('watch/'.$row->video_id, "<img src=".base_url('img/screenshot/big/'.$row->screenshot_url).">", array('title' => $row->title))?>
				<h5><?php echo $row->title?></h5>
			</div>
		</li>
	<?php endforeach; 
	echo '</ul>';
} 
?>