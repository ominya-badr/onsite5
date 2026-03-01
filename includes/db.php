<?php
$host="localhost";
$dbname="school_tournament3";
$username="root";
$password="";
try{
    $pdo=new PDO("mysql:hpst=$host;dbname=$dbname",$username,$password);
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

}
catch(PDOException $e){
    die("connection failed ").$e->getMessage();
}
?>