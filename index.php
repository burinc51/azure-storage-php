<?php
    require_once "./vendor/autoload.php";

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
  

    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=burinstorage;AccountKey=6YeWBTRUXt17J4E2MKwJ/adg9Wj1Bd9jJiXWwuAbYMC51NnoMQC/DPZcyREYx53ZeA4qUgFzlfJl+AStIyuR8Q==';
    $containerName = "mybob";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@1,200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <style>
        * {
            font-family: 'Prompt', sans-serif;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            justify-content: center;
            align-items: center;
        }
        .popup img {
        display: block;
        max-width: 80%;
        max-height: 80%;
        margin: auto;
        margin-top:2%;
        }
        .close {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        }
    </style>
    <script>
        function myFunction(value) {
            var result  = confirm("Do you want to Delete the " + value + "!");
            if(result){
                window.location.href = "./del.php?name="+value;
                alert(" <?php echo "comfirm"; ?> ");
            }
        }
        function togglePopup(imageId) {
            const popup = document.getElementById('popup-' + imageId);
            popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';
        }
        $(document).ready(function() {
        $('.image-link').magnificPopup({
            type: 'image',
            gallery: {
            enabled: true
            }
        });
        });
    </script>
</head>
<body>
        <section class="container mt-5">
            <div class="row">
                    <form method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="mx-auto p-2">
                                    <label for="formFileLg" class="form-label">File Upload</label>
                                    <input class="form-control form-control-lg" id="formFileLg" type="file" name="fileToUpload">
                            </div>
                            <div class="ms-auto p-2" style="width: 120px;">
                                <button type="submit" class="btn btn-success" formaction="./upload.php"><i class="bi bi-cloud-arrow-up"></i> Upload</button>
                            </div>
                    </form >
                            <!--table-->
                            <form method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-secondary">
                                        <tr class="active">
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <?php
                                            try {
                                                $blobClient = BlobRestProxy::createBlobService($connectionString);
                                                $blobs = $blobClient->listBlobs($containerName);
                                                $index=0;
                                                foreach ($blobs->getBlobs() as $blob) {
                                                    $array[$index] = array( "name" => $blob->getName(),"Size" => $blob->getProperties()->getContentLength(),"Date" =>$blob->getProperties()->getCreationTime()->getTimestamp());
                                                $index++;
                                                }

                                                function compareDates($a, $b) {
                                                    return $b['Date'] - $a['Date'];
                                                }

                                                usort($array, 'compareDates');

                                                foreach($array as $key => $value){
                                        ?>
                                        
                                    <tbody>
                                        <tr>
                                            <td><?php echo $value['name']; ?> 
                                                <?php
                                                    $sub = substr($value['name'],-4);
                                                    if($sub == '.png' || $sub == '.gif' || $sub == '.jpg' || $sub == 'jpeg'){
                                                        ?>
                                                            <div class="float-end">
                                                                <button class="btn btn-primary text-right image-link" data-mfp-src="https://burinstorage.blob.core.windows.net/mybob/<?php echo $value['name'];?>">Show</button>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $value['Size']." Bytes"; ?></td>
                                            <td><?php echo date('H:i:s d-m-Y', strval($value['Date']));?></td>
                                            <td>
                                                <div class="btn-group ">
                                                    <a href="https://burinstorage.blob.core.windows.net/mybob/<?php echo $value['name']; ?>" class="btn btn-info" > <i class="bi bi-cloud-download"></i> Download</a>
                                                    <button class="btn btn-danger" onclick="myFunction('<?php echo $value['name'];?>')"><i class="bi bi-trash3"></i> Delete</button>
                                                </div>
                                            </td>
                                        <?php
                                            }
                                            } catch (ServiceException $e) {
                                                echo '<script>alert("'.$e->getMessage().'");</script>';
                                            }
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                        </div>
                    </div>    
        </section>
</body>
</html>
