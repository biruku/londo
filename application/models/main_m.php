<?php 

class Main_m extends CI_Model {

	function q_spes($main_p = NULL){
		$company_limit	= "";
		$spes_limit		= "";
		$location_limit	= "";
		
		if(array_key_exists('company_limit',$main_p)){
			$company_limit	= "LIMIT ".$main_p['company_limit'];
		}
		if(array_key_exists('spes_limit',$main_p)){
			$spes_limit	= "LIMIT ".$main_p['spes_limit'];
		}
		if(array_key_exists('location_limit',$main_p)){
			$location_limit	= "LIMIT ".$main_p['location_limit'];
		}

		$data['q_main_spes']	= $this->db->query("SELECT * FROM `jbspecialist` where spes_level='0' ".$spes_limit." ");	//parent job
		foreach($data['q_main_spes']->result() as $spes_rows){ //child job
			$spes_id	= $spes_rows->spes_id;
			$data['q_main_spes_child'][$spes_id]	= $this->db->query("SELECT * FROM `jbspecialist` where spes_level='".$spes_id."' ".$spes_limit." ");
		}
	return $data;
	}
	
	function q_lokasi($main_p = NULL){
		$company_limit	= "";
		$spes_limit		= "";
		$location_limit	= "";
		
		if(array_key_exists('company_limit',$main_p)){
			$company_limit	= "LIMIT ".$main_p['company_limit'];
		}
		if(array_key_exists('spes_limit',$main_p)){
			$spes_limit	= "LIMIT ".$main_p['spes_limit'];
		}
		if(array_key_exists('location_limit',$main_p)){
			$location_limit	= "LIMIT ".$main_p['location_limit'];
		}

		$data['q_main_state']	= $this->db->query("SELECT *,count(*) as urut FROM  jbstate s, jbcity c where s.state_id=c.state_id and country_id='1' group by c.state_id order by urut desc");	//parent location
		foreach($data['q_main_state']->result() as $state_rows){ //child job
			$state_id	= $state_rows->state_id;
			$data['q_main_state_child'][$state_id]	= $this->db->query("SELECT * FROM `jbcity` where state_id='".$state_id."'");
		}
	return $data;
	}
	
	function q_posisi($main_p = NULL){
		$company_limit	= "";
		$spes_limit		= "";
		$location_limit	= "";
		
		if(array_key_exists('company_limit',$main_p)){
			$company_limit	= "LIMIT ".$main_p['company_limit'];
		}
		if(array_key_exists('spes_limit',$main_p)){
			$spes_limit	= "LIMIT ".$main_p['spes_limit'];
		}
		if(array_key_exists('location_limit',$main_p)){
			$location_limit	= "LIMIT ".$main_p['location_limit'];
		}

		$data['q_main_pos']	= $this->db->query("SELECT * FROM `jbposition` ");
		foreach($data['q_main_pos']->result() as $pos_rows){ //child job
			$pos_id	= $pos_rows->pos_id;
			$data['q_main_pos_child'][$pos_id]	= $this->db->query("SELECT ss.spes_value,s.spes_id, count( * ) AS byk FROM `jbvacancy` v, `jbposition` p, jbspecialist s, jbspecialist ss WHERE v.pos_id = p.pos_id AND v.spes_id = s.spes_id AND s.spes_level = ss.spes_id and p.pos_id='".$pos_id."' GROUP BY ss.spes_id ");
		}
	return $data;
	}

	function q_posisi_level($main_p = NULL){
		$company_limit	= "";
		$spes_limit		= "";
		$location_limit	= "";
		
		if(array_key_exists('company_limit',$main_p)){
			$company_limit	= "LIMIT ".$main_p['company_limit'];
		}
		if(array_key_exists('spes_limit',$main_p)){
			$spes_limit	= "LIMIT ".$main_p['spes_limit'];
		}
		if(array_key_exists('location_limit',$main_p)){
			$location_limit	= "LIMIT ".$main_p['location_limit'];
		}

		$q_main_pos	= $this->db->query("SELECT * FROM `jbposition` ");
	return $q_main_pos;
	}

	function q_state($state_p = NULL)
	{
		$where	="";
		$query	= $this->db->query("SELECT * FROM jbstate where country_id='1' ".$where." ");
		return $query;
	}
	
