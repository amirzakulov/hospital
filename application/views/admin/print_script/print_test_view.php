<!--<div class="row">-->
<!--    <div class="col-lg-8">-->
<!--        <input id="wccpPrintBtn" type="button" style="font-size:18px" onclick="javascript:jsWebClientPrint.print('printerName=Thermal58');" value="Print Label..." />-->
<!--    </div>-->
<!--</div>-->
<!--<script type="text/javascript">-->
<!---->
<!--    var wcppPingTimeout_ms = 60000; //60 sec-->
<!--    var wcppPingTimeoutStep_ms = 500; //0.5 sec-->
<!---->
<!--</script>-->

<div id="container">
    <h1>Welcome to CodeIgniter!</h1>
    <div id="msgInProgress">
        <div id="mySpinner" style="width:32px;height:32px"></div>
        <br />
        Detecting WCPP utility at client side...
        <br />
        Please wait a few seconds...
        <br />
    </div>
    <div id="msgInstallWCPP" style="display:none;">
        <h3>Kompyuteringizda WCPP dasturi o'rnatilmagan.<br /></h3>
        <p>Printerdan foydalanish uchun, <a href="http://localhost/webprint/downloads/wcpp-6.0.0.0-win.exe" target="_blank">WCPP dasturini</a> ko'chirib oling va o'rnating!</p>

        <h3>#2 After installing WCPP...</h3>
        <p>
            <a href="PrintESCPOS.php">You can go and test the printing page</a>
        </p>
    </div>

</div>
<!-- Store User's SessionId -->
<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
<br />
<input id="wccpPrintBtn" type="button" style="display:none; font-size:18px" onclick="javascript:jsWebClientPrint.print('printerName=Thermal58&payment_id=8');" value="Print Label..." />

<script type="text/javascript">

    /*************************
     * WCPP detection
     * */
    var wcppPingTimeout_ms = 60000; //60 sec
    var wcppPingTimeoutStep_ms = 500; //0.5 sec

    function wcppDetectOnSuccess(){
        // WCPP utility is installed at the client side
        // redirect to WebClientPrint sample page

        // get WCPP version
        var wcppVer = arguments[0];

        if(wcppVer.substring(0, 1) == "6") {
            $('#wccpPrintBtn').show();
            $('#msgInProgress').hide();
        } else {
            wcppDetectOnFailure();
        }
    }

    function wcppDetectOnFailure() {
        // It seems WCPP is not installed at the client side
        // ask the user to install it
        $('#msgInProgress').hide();
        $('#msgInstallWCPP').show();
    }

    /***************************
     * end WCPP Detection
     * **************************/

    var wcppGetPrintersTimeout_ms = 60000; //60 sec
    var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

    function wcpGetPrintersOnSuccess(){
        // Display client installed printers
        if(arguments[0].length > 0){
            var p=arguments[0].split("|");
            var options = '';
            for (var i = 0; i < p.length; i++) {
                options += '<option>' + p[i] + '</option>';
            }
            $('#installedPrinters').css('visibility','visible');
            $('#installedPrinterName').html(options);
            $('#installedPrinterName').focus();
            $('#loadPrinters').hide();
        }else{
            alert("No printers are installed in your system.");
        }
    }

    function wcpGetPrintersOnFailure() {
        // Do something if printers cannot be got from the client
        alert("No printers are installed in your system.");
    }
</script>

<?php
echo $wcppDetectionScript;
echo $wcppScript;
?>
