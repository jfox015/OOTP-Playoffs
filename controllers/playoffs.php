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
		
		$season = -1;
		// Extract Parameters
		$league_id = $this->uri->segment(3);
		$season = $this->uri->segment(4);
		
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

        $playoff_data = $this->playoffs_model->generate_playoff_data($teams,$games,$subleagues,$playofff_struct);

        $teams = $playoff_data[0];
        $rounds = $playoff_data[1];
        $series = $playoff_data[2];

        $this->load->helper('open_sports_toolkit/general');

        Template::set('teams',$teams);
        Template::set('rounds',$rounds);
        Template::set('subleagues',$subleagues);
        Template::set('series',$series);
        Template::set('playoffConfig',$playofff_struct);

        Template::render();
	}
	
	public function series() {
		
		Template::render();
	}
	
}

// End main module class