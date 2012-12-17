<script src="<?php echo base_url() ?>js/ajaxfileupload.js"></script>
<script>
	$(document).ready( function() {
		$('#userfile').hide();
		$('button[name=add-btn]').click( function() {
			$('#userfile').click();
		});
		$('.pic-picker').click( function(){
			var frameNum = $(this).val();
			$('[name="screenshot"]').val(frameNum);
			$('#pic-holder').prop('src',  "<?php echo base_url('temp/'.$video->video_id.'/')?>" + frameNum + ".jpg");
		});
		$('#save-set').click( function(){
			$('#message').load("<?php echo base_url('video/save_settings')?>", {'vid_id' : $('input[name="video_id"]').val(), 'title' : $('input[name="title"]').val(),  'screenshot' : $('input[name="screenshot"]').val(), 'note' : $('textarea[name="note"]').val()});
			return false;
		});
		$('#sub-uplaod').click( function(e) {
			e.preventDefault();
			$.ajaxFileUpload({
				url : "<?php echo base_url('video/upload_sub')?>",
				secureuri :false,
				fileElementId :'userfile',
				dataType : 'json',
				data : {'vid_id' : $('input[name="video_id"]').val(), 'sub_lang_id' : $('select[name="lang"]').val()},
				success : function(data, status) {
					if(data.success) {
						$('#message').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.message + '</div>');
						$('#vid-sub-table').load("<?php echo base_url('video/vid_sub_table/'.$video->video_id)?>");
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
		$('#vid-sub-table').on('click', '.del-sub', function(){
			var subDelBtn = $(this);
			$.post("<?php echo base_url('video/del_sub')?>", {'vid_id' : $('input[name="video_id"]').val(), 'sub_id' : subDelBtn.val()}, function(data) {
				if (data.success) {
					subDelBtn.closest('tr').fadeOut().remove();
				}
			}, 'json');
		});
	});
</script>
<div class="row">
	<div class="span7" id="message"></div>
	<div class="span2"><button class="btn btn-small btn-primary pull-right" id="back-to-myvid"><i class="icon-chevron-left icon-white"></i> Back</button></div>
	<div class="span9">
		<fieldset>
		<legend>Settings</legend>
			<div class="row">
				<div class="span3" id="settings-form">
					<?php
					echo form_open('video/save_settings');
					echo form_hidden('video_id', $video->video_id);
					echo form_label('Title', 'title');
					echo form_input('title', $video->title);
					echo form_hidden('screenshot', '');
					echo form_label('Video Description', 'note');
					echo form_textarea('note', $video->note);
					echo '<p>'.form_submit('submit', 'Save changes', 'class="btn btn-primary" id="save-set"').'</p>';
					echo form_close();
					?>
				</div>
				<div class="span6">
					<div class="row" style="text-align: center">
						<p style="margin-bottom:5px">Pick screenshot</p>
						<div class="span6"><img class="img-polaroid span5" id="pic-holder" style="margin:0px auto 10px; float: none;" src="<?php echo base_url('img/screenshot/big/'.$video->screenshot_url)?>"></div>
						<div class="span6 btn-group" data-toggle="buttons-radio">
							<?php foreach ($frame_number as $key) : ?>
							<button type="button" class="btn btn-primary pic-picker" value="<?php echo $key?>"></button>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="span9">
		<fieldset>
			<legend>Subtitle</legend>
			<div class="row">
				<div class="span9">
					<?php
					echo form_open('video/upload_sub');
					echo form_label('Subtitle (.srt)', 'userfile');
					echo form_upload(array('name' => 'userfile', 'id' => 'userfile' ));
					echo '<p>'.form_button(array('name' => 'add-btn', 'type' => 'button', 'class' => 'btn btn-success'), '<i class="icon-plus-sign icon-white"></i> Choose file');
					echo form_dropdown('lang', $available_lang, 'Id', 'class="input-small" style="margin:5px 10px;"');
					echo form_button('submit', '<i class="icon-circle-arrow-up icon-white"></i> Upload file', 'class="btn btn-primary" id="sub-uplaod"').'</p>';
					echo form_close();
					?>
				</div>
				<div class="span9" id="vid-sub-table">
					<?php $this->load->view('video/video_sub_table')?>
				</div>
			</div>
		</fieldset>
	</div>
</div>