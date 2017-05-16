<?php

require_once "includes/init.php";

require_once "classes/class.ac_settings.php";

$user_idd = $_SESSION["gsmm_uid"];


function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



$short_hash = generateRandomString(5);

echo $short_hash;



try{
	//$link = $_POST['article_link'];
	
    $sql = $pdo->prepare("INSERT INTO short_url (user_id,short_hash,long_url)
    VALUES (:uid,:short,:link_url)");
	
	$sql->bindValue(":uid", $user_idd);
        $sql->bindValue(":short", $short_hash);
        $sql->bindValue(":link_url", "http://mongasaab.com/ip.php");
	
  $sql->execute();

  
    }


 catch (PDOException $e) {
    die('<b>Error Occured: </b>' . $e->getMessage());
}





?>
