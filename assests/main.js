
var userId = null;
var questionCount = 0;
var questions = null;

$('#start-quiz').click(function (e) { 
  let name = $("#user-name").val();
  if(name.trim() == '') {
    toastr.warning("Please enter your name.");
  } else {
    let jsonData = {
      name: name
    };

    $("#start-quiz-spinner").removeClass("d-none");
    $("#start-quiz").prop("disabled", true);

    $.ajax({
      url: 'addUser.php',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify(jsonData),
      success: function(response) {
          if(response.status == 1) {
            userId = response.id;
            getQuestions();
          } else {
            $("#start-quiz-spinner").addClass("d-none");
            $("#start-quiz").prop("disabled", false);
            toastr.warning(response.message);
          }
      },
      error: function(xhr, status, error) {
          $("#start-quiz-spinner").addClass("d-none");
          $("#start-quiz").prop("disabled", false);
          console.error(xhr.responseText);
      }
  });
  }
});

function getQuestions() {
  $.ajax({
      url: 'getQuestions.php',
      type: 'GET', 
      dataType: 'json', 
      success: function(data) {
        questionCount = data.data.length;
        questions = data.data;
        setQuestionsHTML(data.data);
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
      }
  });
}

function setQuestionsHTML(data) {
  let quesHtml = '';
  $.each(data, function(index, question) {
    var classs = 'd-none';
    if(index == 0) {
      classs = "";
    }
    quesHtml += `<div class="quiz-header `+classs+`" id="quiz-header-`+index+`">
    <h5 id="question-`
    +index+`">`+(index+1)+` . `+question.question+`</h5>
    <input hidden value="`+question.questionId+`" id="question-id-`+index+`">
    <input hidden value="" id="question-ans-`+index+`">
    <ul>`;

    $.each(question.ansOptions, function(index1, ansOption) {
      quesHtml += `<li>
      <input type="radio" name="answer-`+index+`" value="`+ansOption.ansOptionId+`" class="answer"/>
      <label>`+ansOption.ansOption+`</label>
    </li>`;
    });

    quesHtml += `</ul>
    <div class="text-center">
      <button type="button" class="btn btn-primary btn-sm ans-submit" data-id="`+index+`"><span class="spinner-border spinner-border-sm d-none" id="quiz-spinner-`+index+`"></span> Continue</button>
    </div>
    </div>`;
  });
  $('#quiz').html(quesHtml);
}

$(document).on('click', '.ans-submit', function(){ 
  let index = $(this).attr('data-id');
  var selectedValue = $('input[name="answer-'+index+'"]:checked').val();
  if (selectedValue == undefined) {
    toastr.warning("Please select an option.");
    return;
  }
  let qid = $('#question-id-'+index).val();
  let jsonData = {
    userId: userId,
    qId: qid,
    ansId: selectedValue
  };

  $.ajax({
      url: 'addAnswers.php',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify(jsonData),
      success: function(response) {
          if(response.status == 1) {
            $('#question-ans-'+index).val(response.id);
            questionCount
            let next = parseFloat(index)+1;
            if(next < questionCount) {
              $("#quiz-header-"+index).addClass("d-none");
              $("#quiz-header-"+next).removeClass("d-none");
            } else {
              $('.ans-submit').remove();
              $('#quiz').append(`<div class="text-center">
              <button type="button" class="btn btn-primary btn-sm mb-4" id="review-submit" ><span class="spinner-border spinner-border-sm d-none"></span> Submit</button>
            </div>`);
              $(".quiz-header").removeClass("d-none");
            }

          } else {
            toastr.warning(response.message);
          }
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
      }
  });

}); 

$(document).on('click', '#review-submit', function(){
  let array = [];
  for(let i=0; i<questionCount; i++) {
    let id = $('#question-ans-'+i).val();
    let ansId = $('input[name="answer-'+i+'"]:checked').val();
    let jsonData = {
      id: id,
      ansId: ansId
    };
    array.push(jsonData);
  }

  $.ajax({
      url: 'updateAnswers.php',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify(array),
      success: function(response) {
          if(response.status == 1) {
            
          } else {
            toastr.warning(response.message);
          }
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
      }
  });

});