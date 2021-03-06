<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php'); 
    require('db-connect.php');
    function selectUserData($column) {
        // function to get the desired user data to autofill in the form
        global $pdo;
        $user_ID = $_SESSION['user_ID'];
        try {
          $query = "
            SELECT $column FROM Users WHERE user_ID='$user_ID';
          ";
          $statement = $pdo->prepare($query);
          $statement->execute();
          $results = $statement->fetchAll();
          $statement->closecursor();
          return $results;
  
        } catch (PDOException $e) {
          echo($e->getMessage());
        }
      }
      function selectDriverData($column) {
        // function to get the desired user data to autofill in the form
        global $pdo;
        $user_ID = $_SESSION['user_ID'];
        try {
          $query = "
            SELECT $column FROM Drivers WHERE user_ID='$user_ID';
          ";
          $statement = $pdo->prepare($query);
          $statement->execute();
          $results = $statement->fetchAll();
          $statement->closecursor();
          return $results;
  
        } catch (PDOException $e) {
          echo($e->getMessage());
        }
      }
  ?>

  <div class="container">
      <h1 class="text-center">Edit Driver Account</h1>
      <form action="edit-driver.php" method="POST">

        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstName"
          value="<?php 
              echo(selectUserData('first_name')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastName"
          value="<?php 
              echo(selectUserData('last_name')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email"
          value="<?php 
              echo(selectUserData('email')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">
          <label for="password">Password (at least 8 characters)</label>
          <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="form-group">  
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" class="form-control" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}"
          value="<?php 
              echo(selectUserData('phone_number')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="time_worked">Time Worked</label>
          <input type="number" id="time_worked" class="form-control" name="time_worked" pattern="/^[+-]?((\d+(\.\d*)?)|(\.\d+))$/"
          value="<?php 
              echo(selectDriverData('time_worked')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="salary">Salary</label>
          <input type="number" id="salary" class="form-control" name="salary" pattern="/^[+-]?((\d+(\.\d*)?)|(\.\d+))$/"
          value="<?php 
              echo(selectDriverData('salary')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="dropdown">
			<label for="rating">Rating</label>
			<select id="rating" name="rating" class="form-control">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
			</select> 
		</div>

        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Edit Account</button>
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
          $rating = $_POST['rating'];
          $time_worked = $_POST['time_worked'];
          $user_ID = $_SESSION['user_ID'];

          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
          if (!preg_match("#[0-9]+#", $password)) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Password must have at least 1 number" . "</div>";
          }
          if (!preg_match("#[a-zA-Z]+#", $password)) 
          {
            echo "<div class='alert alert-danger' role='alert'>" . "Password must have at least 1 letter" . "</div>";
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
                try {
                  // initialize the query to insert the new driver into the Driver database
                  // Check the database to see if the email is already existed. If yes, then we cannot allow users to register
                  $query1 = "UPDATE Users SET
                  email=:email, password=:password, phone_number=:phone_number, 
                  first_name=:first_name, last_name=:last_name, user_type=:user_type
                  WHERE `user_ID`='$user_ID';";

                  $query2 = "UPDATE Drivers SET
                  time_worked=:time_worked, salary=:salary, d_rating=:d_rating
                  WHERE `user_ID`='$user_ID';";
                  $statement = $pdo->prepare($query1);
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $hashed_password);
                  $statement->bindValue(':phone_number', $phone);
                  $statement->bindValue(':user_type', 'driver');
                  $statement->execute();
                  $statement->closeCursor();
                  
                  $statement = $pdo->prepare($query2);
                  $statement->bindValue(':time_worked', $time_worked);
                  $statement->bindValue(':salary', $salary);
                  $statement->bindValue(':d_rating', $rating);
                  $statement->execute();
                  $statement->closeCursor();

                  $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
                  $_SESSION['user_type'] = 'driver';
                  // redirect to index.php after successful account creation
                  echo("<script>location.href = 'index.php';</script>");
                  // echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
                  
                } catch (PDOException $e) {
                  echo($e->getMessage());
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to edit driver account" . "</div>";
                } 

              }
              
          }
        
    ?>

</html>

    