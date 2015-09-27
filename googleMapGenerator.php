<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Countrywide Dealerships Serviced</title>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="googleMapJS.js"></script>
   
  </head>
  <body onload="load()">
           
          <table cellpadding="5px" style="font-family: arial">
              <tr>
                  <td style="font-family: arial; font-weight: bold" colspan="7">
                      YTD Dealer Serviced Stats
                  </td>
                   <td>
                      <div style="font-weight: bold; color:red"  id="Message" ></div>
                  </td>
              </tr>
              
              <tr>
                  <td>
                      Total Dealerships:
                  </td>
                  <td>
                      <div style="font-weight: bold"  id="TotalDealerships"></div>
                  </td>
             
                  <td>
                      Serviced:
                  </td>
                  <td>
                      <div style="font-weight: bold; color:green"  id="ServicedDealerships"></div>
                  </td>
             
                  <td>
                      Non Serviced:
                  </td>
                  <td>
                      <div style="font-weight: bold; color:red" id="NonServicedDealerships"></div>
                  </td>
                  <td>
                      Percentage Serviced:
                  </td>
                  <td>
                      <div style="font-weight: bold" id="PercentageServiced"></div>
                  </td>
              </tr>
          </table><td>
          <table> <tr><td> <div id="map" style="width: 1100px; height: 750px"></div></td>
             <td width="280" valign ="top">
        
          <form id="form1" method ="GET">
          <div>  
              
          <table bgcolor ="#D7E7F2"  border="0" cellspacing="0" cellpadding="1px" style="padding-left:8px;padding-bottom:10px;width:500px;" >
          <tr style="height:40px;" valign ="top">
          <td> State </td>
          <td>
          <?php            
              require_once 'googleMapDatabaseManager.php';  
              $dropDownStateList = $DatabaseInfo->getStateDropDown();
              echo $dropDownStateList;  
          ?>
              <td>
              </tr>
           <tr style="height:40px;" ><td>
            Completed Order<br> Prior to n Days:
               </td>
           <td>
           <select name="orderDate" id= "orderDate" onchange="loadFilter(this.id, this.value)">
             <option value="0">None</option>
             <option value="30">30 days</option>
             <option value="45">45 days</option>
             <option value="60">60 days</option> 
             <option value="90">90 days</option> 
             <option value="120">120 days</option> 
             <option value="180">6 months</option> 
             <option value="365">1 year</option>
           </select> 
           </td>
           </tr>
           <tr style="height:40px;" ><td>
           Input Completed Order<br> Prior to n Days:
               </td>
               <td>
           <input  type="text" onkeypress="return isNumberKey(event)" name="orderDateInput" id= "orderDateInput" size ="4"  maxlength= "4" value =" " onchange= "loadFilter(this.id, this.value)">&nbsp; days 
               </td>
           </tr>
           <tr style="height:40px;" ><td>
           Dealers with Last Order NOT Submitted:
               </td>
               <td>
            <select name="orderDateNotSubmitted" id= "orderDateNotSubmitted" onchange="loadFilter(this.id, this.value)">
             <option value="0">None</option>
             <option value="30">30 days</option>
             <option value="45">45 days</option>
             <option value="60">60 days</option> 
             <option value="90">90 days</option> 
             <option value="120">120 days</option> 
             <option value="180">6 months</option> 
             <option value="365">1 year</option>            
           </select> 
               </td>
           </tr>
          <tr style="height:40px;" ><td>
          All Dealers:</td>
          <td>    
          <input  type="checkbox" name="AllNotSubmitted" id= "AllNotSubmitted" onchange= "loadFilter(this.id, this.value)">With No Submitted Orders<br>
          <input  type="checkbox" name="AllWithASubmitted" id= "AllWithASubmitted" onchange= "loadFilter(this.id, this.value)">With a Submitted Orders<br>
          </td>
          </tr>
          <tr colspan ="2"><td>
           <button type="reset" value="Reset" onclick ="load()">Reset</button> </form>
           </td>
          </tr>
          </table>
      </div>
      </form>
             
         </td>
      </table>
           
      </div>  
  
  </body>

</html>
