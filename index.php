<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QUIZ</title>
  <link href="assests/bootstrap.min.css" rel="stylesheet">
  <link href="assests/style.css" rel="stylesheet">
  <script src="assests/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="quiz-container" id="quiz">

    <div class="quiz-header text-center">
      <h2>Welcome!</h2>
      <div class="form-group">
        <label for="exampleFormControlInput1">Please enter your name</label>
        <input type="text" class="form-control" id="user-name" placeholder="Enter your name here.">
      </div>
      <button type="button" class="btn btn-primary btn-sm" id="start-quiz"><span id="start-quiz-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Start Quiz</button>
    </div>

  </div>

  <link rel="stylesheet" href="assests/toastr.min.css">
  <script src="assests/toastr.min.js"></script>
  <script src="assests/popper.min.js"></script>
  <script src="assests/bootstrap.min.js"></script>
  <script src="assests/main.js"></script>
</body>
</html>