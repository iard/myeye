<?php
class Like_db extends CI_Model {
	
	function get_user_like($user_id)
	{
		$this->db->from('like');
		$this->db->join('video', 'video.video_id = like.video_id');
		$this->db->where('like.user_id', $user_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}	
	}

	function check_user_like($video_id, $user_id)
	{
		$query = $this->db->get_where('like', array('user_id' => $user_id, 'video_id' => $video_id));
		
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function add_like_video($video_id, $user_id)
	{
		$data = array(
		'video_id' => $video_id,
		'user_id' => $user_id,
		'like_date' => date("Y-m-d H:i:s")
		);
		
		$this->db->insert('like', $data);
	}
	
	function del_like_video($video_id, $user_id)
	{
		$data = array(
		'video_id' => $video_id,
		'user_id' => $user_id
		);
		
		$this->db->delete('like', $data ); 
	}
}