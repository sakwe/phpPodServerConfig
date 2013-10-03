var xmlHttp
var localWhereToPut

function showPage(pageToFetch,whereToPut)
{ 
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
		{
			alert ("Sorry you cannot run AJAX Applications.")
			return
		} 
	var url=pageToFetch
	localWhereToPut = whereToPut
	//url=url+"?q="
	//url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
			document.getElementById(localWhereToPut).innerHTML  =xmlHttp.responseText
			localWhereToPut = ""

		} 
} 

function GetXmlHttpObject()
{ 
	var objXMLHttp=null
	if (window.XMLHttpRequest)
		{
			objXMLHttp=new XMLHttpRequest()
		}
	else if (window.ActiveXObject)
		{
			objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
		}
	return objXMLHttp
}
