<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=arcosblob;AccountKey=4Uk/MN5y2rJc5O6at5AEmimtW1O9mjVX4F1HFPzaNunWSqnS1iUjq4nBfo6ekcApGRiMO6gpps3pyPuX5OVQRQ==";
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
 	<title>Analisa Gambar dengan Azure Cognitive Service</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">
    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
</head>
	<style type="text/css">
		/* Add a black background color to the top navigation */
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
  <a class="active" href="http://arcosapp.azurewebsites.net/analisa.php">Analisa Gambar</a>
</div> 
        		<h1>Analisis Kendaraan</h1>
		<div class="mt-4 mb-2">
			<form class="d-flex justify-content-lefr" action="analisa.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
				<input type="submit" name="submit" value="Upload">
			</form>
		</div>
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
									<input type="submit" name="submit" value="Analisa Now" class="btn btn-primary">
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

	</div>

<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
  </body>
</html>
