<?php
if (!isset($rounds) || !is_array($rounds) || count($rounds) == 0) :
    echo('<div class="row">No playoff games scheduled</div>' . "\n");
else :
    krsort($rounds);
    $text = "";
    foreach ($rounds as $rnd => $val) :

        ##### Begin Round Display #####
		?>
        <div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<?php
					$fldName = "round_names" . (intval($rnd) - 1);
					echo('<h1>'.$playoffConfig[$fldName].'</h1><br />');
					?>
				
				</div>
			</div>
			
			<?php
			##### Check for Max Round #####
			$fldName = 'max_round';

        ## In World Series
        if ($playoffConfig[$fldName] == $rnd) : ?>
            <div class="row-fluid">
				<div class="span12">
			<?php
            foreach ($series as $serID => $val) :
                if ($series[$serID]['rnd'] != $rnd) :
					continue;
				endif;
                ##### Get Teams #####
                $e = explode(":", $serID);
                $aid = $e[0];
                $hid = $e[1];
                $hW = $teams[$hid]['w'];
                $hPos = $teams[$hid]['pos'];
                $aW = $teams[$aid]['w'];
                $aPos = $teams[$aid]['pos'];

                if ($aPos == $hPos) {
                    if ($aW > $hW) {
                        $who = 'a';
                    }
                    else {
                        $who = 'h';
                    }
                }
                elseif ($aPos > $hPos) {
                    $who = 'a';
                }
                else {
                    $who = 'h';
                }
                if ($who == 'a') {
                    $tmp = $hid;
                    $hid = $aid;
                    $aid = $tmp;
                }

                ##### Show Each Series #####
				?>
                <table cellpadding="2" cellspacing="0" style="width:98%;margin:10px;">
				<tr>
					<td style="padding:1px;width:30%;text-align:center;"><img src="<?php echo($settings['osp.team_logo_url'].$teams[$aid]['logo_file']); ?>" width="150" height="150" /><br />
					<span style="font-size:large; font-weight:bold;"><?php echo($teams[$aid]['name']); ?></span><br /> 
					<?php echo(ordinal_suffix($teams[$aid]['pos'])); ?> place, <?php echo($teams[$aid]['w']."-".$teams[$aid]['l']); ?><br />
					<a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$aid.'.html'); ?>" class="teamLinks">Team Home</a><br />
					<a href="<?php echo($settings['osp.asset_url'].'history/team_year_'.$aid.'_'.$year.'.html'); ?>" class="teamLinks">Roster</a><br />
					<a href="<?php echo($settings['osp.asset_url'].'history/team_'.$aid.'_index.html'); ?>" class="teamLinks">History</a></td>

					<td style="padding:8px;width:40%;text-align:left;">
						<h2>
						<?php
						$homeScore = $series[$serID][$hid]['w'];
						$awayScore = $series[$serID][$aid]['w'];
						$word = " defeat ";
						if ($homeScore == 0 || $awayScore == 0) $word = " sweep ";
						if ($homeScore > $awayScore) {
							echo($teams[$hid]['nickname'].$word.$teams[$aid]['nickname']." ".$homeScore."-".$awayScore);
						} else {
							echo($teams[$aid]['nickname'].$word.$teams[$hid]['nickname']." ".$awayScore."-".$homeScore);
						}
						?>
						</h2>
						<p />
						<h3 class='subhead2'>World Series MVP</h3>
						<div class='subhead_rule'></div>
						<p /><br />
					<div class="spotlight">
					<img src="<?php echo($settings['players_img_path']); ?>player_.png" />
					<span class='spotlight_player'></span><br />
					</div>
					<br class="clear" />
					<table width="100%" border="0" cellspacing="0" cellpadding="5">
					<tr align="center" valign="top">
						<td colspan="2">
						<table border="0" cellspacing="2" cellpadding="3">
						<tr bgcolor="#606060">
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>
								<td class='smallBoldCellWhite'>&nbsp;&nbsp;</td>		
							</tr>
							<tr bgcolor="#EFEEE4" align="right">
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
								<td class='smallCell'></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<p style="text-align:right;margin:0 8px 8px 0;">
					<?php echo(anchor('/playoffs/series/'.$league_id.'/'.$serID.'/'.$rnd,'Complete Series Details')); ?>
					</p>
					</td>

					<td style='"padding:1px;width:30%;text-align:center;">
						<img src="<?php echo($settings['osp.team_logo_url'] . $teams[$hid]['logo_file']); ?>" width="150" height="150" /><br/>
						<span style="font-size:large; font-weight:bold;"><?php echo($teams[$hid]['name']); ?></span><br />
						<?php echo(ordinal_suffix($teams[$hid]['pos'])); ?> place, <?php echo($teams[$hid]['w']."-".$teams[$hid]['l']); ?><br />
						<a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$hid.'.html'); ?>" class="teamLinks">Team Home</a><br />
						<a href="<?php echo($settings['osp.asset_url'].'history/team_year_'.$hid.'_'.$year.'.html'); ?>" class="teamLinks">Roster</a><br />
						<a href="<?php echo($settings['osp.asset_url'].'history/team_'.$hid.'_index.html'); ?>" class="teamLinks">History</a>
						</td>
					</td>
				</tr>
				</table>
            <?php
			endforeach;
			?>
				</div>
			</div>
        <?php
        ## Sub-league playoff round
        else :
        
            $slCnt = 0;
			?>
			
            <div class="row-fluid">
			<?php
			
            foreach ($subleagues as $slid => $val) :
                ## Structure Adjustment

                ##### Begin League Display #####
				?>
                <div class="span6">
                <h3><?php echo($subleagues[$slid]['name']); ?></h3>
				<?php
                foreach ($series as $serID => $val) :
                    if ($series[$serID]['rnd'] != $rnd) {
                        continue;
                    }
                    if ($series[$serID]['slid'] != $slid) {
                        continue;
                    }
                    ##### Get Teams #####
                    $e = explode("_", $serID);
                    $aid = $e[0];
                    $hid = $e[1];

                    $hW = $teams[$hid]['w'];
                    $hPos = $teams[$hid]['pos'];
                    $aW = $teams[$aid]['w'];
                    $aPos = $teams[$aid]['pos'];

                    if ($aPos == $hPos) {
                        if ($aW > $hW) {
                            $who = 'a';
                        }
                        else {
                            $who = 'h';
                        }
                    } elseif ($aPos > $hPos) {
                        $who = 'a';
                    } else {
                        $who = 'h';
                    }
                    if ($who == 'a') {
                        $tmp = $hid;
                        $hid = $aid;
                        $aid = $tmp;
                    }

                    ##### Show Each Series #####
					?>
                    <div class="boxscores">
                    <table cellpadding="2" cellspacing="0" style="border:1px black solid;margin:10px;">
                    <tr class="headline_other">
						<td class="teamsTitles" colspan="4">
                        <?php echo($teams[$aid]['nickname'] . " vs. " . $teams[$hid]['nickname']); ?>
						</td>
                    </tr>
                    <tr>
						<td style="padding:1px;width:44px;border-right:1px solid #999999;"><img src="<?php echo($settings['osp.team_logo_url'] . $teams[$aid]['logo_file']); ?>" width="40" height="40"></td>
						<td class="sl" width="175"><a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$aid.'.html'); ?>"><?php echo($teams[$aid]['name']); ?></a></td>
						<td class="sl" width="175" style="text-align:right;padding-right:5px;"><?php echo(ordinal_suffix($teams[$aid]['pos']) . " place, " . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] ); ?></td>
						<td class="icgb" width="50"><?php echo($series[$serID][$aid]['w']); ?></td>
                    </tr>
                    <tr>
						<td style="padding:1px;width:44px;border-right:1px solid #999999;"><img src="<?php echo($settings['osp.team_logo_url'] . $teams[$hid]['logo_file']); ?>" width="40" height="40"></td>
						<td class="sl" width="175" style="border-top-width:1px; border-top-style:solid; border-top-color:#999999;"><a href="<?php echo($settings['osp.asset_url'].'teams/team_'.$hid.'.html'); ?>"><?php echo($teams[$hid]['name']); ?></a></td>
						<td class="sl" width="175" style="text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;"><?php echo(ordinal_suffix($teams[$hid]['pos']) . " place, " . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] ); ?></td>
						<td class="icgb" width="50" style="border-top-width:1px; border-top-style:solid; border-top-color:#999999;"><?php echo($series[$serID][$hid]['w']); ?></td>
                    </tr>
                    <tr>
						<td class="sl" colspan="4" style="text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;">
						<?php echo anchor('/playoffs/series/'.$league_id.'/'.$serID.'/'.$rnd,'Series Detail'); ?>
                    </tr>
                    </table>
                    </div>
					<br />
				<?php
                endforeach;
                ##### End League Display
				?>
                </div>
				<?php
                $slCnt++;
            endforeach; // END foreach
			?>
            </div>
			<?php
        endif;
		?>
        </div>
	<?php
    endforeach;
endif; // END if (!isset($rounds))
?>