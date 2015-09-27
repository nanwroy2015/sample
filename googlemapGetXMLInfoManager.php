<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'googleMapDatabaseManager.php';
require_once 'googlemapXMLInfo.php';

$state = "";
$dropDate = "";
$dropDateInput = "";
$filter = "";
$query = "";

if(isset($_GET['filter'])) $filter = $_GET['filter']; 
if(isset($_GET['state'])) $state = trim($_GET['state']); 
  
if(isset($_GET['orderDate'])) $date = trim($_GET['orderDate']); 
if(isset($_GET['orderDateInput'])) $date =trim($_GET['orderDateInput']); 
if(isset($_GET['orderDateNotSubmitted'])) $date = $_GET['orderDateNotSubmitted']; 

require_once 'colnameConst.php';
$category1 = CATEGORY::SERVICEDEALER;

$GooglemapXMLInfo = new GooglemapXMLInfo();

if($filter == "state"){
   $query = $DatabaseInfo->getQueryState($state); 
}

if($filter == "orderDate" || $filter == "orderDateInput"){
   $query = $DatabaseInfo->getQueryOrderDate($date,$category1);
    // $query = $DatabaseInfo->getQueryOrderDate('30',$category1);
    //echo "hello".$query;
}

if($filter == "orderDateState"){
   $query = $DatabaseInfo->getQueryOrderDateState($date, $state,$category1);
}

if($filter == "orderDateNotSubmitted"){
    $query = $DatabaseInfo->getQueryOrderDateNotSubmitted($date,$category1);
}

if($filter == "orderDateNotSubmittedState"){
    $query = $DatabaseInfo->getQueryOrderDateNotSubmittedState($date, $state, $category1);
}

if($filter == "AllNotSubmitted"){    
    $query = $DatabaseInfo->getQueryAllNotSubmitted($category1);  
}

if($filter == "AllNotSubmittedState"){    
    $query = $DatabaseInfo->getQueryAllNotSubmittedState($state,$category1);  
}

if($filter == "AllWithASubmitted"){
    $query = $DatabaseInfo->getQueryOrderAllWithASubmitted($category1);  
}

if($filter == "AllWithASubmittedState"){
    $query = $DatabaseInfo->getQueryOrderAllWithASubmittedState($state,$category1);  
    
}
$GooglemapXMLInfo->XMLGenerator($query);
?>
 
