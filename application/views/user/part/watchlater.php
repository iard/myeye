<script>
	$(document).ready( function() {
		var rmWlBtn = $('.add-rm-wl');
		rmWlBtn.button('toggle');
		rmWlBtn.hide();
		$('.thumbnail').hover( function() {
			$('.add-rm-wl', this).fadeIn('fast');
		}, function() {
			$('.add-rm-wl', this).fadeOut('fast');
		});
		rmWlBtn.click( function() { 
			var wlBtn = $(this);
			$.post("<?php echo base_url('watch/add_rm_wl')?>", {"video_id" : wlBtn.val()}, function(data) {
				if (data.wl_status == true) {
					wlBtn.button('toggle');
					// pesan berhasil
				} else {
					// pesan gagal
				}
			}, "json");
			return false;
		});
	});
</script>
<?php 
if ($user_watchlater === FALSE) {
	echo ("<p>No video</p>");
} else {
	echo '<ul class="thumbnails">';
	foreach ($user_watchlater as $row): ?>
		<li class="span3">
			<div class="thumbnail">
				<button class="btn btn-small btn-primary add-rm-wl" value="<?php echo $row->video_id?>" title="Watch this video later">
					<i class="icon-time icon-white"></i> Watch Later
				</button>
				<?php echo anchor('watch/'.$row->video_id, "<img src=".base_url('img/screenshot/big/'.$row->screenshot_url).">", array('title' => $row->title))?>
				<h5><?php echo $row->title?></h5>
			</div>
		</li>
	<?php endforeach; 
	echo '</ul>';
} 
?>