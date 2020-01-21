<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: analisa.php");
	}
} else {
	header("Location: analisa.php");
}
?>

<!DOCTYPE html>
    <html>
    <head>
    <title>Analisa Gambar dengan Azure Cognitive Service</title>
    </head>
        <body>
        <div class="topnav">
            <a href="http://arcosapp.azurewebsites.net/">Registri</a>
            <a class="active" href="http://arcosapp.azurewebsites.net/analisa.php">Analisa Gambar</a>
        </div>
        <h1>Hasil Analisa</h1>
        <script type="text/javascript">
            $(document).ready(function () {
            // **********************************************
            // *** Update or verify the following values. ***
            // **********************************************
            // Replace <Subscription Key> with your valid subscription key.
            var subscriptionKey = "371a5b82fd114364b68fddc33837286f";
            // You must use the same Azure region in your REST API method as you used to
            // get your subscription keys. For example, if you got your subscription keys
            // from the West US region, replace "westcentralus" in the URL
            // below with "westus".
            //
            // Free trial subscription keys are generated in the "westus" region.
            // If you use a free trial subscription key, you shouldn't need to change
            // this region.
            var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
            // Request parameters.
            var params = {
                "visualFeatures": "Categories,Description,Color",
                "details": "",
                "language": "en",
            };
            // Display the image.
            var sourceImageUrl = "<?php echo $url ?>";
            document.querySelector("#sourceImage").src = sourceImageUrl;
            // Make the REST API call.
            $.ajax({
                url: uriBase + "?" + $.param(params),
                // Request headers.
                beforeSend: function(xhrObj){
                    xhrObj.setRequestHeader("Content-Type","application/json");
                    xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
                },
                type: "POST",
                // Request body.
                data: '{"url": ' + '"' + sourceImageUrl + '"}',
            })
            .done(function(data) {
                // Show formatted JSON on webpage.
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
                // console.log(data);
                // var json = $.parseJSON(data);
                $("#description").text(data.description.captions[0].text);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
        });
    </script>
<br>
<div id="wrapper" style="width:1020px; display:table;">
	<div id="imageDiv" style="width:420px; display:table-cell;">
		<b>Source Image:</b>
		<br><br>
		<img id="sourceImage" width="400" />
		<br>
		<h3 id="description">Wait ..</h3>
	</div>
</div>
</body>
</html>
