<?php
class Category_db extends CI_Model{
		
	function get_video_category_join_category($video_id)
	{
		$this->db->from('category');
		$this->db->join('category_video', 'category_video.category_id = category.category_id ');
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
	
	function get_video_category($category_video_id)
	{	
		$query = $this->db->get_where('category_video', array('category_video_id' => $category_video_id));
		
		if ($query->num_rows() > 0){
			$data = $query->row();
			return $data; 
		} else {
			return FALSE;
		}
	}
	
	function get_category_term($term)
	{
		$this->db->like('category', $term); 
		
		$query = $this->db->get('category');
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$data['value'] = $row->category;
				$data['id'] = $row->category_id;
				$data_set[] = $data;
			}
			return $data_set; 
		} else {
			return FALSE;
		}
	}
	
	function add_category($category, $video_id)
	{
		$query = $this->db->get_where('category', array('category' => $category));
		if ($query->num_rows() > 0){
			$category_id = $query->row()->category_id;
		} else {
			$category_id = $this->get_id();
		
			$this->db->insert('category', array(
				'category_id' => $category_id,
				'category' => $category
			));
		}
		
		$this->db->insert('category_video', array(
			'category_id' => $category_id,
			'video_id' => $video_id
		));

		$this->db->from('category');
		$this->db->join('category_video', 'category_video.category_id = category.category_id ');
		$this->db->where('video_id' , $video_id);
		$this->db->where('category_video.category_id' , $category_id);
			
		$query = $this->db->get();

		$data = $query->row();
		
		return $data;
	}
	
	function get_category_video($video_id, $category){
		$this->db->from('category');
		$this->db->join('category_video', 'category_video.category_id = category.category_id ');
		$this->db->where('video_id' , $video_id);
		$this->db->where('category' , $category);
			
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			$data = $query->row();
			return $data;
		} else {
			return FALSE;
		}
	}
	
	function del_video_category($category_video_id){
		$this->db->delete('category_video', array('category_video_id' => $category_video_id));
	}
	
	function get_id()
	{
		do {
			$id = random_string('numeric', 3);
			$query = $this->db->get_where('category', array('category_id' => $id));
		} while ($query->num_rows() > 0);
		
		return $id;
	}
}

/*
 * category
 * category_id - category
 * 
 * category_video
 * category_video_id - catagory_id - video_id
 */