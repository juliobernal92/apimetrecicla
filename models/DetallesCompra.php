<?php

class DetallesCompraModel extends Conectar
{
    public function get_detalles_compra()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_detalle_compra, id_compra, id_chatarra, cantidad, preciopagado, subtotal FROM detalles_compra";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_detalles_by_compra_id($id_compra)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        // Consulta para obtener los detalles
        $strQueryDetalles = "SELECT dc.id_detalle_compra, dc.id_compra, dc.id_chatarra, ch.nombre AS nombre_chatarra, 
                                dc.cantidad, dc.preciopagado, dc.subtotal
                         FROM detalles_compra dc
                         INNER JOIN chatarra ch ON dc.id_chatarra = ch.id_chatarra
                         WHERE dc.id_compra = ?";
        $resultadoDetalles = $conectar->prepare($strQueryDetalles);
        $resultadoDetalles->bindValue(1, $id_compra, PDO::PARAM_INT);
        $resultadoDetalles->execute();
        $detalles = $resultadoDetalles->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para calcular el total
        $strQueryTotal = "SELECT SUM(subtotal) as total
                      FROM detalles_compra
                      WHERE id_compra = ?";
        $resultadoTotal = $conectar->prepare($strQueryTotal);
        $resultadoTotal->bindValue(1, $id_compra, PDO::PARAM_INT);
        $resultadoTotal->execute();
        $total = $resultadoTotal->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'detalles' => $detalles,
            'total' => $total
        ];
    }




    public function insertar_detalle_compra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO detalles_compra (id_compra, id_chatarra, cantidad, preciopagado, subtotal) VALUES (?, ?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_compra, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_chatarra, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->cantidad);
        $resultado->bindValue(4, $data->preciopagado, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->subtotal, PDO::PARAM_INT);

        if ($resultado->execute()) {
            return [
                'success' => true,
                'id_detalle_compra' => $conectar->lastInsertId()
            ];
        } else {
            return [
                'success' => false,
                'error' => $resultado->errorInfo()
            ];
        }
    }


    public function actualizar_detalle_compra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE detalles_compra SET id_compra=?, id_chatarra=?, cantidad=?, preciopagado=?, subtotal=? WHERE id_detalle_compra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_compra, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->id_chatarra, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->cantidad);
        $resultado->bindValue(4, $data->preciopagado, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->subtotal, PDO::PARAM_INT);
        $resultado->bindValue(6, $data->id_detalle_compra, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_detalle_compra($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM detalles_compra WHERE id_detalle_compra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_cantidad($id_detalle_compra, $nueva_cantidad)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE detalles_compra 
                 SET cantidad = ?, subtotal = preciopagado * ? 
                 WHERE id_detalle_compra = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $nueva_cantidad, PDO::PARAM_INT);
        $resultado->bindValue(2, $nueva_cantidad, PDO::PARAM_INT);
        $resultado->bindValue(3, $id_detalle_compra, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
