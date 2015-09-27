<?php

/*
 * Written by Nan wang for Web Peep Track project
 * 
 */

require_once 'dbConnection.php';
require_once 'PeepAPI/PeepRelayMessage.php';
require_once 'PeepAPI/PeepPOSTObjectMethod.php'; 
require_once 'PeepAPI/PeepPublicStaticFunctions.php';

class ProcessManager 
{ 
    var $newPeepID;

    function ProcessPeepTrack($value, $processMethod)
    {
       // $STARTTIME = date("H:i:s");
        $PeepRelayMessage = new PeepRelayMessage(); 
           
        $PeepHandler = new PeepHandler();
      
        switch ($processMethod)
        {
           case 'POSTObjectMethod::GetNewPeepID':            
              $sourceID = $value->sourceID;                
              $PeepRelayMessage = $PeepHandler->GetNewPeepID($sourceID);
              $this->newPeepID = $PeepRelayMessage->peepID;
              break;
           case 'POSTObjectMethod::ProcessPeep':
              $sourceID = $value->sourceID;  
              $PeepRelayMessage = $PeepHandler->ProcessWebPeep($this->newPeepID, $sourceID);
              break;  
        } 
        return $PeepRelayMessage;
    }
    
    
}

class PeepHandler
{   
    var $dbConnection;
  

    function GetNewPeepID($sourceID){        
               
                $this->BeginTransaction();
                $newPeepID = "";
                PublicStaticFunction::WriteLogFile("CALL stored procedure NewPeepIDOnSourceID()");
                $queryGeneratePeepID = "CALL NewPeepIDOnSourceID($sourceID, @newPeepID)";
                $rs = mysql_query($queryGeneratePeepID);          
                $rs = mysql_query( 'SELECT @newPeepID' );               
                while($row = mysql_fetch_assoc($rs)){            
                      $this->newPeepID = $row['@newPeepID'];                  
                      break;             
            }  
                    
        if($rs){  
            $OperationSuccess = '1';
            $queryResult = 'New PeepID generated.';
            $PeepRelayMessage = new PeepRelayMessage(); 
            $PeepRelayMessage->sourceID = $sourceID;  
            $PeepRelayMessage->sqlQuery = $queryGeneratePeepID;
            $PeepRelayMessage->peepID = $this->newPeepID;  
            $PeepRelayMessage->queryResult = $queryResult;
            $PeepRelayMessage->OperationSuccess = $OperationSuccess; 
        }
        
        else{  
           $PeepRelayMessage->sqlQuery = $queryGeneratePeepID."MySQL error ".mysql_errno(); 
           $PeepRelayMessage->queryResult = "Failed to generate New PeepID". mysql_errno();          
        }
        $this->EndTransaction($OperationSuccess); 
        return $PeepRelayMessage;                 
    }

    function ProcessWebPeep($peepID, $sourceID)
    {   
        if(isset($_POST["POSTObjectMethod::ProcessPeep"]))
        {         
                $this->BeginTransaction();
                 $webPeepID = $peepID;
                
                $peep = json_decode(base64_decode($_POST["POSTObjectMethod::ProcessPeep"]));
                $sourceID   =  $peep->sourceID;
                $fname      =  $peep->fname;
                $lname      =  $peep->lname;
                $email      =  $peep->email;
                $cellPhone  =  $peep->cellPhone;
                $homePhone  =  $peep->homePhone;
                $workPhone  =  $peep->workPhone; 
                $addr       =  $peep->address;
                $city       =  $peep->city;
                $state      =  $peep->state;
                $zip        =  $peep->zip;
                
                $leadID     =  $peep->leadID;
                $campaignID =  $peep->campaignID;
                $opt        =  $peep->opt;
                $lastDealDate = $peep->lastDealDate; 
                $dealerID     = $peep->dealerID;
                
                $PeepRelayMessage = new PeepRelayMessage(); 
               
                     
                if($sourceID == '3')    $queryLookupPeepID = "CALL LookupWebPeepID('$fname','$lname','$email',@PeepIDOut)";
                if($sourceID == '2')    $queryLookupPeepID = "CALL LookupPeepID('$fname','$lname','$addr','$city','$state','$zip',@PeepIDOut)"; 
                 
          
                $rs = mysql_query($queryLookupPeepID);          
                $rs = mysql_query( 'SELECT @PeepIDOut' );
                
                while($row = mysql_fetch_assoc($rs))
                {   
                    $PeepIDFound = $row['@PeepIDOut'];  
                    $PeepRelayMessage->peepID = $PeepIDFound;
                    if($PeepIDFound > 0){
                        $PeepRelayMessage->peepID = $PeepIDFound;
                        $PeepRelayMessage->queryResult = "Peep Id is found. Update Peep information.";
                        $query = "CALL UpdateWebPeepIdentity('$PeepIDFound','$email','$cellPhone','$homePhone','$workPhone','$leadID','$campaignID','$opt')";  
                    }
                    else 
                    {   
                        $PeepRelayMessage->peepID = $webPeepID ;
                        $PeepRelayMessage->queryResult = "Insert new Peep ID information.";
                        if($sourceID == '3') $query = "CALL InsertWebPeepIdentity('$webPeepID','$fname','$lname','$email','$cellPhone','$homePhone','$workPhone')"; 
                        if($sourceID == '2') $query= "CALL InsertPeepIdentityInfo('$webPeepID','$fname','$lname','$addr','$city','$state','$zip',
                                             '$email','$lastDealDate','$cellPhone','$homePhone','$workPhone')";
                    }
                }  
               
                 $rs1 = mysql_query($query); 
              
           
               if($rs1){  
                  $OperationSuccess = '1';
                  $PeepRelayMessage->uploadedMessage = "Valid APIKey!"; 
                  $PeepRelayMessage->sourceID = $sourceID;                 
                  $PeepRelayMessage->sqlQuery = $query;                  
                  $PeepRelayMessage->OperationSuccess = $OperationSuccess ;  
                  
                 }
                 else{  
                   $PeepRelayMessage->sqlQuery = "MySQL error ".mysql_errno(); 
                   $PeepRelayMessage->queryResult = "Failed to insert New PeepID information". mysql_errno();
                 }
                 $this->EndTransaction($OperationSuccess);
          
         }
      //   PublicStaticFunction::WriteLogFile(print_r($PeepRelayMessage, true));
         return $PeepRelayMessage;   
    }
 
   
        function GetDBConnection()
     {
        $dbConnection = new dbConnector();
        $dbConnectProperty = new dbConnectProperty();
        $dbConnection->setCustomConnectionParameters($dbConnectProperty);
        $dbConnection->connect();     
        $dbConnection->selectDatabase("peep_track");
        return $dbConnection;
    }
    
     function BeginTransaction()
     {   
        $this->dbConnection = new dbConnector();
        $dbConnectProperty = new dbConnectProperty();
        $this->dbConnection->setCustomConnectionParameters($dbConnectProperty);
        $this->dbConnection->connect();     
        $this->dbConnection->selectDatabase("peep_track");
        $this->dbConnection->beginTransaction(); 
   
    }
    
    function EndTransaction($OperationSuccess)
    {
        if($OperationSuccess)
        {      
            $this->dbConnection->commitTransaction(); 
        }
        else
        {     
            $this->dbConnection->rollbackTransaction(); 
        }
        $this->dbConnection->disconnect();
    }
    
}

?>
