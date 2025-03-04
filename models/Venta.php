<?php

class VentaModel extends Conectar
{
    public function get_ventas()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_venta, id_sucursal, id_empleado, fecha, total FROM ventas";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_venta_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_venta, id_sucursal, id_empleado, fecha, total FROM ventas WHERE id_venta = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_venta($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO ventas (id_sucursal, id_empleado, fecha, total) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_sucursal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha);
        $resultado->bindValue(4, $data->total, PDO::PARAM_INT);
        if ($resultado->execute()) {
            return [
                'success' => true,
                'id_venta' => $conectar->lastInsertId() // Devuelve el ID generado
            ];
        } else {
            return [
                'success' => false,
                'error' => $resultado->errorInfo()
            ];
        }
    }

    public function actualizar_venta($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE ventas SET id_sucursal=?, id_empleado=?, fecha=?, total=? WHERE id_venta=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_sucursal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha);
        $resultado->bindValue(4, $data->total, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->id_venta, PDO::PARAM_INT);
        return $resultado->execute();


        
    }

    public function actualizar_venta_total($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE ventas SET  total=? WHERE id_venta=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->total, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_venta, PDO::PARAM_INT);
        return $resultado->execute();
    }


    public function eliminar_venta($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM ventas WHERE id_venta=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}