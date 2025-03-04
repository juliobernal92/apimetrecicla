<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

header("Content-Type: application/json");
require_once("../config/conexion.php");
require_once("../models/Empleado.php");
require_once("../config/authjwt.php");
require_once("../config/encriptcookie.php");

$empleadoModel = new EmpleadoModel();
$body = json_decode(file_get_contents("php://input"), false);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token);
    
                // Verificamos si se está solicitando por ID
                if (isset($_GET['id'])) {
                    $id_empleado = $_GET['id'];
                    $resultado = $empleadoModel->get_empleado_by_id($id_empleado); // Pasamos solo el ID
                    echo get_resultado($resultado);
                } else {
                    $resultado = $empleadoModel->get_empleados();
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
        if (isset($body->operacion) && $body->operacion == 'LOGIN') {
            $resultado = $empleadoModel->login($body);
            if ($resultado) {
                error_log("Resultado del login: " . json_encode($resultado)); // Registro de depuración

                $jwt = encode($resultado);

                // Establecer la cookie con el token JWT
                setcookie('jwt', $jwt, time() + (8 * 60 * 60), "/", "", false, true); // 30 minutos de expiración, HttpOnly

                // Guardar información adicional en cookies si $resultado contiene los datos esperados
                if (isset($resultado['id_empleado']) && isset($resultado['nombre_apellido']) && isset($resultado['id_sucursal']) )  {
                    // Encriptar los valores antes de establecer las cookies
                    $id_empleado_encrypted = encryptCookie($resultado['id_empleado']);
                    $nombre_apellido_encrypted = encryptCookie($resultado['nombre_apellido']);
                    $nombres = $resultado['nombre_apellido'];
                    $id= $resultado['id_empleado'];
                    $id_sucursal=$resultado['id_sucursal'];
                    $nombre_sucursal=$resultado['nombre_sucursal'];
                    $direccion_sucursal=$resultado['direccion_sucursal'];
                    $telefono_sucursal=$resultado['telefono_sucursal'];

                    // Establecer las cookies encriptadas
                    setcookie('id_empleado', $id_empleado_encrypted, time() + (8 * 60 * 60), "/", "", true, true); // id_empleado
                    setcookie('nombre_apellido', $nombre_apellido_encrypted, time() + (8 * 60 * 60), "/", "", true, true); // nombre y apellido
                    setcookie('nombres',$nombres,time() + (8 * 60 * 60), "/", "", true, true);
                    setcookie('id_sucursal',$id_sucursal,time() + (8 * 60 * 60), "/", "", true, true);
                    setcookie('nombre_sucursal',$nombre_sucursal,time() + (8 * 60 * 60), "/", "", true, true);
                    setcookie('direccion_sucursal',$direccion_sucursal,time() + (8 * 60 * 60), "/", "", true, true);
                    setcookie('telefono_sucursal',$telefono_sucursal,time() + (8 * 60 * 60), "/", "", true, true);


                    // Agregar salida de depuración
                    error_log('Cookies configuradas: id_empleado=' . $resultado['id_empleado'] . ', nombre_apellido=' . $resultado['nombre_apellido'] . $resultado['id_sucursal']);
                } else {
                    // Si el resultado no contiene los datos esperados, puedes lanzar un error o manejarlo apropiadamente
                    http_response_code(500);
                    echo json_encode(["message" => "Login succeeded but user data is incomplete."]);
                    exit();
                }
                echo json_encode([
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "id" => $resultado['id_empleado'],
                    "nombres" => $resultado['nombre_apellido'],
                    "id_sucursal" => $resultado['id_sucursal'],
                    "nombre_sucursal" => $resultado['nombre_sucursal'],
                    "direccion_sucursal" => $resultado['direccion_sucursal'],
                    "telefono_sucursal" => $resultado['telefono_sucursal']
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Login failed."]);
            }
        } else {
            // Protegemos la ruta POST (excepto login)
            if (isset($_COOKIE['jwt'])) {
                $token = $_COOKIE['jwt'];
                try {
                    $userData = decode($token); // Decodificar y verificar el token
                    $resultado = $empleadoModel->insertar_empleado($body);
                    echo get_resultado($resultado);
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(["message" => "Acceso Denegado.", "error" => $e->getMessage()]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Acceso Denegado."]);
            }
        }
        break;

    case 'PUT':
        // Protegemos la ruta PUT
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token); // Decodificar y verificar el token
                $resultado = $empleadoModel->actualizar_empleado($body);
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
        // Protegemos la ruta DELETE
        if (isset($_COOKIE['jwt'])) {
            $token = $_COOKIE['jwt'];
            try {
                $userData = decode($token); // Decodificar y verificar el token
                $resultado = $empleadoModel->eliminar_empleado($body);
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

