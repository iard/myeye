<?php
if ($vid_sub === FALSE)
{
	echo '<p>No subtitle for this video</p>';
}
else
{
	$this->table->set_heading('Available subtitle language', 'Remove');
	$this->table->set_template(array('table_open' => '<table class="table table-hover">'));

	foreach ($vid_sub as $row) {
		$this->table->add_row($row->language, '<button type="button" class="btn btn-warning del-sub" value="'.$row->subtitle_id.'"><i class="icon-remove-sign icon-white"></i> Remove</button>');
	}

	echo $this->table->generate();
}
?>