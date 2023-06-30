<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

// ############ Turn off error reporting to prevent white box and black box hacking
	
	##### LIVE SERVER
	#error_reporting(0); // <- ACTIVATE THIS ON PRODUCTION
	
	##### CONNECT TO USER DATABASE
	//echo "111111".$_SESSION['database'];
	$conn = mysqli_connect('localhost','edmskrcredsphilnavy','2r4FlCfQVZeLJboZ','dbspeedtest') or die ("error");
	if(mysqli_connect_errno($conn))	echo "CONNECTION FAILED: " .mysqli_connect_error();
	
##### SET SYSTEM TIMEZONE
date_default_timezone_set('Asia/Manila');

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Speed Test</title>
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
		background:#CCC;
		color:#202020;
	}
	body{
		text-align:center;
		font-family:"Roboto",sans-serif;
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
		margin-top:0em;
		margin-bottom:0em;
	}
	div.testArea{
		display:inline-block;
		width:14em;
		height:10em;
		position:relative;
		box-sizing:border-box;
		background-color:#FFF;
		border-radius:10px;
	}
	div.testName{
		position:absolute;
		top:1em; left:0;
		width:100%;
		font-size:1.4em;
		z-index:9;
	}
	div.meterText{
		position:absolute;
		bottom:1.2em; left:0;
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
	#dlText1{
		color:#6060AA;
	}
	#ulText1{
		color:#309030;
	}
	#dtText{
		color:#505050;
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
		bottom:1em; left:0;
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

<?php

	if($_SERVER["REQUEST_METHOD"] == "POST"){ # POST

		$tcpval = $_POST['tcpval'];
		$udpval = $_POST['udpval'];
		$dteval = date('Y-m-d H:i:s');
  
		/* INSERT TO EDF TABLE */
		$query = mysqli_query($conn,"INSERT INTO `archive`(
				tcpval,
				udpval,
				dteval
		)VALUES(
				'".$tcpval."',
				'".$udpval."',
				'".$dteval."'
		)");
		
		?>
		
        <div style="height:20px;"></div>
        
        <div style="display:none;" id="startStopBtn" onclick="startStop()"></div>
        <div style="display:none;" id="serverId">Selecting server...</div>
        
        <div id="test">
            <div class="testGroup">
                <div class="testArea">
                    <div class="testName"><strong>DOWNLOAD</strong></div>
                    <div id="dlText1" class="meterText"><?php echo $tcpval; ?></div>
                    <div class="unit">Mbps</div>
                </div>
                <div class="testArea">
                    <div class="testName"><strong>UPLOAD</strong></div>
                    <div id="ulText1" class="meterText"><?php echo $udpval; ?></div>
                    <div class="unit">Mbps</div>
                </div>
            </div>
        </div>

		<?php
		
	} else { # POST ELSE
		
		?>
        
        <div style="height:20px;"></div>
        
        <div style="display:none;" id="startStopBtn" onclick="startStop()"></div>
        <div style="display:none;" id="serverId">Selecting server...</div>
        
        <div id="test">
            <div class="testGroup">
                <div class="testArea">
                    <div class="testName"><strong>DOWNLOAD</strong></div>
                    <div id="dlText" class="meterText"></div>
                    <div class="unit">Mbps</div>
                </div>
                <div class="testArea">
                    <div class="testName"><strong>UPLOAD</strong></div>
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
                <div style="display:none;" class="testArea">
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
				function(initloadkr){
					startStop()
				}
			, 5000);
            
            setTimeout(
                function(getTCPval){
                    document.getElementById("tcpval").value = document.getElementById("dlText").innerHTML;
                }
            , 45000);
        
            setTimeout(
                function(getUDPval){
                    document.getElementById("udpval").value = document.getElementById("ulText").innerHTML;
                }
            , 45000);
        
            setTimeout(
                function(formSUBMIT){
                    document.getElementById("spdtstfrm").submit();
                }
            , 60000);
        
        </script>
        
        <form method="post" id="spdtstfrm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:none" >
        
                TCP:<br>
                <input type="text" name="tcpval" id="tcpval">
                
                <br><br>
                
                UDP:<br>
                <input type="text" name="udpval" id="udpval">
                
                <input type="submit" />
        
        </form>
        
        
        <?php
	
	} # POST ELSE CLOSE

?>

    <div style="height:10px;"></div>
    
    <table align="center" width="100%" border="0" bordercolor="#333333" cellspacing="8px" cellpadding="10px">
    
        <tr>
            <td align="center" colspan="4" style="border-radius:10px;background-color:#FFF;"><font size="+1"><strong>SPEED TEST LOGS</strong></font></td>
        </tr>
        
        <tr>
            <td align="center" width="10%" style="border-radius:10px;background-color:#FFF;"><strong>#</strong></td>
            <td align="center" width="30%" style="border-radius:10px;background-color:#FFF;"><strong>DATE & TIME</strong></td>
            <td align="center" width="30%" style="border-radius:10px;background-color:#FFF;"><strong>DOWNLOAD</strong></td>
            <td align="center" width="30%" style="border-radius:10px;background-color:#FFF;"><strong>UPLOAD</strong></td>
        </tr>
    
        <?php
        
        $archivedata = mysqli_query($conn,"SELECT * FROM `archive` ORDER by id DESC;");
        $rowno = 1;
    
        while($data = mysqli_fetch_array($archivedata,MYSQLI_ASSOC))
        {
            ?>
            
                <tr>
                    <td align="center" style="border-radius:10px;background-color:#FFF;"><strong><?php echo $rowno; ?></strong></td>
                    <td align="center" style="border-radius:10px;background-color:#FFF;"><strong><?php echo strtoupper(date('M j, Y - Hi', strtotime($data['dteval'])))."H"; ?></strong></td>
                    <td align="center" style="border-radius:10px;background-color:#FFF;color:#6060AA;"><strong><?php echo $data['tcpval']; ?>&nbsp;Mbps</strong></td>
                    <td align="center" style="border-radius:10px;background-color:#FFF;color:#309030"><strong><?php echo $data['udpval']; ?>&nbsp;Mbps</strong></td>
                </tr>
            
            <?php
            
            $rowno++;
            
        }
        
        
        ?>
    
    </table>

<?php
header("Refresh:600");

		/*
		300 	= 5 mins
		600 	= 10 mins
		900 	= 15 mins
		1800 	= 30 mins
		3600 	= 60 mins
		*/
?>

<script type="text/javascript">
    initUI();
    loadServers();
</script>

</body>
</html>