<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: Azure/index.php");
	}
} else {
	header("Location: Azure/index.php");
}
?>

<!DOCTYPE html>
    <html>
    <head>
            <title>Analisis Gambar dengan Azure Computer Vision</title>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    </head>
        <body>
        	<h1>Hasil Analisis</h1>
        <script type="text/javascript">
            $(document).ready(function () {
            var subscriptionKey = "5244d0b5afb846009d2d0c8f14313367";
            var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
            var params = {
                "visualFeatures": "Categories,Description,Color",
                "details": "",
                "language": "en",
            };
            var sourceImageUrl = "<?php echo $url ?>";
            document.querySelector("#sourceImage").src = sourceImageUrl;
            $.ajax({
                url: uriBase + "?" + $.param(params),
                beforeSend: function(xhrObj){
                    xhrObj.setRequestHeader("Content-Type","application/json");
                    xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
                },
                type: "POST",
                data: '{"url": ' + '"' + sourceImageUrl + '"}',
            })
            .done(function(data) {
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
                $("#description").text(data.description.captions[0].text);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
        });
    </script>
	<div id="wrapper" style="width:420px; display:table-cell;">
		<img id="sourceImage" width="400" />
		<br>
		<h3 id="description">Wait..</h3>
	</div>
</div>
</body>
</html>
