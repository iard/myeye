<script>
	$(document).ready(function(){
		$('#create-ply-form :submit').click( function() {
			$.post($('#create-ply-form form').attr('action'), {'title' : $('#create-ply-form input[name="title"]').val(),'note' : $('#create-ply-form textarea[name="note"]').val()}, function(data) {
				if (data.success) {
					$('#back-to-ply').click();
				} else {
					$('#message').html(data.message);
				}
			}, 'json');
			return false;
		});
	});
</script>
<div class="row">
	<div class="span2 pull-right"><button class="btn btn-small btn-primary pull-right" id="back-to-ply"><i class="icon-chevron-left icon-white"></i> Back</button></div>
	<div class="span9">
		<fieldset>
		<legend>Create playlist</legend>
			<div class="row">
				<div class="span8" id="message"></div>
				<div class="span9" id="create-ply-form">
					<?php
					echo form_open('playlist/create_playlist');
					echo form_label('Title', 'title');
					echo form_input('title', set_value('title'));
					echo form_label('Note', 'note');;
					echo form_textarea('note', set_value('note'));
					echo '<p>'.form_submit('Create', 'Create Playlist', 'class="btn btn-primary"').'</p>';
					echo form_close();
					?>
				</div>
			</div>
		</fieldset>
	</div>
</div>