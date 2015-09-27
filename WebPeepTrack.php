<?php

require_once 'dbConnection.php';
require_once 'PublicLogger.php'; 
require_once 'PeepAPI/PeepRelayMessage.php';
require_once 'PeepAPI/PeepPublicStaticFunctions.php';
require_once 'PublicLogger.php';


if(IsValidAPIKey())
{  
     echo base64_encode(json_encode(ProcessPostObjects()));
}
else 
{     
    $PeepRelayMessage = new PeepRelayMessage(); 
    $PeepRelayMessage->peepID = -1; 
    $PeepRelayMessage->uploadedMessage = "Invalid APIKey!"; 
    echo base64_encode(json_encode($PeepRelayMessage));
}

function IsValidAPIKey()
{    
    require_once 'PeepAPI/PeepAPISecurityGuard.php';   
    $inboundAPIKey = json_decode(base64_decode($_POST["APIKey"]));    
    
    If($inboundAPIKey)
    {
        $APISecuirtyGuard = new APISecurityGuard();
        if($APISecuirtyGuard->APIKeyValid($inboundAPIKey))
        {
           PublicStaticFunction::WriteLogFile("APIKey is valid!");
            return true;            
        }
    } 
    return false;   
}


function ProcessPostObjects()
{
    
     require_once 'PeepAPI/PeepProcessManager.php';  
     $ProcessManager = new ProcessManager();

     $ServiceRelayMessages = array();
     $transactionSuccess = 0; 
 
     foreach($_POST as $key => $value) 
     { 
        $PeepRelayMessage = new PeepRelayMessage(); 
        $value = json_decode(base64_decode($value));
        
       switch ($key)
        {  
           case 'POSTObjectMethod::GetNewPeepID':  
              PublicStaticFunction::WriteLogFile("Call function GetNewPeeID()!");
              $PeepRelayMessage = $value;
              $PeepRelayMessage = $ProcessManager->ProcessPeepTrack($value, 'POSTObjectMethod::GetNewPeepID');
              PublicStaticFunction::WriteLogFile(print_r($PeepRelayMessage, true));
              break;
           case 'POSTObjectMethod::ProcessPeep': 
              PublicStaticFunction::WriteLogFile("Call function ProcessPeep()!");
              $PeepRelayMessage = $ProcessManager->ProcessPeepTrack($value, 'POSTObjectMethod::ProcessPeep');
              PublicStaticFunction::WriteLogFile(print_r($PeepRelayMessage, true));
             break;
         
       } 
        
        if($key != "APIKey")
        {
            
            AssociativeArrayPush($ServiceRelayMessages, $key, $PeepRelayMessage); 
        }       
       
        $transactionSuccess = $PeepRelayMessage->OperationSuccess;
     
        if(!$transactionSuccess && $key != "APIKey")
        { 
            break;
        }        
    }
    return $ServiceRelayMessages;   
} 

/** @param array $array, string $key, array value */
    function AssociativeArrayPush(&$array, $key, $value)
    {
        $array[$key] = $value;        
        return $array;
    }
 
    
?>
