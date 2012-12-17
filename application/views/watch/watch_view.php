<?php $this->load->view('template/head')?>
<?php $this->load->view('template/'.$nav)?>
<script>
	$(document).ready( function() {
		$('#add-container').hide();
		var addContLoaded = false;
		
		$("#comment-submit").click( function() {
			$.post("<?php echo base_url('watch/add_comment')?>", {"video_id" : "<?php echo $video->video_id?>", "comment" : $("#comment-field").val()}, function(data) {
				if (data.success) {
					$(data.content).appendTo('#comment-container').fadeIn('slow');
				} else {
					//pemberitahuan gagal
				}
			}, 'json');
			$("#comment-field").val('');
		    return false;
		});
		$('#comment-container').on('close','.row-comment', function () {
			$.post("<?php echo base_url('index.php/watch/del_comment')?>", {'comment_id' : $('button', this).val()});
		});
		$('#add-button').click( function() {
			$(this).button('loading');
			if (!addContLoaded) {
				addContLoaded = true;
				$.post("<?php echo base_url('watch/add_rm_ply_cat')?>", {"video_id" : "<?php echo $video->video_id?>"}, function(data) {
					$('#add-container').html(data).fadeToggle('slow');
					$('#add-button').button('reset');
				});
			} else {
				$("#add-container").fadeToggle('slow');
				$('#add-button').button('reset');
			}
		});
		<?php
		if ($like_status == TRUE) {echo "$('#add-rm-like').button('toggle');";};
		if ($wl_status == TRUE) {echo "$('#add-rm-wl').button('toggle');";};
		?>
		$('#add-rm-like').click( function() { 
			$.post("<?php echo base_url('watch/add_rm_like')?>", {"video_id" : "<?php echo $video->video_id?>"}, function(data) {
				if (data.like_status == true) {
					$('#add-rm-like').button('toggle');
					// pesan berhasil
				} else {
					// pesan gagal
				}
			}, "json");
			return false;
		});
		$('#add-rm-wl').click( function() {
			$.post("<?php echo base_url('watch/add_rm_wl')?>", {"video_id" : "<?php echo $video->video_id?>"}, function(data) {
				if (data.wl_status == true) {
					$('#add-rm-wl').button('toggle');
					//pesan berhasil
				} else {
					//pesan gagal
				}
			}, "json");
			return false;
		});
	});
</script>
<div class="span9 margin-top">
	<div class="row-fluid">
		<video class="span12" src="<?php echo base_url('vid/'.$video->video_url)?>" 
			type="video/mp4" poster="<?php echo base_url('img/screenshot/big/'.$video->screenshot_url)?>"
			controls="controls" preload="none">
		</video>
		<div class="span12" id="video-note">
			<div class="row-fluid" >
				<div class="span12" style="margin-bottom:20px">
					<div class="row-fluid">
						<?php if ($user !== FALSE) :?>
						<div class="pull-right" style="margin: 10px 0px">
							<button id="add-rm-like" class="btn btn-small btn-primary" title="Like this video">
								<i class="icon-heart icon-white"></i> Like
							</button>
							<button id="add-rm-wl" class="btn btn-small btn-primary" title="Watch this video later">
								<i class="icon-time icon-white"></i> Watch Later
							</button>
						</div>
						<?php endif; ?>
						<a href="<?php echo base_url('user/'.$video->user_id)?>" class="thumbnail span2" style="margin:0px 20px 0px 0px">
							<img src="<?php echo base_url('img/avatar/'.$video->avatar_url)?>" class="row-fluid">
						</a>
						<h3><?php echo $video->title?></h3>
						<h5>Uploaded by <?php echo anchor('user/'.$video->user_id, $video->user_name, '')?> on <?php echo date("F j, Y", strtotime($video->video_date))?></h5>
					</div>
				</div>
				<div class="span12 well" style="margin-bottom:20px;margin-left:0px;">
					<p><?php echo $video->note?></p>
				</div>
				<?php if ($user !== FALSE) :?>
				<div class="span12" style="margin:0px 0px 20px 0px">
					<button class="btn btn-small btn-primary" id="add-button" data-loading-text="Loading..." data-toggle="button" type="button">
						<i class="icon-plus icon-white"></i> Add to...
					</button>
					<div class="row-fluid">
						<div class="span12" id="add-container"></div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="span12 margin-top" id="comment-container" style="margin-left:0px">
			<?php 
			if ($comments === FALSE){
				echo ("<p>Be the first to comment on this video</p>");
			} else { 
				foreach ($comments as $comment) :
			?>
				<div class="row-fluid row-comment">
					<?php 
					if ($user !== FALSE) {
						if ($user['user_id'] === $comment->user_id) { 
					?>
					<button type="button" class="close" value="<?php echo $comment->comment_id?>" data-dismiss="alert">&times;</button>
					<?php 
						}
					} 
					?>
					<a href="<?php echo base_url('user/'.$comment->user_id)?>" class="thumbnail span1half" style="margin:0px 20px 0px 0px">
						<img src="<?php echo base_url('img/avatar/'.$comment->avatar_url)?>" class="row-fluid">
					</a>
					<strong><?php echo anchor('user/'.$comment->user_id, $comment->user_name, '')?> on <?php echo date("M, j Y h:i A", strtotime($comment->comment_date))?></strong> 
					<p class="span10" style="margin:5px 0px"><?php echo $comment->comment?></p>
				</div>	
			<?php
				endforeach;
			}
			?>
		</div>
		<?php if ($user !== FALSE) :?>
		<div class="span12" style="margin-left:0px">
			<div id="error-message"></div>
			<div class="row-fluid" style="margin-bottom: 10px; padding-top:10px; border-top: 1px solid #EEE;">
				<a href="<?php echo base_url('user/'.$user['user_id'])?>" class="thumbnail span1half" style="margin:0px 20px 100px 0px">
					<img src="<?php echo base_url('img/avatar/'.$user['avatar_url'])?>" class="row-fluid">
				</a>
				<strong>Comment</strong>
				<?php 
				echo form_textarea(array('name' => 'comment', 'id' => 'comment-field', 'class' => 'span10', 'style' => 'height:100px'));
				echo "<p>".form_button(array('name' => 'submit', 'id' => 'comment-submit', 'content' => 'Submit', 'type' => 'button', 'class' => 'btn btn-small btn-primary'))."be cool and be nice dude</p>";
				?>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
<?php $this->load->view('template/foot')?>