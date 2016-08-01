<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel=stylesheet href="reset.css" type="text/css" media=screen>
    <link rel=stylesheet href="article.css" type="text/css" media=screen>
    <title> Sound Perceptual Study</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
    </script>
    <script src="survey.js"> </script>
  </head>
  <body>
    <div id=container>
      <div id=article>
        <div id=header>
          <h1>Match What You See with What You Hear</h1>
          <hr>
        </div>
        <?php
// Declare experiment type. We had two different experiments. This also
// determines the directory for outputting data: ./data/4AFC/ or ./data/2AFC/
$experiment = "2AFC";
$experiment = "4AFC";
$experiment = "MANYCHOICE";
if(isset($_GET["experiment"]))
{
  $experiment = $_GET["experiment"];
}
// FORCE RANDOM
//if(rand(0,1))
//{
//  $experiment="4AFC";
//}else
//{
//  $experiment="2AFC";
//}
        ?>


        <button id=hide_example onclick="hide_examples();" style="display:none;">Hide instructions</button>
        <button id=show_example onclick="show_examples();" style="display:none;">Show instructions</button>
        <div id=example_container>
        <!-- 
        <hr>
        -->

        <!--<div style="display:none;" id=question_container>-->

        <!--
        -->
        <h3>Requirements:</h3>
        <ul>
          <li>This study will take appromixately <strong>25 minutes</strong>. It should be accomplished in one sitting: all data will be submited only upon completion.</li>
          <li>Please do not refresh the page or use the forward/back buttons before finishing the study.</li>
          <li>Please do not use laptop speaker. Use a headphone if possible, otherwise, a earphone is also acceptable. </li>
        </ul>

        <h3>Instructions:</h3>
        <br>
        In each of the following pages:
          <ol>
          <li> Listen to the sound.</li>
          <li> Press the button to hear it again, as many times as you like. </li>
          <li> As soon as you are able, click on the label that best corresponds to the sound. </li>
          <li> Press the "Next" button. </li>
        </ol>
        <ul>  <li> You will be timed, so please do not take a break. </li> </ul>

        <hr>
        </div>

        <form action="./submit.php" method=POST name=survey>
          <input type=hidden name=experiment value=<?php print $experiment;?>>
          <input type=hidden name=unique_id value=<?php print uniqid();?>>
          <input type="hidden" id="UserAgent" name="User Agent" value="">
          <script type="text/javascript">
          $('#UserAgent').val(navigator.userAgent);
          </script>
          <div id=demographic_container style="display:none;">
          <h3>First, please tell us about yourself.</h3><br>
            <table class=no_border>
              <tr><td>Age:</td><td><select name="age" onchange="check_question(this);">
                <option value=""></option>
                <?php 
for($value = 18; $value <= 100; $value++){ 
  echo('<option value="' . $value . '">' . $value . '</option>');
}
                ?>
              </select></td></tr>
              <tr><td>Gender:</td><td><select name=gender onchange="check_question(this);">
                <option value=""></option>
                <option value=male>male</option>
                <option value=female>female</option>
                <option value=other>other</option>
              </select></td></tr>
            </table>
            <hr>
          </div>


        <?php
//$drawings = array("lion");//,"rings","snake");
$drawings = array("aluminium","candywrapper","plasticbag","plasticbottle","sodacan");//,"rings","snake");
$lightings = array("recorded","full", "simple");
$num_lightings = $lightings.length;

//$methods = array("real","ink","lumo","inf");
//$methods = array("recorded","simple","full");
$methods = array("Aluminium Foil","Soda Can","Plastic Bag", "Plastic Bottle", "Candy Wrapper");

// create all possible combos: 3*2*2*3
// Each entry looks like:
//   ['drawing_name','lighting_char',view_num,['cand0',...,'cand3']]
$combos = array();

