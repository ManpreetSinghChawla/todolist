<?php
session_start();
require_once('pdo.php');
require_once('bootstrap.php');
if(!isset($_SESSION['name'])){
	die('Access Denied, Please login <a href="login.php">Here</a> First');
}
if(isset($_POST['back'])){
	header('Location: index.php');
	return;
}
if(isset($_POST['Add'])){
	if(strlen($_POST['add'])<1){
		$_SESSION['error'] = 'Please enter a valid comment';
		header('Location: comment.php?entry_id='.$_GET['entry_id']);
		return;
	}
	else {
		$stmt = "Insert into ".htmlentities($_SESSION['name'])."_comments (entry_id,comment) values (:entry,:comment)";
		$query = $pdo->prepare($stmt);
		$query->execute(array(':comment' => $_POST['add'],
							  ':entry' => $_GET['entry_id']));
		$_SESSION['success'] = "Comment Added!";
		header('Location: comment.php?entry_id='.$_GET['entry_id']);
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
<h1>Comments on entry: <?php
$st = $pdo->query('Select entry from '.htmlentities($_SESSION['name']).' where entry_id='.htmlentities($_GET['entry_id']));
$entry = $st->fetch(PDO::FETCH_ASSOC); 
echo('<p style="color:Orange">'.$entry['entry'].'</p>');?></h1>
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
$stmt = "Select * from ".htmlentities($_SESSION['name'])."_comments where entry_id=".$_GET['entry_id'];
$sql = $pdo->query($stmt);
$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
if(count($rows)==0){
	echo("<h2>Wow, Such Empty! Click Add New to Add a Comment</h2>");
}
else{
	echo("<ul style='margin-left:50px'>");
	foreach($rows as $row) {
		echo("<h3><li>");
		echo(htmlentities($row['comment']));
		echo('<b><a style="font-size:15;text-decoration:none;color:red;margin-left:40px;" href="deletecomment.php?entry_id='.$row['entry_id'].'&comment_id='.$row['comment_id'].'">Delete Comment</a> | <a style="font-size:15;text-decoration:none;color:blue;;" href="edit.php?entry_id='.$row['entry_id'].'&comment_id='.$row['comment_id'].'">Edit Comment</a>');
		echo("</li></h3>");
		echo("<br>");
	}
	echo("</ul>");
}
?>
<br>
<div class="form-group">
<form method="post">
<h5>Add a new Comment By Entering it Below</h5><br>
<input type="text" name="add" class="form-control" placeholder="Comment" size="70"> <br>     <input type="submit" name="Add" class="btn btn-success" value="Add New">


<input type="submit" class="btn btn-primary" name="back" value="Go Back">
</form>
</div>
</div>
</div>
</body>
</html>