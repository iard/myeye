		<?php
		echo form_open('playlist/save_setting');
		echo form_hidden('playlist_id', $playlist->playlist_id);
		echo form_label('Title', 'title');
		echo form_input('title', $playlist->title);
		echo form_error('title');
		echo form_label('Note', 'note');;
		echo form_textarea('note', $playlist->note);
		echo form_error('note');
		echo form_submit('submit', 'Save');
		echo form_close();
		?>