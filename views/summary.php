<?php
if (!isset($rounds) || !is_array($rounds) || count($rounds) == 0) {
    echo('<div class="row">No playoff games scheduled</div>' . "\n");
} else {
    krsort($rounds);
    $text = "";
    foreach ($rounds as $rnd => $val) {

        ##### Begin Round Display #####
        $text .= '<div class="container-fluid">' . "\n";
        $text .= '<div class="row-fluid">' . "\n";
        $text .= "\t" . '<div class="span12">' . "\n";
        $fldName = "round_names" . (intval($rnd) - 1);

        $text .= "  <h1>" . $playoffConfig[$fldName] . "</h1><br />\n";
        $text .= "  </div>\n";
        $text .= "  </div>\n";

        ##### Check for Max Round #####
        $fldName = 'max_round';

        ## In World Series
        if ($playoffConfig[$fldName] == $rnd)
        {
            $text .= "   <td colspan=3 align='center'>\n";
            foreach ($series as $serID => $val) {
                if ($series[$serID]['rnd'] != $rnd) {
                    continue;
                }

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
                $text.="     <table cellpadding=2 cellspacing=0 style='width:98%;margin:10px;'>\n";
				$text.="     <tr>\n";
				$text.="     <td style='padding:1px;width:30%;text-align:center;'><img src='" . $settings['ootp.team_logo_path'] . $teams[$aid]['logo_file']."' ";
				$text.="     width='150' height='150' /><br/>";
				$text.='     <span style="font-size:large; font-weight:bold;">'.$teams[$aid]['name'].'</span><br />'.ordinal_suffix($teams[$aid]['pos'])." place, ".$teams[$aid]['w']."-".$teams[$aid]['l'].'<br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'teams/team_'.$aid.'.html"class="teamLinks">Team Home</a><br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'history/team_year_'.$aid.'_'.$year.'.html" class="teamLinks">Roster</a><br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'history/team_'.$aid.'_index.html" class="teamLinks">History</a></td>';

				$text.="     <td style='padding:8px;width:40%;text-align:left;'>\n<h2>";
				$homeScore = $series[$serID][$hid]['w'];
				$awayScore = $series[$serID][$aid]['w'];
				$word = " defeat ";
				if ($homeScore == 0 || $awayScore == 0) $word = " sweep ";
				if ($homeScore > $awayScore) {
					$text.="     ".$teams[$hid]['nickname'].$word.$teams[$aid]['nickname']." ".$homeScore."-".$awayScore;
				} else {
					$text.="     ".$teams[$aid]['nickname'].$word.$teams[$hid]['nickname']." ".$awayScore."-".$homeScore;
				}
				$text.="</h2>\n";
				$text.="     <p />\n";
				$text.="     <h3 class='subhead2'>World Series MVP</h3>\n";
				$text.="     <div class='subhead_rule'></div>\n";
				$text.="     <p /><br />\n";
				$text.='     <div class="spotlight">'."\n";
				$text.='     <img src="'.$settings['players_img_path'].'player_.png" />'."\n";
				$text.="     <span class='spotlight_player'></span><br />\n";
				$text.='     </div>';
				$text.='     <br class="clear" />';
				$text.='     <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="5">'."\n";
				$text.='     <TR ALIGN="center" VALIGN="top">';
				$text.='         <TD colspan="2">';
				$text.='         <TABLE BORDER="0" CELLSPACING="2" CELLPADDING="3">';
				$text.="         <TR BGCOLOR='#606060'>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";
				$text.="                 <TD CLASS='smallBoldCellWhite'>&nbsp;&nbsp;</TD>\n";		
				$text.="             </TR>\n";
				$text.="             <TR BGCOLOR='#EFEEE4' ALIGN='right'>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="                 <TD CLASS='smallCell'></TD>\n";
				$text.="         </TR>\n";
				$text.="         </TABLE></TD>\n";
				$text.='     </TR>';
				$text.='     </TABLE>';
				$text.="     <p style='text-align:right;margin:0 8px 8px 0;'>\n";
				$text.= 	 anchor('/playoffs/series/'.$league_id.'/'.$serID.'/'.$rnd,'Complete Series Details')."\n";
				$text.='     </p>';
				$text.='     </td>';

				$text.="     <td style='padding:1px;width:30%;text-align:center;'><img src='" . $settings['ootp.team_logo_path'] . $teams[$hid]['logo_file']."' ";
				$text.='     width="150" height="150" /><br/>';
				$text.='     <span style="font-size:large; font-weight:bold;">'.$teams[$hid]['name'].'</span><br />';
				$text.='     '.ordinal_suffix($teams[$hid]['pos'])." place, ".$teams[$hid]['w']."-".$teams[$hid]['l'].'<br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'teams/team_'.$hid.'.html"class="teamLinks">Team Home</a><br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'history/team_year_'.$hid.'_'.$year.'.html" class="teamLinks">Roster</a><br />';
				$text.='     <a href="'.$settings['ootp.asset_url'].'history/team_'.$hid.'_index.html" class="teamLinks">History</a></td></td>';
				$text.='     </tr>';
				$text.='     </table>';
            }
            $text .= "</td>\n";
        }
        ## Sub-league playoff round
        else
        {
            $slCnt = 0;
            $text .= '   <div class="row-fluid">' . "\n";
            foreach ($subleagues as $slid => $val) {
                ## Structure Adjustment
                /*if (($slCnt!=0)&&(($slCnt%2)==0))
                 {
                     $text .= "</div>\n";
                 }
                if (($slCnt!=0)&&(($slCnt%2)==1))
                 {
                     $text .= '<div class="span6"></div>' . "\n";
                }  */

                ##### Begin League Display #####

                $text .= '<div class="span6">' . "\n";
                $text .= '<h3>' . $subleagues[$slid]['name'] . '</h3>' . "\n";

                foreach ($series as $serID => $val) {
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
                    $text .= "    <div class='boxscores'>\n";
                    $text .= "     <table cellpadding=2 cellspacing=0 style='border:1px black solid;margin:10px;'>\n";
                    $text .= "      <tr class='headline_other'>\n";
                    $text .= "       <td class='teamsTitles' colspan=4>\n";
                    $text .= "        " . $teams[$aid]['nickname'] . " vs. " . $teams[$hid]['nickname'] . "\n";
                    $text .= "       </td>\n";
                    $text .= "      </tr>\n";
                    $text .= "      <tr>\n";
                    $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $settings['ootp.team_logo_path'] . $teams[$aid]['logo_file'] . "' width=40 height=40></td>\n";
                    $text .= "       <td class='sl' width=175><a href='".$settings['ootp.asset_url']."teams/team_$aid.html'>" . $teams[$aid]['name'] . "</a></td>\n";
                    $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;'>" . ordinal_suffix($teams[$aid]['pos']) . " place, " . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] . "</td>\n";
                    $text .= "       <td class='icgb' width=50>" . $series[$serID][$aid]['w'] . "</td>\n";
                    $text .= "      </tr>\n";
                    $text .= "      <tr>\n";
                    $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $settings['ootp.team_logo_path'] . $teams[$hid]['logo_file'] . "' width=40 height=40></td>\n";
                    $text .= "       <td class='sl' width=175 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'><a href='".$settings['ootp.asset_url']."teams/team_$hid.html'>" . $teams[$hid]['name'] . "</a></td>\n";
                    $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . ordinal_suffix($teams[$hid]['pos']) . " place, " . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] . "</td>\n";
                    $text .= "       <td class='icgb' width=50 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . $series[$serID][$hid]['w'] . "</td>\n";
                    $text .= "      </tr>\n";
                    $text .= "      <tr>\n";
                    $text .= "       <td class='sl' colspan=4 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>";
                    $text .= anchor('/playoffs/series/'.$league_id.'/'.$serID.'/'.$rnd,'Series Detail')."\n";
                    $text .= "      </tr>\n";
                    $text .= "     </table>\n";
                    $text .= "    </div><br />\n";
                }
                ##### End League Display
                $text .= "    </div>\n";

                $slCnt++;
            } // END foreach
            $text .= "   </div>\n";
            /*if (($slCnt != 0) && (($slCnt % 2) == 1)) {
                $text .= '<div class="span6"></div>' . "\n";
            }   */
        }

        ##### End Round Display
        /*$text.="  </tr>\n";
        $text.=" </table>\n"; */
        $text .= "</div>\n";
    }
    echo $text;
} // END if (!isset($rounds))
?>