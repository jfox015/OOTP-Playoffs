<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_playoffs extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$data = array(
			'name'        => 'Playoffs.Settings.Manage' ,
			'description' => 'Manage OOTP Playoffs Settings' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		// Categories
		$this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field("`league_id` int(11) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field("`season` int(4) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`round` int(1) NOT NULL DEFAULT '1'");
		$this->dbforge->add_field("`series` int(4) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field("`mvp_id` int(11) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field("`mvp_summary` varchar(500) NOT NULL DEFAULT ''");
		$this->dbforge->add_field("`recap` varchar(2000) NOT NULL DEFAULT ''");
		
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('playoffs_round_details');
		
		$this->db->query("UPDATE {$prefix}sql_tables SET required = 1 WHERE name = 'league_playoffs' OR name = 'league_playoff_fixtures' OR name = 'team_record' OR name = 'sub_leagues'");
		
		if ($this->db->table_exists('navigation')) 
		{
			$query = $this->db->query("SELECT nav_group_id FROM {$prefix}navigation_group where title = 'header_nav'");
			if ($query->num_rows() > 0) 
			{
				$row = $query->row();
				$nav_group_id = $row->nav_group_id;
				$data = array('nav_id'=>0,
					  'title'=>'Playoffs',
					  'url'=>'/playoffs',
					  'nav_group_id'=>$nav_group_id,
					  'position'=>4,
					  'parent_id'=>0,
					  'has_kids'=>0);
				$this->db->insert("{$prefix}navigation",$data);
			}
			$query->free_result();
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Playoffs.Settings.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the permission
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Playoffs.Settings.Manage')");
		
		$this->dbforge->drop_table('playoffs_round_details');
		
		$this->db->query("UPDATE {$prefix}sql_tables SET required = 0 WHERE name = 'league_playoffs' OR name = 'league_playoff_fixtures' OR name = 'team_record' OR name = 'sub_leagues'");
		
		//delete the nav item
		$this->db->query("DELETE FROM {$prefix}navigation WHERE (title = 'Playoffs')");
	}
	
	//--------------------------------------------------------------------
	
}