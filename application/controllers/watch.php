<?php
class Watch extends CI_Controller 
{
	private function _user_loggin_validation()
	{
		if ($this->session->userdata('logged_in') !== TRUE)
		{
			return FALSE;//don't have permision
		} 
		else 
		{
			return $this->session->all_userdata();
		}
	}
	
	private function _existing_video_validation($video_id)
	{
		$this->load->model('video_db');
		$video = $this->video_db->get_video($video_id);
		
		if ($video === FALSE)
		{
			return FALSE;//page not found or video does not exist
		} 
		else 
		{
			return $video;
		}
	}
	
	private function _existing_video_and_owner_validation($video_id)
	{
		$this->load->model('video_db');
		$video = $this->video_db->get_video($video_id);
		
		if($video === FALSE){
			redirect('home');//page not found
			die();
		} else if ($video->user_id !== $this->session->userdata('user_id')){
			redirect('home');//don't have permision
			die();
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
	
	public function video_id($video_id)
	{
		$data['video'] = $this->_existing_video_validation($video_id);
		
		if ($data['video'] === FALSE) 
		{
			show_404(); //page not found		
		}
		else
		{
			$data['user'] = $this->_user_loggin_validation();
			if ($data['user'] === FALSE)
			{
				$data['nav'] = 'nav_guest';
			} 
			else 
			{
				if ($data['user']['level'] === "admin") 
				{
					$data['nav'] = 'nav_admin';
				}
				else
				{
					$data['nav'] = 'nav_member';
				}
			}
			
			$this->load->model('comment_db');
			$data['comments'] = $this->comment_db->get_video_comment($video_id);

			$this->load->model('like_db');
			$data['like_status'] = $this->like_db->check_user_like($video_id, $this->session->userdata('user_id'));

			$this->load->model('watch_later_db');
			$data['wl_status'] = $this->watch_later_db->check_user_wl($video_id, $this->session->userdata('user_id'));
			
			//$this->load->model('subtitle_db');
			//$data['sub'] = $this->subtitle_db->get_video_sub($video_id);

			$data['display'] = array('user' => 'none', 'category' => 'none', 'channel' => 'none');
			$data['title'] = $data['video']->title;
			$this->load->view('watch/watch_view', $data);
		}
	}
	
	public function add_comment()
	{
		$video_id = $this->input->post('video_id');
		$comment = $this->input->post('comment');

		$user = $this->_user_loggin_validation();
		$video = $this->_existing_video_validation($video_id);
		
		$this->form_validation->set_rules('comment', 'Comment', 'required');
		
		if ($this->form_validation->run() === FALSE || $user === FALSE || $video_id === FALSE)
		{
			$send['success'] = FALSE;
			$send['content'] = validation_errors();
		} 
		else 
		{
			$this->load->model('comment_db');
			$data['comment'] = $this->comment_db->add_comment($user['user_id'], $video_id, $comment);
			
			$send['success'] = TRUE;
			$send['content'] = $this->load->view('watch/part/container_comment', $data, TRUE);
			
		}
		echo json_encode($send);
	}
	
	public function del_comment()
	{
		$comment_id = $this->input->post('comment_id');
		
		$user = $this->_user_loggin_validation();
		
		if ($user === FALSE)
		{
			die();
		}
		else
		{
			$this->load->model('comment_db');
			$data = $this->comment_db->get_comment($comment_id);

			if ($data->user_id === $user['user_id'])
			{
				$this->comment_db->del_comment($comment_id);
				return TRUE;
			} 
			else 
			{
				die();
			}
		}
	}
	
	public function add_rm_ply_cat()
	{
		$video_id = $this->input->post('video_id');
		
		$user = $this->_user_loggin_validation();
		$data['video'] = $this->_existing_video_validation($video_id);
		
		if ($data['video'] === FALSE || $user === FALSE) 
		{
			echo "<p>You should login to do this action</p>";		
		}
		else
		{
			if($user['level'] === 'admin')
			{
				$add_container = 'container_ply_chnl';
			} 
			else 
			{
				$add_container = 'container_ply';
			}
			
			$this->load->model('playlist_db');
			$data['playlist'] = $this->playlist_db->get_user_video_playlist($user['user_id'], $video_id);
			
			$this->load->model('channel_db');
			$data['channel'] = $this->channel_db->get_user_video_channel($user['user_id'] , $video_id);
			
			$this->load->view('watch/part/'.$add_container, $data);	
		}
	}
	
	/*public function search_category() //mark
	{
		$term = $this->input->get('term');
		//for expert hilangkan pilihan kategori yang telah dimiliki oleh video
		$this->load->model('category_db');
		$data = $this->category_db->get_category_term($term);
		
		echo json_encode($data);
	}
	
	public function add_category() //mark
	{
		$video_id = $this->input->post('video_id');
		$category = ucfirst(strtolower($this->input->post('category')));
		
		$this->_user_loggin_validation();
		$this->_existing_video_and_owner_validation($video_id);
		
		$this->load->model('category_db');
		$valid = $this->category_db->get_category_video($video_id, $category);
		if ($valid === FALSE){
			$this->load->model('category_db');
			$data['category'] = $this->category_db->add_category($category, $video_id);
		
			$this->load->view('watch/part/category_update', $data);
		} else {
			return FALSE;
		}
	}
	
	public function del_category() //mark
	{
		$category_video_id = $this->input->post('category_id');

		$this->load->model('category_db');
		$category_video = $this->category_db->get_video_category($category_video_id);
		
		if ($category_video === FALSE){
			redirect('home');//page not found
		} else {
			$this->_existing_video_and_owner_validation($category_video->video_id);
		}
		
		$this->load->model('category_db');
		$this->category_db->del_video_category($category_video_id);
		
		return TRUE;
	}*/
	
	public function check_uncheck_playlist()
	{
		$video_id = $this->input->post('video_id');
		$playlist_id = $this->input->post('ply_id');
		
		$user = $this->_user_loggin_validation();
		$video = $this->_existing_video_validation($video_id);

		if ($user === FALSE || $video === FALSE)
		{
			$send = array('status' => FALSE);
		}
		else
		{
			$this->load->model('playlist_db');
			$playlist = $this->playlist_db->get_video_on_playlist($playlist_id, $video_id);
			
			if ($playlist === FALSE)
			{
				$send = array('ply_status' => TRUE, 'in_ply' => TRUE);
				$this->playlist_db->add_video_to_playlist($playlist_id, $video_id);
			} 
			else 
			{
				$send = array('ply_status' => TRUE, 'in_ply' => FALSE);
				$this->playlist_db->del_video_on_playlist($playlist_id, $video_id);
			}
		}

		echo json_encode($send);
	}
	
	public function check_uncheck_channel()
	{
		$video_id = $this->input->post('video_id');
		$channel_id = $this->input->post('chnl_id');
		
		$user = $this->_user_loggin_validation();
		$video = $this->_existing_video_validation($video_id);
		
		if ($user === FALSE || $video === FALSE)
		{
			$send = array('status' => FALSE);
		}
		else
		{
			$this->load->model('channel_db');
			$channel = $this->channel_db->get_video_on_channel($channel_id, $video_id);
			
			if ($channel === FALSE)
			{
				$send = array('chnl_status' => TRUE);
				$this->channel_db->add_video_to_channel($channel_id, $video_id);
			} 
			else 
			{
				$send = array('chnl_status' => TRUE);
				$this->channel_db->del_video_on_channel($channel_id, $video_id);
			}
		}

		echo json_encode($send);
	}

	public function add_rm_like()
	{
		$video_id = $this->input->post('video_id');

		$user = $this->_user_loggin_validation();
		$video = $this->_existing_video_validation($video_id);

		if ($user === FALSE || $video === FALSE)
		{
			$send = array('like_status' => FALSE);
		}
		else
		{
			$this->load->model('like_db');
			$data = $this->like_db->check_user_like($video_id, $user['user_id']);

			if ($data === FALSE) {
				$send = array('like_status' => TRUE);
				$this->like_db->add_like_video($video_id, $user['user_id']);
			} else {
				$send = array('like_status' => TRUE);
				$this->like_db->del_like_video($video_id, $user['user_id']);
			}
		}

		echo json_encode($send);
	}

	public function add_rm_wl()
	{
		$video_id = $this->input->post('video_id');

		$user = $this->_user_loggin_validation();
		$video = $this->_existing_video_validation($video_id);

		if ($user === FALSE || $video === FALSE)
		{
			$send = array('wl_status' => FALSE);
		}
		else
		{
			$this->load->model('watch_later_db');
			$data = $this->watch_later_db->check_user_wl($video_id, $user['user_id']);

			if ($data === FALSE) {
				$send = array('wl_status' => TRUE);
				$this->watch_later_db->add_wl_video($video_id, $user['user_id']);
			} else {
				$send = array('wl_status' => TRUE);
				$this->watch_later_db->del_wl_video($video_id, $user['user_id']);
			}
		}
		
		echo json_encode($send);
	}
}