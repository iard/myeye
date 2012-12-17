<!DOCTYPE html>
<html>
	<head>
		<title>Create Playlist</title>
		<script src="<?php echo base_url('js/jquery-1.7.2.min.js')?>"></script>
		<script>
			$(document).ready(function(){
				$('form :submit').click(function(){
					var targetUrl = $('form').attr('action');
					var title = $('input[name="title"]').val(); 
					var note = $('textarea[name="note"]').val();

			    	var dataString = 'title=' + title +'&note='+ note;
						
					$.ajax({
						type: "POST",
						url: targetUrl,
						data: dataString,
						success: function(data) {
							$('#error_message').html(data);
						}
					});
				    return false;
				});
			});
		</script>
	</head>
	<body>
		<?php $this->load->view('template/log_header')?>
		<div id="create_playlist_form">
			<div id="error_message"></div>
		<?php
		echo form_open('playlist/create_playlist');
		echo form_label('Title', 'title');
		echo form_input('title', set_value('title'));
		echo form_label('Note', 'note');;
		echo form_textarea('note', set_value('note'));
		echo form_submit('submit', 'Create Playlist');
		echo form_close();
		?>
		</div>
	</body>
</html>