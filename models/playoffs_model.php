<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Playoffs_model
	
*/

class Playoffs_model extends BF_Model 
{

	protected $table		= '';
	protected $key			= 'id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = false;
	
	/*-----------------------------------------------
	/	PUBLIC FUNCTIONS
	/----------------------------------------------*/
    public function __construct()
    {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
        $this->use_prefix = ($this->settings_lib->item('ootp.use_db_prefix') == 1) ? true : false;
    }
    public function load_playoff_structure($league_id = 100) {
		$struct = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('*')
				 ->where('league_id',$league_id);
		$query = $this->db->get('league_playoffs');
		if ($query->num_rows() > 0) {
			$struct = $query->result_array();
		}
		$query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $struct;
	}
	
	public function get_playoff_teams($league_id = 100) {
	
		return false;
	}

    public function get_team_information($league_id = 100) {
        $teams = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('teams.team_id,abbr,name,nickname,sub_league_id,logo_file,w,l,pos')
            ->join('team_record','team_record.team_id = teams.team_id','left')
            ->where('teams.league_id',$league_id);
        $query = $this->db->get('teams');
        if ($query->num_rows() > 0) {
			$teams = $query->result_array();
		}
        $query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $teams;
    }
	
	public function get_playoff_games($league_id = 100) {
		$games = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('game_id,home_team,away_team,date,played,runs0,runs1')
				  ->where('game_type',3)
				  ->where('league_id',$league_id)
				  ->order_by('date,time','asc');
		$query = $this->db->get('games');
		if ($query->num_rows() > 0) {
			$games = $query->result_array();
		}
		$query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $games;
	}

    public function generate_playoff_data($teams,$games, $subleagues,$playoff_structure) {

        $return_arr = array();
        if (isset($games) && is_array($games) && count($games)) {

            foreach($games as $row)
            {
                $gid=$row['game_id'];
                $hid=$row['home_team'];
                $aid=$row['away_team'];
                $minTID=min($aid,$hid);
                $maxTID=max($aid,$hid);
                $serID=$minTID.":".$maxTID;

                $runs0=$row['runs0'];
                $runs1=$row['runs1'];
                $played=$row['played'];

                if (!isset($teams[$hid][$serID]))
                {
                    $teams[$hid][$serID]=1;
                    $teams[$aid][$serID]=1;
                    $series[$serID][$hid]['w']=0;
                    $series[$serID][$aid]['w']=0;
                    $rnd=$teams[$hid]['rnd']+1;
                    $teams[$hid]['rnd']=$rnd;
                    $teams[$aid]['rnd']=$rnd;
                    $series[$serID]['rnd']=$rnd;
                    $series[$serID]['slid']=$teams[$hid]['slid'];
                    $rounds[$rnd]=1;
                }

                if ($played==1)
                {
                    if ($runs1>$runs0)
                    {
                        $series[$serID][$hid]['w']=$series[$serID][$hid]['w']+1;
                    }
                    else
                    {
                        $series[$serID][$aid]['w']=$series[$serID][$aid]['w']+1;
                    }
                }
            }
            $return_arr = array($teams, $rounds, $series);
        }
        return $return_arr;
    }
	/*-----------------------------------------------
	/	PRIVATE FUNCTIONS
	/----------------------------------------------*/

}