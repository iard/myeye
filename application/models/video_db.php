<?php
class Video_db extends CI_Model {

	function get_video($video_id)
	{
		$this->db->select('user.user_id, nim, user_name, avatar_url, user_date, video_id, title, video_url, duration, frame_count, frame_rate, frame_width, frame_height, screenshot_url, video_date, note');
		$this->db->from('user');
		$this->db->join('video', 'video.user_id=user.user_id');
		$this->db->where('video_id', $video_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row(); 
		} 
		else 
		{
			return FALSE;
		}	
	}
	
	function add_video($video_id, $user_id, $video_url, $frame_count, $frame_rate, $duration, $screenshot_url, $frame_width, $frame_height)
	{
		$data = array(
		'video_id' => $video_id,
		'user_id' => $user_id,
		'video_url' => $video_url,
		'frame_count' => $frame_count,
		'frame_rate' => $frame_rate,
		'duration' =>$duration,
		'screenshot_url' => $screenshot_url,
		'frame_width' => $frame_width,
		'frame_height' => $frame_height,
		'video_date' => date("Y-m-d H:i:s")
		);
		
		$this->db->insert('video', $data);
	}
	
	function edit_video_des($video_id, $title, $screenshot_url, $note)
	{
		$data = array(
			'title' => $title,
			'note' => $note
		);

		if ($screenshot_url !== NULL) {
			$data['screenshot_url'] = $screenshot_url;
		}
		
		$this->db->where('video_id', $video_id);
		$this->db->update('video', $data); 
	}
	
	function get_user_video($user_id)
	{
		$this->db->order_by('video_date desc');
		$query = $this->db->get_where('video', array('user_id' => $user_id));
		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}
	}

	function delete_video($video_id)
	{
		$query = $this->db->get_where('video', array('video_id' => $video_id));

		$video = $query->row();

		unlink('./vid/'.$video->video_url);
		unlink('./img/screenshot/big/'.$video->screenshot_url);
		unlink('./img/screenshot/small/'.$video->screenshot_url);

		$query = $this->db->get_where('subtitle', array('video_id' => $video_id));

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				unlink('./sub/'.$row->subtitle_url);
			}
		}

		$this->db->delete('category_video', array('video_id' => $video_id)); 
		$this->db->delete('channel_video', array('video_id' => $video_id)); 
		$this->db->delete('comment', array('video_id' => $video_id)); 
		$this->db->delete('like', array('video_id' => $video_id)); 
		$this->db->delete('played', array('video_id' => $video_id)); 
		$this->db->delete('playlist_video', array('video_id' => $video_id)); 
		$this->db->delete('subtitle', array('video_id' => $video_id));
		$this->db->delete('watch_later', array('video_id' => $video_id));
		$this->db->delete('video', array('video_id' => $video_id));
	}
}
