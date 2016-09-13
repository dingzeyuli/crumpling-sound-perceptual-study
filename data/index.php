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
        <h1>Results overview</h1>
        <?php
$experiments = array("4AFC","2AFC");
$experiments = "MANYCHOICE";

//$it = new RegexIterator(new DirectoryIterator("."), "/\\.txt\$/i" );
$it = glob('MANYCHOICE/*.txt');

//ARRAY
// 0: alum
// 1: candy
// 2: bag
// 3: bottle
// 4: soda

print "0: alum foil; 1: candy; 2: bag; 3: bottle; 4: soda<br><br>";

$r = array();
$f = array();
$s = array();
for ($i=0; $i<5; ++$i)
for ($j=0; $j<5; ++$j)
{
  $r[$i][$j] = 0;
  $f[$i][$j] = 0;
  $s[$i][$j] = 0;
}

$likert_r = array();
$likert_f = array();
$likert_s = array();
for ($i=0; $i<5; ++$i)
{
  $likert_r[$i] = 0;
  $likert_f[$i] = 0;
  $likert_s[$i] = 0;
}

$num_files = 0.0;
foreach ($it as $filename) {
  //print $filename;
  $num_files ++;
  $file = $filename;
  $current = file_get_contents($file);
  $json_a = json_decode($current);
  //$pairs = explode(":", file_get_contents($file));
  
  $total_index = $json_a->{"instructions"};
  //print $total_index."<br>";
  $data = "";
  for ($x = 0; $x < 60; $x++) {
    //print $x.": ".$json_a->{"question$x"}."<br>";
    $data = $json_a->{"question$x"};
    $i=0;
    $j=0;
    if (strpos($data, 'alum') !== FALSE){ $i = 0;}
    else if (strpos($data, 'candy') !== FALSE){ $i = 1;}
    else if (strpos($data, 'icbag') !== FALSE){ $i = 2;}
    else if (strpos($data, 'icbot') !== FALSE){ $i = 3;}
    else if (strpos($data, 'sodac') !== FALSE){ $i = 4;}
    
    if (strpos($data, 'Alum') !== FALSE){ $j = 0;}
    else if (strpos($data, 'Candy') !== FALSE){ $j = 1;}
    else if (strpos($data, 'ic_Bag') !== FALSE){ $j = 2;}
    else if (strpos($data, 'ic_Bot') !== FALSE){ $j = 3;}
    else if (strpos($data, 'Soda') !== FALSE){ $j = 4;}

    if (strpos($data, 'recorded') !== FALSE) { $r[$i][$j] ++;}
    if (strpos($data, 'full') !== FALSE) { $f[$i][$j] ++;}
    if (strpos($data, 'simple') !== FALSE) { $s[$i][$j] ++;}
  }

  for ($x=61; $x<=120; ++$x)
  {
    $data = $json_a->{"question$x"};
    $i=0;
    if (strpos($data, 'alum') !== FALSE){ $i = 0;}
    else if (strpos($data, 'candy') !== FALSE){ $i = 1;}
    else if (strpos($data, 'icbag') !== FALSE){ $i = 2;}
    else if (strpos($data, 'icbot') !== FALSE){ $i = 3;}
    else if (strpos($data, 'sodac') !== FALSE){ $i = 4;}

    $pieces = explode(".", $data);
    $rating =  (float) $pieces[2];

    if (strpos($data, 'recorded') !== FALSE) { $likert_r[$i] += $rating;}
    if (strpos($data, 'full') !== FALSE) { $likert_f[$i] += $rating;}
    if (strpos($data, 'simple') !== FALSE) { $likert_s[$i] += $rating;}
  }
}

print $num_files." participants submitted.<br><br>";

$num_correct=0;
for ($i=0; $i<5; ++$i)
{
  for ($j=0; $j<5; ++$j)
  {
    print $r[$i][$j]."  ";
  }
  $num_correct += $r[$i][$i];
  print "<br>";
}
print $num_correct/$num_files/20.0;

  print "<br>";
  print "<br>";

$num_correct=0;
for ($i=0; $i<5; ++$i)
{
  for ($j=0; $j<5; ++$j)
  {
    print $f[$i][$j]."  ";
  }
  $num_correct += $f[$i][$i];
  print "<br>";
}
print $num_correct/$num_files/20.0;
  print "<br>";
  print "<br>";


$num_correct=0;
for ($i=0; $i<5; ++$i)
{
  $num_correct += $s[$i][$i];
  for ($j=0; $j<5; ++$j)
  {
    print $s[$i][$j]."  ";
  }
  print "<br>";
}
print $num_correct/$num_files/20.0;

print "<br>";
print "<br>";

print "r: ".array_sum($likert_r)/count($likert_r)/4.0/$num_files." | ";
for ($i=0; $i<5; ++$i)
  print $likert_r[$i]/4.0/$num_files."  ";
print "<br>";
print "f: ".array_sum($likert_f)/count($likert_f)/4.0/$num_files." | ";
for ($i=0; $i<5; ++$i)
  print $likert_f[$i]/4.0/$num_files."  ";
print "<br>";
print "s: ".array_sum($likert_s)/count($likert_s)/4.0/$num_files." | ";
for ($i=0; $i<5; ++$i)
  print $likert_s[$i]/4.0/$num_files."  ";

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
