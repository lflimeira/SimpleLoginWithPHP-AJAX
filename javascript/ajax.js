/*Create function sendRequest that will send the data to the PHP page making a request, 
this page will be executed and it will return an answer.*/
function sendRequest(url,callback,postData) {
    /*
    Call the function createXMLHTTPObject that will try to create a  XMLHTTPObject. 
    XMLHTTPObject is responsible for sending the information and get the return.
    */
    var req = createXMLHTTPObject();
    if (!req) return;
    //Check the method of sending the data.
    var method = (postData) ? "POST" : "GET";
    //open the connection with the URL to send the data.
    req.open(method,url,true);
    //Verify data that will be send and set the header.
    if (postData){
        req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	}else{
        req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	}
    
    //Read the requisition status.
    req.onreadystatechange = function () {
        if (req.readyState != 4) return;
        if (req.status != 200 && req.status != 304) {
            return;
        }
        //If it's ok, calls the callback function.
        callback(req);
    }
    if (req.readyState == 4) return;
    //Send the data to the pages
    req.send(postData);
}

var XMLHttpFactories = [
    function () {return new XMLHttpRequest()},
    function () {return new ActiveXObject("Msxml2.XMLHTTP")},
    function () {return new ActiveXObject("Msxml3.XMLHTTP")},
    function () {return new ActiveXObject("Microsoft.XMLHTTP")}
];

function createXMLHTTPObject() {
    var xmlhttp = false;
    for (var i=0;i<XMLHttpFactories.length;i++) {
        try {
            xmlhttp = XMLHttpFactories[i]();
        }
        catch (e) {
            continue;
        }
        break;
    }
    return xmlhttp;
}