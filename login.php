<!doctype html>
<html lang="en">
<body>
	<?php 
		include('header.php');
		require('db-connect.php');
		session_start();

	?>
	<h1 style="text-align:center">Login Page</h1>

	<div class="container">
			<form action="login.php" method="POST">

				<div class="form-group">
					<label for="email">Email</label>
					<input name="email" type="text" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
				</div>

				<div class="dropdown">
						<label for="accountType">Account Type</label>
						<select id="accountType" name="accountType" class="form-control">
								<option value="Customers">Customer</option>
								<option value="Drivers">Driver</option>
								<option value="RestarantOwners">Restaurant Owner</option>
						</select> 
				</div>

				<button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Sign in</button>
			</form>
			<a href="#" class="mt-3 mb-3">Forgot password?</a>
			<a href="./signup.php" class="mb-3">Create an account</a>
	</div>
	<?php 
		require('footer.php');
	?>
</body>
	<?php 
		global $pdo; // pdo is from db-connect.php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') // only on form submission perform below
		{   	  
			try {
				// get post data and save them to variables
				$email = $_POST['email'];
				$password = $_POST['password'];
				$accountType = $_POST['accountType'];
				$query = "";

				// update queries based on type of user (since different users stored in different tables)
				// TODO: signup and login process for drivers and restaurant owners
				if ($accountType == "Customers") {
					$query = "SELECT * FROM `$accountType` WHERE c_email=:email";
				} elseif ($accountType == "Drivers") {
					$query = "SELECT * FROM `$accountType` WHERE d_email=:email";
				} else {
					$query = "SELECT * FROM `$accountType` WHERE r_email=:email";
				}

				// prepare query against sql injections
				$statement = $pdo->prepare($query);
				$statement->bindValue(':email', $email);
				$statement->execute();
				
				$result = $statement->fetch();
				$statement->closeCursor();
		
				// if not a matching password display error message
				if($result['c_password'] != $password)
				{
					echo "<div class='alert alert-danger' role='alert'>" . "Unable to login" . "</div>";
				}
				else
				{				
					// matching data, set session data to store first name and display on index page	
					$_SESSION['firstName'] = $result['c_firstName'];
					// redirect to index page
					echo("<script>location.href = 'index.php';</script>");
				}

			} catch (PDOException $e) {
				echo $e;
			}
		}	
	?>
</html>

		