<?php
class User extends CI_Controller {

	public function user_id($user_id)
	{
		$this->load->model('user_db');
		$data['user_profile'] = $this->user_db->get_user($user_id);

		if ($data['user_profile'] === FALSE)
		{
			show_404();
		}
		else
		{
			$data['own_profile'] = FALSE;

			if ($this->session->userdata('logged_in') === TRUE)
			{
				$data['user'] = $this->session->all_userdata();

				if ($data['user_profile']->level === "admin") 
				{
					$data['user_nav'] = "user_nav_admin";

					$this->load->model('video_db');
					$data['user_video'] = $this->video_db->get_user_video($user_id);

					//$this->load->model('channel_db');
					//$data['user_channel'] = $this->channel_db->get_user_channel($user_id);

					if ($data['user']['level'] === "admin")
					{
						if ($data['user']['user_id'] === $user_id)
						{
							$data['nav'] = 'nav_admin';
							$data['own_profile'] = TRUE;

							$this->load->model('watch_later_db');
							$data['user_watchlater'] = $this->watch_later_db->get_user_wl($user_id);
						}
						else
						{
							$data['nav'] = 'nav_admin_admin';
						}
					}
					else
					{
						$data['nav'] = 'nav_member_admin';
					}
				}
				else
				{
					$data['user_nav'] = "user_nav_member";

					if ($data['user']['level'] === "admin")
					{
						$data['nav'] = 'nav_admin_member';
					}
					else
					{
						if ($data['user']['user_id'] === $user_id)
						{
							$data['nav'] = 'nav_member';
							$data['own_profile'] = TRUE;

							$this->load->model('watch_later_db');
							$data['user_watchlater'] = $this->watch_later_db->get_user_wl($user_id);
						}
						else
						{
							$data['nav'] = 'nav_member_member';
						}
					}
				}
			} 
			else 
			{
				if ($data['user_profile']->level === "admin") 
				{
					$data['nav'] = 'nav_guest_admin';
					$data['user_nav'] = "user_nav_admin";

					$this->load->model('video_db');
					$data['user_video'] = $this->video_db->get_user_video($user_id);

					//$this->load->model('channel_db');
					//$data['user_channel'] = $this->channel_db->get_user_channel($user_id);
				}
				else
				{
					$data['nav'] = 'nav_guest_member';
					$data['user_nav'] = "user_nav_member";
				}
			}

			$this->load->model('like_db');
			$data['user_like'] = $this->like_db->get_user_like($user_id);
			
			$this->load->model('playlist_db');
			$data['user_playlist'] = $this->playlist_db->get_user_playlist($user_id);
			
			$data['display'] = array('user' => 'block', 'category' => 'none', 'channel' => 'none');
			$data['title'] = $data['user_profile']->user_name." on myeye";
			$this->load->view('user/user_view', $data);
		}
	}

	public function profile()
	{
		$this->load->model('user_db');
		$data['user_profile'] = $this->user_db->get_user($this->session->userdata('user_id'));
		$data['own_profile'] = TRUE;

		$this->load->view('user/part/profile', $data);
	}
	
	public function video()
	{
		$this->load->model('video_db');
		$data['user_video'] = $this->video_db->get_user_video($this->session->userdata('user_id'));
		$data['own_profile'] = TRUE;

		$this->load->view('user/part/myvideo', $data);
	}
	
	public function playlist($user_id)
	{
		$this->load->model('playlist_db');
		$data['user_playlist'] = $this->playlist_db->get_user_playlist($user_id);
		if ($data['user_playlist'] !== FALSE) 
		{
			$data['own_profile'] = ($data['user_playlist'][0]->user_id === $this->session->userdata('user_id') ? TRUE : FALSE);
		}
		
		$this->load->view('user/part/playlist', $data);
	}
	
	public function like($user_id){
		$this->load->model('like_db');
		$data['like'] = $this->like_db->get_user_like($user_id);
		
		$this->load->view('user/part/like', $data);
	}
	
	public function watchlater(){
		$this->load->model('watch_later_db');
		$data['watch_later'] = $this->watch_later_db->get_user_wl($this->session->userdata('user_id'));
		
		$this->load->view('user/part/watchlater', $data);
	}
	

}
