<?php $this->load->view('template/head')?>
<?php $this->load->view('template/'.$nav)?>
<script>
	$(document).ready( function() {
		<?php if ($own_profile === TRUE) { ?>
		$("#profile").on('click', '#profile-settings', function() {
			$('#profile').load("<?php echo base_url('settings')?>");
			return false;
		});
		$("#profile").on('click', '#avatar-setting', function() {
			$('#profile').load("<?php echo base_url('settings/avatar')?>");
			return false;
		});
		$("#profile").on('click', '#back-to-profile', function() {
			$('#profile').load("<?php echo base_url('user/profile')?>");
			return false;
		});
		$("#playlist").on('click', '.ply-set', function() { var plySetBtn = $(this);
			$("#playlist").load("<?php echo base_url('playlist/settings')?>", { 'ply_id' : plySetBtn.val()});
			return false;
		});
		$("#playlist").on('click', '#create-ply', function() {
			$('#playlist').load("<?php echo base_url('playlist/create')?>");
			return false;
		});
		$("#myvideo").on('click', '.vid-set', function() { var vidSetBtn = $(this);
			$('#myvideo').load("<?php echo base_url('video/settings')?>", { 'vid_id' : vidSetBtn.val()});
			return false;
		});
		$("#myvideo").on('click', '#back-to-myvid', function() {
			$('#myvideo').load("<?php echo base_url('user/video')?>");
			return false;
		});
		<?php } ?>
		$("#playlist").on('click', '.to-vid-ply', function() {
			$('#playlist').load($(this).attr('href'));
			return false;
		});
		$("#playlist").on('click', '#back-to-ply', function() {
			$('#playlist').load("<?php echo base_url('user/playlist/'.$user_profile->user_id)?>");
			return false;
		});
	});
</script>
<div class="span9 margin-top" style="overflow:hidden">
	<div class="row" style="width:100000px">
		<?php $this->load->view('user/'.$user_nav)?>
	</div>
</div>
<?php $this->load->view('template/foot')?>