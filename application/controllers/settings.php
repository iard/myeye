<?php
class Settings extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if($this->session->userdata('logged_in') !== TRUE)
		{
			die();//don't have permision
		}
	}
	
	public function index()
	{
		$data['user'] = $this->session->all_userdata();
		
		$this->load->model('user_db');
		$data['profile_user'] = $this->user_db->get_user($this->session->userdata('user_id'));
		
		$this->load->view('setting/edit_view', $data);
	}
	
	public function save_settings()
	{
		$data ['user'] = $this->session->all_userdata();
		
		$this->form_validation->set_rules('nim', 'NIM', 'required|min_length[8]|max_length[10]|is_numeric|callback_nim_check');
		$this->form_validation->set_rules('user_name', 'Username', 'required|min_length[6]|max_length[15]|callback_username_check');
		$this->form_validation->set_rules('password', 'Password', 'min_length[6]|max_length[32]|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');	
		
		if ($this->form_validation->run() === FALSE)
		{
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors('', '').'</div>';
		} 
		else 
		{
			$nim = $this->input->post('nim');
			$user_name = $this->input->post('user_name');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			
			$this->load->model('user_db');
			$this->user_db->edit_user($this->session->userdata('user_id'), $nim, $user_name, $password, $email);
						
			$this->session->set_userdata('nim', $nim);
			$this->session->set_userdata('user_name', $user_name);
			
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Your profile has been updated.</div>';
		}
	}

	public function avatar()
	{
		$data['user'] = $this->session->all_userdata();
		
		$this->load->view('setting/edit_avatar_view', $data);
	}
	
	public function save_avatar()
	{	
		$config['upload_path'] = './temp/';
		$config['allowed_types'] = 'jpg|png';
		$config['encrypt_name'] = TRUE;
		$config['max_size']	= '1000';
		
		$this->upload->initialize($config);
		
		if ( $this->upload->do_upload('userfile') === FALSE) 
		{
			$data['success'] = FALSE;
			$data['message'] = $error = $this->upload->display_errors('', '');
		} 
		else 
		{
			$file = $this->upload->data();
			
			$this->load->model('user_db');
			$old_avatar = $this->user_db->get_user($this->session->userdata('user_id'));
			$this->user_db->edit_avatar($this->session->userdata('user_id'), $file['file_name']);
			
			$imgconfig['source_image']	= './temp/'.$file['file_name'];
			$imgconfig['new_image']	= './img/avatar/';
			$imgconfig['maintain_ratio'] = TRUE;
			$imgconfig['width']	 = 200;
			$imgconfig['height'] = 200;
			
			$this->image_lib->initialize($imgconfig); 
			if (! $this->image_lib->resize())
			{
				echo $this->image_lib->display_errors();
			} 
			else 
			{
				if ($old_avatar->avatar_url !== 'default.png')
				{
					unlink('./img/avatar/'.$old_avatar->avatar_url);
				}
				unlink('./temp/'.$file['file_name']);
				$this->user_db->edit_avatar($this->session->userdata('user_id'), $file['file_name']);
				$this->session->set_userdata('avatar_url', $file['file_name']);
				$data['success'] = TRUE;	
			}
		}
		echo json_encode($data);
	}

	public function nim_check($nim)
	{
		$this->load->model('user_db');
		$data = $this->user_db->check('nim', $nim);
		
		if ($data === FALSE || $data->nim === $this->session->userdata('nim')){
			return TRUE;
		} else {
			$this->form_validation->set_message('nim_check', 'The %s is already taken. Please fill your own nim');
			return FALSE;
		}
	}

	public function username_check($user_name)
	{
		$this->load->model('user_db');
		$data = $this->user_db->check('user_name', $user_name);
		
		if ($data === FALSE || $data->user_name === $this->session->userdata('user_name')){
			return TRUE;
		} else {
			$this->form_validation->set_message('username_check', 'The %s is already taken. Please select other user_name');
			return FALSE;
		}
	}
}	