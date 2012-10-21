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

        $max_round = 1;
        $this->db->select('max_round')
                 ->where('league_id',$league_id);
        $query = $this->db->get('league_playoffs');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $max_round = $row->max_round;
        }
        $query->free_result();
        unset($query);

        $round_name_select = '';
        $counter = 1;
        while ($counter < ($max_round+1))  {
             if (!empty($round_name_select)) { $round_name_select .= ","; }
             $round_name_select .= 'round_names'.($counter-1);
             $counter++;
        }
        $this->db->select('play_off_mode,round,max_round,'.$round_name_select)
				 ->where('league_id',$league_id);
		$query = $this->db->get('league_playoffs');
		if ($query->num_rows() > 0) {
			$struct = $query->result_array();
		}
		$query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $struct[0];
	}

    public function get_team_information($league_id = 100) {
        $teams = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('teams.team_id,abbr,name,nickname,sub_league_id,logo_file,text_color_id,background_color_id, w,l,pos')
            ->join('team_record','team_record.team_id = teams.team_id','left')
            ->where('teams.league_id',$league_id);
        $query = $this->db->get('teams');
        if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$teams = $teams + array($row->team_id => array('team_id'=>$row->team_id, 'abbr'=>$row->abbr,'name'=>$row->name,'nickname'=>$row->nickname,
				'city'=>$row->name,'sub_league_id'=>$row->sub_league_id,'logo'=>$row->logo_file,'text_color_id'=>$row->text_color_id,
				'background_color_id'=>$row->background_color_id,'w'=>$row->w,'l'=>$row->l,'pos'=>$row->pos));
			}
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

    public function generate_summary_data($teams,$games, $subleagues, $playoff_structure) {

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
                    $series[$serID]['slid']=$teams[$hid]['sub_league_id'];
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
        foreach($rounds as $round => $val) {
            echo($round ." = ".$val."<br />");
        }
        return $return_arr;
    }

    public function generate_series_data($teams,$games, $subleagues, $playoff_structure) {
        $return_arr = array();

        return $return_arr;
    }
	/*-----------------------------------------------
	/	PRIVATE FUNCTIONS
	/----------------------------------------------*/

}