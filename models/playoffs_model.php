<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Playoffs_model
	
*/

class Playoffs_model extends BF_Model
{

    protected $table = '';
    protected $key = 'id';
    protected $soft_deletes = false;
    protected $date_format = 'datetime';
    protected $set_created = false;
    protected $set_modified = false;

    protected $dbprefix = '';
    protected $use_prefix = false;

    /*-----------------------------------------------
     /	PUBLIC FUNCTIONS
     /----------------------------------------------*/

	//--------------------------------------------------------------------
	
	/**
	 * C'TOR.
	 *
	 * CReates a new instance of Playoffs_model.
	 *
	 * @return	<void>
	 */
   public function __construct()
    {
        parent::__construct();
		// Since this model doesn't extend the base model in the open sports toolkit, we do this manually
        $this->dbprefix = $this->db->dbprefix;
        $this->use_prefix = ($this->settings_lib->item('osp.use_db_prefix') == 1) ? true : false;
    }

	//--------------------------------------------------------------------
	
	/**
	 * LOAD PLAYOFF STRUCTURE.
	 *
	 * This function takes the listings of all teams for the selected league and their information.
	 *
	 * @param	int	$league_id				League Id, default is 100
	 * @return	Array						Team information array
	 */
   public function load_playoff_structure($league_id = 100)
    {
        $struct = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';

        $max_round = 1;
        $this->db->select('max_round')
            ->where('league_id', $league_id);
        $query = $this->db->get('league_playoffs');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $max_round = $row->max_round;
        }
        $query->free_result();
        unset($query);

        $round_name_select = '';
        $counter = 1;
        while ($counter < ($max_round + 1)) {
            if (!empty($round_name_select)) {
                $round_name_select .= ",";
            }
            $round_name_select .= 'round_names' . ($counter - 1);
            $counter++;
        }
        $this->db->select('play_off_mode,round,max_round,' . $round_name_select)
            ->where('league_id', $league_id);
        $query = $this->db->get('league_playoffs');
        if ($query->num_rows() > 0) {
            $struct = $query->result_array();
        }
        $query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $struct[0];
    }

	//--------------------------------------------------------------------
	
	/**
	 * GET TEAM INFORMATION.
	 *
	 * This function takes the listings of all teams for the selected league and their information.
	 *
	 * @param	int	$league_id				League Id, default is 100
	 * @return	Array						Team information array
	 */
   public function get_team_information($league_id = 100)
    {
        $teams = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('teams.team_id,abbr,name,nickname,sub_league_id,logo_file,text_color_id,background_color_id, w,l,pos')
            ->join('team_record', 'team_record.team_id = teams.team_id', 'left')
            ->where('teams.league_id', $league_id);
        $query = $this->db->get('teams');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $teams = $teams + array($row->team_id => array('team_id' => $row->team_id, 'abbr' => $row->abbr, 'name' => $row->name, 'nickname' => $row->nickname,
                    'city' => $row->name, 'sub_league_id' => $row->sub_league_id, 'logo_file' => $row->logo_file, 'text_color_id' => $row->text_color_id,
                    'background_color_id' => $row->background_color_id, 'w' => $row->w, 'l' => $row->l, 'pos' => $row->pos));
            }
        }
        $query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $teams;
    }

	//--------------------------------------------------------------------
	
	/**
	 * GET PLAYOFF GAMES.
	 *
	 * This function takes the listings of teams, games, subleagues and playoff structure and creates arrays 
	 * for output in the view. This code is based off the playoffs.php code found in StatsLab v9+.
	 *
	 * @author	Frank Esselink
	 */
    public function get_playoff_games($league_id = 100, $home_team = false, $away_team = false)
    {
        $games = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        $this->db->select('game_id,home_team,away_team,date,time,played,innings,runs0,runs1,hits0,hits1,errors0,errors1,winning_pitcher,losing_pitcher,save_pitcher')
            ->where('game_type', 3)
            ->where('league_id', $league_id);
        if ($home_team !== false && $away_team !== false)  {
            $this->db->where('((home_team='.$home_team.' AND away_team='.$away_team.') OR (home_team='.$away_team.' AND away_team='.$home_team.'))');
        }
        $this->db->order_by('date,time', 'asc');
        $query = $this->db->get('games');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $games = $games + array($row->game_id => array('game_id'=>$row->game_id,'home_team'=>$row->home_team,'hid'=>$row->home_team,'away_team'=>$row->away_team,'aid'=>$row->away_team,'date'=>$row->date,'time'=>$row->time,'played'=>$row->played,'innings'=>$row->innings,
                'runs0'=>$row->runs0,'runs1'=>$row->runs1,'hits0'=>$row->hits0,'hits1'=>$row->hits1,'errors0'=>$row->errors0,'errors1'=>$row->errors1,'winning_pitcher'=>$row->winning_pitcher,'losing_pitcher'=>$row->losing_pitcher,'save_pitcher'=>$row->save_pitcher,));
            }
        }
        $query->free_result();
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $games;
    }

	//--------------------------------------------------------------------
	
	/**
	 * GENERATE PLAYOFF DATA.
	 *
	 * This function takes the listings of teams, games, subleagues and playoff structure and creates arrays 
	 * for output in the view. This code is based off the playoffs.php code found in StatsLab v9+.
	 *
	 * @param	Array	$teams				Array of team information
	 * @param	Array	$games				Array of game data
	 * @param	Array	$subleagues			Array of subleagues
	 * @param	Array	$playoff_structure	Array of playoff information
	 * @return	Array						array(ammended teams array, rounds, series, game id list, playoff count)
	 * @auuthor	Frank Esselink
	 */
    public function generate_playoff_data($teams = false, $games = false, $subleagues = false, $playoff_structure = false)
    {
        $return_arr = array();
        if ($games !== false && is_array($games) && count($games)) {

            $gidList = array();
            $pcnt = 0;
            $rnd = 0;
            foreach ($games as $game_id => $row) {
                $hid = $row['home_team'];
                $aid = $row['away_team'];
                $minTID = min($aid, $hid);
                $maxTID = max($aid, $hid);
                $serID = $minTID . "_" . $maxTID;

                $runs0 = $row['runs0'];
                $runs1 = $row['runs1'];
                $played = $row['played'];

                if (!isset($teams[$hid][$serID])) {
                    $teams[$hid][$serID] = 1;
                    $teams[$aid][$serID] = 1;
                    $series[$serID][$hid]['w'] = 0;
                    $series[$serID][$aid]['w'] = 0;
                    $rnd = (isset($teams[$hid]['rnd'])) ? $teams[$hid]['rnd'] + 1 : 1;
                    $teams[$hid]['rnd'] = $rnd;
                    $teams[$aid]['rnd'] = $rnd;
                    $series[$serID]['rnd'] = $rnd;
                    $series[$serID]['slid'] = $teams[$hid]['sub_league_id'];
                    $rounds[$rnd] = 1;
                }

                if ($played == 1) {
                    if ($runs1 > $runs0) {
                        $series[$serID][$hid]['w'] = $series[$serID][$hid]['w'] + 1;
                    }
                    else {
                        $series[$serID][$aid]['w'] = $series[$serID][$aid]['w'] + 1;
                    }
                    //if (!empty($gidList)) { $gidList .= ","; }
                    array_push($gidList, intval($game_id));
                    $pcnt += 1;
                }
            }
            //echo("game id list = ".$gidList."<br />");
            $return_arr = array($teams, $rounds, $series, $gidList, $pcnt);
        }
        return $return_arr;
    }
    /*-----------------------------------------------
     /	PRIVATE FUNCTIONS
     /----------------------------------------------*/

}