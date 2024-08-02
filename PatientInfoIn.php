<?php
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['DoctorID'])) {
  header("Location: login.php");
  exit();
}
?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Patient Info</title>
  <link rel="icon" href="imgs/drcare.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
  <link rel="stylesheet" href="styles/prescription.css">
  <link rel="stylesheet" href="styles/style.css">
</head>

<body>
  <?php include('header.php'); ?>
  <script type="text/javascript" src="js/light-dark.js"></script>

  <div class="container my-5">
    <header class="d-flex justify-content-between my-4">
      <div>
        <a href="index.php" class="btn btn-primary back-btn" style="margin-top: 100px;">Back</a>
      </div>
    </header>

    <div class="wholeform">
      <form id="PatientInfo" name="PatientInfo" action="process.php" method="post" class="row g-3 needs-validation" novalidate>
        <h1>Patient Info</h1>

        <div class="name">

        <!-- First Name -->

        <div class="form-floating mb-3">
          <input type="text" class="form-control pname" name="firstname" placeholder="Enter Your First Name" id="validationCustom01" required>
          <label for="floatingInput">First Name</label>
          <div class="invalid-feedback"> Should Enter the First Name! </div>
        </div>

        <span style="margin-left: 1cm"></span>

        <!-- Second Name -->


        <div class="form-floating mb-3">
          <input type="text" class="form-control pname" name="lastname" placeholder="Enter Your Last Name" id="validationCustom02" required>
          <label for="floatingInput">Last Name</label>
          <div class="invalid-feedback"> Should Enter the Last Name! </div>
        </div>

        </div>

        <!-- Email -->

        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" placeholder="Enter Your Email" id="validationCustom03" required>
          <label for="floatingInput" class="lable">Email address</label>
          <div class="invalid-feedback"> Should be a Valid Email! </div>
        </div>

        <!-- Gender -->

        <div class="form-select">
          <select name="gender" class="form-control" id="validationCustom05" required>
            <option selected disabled value="">Select Gender:</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
          <div class="invalid-feedback"> Please select a valid Gender. </div>
        </div>

        <!-- Phone Number -->

        <span class="number">

          <label for="floatingInput">Phone Number : </label>
          <input type="tel" class="telnumber" id="validationCustom04" name="contactnumber" placeholder="0778915586" pattern="[0-9]{10}" minlength="10" maxlength="10" required>
          <div class="invalid-feedback"> Enter the Phone Number ( 0778915586 )</div>
        </span>

        <!-- DOB -->

        <span class="DOB">
          <label for="floatingInput">Date Of Birth : </label>
          <input class="dateinput" type="date" name="date" id="validationCustom04" required>
          <div class="invalid-feedback"> Birth Date is Required! </div>
        </span>

        <!-- Diagnosis -->

        <div class="form-floating">
          <textarea class="form-control" name="Diagnosis" placeholder="Give the Diagnosis here" style="height: 200px"></textarea>
          <label for="floatingTextarea2">Diagnosis</label>
        </div>

        <!-- Medications -->

        <div class="form-floating" style="background-color: #434343; border-radius: 10px;">
          <div class="medicine">
            <section class="med_list"></section>
            <div id="add_med" data-toggle="tooltip" data-placement="right" title="Click anywhere on the blank space to add more.">Click to add Medications...</div>
          </div>
        </div>

        <input type="hidden" name="Medications" id="Medications">



        <!-- Submit -->

        <div class="">
          <input type="submit" name="create" value="Add Patient" class="button1">
        </div>

      </form>
    </div>
  </div>

  <script id="new_medicine" type="text/template">
    <div class="med">&#x26AB;<input class="med_name" data-med_id="{{ med_id }}" data-toggle="tooltip" title="Click to edit..." placeholder="Enter medicine name">
            <div class="med_name_action"><button class="btn btn-sm btn-success save" data-med_id="{{ med_id }}">Save</button><button class="btn btn-sm btn-danger cancel-btn">Cancel</button></div>
            <div class="schedual">
                <div class="sc_time folded"><select class="sc" data-med_id="{{ med_id }}"><option value="morning + noon + night" selected="">1+1+1</option><option value="morning + night">1+0+1</option><option value="noon + night">0+1+1</option><option value="morning">1+0+0</option><option value="night">0+0+1</option><option value="breakfast + lunch + supper + bedtime">1+1+1+1</option></select>
                    <div
                        class="med_when_action"><button class="btn btn-sm btn-success save" data-med_id="{{ med_id }}">&amp;check;</button></div>
            </div>
            <div class="taking_time select folded"><select class="meal" data-med_id="{{ med_id }}"><option value="After Meal" selected="">After Meal</option><option value="Before Meal">Before Meal</option><option value="Take any time">Take any time</option></select>
                <div class="med_meal_action"><button class="btn btn-sm btn-success save" data-med_id="{{ med_id }}">&amp;check;</button></div>
            </div>
        </div>
        <div class="med_footer">
            <div class="period folded">Take for<input class="med_period" type="text" data-med_id="{{ med_id }}" placeholder="? days/weeks...">
                <div class="med_period_action"><button class="btn btn-sm btn-success save" data-med_id="{{ med_id }}">&amp;check;</button></div><span class="date"></span></div>
            <div class="del_action"><button class="btn btn-sm btn-danger delete" data-med_id="{{ med_id }}"><i class="fa fa-trash" aria-hidden="true"></i></button></div>
        </div>
        <hr>
        </div>
    </script>

  <script>
    (() => {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.1/mustache.min.js"></script>
  <script src="js/prescription.js"></script>
  <?php include('footer.php'); ?>
</body>

</html>
<?php
ob_end_flush();
?>