	function login($login_p = NULL)
	{
		$username	= $login_p['username'];
		$password	= $login_p['password'];	
		$query	= $this->db->query("SELECT * FROM jbuser where username='".$username."' and password=md5('".$password."') and enable='1'");
		return $query;
	}
	
	function ulastlogin($user_id  = NULL)
	{
		$this->db->query("update jbuser set lastlogin=NOW() where user_id='".$user_id."'");
	}
		
	function register_js($register_p = NULL)
	{	
		$sql_user	= "INSERT INTO `jbuser` ( `user_id` ,`username` ,`password` ,`email`, `enable` ,`lastlogin` ,`priv` ) 
						VALUES (NULL , '".$register_p['username']."', '".$register_p['password']."', '".$register_p['email']."', '1',CURRENT_TIMESTAMP , '".$register_p['type']."')";
		$this->db->query($sql_user);
		$id_user	= $this->db->insert_id();
		
		$sql_js	= "INSERT INTO `jbseek` (`sk_id` ,`sk_nama` ,`sk_tgl_lahir` ,`sk_jns_klm` ,`sk_alamat` ,`sk_no_tlp` , `sk_no_hp` , `user_id` , `city_id` ) 
						VALUES ( NULL , '".$register_p['sk_nama']."', '".$register_p['sk_tgl_lahir']."', '".$register_p['sk_jns_klm']."', '".$register_p['sk_alamat']."', '".$register_p['sk_no_tlp']."', '".$register_p['sk_no_hp']."', '".$id_user."', '".$register_p['city_id']."' )";
		$this->db->query($sql_js);
	}
	
	function register_cm($register_p = NULL)
	{	
		$sql_user	= "INSERT INTO `jbuser` ( `user_id` ,`username` ,`password` ,`email`, `enable` ,`lastlogin` ,`priv` ) 
						VALUES (NULL , '".$register_p['username']."', '".$register_p['password']."', '".$register_p['email']."', '1',CURRENT_TIMESTAMP , '".$register_p['type']."')";
		$this->db->query($sql_user);
		$id_user	= $this->db->insert_id();

		$sql_cm		= "INSERT INTO jbcompany (comp_id, comp_alamat, comp_nama, comp_logo, comp_desc, comp_type_id, user_id, city_id) 
						VALUES(NULL, '".$register_p['comp_alamat']."', '".$register_p['comp_nama']."', NULL, NULL, '".$register_p['comp_type_id']."','".$id_user."', '".$register_p['city_id']."')";
		$this->db->query($sql_cm);

	}
	

	function get_city($stateid)
	{
		$query	= $this->db->query("SELECT * FROM jbcity WHERE state_id='".$stateid."'");
		return $query;
	}

	function get_city_in($in)
	{
		$query	= $this->db->query("SELECT * FROM jbcity WHERE city_id IN (".$in.")");
		return $query;
	}
	
	function check_user($par)
	{
		$where	= "";
		if(!empty($par['username']) && $par['username'] != ""){
			$where	.= " and username='".$par['username']."'";
		}
		if(!empty($par['not_user_id']) && $par['not_user_id'] != ""){
			$where	.= " and user_id !='".$par['not_user_id']."'";
		}
		if(!empty($par['user_id']) && $par['user_id'] != ""){
			$where	.= " and user_id ='".$par['user_id']."'";
		}
		if(!empty($par['password_lama']) && $par['password_lama'] != ""){
			$where	.= " and password =md5('".$par['password_lama']."')";
		}
		if(!empty($par['email']) && $par['email'] != ""){
			$where	.= " and email='".$par['email']."'";
		}
		if(!empty($par['user_id']) && $par['user_id'] != ""){
			$where	.= " and user_id='".$par['user_id']."'";
		}
	
		$query	= $this->db->query("SELECT * FROM jbuser WHERE 1 ".$where." ");
		

		return $query;
	}

	function edit_account($par){	
		$set	= "";
		if(!empty($par['cpassword_baru']) && $par['cpassword_baru'] != ""){

			$sql_check_pass	= "select * from jbuser where password=md5('".$par['password_lama']."') and user_id='".$par['user_id']."'";
			$q_check_pass	= $this->db->query($sql_check_pass);
			if($q_check_pass->num_rows() > 0 ){
				$set	.= ", password=md5('".$par['cpassword_baru']."')";
			
			}

		}
	
		$sql	= "update jbuser set username='".$par['username']."' ".$set." where user_id='".$par['user_id']."'";
		$this->db->query($sql);
	}
	
}	

