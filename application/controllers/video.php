<?php
class Video extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('logged_in') !== TRUE)
		{
			die();
		}
	}
	
	private function _authorized($video_id)
	{
		$this->load->model('video_db');
		$video = $this->video_db->get_video($video_id);
		
		if ($video === FALSE) 
		{
			die();
		} 
		else if ($video->user_id !== $this->session->userdata('user_id')) 
		{
			die();
		}
		else
		{
			return $video;
		}
	}
	
	public function settings()
	{
		$video_id = $this->input->post('vid_id');

		$data['video'] = $this->_authorized($video_id);

		$vid = realpath('./vid/'.$data['video']->video_url);
		$movie = new ffmpeg_movie($vid);

		$multiply = ($data['video']->frame_count - 10) / 100;

		for ($i=1; $i < 101; $i++) 
		{
			$frame_number[$i] = floor($i * $multiply);
			$file_name = $video_id.$frame_number[$i].'.jpg';
			$frame = $movie->getFrame($frame_number[$i]);
			imagejpeg($frame->toGDImage(), './temp/'.$file_name);

			$config['source_image']	= './temp/'.$file_name;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 360;

			$this->image_lib->initialize($config); 
			$this->image_lib->resize();
		}
		
		$this->load->model('subtitle_db');
		$data['available_lang'] = $this->subtitle_db->get_sub_language();
		$data['vid_sub'] = $this->subtitle_db->get_video_sub($video_id);
		$data['frame_number'] = $frame_number;

		$this->load->view('video/video_setting', $data);
	}

	public function save_settings()
	{
		$video_id = $this->input->post('vid_id');
		$video = $this->_authorized($video_id);
		
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('screenshot', 'Screenshot', 'numeric|callback_framerate_check');
		$this->form_validation->set_rules('note', 'Description', 'max_length[1500]');
		
		if ($this->form_validation->run() === FALSE)
		{
			//$send['status'] = FALSE;
			//$send['message'] = validation_errors('', '');
			echo validation_errors('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		} 
		else 
		{
			$title = $this->input->post('title');
			$screenshot = $this->input->post('screenshot');
			$note = $this->input->post('note');

			if ($screenshot !== "")
			{
				unlink('./img/screenshot/big/'.$video->screenshot_url);
				unlink('./img/screenshot/small/'.$video->screenshot_url);
				$files = glob('./temp/'.$video->video_id.'*'); 
				foreach($files as $file)
				{
					if(is_file($file))
					{
						unlink($file); 
					}
				}

				$file_name = strtolower(random_string('alnum', 32)).".jpg";
				$vid = realpath('./vid/'.$video->video_url);
				$movie = new ffmpeg_movie($vid);
				$frame = $movie->getFrame($screenshot);
				imagejpeg($frame->toGDImage(), './img/screenshot/big/'.$file_name);

				$img_config['source_image']	= './img/screenshot/big/'.$file_name;
				$img_config['new_image']	= './img/screenshot/small/';
				$img_config['maintain_ratio'] = TRUE;
				$img_config['width']	 = 240;
				$img_config['height']	= 135;
				
				$this->image_lib->initialize($img_config); 
				$this->image_lib->resize();
			}
			else
			{
				$file_name = NULL;
			}

			$this->load->model('video_db');
			$this->video_db->edit_video_des($video_id, $title, $file_name, $note);

			//$send['status'] = TRUE;
			//$send['message'] = '<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>Settings has been updated</div>';
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Settings has been updated</div>';
		}
	}

	public function upload_sub()
	{
		$video_id = $this->input->post('vid_id');
		$video = $this->_authorized($video_id);

		$config['upload_path'] = './sub/';
		$config['allowed_types'] = 'srt';
		$config['encrypt_name'] = TRUE;
		$config['max_size']	= '500';

		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload())
		{
			$send['success'] = FALSE;
			$send['message'] = $this->upload->display_errors('', '');
		}
		else
		{
			$file = $this->upload->data();
			$sub_lang_id = $this->input->post('sub_lang_id');

			$this->load->model('subtitle_db');
			$this->subtitle_db->add_subtitle($video_id, $sub_lang_id, $file['file_name']);

			$send['success'] = TRUE;
			$send['message'] = 'Your Subtitle is uploaded';
		}
		echo json_encode($send);
	}

	public function vid_sub_table($video_id)
	{
		$this->load->model('subtitle_db');
		$data['vid_sub'] = $this->subtitle_db->get_video_sub($video_id);

		$this->load->view('video/video_sub_table', $data);
	}

	public function del_sub()
	{
		$video_id = $this->input->post('vid_id');
		$video = $this->_authorized($video_id);

		$subtitle_id = $this->input->post('sub_id');
		$this->load->model('subtitle_db');
		$this->subtitle_db->del_video_sub($subtitle_id);

		echo json_encode(array('success' => TRUE));
	}

	public function framerate_check($numb)
	{
		$video_id = $this->input->post('vid_id');

		$this->load->model('video_db');
		$data = $this->video_db->get_video($video_id);

		if ($numb > $data->frame_count)
		{
			$this->form_validation->set_message('framerate_check', 'The %s frame is beyond this video duration');
			return FALSE;
		} 
		else 
		{
			return TRUE;
		}
	}
}