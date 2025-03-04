<?php

class GastosModel extends Conectar
{
    public function get_gastos()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_gasto, descripcion, monto, fecha, observaciones, activo FROM gastos where activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_gasto_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_gasto, descripcion, monto, fecha, observaciones, activo FROM gastos WHERE id_gasto = ? and activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_gasto($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO gastos (descripcion, monto, fecha, observaciones, activo) VALUES (?, ?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->descripcion);
        $resultado->bindValue(2, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha);
        $resultado->bindValue(4, $data->observaciones);
        $resultado->bindValue(5, $data->activo, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_gasto($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE gastos SET descripcion=?, monto=?, fecha=?, observaciones=?, activo=? WHERE id_gasto=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->descripcion);
        $resultado->bindValue(2, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha);
        $resultado->bindValue(4, $data->observaciones);
        $resultado->bindValue(5, $data->activo, PDO::PARAM_INT);
        $resultado->bindValue(6, $data->id_gasto, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_gasto($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE gastos SET activo=0 WHERE id_gasto=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
