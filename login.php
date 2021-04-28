<!doctype html>
<html lang="en">
<body>
	<?php 
		include('header.php');
		require('db-connect.php');
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
		include('footer.php');
	?>
</body>
	<?php 
		global $pdo;

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{   	  
			try {
				$email = $_POST['email'];
				$password = $_POST['password'];
				$accountType = $_POST['accountType'];
				echo($accountType);
				$query = "";

				if ($accountType == "Customers") {
					$query = "SELECT * FROM `$accountType` WHERE c_email=:email";
				} elseif ($accountType == "Drivers") {
					$query = "SELECT * FROM `$accountType` WHERE d_email=:email";
				} else {
					$query = "SELECT * FROM `$accountType` WHERE r_email=:email";
				}

				$statement = $pdo->prepare($query);
				$statement->bindValue(':email', $email);
				$statement->execute();
				
				$result = $statement->fetch();
				echo("Result: " . $result);
				$statement->closeCursor();

				if($result['password'] != $password)
				{
					echo "<div class='alert alert-danger' role='alert'>" . "Unable to login" . "</div>";
				}
				else
				{
					$_SESSION['user'] = $username;
					echo("<script>location.href = 'index.php';</script>");
				}

			} catch (PDOException $e) {
				echo $e;
			}
		}	
	?>
</html>

		