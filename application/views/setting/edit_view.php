<script>
	$(document).ready(function(){
		$("#edit-form button").click( function() {
			$.post($("form").attr("action"), {'nim' : $('input[name="nim"]').val(), 
				'user_name' : $('input[name="user_name"]').val(), 
				'password' : $('input[name="password"]').val(), 
				'passconf' : $('input[name="passconf"]').val(), 
				'email' : $('input[name="email"]').val()}, function(data) {
					$('#message').html(data);
					$('input[name="password"]').val(''); 
					$('input[name="passconf"]').val('');
				});
			return false;
		});
	});
</script>
<div class="row">
	<div class="span7" id="message"></div>
	<div class="span2"><button class="btn btn-small btn-primary pull-right" id="back-to-profile"><i class="icon-chevron-left icon-white"></i> Back</button></div>
	<div class="span9" id="edit-form">
		<?php
		echo form_open('settings/save_settings');
		echo form_label('NIM', 'nim');
		echo form_input('nim', $profile_user->nim);
		echo form_label('Username', 'user_name');;
		echo form_input('user_name', $profile_user->user_name);
		echo form_label('Password*', 'password');;
		echo form_password('password', '');
		echo form_label('Retype Password*', 'passconf');;
		echo form_password('passconf', '');
		echo form_label('Email Address', 'email');;
		echo form_input('email', $profile_user->email);
		echo '<p>'.form_button(array('name' => 'button', 'class' => 'btn btn-primary', 'type' => 'button', 'content' => 'Save changes')).'</p>';
		echo form_close();
		?>
		<p>* Leave it blank if you don't wanna change your password.</p>
	</div>
</div>