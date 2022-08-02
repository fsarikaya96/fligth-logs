<?php
try {
    $connect= new PDO("mysql:host=db;dbname=airline","root","root");
}catch (PDOException $e){
    die($e->getMessage());
}