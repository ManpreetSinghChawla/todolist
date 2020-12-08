<?php
session_start();
require_once('bootstrap.php');
require_once('pdo.php');
if(!isset($_SESSION['name'])){
	die('Access Denied, Please login <a href="login.php">Here</a> first');
}
if(!isset($_GET['entry_id'])){
	die('No entry selected to be deleted. Please <a href="index.php">Try</a> again');
}
$entry_id = htmlentities($_GET['entry_id']);
$comment_id = htmlentities($_GET['comment_id']);
$query = "Select comment from ".$_SESSION['name']."_comments where comment_id = :comment";
$sql = $pdo->prepare($query);
$sql->execute(array(':comment' => $comment_id));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if($row === false){
	$_SESSION['error'] = "No such Comment found. Please click on 'Delete Comment' for an appropriate entry";
	header('Location: comment.php?entry_id='.$_GET['entry_id']);
	return;
}
$stmt = "Delete from ".$_SESSION['name']."_comments where comment_id =  :comment";
$sql = $pdo->prepare($stmt);
$sql->execute(array(':comment' => $comment_id));
$_SESSION['success'] = 'The Comment has been deleted Successfully';
header('Location: comment.php?entry_id='.$_GET['entry_id']);
return;
?>