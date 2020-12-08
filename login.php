<?php
require_once("pdo.php");
require_once('bootstrap.php');
session_start();
if (isset($_POST['cancel'])) {
    header("Location: home.php");
    return;
}
if(isset($_POST['new'])){
	header("Location: create.php");
	return;
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    if (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
        $_SESSION['error'] = "User name and password are required";
		header('Location: login.php');
		return;
    } else if (strpos($_POST['email'], "@") === false || strpos($_POST['email'],".") === false) {
        $_SESSION['error'] = "Enter a valid email id: example@example.example";
		header('Location: login.php');
		return;
    } else {
        $query = "Select password,name from users where email = :email";
		$sql = $pdo->prepare($query);
		$sql->execute(array(':email' => htmlentities($_POST['email'])));
		$row = $sql->fetch(PDO::FETCH_ASSOC);
        if ($row['password'] === $_POST['password']) {
            error_log("Login success ".$_POST['email']);
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['name'] = $row['name'];
            header("Location: index.php");
            return;
        } elseif($row === false) {
			$_SESSION['error'] = "No such user exists, Please Click Create Account to get Started";
			header('Location: login.php');
			return;
		}
		else{
            $_SESSION['error'] = "Incorrect password, Try again";
			header('Location: login.php');
			return;
        }
    }
}
?>
<html>
<head><link rel="shortcut icon" href="favicon.ico" type="image/x-icon"><title>Login | To-Do List</title></head>
<body>
<nav class="navbar navbar-light" style="background-color:black">
	<div class="navbar-header">
	<a class="navbar-brand" href="home.php"><img src="favicon.ico" width="30" height="30" alt="" loading="lazy"></a>
	<a class="navbar-brand" href="login.php" style="color:limegreen">Login</a>
	<a class="navbar-brand" href="index.php" style="color:limegreen">My List</a>
	</div>
</nav>
<br><br>
<div class="container">
	<div class="jumbotron">
	    <h1>Please Log In</h1><br>
			<?php
		if (isset($_SESSION['error'])) {
			echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
			unset($_SESSION['error']);
		}
		if (isset($_SESSION['success'])) {
			echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
			unset($_SESSION['success']);
		}
		?>
		<div class="form-group">
		<form method="POST">
			  <label for="nam">Email:     </label><input type="text" class="form-control" name="email" id="nam" placeholder="example@example.com"><br/>
			  <label for="password">Password:  </label><input type="password" class="form-control" name="password" id="password" placeholder="Password"><br/>
			  <input type="submit" value="Log In" class="btn btn-success">    <input type="submit" class="btn btn-danger" name="cancel" value="Cancel"><br><br>
			  
			  <p><b>New to the Site?<b>  <br>  
			  <input type="submit" class="btn btn-info" value="Create a New Account" name="new">
		</form>
		</div>
	</div>
</div>
</body>