switch($experiment)
{
   case "MANYCHOICE":
    $n_choose_k = array(array(0,1,2,3,4));
    //$lightings = array("s","d");
    $views = array("1");//,"2");
    $num_repititions = 4;
    break;
  case "4AFC":
    $n_choose_k = array(array(0,1,2,3));
    $lightings = array("s","d");
    $views = array("1");//,"2");
    $num_repititions = 2;
    break;
  case "2AFC":
    $n_choose_k = array(
      array(0,1),
      array(0,2),
      array(0,3),
      array(1,2),
      array(1,3),
      array(2,3));
    $views = array("1");
    $lightings = array("s","d");
    $num_repititions = 1;
    break;
}

for($r = 0;$r<$num_repititions;$r++)
{
  foreach($drawings as $drawing)
  {
    foreach($lightings as $lighting)
    {
      foreach($views as $view)
      {
        foreach($n_choose_k as $set)
        {
          $methods_copy = array();
          foreach($set as $item)
          {
            $methods_copy[] = $methods[$item];
          }
          shuffle($methods_copy);
          $combos[] = array($drawing,$lighting,$view,$methods_copy);
        }
      }
    }
  }
}

// randomly order
shuffle($combos);

foreach($combos as $ques_i=>$combo)
{
  $drawing = $combo[0];
  $lighting = $combo[1];
  $view = $combo[2];
  $methods = $combo[3];
  print "
    <div class=question id=question$ques_i style='display:none;'>
    <hr>
            <div class=which>
               </div>
            <audio id=audio$ques_i controls>
              <source src=\"sounds/{$drawing}_$lighting.wav\" type=\"audio/mpeg\">
              Your browser does not support the audio element.
            </audio>
            <div class=candidates_container>
            <input type=hidden name=timing$ques_i id=timing$ques_i value=".Mickey.">
            <input type=hidden name=alternatives$ques_i value=".join('-',$methods).">
";
  foreach($methods as $meth_i=>$method)
  {
    $str = $drawing.$view."_".$method."_".$lighting;
    $name=str_replace(' ', '_', $method);
    print
"              <div class=figure>
                <label for=question$ques_i"."candidate".$meth_i.">
                  <img class=candidate src=./sound_images/".$name.".jpg alt=''><br>
                  <input type=radio value=$drawing.$lighting.$name name=question$ques_i id=question".$ques_i."candidate".$meth_i.">
                  $method
                </label>
              </div>";
    if($meth_i % 2 ==1)
    {
      //print "<br>";
    }
    print "
";
  }

$num_lightings = count($lightings);
$num_drawings = count($drawings);
$base_index = $num_lightings * $num_drawings * $num_repititions;
$num_repititions_2 = 4;
$total_index = sizeof($combos) + 5*3*$num_repititions_2;
print
"          </div>
          </div>";
}

$num_repititions = $num_repititions_2;
?>

<?php
print "
  <div class=question id=question$base_index style='display:none;'>
    <hr>
            <div class=which>
         <h3>Instructions:</h3>
        <br>
        In each of the following pages:
          <ol>
          <li> Listen to the sound.</li>
          <li> Press the button to hear it again, as many times as you like. </li>
          <li> Does this sound correspond to the object in the image? Please rate your response from 1 to 7.  </li>
          <li> Press the \"Next\" button. </li>
        </ol>
        <ul>  <li> You will be timed, so please do not take a break. </li> </ul>

           <div class=candidates_container>
            <input type=hidden name=timing$base_index id=timing$base_index value=Mickey>
                  <br><br><br>
                  <input type=radio value=$total_index name=question$base_index id=question{$base_index}candidate0>
                  <input type=hidden value=$total_index name=instructions id=question{$base_index}candidate0>
                  Yes, I have read and understand the instructions.
            </div>
            </div>
  </div>"

?>

<?php



  
$lightings = array("Aluminium Foil","Soda Can","Plastic Bag", "Plastic Bottle", "Candy Wrapper");
#$drawings = array("aluminium","candywrapper","plasticbag","plasticbottle","sodacan");//,"rings","snake");
$drawings= array("recorded","full", "simple");
$methods= array("recorded","full", "simple");


$num_lightings = count($lightings);
$num_drawings = count($drawings);


