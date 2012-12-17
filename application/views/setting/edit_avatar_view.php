<script src="<?php echo base_url() ?>js/ajaxfileupload.js"></script>
<script>
	$(document).ready( function() {
		$('#userfile').hide();
		$('button[name=add-btn]').click( function() {
			$('#userfile').click();
		});
		$("button[name=upload-btn]").click( function(e) {
			e.preventDefault();
			$.ajaxFileUpload({
				url : "<?php echo base_url('settings/save_avatar')?>",
				secureuri :false,
				fileElementId :'userfile',
				dataType : 'json',
				success : function(data, status) {
					if(data.success) {
						$('#back-to-profile').trigger('click');
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
<div class="row">
	<div class="span7" id="message"></div>
	<div class="span2"><button class="btn btn-small btn-primary pull-right" id="back-to-profile"><i class="icon-chevron-left icon-white"></i> Back</button></div>
	<div class="span9" id="upload-form">
		<?php
		echo form_open_multipart('settings/save_avatar');
		echo form_upload(array('name' => 'userfile', 'id' => 'userfile' ));
		echo form_button(array('name' => 'add-btn', 'type' => 'button', 'class' => 'btn btn-success', 'style' => 'margin-right:5px'), '<i class="icon-plus-sign icon-white"></i> Choose file');
		echo form_button(array('name' => 'upload-btn', 'type' => 'submit', 'class' => 'btn btn-primary'), '<i class="icon-circle-arrow-up icon-white"></i> Upload file');
		echo form_close();
		?>
	</div>
</div>
