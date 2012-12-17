<?php $this->load->view('template/head')?>
<?php $this->load->view('template/nav_admin')?> 
<div class="span9 margin-top">
	<div class="row">
		<div class="span9" id="message"></div>
		<div class="span9">
			<?php 
				echo form_open_multipart('upload/do_upload');
				echo form_upload('userfile', '', 'id="userfile"');
				?>
				<div id="upload" class="btn btn-success"><i class="icon-plus-sign icon-white"></i> Choose file</div>
				<?php
				echo form_button(array('type' => 'submit', 'name' => 'upload','class' => "btn btn-primary", 'id' => 'do-upload'), '<i class="icon-circle-arrow-up icon-white"></i> Upload file');
				echo form_close();
			?>
		</div>
	</div>
</div>
<script src="<?php echo base_url() ?>js/ajaxfileupload.js"></script>
<script>
	$(document).ready( function() { 
		$('input[name=userfile]').hide();
		$('#upload').click( function() {
			$('input[name=userfile]').click();
		});
		$("#do-upload").click( function(e) {
			e.preventDefault();
			$.ajaxFileUpload({
				url : "<?php echo base_url('upload/do_upload')?>",
				secureuri :false,
				fileElementId :'userfile',
				dataType : 'json',
				success : function(data, status) {
					if(data.success) {
						$('#message').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.message + '</div>');
					} else {
						$('#message').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.message + '</div>');
					}
				},
				error: function (data, status, e) {
					alert(e);
				}
			});
			return false;
		});
	});
</script>
<?php $this->load->view('template/foot')?>