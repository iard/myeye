<?php
class Upload extends CI_Controller {
	
	var $data = '';
	
	public function __construct(){
		parent::__construct();
		
		if ($this->session->userdata('logged_in') !== TRUE)
		{
			die();
		}
		else if ($this->session->userdata('level') !== 'admin')
		{
			die();
		}
	}
	
	public function index()
	{
		$data['display'] = array('user' => 'none', 'category' => 'none', 'channel' => 'none');
		$data['user'] = $this->session->all_userdata();
		$data['title'] = "Upload video";
		$this->load->view('upload/upload_form', $data);
	}
	
	public function do_upload()
	{	
		$config['upload_path'] = './vid/';
		$config['allowed_types'] = 'mp4';
		$config['encrypt_name'] = TRUE;
		$config['max_size']	= '5000000';
		
		$this->upload->initialize($config);
		
		if ( $this->upload->do_upload('userfile') === FALSE)
		{
			$send['success'] = FALSE;
			$send['message'] = $this->upload->display_errors('', '');
			
		} 
		else 
		{
			$data = $this->upload->data();
			$video_id = $this->get_id();
			$file_name = strtolower(random_string('alnum', 32)).".jpg";

			$vid = realpath('./vid/'.$data['file_name']);
			$movie = new ffmpeg_movie($vid);
			$frame_count = $movie->getFrameCount();
			$frame_rate = $movie->getFrameRate();
			$duration = $movie->getDuration();
			$frame_width = $movie->getFrameWidth();
			$frame_height = $movie->getFrameHeight();
			$frame = $movie->getFrame(rand(1 , $frame_rate));
			imagejpeg($frame->toGDImage(), './img/screenshot/big/'.$file_name);

			$img_config['source_image']	= './img/screenshot/big/'.$file_name;
			$img_config['new_image']	= './img/screenshot/small/';
			$img_config['maintain_ratio'] = TRUE;
			$img_config['width'] = 240;
			$img_config['height'] = 135;
			
			$this->image_lib->initialize($img_config); 
			$this->image_lib->resize();

			$this->load->model('video_db');
			$this->video_db->add_video($video_id, $this->session->userdata('user_id'), $data['file_name'], $frame_count, $frame_rate, $duration, $file_name, $frame_width, $frame_height);
			
			$send['success'] = TRUE;
			$send['message'] = 'Upload success. Please add information this video';
		}
		echo json_encode($send);
	}
	
	function get_id(){
		do {
			$file_name = rand(100000, 999999);
			$this->load->model('video_db');
			$data = $this->video_db->get_video($file_name);
		} while ($data !== FALSE);
		
		return $file_name;
	}
}