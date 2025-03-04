<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/DetallesVenta.php");
require_once("../config/authjwt.php");

$detallesVentaModel = new DetallesVentaModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    $id_detalle_venta = $_GET['id'];
                    $resultado = $detallesVentaModel->get_detalle_venta_by_id($id_detalle_venta);
                    echo get_resultado($resultado);
                } else {
                    $resultado = $detallesVentaModel->get_detalles_venta();
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
                $resultado = $detallesVentaModel->insertar_detalle_venta($body);
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
        
                    if (!empty($body->id_detalle_venta) && !empty($body->cantidad)) {
                        $resultado = $detallesVentaModel->actualizar_cantidad(
                            $body->id_detalle_venta, 
                            $body->cantidad
                        );
        
                        if ($resultado) {
                            echo json_encode([
                                'codigo' => 200,
                                'descripcion' => 'Cantidad actualizada con éxito.'
                            ]);
                        } else {
                            echo json_encode([
                                'codigo' => 500,
                                'descripcion' => 'Error al actualizar la cantidad.'
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'codigo' => 400,
                            'descripcion' => 'Datos insuficientes para actualizar.'
                        ]);
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
        
        
    
        /*
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                $resultado = $detallesVentaModel->actualizar_detalle_venta($body);
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
        */

    case 'DELETE':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                if (isset($_GET['id'])) {
                    $id_detalle_venta = $_GET['id'];
                    $resultado = $detallesVentaModel->eliminar_detalle_venta($id_detalle_venta);
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