<?php
	session_start();
	// set up the database, yay!
	const DB_HOST='localhost';
	const DB_USER='root';
	const DB_PW='Nafai6831';
	const DB_DB='exoworld';


	try {
			$pdo = new PDO("mysql:host={DB_HOST};dbname={DB_DB}", '{DB_USER}', '{DB_PW}');
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo "Connected successfully"; 
			}
	catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage();
			}
?>