<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'googleMapDatabaseManagerList.php';
//require_once 'googlemapXMLInfo.php';

$state = "";
$dropDate = "";
$dropDateInput = "";
$filter = "";
$query = "";

if(isset($_GET['f'])) $filter = $_GET['f']; 
if(isset($_GET['st1'])) $state = $_GET['st1']; 
if(isset($_GET['od1'])) $date = $_GET['od1']; 
if(isset($_GET['odI1'])) $date = $_GET['odI1']; 
if(isset($_GET['odNS1'])) $date = $_GET['odNS1']; 

require_once 'colnameConst.php';
$category1 = CATEGORY::SERVICEDEALER;

//$DatabaseInfoList = new DatabaseInfoList();

if($filter == "st1"){
   $query = $DatabaseInfoList->getQueryState($state); 
   
}

if($filter == "od1" || $filter == "odI1"){
     $query = $DatabaseInfoList->getQueryOrderDate($date,$category1);
    // echo $query;
}

if($filter == "odS1"){
   $query = $DatabaseInfoList->getQueryOrderDateState($date, $state,$category1);
  // echo $query;
}

if($filter == "odNS1"){
    $query = $DatabaseInfoList->getQueryOrderDateNotSubmitted($date,$category1);  
}

if($filter == "odNSSt1"){
    $query = $DatabaseInfoList->getQueryOrderDateNotSubmittedState($date, $state, $category1);   
}
if($filter == "AllNotSub1"){    
    $query = $DatabaseInfoList->getQueryAllNotSubmitted($category1);  
   
}

if($filter == "AllNotSubSt1"){    
    $query = $DatabaseInfoList->getQueryAllNotSubmittedState($state,$category1);
     echo $query;
}

if($filter == "AllWithASub1"){
    $query = $DatabaseInfoList->getQueryOrderAllWithASubmitted($category1);  
}

if($filter == "AllWithASubSt1"){
    $query = $DatabaseInfoList->getQueryOrderAllWithASubmittedState($state,$category1);  
    
}



    $KoolControlsFolder = "KoolPHPSuite/koolControls/";
    require $KoolControlsFolder."/KoolAjax/koolajax.php";
    $koolajax->scriptFolder = $KoolControlsFolder."/KoolAjax";

    require $KoolControlsFolder."/KoolGrid/koolgrid.php";

    require_once 'googlemapGetListInfoManager.php';
    require_once 'DatabaseInfoList.php';

    $DatabaseInfoList = new DatabaseInfoList("address_mapping");
    $db_con = $DatabaseInfoList->dbConnection();
    $DatabaseInfoList->dbSelection($db_con); 

    $ds = new MySQLDataSource($db_con);//This $db_con link has been created inside KoolPHPSuite/Resources/runexample.php
    $ds->SelectCommand = $query;
   
 
    $grid = new KoolGrid("grid");
    $grid->scriptFolder = $KoolControlsFolder."/KoolGrid";
    $grid->styleFolder="default";
    $grid->DataSource = $ds;
    $grid->Width = "1550px";

    $grid->RowAlternative = true;

   // $grid->AjaxEnabled = true;
  //  $grid->AjaxLoadingImage =  $KoolControlsFolder."/KoolAjax/loading/5.gif";
    $grid->AutoGenerateColumns = true;

   // $grid->AllowFiltering = false;//Enable filtering for all rows;

   // $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();

    $grid->Process();
  

?>
 <form id="form1" method="post">
        <fieldset style="width:250px;padding-left:10px;padding-bottom:10px;">
        
		<input type="submit" name="ExportToCSV" value = "Export to CSV" />
                <br/><br>
	</div>
	<?php echo $koolajax->Render();?>
	<?php echo $grid->Render();?>
        <br><br>
</form>
