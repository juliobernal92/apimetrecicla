<?php

class CajaModel extends Conectar
{
    public function get_cajas()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_caja, fecha, monto_inicial, monto_final, observaciones FROM caja";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_caja_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_caja, fecha, monto_inicial, monto_final, observaciones FROM caja WHERE id_caja = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_caja($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO caja (fecha, monto_inicial, monto_final, observaciones) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->fecha);
        $resultado->bindValue(2, $data->monto_inicial, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->monto_final, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->observaciones);
        return $resultado->execute();
    }

    public function actualizar_caja($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE caja SET fecha=?, monto_inicial=?, monto_final=?, observaciones=? WHERE id_caja=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->fecha);
        $resultado->bindValue(2, $data->monto_inicial, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->monto_final, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->observaciones);
        $resultado->bindValue(5, $data->id_caja, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_caja($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM caja WHERE id_caja=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
