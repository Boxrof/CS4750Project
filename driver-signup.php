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
          <label for="time_worked">Time Worked</label>
          <input type="number" id="time_worked" class="form-control" name="time_worked" pattern="/^[+-]?((\d+(\.\d*)?)|(\.\d+))$/">
        </div>

        <div class="form-group">  
          <label for="salary">Salary</label>
          <input type="number" id="salary" class="form-control" name="salary" pattern="/^[+-]?((\d+(\.\d*)?)|(\.\d+))$/">
        </div>

        <div class="dropdown">
			<label for="rating">Rating</label>
			<select id="rating" name="rating" class="form-control">
				<option value="*">1</option>
				<option value="**">2</option>
				<option value="***">3</option>
                <option value="****">4</option>
                <option value="*****">5</option>
			</select> 
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
          $rating = $_POST['rating'];
          $time_worked = $_POST['time_worked'];

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

              $res = $pdo->prepare("SELECT * FROM Users WHERE email=:email");
              $res->bindParam(":email", $email); // adding email variable to the where clause in SQL statement
              $res->execute();
              
              if ($res->rowCount() > 0) {
                // Already exist
                echo "<div class='alert alert-danger' role='alert'>" . "Email already exists" . "</div>";
              } else {
                try {
                  // initialize the query to insert the new driver into the Driver database
                  // Check the database to see if the email is already existed. If yes, then we cannot allow users to register
                  $query1 = "INSERT INTO Users (email, password, phone_number, first_name, last_name, user_type) 
                  VALUES (:email, :password, :phone, :first_name, :last_name, :user_type)";

                  $query2 = "INSERT INTO Drivers (user_ID, time_worked, salary, d_rating) 
                  VALUES (:user_ID, :time_worked, :salary, :rating)";
    
                  $statement = $pdo->prepare($query1);
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $password);
                  $statement->bindValue(':phone', $phone);
                  $statement->bindValue(':user_type', 'driver');
                  $statement->execute();
                  $statement->closeCursor();

                  $res = $pdo->prepare("SELECT * FROM Users WHERE email=:email");
                  $res->bindParam(":email", $email); // adding email variable to the where clause in SQL statement
                  $res->execute();
                  $result = $res->fetch();
                  $res->closeCursor();
                  $user_ID = $result['user_ID'];
                  
                  $statement = $pdo->prepare($query2);
                  $statement->bindValue(':user_ID', $user_ID);
                  $statement->bindValue(':time_worked', $time_worked);
                  $statement->bindValue(':salary', $salary);
                  $statement->bindValue(':rating', $rating);
                  $statement->execute();
                  $statement->closeCursor();

                  $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
                  $_SESSION['user_type'] = 'driver';
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

    