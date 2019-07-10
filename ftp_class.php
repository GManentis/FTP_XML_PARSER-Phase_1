<?php

class ftp_class 
{

    private $host;
    private $user;
    private $pass;
    private $conn;
    
	
	public function __construct($host,$user,$pass){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;

        $this->conn = ftp_connect($this->host) or die("Could not connect to $host");
        ftp_login($this->conn,$this->user,$this->pass);
        echo "connected ok ".$this->host;
    }
	
	public function xmlOpen(){
		
		$xmlDoc = new DOMDocument();
$xmlDoc->load("note.xml");

	}


}


?>