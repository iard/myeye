<script>
	$(document).ready(function(){
		$('#ply-setting button').click( function() {
			$('#message').load($('form').attr('action'), {'playlist_id' : $('input[name="playlist_id"]').val(), 'title' : $('input[name="title"]').val(), 'note' : $('textarea[name="note"]').val()});
			return false;
		});
		$('#del-ply').click( function() {
			$.post($(this).attr('href'), function(data) {
				if (data == true) {
					$('#back-to-ply').trigger('click');
				}
			});
			return false;
		});
	});
</script>
<div class="row">
	<div class="span7" id="message"></div>
	<div class="span2"><button class="btn btn-small btn-primary pull-right" id="back-to-ply"><i class="icon-chevron-left icon-white"></i> Back</button></div>
	<div class="span9 "id="ply-setting" style="margin-bottom:20px; border-bottom: 1px solid #dddddd;">
		<?php echo form_open('playlist/save_setting');
		echo form_hidden('playlist_id', $playlist->playlist_id);
		echo form_label('Playlist Title', 'title');
		echo form_input('title', $playlist->title);
		echo form_label('Playlist Note', 'note');;
		echo form_textarea('note', $playlist->note);
		echo '<p>'.form_button(array('name' => 'button', 'class' => 'btn btn-primary', 'type' => 'button', 'style' => 'margin-right:5px', 'content' => 'Save changes')).'</p>';
		echo form_close();
		?>
	</div>
	<div class="span9"><?php echo anchor('playlist/delete/'.$playlist->playlist_id, 'Delete Playlist', 'class="btn btn-warning" id="del-ply" title="Delete this Playlist"')?></div>
</div>