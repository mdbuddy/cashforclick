<?php

require_once "includes/init.php";
require_once "classes/class.ac_settings.php";

//$user_idd = $_SESSION["gsmm_uid"];


date_default_timezone_set('Asia/Kolkata');

$timestamp = time();
$date_time = date("Y-m-d H:i:s", $timestamp);
$date_time1=new DateTime($date_time);

//echo "Current date and local time on this server is $date_time";



if($_GET){
$hash = $_GET['i'];

//echo $hash;

try{
$sql2 = $pdo->prepare("SELECT user_id,adv_id,long_url FROM short_url where short_hash='".$hash."'"); 
     $sql2->execute();

    
}
catch(PDOException $e) {
    // echo "Error: " . $e->getMessage();
}


$result = $sql2->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row => $linkkk){
$user_idd=$linkkk['user_id'];
$adv_id=$linkkk['adv_id'];
		
$redirect = $linkkk['long_url'];
//echo $redirect;
		 
echo "Please wait a momemt...";

}






$ip = $_SERVER['REMOTE_ADDR'];
//echo $ip;
//echo "<br>";

//$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

function curl_get_contents($url)
{
$ch = curl_init();
$timeout = 10;

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

$data = curl_exec($ch);

curl_close($ch);

return $data;
}


$details = json_decode(curl_get_contents("http://ipinfo.io/{$ip}/json"));

//echo $details->country;



try{
$sql3 = $pdo->prepare("SELECT * FROM visitors where ip_addr='$ip' AND hash='$hash'"); 
     $sql3->execute();

    
}

catch(PDOException $e) {
     echo "Error: " . $e->getMessage();
}




$result = $sql3->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row => $link){
		$table_time=new DateTime( $link['time']);
                		$visitor_count=$link['visitor'];
                		echo $visitor_count;
		 


}
if($link['time'])
{
$start = '2013-04-11 18:25:20';
$end = '2013-04-12 19:14:25';

 $start1=new DateTime($start);
 $end1=new DateTime($end);
   
$interval = $table_time->diff($date_time1);
//echo $interval->format('%a');
//echo "<br>";
//echo $interval->format('%H:%I:%S');

if(($interval->format('%a') > '0') || ($interval->format('%H')>='0')){ // change it to 6 hours
//echo "Update";



try{
$visitor_count = $visitor_count + 1;
	
    $sql4 = $pdo->prepare("UPDATE visitors SET time='$date_time' , visitor='$visitor_count' where ip_addr = '$ip' AND hash='$hash'" );
	
	    //$sql->bindValue(":time", $date_time);	
  $sql4->execute();
   // echo "Updated";
    }


 catch (PDOException $e) {
    die('<b>Error Occured: </b>' . $e->getMessage());
  }


}







}
else{
//echo $ip;
try{
	
    $sql = $pdo->prepare("INSERT INTO visitors(pub_id,adv_id,ip_addr,country_code,hash,time)
    VALUES (:uid,:aid,:ip_addr,:c_code,:hash,:time)");
	
	$sql->bindValue(":uid", $user_idd);
        $sql->bindValue(":aid", $adv_id);
        $sql->bindValue(":ip_addr", $ip);
        $sql->bindValue(":c_code", $details->country);
        $sql->bindValue(":hash", $hash);
        $sql->bindValue(":time", $date_time);	
  $sql->execute();
   // echo "Inserted";
    }


 catch (PDOException $e) {
    die('<b>Error Occured: </b>' . $e->getMessage());
  }
}

}



else{
	echo "Error";
}

?>
<script>
var myvar = "<?php echo $redirect;?>";

window.location = myvar;

</script>
