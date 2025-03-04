<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");
require_once("../config/conexion.php");
require_once("../models/Adelanto.php");
require_once("../config/authjwt.php");

$adelantoModel = new AdelantoModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    $id_adelanto = $_GET['id'];
                    $resultado = $adelantoModel->get_adelanto_by_id($id_adelanto);
                    echo get_resultado($resultado);
                } else {
                    $resultado = $adelantoModel->get_adelantos();
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
                $resultado = $adelantoModel->insertar_adelanto($body);
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
                /*
                $resultado = $adelantoModel->actualizar_adelanto($body);
                
                if ($resultado) {
                    echo get_resultado($resultado);
                } else {
                    echo json_encode(array("success" => false, "message" => "Error al actualizar el adelanto"));
                }*/
                /*
                if (isset($body->id_adelanto) && isset($body->estado)) {
                    // Llamada a la función autorizar_adelanto
                    $resultado = $adelantoModel->autorizar_adelanto($body);
                    echo get_resultado($resultado);
                } else {
                    echo json_encode(["success" => false, "message" => "Datos insuficientes"]);
                }*/
                // Diferenciar entre actualización completa y autorización
                if (isset($body->id_adelanto)) {
                    if (isset($body->estado) && !isset($body->id_empleado) && !isset($body->fecha) && !isset($body->monto)) {
                        // Si solo se envía el estado, usa `autorizar_adelanto`
                        $resultado = $adelantoModel->autorizar_adelanto($body);
                    } else {
                        // Si se envían varios campos, usa `actualizar_adelanto`
                        $resultado = $adelantoModel->actualizar_adelanto($body);
                    }
                    echo get_resultado($resultado);
                } else {
                    echo json_encode(["success" => false, "message" => "Datos insuficientes"]);
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

    case 'DELETE':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                $resultado = $adelantoModel->eliminar_adelanto($body);
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
