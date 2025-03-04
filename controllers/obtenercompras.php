<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/ObtenerCompra.php");
require_once("../config/authjwt.php");

$comprasModel = new ObtenerComprasModel();

if (!isset($_COOKIE['jwt'])) {
    http_response_code(401);
    echo json_encode(["codigo" => 401, "descripcion" => "Acceso Denegado."]);
    exit;
}

try {
    $token = $_COOKIE['jwt'];
    $userData = decode($token);

    if (!isset($_GET['id_sucursal'])) {
        http_response_code(400);
        echo json_encode(["codigo" => 400, "descripcion" => "ID de sucursal requerido."]);
        exit;
    }

    $idSucursal = $_GET['id_sucursal'];
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'diarias':
            $resultado = $comprasModel->getComprasDiarias($idSucursal);
            echo get_resultado($resultado);
            break;

        case 'semanales':
            $resultado = $comprasModel->getComprasSemanales($idSucursal);
            echo get_resultado($resultado);
            break;

        case 'mensuales':
            $resultado = $comprasModel->getComprasMensuales($idSucursal);
            echo get_resultado($resultado);
            break;

        default:
            http_response_code(400);
            echo json_encode(["codigo" => 400, "descripcion" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["codigo" => 401, "descripcion" => "Acceso Denegado.", "error" => $e->getMessage()]);
}

function get_resultado($data)
{
    return json_encode([
        'codigo' => 200,
        'descripcion' => 'Ok',
        'data' => ['resultado' => $data]
    ]);
}
