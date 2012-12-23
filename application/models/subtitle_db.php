<?php
class Subtitle_db extends CI_Model{

	function get_video_sub($video_id)
	{
		$this->db->from('subtitle');
		$this->db->join('subtitle_language', 'subtitle_language.subtitle_language_id = sub_language_id');		
		$this->db->where('video_id' , $video_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}
	}
		
	function get_sub_language()
	{
		$query = $this->db->get('subtitle_language');
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[$row->subtitle_language_id] = $row->language;
			}
			return $data; 
		} 
		else 
		{
			return FALSE;
		}
	}

	function get_sub($subtitle_id)
	{
		$query = $this->db->get_where('subtitle', array('subtitle_id' => $subtitle_id));
		
		if ($query->num_rows() > 0)
		{
			return $query->row(); 
		} 
		else 
		{
			return FALSE;
		}
	}

	function add_subtitle($video_id, $sub_lang_id, $subtitle_url)
	{
		$this->db->from('subtitle');
		$this->db->where('video_id', $video_id);
		$this->db->where('sub_language_id', $sub_lang_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			foreach ($query->result() as $row) 
			{
				unlink('./sub/'.$row->subtitle_url);
				$this->db->delete('subtitle', array('subtitle_id' => $row->subtitle_id));
			}
		}

		$data = array('video_id' => $video_id, 
			'sub_language_id' => $sub_lang_id, 
			'subtitle_url' =>$subtitle_url
		);

		$this->db->insert('subtitle', $data);
	}

	function del_video_sub($subtitle_id)
	{
		$this->db->from('subtitle');
		$this->db->where('subtitle_id', $subtitle_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			unlink('./sub/'.$query->row()->subtitle_url);
			$this->db->delete('subtitle', array('subtitle_id' => $row->subtitle_id));
		}
	}
}

/*
subtitle_language
subtitle_language_id - language

subtitle
subtitle_id - video_id - subtitle_url - sub_language_id
*/