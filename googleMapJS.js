    var customIcons = {
     ServicedDealer: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      },
      Dealership: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_green.png'
      }
    };

    function load() {
      loadFilter("", "");
      
    }
       function getLink(file,filter,selectedItem){
         // link = "googlemapGetXMLInfoManager.php";
    //      message = "Loading data...Please wait..."
         
          if(filter){
            // alert(filter);
             reset();
              switch(filter){
                  case "orderDate":  
                      if(state.value) 
                          link = file + "orderDate=" + selectedItem  + "&filter=orderDateState&state=" + state.value;
                      else link = file + "orderDate=" + selectedItem + "&filter=orderDate";                 
                  //    Message.innerHTML = message ; 
                  //    alert(link);                   
                      break;

                  case "orderDateInput":  
                     if(state.value) 
                          link = file + "orderDateInput=" + selectedItem  + "&filter=orderDateState&state=" + state.value;
                     else link = file + "orderDateInput=" + selectedItem  + "&filter=orderDate";      
                 //    Message.innerHTML = message ;   
                  //  alert(selectedItem);
                     break;

                  case "orderDateNotSubmitted":
                     if(state.value) 
                          link = file + "orderDateNotSubmitted=" + selectedItem  + "&filter=orderDateNotSubmittedState&state=" + state.value;
                     else link = file + "orderDateNotSubmitted=" + selectedItem  + "&filter=orderDateNotSubmitted"; 
                    // alert(link);
                 //    Message.innerHTML = message ;   
                     break;

                  case "AllNotSubmitted":
                       if(state.value) {
                           link = file + "filter=AllNotSubmittedState&state=" + state.value;; 
                       }
                       else 
                        link = file +"filter=AllNotSubmitted";
                   //   alert(link);
                       break;

                  case "AllWithASubmitted":
                      if(state.value) {
                           link = file + "filter=AllWithASubmittedState&state=" + state.value;; 
                      }
                      else  link = file + "filter=AllWithASubmitted";
                      break;

                  case "state":  
                     link = file + "state=" + selectedItem  + "&filter=state";                         
                   //  alert(link);   
                     break;        
                }
                   return link;
            }          
      }
      
    function loadFilter(filter,selectedItem) {
      //  alert(id);
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(39.0997300, -94.5785700),
        zoom: 5,
        mapTypeId: 'roadmap'
      });
      
      var infoWindow = new google.maps.InfoWindow;
      
      
      link = getLink("googlemapGetXMLInfoManager.php?",filter,selectedItem);
     
      downloadUrl(link, function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        var totalDealers = markers.length;
        //alert(totalDealers);
        var servicedDealers = 0;
        var nonServicedDealers = 0;
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          
          var address = markers[i].getAttribute("address");
          var city = markers[i].getAttribute("city");
          var state = markers[i].getAttribute("state");
          var zip = markers[i].getAttribute("zip");
          var phone = markers[i].getAttribute("phone");
          var fax = markers[i].getAttribute("fax");
          var website = markers[i].getAttribute("website");
          website = website.replace("http://", "");
         
          website = "<a href='http://" + website + "' target='_blank' >" + website + "</a>"
          var type = markers[i].getAttribute("type");
          if(type == "ServicedDealer")
              servicedDealers++;
          else
              if(type == "Dealership")
                  nonServicedDealers++;
                  
          var tooltip = "Name: \t\t" + name + "\nAddress: \t" + address + "\n" + "Type: \t\t" + type;
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address + "<br/>" + city + ", " + state + " " + zip + "<br />" + "Phone: " + phone + "<br />" + "Fax: " + fax + "<br />" + website;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            title: tooltip
          });         
         
          TotalDealerships.innerHTML = totalDealers;
          ServicedDealerships.innerHTML = servicedDealers;
          NonServicedDealerships.innerHTML = nonServicedDealers;
          PercentageServiced.innerHTML = (servicedDealers/totalDealers).toFixed(2) * 100  + "%";
          Message.innerHTML = "";
         
          bindInfoWindow(marker, map, infoWindow, html);        
        }
      });     
      if(filter){
         resetHint();
         selectFilter(filter,selectedItem);
        } 
    }
    
    function resetSelection(){
          state.value ="";
          dropDate.value ="";
          dropDateInput.value ="";
    }
    
    function reset(){
          TotalDealerships.innerHTML = "";
          ServicedDealerships.innerHTML = "";
          NonServicedDealerships.innerHTML = "";
          PercentageServiced.innerHTML = "";
    }
    
    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // Added to allow decimal, period, or delete
    if (charCode == 110 || charCode == 190 || charCode == 46) 
            return true;

    if (charCode > 31 && (charCode < 48 || charCode > 57)) 
            return false;

    return true;
}

 function selectFilter(filter, selectedItem) {
  // alert("selectedItem=" + selectedItem);
   if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
   } else { // code for IE6, IE5
     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function() {
     if (xmlhttp.readyState==4 && xmlhttp.status==200) {
       document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
     }
   }
   file = "GetGridInfoManager.php?";
   URL = getLink(file, filter,selectedItem);  
 // xmlhttp.open("GET","GetGridInfoManager.php?f="+filter +"&st1=" + state1.value,true);
   

   xmlhttp.open("GET",URL,true); 
 //  xmlhttp.open("GET","GetGridInfoManager.php?filter="+filter+"&state="+selectedItem, true);
   xmlhttp.send();
 }
 
 function resetHint(){
     document.getElementById("txtHint").innerHTML= "";
 }
 
 
