<?php

class CapitalModel extends Conectar
{
    public function get_capitales()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_capital, fecha, tipo_transaccion, monto, descripcion FROM capital";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_capital_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_capital, fecha, tipo_transaccion, monto, descripcion FROM capital WHERE id_capital = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_capital($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO capital (fecha, tipo_transaccion, monto, descripcion) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->fecha);
        $resultado->bindValue(2, $data->tipo_transaccion);
        $resultado->bindValue(3, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->descripcion);
        return $resultado->execute();
    }

    public function actualizar_capital($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE capital SET fecha=?, tipo_transaccion=?, monto=?, descripcion=? WHERE id_capital=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->fecha);
        $resultado->bindValue(2, $data->tipo_transaccion);
        $resultado->bindValue(3, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->descripcion);
        $resultado->bindValue(5, $data->id_capital, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_capital($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM capital WHERE id_capital=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
?>
