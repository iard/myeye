<?php
class Signin extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$logged_in = $this->session->userdata('logged_in');
		
		if($logged_in === TRUE)
		{
			redirect('home');
		}
	}
		
	public function index()
	{
		$data['title'] = "Signin";
		$data['display'] = array('user' => 'none', 'category' => 'none', 'channel' => 'none');
		$this->load->view('signin/signin_view', $data);
	}
		
	public function validate()
	{
		$user_name = $this->input->post('user');
		$password = $this->input->post('password');
		
		$this->load->model('user_db');
		$data = $this->user_db->user_validate($user_name, $password);
		
		if ($data !== FALSE)
		{
			$session_data = array(
				'user_id'  => $data->user_id,
				'nim' => $data->nim,
				'user_name' => $data->user_name,
				'avatar_url' => $data->avatar_url,
				'level' => $data->level,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($session_data);

			echo FALSE;
		}
		else
		{
			echo '<div class="alert alert-error" style="margin-top:20px">Username or password are not valid
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>';
		}
	}
	
}
