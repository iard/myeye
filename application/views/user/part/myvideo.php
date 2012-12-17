<?php if ($own_profile === TRUE) { ?>
<script>
	$(document).ready( function() {
		$('.vid-set').hide();
		$('.thumbnail').hover( function() {
			$('.vid-set', this).fadeIn('fast');
		}, function() {
			$('.vid-set', this).fadeOut('fast');
		});
	});
</script>
<?php } ?>
<?php 
if ($user_video === FALSE) {
	echo ("<p>No video</p>");
} else {
	echo '<ul class="thumbnails">';
	foreach ($user_video as $row): ?>
		<li class="span3">
			<div class="thumbnail">
				<?php if ($own_profile === TRUE) { ?>
				<button class="btn btn-small btn-primary vid-set" value="<?php echo $row->video_id?>">
					<i class="icon-cog icon-white" ></i> Settings
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