<?php
class Playlist extends CI_Controller 
{
	
	private function _user_loggin_validation()
	{
		if ($this->session->userdata('logged_in') !== TRUE)
		{
			die();
		}
		else
		{
			return $this->session->all_userdata();
		}
	}
	
	private function _existing_playlist_and_owner_validation($playlist_id)
	{
		$this->load->model('playlist_db');
		$playlist = $this->playlist_db->get_playlist($playlist_id);
		
		if($playlist === FALSE)
		{
			die();
		} 
		else if ($playlist->user_id !== $this->session->userdata('user_id'))
		{
			die();
		}
		else
		{
			return $playlist;
		}
	}
	
	private function _existing_playlist_validation($playlist_id)
	{
		$this->load->model('playlist_db');
		$playlist = $this->playlist_db->get_playlist($playlist_id);
		
		if ($playlist === FALSE)
		{
			die();
		}
		else
		{
			return $playlist;
		}
	}
	
	private function _existing_user_validation($user_id)
	{
		$this->load->model('user_db');
		$user = $this->user_db->get_user($user_id);
		
		if($user === FALSE){
			redirect('home');//page not found
			die();
		}
	}

	/*public function user_id($user_id)
	{
		$this->_existing_user_validation($user_id);
		
		if($this->session->userdata('logged_in') === TRUE){
			$data['header'] = 'log_header';
			$data['user'] = $this->session->all_userdata();
		} else {
			$data['header'] = 'header';
		}
		
		$this->load->model('playlist_db');
		$playlist = $this->playlist_db->get_user_playlist($user_id);
		
		if ($playlist === FALSE){
			$data['playlist'] = FALSE;
		} else {
			foreach ($playlist as $row) {
				$row->video_list = $this->playlist_db->get_playlist_video_join_video($row->playlist_id);
				$data['playlist'][] = $row;
			}
		}
		
		$this->load->view('playlist/playlist_view', $data);
	}*/
	
	public function playlist_id($playlist_id)
	{
		$playlist = $this->_existing_playlist_validation($playlist_id);

		$this->load->model('playlist_db');
		$data['ply_info'] = $this->playlist_db->get_playlist_join_user($playlist_id);
		$data['ply_video'] = $this->playlist_db->get_playlist_video_join_video($playlist_id);
		$data['own_profile'] = ($data['ply_info']->user_id === $this->session->userdata('user_id') ? TRUE : FALSE);

		$this->load->view('playlist/playlist_view', $data);
	}

	public function create()
	{
		$user = $this->_user_loggin_validation();
		
		$this->load->view('playlist/create_playlist');
	}

	public function create_playlist()
	{
		$user = $this->_user_loggin_validation();
		
		$this->form_validation->set_rules('title', 'Title', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('note', 'Note', 'min_length[6]');
		
		if ($this->form_validation->run() === FALSE)
		{
			$send['success'] = FALSE;
			$send['message'] = validation_errors('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		} 
		else 
		{
			$title = $this->input->post('title');
			$note = $this->input->post('note');
			
			$this->load->model('playlist_db');
			$this->playlist_db->create_playlist($this->_get_id(), $this->session->userdata('user_id'), $title, $note);

			$send['success'] = TRUE;
			//$send['message'] = ;
		}
		echo json_encode($send);
	}
	
	public function settings()
	{
		$playlist_id = $this->input->post('ply_id');

		$user = $this->_user_loggin_validation();
		$data['playlist'] = $this->_existing_playlist_and_owner_validation($playlist_id);
		
		$this->load->view('playlist/playlist_setting', $data);
	}
	
	public function save_setting()
	{
		$playlist_id = $this->input->post('playlist_id');
		
		$user = $this->_user_loggin_validation();
		$playlist = $this->_existing_playlist_and_owner_validation($playlist_id);
		
		$this->form_validation->set_rules('title', 'Title', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('note', 'Note', 'min_length[6]');
		
		if ($this->form_validation->run() === FALSE)
		{
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors('', '').'</div>';
		} 
		else 
		{
			$title = $this->input->post('title');
			$note = $this->input->post('note');
			
			$this->load->model('playlist_db');
			$this->playlist_db->edit_playlist($playlist_id, $title, $note);
			
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Your setting has been saved.</div>';
		}
	}
	
	public function del_video()
	{
		$playlist_video_id = $this->input->post('ply_vid_id');
		
		$user = $this->_user_loggin_validation();

		$this->load->model('playlist_db');
		$playlist_video = $this->playlist_db->get_playlist_video_join_playlist($playlist_video_id);

		if ($playlist_video === FALSE) 
		{
			die();
		}
		else
		{
			if ($playlist_video->user_id !== $user['user_id']) 
			{
				die();
			}
			else
			{
				$this->playlist_db->del_video_from_playlist($playlist_video_id);
				echo TRUE;
			}
		}
	}
	
	public function delete($playlist_id)
	{
		$user = $this->_user_loggin_validation();
		$playlist = $this->_existing_playlist_and_owner_validation($playlist_id);
		
		$this->load->model('playlist_db');
		$this->playlist_db->del_playlist($playlist_id);
		
		echo TRUE;
	}
	
	private function _get_id()
	{
		do {
			$playlist_id = rand(100, 999);
			$this->load->model('playlist_db');
			$data = $this->playlist_db->get_playlist($playlist_id);
		} while ($data !== FALSE);
		
		return $playlist_id;
	}
}