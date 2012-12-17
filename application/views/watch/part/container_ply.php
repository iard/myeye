<div calss="row-fluid">
	<div class="span12" id="add-rm-ply">
		<h3>Playlist</h3>
		<?php 
		if ($playlist === FALSE) 
		{
			echo "<p>You don't have any playlist.</p>";
		}
		else
		{
			echo '<div class="btn-group">';
			foreach ($playlist as $row)
			{
				echo '<button type="button" class="btn" value="'.$row->playlist_id.'">'.$row->title.'</button>';
			}
			echo '</div>';
		}
		?>
	</div>
</div>
<script>
	$(document).ready(function() {
		<?php
		if ($playlist !== FALSE) 
		{
			foreach ($playlist as $row)
			{
				if ($row->video_id !== NULL) 
				{
					echo "$('#add-rm-ply button[value=\"".$row->playlist_id."\"]').button('toggle');";
				}
			}
		}

		if ($channel !== FALSE) 
		{
			foreach ($channel as $row)
			{
				if ($row->video_id !== NULL) 
				{
					echo "$('#add-rm-chnl button[value=\"".$row->channel_id."\"]').button('toggle');";
				}
			}
		}
		?>
		
		$('#add-rm-ply button').click( function() {
			var plyTag = $(this);
			$.post("<?php echo base_url('index.php/watch/check_uncheck_playlist')?>", {'video_id' : "<?php echo $video->video_id?>", 'ply_id' : plyTag.val()}, function(data) {
				if (data.ply_status == true) {
					plyTag.button('toggle');
					// pesan berhasil
				} else {
					// pesan gagal
				}
			}, "json");
		});
		
		$('#add-rm-chnl button').click( function() {
			var chnlTag = $(this);
			$.post("<?php echo base_url('index.php/watch/check_uncheck_channel')?>", {'video_id' : "<?php echo $video->video_id?>", 'chnl_id' : chnlTag.val()}, function(data) {
				if (data.chnl_status == true) {
					chnlTag.button('toggle');
					// pesan berhasil
				} else {
					// pesan gagal
				}
			}, "json");
		});
	});
</script>