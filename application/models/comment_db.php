<?php
class Comment_db extends CI_Model{
	
	function get_comment($comment_id)
	{
		$query = $this->db->get_where('comment', array('comment_id' => $comment_id));
		
		if ($query->num_rows() > 0)
		{
			$data = $query->row();
			return $data; 
		} 
		else 
		{
			return FALSE;
		}
	}
	
	function add_comment($user_id, $video_id, $comment)
	{
		$comment_id = $this->get_id();
		
		$this->db->insert('comment', array(
			'comment_id' => $comment_id,
			'video_id' => $video_id,
			'user_id' => $user_id,
			'comment' => $comment, 
			'comment_date' => date("Y-m-d H:i:s")
		));
		
		$this->db->select('comment_id, video_id, user.user_id, user_name, avatar_url, comment, comment_date');
		$this->db->from('user');
		$this->db->join('comment', 'user.user_id = comment.user_id');
		$this->db->where('comment_id' , $comment_id);
			
		$query = $this->db->get();
		
		$data = $query->row();
		
		return $data;
	}

	function del_comment($comment_id)
	{
		$this->db->delete('comment', array('comment_id' => $comment_id));
	}
	
	function get_video_comment($video_id)
	{
		$this->db->select('comment_id, video_id, user.user_id, user_name, avatar_url, comment, comment_date');
		$this->db->from('user');
		$this->db->join('comment', 'user.user_id = comment.user_id');
		$this->db->where('video_id' , $video_id);
		$this->db->order_by("comment_date", "asc"); 
			
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			return $data; 
		} 
		else 
		{
			return FALSE;
		}		
	}
	
	function get_id()
	{
		do {
			$id = random_string('alnum', 10);
			$data = $this->get_comment($id);
		} while ($data !== FALSE);
		
		return $id;
	}

}

/*
 * comment
 * comment_id - video_id - user_id - comment - comment_date
 */