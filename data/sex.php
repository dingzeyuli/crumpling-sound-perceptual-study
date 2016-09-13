<!DOCTYPE HTML>
<html>
  <head>
    <link rel=stylesheet href="../reset.css" type="text/css" media=screen>
    <link rel=stylesheet href="../article.css" type="text/css" media=screen>
    <title>Sound - Results overview</title>
  </head>
  <body>
    <div id=container>
      <div id=article>
        <?php
$experiments = array("4AFC","2AFC");
$experiments = "MANYCHOICE";

//$it = new RegexIterator(new DirectoryIterator("."), "/\\.txt\$/i" );
$it = glob('MANYCHOICE/*.txt');

//ARRAY
/// 0: alum
// 1: candy
// 2: bag
// 3: bottle
// 4: soda

//print "0: alum foil; 1: candy; 2: bag; 3: bottle; 4: soda<br><br>";

$num_files = 0.0;
$num_males = 0.0;
$num_females = 0.0;
$ages = 0.0;
foreach ($it as $filename) {
  //print $filename;
  $num_files ++;
  $file = $filename;
  $current = file_get_contents($file);
  $json_a = json_decode($current);
  
  $total_index = $json_a->{"instructions"};
  $data = "";

  $sex = $json_a->{"gender"};
  if ($sex=="male")
  {
    $num_males ++;
  }
  else
  {
    $num_females ++;
  }

  $age = $json_a->{"age"};
  $ages += (float) $age;
  print $age."<br>";
}

print $num_males."<br>";
print $num_females."<br>";
print $ages/$num_files."<br>";

/*
foreach($experiments as $experiment)
{
  print "
           <h3 id=$experiment>$experiment</h3>
";
  $num_subjects = trim(`ls $experiment/*.txt | wc -l`);
  
  if($experiment=="4AFC")
  {
    $real = floatval(trim(`grep -o _real $experiment/*.txt | wc -l`));
    $ink =  floatval(trim(`grep -o _ink  $experiment/*.txt | wc -l`));
    $lumo = floatval(trim(`grep -o _lumo $experiment/*.txt | wc -l`));
    $inf =  floatval(trim(`grep -o _inf  $experiment/*.txt | wc -l`));
    $total = $real+$ink+$lumo+$inf;
    print "
             <p>There have been <strong>$total</strong> total questions asked.
             Counts of total comparison wins:</p>
             <table>
               <tr><td align=right>Real: </td><td>$real</td></tr>
               <tr><td align=right>Ink: </td><td>$ink</td></tr>
               <tr><td align=right>Lumo: </td><td>$lumo</td></tr>
               <tr><td align=right>Inf: </td><td>$inf</td></tr>
             </table>";
    print "
             <img
               src=https://chart.googleapis.com/chart?cht=bvg&chs=250x300&chd=t:".
         intval(($real/$total)*100).",".
         intval(($ink/$total)*100 ).",".
         intval(($lumo/$total)*100).",".
         intval(($inf/$total)*100 ).
         "&chco=4D89F9&chbh=20,1,30&chxt=x,y&chxl=0:|real|ink|lumo|inf>
    ";
  }else
  {
    $methods = array("real","ink","lumo","inf");
    $colors = array("FF5544","55FF44","5544FF","555555");
    print "<table style='text-align:center;'>";
    for($i = 0;$i<sizeof($methods);$i++)
    {
      print "<tr>";
      $mi = $methods[$i];
      for($j = $i+1;$j<sizeof($methods);$j++)
      {
        $mj = $methods[$j];
        $mi_vs_mj = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj)" 2AFC/* | wc -l`));
        $mi_over_mj = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj).,.question[0-9]*...[^_]*_$mi" 2AFC/* | wc -l`));
        $mj_over_mi = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj).,.question[0-9]*...[^_]*_$mj" 2AFC/* | wc -l`));
        print "<td>$mi ".$mi_over_mj." ".($mi_over_mj>$mj_over_mi?"&gt;":"&le;")." ".
        $mj_over_mi." $mj</td>";
      }
      print "</tr><tr>";

      for($j = $i+1;$j<sizeof($methods);$j++)
      {
        $mj = $methods[$j];
        $mi_vs_mj = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj)" 2AFC/* | wc -l`));
        $mi_over_mj = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj).,.question[0-9]*...[^_]*_$mi" 2AFC/* | wc -l`));
        $mj_over_mi = floatval(trim(`egrep -o ".($mj-$mi|$mi-$mj).,.question[0-9]*...[^_]*_$mj" 2AFC/* | wc -l`));
        print "<td>
          <img
          src=https://chart.googleapis.com/chart?cht=p&chs=265x200&chd=t:".
          intval(($mi_over_mj/$mi_vs_mj)*100).",".
          intval(($mj_over_mi/$mi_vs_mj)*100).
          "&chco=".$colors[$i]."|".$colors[$j].
          "&chl=$mi|$mj></td>";
      }
      print "</tr>";
    }
    print "</table>";
  }

  $none =         floatval(trim(`grep -o none          $experiment/*.txt | wc -l`));
  $basic =        floatval(trim(`grep -o basic         $experiment/*.txt | wc -l`));
  $intermediate = floatval(trim(`grep -o intermediate  $experiment/*.txt | wc -l`));
  $advanced =     floatval(trim(`grep -o advanced      $experiment/*.txt | wc -l`));
  $num_subjects = $none+$basic+$intermediate+$advanced;
  print "<p>There have been <strong>$num_subjects</strong> subjects.</p>";
  //         <table>
  //           <tr><td align=right>none: </td><td>$none</td></tr>
  //           <tr><td align=right>basic: </td><td>$basic</td></tr>
  //           <tr><td align=right>intermediate: </td><td>$intermediate</td></tr>
  //           <tr><td align=right>advanced: </td><td>$advanced</td></tr>
  //         </table>";
  print "<div style='color:#fff;background-color:#041;display:inline-block;padding-bottom:10px;padding-top:10px;width:".intval(800*($none/$num_subjects))."px;text-align:center;'>None: ".$none."</div>";
  print "<div style='color:#000;background-color:#093;display:inline-block;padding-bottom:10px;padding-top:10px;width:".intval(800*($basic/$num_subjects))."px;text-align:center;'>Basic: ".$basic."</div>";
  print "<div style='color:#000;background-color:#0b4;display:inline-block;padding-bottom:10px;padding-top:10px;width:".intval(800*($intermediate/$num_subjects))."px;text-align:center;'>Inter.: ".$intermediate."</div>";
  print "<div style='color:#000;background-color:#0e6;display:inline-block;padding-bottom:10px;padding-top:10px;width:".intval(800*($advanced/$num_subjects))."px;text-align:center;'>Adv.: ".$advanced."</div>";
  //print "
  //         <img
  //           src=https://chart.googleapis.com/chart?cht=p3&chs=400x200&chd=t:".
  //     $none.",".
  //     $basic.",".
  //     $intermediate.",".
  //     $advanced.
  //     "&chco=000000|558888|66aaaa|77dddd&chl=none|basic|int.|adv.>
  //";
  print "<hr>";
}
*/
    ?>
        </div>
      </div>
  </body>
</html>
