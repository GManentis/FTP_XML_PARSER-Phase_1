<?php

$host = '...';
$user = '...';
$pass = '...';


$conn = ftp_connect($host) or die("Could not connect to $host");
ftp_login($conn,$user,$pass);



$local_file = "test1.xml";
$server_file = "test1.xml";

// download server file
if (ftp_get($conn, $local_file, $server_file, FTP_ASCII))
  {
  echo "Successfully written to $local_file.";
  }
else
  {
  echo "Error downloading $server_file.";
  }

// close connection
ftp_close($conn);




$xmlDoc = new DOMDocument();
$xmlDoc->load("test1.xml");

if (!$xmlDoc->schemaValidate('validator.xsd'))        // Or schemaValidateSource if string used.
{
  echo 'error';
}

$names = $xmlDoc->getElementsbyTagName('name');
$emails = $xmlDoc->getElementsbyTagName('email');
$etcs = $xmlDoc->getElementsByTagName('etc');

$entriesArray = array();



for( $i=0; $i<sizeof($names);$i++)
{
	$entry = new stdClass();
	$entry->name = $names[$i]->nodeValue;
	$entry->email = $emails[$i]->nodeValue;
	$entry->etc = $etcs[$i]->nodeValue;
	
	array_push($entriesArray,$entry);
	//$inputToJSON = json_encode($arrayInput);
		
}

		$hostname_DB = "127.0.0.1";
		$database_DB = "test";
		$username_DB = "root";
		$password_DB = "";
		try 
		{
		   $CONNPDO = new PDO("mysql:host=".$hostname_DB.";dbname=".$database_DB.";charset=UTF8", $username_DB, $password_DB, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3));
		} 
		catch (PDOException $e) 
		{
		   $CONNPDO = null;
		}
		if ($CONNPDO != null) 
		{  
			foreach($entriesArray as $arrayInput)
			{
					$email = $arrayInput->email;
					$getdata_PRST = $CONNPDO->prepare("SELECT * FROM test WHERE email = :email");
					$getdata_PRST -> bindValue(":email", $email);
					$getdata_PRST -> execute() or die($CONNPDO->errorInfo());
					$count = $getdata_PRST->rowCount();
					
					if($count > 0)	
					{
						$updata_PRST = $CONNPDO->prepare("UPDATE test SET  name = :name, etc = :etc  WHERE email = :email");
						$updata_PRST->bindValue(":name",$arrayInput->name);
						$updata_PRST->bindValue(":email",$arrayInput->email);
						$updata_PRST->bindValue(":etc",$arrayInput->etc);
						$updata_PRST->execute() or die($CONNPDO->errorInfo());
					}
					else
					{
						$adddata_PRST = $CONNPDO->prepare("INSERT INTO test(name, email, etc) VALUES (:name, :email, :etc)");
						$adddata_PRST->bindValue(":name",$arrayInput->name);
						$adddata_PRST->bindValue(":email",$arrayInput->email);
						$adddata_PRST->bindValue(":etc",$arrayInput->etc);
						$adddata_PRST->execute() or die($CONNPDO->errorInfo());
					}
			}
	
	
			echo "All good";
		}
		else
		{
			echo "No good";
		}
				



?>