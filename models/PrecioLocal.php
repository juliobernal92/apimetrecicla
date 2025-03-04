<?php

class PrecioLocalModel extends Conectar
{
    public function get_preciolocal()
    {
        $conectar = parent::Conexion();
        parent::set_name();


        $strQuery = "
            SELECT pv.id_preciolocal, pv.id_chatarra, c.nombre, pv.precioventa, pv.activo 
            FROM preciolocal pv 
            JOIN chatarra c ON pv.id_chatarra = c.id_chatarra 
            WHERE pv.activo = 1
        ";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_preciolocal_por_sucursal($sucursal, $localventa)
{
    $conectar = parent::Conexion();
    parent::set_name();

    // Usar parámetros nombrados en la consulta SQL
    $strQuery = "
    SELECT pv.id_preciolocal, pv.id_chatarra, c.nombre, pv.precioventa, pv.activo 
    FROM preciolocal pv 
    JOIN chatarra c ON pv.id_chatarra = c.id_chatarra 
    WHERE pv.activo = 1 AND pv.id_sucursal = :sucursal AND pv.id_localventa = :localventa;
    ";

    $resultado = $conectar->prepare($strQuery);
    
    // Usar bindValue con los parámetros nombrados
    $resultado->bindValue(':sucursal', $sucursal, PDO::PARAM_INT);
    $resultado->bindValue(':localventa', $localventa, PDO::PARAM_INT);
    
    $resultado->execute();

    return $resultado->fetchAll(PDO::FETCH_ASSOC);
}


    public function get_preciolocal_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        // Modificamos la consulta para incluir el nombre de la chatarra
        $strQuery = "
        SELECT pv.id_preciolocal, pv.id_chatarra, pv.id_localventa, pv.precioventa, pv.activo, c.nombre
        FROM preciolocal pv
        JOIN chatarra c ON pv.id_chatarra = c.id_chatarra
        WHERE pv.id_preciolocal = ? AND pv.activo = 1
    ";
        // Preparar y ejecutar la consulta
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();

        // Retornar el resultado de la consulta
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }


    public function get_chatarra_sin_precio_venta($idLocal, $idSucursal)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "
        SELECT c.id_chatarra, c.nombre 
        FROM chatarra c 
        WHERE c.id_sucursal = :idSucursal
        AND c.id_chatarra NOT IN (
            SELECT pv.id_chatarra 
            FROM preciolocal pv 
            WHERE pv.id_localventa = :idLocal
            AND pv.id_sucursal = :idSucursal
        )
        AND c.activo = 1;
        ";

        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(':idLocal', $idLocal, PDO::PARAM_INT);
        $resultado->bindValue(':idSucursal', $idSucursal, PDO::PARAM_INT);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertar_preciolocal($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO preciolocal (id_chatarra, id_localventa, id_sucursal, precioventa, activo) 
                     VALUES (?, ?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_chatarra, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_localventa, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->id_sucursal, PDO::PARAM_INT);  // Añadido el id_sucursal
        $resultado->bindValue(4, $data->precioventa, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->activo, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_preciolocal($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        // Actualizamos solo el campo 'precioventa' sin tocar los demás
        $strQuery = "UPDATE preciolocal SET precioventa=? WHERE id_preciolocal=?";
        $resultado = $conectar->prepare($strQuery);

        // Solo enviamos el nuevo precio y el id_preciolocal
        $resultado->bindValue(1, $data->precioventa, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_preciolocal, PDO::PARAM_INT);

        return $resultado->execute();
    }

    public function eliminar_preciolocal($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE preciolocal SET activo=0 WHERE id_preciolocal=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
