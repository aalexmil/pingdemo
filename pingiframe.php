<html>
<script>

   var Ping = function(opt) {
       this.opt = opt || {};
       this.favicon = this.opt.favicon || "/favicon.ico";
       this.timeout = this.opt.timeout || 0;
       this.logError = this.opt.logError || false;
   };

   Ping.prototype.ping = function(source, callback) {
       var promise, resolve, reject;
       if (typeof Promise !== "undefined") {
           promise = new Promise(function(_resolve, _reject) {
               resolve = _resolve;
               reject = _reject;
           });
       }

       var self = this;
       self.wasSuccess = false;
       self.img = new Image();
       self.img.onload = onload;
       self.img.onerror = onerror;

       var timer;
       var start = new Date();

       function onload(e) {
           self.wasSuccess = true;
           pingCheck.call(self, e);
       }

       function onerror(e) {
           self.wasSuccess = false;
           pingCheck.call(self, e);
       }

       if (self.timeout) {
           timer = setTimeout(function() {
               pingCheck.call(self, undefined);
       }, self.timeout); }

       function pingCheck() {
           if (timer) { clearTimeout(timer); }
           var pong = new Date() - start;

           if (!callback) {
               if (promise) {
                   return this.wasSuccess ? resolve(pong) : reject(pong);
               } else {
                   throw new Error("Promise is not supported by your browser. Use callback instead.");
               }
           } else if (typeof callback === "function") {
               if (!this.wasSuccess) {
                   if (self.logError) { console.error("error loading resource"); }
                   if (promise) { reject(pong); }
                   return callback("error", pong);
               }
               if (promise) { resolve(pong); }
               return callback(null, pong);
           } else {
               throw new Error("Callback is not a function.");
           }
       }

       self.img.src = source + self.favicon + "?" + (+new Date());
       return promise;
   };

   if (typeof exports !== "undefined") {
       if (typeof module !== "undefined" && module.exports) {
           module.exports = Ping;
       }
   } else {
       window.Ping = Ping;
   }

    
    
    
    
    
    var p = new Ping();
    var foobar = -1
    x = p.ping("http://globalaccelerator.polemikon.ca", function(err, data) {
        document.getElementById("ping-alex").innerHTML = data;
        foobar = data;
    }).then(function(result) {
  //console.log("Posting result:"+result); // "Stuff worked!";
  window.parent.postMessage(['varA', result], '*');
}, function(err) {
  console.log(err); // Error: "It broke"
});

    
    
    
</script>

RTT from your web browser to this AWS server (http://globalaccelerator.polemikon.ca): <span id="ping-alex"></span>ms
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

<script>
setTimeout(function(){
   window.location.reload(1);
}, 1000);
</script>
</html>
