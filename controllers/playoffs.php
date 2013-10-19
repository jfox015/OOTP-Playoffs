<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Playoffs extends Front_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('playoffs_model');
		$this->load->model('open_sports_toolkit/leagues_model');
		$this->load->model('open_sports_toolkit/teams_model');
		
		$this->lang->load('playoffs');
		
	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		redirect('/playoffs/summary/');
	}
	
	public function summary() {
		
		$settings = $this->settings_lib->find_all();
		
		// Extract Parameters
		$league_id = $this->uri->segment(3);
		$league_id = (isset($league_id) && !empty($league_id)) ? $league_id : 100;
		$season = $this->uri->segment(4);
		$season = (isset($season) && !empty($season)) ? $season : -1;
		
		// Unless a specific season is passed, assume current or last post-season data
		if ($season != -1 && ($season === null || empty($season))) {
			$seasons = $this->leagues_model->get_all_seasons($league_id);
			if (is_array($seasons) && count($seasons) > 0) {
				$season = $seasons[0];
			}
		}
		// Get Structure
		$subleagues = $this->leagues_model->get_subleague_info($league_id);
		$playofff_struct = $this->playoffs_model->load_playoff_structure($league_id);
		$teams = $this->teams_model->get_teams_array($league_id, true);
        $games = $this->playoffs_model->get_playoff_games($league_id);

        $playoff_data = $this->playoffs_model->generate_playoff_data($teams,$games,$subleagues,$playofff_struct);

		if (is_array($playoff_data) && count($playoff_data)) {
			$teams = $playoff_data[0];
			$rounds = $playoff_data[1];
			$series = $playoff_data[2];

            // ASSURE PATH COMPLIANCE TO OOPT VERSION
            $this->load->helper('open_sports_toolkit/general');
            $settings = get_asset_path($settings);

            Template::set('teams',$teams);

			Template::set('rounds',$rounds);
			Template::set('subleagues',$subleagues);
			Template::set('series',$series);
			Template::set('playoffConfig',$playofff_struct);
			Template::set('settings',$settings);
			Template::set('league_id',$league_id);
			Template::set('year',$playoff_data[5]);

            Assets::add_module_css('playoffs','playoffs.css');

        }

        Template::render();
	}
	
	public function series() {

        $settings = $this->settings_lib->find_all();

        // Extract Parameters
        $league_id = $this->uri->segment(3);
        $league_id = (isset($league_id) && !empty($league_id)) ? $league_id : 100;
        $series_id = $this->uri->segment(4);
        $series_id = (isset($series_id) && !empty($series_id)) ? $series_id : -1;
        $round = $this->uri->segment(5);
        $round = (isset($round) && !empty($round)) ? $round : 1;

        // Unless a specific season is passed, assume current or last post-season data
        if ($series_id != -1) {
            // Get Structure
            $subleagues = $this->leagues_model->get_subleague_info($league_id);
            $playofff_struct = $this->playoffs_model->load_playoff_structure($league_id);
            $teams = $this->teams_model->get_teams_array($league_id, true);
            $sTeams = explode("_",$series_id);
            $games = $this->playoffs_model->get_playoff_games($league_id, $sTeams[0], $sTeams[1]);
            
            $playoff_data = $this->playoffs_model->generate_playoff_data($teams,$games,$subleagues,$playofff_struct);

			if (is_array($playoff_data) && count($playoff_data)) {

                $this->load->helper('open_sports_toolkit/general');
                $this->load->model('lastsim/lastsim_model');

                // ASSURE PATH COMPLIANCE TO OOPT VERSION
                $settings = get_asset_path($settings);

                $teams = $playoff_data[0];
                $rounds = $playoff_data[1];
                $series = $playoff_data[2];
                $gidList = $playoff_data[3];
                $pcnt = $playoff_data[4];

                // Team Situational Records
				$teams = $this->lastsim_model->get_team_situational_records($league_id, $teams, $sTeams[0], $sTeams[1]);
				
				// BOXSCORES		
				$box = $this->lastsim_model->get_playoff_box_scores($league_id, $sTeams[0], $sTeams[1]);
				if (isset($box) && is_array($box) && count($box)) {
					$data = array();
					$data['boxscores'] = $box ;
					$data['gamecast_links'] = in_array('gamecast',module_list(true));
					$data['settings'] = $settings;
					$data['teams'] = $teams;
					Template::set('boxscores',$this->load->view('lastsim/loop_boxscores',$data,true));
					unset($data);
				}
				
				// UNPLAYED GAMES	
				$upcoming = $this->lastsim_model->get_upcoming_playoff_games($league_id, $sTeams[0], $sTeams[1]);
				if (isset($upcoming) && is_array($upcoming) && count($upcoming)) {$data = array();
					$data = array();
					$data['settings'] = $settings;
					$data['teams'] = $teams;
					$data['team_scores'] = $this->lastsim_model->get_situational_scoring($sTeams[0],$league_id);
					$data['team_scores'] = $this->lastsim_model->get_situational_scoring($sTeams[1],$league_id, $data['team_scores']);
					$data['upcoming'] = $upcoming;
					Template::set('upcoming',$this->load->view('lastsim/loop_upcoming',$data,true));
					unset($data);
				}

                $batting = array();
                $pitching = array();
                $top_perf = array();
				if (count($gidList) > 0) {

                    if (in_array('players',module_list(true)))
                    {
                        modules::run('players/player_link_init');
                        $this->load->helper('players/players');
                    }

                    // BATTING AND PITCHING STATS
                    $this->load->library('open_sports_toolkit/stats');
                    Stats::init($settings['osp.game_sport'],$settings['osp.game_source']);
                    $stats_list = Stats::get_stats_list();

                    // TOP PERFORMERS
					$top_bat_class = stats_class(TYPE_OFFENSE, CLASS_BASIC, array('NAME','TID','TOP_PLAYERS'));
                    $top_batters = Stats::get_stats(ID_TEAM, false, TYPE_OFFENSE,$top_bat_class,STATS_GAME,RANGE_GAME_ID_LIST,array('where'=>array('league_id'=>$league_id),'split'=>SPLIT_NONE,'id_list'=>$gidList, 'limit'=>5, 'order_by'=>array('TRO'), 'order_dir'=> 'desc', 'select_data'=>array('GAME_COUNT'=>$pcnt)));
                    $top_perf['batters'] = $this->load->view('lastsim/top_performers',array('performers'=>$top_batters,'stats_class'=>$top_bat_class,'stats_list'=>$stats_list,'player_type'=>TYPE_OFFENSE, 'teams'=>$teams), true);

                    $top_pitch_class = stats_class(TYPE_SPECIALTY, CLASS_BASIC, array('NAME','TID','TOP_PLAYERS'));
                    $top_pitchers = Stats::get_stats(ID_TEAM, false, TYPE_SPECIALTY,$top_pitch_class,STATS_GAME,RANGE_GAME_ID_LIST,array('where'=>array('league_id'=>$league_id),'split'=>SPLIT_NONE,'id_list'=>$gidList, 'limit'=>5, 'order_by'=>array('TRP'), 'order_dir'=> 'desc', 'select_data'=>array('GAME_COUNT'=>$pcnt)));
                    $top_perf['pitchers'] = $this->load->view('lastsim/top_performers',array('performers'=>$top_pitchers,'stats_class'=>$top_pitch_class,'stats_list'=>$stats_list,'player_type'=>TYPE_SPECIALTY, 'teams'=>$teams), true);

                    $stat_classes = array (
                        'Batting'=>stats_class(TYPE_OFFENSE, CLASS_COMPLETE, array('NAME')),
                        'Batting_totals'=>stats_class(TYPE_OFFENSE, CLASS_COMPLETE, array('NAME')),
                        'Pitching'=>stats_class(TYPE_SPECIALTY,CLASS_COMPLETE, array('NAME')),
                        'Pitching_totals'=>stats_class(TYPE_SPECIALTY,CLASS_COMPLETE, array('NAME'))
                    );
                    $stats= array (
                        'home' => array(
							'Batting'=>Stats::get_stats(ID_TEAM, $sTeams[0],TYPE_MIXED_OFFENSE,$stat_classes['Batting'],STATS_GAME,RANGE_GAME_ID_LIST,array('split'=>SPLIT_NONE,'id_list'=>$gidList)),
                            'Pitching'=>Stats::get_stats(ID_TEAM, $sTeams[0],TYPE_SPECIALTY,$stat_classes['Pitching'],STATS_GAME,RANGE_GAME_ID_LIST,array('split'=>SPLIT_NONE,'id_list'=>$gidList))
                        ),
                        'away' => array (
                           'Batting'=>Stats::get_stats(ID_TEAM, $sTeams[1],TYPE_MIXED_OFFENSE,$stat_classes['Batting'],STATS_GAME,RANGE_GAME_ID_LIST,array('split'=>SPLIT_NONE,'id_list'=>$gidList)),
                           'Pitching'=>Stats::get_stats(ID_TEAM, $sTeams[1],TYPE_SPECIALTY,$stat_classes['Pitching'],STATS_GAME,RANGE_GAME_ID_LIST,array('split'=>SPLIT_NONE,'id_list'=>$gidList))
                        )
                    );
                    // RENDER STATS TO VIEW CODE
                    $home_name = $teams[$sTeams[0]]['name']." ".$teams[$sTeams[0]]['nickname'];
                    $away_name = $teams[$sTeams[1]]['name']." ".$teams[$sTeams[1]]['nickname'];

                    $batting = array(
                        'home' => $this->load->view('open_sports_toolkit/stats_table',array('teamname'=>$home_name,'player_type'=>TYPE_OFFENSE,'stats_class'=>$stat_classes['Batting'],'stats_list'=>$stats_list,'records'=>$stats['home']['Batting']), true),
                        'away' => $this->load->view('open_sports_toolkit/stats_table',array('teamname'=>$away_name,'player_type'=>TYPE_OFFENSE,'stats_class'=>$stat_classes['Batting'],'stats_list'=>$stats_list,'records'=>$stats['away']['Batting']), true)
                    );
                    $pitching = array(
                        'home' => $this->load->view('open_sports_toolkit/stats_table',array('teamname'=>$home_name,'player_type'=>TYPE_SPECIALTY,'stats_class'=>$stat_classes['Pitching'],'stats_list'=>$stats_list,'records'=>$stats['home']['Pitching'], 'totals'=>$stats['home']['Pitching_totals']), true),
                        'away' => $this->load->view('open_sports_toolkit/stats_table',array('teamname'=>$away_name,'player_type'=>TYPE_SPECIALTY,'stats_class'=>$stat_classes['Pitching'],'stats_list'=>$stats_list,'records'=>$stats['away']['Pitching']), true)
                    );
                }

				Template::set('batting',$batting);
                Template::set('pitching',$pitching);
                Template::set('top_perf',$top_perf);
                Template::set('teams',$teams);
                Template::set('rounds',$rounds);
                Template::set('subleagues',$subleagues);
                Template::set('series',$series);
                Template::set('playoffConfig',$playofff_struct);
                Template::set('settings',$settings);
                Template::set('league_id',$league_id);
                Template::set('serID',$series_id);
                Template::set('home_team_id',$sTeams[0]);
                Template::set('away_team_id',$sTeams[1]);
                Template::set('pcnt',$pcnt);
                Template::set('rnd',$round);
                Template::set('game_count',sizeof($games));

				Template::set('scripts',$this->load->view('lastsim/boxscores_js',null,true));
				
                Assets::add_module_css('playoffs','playoffs.css');
                Assets::add_module_css('playoffs','series.css');
                Assets::add_module_css('lastsim','box_styles.css');

            }
        }   else {
            Template::set('outMess',"<b>Error:</b> No series Id was provided or the series was not found.");
        }

        Template::render();
	}
	
}

// End main module class