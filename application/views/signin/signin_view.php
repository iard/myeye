<?php $this->load->view('template/head')?>
<?php $this->load->view('template/nav_guest')?>	
<div class="span9" style="margin-top:70px">
	<div id="form-signin" class="well" style="margin: 0 auto; width: 300px;">
		<h1>Login, Dude !!</h1>
		<script>
			$(document).ready(function(){
				$("#submit-form").click(function(){
					$.post("<?php echo base_url('signin/validate')?>", { user : $('input[name="user"]').val(), password : $('input[name="password"]').val() }, function(data) {
						if (data == false) { window.location.href = "<?php echo base_url('home')?>" } else { $('#error-message').html(data) }
					});
					return false;
				});
			});
		</script>
		<div id="error-message"></div>
		<?php 
			echo form_open('signin/validate',array('style' => 'margin-top: 10px'));
			echo form_label('NIM or Username', 'user');
			echo form_input(array('name' => 'user', 'placeholder' => 'username','style' => 'width: 280px'));
			echo form_label('Password', 'password',array('style' => 'margin-top: 10px'));
			echo form_password(array('name' => 'password', 'placeholder' => 'password','style' => 'width: 280px'));
		?>
		<p style="margin-top: 10px;">
		<?php
			echo form_submit(array('name' => 'submit','value' => "login",'class' => 'btn btn-primary','id' => 'submit-form', 'style' => 'margin-right: 10px'));
			echo anchor('signup', 'Signup here, free!!',array('class' => 'btn btn-success margin-left'));
			?>
		</p>
		<?php
		echo form_close();
		?>
	</div>
</div>
<?php $this->load->view('template/foot')?>