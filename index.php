<?php
session_start();
require_once('pdo.php');
require_once('bootstrap.php');
if(!isset($_SESSION['name'])){
	die('Access Denied, Please login <a href="login.php">Here</a> First');
}

if(isset($_POST['logout'])){
	header('Location: logout.php');
	return;
}

if(isset($_POST['add'])){
	if(strlen($_POST['add'])<1){
		$_SESSION['error'] = 'Please enter valid data to insert into the list';
		header('Location: index.php');
		return;
	}
	else {
		$stmt = "Insert into ".htmlentities($_SESSION['name'])." (entry) values (:entry)";
		$query = $pdo->prepare($stmt);
		$query->execute(array(':entry' => $_POST['add']));
		$_SESSION['success'] = "Item Added!";
		header('Location: index.php');
		return;
	}
}
?>
<html>
<head><link rel="shortcut icon" href="favicon.ico" type="image/x-icon"><?php echo("<title>".$_SESSION['name']);?> | To-Do List</title>
<body>
<nav class="navbar navbar-light" style="background-color:black">
	<div class="navbar-header">
	<a class="navbar-brand" href="home.php"><img src="favicon.ico" width="30" height="30" alt="" loading="lazy"></a>
	<a class="navbar-brand" href="login.php" style="color:limegreen">Login</a>
	<a class="navbar-brand" href="index.php" style="color:limegreen">My List</a>
	</div>
	<a class="navbar-brand" href="logout.php" style="color:limegreen">Logout</a>
</nav>
<br>
<br>
<div class="container">
<div class="jumbotron">
<h1><?php echo($_SESSION['name']."'s");?>  To-Do List</h1>
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
<br><br>
<?php 
$stmt = "Select entry,entry_id from ".htmlentities($_SESSION['name']);
$sql = $pdo->query($stmt);
$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
if(count($rows)==0){
	echo("<h2>Wow, Such Empty! Click Add New to Add an Entry</h2>");
}
else{
	echo("<ul style='margin-left:50px'>");
	foreach($rows as $row) {
		echo("<h3><li>");
		echo(htmlentities($row['entry']));
		echo('<b><a style="font-size:15;text-decoration:none;color:green;margin-left:40px;" href="delete.php?entry_id='.$row['entry_id'].'">Mark as Done</a> | <a style="font-size:15;text-decoration:none;color:blue;" href="comment.php?entry_id='.$row['entry_id'].'&'.'name='.$_SESSION['name'].'">View Comments</a></b>');
		echo("</li></h3>");
		echo("<br>");
	}
	echo("</ul>");
}
?>
<br><br>
<div class="form-group">
<form method="post">
<p>Add a new Entry By Entering it Below</p>
<input type="text" name="add" class="form-control" size="70" placeholder="Entry Here"><br>      <input type="submit" name="Add" class="btn btn-success" value="Add New">


<input type="submit" name="logout" class="btn btn-info" value="Logout">
</form>
</div>
</div>
</div>
</body>
</html>