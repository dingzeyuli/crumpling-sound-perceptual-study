// number of milliseconds used to fade things in and out
var fade_ms = 200;

var cur_question_id = -1;
var next_question_id = -1;
var first_batch = 0;

var timer_click = -200;

//window.onbeforeunload = function() {
//    return "Data will be lost if you leave the page, are you sure?";
//};

function begin_questionnaire()
{
  $("#header").hide();
  $("#example_container").hide();
  $("#show_example").hide();
  $("#hide_example").hide();
  $("#begin").hide();
  $("#demographic_container").fadeIn(fade_ms);
  $("#begin_survey").show();
}

function begin_survey()
{
  if(!check_demographic_questionnaire())
  {
    return;
  }
  $("#header").hide();
  hide_examples();
  //generate_questions();
  $("#demographic_container").hide();
  $("#begin_survey").hide();
  $("#next").show();

  timer_click = new Date();
  next_question();
}

function check_response()
{
  return typeof $('input:radio[name=question'+cur_question_id+']:checked').val() !== 'undefined';
}

function next_question()
{
  if(cur_question_id>=0 && !check_response())
  {
    alert("Please answer the question.");
    return;
  }


  next_question_id = cur_question_id + 1;
  if (cur_question_id >=0 && cur_question_id != 60 && cur_question_id <= 120)
    $("#audio"+cur_question_id)[0].pause();
  if (next_question_id>= 0 && next_question_id != 60 && next_question_id <= 120)
    $("#audio"+ next_question_id)[0].play();

  var diff=new Date() - timer_click;
  timer_click = new Date();
  $("#timing"+(cur_question_id)).val(String(diff));

  $("#question"+cur_question_id).hide();
  cur_question_id++;
  $("#question"+cur_question_id).fadeIn(fade_ms);

  if($("#question"+(cur_question_id+1)).length === 0)
  {
    $("#next").hide();
    $("#finish").show();
  }
}

function finish()
{
  $("#question"+cur_question_id).hide();
  var diff=new Date() - timer_click;
  timer_click = new Date();
  $("#timing"+cur_question_id).val(String(diff));
  cur_question_id++;
  // submit the survey
  document.survey.submit();
  $("#thanks").fadeIn(fade_ms);
}

// Hide the examples container
function hide_examples()
{
  $("#example_container").hide();
  $("#show_example").show();
  $("#hide_example").hide();
}

// Hide the examples container
function show_examples()
{
  $("#example_container").fadeIn(fade_ms);
  $("#hide_example").show();
  $("#show_example").hide();
}


// Checks that each question in the demographics questionnaire has been
// answered. Highlights those that are not answered.
//
// Returns true only if each question is answered.
function check_demographic_questionnaire()
{
  var valid = true;
  // get form
  $.each($("#demographic_container :input"), function(index,select)
    {
      valid &= check_question(select);
    }
  );

  return valid;
}

// Helper for check_demographic_questionnaire
//
// Inputs:
//   select  DOM element for select of question
//
// Returns true only if question is answered.
function check_question(select)
{
  if(select.value == "")
  {
    select.className = "missing";
    select.parentNode.parentNode.className = "missing";
    return false;
  }else
  {
    select.className = "";
    select.parentNode.parentNode.className = "";
    return true;
  }
}

function generate_questions()
{
  var drawings = Array("lion","rings","snake");
  var num_drawings = drawings.length;
  var lightings = Array("s","d");
  var num_lightings = lightings.length;
  var num_views = 2;
  var num_repititions = 3;

  var methods = Array("real","ink","lumo","inf");
  var num_methods = methods.length;

  // create all possible combos: 3*2*2*3
  // Each entry looks like:
  //   ['drawing_name','lighting_char',view_num,['cand0',...,'cand3']]
  var combos = Array();
  for(var r = 0;r<num_repititions;r++)
  {
    for(var d = 0;d<num_drawings;d++)
    {
      for(var l = 0;l<num_lightings;l++)
      {
        for(var v = 0;v<num_views;v++)
        {
          combos.push([drawings[d],lightings[l],v+1,shuffle(methods.slice(0))]);
        }
      }
    }
  }

  // randomly order
  shuffle(combos);

  var num_combos = combos.length;
  for(var c = 0;c<num_combos;c++)
  {

  }



}

//  http://stackoverflow.com/a/2450976/148668
function shuffle(array) {
  var currentIndex = array.length
    , temporaryValue
    , randomIndex
    ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

// A countdown timer in jQuery:
// http://stackoverflow.com/questions/3089475/how-can-i-create-a-5-second-countdown-timer-with-jquery-that-ends-with-a-login-p
var kStudySeconds = 1;
//TODO
function update_primer_button( seconds )
{
  if( seconds == kStudySeconds )
  {
    $('#begin').html( 'Begin (study in ' + seconds + ' seconds)' );
  }
  else if( seconds > 1 )
  {
    $('#begin').html( 'Begin (study in ' + seconds + ' seconds)' );
  }
  else if( seconds == 1 )
  {
    $('#begin').html( 'Begin (study in 1 second)' );
  }
  else
  {
    $('#begin').html( 'Begin' );
    $('#begin').removeAttr( 'disabled' );
  }
}

var counter = kStudySeconds;
var countdown_timer = setInterval(
    function() {
    counter--;
    update_primer_button( counter );
    if( counter <= 0 )
    {
    clearInterval( countdown_timer );
    }
    },
    1000
    );
update_primer_button( counter );
