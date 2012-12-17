<?php
class Playlist_db extends CI_Model 
{
	function get_playlist($playlist_id)
	{
		$query = $this->db->get_where('playlist', array('playlist_id' => $playlist_id));
		
		if ($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}
	
	function get_playlist_join_user($playlist_id)
	{
		$this->db->select('user.user_id, nim, user_name, email, avatar_url, user_date, playlist_id, title, note, playlist_date');
		$this->db->from('user');
		$this->db->join('playlist', 'playlist.user_id = user.user_id');		
		$this->db->where('playlist_id' , $playlist_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}
	
	function get_user_playlist($user_id)
	{
		$query = $this->db->get_where('playlist', array('user_id' => $user_id));
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) 
			{
				$this->db->select('screenshot_url');
				$this->db->from('playlist_video');
				$this->db->join('video', 'video.video_id = playlist_video.video_id');
				$this->db->where('playlist_id', $row->playlist_id);
				$this->db->limit(4);
				$sec_query = $this->db->get();

				if ($sec_query->num_rows() > 0) 
				{
					foreach ($sec_query->result() as $sec_row) 
					{
						$row->video[] = $sec_row->screenshot_url;
					}
				} 
				else 
				{
					$row->video = NULL;
				}

				$data[] = $row;
			}
			return $data;
		} 
		else 
		{
			return FALSE;
		}	
	}
	
	function get_playlist_video_join_video($playlist_id)
	{
		$this->db->from('video');
		$this->db->join('playlist_video', 'playlist_video.video_id = video.video_id');		
		$this->db->where('playlist_id' , $playlist_id);

		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}	
	}
	
	function get_video_on_playlist($playlist_id, $video_id)
	{
		$this->db->from('playlist');
		$this->db->join('playlist_video', 'playlist_video.playlist_id = playlist.playlist_id');		
		$this->db->where('playlist.playlist_id' , $playlist_id);
		$this->db->where('video_id', $video_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			$data= $query->row(); 
			return $data;
		} else {
			return FALSE;
		}	
	}

	function get_playlist_video_join_playlist($playlist_video_id)
	{
		$this->db->from('playlist');
		$this->db->join('playlist_video', 'playlist_video.playlist_id = playlist.playlist_id');		
		$this->db->where('playlist_video.playlist_video_id' , $playlist_video_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$data= $query->row(); 
			return $data;
		} 
		else 
		{
			return FALSE;
		}
	}
	
	function get_user_video_playlist($user_id, $video_id)
	{
		$query = $this->db->get_where('playlist', array('user_id' => $user_id));
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$sec_query = $this->db->get_where('playlist_video', array('playlist_id' => $row->playlist_id, 'video_id' => $video_id));
				
				if ($sec_query->num_rows() > 0) {
					$row->playlist_video_id = $sec_query->row()->playlist_video_id;
					$row->video_id = $sec_query->row()->video_id;
				} else {
					$row->playlist_video_id = NULL;
					$row->video_id = NULL;
				}
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}	
	}
	
	function create_playlist($playlist_id, $user_id, $title, $note)
	{
		$data = array(
		'playlist_id' => $playlist_id,
		'user_id' => $user_id,
		'title' => $title,
		'note' => $note,
		'playlist_date' => date("Y-m-d H:i:s")
		);
		
		$this->db->insert('playlist', $data);
	}
	
	function edit_playlist($playlist_id, $title, $note)
	{
		$data = array(
			'title' => $title,
			'note' => $note
		);
		
		$this->db->where('playlist_id', $playlist_id);
		$this->db->update('playlist', $data); 
	}
	
	function del_playlist($playlist_id)
	{
		$data = array(
		'playlist_id' => $playlist_id
		);
		
		$this->db->delete('playlist', $data );
		$this->db->delete('playlist_video', $data ); 
	}
	
	function add_video_to_playlist($playlist_id, $video_id)
	{
		$data = array(
		'playlist_id' => $playlist_id,
		'video_id' => $video_id
		);
		
		$this->db->insert('playlist_video', $data);
	}
	
	function del_video_on_playlist($playlist_id, $video_id)
	{
		$data = array(
		'playlist_id' => $playlist_id,
		'video_id' => $video_id
		);

		$this->db->delete('playlist_video', $data ); 
	}
	
	function del_video_from_playlist($playlist_video_id)
	{
		$data = array(
		'playlist_video_id' => $playlist_video_id
		);

		$this->db->delete('playlist_video', $data ); 
	}
}

/*
 * playlist
 * playlist_id - user_id - title - note - playlist_date
 * 
 * playlist_video
 * playlist_video_id - playlist_id - video_id
 */