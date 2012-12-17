<div calss="row-fluid">
	<div class="span6" id="add-rm-ply">
		<h3>Playlist</h3>
		<?php 
		if ($playlist === FALSE) {
			echo "<p>You don't have any playlist.</p>";
		} else {
			echo '<div class="btn-group">';
			foreach ($playlist as $row) {
				echo '<button type="button" class="btn" value="'.$row->playlist_id.'">'.$row->title.'</button>';
			}
			echo '</div>';
		}
		?>
	</div>
	<div class="span6" id="add-rm-chnl">
		<h3>Channel</h3>
		<?php 
		if ($channel === FALSE) {
			echo "<p>You don't have any channel.</p>";
		} else {
			echo '<div class="btn-group">';
			foreach ($channel as $row) {
				echo '<button type="button" class="btn" value="'.$row->channel_id.'">'.$row->title.'</button>';
			}
			echo '</div>';
		}
		?>
	</div>
</div>
<script>
	$(document).ready(function() {
		<?php
		if ($playlist !== FALSE) {
			foreach ($playlist as $row) {
				if ($row->video_id !== NULL) {
					echo "$('#add-rm-ply button[value=\"".$row->playlist_id."\"]').button('toggle');";
				}
			}
		}

		if ($channel !== FALSE) {
			foreach ($channel as $row) {
				if ($row->video_id !== NULL) {
					echo "$('#add-rm-chnl button[value=\"".$row->channel_id."\"]').button('toggle');";
				}
			}
		}
		?>		
		/*$('#search_category').autocomplete({
			source: "<?php echo base_url('index.php/watch/search_category')?>",
			minLength: 2
		});
		
		$('#add_catagory_btn').click(function(){
			var video_id = $('#video_id_field').val();
			var category = $('#search_category').val();
			if (category == "") {
				alert("Category can't be empty");
				$("#search_category").focus();
				return false;  
			}
			    	
			var dataString = 'video_id=' + video_id + '&category=' + category;
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('index.php/watch/add_category')?>",
				data: dataString,
				success: function(data) {
					if (data == false) {
						alert("Categoriy have been owned by this video");
						$("#search_category").focus();
					} else {
						$(data).fadeIn('slow').appendTo('ul#show_category');
						$('#search_category').val('');
					}
				}
			});
		});
		
		$('#show_category').delegate("button", "click", function() {
			var category_id = $(this).attr("class");
			var tag_id = 'li.' +category_id;
			var dataString = 'category_id=' + category_id;
			
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('index.php/watch/del_category')?>",
				data: dataString,
				success: function() {
					$(tag_id).fadeOut('slow', function() {
						$(this).remove();
					});
				}
			});
		});*/
		
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