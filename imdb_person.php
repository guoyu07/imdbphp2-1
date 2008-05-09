<?php
 #############################################################################
 # IMDBPHP                              (c) Giorgos Giagas & Itzchak Rehberg #
 # written by Giorgos Giagas                                                 #
 # extended & maintained by Itzchak Rehberg <izzysoft AT qumran DOT org>     #
 # http://www.izzysoft.de/                                                   #
 # ------------------------------------------------------------------------- #
 # This program is free software; you can redistribute and/or modify it      #
 # under the terms of the GNU General Public License (see doc/LICENSE)       #
 #############################################################################

 /* $Id$ */

require ("imdb_person.class.php");

$person = new imdb_person ($_GET["mid"]);

if (isset ($_GET["mid"])) {
  $pid = $_GET["mid"];
  $person->setid ($pid);

  echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>\n";
  echo "<HTML><HEAD>\n <TITLE>".$person->name()."</TITLE>\n";
  echo " <STYLE TYPE='text/css'>body,td,th { font-size:12px; font-family:sans-serif; }</STYLE>\n";
  echo "</HEAD>\n<BODY>\n<TABLE BORDER='1' ALIGN='center' STYLE='border-collapse:collapse'>";

  # Name
  echo '<TR><TH COLSPAN="3" STYLE="background-color:#ffb000">';
  echo $person->name()."</TH></tr>\n";
  flush();

  # Photo
  echo '<TR><TD rowspan="28" valign="top">';
  if (($photo_url = $person->photo_localurl() ) != FALSE) {
    echo '<div align="center"><img src="'.$photo_url.'" alt="Cover"></div>';
  } else {
    echo "No photo available";
  }

  # Birthday
  $birthday = $person->born();
  if (!empty($birthday)) {
    echo "<div align='center' style='font-size:10px;'>".$person->name()."<br><b>&#9788;</b> ".$birthday["day"].".".$birthday["month"]." ".$birthday["year"];
    if (!empty($birthday["place"])) echo "<br>in ".$birthday["place"];
    echo "</div>";
  }

  # Death
  $death = $person->died();
  if (!empty($death)) {
    echo "<div align='center' style='font-size:10px;'><b>&#8224;</b> ".$death["day"].".".$death["month"]." ".$death["year"];
    if (!empty($death["place"])) echo "<br>in ".$death["place"];
    if (!empty($death["cause"])) echo "<br>Cause: ".$death["cause"];
    echo "</div>";
  }

  // This also works for all the other filmographies:
  $ff = array("producer","director","actor","self");
  foreach ($ff as $var) {
    $fdt = "movies_$var";
    $filmo = $person->$fdt();
    $flname = ucfirst($var)."s Filmography";
    if (!empty($filmo)) {
      echo "</TD><TD><b>$flname:</b> </td><td>\n";
      echo "<table align='left' border='1' style='border-collapse:collapse;background-color:#ddd;'><tr><th style='background-color:#07f;'>Movie</th><th style='background-color:#07f;'>Character</th></tr>";
      foreach ($filmo as $film) {
        echo "<tr><td><a href='imdb.php?mid=".$film["mid"]."'>".$film["name"]."</a>";
        if (!empty($film["year"])) echo " (".$film["year"].")";
        echo "</td><td>";
        if (empty($film["chname"])) echo "&nbsp;";
        else {
          if (empty($film["chid"])) echo $film["chname"];
          else echo "<a href='http://".$person->imdbsite."/character/ch".$film["chid"]."/'>".$film["chname"]."</a>";
        }
        echo "</td></tr>";
      }
      echo "</table></TD></TR>\n";
    }
  }

  echo '</TABLE><BR>';
}
?>
