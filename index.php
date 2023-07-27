<html>

<head>
    <script src="plotly-2.24.1.min.js" charset="utf-8"></script>
</head>
<body>
<h1>RTT Test</h1>

<div id="myDiv" style="width:800px;height:450px;"></div>


<iframe id="iframe1" frameBorder="0" width="500"></iframe>
  <script type="text/javascript">
    var ifr = window.document.getElementById("iframe1");
    ifr.src = "pingiframe.php"
    var mostRecentPingTime;
    if (typeof window.addEventListener != 'undefined') {
        window.addEventListener('message', function(e) {
            mostRecentPingTime = e.data[1];
        }, false);
    }
 

    var time = new Date();

    var data = [{
      x: [time],
      y: [mostRecentPingTime],
      mode: 'lines',
      line: {color: '#80CAF6', shape: 'spline'}
    }]


    Plotly.newPlot('myDiv', data);

    var cnt = 0;

    var interval = setInterval(function() {

      var time = new Date();

      var update = {
      x:  [[time]],
      y: [[mostRecentPingTime]]
      }

      Plotly.extendTraces('myDiv', update, [0])

      if(++cnt === 1000) clearInterval(interval);
    }, 1000);

 
  </script>

<hr />


<div>

<?php
    
if (isset($_SERVER['REMOTE_ADDR'])) {
    echo "Client IP: " . $_SERVER['REMOTE_ADDR'] . ":" . $_SERVER['REMOTE_PORT'];
}
if (isset($_SERVER['REMOTE_HOST'])) {
    echo "<br>Client Name: ". $_SERVER['REMOTE_HOST'];
}   
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    echo "<br>Proxy IP: " . $_SERVER['HTTP_X_FORWARDED_FOR'];
}   

?>
</div>



<div id="footer">
<?php
$aws_test = shell_exec("curl -s http://169.254.169.254/latest/meta-data/public-ipv4 -m .5");
if($aws_test){
  $aws_publicIP = shell_exec("curl -s http://169.254.169.254/latest/meta-data/public-ipv4");
  $aws_privateIP = shell_exec("curl -s http://169.254.169.254/latest/meta-data/local-ipv4");
  $aws_publicHostname = shell_exec("curl -s http://169.254.169.254/latest/meta-data/public-hostname");
  $aws_AZ = shell_exec("curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone");
  echo "We're running in: $aws_AZ<br>$aws_publicHostname<br>external IP: $aws_publicIP<br>internal IP: $aws_privateIP";
} else {
  echo "Metadata not available.";
}
?>
</div>
</body>
</html>
