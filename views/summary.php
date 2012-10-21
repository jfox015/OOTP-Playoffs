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
                $text .= "     <table cellpadding=2 cellspacing=0 style='border:1px black solid;width:400px;margin:10px;'>\n";
                $text .= "      <tr class='headline_ws'>\n";
                $text .= "       <td class='teamsTitles' colspan=4>\n";
                $text .= "        " . $teams[$aid]['city'] . " vs. " . $teams[$hid]['city'] . "\n";
                $text .= "       </td>\n";
                $text .= "      </tr>\n";
                $text .= "      <tr>\n";
                $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $logo_path . $teams[$aid]['logo'] . "' width=40 height=40></td>\n";
                $text .= "       <td class='sl' width=175><a href='".$settings['ootp.asset_url']."teams/team_$aid.html'>" . $teams[$aid]['name'] . "</a></td>\n";
                $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;'>" . ordinal_suffix($teams[$aid]['pos']) . " place, " . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] . "</td>\n";
                $text .= "       <td class='icgb' width=50>" . $series[$serID][$aid]['w'] . "</td>\n";
                $text .= "      </tr>\n";
                $text .= "      <tr>\n";
                $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $logo_path . $teams[$hid]['logo'] . "' width=40 height=40></td>\n";
                $text .= "       <td class='sl' width=175 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'><a href='$lgpath/teams/team_$hid.html'>" . $teams[$hid]['name'] . "</a></td>\n";
                $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . ordinal_suffix($teams[$hid]['pos']) . " place, " . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] . "</td>\n";
                $text .= "       <td class='icgb' width=50 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . $series[$serID][$hid]['w'] . "</td>\n";
                $text .= "      </tr>\n";
                $text .= "      <tr>\n";
                $text .= "       <td class='sl' colspan=4 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>";
                $text .= anchor('/playoffs/'.$league_id.'/'.$serID.'/'.$rnd,'Series Detail')."\n";
                $text .= "      </td>\n";
                $text .= "      </tr>\n";
                $text .= "     </table>\n";
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
                    $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $logo_path . $teams[$aid]['logo'] . "' width=40 height=40></td>\n";
                    $text .= "       <td class='sl' width=175><a href='".$settings['ootp.asset_url']."teams/team_$aid.html'>" . $teams[$aid]['name'] . "</a></td>\n";
                    $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;'>" . ordinal_suffix($teams[$aid]['pos']) . " place, " . $teams[$aid]['w'] . "-" . $teams[$aid]['l'] . "</td>\n";
                    $text .= "       <td class='icgb' width=50>" . $series[$serID][$aid]['w'] . "</td>\n";
                    $text .= "      </tr>\n";
                    $text .= "      <tr>\n";
                    $text .= "       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='" . $logo_path . $teams[$hid]['logo'] . "' width=40 height=40></td>\n";
                    $text .= "       <td class='sl' width=175 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'><a href='$lgpath/teams/team_$hid.html'>" . $teams[$hid]['name'] . "</a></td>\n";
                    $text .= "       <td class='sl' width=175 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . ordinal_suffix($teams[$hid]['pos']) . " place, " . $teams[$hid]['w'] . "-" . $teams[$hid]['l'] . "</td>\n";
                    $text .= "       <td class='icgb' width=50 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>" . $series[$serID][$hid]['w'] . "</td>\n";
                    $text .= "      </tr>\n";
                    $text .= "      <tr>\n";
                    $text .= "       <td class='sl' colspan=4 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>";
                    $text .= anchor('/playoffs/'.$league_id.'/'.$serID.'/'.$rnd,'Series Detail')."\n";
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