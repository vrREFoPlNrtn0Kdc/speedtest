<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="favicon.ico">
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
<title>LibreSpeed Example</title>
<script type="text/javascript" src="speedtest.js"></script>
<script type="text/javascript">

//LIST OF TEST SERVERS. See documentation for details if needed
var SPEEDTEST_SERVERS=[

{
"name":"New York, United States (2) (Clouvider)",
"server":"//nyc.speedtest.clouvider.net/backend","id":52,
"dlURL":"garbage.php",
"ulURL":"empty.php",
"pingURL":"empty.php",
"getIpURL":"getIP.php",
"sponsorName":"Clouvider",
"sponsorURL":"https://www.clouvider.co.uk/"
}

];


//INITIALIZE SPEEDTEST
var s=new Speedtest(); //create speedtest object

s.onupdate=function(data){ //callback to update data in UI
    I("ip").textContent=data.clientIp;
    I("dlText").textContent=(data.testState==1&&data.dlStatus==0)?"...":data.dlStatus;
    I("ulText").textContent=(data.testState==3&&data.ulStatus==0)?"...":data.ulStatus;
    I("pingText").textContent=data.pingStatus;
    I("jitText").textContent=data.jitterStatus;
}
s.onend=function(aborted){ //callback for test ended/aborted
    I("startStopBtn").className=""; //show start button again
    if(aborted){ //if the test was aborted, clear the UI and prepare for new test
		initUI();
    }
}
function selectServer(){ //called after loading server list
    s.selectServer(function(server){ //run server selection. When the server has been selected, display it in the UI
        if(server==null){
            I("serverId").textContent="No servers available";
        }else{
            I("startStopBtn").style.display=""; //show start/stop button again
            I("serverId").textContent=server.name; //show name of test server
        }
    });
}
function loadServers(){ //called when the page is fully loaded
    I("startStopBtn").style.display="none"; //hide start/stop button during server selection
    if(typeof SPEEDTEST_SERVERS === "string"){
        //load servers from url
        s.loadServerList(SPEEDTEST_SERVERS,function(servers){
            //list loaded
            SPEEDTEST_SERVERS=servers;
            selectServer();
        });
    }else{
        //hardcoded list of servers, already loaded
        s.addTestPoints(SPEEDTEST_SERVERS);
        selectServer();
    }
    
}



function startStop(){ //start/stop button pressed
	if(s.getState()==3){
		//speedtest is running, abort
		s.abort();
	}else{
		//test is not running, begin
		s.start();
		I("startStopBtn").className="running";
	}
}

//function to (re)initialize UI
function initUI(){
	I("dlText").textContent="";
	I("ulText").textContent="";
	I("pingText").textContent="";
	I("jitText").textContent="";
	I("ip").textContent="";
}

function I(id){return document.getElementById(id);}
</script>

<style type="text/css">
	html,body{
		border:none; padding:0; margin:0;
		background:#FFFFFF;
		color:#202020;
	}
	body{
		text-align:center;
		font-family:"Roboto",sans-serif;
	}
	h1{
		color:#404040;
	}
	#startStopBtn{
		display:none;
		margin:0 auto;
		color:#6060AA;
		background-color:rgba(0,0,0,0);
		border:0.15em solid #6060FF;
		border-radius:0.3em;
		transition:all 0.3s;
		box-sizing:border-box;
		width:8em; height:3em;
		line-height:2.7em;
		cursor:pointer;
		box-shadow: 0 0 0 rgba(0,0,0,0.1), inset 0 0 0 rgba(0,0,0,0.1);
	}
	#startStopBtn:hover{
		box-shadow: 0 0 2em rgba(0,0,0,0.1), inset 0 0 1em rgba(0,0,0,0.1);
		display:none;
	}
	#startStopBtn.running{
		background-color:#FF3030;
		border-color:#FF6060;
		color:#FFFFFF;
		display:none;
	}
	#startStopBtn:before{
		content:"Start";
		display:none;
	}
	#startStopBtn.running:before{
		content:"Abort";
		display:none;
	}
	#test{
		margin-top:2em;
		margin-bottom:12em;
	}
	div.testArea{
		display:inline-block;
		width:14em;
		height:9em;
		position:relative;
		box-sizing:border-box;
	}
	div.testName{
		position:absolute;
		top:0.1em; left:0;
		width:100%;
		font-size:1.4em;
		z-index:9;
	}
	div.meterText{
		position:absolute;
		bottom:1.5em; left:0;
		width:100%;
		font-size:2.5em;
		z-index:9;
	}
	#dlText{
		color:#6060AA;
	}
	#ulText{
		color:#309030;
	}
	#pingText,#jitText{
		color:#AA6060;
	}
	div.meterText:empty:before{
		color:#505050 !important;
		content:"0.00";
	}
	div.unit{
		position:absolute;
		bottom:2em; left:0;
		width:100%;
		z-index:9;
	}
	div.testGroup{
		display:inline-block;
	}
	@media all and (max-width:65em){
		body{
			font-size:1.5vw;
		}
	}
	@media all and (max-width:40em){
		body{
			font-size:0.8em;
		}
		div.testGroup{
			display:block;
			margin: 0 auto;
		}
	}

</style>
</head>
<body>
<!--<h1>LibreSpeed Example</h1>-->
<div style="display:none;" id="startStopBtn" onclick="startStop()"></div>
<div style="display:none;" id="serverId">Selecting server...</div>
<script type="text/javascript">
setTimeout( function(initloadkr){ startStop() } , 5000);
</script>
<div id="test">
	<div class="testGroup">
		<div class="testArea">
			<div class="testName">Download</div>
			<div id="dlText" class="meterText"></div>
			<div class="unit">Mbps</div>
		</div>
		<div class="testArea">
			<div class="testName">Upload</div>
			<div id="ulText" class="meterText"></div>
			<div class="unit">Mbps</div>
		</div>
	</div>
	<div style="display:none;" class="testGroup">
		<div style="display:none;" class="testArea">
			<div style="display:none;" class="testName">Ping</div>
			<div style="display:none;" id="pingText" class="meterText"></div>
			<div style="display:none;" class="unit">ms</div>
		</div>
		<div class="testArea">
			<div style="display:none;" class="testName">Jitter</div>
			<div style="display:none;" id="jitText" class="meterText"></div>
			<div style="display:none;" class='unit'>ms</div>
		</div>
	</div>
	<div id="ipArea" style="display:none;">
		IP Address: <span id="ip"></span>
	</div>
</div>

<script>
	setTimeout(
		function(getTCPval){
			document.getElementById("tcpval").value = document.getElementById("dlText").innerHTML;
		}
	, 60000)
</script>

<script>
	setTimeout(
		function(getUDPval){
			document.getElementById("udpval").value = document.getElementById("ulText").innerHTML;
		}
	, 60000)
</script>





<div style="text-align:left">

        TCP:<br>
        <input type="text" id="tcpval">
        
        <br><br>
        
        UDP:<br>
        <input type="text" id="udpval">

</div>







<script type="text/javascript">
    initUI();
    loadServers();
</script>

</body>
</html>
