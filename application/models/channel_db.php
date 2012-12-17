<?php
class Channel_db extends CI_Model 
{
	function get_channel($channel_id)
	{
		$query = $this->db->get_where('channel', array('channel_id' => $channel_id));
		
		if ($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}
	
	function get_channel_join_user($channel_id)
	{
		$this->db->select('user.user_id, nim, user_name, email, avatar_url, user_date, channel_id, title, note');
		$this->db->from('user');
		$this->db->join('channel', 'channel.user_id = user.user_id');		
		$this->db->where('channel_id' , $channel_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}
	
	function get_user_channel($user_id)
	{
		$query = $this->db->get_where('channel', array('user_id' => $user_id));
		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}	
	}
	
	function get_channel_video_join_video($channel_id)
	{
		$this->db->from('video');
		$this->db->join('channel_video', 'channel_video.video_id = video.video_id');		
		$this->db->where('channel_id' , $channel_id);

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
	
	function get_video_on_channel($channel_id, $video_id = NULL)
	{
		$this->db->from('channel');
		$this->db->join('channel_video', 'channel_video.channel_id = channel.channel_id');		
		$this->db->where('channel.channel_id' , $channel_id);
		if ($video_id !== NULL){
			$this->db->where('video_id', $video_id);
		}
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			$data= $query->row(); 
			return $data;
		} else {
			return FALSE;
		}	
	}
	
	function get_user_video_channel($user_id, $video_id)
	{
		$query = $this->db->get_where('channel', array('user_id' => $user_id));
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$sec_query = $this->db->get_where('channel_video', array('channel_id' => $row->channel_id, 'video_id' => $video_id));
				
				if ($sec_query->num_rows() > 0) {
					$row->channel_video_id = $sec_query->row()->channel_video_id;
					$row->video_id = $sec_query->row()->video_id;
				} else {
					$row->channel_video_id = NULL;
					$row->video_id = NULL;
				}
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}	
	}
	
	function create_channel($channel_id, $user_id, $title, $note)
	{
		$data = array(
		'channel_id' => $channel_id,
		'user_id' => $user_id,
		'title' => $title,
		'note' => $note
		);
		
		$this->db->insert('channel', $data);
	}
	
	function del_channel($channel_id)
	{
		$data = array(
		'channel_id' => $channel_id
		);
		
		$this->db->delete('channel', $data );
		$this->db->delete('channel_video', $data ); 
	}
	
	function add_video_to_channel($channel_id, $video_id)
	{
		$data = array(
		'channel_id' => $channel_id,
		'video_id' => $video_id
		);
		
		$this->db->insert('channel_video', $data);
	}
	
	function del_video_on_channel($channel_id, $video_id)
	{
		$data = array(
		'channel_id' => $channel_id,
		'video_id' => $video_id
		);

		$this->db->delete('channel_video', $data ); 
	}
}

/*
 * channel
 * channel_id - user_id - title - note
 * 
 * channel_video
 * channel_video_id - channel_id - video_id
 */	