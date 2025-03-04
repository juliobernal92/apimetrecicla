<?php

class DetallesVentaModel extends Conectar
{
    public function get_detalles_venta()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_detalle_venta, id_preciolocal, id_venta, cantidad, subtotal FROM detalles_venta";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_detalle_venta_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        // Consulta SQL actualizada
        $strQuery = "SELECT dv.id_detalle_venta, c.nombre AS material, dv.cantidad, dv.subtotal, pl.precioventa 
        FROM detalles_venta dv 
        JOIN ventas v ON dv.id_venta = v.id_venta 
        JOIN preciolocal pl ON dv.id_preciolocal = pl.id_preciolocal 
        JOIN chatarra c ON pl.id_chatarra = c.id_chatarra 
        WHERE dv.id_venta = ?";

        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        $detalles = $resultado->fetchAll(PDO::FETCH_ASSOC);


        // Consulta para calcular el total
        $strQueryTotal = "SELECT SUM(subtotal) as total
                      FROM detalles_venta
                      WHERE id_venta = ?";
        $resultadoTotal = $conectar->prepare($strQueryTotal);
        $resultadoTotal->bindValue(1, $id, PDO::PARAM_INT);
        $resultadoTotal->execute();
        $total = $resultadoTotal->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'detalles' => $detalles,
            'total' => $total
        ];
    }

    public function insertar_detalle_venta($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO detalles_venta (id_preciolocal, id_venta, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_preciolocal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_venta, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->cantidad);
        $resultado->bindValue(4, $data->subtotal, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_detalle_venta($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE detalles_venta SET id_preciolocal=?, id_venta=?, cantidad=?, subtotal=? WHERE id_detalle_venta=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_preciolocal, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_venta, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->cantidad);
        $resultado->bindValue(4, $data->subtotal, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->id_detalle_venta, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_detalle_venta($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM detalles_venta WHERE id_detalle_venta=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }


    public function actualizar_cantidad($id_detalle_compra, $nueva_cantidad)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        // Obtener el precio de venta desde la tabla preciolocal
        $strQueryPrecio = "SELECT pl.precioventa 
                       FROM detalles_venta dv
                       JOIN preciolocal pl ON dv.id_preciolocal = pl.id_preciolocal
                       WHERE dv.id_detalle_venta = ?";

        $stmtPrecio = $conectar->prepare($strQueryPrecio);
        $stmtPrecio->bindValue(1, $id_detalle_compra, PDO::PARAM_INT);
        $stmtPrecio->execute();
        $precio = $stmtPrecio->fetchColumn(); // Obtener solo el precio

        if ($precio === false) {
            return false; // Si no se encuentra el precio, retornar error
        }

        // Calcular el nuevo subtotal
        $nuevo_subtotal = $nueva_cantidad * $precio;

        // Actualizar la cantidad y el subtotal
        $strQuery = "UPDATE detalles_venta
                 SET cantidad = ?, subtotal = ? 
                 WHERE id_detalle_venta = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $nueva_cantidad, PDO::PARAM_INT);
        $resultado->bindValue(2, $nuevo_subtotal, PDO::PARAM_STR); // Puede ser decimal
        $resultado->bindValue(3, $id_detalle_compra, PDO::PARAM_INT);

        return $resultado->execute();
    }
}
