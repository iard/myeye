<?php
class User_db extends CI_Model {

	function get_user($user_id)
	{
		$this->db->select('user_id, nim, user_name, email, avatar_url, user_date, level');
		$query = $this->db->get_where('user', array('user_id' => $user_id));
		
		if ($query->num_rows == 1 )
		{
			$data = $query->row(); 
			return $data;
		} else {
			return FALSE;
		}
	}
	
	function get_all_user()
	{
		$this->db->select('user_id, user_name');
		$query = $this->db->get('user');
		
		if ($query->num_rows > 0 ){
			foreach ($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}
	}
	
	function user_validate($nim_username, $password)
	{
		$this->db->select('user_id, nim, user_name, avatar_url, level');	
		$this->db->where('user_name', $nim_username);
		$this->db->where('password', md5($password));			
		$query = $this->db->get('user');
		
		if ($query->num_rows == 1 )
		{
			$data = $query->row(); 
			return $data;
		} else {
			$this->db->select('user_id, nim, user_name, avatar_url, level');	
			$this->db->where('nim', $nim_username);
			$this->db->where('password', md5($password));			
			$query = $this->db->get('user');
			
			if ($query->num_rows == 1 )
			{
				$data = $query->row(); 
				return $data;
			} else {
				return FALSE;
			}
		}
	}
	
	function add_user($user_id, $nim, $user_name, $password, $email, $level)
	{
		$data = array(
		'user_id' => $user_id,
		'nim' => $nim,
		'user_name' => $user_name,
		'password' => md5($password),
		'email' => $email,
		'avatar_url' => 'default.png',
		'user_date' => date("Y-m-d"),
		'level' => $level
		);
		
		$this->db->insert('user', $data);
	}
	
	function edit_user($user_id, $nim, $user_name, $password, $email)
	{
		if($password !== ''){
			$data = array(
			'nim' => $nim,
			'user_name' => $user_name,
			'password' => md5($password),
			'email' => $email
			);
		} else {
			$data = array(
			'nim' => $nim,
			'user_name' => $user_name,
			'email' => $email
			);
		}
		
		$this->db->where('user_id', $user_id);
		$this->db->update('user', $data); 
	}

	function edit_avatar($user_id, $avatar_url){
		$data = array(
		'avatar_url' => $avatar_url
		);
		
		$this->db->where('user_id', $user_id);
		$this->db->update('user', $data); 
	}
	
	function check($item, $value)
	{
		$query = $this->db->get_where('user', array($item => $value));
		
		if ($query->num_rows == 1 ){ 
			$data = $query->row(); 
			return $data;
		} else {
			return FALSE;
		}
	}
	
	
    
}
