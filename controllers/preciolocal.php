<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/PrecioLocal.php");
require_once("../config/authjwt.php");

$precioLocalModel = new PrecioLocalModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    // Obtener detalle por ID
                    $id_preciolocal = $_GET['id'];
                    $resultado = $precioLocalModel->get_preciolocal_by_id($id_preciolocal);
                    echo get_resultado($resultado);
                } elseif (isset($_GET['id_localventa']) && isset($_GET['id_sucursal'])) {
                    // Obtener chatarras sin precio de venta para un local y sucursal
                    $id_localventa = intval($_GET['id_localventa']);
                    $id_sucursal = intval($_GET['id_sucursal']); // Obtener el id_sucursal

                    // Llamar al modelo con ambos parámetros
                    $resultado = $precioLocalModel->get_chatarra_sin_precio_venta($id_localventa, $id_sucursal);

                    if (!empty($resultado)) {
                        echo get_resultado($resultado);
                    } else {
                        echo json_encode([
                            'codigo' => 404,
                            'descripcion' => 'No hay chatarras disponibles para este local y sucursal.',
                            'data' => []
                        ]);
                    }
                } elseif (isset($_GET['sucursal']) && isset($_GET['localventa'])) {
                    // Filtrar por id_sucursal
                    $sucursal = intval($_GET['sucursal']);
                    $localventa = intval($_GET['localventa']);

                    // Comprobar si la consulta se ejecuta correctamente
                    $resultado = $precioLocalModel->get_preciolocal_por_sucursal($sucursal, $localventa);

                    if (!empty($resultado)) {
                        echo get_resultado($resultado);
                    }
                } else {
                    // Obtener todos los registros de precios locales
                    $resultado = $precioLocalModel->get_preciolocal();
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
                $resultado = $precioLocalModel->insertar_preciolocal($body);
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
                $userData = decode($token); // Decodifica el token JWT.

                $resultado = $precioLocalModel->actualizar_preciolocal($body);
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
                    $id_preciolocal = $_GET['id'];
                    $resultado = $precioLocalModel->eliminar_preciolocal($id_preciolocal);
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
