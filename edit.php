<?php
session_start();
require_once('pdo.php');
require_once('bootstrap.php');
if(!isset($_SESSION['name'])){
	die('Access Denied, Please login <a href="login.php">Here</a> First');
}
if(isset($_POST['back'])){
	header('Location: comment.php?entry_id='.$_GET['entry_id']);
	return;
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
<h1>Edit Comment</h1>
<?php
$comment_id = htmlentities($_GET['comment_id']);
$query = "Select comment from ".$_SESSION['name']."_comments where comment_id = :comment";
$sql = $pdo->prepare($query);
$sql->execute(array(':comment' => $comment_id));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if($row === false){
	$_SESSION['error'] = "No such Comment found. Please click on 'Edit Comment' for an appropriate comment";
	header('Location: comment.php?entry_id='.$_GET['entry_id']);
	return;
}
if(isset($_POST['edit_comment'])){
	if(strlen($_POST['edit_comment'])<1){
		$_SESSION['error'] = 'Please enter a valid comment';
		header('Location: comment.php?entry_id='.$_GET['entry_id']);
		return;
	}
	else {
		$stmt = "Update ".htmlentities($_SESSION['name'])."_comments set comment = :comment where comment_id = ".$_GET['comment_id'];
		$query = $pdo->prepare($stmt);
		$query->execute(array(':comment' => htmlentities($_POST['edit'])));
		$_SESSION['success'] = "Comment Changed Successfully!";
		header('Location: comment.php?entry_id='.$_GET['entry_id'].'&stmt='.$stmt);
		return;
	}
}
?>
<br><br>
<div class="form-group">
<form method="post">
<p>Type the edited comment below</p>
<input type="text" class="form-control" name="edit" size="70" value=<?php echo($row['comment']) ?>><br>      <input type="submit" class="btn btn-success" name="edit_comment" value="Save">

<input type="submit" class="btn btn-primary" name="back" value="Go Back">
</form>
</div>
</div>
</div>
</body>
</html>