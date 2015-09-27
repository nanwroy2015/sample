<?php
 
    class GooglemapXMLInfo{
        
        function parseToXML($htmlStr) 
        { 
            $xmlStr = str_replace('<','&lt;',$htmlStr); 
            $xmlStr = str_replace('>','&gt;',$xmlStr); 
            $xmlStr = str_replace('"','&quot;',$xmlStr); 
            $xmlStr = str_replace("'",'&#39;',$xmlStr); 
            $xmlStr = str_replace("&",'&amp;',$xmlStr); 
            return $xmlStr; 
        } 
       
     
            // Start XML file, echo parent node

        function XMLGenerator($result){             
            header("Content-type: text/xml");

            // Start XML file, echo parent node
            echo '<markers>';

            // Iterate through the rows, printing XML nodes for each
            while ($row = @mysql_fetch_assoc($result)){
              // ADD TO XML DOCUMENT NODE
              echo '<marker ';
              echo 'name="' .$this->parseToXML($row['DealerName']) . '" ';
              echo 'address="' .$this->parseToXML($row['Address']) . '" ';
              echo 'lat="' . $row['Latitude'] . '" ';
              echo 'lng="' . $row['Longitude'] . '" ';
              echo 'type="' . $row['category'] . '" ';
              echo 'city="' . $row['City'] . '" ';
              echo 'state="' . $row['State'] . '" ';
              echo 'zip="' . $row['Zip'] . '" ';
              echo 'phone="' . $row['Phone'] . '" ';
              echo 'fax="' . $row['Fax'] . '" ';
              echo 'website="' . $row['Website'] . '" ';
              echo '/>';
            }
            //echo $ParamValue;
            mysql_free_result($result);
            
            // End XML file
            echo '</markers>';
         }
}
?>
