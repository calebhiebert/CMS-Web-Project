<?php
require_once 'data/token.php';


use \Eventviva\ImageResize;

$log = '';

if (isset($_FILES['image'])) {
    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $entityId = filter_input(INPUT_GET, 'entityid', FILTER_SANITIZE_NUMBER_INT);

    $log .= 'Token: '.$token.' EntityId: '.$entityId.' ';

    $entity = getEntity($entityId);
    $tokenValid = validateToken($token);

    if($tokenValid && $entity != null) {
        $user = getUserByToken($token);
        $file = $_FILES['image'];

        if ($file['error'] == 0) {

            // generate a random id for this image
            $imageId = random_text('alpha', 16);

            // get the file extension
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if(in_array($ext, IMAGE_FILE_TYPES)) {

                // create a new image object to resize the image
                $image = new ImageResize($file['tmp_name']);

                // save to all defined sizes
                foreach (IMAGE_FILE_SIZES as $name => $pxHeight) {
                    if (!file_exists(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name))
                        mkdir(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name, 0777, true);

                    $image->resizeToWidth($pxHeight, true);
                    $image->save(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $imageId . '.' . $ext);
                    $log .= 'Saved for ' . $name . ' ';
                }

                // add the image to the database
                putImage($imageId, $ext, $entity->getId(), $file['size'], null, substr($file['name'], 0, 60));

                $edit = new Edit();
                $edit->setPictureId($imageId);
                $edit->setUserId($user->getId());
                putEditEntry($edit);

                $log .= 'Success!';
            } else {
                $log .= 'Invalid format. ';
            }
        } else {
            $log .= 'Upload Error: '.$file['error'];
        }
    } else {
        if(!$tokenValid) {
            $log .= 'Token invalid! ';
        }

        if($entity == null) {
            $log .= 'Entity does not exist! ';
        }
    }
} else {
    $log .= 'Incorrect form submission! ';
}

echo $log;
file_put_contents('../logs/image.log', $log."\n", FILE_APPEND);
?>