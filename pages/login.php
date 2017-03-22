<?php
/**
 * Allows a user to log in
 */

require_once "data/token.php";

if($token_valid) {
    header('Location: /');
    exit;
} else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));

    if (strlen($username) < USERNAME_MIN_LENGTH || strlen($username) > USERNAME_MAX_LENGTH) {
        $bad_login = true;
    }

    if (strlen($password) == 0) {
        $bad_login = true;
    }

    if (!isset($bad_login)) {
        try {
            $stmt = $db->prepare('SELECT Id, Password FROM Users WHERE Username = :username OR Email = :username');
            $stmt->bindValue(':username', $username);
            $stmt->execute();
        } catch (PDOException $e) {
            //TODO handle error
        }

        if ($stmt->rowCount() != 1) {
            $bad_login = true;
        } else {
            $row = $stmt->fetch();

            if (password_verify($password, $row['Password'])) {
                $token = newToken($row['Id']);

                if($token !== false) {
                    setcookie('token', $token, time() + TOKEN_LIFE, '/');
                    header('Location: /');
                    exit;
                }
            } else {
                $bad_login = true;
            }
        }
    }
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Log In<?php endblock() ?>

<?php startblock('body') ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="card col-md-5">
                <div class="card-block">
                    <form action="/login" method="post">
                        <fieldset class="form-group">
                            <label for="inpt-username" class="form-control-label">Username</label>
                            <div class="input-group">
                                <i class="fa fa-user-circle input-group-addon"></i>
                                <input type="text" class="form-control" name="username" id="inpt-username" value="<?= isset($username) ? $username : '' ?>">
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="inpt-password" class="form-control-label">Password</label>
                            <div class="input-group">
                                <i class="fa fa-key input-group-addon"></i>
                                <input type="password" class="form-control" name="password" id="inpt-password" value="<?= isset($password) ? $password : '' ?>">
                            </div>
                        </fieldset>
                        <?php if(isset($bad_login)): ?>
                            <div class="alert alert-danger">
                                This login is not correct!
                            </div>
                        <?php endif ?>
                        <button type="submit" class="btn btn-primary">Log In</button>
                        <a class="btn btn-secondary" href="/register">Register</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endblock() ?>
