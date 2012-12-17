<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $profile_user->user_name." on IARDTube"?></title>
	</head>
	<body>
		<?php $this->load->view('template/'.$header)?>
		<div id="user_profile">
			<div id="avatar">
				<?php 
				if (isset($user)){
					if ($user['user_id'] === $profile_user->user_id){
						echo anchor('usersetting/upload_avatar', '<img src='.base_url('img/avatar/big/'.$profile_user->avatar_url).' />', array('title' => 'Edit Avatar'));
					}
				} else {
					echo ('<img src='.base_url('img/'.$profile_user->avatar_url).' />');
				}?>
			</div>	
			<?php 
				echo "<ul>";
				foreach ($profile_user as $item => $value) {
					echo ("<li>".$item." = ".$value."</li>");
				}
				echo "</ul>";
				if (isset($user)){
					if ($user['user_id'] === $profile_user->user_id){
						echo anchor('usersetting/edit', 'Edit', array('title' => 'Edit Profile'));
					}
				} /*else {
					//add friends
				}*/
			?>
		</div>
		<div id="user_video">			
			<?php 
			if ($video === FALSE){
				echo ($profile_user->user_name." does not have any video");
			}else{
				foreach ($video as $row) {
					echo "<ul>";
					foreach ($row as $item => $value) {
						echo ("<li>".$item." = ".$value."</li>");
					}
					echo "</ul>";
				}
			}
			?>
		</div>
	</body>
</html>