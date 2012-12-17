<?php
class Home extends CI_Controller {

	/*public function __construct(){
		parent::__construct();
		$this->log_session = $this->session->all_userdata();
	}*/

	public function index()
	{
		if ($this->session->userdata('logged_in') === TRUE)
		{
			$data['user'] = $this->session->all_userdata();
			if ($this->session->userdata('level') === "admin") 
			{
				$data['nav'] = 'nav_admin';
			}
			else
			{
				$data['nav'] = 'nav_member';
			}
		} 
		else 
		{
			$data['nav'] = 'nav_guest';
		}

		$data['display'] = array('user' => 'none', 'category' => 'none', 'channel' => 'none');
		$data['title'] = "Home";
		$this->load->view('home/home_view', $data);
	}
}