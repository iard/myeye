<div id="profile" class="span9">
	<?php $this->load->view('user/part/profile')?>
</div>
<div id="myvideo" class="span9">
	<?php $this->load->view('user/part/myvideo')?>
</div>
<div id="like" class="span9">
	<?php $this->load->view('user/part/like')?>
</div>
<?php if ($own_profile === TRUE) { ?>
<div id="watchlater" class="span9">
	<?php $this->load->view('user/part/watchlater')?>
</div>
<?php } ?>
<div id="playlist" class="span9">
	<?php $this->load->view('user/part/playlist')?>
</div>
<div id="channel" class="span9"></div>