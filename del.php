<?php
    namespace MicrosoftAzure\Storage\Samples;

    require_once "./vendor/autoload.php";

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=burinstorage;AccountKey=6YeWBTRUXt17J4E2MKwJ/adg9Wj1Bd9jJiXWwuAbYMC51NnoMQC/DPZcyREYx53ZeA4qUgFzlfJl+AStIyuR8Q==';
    $containerName = "mybob";
    $id = $_GET["name"];
    $cleaned_name = str_replace('%', '', $id);
    $filePathToDelete = $cleaned_name;

    try {
        $blobClient = BlobRestProxy::createBlobService($connectionString);
        $blobClient->deleteBlob($containerName, $filePathToDelete);
        header("Location: index.php");
    } catch (ServiceException $e) {
        echo "Error uploading file: " . $e->getMessage();
    }
?>
 