<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=arcosblob;AccountKey=4Uk/MN5y2rJc5O6at5AEmimtW1O9mjVX4F1HFPzaNunWSqnS1iUjq4nBfo6ekcApGRiMO6gpps3pyPuX5OVQRQ==;EndpointSuffix=core.windows.net";
$containerName = "arco";
// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
if (isset($_POST['submit'])) {
	$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	// echo fread($content, filesize($fileToUpload));
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: analisa.php");
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>
<!DOCTYPE html>
<html>
 <head>
    <title>Unggah dan Analisa Gambar dengan Azure Cognitive Service</title>
  </head>
  <style type="text/css">
  .topnav {
  background-color: #333;
  overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
  background-color: #ddd;
  color: black;
}

/* Add a color to the active/current link */
.topnav a.active {
  background-color: #4CAF50;
  color: white;
}
</style>
<body>
<div class="topnav">
  <a href="http://arcosapp.azurewebsites.net/">Registri</a>
  <a class="active" href="https://arcosapp.azurewebsites.net/analisa.php">Analisa Gambar</a>
</div>

	<h1>Pilih, Unggah dan Analisa Gambar:</h1>
	<br><br>
	<form action="analisa.php" method="post">
		<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
		<input type="submit" name="submit" value="Upload">
	</form>
	<table class='table table-hover'>
			<thead>
				<tr>
					<th>Name</th>
					<th>URL</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="AZvision.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Analisa" class="btn btn-primary">
								</form>
							</td>
						</tr>
						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
			</tbody>
	</table>
  </body>
</html>
