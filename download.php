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
        $blob = $blobClient->getBlob($containerName, $blobName);

        $fileext = pathinfo($blobName, PATHINFO_EXTENSION);
        if ($fileext === "pdf") {
            header('Content-type: application/pdf');
        } else if ($fileext === "doc") {
            header('Content-type: application/msword');
        } else if ($fileext === "docx") {
            header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        } else if ($fileext === "txt") {
            header('Content-type: text/plain');
        } else if ($fileext === "jpg" || $fileext === "jpeg") {
            header('Content-type: image/jpeg');
        } else if ($fileext === "png") {
            header('Content-type: image/png');
        } else if ($fileext === "gif") {
            header('Content-type: image/gif');
        }
        header("Content-Disposition: attachment; filename=\"" . $blobName . "\"");
        fpassthru($blob->getContentStream());
    } catch (ServiceException $e) {
        echo '<script>alert("'.$e->getMessage().'");</script>';
    }
    // try {
    //     $url = "https://burinstorage.blob.core.windows.net/mybob/".$blobName;
    //     header("Location: $url");
    // } catch (ServiceException $e) {
    //     echo '<script>alert("'.$e->getMessage().'");</script>';
    // }
?>




