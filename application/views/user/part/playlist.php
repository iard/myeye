<?php if ($own_profile === TRUE) { ?>
<script>
	$(document).ready( function() {
		var plySetBtn = $('.ply-set');
		plySetBtn.hide();
		$('.thumbnail').hover( function() {
			$('.ply-set', this).fadeIn('fast');
		}, function() {
			$('.ply-set', this).fadeOut('fast');
		});
	});
</script>
<?php } 
if ($user_playlist === FALSE) {
	echo ("<p>No playlist</p>");
} else {
	echo '<ul class="thumbnails">';
	foreach ($user_playlist as $row): ?>
		<li class="span3">
			<div class="thumbnail">
				<a href="<?php echo base_url('playlist/playlist_id/'.$row->playlist_id)?>" class="to-vid-ply">
					<div class="row-fluid">
						<?php if ($own_profile === TRUE) { ?>
						<button class="btn btn-small btn-primary ply-set" value="<?php echo $row->playlist_id?>">
							<i class="icon-cog icon-white" ></i> Settings
						</button>
						<?php } 
						if ($row->video == NULL) {
							echo "Playlist empty";
						} else {
							foreach ($row->video as $key) :?>
								<div class="span6" style="margin:0px">
									<img src="<?php echo base_url('img/screenshot/small/'.$key)?>">
								</div>
							<?php endforeach;
						} ?>
					</div>
				</a>
				<h5><?php echo $row->title?></h5>
			</div>
		</li>
	<?php endforeach; 
	echo '</ul>';
} ?>