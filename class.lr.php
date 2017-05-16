<?php

Class Lr
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function Login($username, $password)
    {
        $query = $this->db->prepare("SELECT user_id, user_password FROM gsmm_users WHERE user_username = :username AND user_status = 1");
        $query->bindValue(":username", $username);
        $query->execute();
        $r = $query->Fetch(PDO::FETCH_ASSOC);

        if($r != false) {
            if($r["user_password"] == $password) {
                $_SESSION["gsmm_uid"] = $r["user_id"];
                $_SESSION["gsmm_user"] = $username;
                $_SESSION["user_type"] = "publisher";
                return true;
            }
        }

        return false;
    }
	
	
    public function Register($uname, $pass, $email, $plink)
    {
        $tstamp = time();
        $query = $this->db->prepare("INSERT INTO gsmm_users (user_username, user_password, user_email,page_link, user_date) VALUES(:username, :password, :email, :plink, '{$tstamp}')");
        $query->bindValue(":username", $uname);
        $query->bindValue(":password", $pass);
        $query->bindValue(":email", $email);
		$query->bindValue(":plink", $plink);
		try{
        $query->execute();

        if($query->rowCount() > 0) {
            return true;
			
        }

        return false;
		}

 catch (Exception $e) {
   // echo 'Caught exception: ',  $e->getMessage(), "\n";
    
    
  echo '<script language="javascript">';
echo 'alert("Entered Email or username is already Exist!")';
echo '</script>';

}

    }

    public function checkUsername($username)
    {
        $query = $this->db->prepare("SELECT user_id FROM gsmm_users WHERE user_username = :username");
        $query->bindValue(":username", $username);
        $query->execute();

        $r = $query->Fetch(PDO::FETCH_ASSOC);

        if($r != false) {
            if(isset($r["user_id"])) {
                return false;
            }
        }

        return true;
    }

    public function checkEmail($email)
    {
        $query = $this->db->prepare("SELECT user_id FROM gsmm_users WHERE user_email = :email");
        $query->bindValue(":email", $email);
        $query->execute();

        $r = $query->Fetch(PDO::FETCH_ASSOC);

        if($r != false) {
            if(isset($r["user_id"])) {
                return false;
            }
        }

        return true;
    }
	
	function create_hash($max=32){
		$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
		$size=StrLen($chars)-1;
		$hash=null;
		while($max--)$hash.=$chars[rand(0,$size)];
		return $hash; 
	}
}