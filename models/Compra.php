<?php

class ComprasModel extends Conectar
{
    public function get_compras()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_compra, id_sucursal, fecha, id_proveedor, id_empleado, total FROM compras";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_compra_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_compra, id_sucursal, fecha, id_proveedor, id_empleado, total FROM compras WHERE id_compra = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_compra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO compras (id_sucursal, fecha, id_proveedor, id_empleado, total) VALUES (?, ?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_sucursal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->fecha);
        $resultado->bindValue(3, $data->id_proveedor, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->total, PDO::PARAM_INT);

        if ($resultado->execute()) {
            return [
                'success' => true,
                'id_compra' => $conectar->lastInsertId() // Devuelve el ID generado
            ];
        } else {
            return [
                'success' => false,
                'error' => $resultado->errorInfo()
            ];
        }
    }


    public function actualizar_compra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE compras SET id_sucursal=?, fecha=?, id_proveedor=?, id_empleado=?, total=? WHERE id_compra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_sucursal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->fecha);
        $resultado->bindValue(3, $data->id_proveedor, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->total, PDO::PARAM_INT);
        $resultado->bindValue(6, $data->id_compra, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_compra_total($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE compras SET  total=? WHERE id_compra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->total, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_compra, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_compra($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM compras WHERE id_compra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }


    public function getComprasDiarias()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $sql = "SELECT 
                ch.nombre AS Material, 
                SUM(dc.cantidad) AS Cantidad, 
                SUM(dc.subtotal) AS Monto
            FROM 
                detalles_compra dc
            JOIN 
                chatarra ch ON dc.id_chatarra = ch.id_chatarra
            JOIN 
                compras c ON dc.id_compra = c.id_compra
            WHERE 
                DATE(c.fecha) = CURDATE()
            GROUP BY 
                ch.nombre";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // Compras Semanales
    public function getComprasSemanales()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $sql = "SELECT 
                    ch.nombre AS Material, 
                    SUM(dc.cantidad) AS Cantidad, 
                    SUM(dc.subtotal) AS Monto
                FROM 
                    detalles_compra dc
                JOIN 
                    chatarra ch ON dc.id_chatarra = ch.id_chatarra
                JOIN 
                    compras c ON dc.id_compra = c.id_compra
                WHERE 
                    YEARWEEK(c.fecha, 1) = YEARWEEK(CURDATE(), 1)
                GROUP BY 
                    ch.nombre";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compras Mensuales
    public function getComprasMensuales()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $sql = "SELECT 
                    ch.nombre AS Material, 
                    SUM(dc.cantidad) AS Cantidad, 
                    SUM(dc.subtotal) AS Monto
                FROM 
                    detalles_compra dc
                JOIN 
                    chatarra ch ON dc.id_chatarra = ch.id_chatarra
                JOIN 
                    compras c ON dc.id_compra = c.id_compra
                WHERE 
                    MONTH(c.fecha) = MONTH(CURDATE()) AND YEAR(c.fecha) = YEAR(CURDATE())
                GROUP BY 
                    ch.nombre";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
