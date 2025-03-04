<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/Venta.php");
require_once("../config/authjwt.php");

$ventaModel = new VentaModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    $id_venta = $_GET['id'];
                    $resultado = $ventaModel->get_venta_by_id($id_venta);
                    echo get_resultado($resultado);
                } else {
                    $resultado = $ventaModel->get_ventas();
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
                $resultado = $ventaModel->insertar_venta($body);
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
                $resultado = $ventaModel->actualizar_venta($body);
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
                    $id_venta = $_GET['id'];
                    $resultado = $ventaModel->eliminar_venta($id_venta);
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
