<!DOCTYPE html>
<html>
  <head>
    <link rel=stylesheet href="reset.css" type="text/css" media=screen>
    <link rel=stylesheet href="article.css" type="text/css" media=screen>
    <title>SOUND SOUND - Perceptual Study</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
    </script>
    <script src="survey.js"> </script>
  </head>
  <body>
    <div id=container>
      <div id=article>
        <div id=header>
          <h1>Evaluate Recognition of Crumpling Sounds</h1>
          <hr>
        </div>
        <?php
// Declare experiment type. We had two different experiments. This also
// determines the directory for outputting data: ./data/4AFC/ or ./data/2AFC/
$experiment = "2AFC";
$experiment = "4AFC";
$experiment = "MANYCHOICE"
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


        <button id=hide_example onclick="hide_examples();" style="display:none;">Hide examples</button>
        <button id=show_example onclick="show_examples();" style="display:none;">Show examples</button>
        <div id=example_container>
        <p>Here are two examples of drawings and images where 3D
        effects have been added. In the following study, I will
        ask you which image of several, in your opinion, has the best 3D
        effects. Please take a moment to study these examples before
        continuing. Notice how the 3D images incorporate shading, shadows,
        lighting and reflectance to give a feeling of depth.</p>
        <!-- <p class=light>The same question may appear multiple times; please try to answer consistently</p>-->
        <table>
          <tr>
            <td><div class=figure><img height=260 src="./study_images/lion2_drawing.jpg" alt="Lion drawing"   ><br>Original drawing</div></td>
            <td><div class=figure><img height=260 src="./study_images/lion2_real_d.jpg" alt="Lion rendering"  ><br>Drawing + 3D effects</div></td></tr>
          <tr>
            <td><div class=figure><img height=260 src="./study_images/rings1_drawing.jpg" alt="Rings drawing" ><br>Original drawing</div></td>
            <td><div class=figure><img height=260 src="./study_images/rings1_real_s.jpg" alt="Rings rendering"><br>Drawing + 3D effects</div></td></tr>

        </table>
        <hr>
        </div>


        <!--<div style="display:none;" id=question_container>-->

        <!--
        -->
        <h3>Requirements checklist</h3>
        <ul>
          <li>This study will take an estimated <strong>15 minutes</strong>. It should be accomplished in one sitting: all data will be submited only upon completion.</li>
          <li>Please do not refresh the page or use the forward/back buttons before finishing the study.</li>
        </ul>

        <form action="./submit.php" method=POST name=survey>
          <input type=hidden name=experiment value=<?php print $experiment;?>>
          <input type=hidden name=unique_id value=<?php print uniqid();?>>
          <input type="hidden" id="UserAgent" name="User Agent" value="">
          <script type="text/javascript">
          $('#UserAgent').val(navigator.userAgent);
          </script>
          <div id=demographic_container style="display:none;">
          <h3>First, please tell us about yourself.</h3>
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
              <tr><td>Computer graphics experience:</td><td>
              <select name=experience onchange="check_question(this);">
                <option value=""></option>
                <option value="none">none</option>
                <option value="basic">basic</option>
                <option value="intermediate">intermediate</option>
                <option value="advanced">advanced</option>
              </select></td></tr>
            </table>
            <hr>
          </div>


        <?php
$drawings = array("lion");//,"rings","snake");
$num_lightings = $lightings.length;

$methods = array("real","ink","lumo","inf");

// create all possible combos: 3*2*2*3
// Each entry looks like:
//   ['drawing_name','lighting_char',view_num,['cand0',...,'cand3']]
$combos = array();

switch($experiment)
{
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
  print "<div class=question id=question$ques_i style='display:none;'>
            <div class=which>
               We are trying to add 3D effects to this drawing.<br>
               Which of the following images below has the most correct appearance?
            </div>
            <img class=drawing
              src=./study_images/".$drawing.$view."_drawing.jpg alt=''>
            <br>
            <div class=candidates_container>
            <input type=hidden name=timing$ques_i id=timing$ques_i value=".Mickey.">
            <input type=hidden name=alternatives$ques_i value=".join('-',$methods).">
";
  foreach($methods as $meth_i=>$method)
  {
    $str = $drawing.$view."_".$method."_".$lighting;
    print
"              <div class=figure>
                <label for=question$ques_i"."candidate".$meth_i.">
                  <img class=candidate src=./study_images/".$str.".jpg alt=''><br>
                  <input type=radio value=$str name=question$ques_i id=question".$ques_i."candidate".$meth_i.">
                  This image
                </label>
              </div>";
    if($meth_i % 2 ==1)
    {
      print "<br>";
    }
    print "
";
  }

print
"          </div>
          <p style='text-align:left;'>Question ".($ques_i+1)." of ".sizeof($combos)."</p>
          </div>";
}
        ?>
        </form>
        <!-- <p class=light>The same question may appear multiple times; please try to answer consistently</p> -->

        <div id=submit_container>
          <button id=begin onclick="begin_questionnaire(); disabled">Begin</button>
          <button style="display:none;" id=begin_survey onclick="begin_survey();">Next</button>
          <button style="display:none;" id=next onclick="next_question();">Next</button>
          <button style="display:none;" id=finish onclick="finish();">Finish!</button>
        </div>

      </div>
    </div>
  </body>
</html>
