<?php
session_start();
require_once('pdo.php');
require_once('bootstrap.php');
if(isset($_POST['cancel'])){
	header('Location: login.php');
	return;
}
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){
	
	if(strlen($_POST['name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['password'])<1){
		$_SESSION['error'] = "All Fields are required";
		header('Location: create.php');
		return;
	}
	else if (strpos($_POST['email'], "@") === false || strpos($_POST['email'],".") === false) {
        $_SESSION['error'] = "Enter a valid email id: example@example.example";
		header('Location: create.php');
		return;
	}
	else if(strpos($_POST['name']," ") != false){
		$_SESSION['error'] = "Username can't contain spaces in it, Please enter a valid username";
		header('Location: create.php');
		return;
	}
	else {
		$table = "Insert into users (name,email,password) values (:name,:email,:password)";
		$add = $pdo->prepare($table);
		$add->execute(array('name' => htmlentities($_POST['name']),
							'email' => htmlentities($_POST['email']),
							'password' => htmlentities($_POST['password'])));
		$stmt = "Create table ".htmlentities($_POST['name'])." (
		entry_id int auto_increment primary key,
		entry varchar(500) not null)";
		$sql = $pdo->prepare($stmt);
		$sql->execute();
		$_SESSION['success'] = "Your Account has been created, Please log in to continue";
		$stmt = "Create table ".htmlentities($_POST['name'])."_comments (
		comment_id int auto_increment primary key,
		entry_id int references ".$_POST['name']."(entry_id),
		comment varchar(500) not null)";
		$sql = $pdo->prepare($stmt);
		$sql->execute();
		header('Location: login.php');
		return;
	}
}
?>
<html>
<head><link rel="shortcut icon" href="favicon.ico" type="image/x-icon"><title>Create Account | To-Do List</title></head>
<body>
<nav class="navbar navbar-light" style="background-color:black">
	<div class="navbar-header">
	<a class="navbar-brand" href="home.php"><img src="favicon.ico" width="30" height="30" alt="" loading="lazy"></a>
	<a class="navbar-brand" href="login.php" style="color:limegreen">Login</a>
	<a class="navbar-brand" href="index.php" style="color:limegreen">My List</a>
	</div>
	<a class="navbar-brand" href="logout.php" style="color:limegreen">Logout</a>
</nav>
<br><br>
<div class="container">
<div class="jumbotron">
<h1>Create an Account</h1>
<?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
		unset($_SESSION['error']);
    }
?>
<br>
<h4>Please Fill The Following Fields to Continue</h4><br><br>
<form method="post">
	 <label for="name">Username:   </label><input type="text" name="name" class="form-control" id="name" placeholder="Username"><br>
          <label for="nam" >Email:     </label><input type="text" class="form-control" name="email" id="nam" placeholder="example@example.com"><br>
          <label for="password">Password:  </label><input type="password" name="password" class="form-control" id="password" placeholder="Password"><br>
          <input type="submit" class="btn btn-success" value="Create">    <input type="submit" name="cancel" class="btn btn-danger" value="Cancel">
</form>
</div>
</div>
</body>
</html>