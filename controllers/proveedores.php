<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/Proveedor.php");
require_once("../config/authjwt.php");

$proveedorModel = new ProveedorModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);

                if (isset($_GET['id'])) {
                    $id_proveedor = $_GET['id'];
                    $resultado = $proveedorModel->get_proveedor_by_id($id_proveedor);
                    echo get_resultado($resultado);
                }elseif (isset($_GET['id_sucursal'])) {
                    // Obtener proveedor por sucursal
                    $id_sucursal = intval($_GET['id_sucursal']);
                    $resultado = $proveedorModel->get_proveedores_by_sucursal($id_sucursal);
                    echo get_resultado($resultado);
                }
                else {
                    $resultado = $proveedorModel->get_proveedores();
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
                    $resultado = $proveedorModel->insertar_proveedor($body);
        
                    if ($resultado['success']) {
                        echo get_resultado(['id_proveedor' => $resultado['id_proveedor']]);
                    } else {
                        http_response_code(500);
                        echo json_encode([
                            'codigo' => 500,
                            'descripcion' => 'Error al insertar proveedor.',
                            'error' => $resultado['error']
                        ]);
                    }
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode([
                        'codigo' => 401,
                        'descripcion' => 'Acceso Denegado.',
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["codigo" => 401, "descripcion" => "Acceso Denegado."]);
            }
            break;
        

    case 'PUT':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
                $resultado = $proveedorModel->actualizar_proveedor($body);
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
                    $id_proveedor = $_GET['id'];
                    $resultado = $proveedorModel->eliminar_proveedor($id_proveedor);
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