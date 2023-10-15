<?php
    namespace MicrosoftAzure\Storage\Samples;
    require_once "./vendor/autoload.php";

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=burinstorage;AccountKey=6YeWBTRUXt17J4E2MKwJ/adg9Wj1Bd9jJiXWwuAbYMC51NnoMQC/DPZcyREYx53ZeA4qUgFzlfJl+AStIyuR8Q==';
    $containerName = "mybob";
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    $blobName = urldecode($_GET["name"]);
    try {
        $url = "https://burinstorage.blob.core.windows.net/mybob/".$blobName;
        header("Location: $url");
    } catch (ServiceException $e) {
        echo '<script>alert("'.$e->getMessage().'");</script>';
    }
?>




