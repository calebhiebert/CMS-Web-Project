<?php
require_once 'data/token.php';

use \Eventviva\ImageResize;
try {
    if (isset($_FILES['image']) || isset($_GET['f_id'])) {
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $entityId = filter_input(INPUT_GET, 'entityid', FILTER_SANITIZE_NUMBER_INT);
        $f_id = filter_input(INPUT_GET, 'f_id', FILTER_SANITIZE_URL);

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            handleImage($entityId, $token, $_FILES['image']['name'], $_FILES['image']['size'], $_FILES['image']['tmp_name']);
        } else if ($f_id != null) {
            $f_secret = filter_input(INPUT_GET, 'f_secret', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $f_server = filter_input(INPUT_GET, 'f_server', FILTER_SANITIZE_NUMBER_INT);
            $f_farm = filter_input(INPUT_GET, 'f_farm', FILTER_SANITIZE_NUMBER_INT);
            $f_format = filter_input(INPUT_GET, 'f_format', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $tmpFile = '../images/' . $f_id . '.' . $f_format;
            $url = constructFlickrUrl(['server' => $f_server, 'farm' => $f_farm, 'secret' => $f_secret, 'id' => $f_id], 'o', $f_format);

            try {
                $client = new GuzzleHttp\Client();
                $res = $client->request('GET', $url, [
                    'verify' => false,
                    'sink' => $tmpFile
                ]);

                handleImage($entityId, $token, 'Flickr Photo.jpg', 50, $tmpFile);
            } catch (Exception $e) {
            }
        }
    }
} catch (Exception $e) {

}

redirect('/entity/'.$entityId.'/images');

function handleImage($entityId, $token, $name, $size, $tmpName) {
    $log = 'Token: '.$token.' EntityId: '.$entityId.' ';

    $entity = getEntity($entityId);
    $tokenValid = validateToken($token);

    if($tokenValid && $entity != null) {
        $user = getUserByToken($token);

        // generate a random id for this image
        $imageId = random_text('alpha', 16);

        echo $name;

        // get the file extension
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if(in_array($ext, IMAGE_FILE_TYPES)) {

            // create a new image object to resize the image
            $image = new ImageResize($tmpName);

            // save to all defined sizes
            foreach (IMAGE_FILE_SIZES as $name => $pxHeight) {
                if (!file_exists(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name))
                    mkdir(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name, 0777, true);

                $image->resizeToWidth($pxHeight, true);
                $image->save(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $imageId . '.' . $ext);
                $log .= 'Saved for ' . $name . ' ';
            }

            // add the image to the database
            putImage($imageId, $ext, $entity->getId(), $size, null, substr($name, 0, 60));

            $edit = new Edit();
            $edit->setPictureId($imageId);
            $edit->setUserId($user->getId());
            putEditEntry($edit);

            $log .= 'Success!';
        } else {
            $log .= 'Invalid format. ';
        }
    } else {
        if(!$tokenValid) {
            $log .= 'Token invalid! ';
        }

        if($entity == null) {
            $log .= 'Entity does not exist! ';
        }
    }

    echo $log;
//    file_put_contents('../logs/image.log', $log."\n", FILE_APPEND);
}
?>