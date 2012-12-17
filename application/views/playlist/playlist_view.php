<?php if ($own_profile === TRUE) { ?>
<script>
	$(document).ready( function() {
		var rmPlyBtn = $('.rm-ply');
		rmPlyBtn.hide();
		$('.thumbnail').hover( function() {
			$('.rm-ply', this).fadeIn('fast');
		}, function() {
			$('.rm-ply', this).fadeOut('fast');
		});
		rmPlyBtn.click( function() {
			var plyBtn = $(this);
			$.post("<?php echo base_url('index.php/playlist/del_video')?>", {'ply_vid_id' : plyBtn.val()}, function(data) {
				if (data == true) {
					plyBtn.closest('li').fadeOut().remove();
				}
			});
		});
	});
</script>
<?php } ?>
<div class="row">
	<div class="span9" style="border-Bottom:1px solid #dddddd; margin-bottom:20px">
		<div class="row">
			<div class="span7" id="small-profile">
				<a href="<?php echo base_url('user/'.$ply_info->user_id)?>" class="thumbnail span1" style="margin:0px 20px 0px 0px">
					<img src="<?php echo base_url('img/avatar/'.$ply_info->avatar_url)?>" class="row-fluid">
				</a>
				<h3><?php echo $ply_info->title?></h3>
				<h5>Created by <?php echo anchor('user/'.$ply_info->user_id, $ply_info->user_name, '')?> on <?php echo date("F j, Y", strtotime($ply_info->playlist_date))?></h5>
			</div>
			<div class="span2"><button class="btn btn-small btn-primary pull-right" id="back-to-ply"><i class="icon-chevron-left icon-white"></i> Back</button></div>
			<div class="span9"><p style="padding:10px 0px"><?php echo $ply_info->note?></p></div>
		</div>
	</div>
	<div class="span9">			
	<?php
		if ($ply_video === FALSE) {
		echo ("<p>No video</p>");
	} else {
		echo '<ul class="thumbnails">';
		foreach ($ply_video as $row): ?>
			<li class="span3">
				<div class="thumbnail">
					<?php if ($own_profile === TRUE) { ?>
					<button class="btn btn-small btn-primary rm-ply" value="<?php echo $row->playlist_video_id?>" title="Remove from this playlist">
						<i class="icon-remove-circle icon-white"></i> Remove
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
	</div>
</div>