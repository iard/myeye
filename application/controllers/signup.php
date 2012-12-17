<?php
class Signup extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$logged_in = $this->session->userdata('logged_in');
		
		if($logged_in === TRUE){
			redirect('home');
		}
	}

	public function index()
	{
		$data['title'] = "Signup";
		$data['display'] = array('user' => 'none', 'category' => 'none', 'channel' => 'none');
		$this->load->view('signup/signup_form', $data);
	}
	
	public function create_user()
	{
		$this->form_validation->set_rules('nim', 'NIM', 'required|min_length[8]|max_length[10]|is_numeric|callback_nim_check');
		$this->form_validation->set_rules('user_name', 'Username', 'required|min_length[6]|max_length[15]|callback_username_check');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');	
		
		if ($this->form_validation->run() === FALSE){
			$data['success'] = FALSE;
			$data['data'] = $this->load->view('signup/signup_error', '', TRUE);
			echo json_encode($data);
		} else {
			$nim = $this->input->post('nim');
			$user_name = $this->input->post('user_name');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			
			$this->load->model('user_db');
			$this->user_db->add_user($this->_get_id(), $nim, $user_name, $password, $email, "member");
			
			$data['success'] = TRUE;
			$data['data'] = $this->load->view('signup/signup_success', '', TRUE);
			echo json_encode($data);
		}
	}
	
	function _get_id()
	{
		do {
			$user_id = rand(100000, 999999);
			$this->load->model('user_db');
			$data = $this->user_db->get_user($user_id);
		} while ($data !== FALSE);
		
		return $user_id;
	}

	public function nim_check($nim)
	{
		$this->load->model('user_db');
		$data = $this->user_db->check('nim', $nim);
		
		if ($data === FALSE){
			return TRUE;
		} else {
			$this->form_validation->set_message('nim_check', 'The %s is not available. Please fill your own %s');
			return FALSE;
		}
	}

	public function username_check($user_name)
	{
		$this->load->model('user_db');
		$data = $this->user_db->check('user_name', $user_name);
		
		if ($data === FALSE){
			return TRUE;
		} else {
			$this->form_validation->set_message('username_check', 'The %s is not available. Please choose other username');
			return FALSE;
		}
	}
}