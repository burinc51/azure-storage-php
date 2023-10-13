<?php
namespace MicrosoftAzure\Storage\Samples;
require_once "./vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

$connectionString = 'DefaultEndpointsProtocol=https;AccountName=burinstorage;AccountKey=6YeWBTRUXt17J4E2MKwJ/adg9Wj1Bd9jJiXWwuAbYMC51NnoMQC/DPZcyREYx53ZeA4qUgFzlfJl+AStIyuR8Q==';
$containerName = "mybob";
$blobClient = BlobRestProxy::createBlobService($connectionString);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $fileName = $_FILES["fileToUpload"]["name"];
    $filePath = $_FILES["fileToUpload"]["tmp_name"];
    if (preg_match('/[ก-๙]/u', substr($fileName,0,-4)) || preg_match('/[0-9]/',substr($fileName,0,-4))) {
        echo "
        <script> alert ('Please upload the file in English.');
            window.location.href  = 'index.php';
        </script>";
    }else{
        if ($fileName == ""){
            echo "
            <script> alert ('Error uploaded successfully.');
                window.location.href  = 'index.php';
            </script>";
        }
        try {
            $blobClient->createBlockBlob(
                $containerName,
                $fileName,
                fopen($filePath, "r")
            );
            echo "
                <script> alert ('File uploaded successfully.');
                    window.location.href  = 'index.php';
                </script>";
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo "HTTP status code: $code\n";
            echo "Error message: $error_message\n";
        } 
    }
}else{
    echo "
        <script> alert ('Error uploaded.');
            window.location.href  = 'index.php';
        </script>";
}
?>
