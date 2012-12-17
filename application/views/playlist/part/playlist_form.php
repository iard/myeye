		<?php
		echo form_open('playlist/create_playlist');
		echo form_label('Title', 'title');
		echo form_input('title', set_value('title'));
		echo form_error('title');
		echo form_label('Note', 'note');;
		echo form_textarea('note', set_value('note'));
		echo form_error('note');
		echo form_submit('submit', 'Create Playlist');
		echo form_close();
		?>