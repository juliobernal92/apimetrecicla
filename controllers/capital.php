<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/Capital.php");
require_once("../config/authjwt.php");

$capitalModel = new CapitalModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    $id_capital = $_GET['id'];
                    $resultado = $capitalModel->get_capital_by_id($id_capital);
                    echo get_resultado($resultado);
                } else {
                    $resultado = $capitalModel->get_capitales();
                    echo get_resultado($resultado);
                }
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(["message" => "Acceso Denegado.", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Acceso Denegado."]);
        }
        break;

    case 'POST':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                $resultado = $capitalModel->insertar_capital($body);
                echo get_resultado($resultado);
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(["message" => "Acceso Denegado.", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Acceso Denegado."]);
        }
        break;

    case 'PUT':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                $resultado = $capitalModel->actualizar_capital($body);
                echo get_resultado($resultado);
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(["message" => "Acceso Denegado.", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Acceso Denegado."]);
        }
        break;

    case 'DELETE':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                if (isset($_GET['id'])) {
                    $id_capital = $_GET['id'];
                    $resultado = $capitalModel->eliminar_capital($id_capital);
                    echo get_resultado($resultado);
                } else {
                    echo json_encode(["success" => false, "message" => "ID requerido"]);
                }
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(["message" => "Acceso Denegado.", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Acceso Denegado."]);
        }
        break;

    default:
        http_response_code(400);
        break;
}

function get_resultado($data)
{
    $result = array(
        'codigo' => 200,
        'descripcion' => 'Ok',
        'data' => array('resultado' => $data)
    );
    return json_encode($result);
}
