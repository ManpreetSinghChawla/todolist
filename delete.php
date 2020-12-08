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
$query = "Select entry from ".$_SESSION['name']." where entry_id = :id";
$sql = $pdo->prepare($query);
$sql->execute(array(':id' => $entry_id));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if($row === false){
	$_SESSION['error'] = "No such entry found. Please click the 'Mark as Done' for an appropriate entry";
	header('Location: index.php');
	return;
}
$stmt = "Delete from ".$_SESSION['name']." where entry_id =  :entry";
$sql = $pdo->prepare($stmt);
$sql->execute(array(':entry' => $entry_id));
$_SESSION['success'] = 'The entry has been deleted Successfully';
header('Location: index.php');
return;
?>