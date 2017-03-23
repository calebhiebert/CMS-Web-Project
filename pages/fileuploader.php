<?php
require_once 'data/token.php';
// Processing an uploaded file. No security guards!

$error = false;
if ($_POST) {
    if($_FILES['uploaded_file']) {

        $file = $_FILES['uploaded_file'];

        $imageLocation = '..'.DIRECTORY_SEPARATOR.'images';
        $imageName = random_text('alpha', 16);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName =  $imageName.'.'.strtolower($ext);

        $fullPath = $imageLocation.DIRECTORY_SEPARATOR.$fileName;
        echo $fullPath;

        move_uploaded_file($file['tmp_name'], $fullPath);

        putImage($imageName, $ext, 6, $file['size'], 'Test Caption', $file['name']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Uploads</title>
</head>

<body>
<h1>File Uploads</h1>
<?php if ($error): ?>
    <p><?= $error ?></p>
<?php endif ?>
<form method="post" enctype="multipart/form-data">
    <label for="uploaded_file">Filename:</label>
    <input type="file" name="uploaded_file" id="uploaded_file" />
    <br />
    <input type="submit" name="submit" value="Submit" />
</form>

<?php if ($_POST): ?>
    <pre><?= print_r($_FILES) ?></pre>
<?php endif ?>
</body>
</html>