#$methods = array("simplified","full","Plastic Bag", "Plastic Bottle", "Candy Wrapper");

$experiment = "LIKERT";
$combos = array();

switch($experiment)
{
   case "LIKERT":
    $n_choose_k = array(array(0));
    #$lightings = array("recorded","full");
    $views = array("1");//,"2");
    break;
   case "MANYCHOICE":
    $n_choose_k = array(array(0,1,2,3,4));
    //$lightings = array("s","d");
    $lightings = array("recorded","full");
    $views = array("1");//,"2");
    $num_repititions = 1;
    break;
  case "4AFC":
    $n_choose_k = array(array(0,1,2,3));
    $lightings = array("s","d");
    $views = array("1");//,"2");
    $num_repititions = 2;
    break;
  case "2AFC":
    $n_choose_k = array(
      array(0,1));
    $views = array("1");
    $lightings = array("s");//,"d");
    $num_repititions = 1;
    break;
}

for($r = 0;$r<$num_repititions;$r++)
{
  foreach($drawings as $drawing)
  {
    foreach($lightings as $lighting)
    {
      foreach($views as $view)
      {
        foreach($n_choose_k as $set)
        {
          $methods_copy = array();
          foreach($set as $item)
          {
            $methods_copy[] = $methods[$item];
          }
          shuffle($methods_copy);
          #$lighting = "recorded";
          #$drawing = "1";
          $combos[] = array($drawing,$lighting,$view,$methods_copy);
        }
      }
    }
  }
}

// randomly order
shuffle($combos);

foreach($combos as $ques_i=>$combo)
{
  $ques_ii = $ques_i + $base_index + 1;
  $drawing = $combo[0];
  $lighting = $combo[1];
  $view = $combo[2];
  $methods = $combo[3];
  $name=str_replace(' ', '_', $lighting);
  $audioname = "";
  if ($lighting == "Plastic Bag")
    $audioname = "plasticbag";
  if ($lighting == "Plastic Bottle")
    $audioname = "plasticbottle";
  if ($lighting == "Soda Can")
    $audioname = "sodacan";
  if ($lighting == "Aluminium Foil")
    $audioname = "aluminium";
  if ($lighting == "Candy Wrapper")
    $audioname = "candywrapper";
  print "<div class=question id=question$ques_ii style='display:none;'>
    <hr>
    <div class=which>
               </div>
            <div class=candidates_container>
            <input type=hidden name=timing$ques_ii id=timing$ques_ii value=".Mickey.">
            <img class=candidate src=./sound_images/".$name.".jpg alt=''><br>
            <audio id=audio$ques_ii controls>
              <source src=\"sounds/{$audioname}_{$drawing}.wav\" type=\"audio/mpeg\">
              Your browser does not support the audio element.
            </audio>
            <div style=\"margin: 0px auto;\">Does this sound correspond to the object in the image?</div>
            <input type=hidden name=alternatives$ques_ii value=".join('-',$methods).">
";

print "<hr><table class=\"likert-table\" style=\"margin: 0px auto;\">
        <tr>
            <td>Not at all</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Definitely Yes</td>
        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
        </tr>
        <tr>
            <td><input type=\"radio\" value=\"$drawing.$audioname.1\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.2\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.3\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.4\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.5\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.6\" name=\"question$ques_ii\"/></td>
            <td><input type=\"radio\" value=\"$drawing.$audioname.7\" name=\"question$ques_ii\"/></td>
        </tr>
</table>";

print
"          </div>
<!--
          <p style='text-align:left;'>Question ".($ques_ii)." of ".$total_index."</p>
-->
          </div>";
}
        ?>


        </form>

        <div id=submit_container>
          <button id=begin onclick="begin_questionnaire();" disabled>Begin</button>
          <button style="display:none;" id=begin_survey onclick="begin_survey();">Next</button>
          <button style="display:none;" id=next onclick="next_question();">Next</button>
          <button style="display:none;" id=finish onclick="finish();">Finish!</button>
        </div>

      </div>
    </div>
  </body>
</html>
