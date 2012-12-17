<div class="row-fluid">
	<div class="span12 hero-unit">
		<div class="row-fluid">
			<div class="span9">
				<h2><?php echo $user_profile->user_name?></h2>
				<p>Join date <?php echo $user_profile->user_date?></p>
				<?php if ($own_profile === TRUE) { ?>
				<p>
					<button class="btn btn-primary" id="profile-settings"><i class="icon-cog icon-white"></i> Settings</button>
				</p>
				<?php } ?>
			</div>
			<div class="pull-right span3">
				<?php if ($own_profile === TRUE) { ?>
					<a href="#" id="avatar-setting" title="Change your profile picture"><?php echo '<img src='.base_url('img/avatar/'.$user_profile->avatar_url).' class="img-polaroid" />'?></a>
				<?php } else {
					echo '<img src='.base_url('img/avatar/'.$user_profile->avatar_url).' class="img-polaroid" />';
				} ?>
			</div>
		</div>
	</div>
</div>