<?php
$gidList = trim($gidList, ",");
$fldName = "round_names" . (intval($rnd) - 1);
$text .= "    <div style='position:relative; width:100%;'>\n";
$text .= "    <div style='float:left; width:65%;'>\n";
$text .= "    <h1 style='display:inline;'>" . $playoffConfig[$fldName] . " Stats</h1>\n";
$text .= "    <br />\n";
$text .= "    <h2>";
$homeScore = $series[$serID][$hid]['w'];
$awayScore = $series[$serID][$aid]['w'];
$word = " defeat ";
if ($homeScore == 0 || $awayScore == 0) $word = " sweep ";
if ($homeScore > $awayScore) {
    $text .= $teams[$hid]['nickname'] . $word . $teams[$aid]['nickname'] . " " . $homeScore . "-" . $awayScore;
} else {
    $text .= $teams[$aid]['nickname'] . $word . $teams[$hid]['nickname'] . " " . $awayScore . "-" . $homeScore;
}
##### Begin Round Display #####

$text .= "</h2>\n";
$text .= "         </div>\n";/*
$text .= "         <div class='stats_links' style='float:left; width:30%; text-align:right;'>\n";
$text .= "         <br /><br />\n";
$text .= "         <a class='menu' href='index.html'>Back to Summary page</a><br />\n";
$text .= "         <a class='menu' href='../league_".$league_id."_index.html'>Post Season Home</a>\n";
$text .= "         </div>\n";
$text .= "    </div>\n";
$text .= "    <br clear='all' /><br />\n";
$text .= "    <div class='matchup'>\n";
$text .= "    	<div class='matchbox' style='background: " . $teams[$aid]['background_color_id'] . ";'>\n";
$text .= "    	<div class='matchlogo logoleft' style='background: url(../../images/" . $teams[$aid]['logo'] . ") center left no-repeat;'></div>\n";
$text .= "    	<div class='matchscore' style='float:right;color:" . $teams[$aid]['text_color_id'] . ";'>" . $series[$serID][$aid]['w'] . "</div>\n";
$text .= "    	<div class='matchteam' style='float:right;text-align:right;'><a href='../../teams/team_$aid.html' style='color:" . $teams[$aid]['text_color_id'] . ";'>" . $teams[$aid]['name'] . "</a></div>\n";
$text .= "    	</div>\n";
$text .= "    	<div style='float:left; padding-top:32px;height:25px;width:55px;text-align:center' class='icgb'>vs.</div>\n";
$text .= "    	<div class='matchbox' style='background: " . $teams[$hid]['background_color_id'] . ";'>\n";
$text .= "    	<div class='matchlogo logoright' style='background:url(../../images/" . $teams[$hid]['logo'] . ") center right no-repeat; '></div>\n";
$text .= "    	<div class='matchscore' style='float:left;color:" . $teams[$hid]['text_color_id'] . ";'>" . $series[$serID][$hid]['w'] . "</div>\n";
$text .= "    	<div class='matchteam' style='float:left;text-align:left;'><a href='../../teams/team_$hid.html' style='color:" . $teams[$hid]['text_color_id'] . ";'>" . $teams[$hid]['name'] . "</a></div>\n";
$text .= "    	</div>\n";
$text .= "    	<br clear='all' />\n";
$text .= "    	<div class='matchrecord' style='background-color:" . $teams[$aid]['text_color_id'] . ";color:" . $teams[$aid]['background_color_id'] . ";'>" . ordinal_suffix($teams[$aid]['pos']) . " place, " . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] . "</div>\n";
$text .= "    	<div style='float:left; width:55px; text-align:center'>&nbsp;</div>\n";
$text .= "    	<div class='matchrecord' style='background-color:" . $teams[$hid]['text_color_id'] . ";color:" . $teams[$hid]['background_color_id'] . "; text-align:right;'>" . ordinal_suffix($teams[$hid]['pos']) . " place, " . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] . "</div>\n";
$text .= "    	<br clear='all' />\n";
$text .= "    </div>\n";

if ($pcnt > 0) {
    ##### Get Games w/ Great Ind Performance #####
    $queryB = "SELECT game_id,pgb.player_id,first_name,last_name,h,d,t,hr,rbi,sb FROM players_game_batting as pgb,players as p WHERE pgb.player_id=p.player_id AND pgb.league_id=" . $lgid . " AND (hr>2 OR h>5 OR rbi>7 or sb>3 OR ((h-d-t-hr)>0 AND d>0 AND t>0 AND hr>0)) AND game_id IN ($gidList);";
    $resultB = mysql_query($queryB, $db);
    while ($row = mysql_fetch_array($resultB)) {
        $gid = $row['game_id'];
        $pid = $row['player_id'];
        $fi = $row['first_name'];
        $fi = $fi[0];
        $name = $fi . ". " . $row['last_name'];
        $h = $row['h'];
        $d = $row['d'];
        $t = $row['t'];
        $hr = $row['hr'];
        $s = $h - $d - $t - $hr;
        $rbi = $row['rbi'];
        $sb = $row['sb'];

        if ($hr > 2) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> belts $hr HR's";
        }
        if ($h > 5) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> knocks $h hits";
        }
        if ($rbi > 7) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> plates $rbi";
        }
        if ($sb > 3) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> steals $sb bases";
        }
        if (($s > 0) && ($d > 0) && ($t > 0) && ($hr > 0)) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> hits for the cycle";
        }
    }

    $queryP = "SELECT game_id,pgp.player_id,first_name,last_name,k,(ip*3+ipf)/3 as ip,ha,cg,sho FROM players_game_pitching_stats as pgp,players as p WHERE pgp.player_id=p.player_id AND pgp.league_id=" . $lgid . " AND (k>14 OR ((ip*3+ipf)/3)>9 OR (ha=0 AND ((ip*3+ipf)/3)>7) OR (ha<3 AND cg=1 AND sho=1)) AND game_id IN ($gidList);";
    $resultP = mysql_query($queryP, $db);
    while ($row = mysql_fetch_array($resultP)) {
        $gid = $row['game_id'];
        $pid = $row['player_id'];
        $fi = $row['first_name'];
        $fi = $fi[0];
        $name = $fi . ". " . $row['last_name'];
        $k = $row['k'];
        $ip = $row['ip'];
        $ha = $row['ha'];
        $cg = $row['cg'];
        $sho = $row['sho'];
        if (floor($ip) == $ip) {
            $dispIP = round($ip, 0);
        } else {
            $dispIP = round(floor($ip), 0) . " " . round((3 * ($ip - floor($ip))), 0) . "/3";
        }

        if ($k > 14) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> strikes out $k";
        }
        if ($ip > 9) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> goes " . $dispIP . " innings";
        }
        if (($ha == 0) && ($ip > 7)) {
            if ($cg == 1) {
                $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> pitches a no-hitter";
            }
            else {
                $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> fails to allow a hit";
            }
        }
        if (($ha < 3) && ($cg == 1) && ($sho == 1) && ($ha != 0)) {
            $games[$gid]['note'] = $games[$gid]['note'] . ", <a href='../../players/player_$pid.html'>$name</a> pitches a $ha-hit shutout";
        }
    }
}

##### Get Team Situational Records #####
$query = "SELECT game_id,home_team,away_team,runs0,runs1 FROM games WHERE game_type=0 AND league_id=$lgid AND played=1 AND (home_team=$hid OR home_team=$aid OR away_team=$hid OR away_team=$aid);";
$result = mysql_query($query, $db);
while ($row = mysql_fetch_array($result)) {
    $hid = $row['home_team'];
    $aid = $row['away_team'];
    if ($row['runs0'] > $row['runs1']) {
        $teams[$aid]['rw'] = $teams[$aid]['rw'] + 1;
        $teams[$hid]['hl'] = $teams[$hid]['hl'] + 1;
    }
    else {
        $teams[$aid]['rl'] = $teams[$aid]['rl'] + 1;
        $teams[$hid]['hw'] = $teams[$hid]['hw'] + 1;
    }
}

$text .= "<div>\n";
##### Display Games #####
$text .= " <div id='first' class='boxscores'><table cellpadding=2 cellspacing=0 border=0 style='border:0;margin:0;'><tr class='h1_l'><td width='420px'>Box Scores</td></tr></table>\n";
foreach ($games as $gid => $val) {
    $played = $games[$gid]['played'];
    if ($played == 1) ## Played Games
    {
        $hid = $games[$gid]['hid'];
        $hname = $teams[$hid]['name'];
        $aid = $games[$gid]['aid'];
        $aname = $teams[$aid]['name'];
        $inns = $games[$gid]['innings'];

        $query2 = "SELECT team,inning,score FROM games_score WHERE game_id=" . $gid . " AND inning>" . ($inns - 12) . " ORDER BY team,inning;";
        $result2 = mysql_query($query2, $db);
        $inntxt = "<td class='hc' width=18>&#160;</td>";
        $ainn = "";
        $hinn = "";
        $inncnt = 0;
        while ($row = mysql_fetch_array($result2)) {
            if ($row['team'] == 0) {
                $inntxt .= "<td class='hc' width=15>" . $row['inning'] . "</td>";
                $ainn .= "<td class='ic' width=15>" . $row['score'] . "</td>";
                $inncnt++;
            }
            else {
                if (($games[$gid]['runs1'] > $games[$gid]['runs0']) && ($row['inning'] > 8) && ($row['inning'] == $inns) && ($row['score'] == 0)) {
                    $row['score'] = "X";
                }
                $hinn .= "<td class='ic' width=15>" . $row['score'] . "</td>";
            }
        }
        for ($i = 0; $i < (12 - $inncnt); $i++) {
            $inntxt .= "<td class='hc' width=15>&#160;</td>";
            $ainn .= "<td class='ic' width=15>&#160;</td>";
            $hinn .= "<td class='ic' width=15>&#160;</td>";
        }
        $inntxt .= "<td class='hc' width=15>R</td><td class='hc' width=15>H</td><td class='hc' width=15>E</td>";
        $ainn .= "<td class='icgb' width=15>" . $games[$gid]['runs0'] . "</td><td class='ic' width=15>" . $games[$gid]['hits0'] . "</td><td class='ic' width=15>" . $games[$gid]['errors0'] . "</td>";
        $hinn .= "<td class='icgb' width=15>" . $games[$gid]['runs1'] . "</td><td class='ic' width=15>" . $games[$gid]['hits1'] . "</td><td class='ic' width=15>" . $games[$gid]['errors1'] . "</td>";

        $wpid = $games[$gid]['winning_pitcher'];
        $lpid = $games[$gid]['losing_pitcher'];
        $spid = $games[$gid]['save_pitcher'];
        $query3 = "SELECT player_id,first_name,last_name FROM players WHERE player_id=$wpid OR player_ID=$lpid OR player_ID=$spid;";
        $result3 = mysql_query($query3, $db);
        while ($row = mysql_fetch_array($result3)) {
            $pid = $row['player_id'];
            $fi = $row['first_name'];
            $fi = $fi[0];
            $pitcher[$pid] = "<a href='../../players/player_$pid.html'>" . $fi . ". " . $row['last_name'] . "</a>";
        }
        $ptxt = "W: " . $pitcher[$wpid] . " L: " . $pitcher[$lpid];
        if ($spid != 0) {
            $ptxt .= " S: " . $pitcher[$spid];
        }


        $query4 = "SELECT p.player_id,p.team_id,pb.hr,p.first_name,p.last_name FROM players as p,players_game_batting as pb WHERE p.player_id=pb.player_id AND game_id=" . $gid . ";";
        $result4 = mysql_query($query4, $db);
        unset($hrtxt);
        $tothr = 0;
        while ($row = mysql_fetch_array($result4)) {
            $hrcnt = $row['hr'];
            $tid = $row['team_id'];
            if ($hrcnt > 0) {
                $fi = $row['first_name'];
                $fi = $fi[0];
                $hrtxt[$tid] .= " <a href='../../players/player_" . $row['player_id'] . ".html'>" . $fi . ". " . $row['last_name'] . "</a>";
                if ($hrcnt > 1) {
                    $hrtxt[$tid] .= " (" . $hrcnt . ")";
                }
                $hrtxt[$tid] .= ",";
                $tothr++;
            }
        }
        if ($hrtxt[$aid] != "") {
            $hrtxt[$aid] = $teams[$aid]['abbr'] . ":" . substr_replace($hrtxt[$aid], "", -1);
        }
        if ($hrtxt[$hid] != "") {
            $hrtxt[$hid] = $teams[$hid]['abbr'] . ":" . substr_replace($hrtxt[$hid], "", -1);
        }
        if ($tothr == 0) {
            $hrtxt = "&nbsp;";
        }
        else {
            $hrtxt = "HR - " . $hrtxt[$aid] . " " . $hrtxt[$hid];
        }
        if (isset($games[$gid]['note'])) {
            $gnote = $games[$gid]['note'];
            $gnote = trim($gnote, ", ");
            $gnote = "<br />Notes: " . $gnote;
        }
        else {
            $gnote = "";
        }

        unset($gDate);
        $gDate = new Date($games[$gid]['date']);
        $text .= " <table cellspacing=0 cellpadding=0 style='border:0px;width:400px;margin:10px;'>\n";
        $text .= "  <tr>\n";
        $text .= "   <td>\n";
        $text .= "    " . $gDate->format("M j, Y");
        $text .= ": <a href='box_scores/game_box_$gid.html'>Box Score</a>";
        $text .= " | <a href='game_logs/log_$gid.html'>Game Log</a>\n";
        $text .= "   </td>\n";
        $text .= "  </tr>\n";
        $text .= "  <tr>\n";
        $text .= "   <td>\n";
        $text .= "    <table cellpadding=0 cellspacing=0 style='border:1px black solid;width:400px;margin-top:2px;margin-left:0px;'>\n";
        $text .= "     <tr>\n";
        $text .= "      <td style='padding:1px;width:44px;border-right:1px solid #999999;'>\n";
        $text .= "       <img src='../../images/" . $teams[$aid]['logo'] . "' width=40 height=40><br>\n";
        $text .= "       <img src='../../images/" . $teams[$hid]['logo'] . "' width=40 height=40>\n";
        $text .= "      </td>\n";
        $text .= "      <td valign='top' style='padding:0px;margin:0px'>\n";
        $text .= "       <table cellspacing=0 cellpadding=1 style='width:356px;margin:0px;border:0px'>\n";
        $text .= "        <tr>$inntxt</tr>\n";
        $text .= "        <tr><td class='gl'><a href='../../teams/team_$aid.html'>" . $teams[$aid]['city'] . "</a></td>";
        $text .= "$ainn</tr>\n";
        $text .= "        <tr><td class='gl'><a href='../../teams/team_$hid.html'>" . $teams[$hid]['city'] . "</a></td>";
        $text .= "$hinn</tr>\n";
        $text .= "        <tr><td colspan=16 class='gl' style='padding:6px 4px 4px 4px;'>" . $ptxt . "<br />" . $hrtxt . $gnote . "</td></tr>\n";
        $text .= "       </table>\n";
        $text .= "      </td>\n";
        $text .= "     </tr>\n";
        $text .= "    </table>\n";
        $text .= "   </td>\n";
        $text .= "  </tr>\n";
        $text .= " </table>\n";
    }
    else ## Unplayed Games
    {
        $hid = $games[$gid]['hid'];
        $hname = $teams[$hid]['name'];
        $aid = $games[$gid]['aid'];
        $aname = $teams[$aid]['name'];

        unset($date);
        $date = new Date($games[$gid]['date'] . " " . $games[$gid]['time']);
        $text .= "<table cellspacing=0 cellpadding=0 style='border:0px;width:400px;margin:10px;'>\n";
        $text .= " <tr>\n";
        $text .= "  <td>\n";
        $text .= "   " . $date->format("M j, Y") . "\n";
        $text .= "  </td>\n";
        $text .= " </tr>\n";
        $text .= " <tr>\n";
        $text .= "  <td>\n";
        $text .= "    <table cellpadding=0 cellspacing=0 style='border:1px black solid;width:400px;margin-top:2px;margin-left:0px;'>\n";
        $text .= "     <tr>\n";
        $text .= "      <td style='padding:1px;width:44px;border-right:1px solid #999999;'>\n";
        $text .= "       <img src='../../images/" . $teams[$aid]['logo'] . "' width=40 height=40><br>\n";
        $text .= "       <img src='../../images/" . $teams[$hid]['logo'] . "' width=40 height=40>\n";
        $text .= "      </td>\n";
        $text .= "        <td valign='top' style='padding:0px;margin:0px'>\n";
        $text .= "       <table cellspacing=0 cellpadding=1 style='width:356px;margin:0px;border:0px'>\n";

        $text .= "        <tr><td class='hl' colspan=2>" . $date->format("g:i a") . "</td></tr>\n";
        $text .= "        <tr><td class='gl' width=175><a href='../../teams/team_$aid.html'>$aname</a></td>\n";
        $text .= "            <td class='gl' width=175>" . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] . ", On Road: " . $teams[$aid]['rw'] . "-" . $teams[$aid]['rl'] . "</td></tr>\n";
        $text .= "        <tr><td class='gl' width=175><a href='../../teams/team_$hid.html'>$hname</a></td>\n";
        $text .= "            <td class='gl' width=175>" . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] . ", At Home: " . $teams[$hid]['hw'] . "-" . $teams[$hid]['hl'] . "</td></tr>\n";
        $text .= "        <tr><td class='gl' colspan=2 style='padding:6px 4px 4px 4px;'>&nbsp;<br />&nbsp;</td></tr>\n";
        $text .= "       </table>\n";
        $text .= "      </td>\n";
        $text .= "     </tr>\n";
        $text .= "    </table>\n";
        $text .= "   </td>\n";
        $text .= "  </tr>\n";
        $text .= " </table>\n";
    }
}
$text .= " </div>\n";

if ($pcnt == 0) {
    $text .= "</div>\n";
    ##### Write Output to File #####
    $fpath = "./cache/league_" . $lgid . "_playoff_series_$seriesID.html";
    $f = fopen($fpath, 'w');
    fwrite($f, $text);
    fclose($f);
    return;
}

##### Get Top Players #####
$text .= "<div class='textbox' style='clear:right;'>\n";
$text .= " <table cellpadding=0 cellspacing=0 border=0 width=460>\n";
$text .= "  <tr class='h1_l'><td>Top Performers</td></tr>\n";
$text .= "  <tr>\n";
$text .= "   <td>\n";


$text .= " <div class='tablebox'>\n";
$text .= " <table cellpadding=0 cellspacing=0 border=0>\n";
$text .= " <tr class='h1_l'><td>Series MVP</td></tr>\n";
$text .= " <tr><td>\n";
$text .= " <table class='sortable' width=440>\n";
$text .= "  <thead><tr class='hsn2'>\n";
$text .= "  <td></td><td></td>\n";
$text .= "  <td>AVG</td><td>R</td><td>H</td><td>HR</td><td>RBI</td><td>SB</td><td>OPS</td>\n";
$text .= "  <td>W</td><td>L</td><td>SV</td><td>IP</td><td>ERA</td><td>K</td><td>WHIP</td><td>OAVG</td>\n";
$text .= "  </tr></thead>\n";
$text .= "  </thead>\n";
$text .= "  <tr>\n";
$text .= " <td class='s1'><div class='spotlight'><img src='images/player_.png' /></div></td>\n";
$text .= " <td class='s1_l'><h3>[Name here]</h3></td>\n";
$text .= " <td class='s1'>2</td><td class='s1'>0</td><td class='s1'>0</td><td class='s1'>12.0</td><td class='s1'>3.00</td><td class='s1'>12</td><td class='s1'>1.17</td><td class='s1'>.244</td></tr>\n";
$text .= " </table>\n";
$text .= " </td></tr>\n";
$text .= " </table>\n";
$text .= " </div>\n";
$text .= " </td></tr><tr><td>\n";

$text .= " <div class='tablebox'>\n";
$text .= "  <table cellpadding=0 cellspacing=0 border=0>\n";
$text .= "   <tr class='h1_l'><td>Batters</td></tr>\n";
$text .= "   <tr><td>\n";
$query = "SELECT * FROM (SELECT p.player_id,p.first_name,p.last_name,p.team_id,sum(h) as h,sum(hr) as hr,sum(rbi) as rbi,sum(r) as r,sum(sb) as sb,(sum(h)/sum(ab)) as avg,(sum(h)+sum(bb)+sum(hp))/(sum(ab)+sum(bb)+sum(hp)+sum(sf)) as obp,(sum(h)+sum(d)+2*sum(t)+3*sum(hr))/sum(ab) as slg,if(SUM(pa)<(2*$pcnt),-99,(0.47*(sum(h)-sum(d)-sum(t)-sum(hr)) + .78*sum(d) + 1.09*sum(t) + 1.4*sum(hr) + .33*(sum(bb)-sum(hp)) + .3*sum(sb) + .5*(-.52*sum(cs) - .26*(sum(ab)-sum(h)-sum(gdp)) - .72*sum(gdp)))) as lw FROM players_game_batting as pgb,players as p WHERE p.player_id=pgb.player_id AND game_id IN ($gidList) GROUP BY player_id ORDER BY lw DESC,last_name,first_name LIMIT 5) as t ORDER BY last_name,first_name;";
$result = mysql_query($query, $db);
$text .= "   <table class='sortable' width=440>\n";
$text .= "    <thead><tr class='hsn2'>\n";
$text .= "     <td class='hsn2_l'>Player</td>";
$text .= "<td>AVG</td>";
$text .= "<td>R</td>";
$text .= "<td>H</td>";
$text .= "<td>HR</td>";
$text .= "<td>RBI</td>";
$text .= "<td>SB</td>";
$text .= "<td>OPS</td>";
$text .= "</tr></thead>\n";
$rownum = 0;
while ($row = mysql_fetch_array($result)) {
    $cls = $rownum % 2 + 1;
    $tid = $row['team_id'];
    $text .= "    <tr>";
    $text .= "<td class='s" . $cls . "_l'><a href='../../players/player_" . $row['player_id'] . ".html'>" . $row['first_name'] . " " . $row['last_name'] . "</a>, <a href='../../teams/team_" . $tid . ".html'>" . $teams[$tid]['abbr'] . "</a></td>";
    $text .= "<td class='s" . $cls . "'>" . strstr(sprintf("%.3f", $row['avg']), ".") . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['r'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['h'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['hr'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['rbi'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['sb'] . "</td>";
    $ops = sprintf("%.3f", $row['obp'] + $row['slg']);
    if ($ops < 1) {
        $ops = strstr($ops, ".");
    }
    $text .= "<td class='s" . $cls . "'>" . $ops . "</td>";
    $text .= "</tr>\n";
    $rownum++;
}
$text .= "   </table>\n";
$text .= "   </td></tr>\n";
$text .= "  </table>\n";
$text .= " </div>\n";

$text .= " </td></tr><tr><td>\n";

$text .= " <div class='tablebox'>\n";
$text .= "  <table cellpadding=0 cellspacing=0 border=0>\n";
$text .= "   <tr class='h1_l'><td>Pitchers</td></tr>\n";
$text .= "   <tr><td>\n";
$query = "SELECT * FROM (SELECT p.player_id,p.first_name,p.last_name,p.team_id,((SUM(ip)*3+SUM(ipf))/3) as ip,sum(w) as w,sum(l) as l,sum(s) as sv,sum(k) as k,9*sum(er)/((SUM(ip)*3+SUM(ipf))/3) as era,(sum(bb)+sum(ha)+sum(hp))/((SUM(ip)*3+SUM(ipf))/3) as whip,sum(ha)/sum(ab) as oavg,(((sum(ha)+sum(bb)+sum(hp))*(0.89*(1.255*(sum(ha)-sum(hra))+4*sum(hra))+0.56*(sum(bb)+sum(hp)-sum(iw))))/(sum(bf)*((SUM(ip)*3+SUM(ipf))/3)))*9*0.75 as erc,if(SUM(ip)<($pcnt-1),-99,3*((SUM(ip)*3+SUM(ipf))/3)+4*sum(w)-4*sum(l)+5*sum(s)+sum(k)+.5*(-2*sum(ha)-2*sum(bb))) as score FROM players_game_pitching_stats as pgp,players as p WHERE p.player_id=pgp.player_id AND game_id IN ($gidList) GROUP BY p.player_id ORDER BY score DESC,last_name,first_name LIMIT 5) as t ORDER BY last_name,first_name;";
$result = mysql_query($query, $db);
$text .= "   <table class='sortable' width=440>\n";
$text .= "    <thead><tr class='hsn2'>\n";
$text .= "     <td class='hsn2_l'>Player</td>";
$text .= "<td>W</td>";
$text .= "<td>L</td>";
$text .= "<td>SV</td>";
$text .= "<td>IP</td>";
$text .= "<td>ERA</td>";
$text .= "<td>K</td>";
$text .= "<td>WHIP</td>";
$text .= "<td>OAVG</td>";
$text .= "</tr></thead>\n";
$rownum = 0;
while ($row = mysql_fetch_array($result)) {
    $cls = $rownum % 2 + 1;
    $tid = $row['team_id'];
    $text .= "    <tr>";
    $text .= "<td class='s" . $cls . "_l'><a href='../../players/player_" . $row['player_id'] . ".html'>" . $row['first_name'] . " " . $row['last_name'] . "</a>, <a href='../../teams/team_" . $tid . ".html'>" . $teams[$tid]['abbr'] . "</a></td>";
    $text .= "<td class='s" . $cls . "'>" . $row['w'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['l'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['sv'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . sprintf("%.1f", $row['ip']) . "</td>";
    $text .= "<td class='s" . $cls . "'>" . sprintf("%.2f", $row['era']) . "</td>";
    $text .= "<td class='s" . $cls . "'>" . $row['k'] . "</td>";
    $text .= "<td class='s" . $cls . "'>" . sprintf("%.2f", $row['whip']) . "</td>";
    $oavg = sprintf("%.3f", $row['oavg']);
    if ($oavg < 1) {
        $oavg = strstr($oavg, ".");
    }
    $text .= "<td class='s" . $cls . "'>" . $oavg . "</td>";
    $text .= "</tr>\n";
    $rownum++;
}
$text .= "   </table>\n";
$text .= "   </td></tr>\n";
$text .= "  </table>\n";
$text .= " </div>\n";

$text .= "   </td>\n";
$text .= "  </tr>\n";
$text .= " </table>\n";

$text .= "</div>\n";
$text .= "</div>\n";

##### Display Team Batting Stats #####
foreach ($e as $key => $tid) {
    ##### Get Batter Stats #####
    $Tg = 0;
    $Tab = 0;
    $Th = 0;
    $Td = 0;
    $Tt = 0;
    $Thr = 0;
    $Trbi = 0;
    $Tr = 0;
    $Tsb = 0;
    $Tcs = 0;
    $Tbb = 0;
    $Thbp = 0;
    $Tsf = 0;
    $Tgidp = 0;
    $Tk = 0;

    $query = "SELECT p.player_id,p.first_name,p.last_name,COUNT(game_id) as g,sum(ab) as ab,sum(h) as h,sum(d) as d,sum(t) as t,sum(hr) as hr,sum(rbi) as rbi,sum(r) as r,sum(sb) as sb,sum(cs) as cs,sum(bb) as bb,sum(hp) as hbp,sum(sf) as sf,sum(gdp) as gidp,sum(k) as k FROM players_game_batting as pgb,players as p WHERE p.player_id=pgb.player_id AND p.team_id=$tid AND game_id IN ($gidList) GROUP BY player_id ORDER BY last_name,first_name;";
    $result = mysql_query($query, $db);
    $text .= " <div class='tablebox' style='width:915px;'>\n";
    $text .= "  <table cellpadding=0 cellspacing=0 border=0><tr class='h1_l'><td>" . $teams[$tid]['name'] . " Batting Stats</td></tr><tr><td>\n";
    $text .= "   <table class='sortable' style='width:915px;'>\n";
    $text .= "    <thead><tr class='hsn2'>\n";
    $text .= "     <td class='hsn2_l'>Player</td><td class='hsn2'>G</td><td class='hsn2'>AB</td><td class='hsn2'>R</td><td class='hsn2'>H</td><td class='hsn2'>2B</td><td class='hsn2'>3B</td><td class='hsn2'>HR</td><td class='hsn2'>RBI</td><td class='hsn2'>BB</td><td class='hsn2'>K</td><td class='hsn2'>SB</td><td class='hsn2'>CS</td><td class='hsn2'>AVG</td><td class='hsn2'>OBP</td><td class='hsn2'>SLG</td><td class='hsn2'>OPS</td>\n";
    $text .= "    </tr></thead>\n";
    $rownum = 0;
    $prevTID = "";
    while ($row = mysql_fetch_array($result)) {
        $g = $row['g'];
        $ab = $row['ab'];
        $h = $row['h'];
        $d = $row['d'];
        $t = $row['t'];
        $hr = $row['hr'];
        $rbi = $row['rbi'];
        $r = $row['r'];
        $sb = $row['sb'];
        $cs = $row['cs'];
        $bb = $row['bb'];
        $hbp = $row['hbp'];
        $sf = $row['sf'];
        $gidp = $row['gidp'];
        $k = $row['k'];
        $Tg += $g;
        $Tab += $ab;
        $Th += $h;
        $Td += $d;
        $Tt += $t;
        $Thr += $hr;
        $Trbi += $rbi;
        $Tr += $r;
        $Tsb += $sb;
        $Tcs += $cs;
        $Tbb += $bb;
        $Thbp += $hbp;
        $Tsf += $sf;
        $Tgidp += $gidp;
        $Tk += $k;

        $rcls = $rownum % 2 + 1;
        $text .= "    <tr class='s" . $rcls . "'>";
        $text .= "<td class='s" . $rcls . "_l'><a href='../../players/player_" . $row['player_id'] . ".html'>" . $row['first_name'] . " " . $row['last_name'] . "</a></td>";
        $text .= "<td>" . $g . "</td>";
        $text .= "<td>" . $ab . "</td>";
        $text .= "<td>" . $r . "</td>";
        $text .= "<td>" . $h . "</td>";
        $text .= "<td>" . $d . "</td>";
        $text .= "<td>" . $t . "</td>";
        $text .= "<td>" . $hr . "</td>";
        $text .= "<td>" . $rbi . "</td>";
        $text .= "<td>" . $bb . "</td>";
        $text .= "<td>" . $k . "</td>";
        $text .= "<td>" . $sb . "</td>";
        $text .= "<td>" . $cs . "</td>";
        if ($ab == 0) {
            $avg = round(0, 3);
            $slg = round(0, 3);
        }
        else {
            $avg = $h / $ab;
            $slg = ($h + $d + 2 * $t + 3 * $hr) / $ab;
        }
        if (($ab + $bb + $hbp + $sf) == 0) {
            $obp = 0;
        }
        else {
            $obp = ($h + $bb + $hbp) / ($ab + $bb + $hbp + $sf);
        }
        $ops = $obp + $slg;
        if ($avg < 1) {
            $avg = strstr(sprintf("%.3f", $avg), ".");
        }
        else {
            $avg = sprintf("%.3f", $avg);
        }
        if ($obp < 1) {
            $obp = strstr(sprintf("%.3f", $obp), ".");
        }
        else {
            $obp = sprintf("%.3f", $obp);
        }
        if ($slg < 1) {
            $slg = strstr(sprintf("%.3f", $slg), ".");
        }
        else {
            $slg = sprintf("%.3f", $slg);
        }
        if ($ops < 1) {
            $ops = strstr(sprintf("%.3f", $ops), ".");
        }
        else {
            $ops = sprintf("%.3f", $ops);
        }
        $text .= "<td>" . $avg . "</td>";
        $text .= "<td>" . $obp . "</td>";
        $text .= "<td>" . $slg . "</td>";
        $text .= "<td>" . $ops . "</td>";
        $text .= "</tr>\n";
        $rownum++;
    }

    ## Display Team Totals
    $text .= "    <tfoot><tr class='hsn2'>";
    $text .= "<td class='hsn2_l'>Totals</td>";
    $text .= "<td class='hsn2'>" . $Tg . "</td>";
    $text .= "<td class='hsn2'>" . $Tab . "</td>";
    $text .= "<td class='hsn2'>" . $Tr . "</td>";
    $text .= "<td class='hsn2'>" . $Th . "</td>";
    $text .= "<td class='hsn2'>" . $Td . "</td>";
    $text .= "<td class='hsn2'>" . $Tt . "</td>";
    $text .= "<td class='hsn2'>" . $Thr . "</td>";
    $text .= "<td class='hsn2'>" . $Trbi . "</td>";
    $text .= "<td class='hsn2'>" . $Tbb . "</td>";
    $text .= "<td class='hsn2'>" . $Tk . "</td>";
    $text .= "<td class='hsn2'>" . $Tsb . "</td>";
    $text .= "<td class='hsn2'>" . $Tcs . "</td>";
    if ($Tab == 0) {
        $avg = round(0, 3);
        $slg = round(0, 3);
    }
    else {
        $avg = $Th / $Tab;
        $slg = ($Th + $Td + 2 * $Tt + 3 * $Thr) / $Tab;
    }
    $pa = $Tab + $Tbb + $Thbp + $Tsf;
    if ($pa == 0) {
        $obp = 0;
    }
    else {
        $obp = ($Th + $Tbb + $Thbp) / $pa;
    }
    $ops = $obp + $slg;
    if ($avg < 1) {
        $avg = strstr(sprintf("%.3f", $avg), ".");
    }
    else {
        $avg = sprintf("%.3f", $avg);
    }
    if ($obp < 1) {
        $obp = strstr(sprintf("%.3f", $obp), ".");
    }
    else {
        $obp = sprintf("%.3f", $obp);
    }
    if ($slg < 1) {
        $slg = strstr(sprintf("%.3f", $slg), ".");
    }
    else {
        $slg = sprintf("%.3f", $slg);
    }
    if ($ops < 1) {
        $ops = strstr(sprintf("%.3f", $ops), ".");
    }
    else {
        $ops = sprintf("%.3f", $ops);
    }
    $text .= "<td class='hsn2'>" . $avg . "</td>";
    $text .= "<td class='hsn2'>" . $obp . "</td>";
    $text .= "<td class='hsn2'>" . $slg . "</td>";
    $text .= "<td class='hsn2'>" . $ops . "</td>";
    $text .= "</tr></tfoot>\n";

    $text .= "   </table>\n";
    $text .= "  </td></tr></table>\n";
    $text .= " </div>   <!-- END batter stats DIV -->\n";
}

##### Display Team Pitching Stats #####
foreach ($e as $key => $tid) {
    $Tg = 0;
    $Tgs = 0;
    $Tip = 0;
    $Tw = 0;
    $Tl = 0;
    $Tsv = 0;
    $Tr = 0;
    $Ter = 0;
    $Tk = 0;
    $Tha = 0;
    $Tbba = 0;
    $Thra = 0;
    $Tcg = 0;
    $Tsho = 0;
    $Tab = 0;
    $Tsf = 0;

    ##### Get Pitcher Stats #####
    $query = "SELECT p.player_id,p.first_name,p.last_name,sum(g) as g,sum(gs) as gs,((sum(IP)*3+sum(ipf))/3) as ip,sum(w) as w,sum(l) as l,sum(s) as sv,sum(r) as r,sum(er) as er,sum(k) as k,sum(ha) as ha,sum(bb) as bba,sum(hra) as hra,sum(cg) as cg,sum(sho) as sho,sum(ab) as ab,sum(sf) as sf FROM players_game_pitching_stats as pgp,players as p WHERE p.player_id=pgp.player_id AND p.team_id=$tid AND game_id IN ($gidList) GROUP BY player_id ORDER BY last_name,first_name;";
    $result = mysql_query($query, $db);
    $text .= " <div class='tablebox' style='width:915px;'>\n";
    $text .= "  <table cellpadding=0 cellspacing=0 border=0><tr class='h1_l'><td>" . $teams[$tid]['name'] . " Pitching Stats</td></tr><tr><td>\n";
    $text .= "   <table class='sortable' style='width:915px;'>\n";
    $text .= "    <thead><tr class='hsn2'>\n";
    $text .= "     <td class='hsn2_l'>Player</td><td class='hsn2'>W</td><td class='hsn2'>L</td><td class='hsn2'>SV</td><td class='hsn2'>CG</td><td class='hsn2'>SHO</td><td class='hsn2'>ERA</td><td class='hsn2'>G</td><td class='hsn2'>GS</td><td class='hsn2'>IP</td><td class='hsn2'>HA</td><td class='hsn2'>R</td><td class='hsn2'>ER</td><td class='hsn2'>HR</td><td class='hsn2'>BB</td><td class='hsn2'>K</td><td class='hsn2'>WHIP</td><td class='hsn2'>OAVG</td><td class='hsn2'>BABIP</td>\n";
    $text .= "    </tr></thead>\n";
    $rownum = 0;
    while ($row = mysql_fetch_array($result)) {
        $rcls = $rownum % 2 + 1;

        $g = $row['g'];
        $gs = $row['gs'];
        $ip = $row['ip'];
        $w = $row['w'];
        $l = $row['l'];
        $sv = $row['sv'];
        $r = $row['r'];
        $er = $row['er'];
        $k = $row['k'];
        $ha = $row['ha'];
        $bba = $row['bba'];
        $hra = $row['hra'];
        $cg = $row['cg'];
        $sho = $row['sho'];
        $ab = $row['ab'];
        $sf = $row['sf'];
        $Tg += $g;
        $Tgs += $gs;
        $Tip += $ip;
        $Tw += $w;
        $Tl += $l;
        $Tsv += $sv;
        $Tr += $r;
        $Ter += $er;
        $Tk += $k;
        $Tha += $ha;
        $Tbba += $bba;
        $Thra += $hra;
        $Tcg += $cg;
        $Tsho += $sho;
        $Tab += $ab;
        $Tsf += $sf;

        $text .= "    <tr class='s" . $rcls . "'>";
        $text .= "<td class='s" . $rcls . "_l'><a href='../../players/player_" . $row['player_id'] . ".html'>" . $row['first_name'] . " " . $row['last_name'] . "</a></td>";
        $text .= "<td>" . $w . "</td>";
        $text .= "<td>" . $l . "</td>";
        $text .= "<td>" . $sv . "</td>";
        $text .= "<td>" . $cg . "</td>";
        $text .= "<td>" . $sho . "</td>";
        if ($ip == 0) {
            $era = 0;
            $whip = 0;
        }
        else {
            $era = $er * 9 / $ip;
            $whip = ($ha + $bba) / $ip;
        }
        $era = sprintf("%.2f", $era);
        $text .= "<td>" . $era . "</td>";
        $text .= "<td>" . $g . "</td>";
        $text .= "<td>" . $gs . "</td>";
        $ip = sprintf("%.1f", $ip);
        if ($ip < 1) {
            $ip = strstr($ip, ".");
        }
        $text .= "<td>" . $ip . "</td>";
        $text .= "<td>" . $ha . "</td>";
        $text .= "<td>" . $r . "</td>";
        $text .= "<td>" . $er . "</td>";
        $text .= "<td>" . $hra . "</td>";
        $text .= "<td>" . $bba . "</td>";
        $text .= "<td>" . $k . "</td>";
        $whip = sprintf("%.2f", $whip);
        if ($whip < 1) {
            $whip = strstr($whip, ".");
        }
        $text .= "<td>" . $whip . "</td>";
        if ($ab == 0) {
            $oavg = 0;
        }
        else {
            $oavg = $ha / $ab;
        }
        if ($oavg < 1) {
            $oavg = strstr(sprintf("%.3f", $oavg), ".");
        }
        else {
            $oavg = sprintf("%.3f", $oavg);
        }
        $text .= "<td>" . $oavg . "</td>";
        $bip = $ab - $k - $hra + $sf;
        if ($bip == 0) {
            $babip = 0;
        }
        else {
            $babip = ($ha - $hra) / $bip;
        }
        if ($babip < 1) {
            $babip = strstr(sprintf("%.3f", $babip), ".");
        }
        else {
            $babip = sprintf("%.3f", $babip);
        }
        $text .= "<td>" . $babip . "</td>";
        $text .= "</tr>\n";
        $rownum++;
    }

    ## Display Team Totals
    $text .= "    <tfoot><tr class='hsn2'>";
    $text .= "<td class='hsn2_l'>Totals</td>";
    $text .= "<td class='hsn2'>" . $Tw . "</td>";
    $text .= "<td class='hsn2'>" . $Tl . "</td>";
    $text .= "<td class='hsn2'>" . $Tsv . "</td>";
    $text .= "<td class='hsn2'>" . $Tcg . "</td>";
    $text .= "<td class='hsn2'>" . $Tsho . "</td>";
    if ($Tip == 0) {
        $era = 0;
        $whip = 0;
    }
    else {
        $era = $Ter * 9 / $Tip;
        $whip = ($Tha + $Tbba) / $Tip;
    }
    $era = sprintf("%.2f", $era);
    $text .= "<td class='hsn2'>" . $era . "</td>";
    $text .= "<td class='hsn2'>" . $Tg . "</td>";
    $text .= "<td class='hsn2'>" . $Tgs . "</td>";
    $ip = sprintf("%.1f", $Tip);
    if ($ip < 1) {
        $ip = strstr($ip, ".");
    }
    $text .= "<td class='hsn2'>" . $ip . "</td>";
    $text .= "<td class='hsn2'>" . $Tha . "</td>";
    $text .= "<td class='hsn2'>" . $Tr . "</td>";
    $text .= "<td class='hsn2'>" . $Ter . "</td>";
    $text .= "<td class='hsn2'>" . $Thra . "</td>";
    $text .= "<td class='hsn2'>" . $Tbba . "</td>";
    $text .= "<td class='hsn2'>" . $Tk . "</td>";
    $whip = sprintf("%.2f", $whip);
    if ($whip < 1) {
        $whip = strstr($whip, ".");
    }
    $text .= "<td class='hsn2'>" . $whip . "</td>";
    if ($Tab == 0) {
        $oavg = 0;
    }
    else {
        $oavg = $Tha / $Tab;
    }
    if ($oavg < 1) {
        $oavg = strstr(sprintf("%.3f", $oavg), ".");
    }
    else {
        $oavg = sprintf("%.3f", $oavg);
    }
    $text .= "<td class='hsn2'>" . $oavg . "</td>";
    $bip = $Tab - $Tk - $Thra + $Tsf;
    if ($bip == 0) {
        $babip = 0;
    }
    else {
        $babip = ($Tha - $Thra) / $bip;
    }
    if ($babip < 1) {
        $babip = strstr(sprintf("%.3f", $babip), ".");
    }
    else {
        $babip = sprintf("%.3f", $babip);
    }
    $text .= "<td class='hsn2'>" . $babip . "</td>";
    $text .= "</tr></tfoot>\n";

    $text .= "   </table>\n";
    $text .= "  </td></tr></table>\n";
    $text .= " </div>   <!-- END pitcher stats DIV -->\n";
}
   */
echo $text;
?>