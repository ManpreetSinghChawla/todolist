<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=todolist','exploder29','nomorefish'); 
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>
