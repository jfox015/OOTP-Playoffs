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
		Template::render();
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
		$teams = $this->playoffs_model->get_team_information($league_id);
        $games = $this->playoffs_model->get_playoff_games($league_id);

        $playoff_data = $this->playoffs_model->generate_summary_data($teams,$games,$subleagues,$playofff_struct);

		if (is_array($playoff_data) && count($playoff_data)) {
			$teams = $playoff_data[0];
			$rounds = $playoff_data[1];
			$series = $playoff_data[2];

			$logo_path = $settings['ootp.asset_url'].'images/';
            if (intval($settings['ootp.game_version']) >= 13) {
                $logo_path .= 'team_logos/';
            }
            Template::set('teams',$teams);

			Template::set('rounds',$rounds);
			Template::set('subleagues',$subleagues);
			Template::set('series',$series);
			Template::set('playoffConfig',$playofff_struct);
			Template::set('logo_path',$logo_path);
			Template::set('settings',$settings);
			Template::set('league_id',$league_id);

            $this->load->helper('open_sports_toolkit/general');

            Assets::add_module_css('playoffs','playoffs.css');

        }

        Template::render();
	}
	
	public function series() {

        $settings = $this->settings_lib->find_all();

        // Extract Parameters
        $league_id = $this->uri->segment(3);
        $league_id = (isset($league_id) && !empty($league_id)) ? $league_id : 100;
        $series_id = $this->uri->segment(5);
        $series_id = (isset($series_id) && !empty($series_id)) ? $series_id : -1;
        $round = $this->uri->segment(5);
        $round = (isset($round) && !empty($round)) ? $round : 1;

        // Unless a specific season is passed, assume current or last post-season data
        if ($series_id != -1) {
            // Get Structure
            $subleagues = $this->leagues_model->get_subleague_info($league_id);
            $playofff_struct = $this->playoffs_model->load_playoff_structure($league_id);
            $teams = $this->playoffs_model->get_team_information($league_id);
            $games = $this->playoffs_model->get_playoff_games($league_id);

            $playoff_data = $this->playoffs_model->generate_series_data($teams,$games,$subleagues,$playofff_struct);
        }   else {
            Template::set('outMess',"<b>Error:</b> No series Id was provided or the series was not found.");
        }
        Template::render();
	}
	
}

// End main module class