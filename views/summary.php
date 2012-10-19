<?php
if (!isset($rounds) || !is_array($rounds) || count($rounds) == 0) { 
	echo("<div class='textbox'>No playoff games scheduled</div>\n");
} else {
	krsort($rounds);
    $text="";
	foreach ($rounds as $rnd => $val) 
	{
      ##### Begin Round Display #####
      $text.="<div class='textbox'>\n";
      $text.=" <table cellpadding=0 cellspacing=0 border=0 width='935px'>\n";
      $fldName="round_names".($rnd-1);
      $text.="  <tr class='title2'><td colspan=3>".$playoffConfig[$fldName]."</td></tr>\n";
      $text.="  <tr>\n";

      ##### Check for Max Round #####
      $fldName='max_round';
      if ($playoffConfig[$fldName]==$rnd)   ## In World Series
       {
         $text.="   <td colspan=3 align='center'>\n";
	 foreach ($series as $serID => $val)
	  {
  	    if ($series[$serID]['rnd']!=$rnd) {continue;}

  	    ##### Get Teams #####
	    $e=explode(":",$serID);
	    $aid=$e[0];
	    $hid=$e[1];

	    $hW=$teams[$hid]['w'];
	    $hPos=$teams[$hid]['pos'];
	    $aW=$teams[$aid]['w'];
	    $aPos=$teams[$aid]['pos'];

	    if ($aPos==$hPos)
	     {
	       if ($aW>$hW) {$who='a';}
	        else {$who='h';}
	     }
	     elseif ($aPos>$hPos) {$who='a';}
	     else {$who='h';}
            if ($who=='a')
	     {
	       $tmp=$hid;
	       $hid=$aid;
	       $aid=$tmp;
	     }

	    ##### Show Each Series #####
	    $text.="     <table cellpadding=2 cellspacing=0 style='border:1px black solid;width:400px;margin:10px;'>\n";
	    $text.="      <tr class='headline'>\n";
	    $text.="       <td class='hsc2_l' colspan=4>\n";
	    $text.="        ".$teams[$aid]['abbr']." vs. ".$teams[$hid]['abbr']."\n";
	    $text.="       </td>\n";
	    $text.="      </tr>\n";
	    $text.="      <tr>\n";
	    $text.="       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='$lgpath/images/".$teams[$aid]['logo']."' width=40 height=40></td>\n";
	    $text.="       <td class='sl' width=175><a href='$lgpath/teams/team_$aid.html'>".$teams[$aid]['name']."</a></td>\n";
	    $text.="       <td class='sl' width=175 style='text-align:right;padding-right:5px;'>".ordinal_suffix($teams[$aid]['pos'])." place, ".$teams[$aid]['w']."-".$teams[$aid]['l']."</td>\n";
	    $text.="       <td class='icgb' width=50>".$series[$serID][$aid]['w']."</td>\n";
	    $text.="      </tr>\n";
	    $text.="      <tr>\n";
	    $text.="       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='$lgpath/images/".$teams[$hid]['logo']."' width=40 height=40></td>\n";
	    $text.="       <td class='sl' width=175 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'><a href='$lgpath/teams/team_$hid.html'>".$teams[$hid]['name']."</a></td>\n";
	    $text.="       <td class='sl' width=175 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>".ordinal_suffix($teams[$hid]['pos'])." place, ".$teams[$hid]['w']."-".$teams[$hid]['l']."</td>\n";
	    $text.="       <td class='icgb' width=50 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>".$series[$serID][$hid]['w']."</td>\n";
	    $text.="      </tr>\n";
            $text.="      <tr>\n";
	    $text.="       <td class='sl' colspan=4 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>--> <a href='./playoffs.php?series=$serID&round=$rnd'>Series Detail</a>, <a href='./matchups.php?team_id1=$hid&team_id2=$aid'>Season Series</a></td>\n";
	    $text.="      </tr>\n";
	    $text.="     </table>\n";
	  }
	 $text.="</td>\n";    
       }
       else    ## Sub-league playoff round
       {
         $slCnt=0;
         foreach ($subleagues as $slid => $val)
          {
            ## Structure Adjustment
            if (($slCnt!=0)&&(($slCnt%2)==0))
             {
               $text.="  </tr>\n";
               $text.="  <tr>\n";
             }
            if (($slCnt!=0)&&(($slCnt%2)==1))
             {
               $text.="   <td>&nbsp;</td>\n";
	     }

            ##### Begin League Display #####
            $text.="   <td>\n";
	    $text.="    <div class='boxscores'>\n";
	    $text.="     <table cellpadding=2 cellspacing=0 border=0 style='border:0;margin:0;'><tr class='title'><td width='420px'>".$subleagues[$slid]['name']."</td></tr></table>\n";
   
	    foreach ($series as $serID => $val)
  	     {
               if ($series[$serID]['rnd']!=$rnd) {continue;}
               if ($series[$serID]['slid']!=$slid) {continue;}

  	       ##### Get Teams #####
	       $e=explode(":",$serID);
	       $aid=$e[0];
	       $hid=$e[1];
   
	       $hW=$teams[$hid]['w'];
	       $hPos=$teams[$hid]['pos'];
	       $aW=$teams[$aid]['w'];
	       $aPos=$teams[$aid]['pos'];
   
	       if ($aPos==$hPos)
  	        {
	          if ($aW>$hW) {$who='a';}
	           else {$who='h';}
	        }
	        elseif ($aPos>$hPos) {$who='a';}
	        else {$who='h';}
               if ($who=='a')
	        {
	          $tmp=$hid;
	          $hid=$aid;
		  $aid=$tmp;
		}
   
	       ##### Show Each Series #####
	       $text.="     <table cellpadding=2 cellspacing=0 style='border:1px black solid;width:400px;margin:10px;'>\n";
	       $text.="      <tr class='headline'>\n";
	       $text.="       <td class='hsc2_l' colspan=4>\n";
	       $text.="        ".$teams[$aid]['abbr']." vs. ".$teams[$hid]['abbr']."\n";
	       $text.="       </td>\n";
	       $text.="      </tr>\n";
	       $text.="      <tr>\n";
	       $text.="       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='$lgpath/images/".$teams[$aid]['logo']."' width=40 height=40></td>\n";
	       $text.="       <td class='sl' width=175><a href='$lgpath/teams/team_$aid.html'>".$teams[$aid]['name']."</a></td>\n";
	       $text.="       <td class='sl' width=175 style='text-align:right;padding-right:5px;'>".ordinal_suffix($teams[$aid]['pos'])." place, ".$teams[$aid]['w']."-".$teams[$aid]['l']."</td>\n";
	       $text.="       <td class='icgb' width=50>".$series[$serID][$aid]['w']."</td>\n";
	       $text.="      </tr>\n";
	       $text.="      <tr>\n";
	       $text.="       <td style='padding:1px;width:44px;border-right:1px solid #999999;'><img src='$lgpath/images/".$teams[$hid]['logo']."' width=40 height=40></td>\n";
	       $text.="       <td class='sl' width=175 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'><a href='$lgpath/teams/team_$hid.html'>".$teams[$hid]['name']."</a></td>\n";
	       $text.="       <td class='sl' width=175 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>".ordinal_suffix($teams[$hid]['pos'])." place, ".$teams[$hid]['w']."-".$teams[$hid]['l']."</td>\n";
	       $text.="       <td class='icgb' width=50 style='border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>".$series[$serID][$hid]['w']."</td>\n";
	       $text.="      </tr>\n";
	       $text.="      <tr>\n";
	       $text.="       <td class='sl' colspan=4 style='text-align:right;padding-right:5px;border-top-width:1px; border-top-style:solid; border-top-color:#999999;'>--> <a href='./playoffs.php?series=$serID&round=$rnd'>Series Detail</a>, <a href='./matchups.php?team_id1=$hid&team_id2=$aid'>Season Series</a></td>\n";
	       $text.="      </tr>\n";
	       $text.="     </table>\n";
	     }
    
	    ##### End League Display
	    $text.="    </div>\n";
	    $text.="   </td>\n";
	    $slCnt++;
	  }
	 if (($slCnt!=0)&&(($slCnt%2)==1))
	 {
            $text.="   <td colspan=2>&nbsp;</td>\n";
          }
       }

      ##### End Round Display
      $text.="  </tr>\n";
      $text.=" </table>\n";
      $text.="</div>\n";
    }

   echo $text;

} // END if (!isset($rounds))
?>