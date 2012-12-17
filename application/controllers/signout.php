<?php
class Signout extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('home');
		}
	}
	
	public function index(){
		$this->session->sess_destroy();
		redirect('home');
	}
}