<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['url'])) {
        $url = $_POST['url'];
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

    <script language="javascript">
        document.getElementById('analyze_btn').click(); 
    </script>

</head>
<body>
 
<script type="text/javascript">
    function processImage() {
        
        var subscriptionKey = "371a5b82fd114364b68fddc33837286f";
 
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0";
 
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        var sourceImageUrl = document.getElementById("inputImage").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "/analyze?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>
 
<h1>Analyze image:</h1>
Tekan tombol <strong>Analyze image</strong> untuk memulai proses analisis gambar.
<br><br>
URL gambar:
<input type="text" name="inputImage" id="inputImage"
    value="<?php echo $url ?>" readonly />
<button id="analyze_btn" onclick="processImage()">Analyze image</button>
<br><br>
<script language="javascript">
document.getElementById('analyze_btn').click(); 
</script>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
</body>
</html>
