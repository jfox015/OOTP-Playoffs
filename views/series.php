<?php
if (!isset($series) || !is_array($series) || count($series) == 0) {
    if (isset($outMess) && !empty($outMess)) {
        echo($outMess);
    }
}  else {

    $text = '';
    $gidList = trim($gidList, ",");
    $fldName = "round_names" . (intval($rnd) - 1);
    $homeScore = $series[$serID][$home_team_id]['w'];
    $awayScore = $series[$serID][$away_team_id]['w'];
    $word = " vs ";
    if ($pcnt == sizeof($games)) {
        $words = " defeat ";
        if ($homeScore == 0 || $awayScore == 0) $word = " sweep ";
    }
    ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span7">
            <h1 style="display:inline;"><?php echo($playoffConfig[$fldName]); ?> Stats</h1>
            <br />
                <!-- DISPLAY MATCHUP INFORMATION -->
            <h2>
            <?php
            if ($homeScore > $awayScore) {
                echo($teams[$home_team_id]['nickname'] . $word . $teams[$away_team_id]['nickname'] . " " . $homeScore . "-" . $awayScore);
            } else {
                echo($teams[$away_team_id]['nickname'] . $word . $teams[$home_team_id]['nickname'] . " " . $awayScore . "-" . $homeScore);
            }
            ?>
            </h2>
            </div>

            <div class="span5 stats_links" style="text-align:right;">
            <br /><br />
            <?php echo anchor('/playoffs/summary/'.$league_id, 'Back to Summary page', array('class'=>'menu')).'<br />';
            echo ('<a class="menu" href="'.$settings['ootp.asset_url'].'league_'.$league_id.'_index.html">Post Season Home</a>');
            ?>
            </div>
        </div>

        <br clear='all' /><br />

        <div class="row-fluid">
            <div class="span12">
                <div class="matchup">
                    <div class="matchbox" style="background:<?php echo($teams[$away_team_id]['background_color_id']); ?>;">
                        <div class="matchlogo logoleft" style="background: url(<?php echo($settings['ootp.team_logo_path'].$teams[$away_team_id]['logo']); ?>) center left no-repeat;"></div>
                        <div class="matchscore" style="float:right;color:<?php echo($teams[$away_team_id]['text_color_id']); ?>;"><?php echo($series[$serID][$away_team_id]['w']);?></div>
                        <div class="matchteam" style="float:right;text-align:right;"><a href="<?php echo($settings['ootp.asset_url'].'teams/team_'.$away_team_id);?>.html" style="color:<?php echo($teams[$away_team_id]['text_color_id']); ?>;"><?php echo($teams[$away_team_id]['name']); ?></a></div>
                    </div>
                    <div style="float:left; padding-top:32px;height:25px;width:55px;text-align:center" class="icgb">vs.</div>
                    <div class="matchbox" style="background: <?php echo($teams[$home_team_id]['background_color_id']);?>;">
                        <div class="matchlogo logoright" style="background:url(<?php echo($settings['ootp.team_logo_path'].$teams[$home_team_id]['logo']); ?>) center right no-repeat; "></div>
                        <div class="matchscore" style="float:left;color:<?php echo($teams[$home_team_id]['text_color_id']); ?>;"><?php echo($series[$serID][$home_team_id]['w']);?></div>
                        <div class="matchteam" style="float:left;text-align:left;"><a href="<?php echo($settings['ootp.asset_url'].'teams/team_'.$home_team_id);?>.html" style="color:<?php echo($teams[$home_team_id]['text_color_id']); ?>;"><?php echo($teams[$home_team_id]['name']); ?></a></div>
                    </div>
                <br clear='all' />
                    <div class="matchrecord" style="background-color:<?php echo($teams[$away_team_id]['text_color_id']); ?>;color:<?php echo($teams[$away_team_id]['background_color_id']); ?>;"><?php echo(ordinal_suffix($teams[$away_team_id]['pos']));?> place, <?php echo($teams[$away_team_id]['w']. " - ".$teams[$away_team_id]['l']); ?></div>
                    <div style="float:left; width:55px; text-align:center">&nbsp;</div>
                    <div class="matchrecord" style="background-color:<?php echo($teams[$home_team_id]['text_color_id']); ?>;color:<?php echo($teams[$home_team_id]['background_color_id']); ?>;text-align:right;"><?php echo(ordinal_suffix($teams[$home_team_id]['pos']));?> place, <?php echo($teams[$home_team_id]['w']. " - ".$teams[$home_team_id]['l']); ?></div>
                    <br clear="all" />
                </div>
            </div>
        </div>
            <!-- BOXSCORES AND UNPLAYED GAMES -->
        <div class="row-fluid">

            <div class="span7">
            <h3>Box Scores</h3>
            <?php if (isset($boxscores)) {
                echo($boxscores);
            }
            ?>

            <h3>Upcoming Schedule</h3>
            <?php if (isset($upcoming)) {
                echo($upcoming);
            }
            ?>
            </div>

            <div class="span5">
            <h3>Top Performers</h3>

            <?php
            if (isset($top_perf['batters'])) {
                echo($top_perf['batters']);
            }

            if (isset($top_perf['pitchers'])) {
                echo($top_perf['pitchers']);
            }
            ?>
            </div>
        </div>

            <!-- TEAM STATS -->
        <div class="row-fluid">

            <div class="span12">

                <!-- TEAM BATTING -->
                <?php
                if (isset($batting['away'])) {
                    echo($batting['away']);
                }
                if (isset($batting['home'])) {
                    echo($batting['home']);
                }
                ?>
                <!-- TEAM PITCHING -->
                <?php
                if (isset($pitching['away'])) {
                    echo($pitching['away']);
                }
                if (isset($pitching['home'])) {
                    echo($pitching['home']);
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>