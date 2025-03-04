<?PHP
class EmpleadoModel extends Conectar
{
    public function get_empleados()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_empleado, nombre_apellido, telefono, direccion, cedula, fecha_contratacion, id_sucursal, activo FROM empleados where activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_empleado_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_empleado, nombre_apellido, telefono, direccion, cedula, fecha_contratacion, id_sucursal, activo FROM empleados where id_empleado=? and activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertar_empleado($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO empleados ( nombre_apellido, telefono, direccion, cedula, pass, fecha_contratacion, id_sucursal,id_rol, activo) VALUES(?, ?, ?, ?,?,?,?,?,?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre_apellido);
        $resultado->bindValue(2, $data->telefono);
        $resultado->bindValue(3, $data->direccion);
        $resultado->bindValue(4, $data->cedula);
        $resultado->bindValue(5, password_hash($data->pass, PASSWORD_DEFAULT));
        $resultado->bindValue(6, $data->fecha_contratacion);
        $resultado->bindValue(7, $data->id_sucursal);
        $resultado->bindValue(8, $data->id_rol);
        $resultado->bindValue(9, 1);
        return $resultado->execute();
    }

    public function actualizar_empleado($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE empleados SET nombre_apellido=?, telefono=?,  direccion=?, activo=? WHERE id_empleado=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre_apellido);
        $resultado->bindValue(2, $data->telefono);
        $resultado->bindValue(3, $data->direccion);
        $resultado->bindValue(4, $data->activo);
        $resultado->bindValue(5, $data->id_empleado);
        return $resultado->execute();
    }

    public function eliminar_empleado($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE empleados SET activo=0 WHERE id_empleado=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_empleado);
        return $resultado->execute();
    }

    public function login($data)
{
    $conectar = parent::Conexion();
    parent::set_name();

    // Realizamos un JOIN con la tabla sucursales
    $strQuery = "
        SELECT e.id_empleado, e.nombre_apellido, e.pass, e.id_sucursal, 
               s.nombre AS nombre_sucursal, s.direccion AS direccion_sucursal, s.telefono AS telefono_sucursal
        FROM empleados e
        INNER JOIN sucursales s ON e.id_sucursal = s.id_sucursal
        WHERE e.cedula = ? AND e.activo = 1";
    
    $resultado = $conectar->prepare($strQuery);
    $resultado->bindValue(1, $data->cedula);
    $resultado->execute();

    $empleado = $resultado->fetch(PDO::FETCH_ASSOC);

    if ($empleado && !empty($empleado['pass']) && password_verify($data->pass, $empleado['pass'])) {
        unset($empleado['pass']); // Eliminamos el hash de la contraseña
        return $empleado;
    } else {
        return false;
    }
}

}
