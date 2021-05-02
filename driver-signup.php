<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php'); 
    require('db-connect.php');
  ?>

  <div class="container">
      <h1 class="text-center">Create a Driver Account</h1>
      <form action="driver-signup.php" method="POST">

        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstName">
        </div>

        <div class="form-group">  
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastName">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email">
        </div>

        <div class="form-group">
          <label for="password">Password (at least 8 characters)</label>
          <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="form-group">  
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" class="form-control" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}">
        </div>

        <div class="form-group">  
          <label for="salary">Salary</label>
          <input type="number" id="salary" class="form-control" name="salary" pattern="/^[+-]?((\d+(\.\d*)?)|(\.\d+))$/">
        </div>

        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Create Account</button>
        <a href="./login.php" class="d-inline-block text-center mt-3 mb-3">Already have an account? Sign in</a>
      </form>
    </div>
    <?php require('footer.php'); ?>
</body>

    <style>
        .error {
            color: red;
            font-style: italic;
        }
    </style>

    <?php 
        global $pdo; // pdo is from db-connect.php

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {   	 
          // store post data into variables
          $firstName = $_POST['firstName'];
          $lastName = $_POST['lastName'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $phone = $_POST['phone'];
          $salary = $_POST['salary'];

          $num_errors = 0;

          // make sure all the data from the post is not empty
          // TODO: account for duplicate email addresses, don't want duplicates
          // TODO: more checking for username, maybe include numbers or special characters
          if(!$firstName)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your first name" . "</div>";
          }

          if(!$lastName)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your last name" . "</div>";
          }

          if(!$email)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter an email" . "</div>";
          }

          if(strlen($password) < 8)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Password must be at least 8 characters" . "</div>";
          }

          if(!$phone)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your phone number" . "</div>";
          }

          if (!$salary)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your salary" . "</div>";
          }



          if($num_errors == 0) // if all the above error checking for the form data passed
          {

              $res = $pdo->prepare("SELECT * FROM Drivers WHERE d_email=:email");
              $res->bindParam(":email", $email); // adding email variable to the where clause in SQL statement
              $res->execute();
              
              if ($res->rowCount() > 0) {
                // Already exist
                echo "<div class='alert alert-danger' role='alert'>" . "Email already exists" . "</div>";
              } else {
                try {
                  // initialize the query to insert the new driver into the Driver database
                  // Check the database to see if the email is already existed. If yes, then we cannot allow users to register
                  
                  $query = "INSERT INTO Drivers (d_firstName, d_lastName, d_email, d_password, d_phone_number, time_worked, salary) 
                    VALUES (:firstName, :lastName, :email, :password, :phone, :time_worked, :salary)";
    
                  $statement = $pdo->prepare($query);
                  // bind the form data to the sql query
                  $statement->bindValue(':firstName', $firstName);
                  $statement->bindValue(':lastName', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $password);
                  $statement->bindValue(':phone', $phone);
                  $statement->bindValue(':time_worked', 0.0);
                  $statement->bindValue(':salary', $salary);
                  $statement->execute();
                  
                  $statement->closeCursor();
                  $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
                  // redirect to index.php after successful account creation
                  echo("<script>location.href = 'index.php';</script>");
                  // echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
                  
                } catch (PDOException $e) {
                  echo($e->getMessage());
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to create account" . "</div>";
                }

              }
              
          }
        }	
    ?>

</html>

    