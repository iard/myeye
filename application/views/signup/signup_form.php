<?php $this->load->view('template/head')?>
<script>
	$(document).ready(function(){
		$("#signup_form :submit").click(function(){
			$.post($("form").attr("action"), { nim : $('input[name="nim"]').val(), 
				user_name : $('input[name="user_name"]').val(), 
				password : $('input[name="password"]').val(), 
				passconf : $('input[name="passconf"]').val(), 
				email : $('input[name="email"]').val()}, 
				function(data){
					if (data.success) {
						$('#signup_form').slideToggle('slow', function() {
							$(this).slideDown('slow').html(data.data);
						});
					} else {
						$('#error_message').slideToggle('slow', function() {
							$(this).slideDown('slow').html(data.data);
						});
					}
 				}, "json");
		    return false;
		});
	});
</script>	
<?php $this->load->view('template/nav_guest')?>
<div class="container-fluid" style="margin-top:70px">
	<div class="row-fluid">
		<div class="span9">
			<div style="margin: 0 auto; width: 400px;" class="well">
				<h1>Signup Free!!</h1>
				<div id="signup_form">
					<div id="error_message"></div>
					<?php
					echo form_open('signup/create_user',array('style' => 'margin-top: 30px'));
					echo form_label('NIM', 'nim');
					echo form_input(array('name' => 'nim','style' => 'width: 380px'));
					echo form_label('Username', 'user_name');;
					echo form_input(array('name' => 'user_name','style' => 'width: 380px'));
					echo form_label('Password', 'password');;
					echo form_password(array('name' => 'password','style' => 'width: 380px'));
					echo form_label('Retype Password', 'passconf');
					echo form_password(array('name' => 'passconf','style' => 'width: 380px'));
					echo form_label('Email Address', 'email');;
					echo form_input(array('name' => 'email','style' => 'width: 380px'));
					echo form_submit(array('name' => 'submit','value' => "create account",'class' => 'btn btn-primary', 'style' => 'margin-right:10px'));
					echo anchor('signin', 'back to signin',array('class' => 'btn btn-danger margin-left'));
					echo form_close();
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/foot')?>