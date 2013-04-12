<?php
if (!isset($series) || !is_array($series) || count($series) == 0) :
    if (isset($outMess) && !empty($outMess)) :
        echo($outMess);
    endif;
else :

    $fldName = "round_names" . (intval($rnd) - 1);
    $homeScore = $series[$serID][$home_team_id]['w'];
    $awayScore = $series[$serID][$away_team_id]['w'];
    $word = lang('poff_match_vs');
    if ($pcnt == $game_count) :
        $word = lang('poff_match_defeats');
        if ($homeScore == 0 || $awayScore == 0) $word = lang('poff_match_sweeps');
    endif;
    ?>
	<?php if(isset($popup_template)) { echo($popup_template); } ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span7">
            <h1 style="display:inline;"><?php echo($playoffConfig[$fldName].' '.lang('poff_title_stats')); ?></h1>
            </div>

            <div class="span5 stats_links" style="text-align:right;">
            <br /><br />
                <!-- DISPLAY MATCHUP INFORMATION -->
            <b>
            <?php
            if ($homeScore > $awayScore) {
                echo($teams[$home_team_id]['nickname'] .' '. $word .' '. $teams[$away_team_id]['nickname'] . " " . $homeScore . "-" . $awayScore);
            } else {
                echo($teams[$away_team_id]['nickname'] .' '. $word .' '. $teams[$home_team_id]['nickname'] . " " . $awayScore . "-" . $homeScore);
            }
            ?>
            </b>
			<br />
            <?php echo anchor('/playoffs/summary/'.$league_id, lang('poff_link_summary'), array('class'=>'menu')).'<br />';
            echo ('<a class="menu" href="'.$settings['osp.asset_url'].'league_'.$league_id.'_index.html">'.lang('poff_link_post_home').'</a>');
            ?>
            </div>
        </div>

        <br clear='all' /><br />
        <?php
		// COLOR ADJUSTMENT. 
		// PREVENT META BAR FROM BEING WHITE IF TEAM TEXT COLOR IS WHITE. IF SO, FLIP THE TEXT AND BG COLORS
        if ($teams[$away_team_id]['text_color_id'] == "#FFFFFF")     {
            $away_bkgd = $teams[$away_team_id]['text_color_id'];
            $away_text = $teams[$away_team_id]['background_color_id'];
        } else {
            $away_bkgd = $teams[$away_team_id]['background_color_id'];
            $away_text = $teams[$away_team_id]['text_color_id'];
        }
        if ($teams[$home_team_id]['text_color_id'] == "#FFFFFF")     {
            $home_bkgd = $teams[$home_team_id]['text_color_id'];
            $home_text = $teams[$home_team_id]['background_color_id'];
        } else {
            $home_bkgd = $teams[$home_team_id]['background_color_id'];
            $home_text = $teams[$home_team_id]['text_color_id'];
        }
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="matchup">
                    <div class="matchbox" style="background:<?php echo($away_bkgd); ?>;">
                        <div class="matchlogo logoleft" style="background: url(<?php echo($settings['osp.team_logo_url'].$teams[$away_team_id]['logo_file']); ?>) center left no-repeat;"></div>
                        <div class="matchscore" style="float:right;color:<?php echo($away_text); ?>;"><?php echo($series[$serID][$away_team_id]['w']);?></div>
                        <div class="matchteam" style="float:right;text-align:right;"><a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$away_team_id);?>.html" style="color:<?php echo($away_text); ?>;"><?php echo($teams[$away_team_id]['name']); ?></a></div>
                    </div>
                    <div style="float:left; padding-top:32px;height:25px;width:55px;text-align:center" class="icgb">vs.</div>
                    <div class="matchbox" style="background: <?php echo($home_bkgd);?>;">
                        <div class="matchlogo logoright" style="background:url(<?php echo($settings['osp.team_logo_url'].$teams[$home_team_id]['logo_file']); ?>) center right no-repeat; "></div>
                        <div class="matchscore" style="float:left;color:<?php echo($home_text); ?>;"><?php echo($series[$serID][$home_team_id]['w']);?></div>
                        <div class="matchteam" style="float:left;text-align:left;"><a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$home_team_id);?>.html" style="color:<?php echo($home_text); ?>;"><?php echo($teams[$home_team_id]['name']); ?></a></div>
                    </div>
                <br clear='all' />
                    <div class="matchrecord" style="background-color:<?php echo($away_text); ?>;color:<?php echo($away_bkgd); ?>;"><?php echo(ordinal_suffix($teams[$away_team_id]['pos']).' '.lang('poff_meta_place'));?>, <?php echo($teams[$away_team_id]['w']. " - ".$teams[$away_team_id]['l']); ?></div>
                    <div style="float:left; width:55px; text-align:center">&nbsp;</div>
                    <div class="matchrecord" style="background-color:<?php echo($home_text); ?>;color:<?php echo($home_bkgd); ?>;text-align:right;"><?php echo(ordinal_suffix($teams[$home_team_id]['pos']).' '.lang('poff_meta_place'));?>, <?php echo($teams[$home_team_id]['w']. " - ".$teams[$home_team_id]['l']); ?></div>
                    <br clear="all" />
                </div>
            </div>
        </div>
		
		<br clear='all' /><br />
		
            <!-- BOXSCORES AND UNPLAYED GAMES -->
        <div class="row-fluid">

            <div class="span6">
            
            <?php if (isset($boxscores) && !empty($boxscores)) {
                echo('<h3>'.lang('poff_title_boxscores').'</h3>');
                echo($boxscores);
            }
            ?>

            <?php if (isset($upcoming) && !empty($upcoming)) {
                echo('<h3>'.lang('poff_title_upcoming').'</h3>');
                echo($upcoming);
            }
            ?>
            </div>

            <div class="span6">
			<?php if(isset($top_perf) && is_array($top_perf) && count($top_perf)) : ?>
            <h3><?php echo lang('poff_title_performers'); ?></h3>
			<br />
            <?php
				if (isset($top_perf['batters'])) :
					echo($top_perf['batters']);
				endif;

				if (isset($top_perf['pitchers'])) :
					echo($top_perf['pitchers']);
				endif;
			endif;
            ?>
            </div>
        </div>

        <br clear='all' /><br />

            <!-- TEAM STATS -->
        <div class="row-fluid">

            <div class="span12">

                <!-- TEAM BATTING -->
                <?php
                if (isset($batting['away'])) :
                    echo($batting['away']);
                endif;
                if (isset($batting['home'])) :
                    echo($batting['home']);
                endif;
				if (isset($batting['totals'])) :
                    echo($batting['totals']);
                endif;
                ?>
                <!-- TEAM PITCHING -->
                <?php
                if (isset($pitching['away'])) :
                    echo($pitching['away']);
                endif;
                if (isset($pitching['home'])) :
                    echo($pitching['home']);
                endif;
                ?>
            </div>
        </div>
    </div>
<?php
endif;
?>