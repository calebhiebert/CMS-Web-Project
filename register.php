<?php
/**
 * Allows a user to register for an account
 */
require_once "db.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $passwordConf = filter_input(INPUT_POST, 'password_conf', FILTER_SANITIZE_STRING);
    $employeeCode = filter_input(INPUT_POST, 'employee_code', FILTER_SANITIZE_SPECIAL_CHARS);

    if (strlen(trim($username)) < USERNAME_MIN_LENGTH || strlen(trim($username)) > 60) {
        $msgUname = 'Your username must be between 5 and 60 characters long';
    }

    if (strlen(trim($email)) == 0) {
        $msgEml = 'You must enter an email';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgEml = 'Please enter a valid email';
    } else if (strlen(trim($email)) > 255) {
        $msgEml = 'This email is too long! The max length is 255 characters';
    }

    if(strlen(trim($password)) == 0) {
        $msgPwd = 'Please fill out both password fields';
    } else if (trim($password) != trim($passwordConf)) {
        $msgPwd = 'These passwords do not match';
    }

    if(strlen(trim($employeeCode)) != 16) {
        $msgEmpCode = 'Your employee code should be 16 characters long';
    }

    if(!isset($msgUname) && !isset($msgEml) && !isset($msgPwd) && !isset($msgEmpCode)) {

        $permLevel = getEmployeeCodePermLevel($employeeCode);

        if($permLevel == -1) {
            $msgEmpCode = 'This is not a valid employee code';
        } else {
            $db->beginTransaction();

            invalidatemployeeCode($employeeCode);

            $pwHash = password_hash($password, PASSWORD_BCRYPT);

            try {
                $stmt = $db->prepare('INSERT INTO Users (Username, Password, Email, PermLevel) VALUES (:username, :pwHash, :email, :permLevel)');
                $stmt->execute(['username' => $username, 'pwHash' => $pwHash, 'email' => $email, 'permLevel' => $permLevel]);
                $db->commit();
                header('Location: /login');
            } catch (PDOException $e) {
                if(strpos($e->getMessage(), 'uc_Username') !== false) {
                    $msgUname = 'This username is taken';
                } else if(strpos($e->getMessage(), 'uc_Email') !== false) {
                    $msgEml = 'This email is already in use';
                } else {
                    echo $e->getMessage();
                }

                $db->rollBack();
            }
        }
    }
}

function getEmployeeCodePermLevel($code) {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Code, PermLevel FROM RegistrationCodes WHERE Code = :code');
        $stmt->bindValue(':code', $code);
        $stmt->execute();

        if ($stmt->rowCount() != 1) {
            return -1;
        } else {
            return $stmt->fetchColumn(1);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return null;
}

function invalidatemployeeCode($code) {
    global $db;

    $stmt = $db->prepare('DELETE FROM RegistrationCodes WHERE Code = :code');
    $stmt->bindValue(':code', $code);
    $stmt->execute();
}
?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Register<?php endblock() ?>
<?php startblock('body') ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-block">
            <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <form action="register" method="post">
                    <fieldset class="form-group<?= isset($msgUname) ? ' has-danger' : '' ?>">
                        <label for="inpt-username" class="form-control-label">Username</label>
                        <input type="text" class="form-control" name="username" id="inpt-username" value="<?= $username ?>" minlength="5" maxlength="60" required>
                        <div class="form-control-feedback"><?= isset($msgUname) ? $msgUname : '' ?></div>
                    </fieldset>
                    <fieldset class="form-group<?= isset($msgEml) ? ' has-danger' : '' ?>">
                        <label for="inpt-email" class="form-control-label">Email</label>
                        <input type="email" class="form-control" name="email" id="inpt-email" value="<?= $email ?>" maxlength="255" required>
                        <div class="form-control-feedback"><?= isset($msgEml) ? $msgEml : '' ?></div>
                    </fieldset>
                    <fieldset class="form-group<?= isset($msgPwd) ? ' has-danger' : '' ?>">
                        <label for="inpt-password" class="form-control-label">Password</label>
                        <input type="password" class="form-control" name="password" id="inpt-password" value="<?= !isset($msgPwd) ? $password : '' ?>" required>
                        <label for="inpt-password-conf" class="form-control-label">Password (again)</label>
                        <input type="password" class="form-control" name="password_conf" id="inpt-password-conf" value="<?= !isset($msgPwd) ? $passwordConf : '' ?>" required>
                        <div class="form-control-feedback"><?= isset($msgPwd) ? $msgPwd : '' ?></div>
                    </fieldset>
                    <fieldset class="form-group<?= isset($msgEmpCode) ? ' has-danger' : '' ?>">
                        <label for="impt-employee-code" class="form-control-label">Employee Code</label>
                        <input type="text" class="form-control" name="employee_code" id="impt-employee-code" value="<?= $employeeCode ?>" required>
                        <div class="form-control-feedback"><?= isset($msgEmpCode) ? $msgEmpCode: '' ?></div>
                        <small class="form-text text-muted">You should have received this code from HR with your employee manual</small>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            <?php else: ?>
                <form action="register" method="post">
                    <fieldset class="form-group">
                        <label for="inpt-username" class="form-control-label">Username</label>
                        <input type="text" class="form-control" name="username" id="inpt-username" required>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="inpt-email" class="form-control-label">Email</label>
                        <input type="email" class="form-control" name="email" id="inpt-email" required>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="inpt-password" class="form-control-label">Password</label>
                        <input type="password" class="form-control" name="password" id="inpt-password" required>
                        <label for="inpt-password-conf" class="form-control-label">Password (again)</label>
                        <input type="password" class="form-control" name="password_conf" id="inpt-password-conf" required>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="impt-employee-code">Employee Code</label>
                        <input type="text" class="form-control" name="employee_code" id="impt-employee-code">
                        <small class="form-text text-muted">You should have received this code from HR with your employee manual</small>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>
<?php endblock() ?>


