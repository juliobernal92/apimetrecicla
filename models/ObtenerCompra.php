<?php

class ObtenerComprasModel extends Conectar
{
    public function getComprasDiarias($idSucursal)
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
                    AND c.id_sucursal = ?
                GROUP BY 
                    ch.nombre
                    ORDER BY 
                    SUM(dc.subtotal) DESC
                    ";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $idSucursal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComprasSemanales($idSucursal)
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
                    AND c.id_sucursal = ?
                GROUP BY 
                    ch.nombre
                    ORDER BY 
                    SUM(dc.subtotal) DESC
                    ";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $idSucursal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComprasMensuales($idSucursal)
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
                    MONTH(c.fecha) = MONTH(CURDATE())
                    AND YEAR(c.fecha) = YEAR(CURDATE())
                    AND c.id_sucursal = ?
                GROUP BY 
                    ch.nombre
                    ORDER BY 
                    SUM(dc.subtotal) DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $idSucursal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
