<?php
require_once 'util.php';
require_once 'db.php';
require_once 'db/crud.php';
require_once 'db/model.php';

header('Content-Type: application/json');

if($_GET) {
    $call = filter_input(INPUT_GET, 'call', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if($_POST) {
        switch ($call) {
            case 'create-registration-code':
                createRegistrationCode(filter_input(INPUT_POST, 'perm-level', FILTER_SANITIZE_NUMBER_INT));
                break;
            case 'delete-registration-code':
                deleteRegistrationCode(filter_input(INPUT_POST, 'code', FILTER_SANITIZE_NUMBER_INT));
                break;
            default:
                defaultResponse($call);
        }
    }
}

/**
 * @param $permLevel int
 */
function createRegistrationCode($permLevel) {
    if(is_numeric($permLevel)) {
        if(strlen($permLevel) == 1) {
            $randCode = random_text('nozero', 16);

            $registrationCode = new RegistrationCode();
            $registrationCode->setCode($randCode);
            $registrationCode->setPermLevel((int) $permLevel);

            $resp = putRegistrationCode($registrationCode);

            if($resp != null) {
                $response = createResponse('Code created successfully!');
                $response['code'] = $registrationCode->getCode();
                $response['perm'] = $registrationCode->getPermLevel();
                http_response_code(200);
                echo json_encode($response);
            }
        } else {
            http_response_code(400);
            echo json_encode(createResponse('The permission level must be a number between 0 and 9'));
        }
    } else {
        http_response_code(400);
        echo json_encode(createResponse('The permission level must be a valid integer'));
    }
}

function deleteRegistrationCode($code) {
    removeRegistrationCode($code);

    http_response_code(200);
    echo json_encode(createResponse('Code deleted'));
}

function defaultResponse($call) {
    http_response_code(400);
    echo json_encode(createResponse($call . ' is not a valid api call :('));
}

/**
 * @param $message string
 * @return array
 */
function createResponse($message) {
    return ['message' => $message];
}

?>