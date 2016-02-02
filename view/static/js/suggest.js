function suggestServiceType(strServiceType)
{
    var xmlhttp;
    if (strServiceType.length == 0){ 
        document.getElementById("MsgSuggest").innerHTML = "";
        return;
    }
    
    if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200 ) {
            document.getElementById("MsgSuggest").innerHTML=xmlhttp.responseText;
        }
    }
    
    xmlhttp.open("GET","/openapi/?module=app&controller=index&action=existServiceType&servicetype="+strServiceType, true);
    xmlhttp.send();
}
