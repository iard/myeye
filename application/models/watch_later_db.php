<?php
class Watch_later_db extends CI_Model {
	
	function get_user_wl($user_id)
	{
		$this->db->from('watch_later');
		$this->db->join('video', 'video.video_id = watch_later.video_id');
		$this->db->where('watch_later.user_id', $user_id);
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

	function check_user_wl($video_id, $user_id)
	{
		$query = $this->db->get_where('watch_later', array('user_id' => $user_id, 'video_id' => $video_id));
		
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	function add_wl_video($video_id, $user_id)
	{
		$data = array(
		'video_id' => $video_id,
		'user_id' => $user_id,
		'watch_later_date' => date("Y-m-d H:i:s")
		);
		
		$this->db->insert('watch_later', $data);
	}
	
	function del_wl_video($video_id, $user_id){
		$data = array(
		'video_id' => $video_id,
		'user_id' => $user_id
		);
		
		$this->db->delete('watch_later', $data ); 
	}
}