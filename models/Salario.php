<?php

class SalarioModel extends Conectar
{
    public function get_salarios()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_salario, id_empleado, monto, fecha_pago, estado, frecuencia_pago FROM salarios";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_salario_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_salario, id_empleado, monto, fecha_pago, estado, frecuencia_pago FROM salarios WHERE id_salario = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_salario($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO salarios (id_empleado, monto, fecha_pago, estado, frecuencia_pago) VALUES (?, ?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha_pago);
        $resultado->bindValue(4, $data->estado);
        $resultado->bindValue(5, $data->frecuencia_pago);
        return $resultado->execute();
    }

    public function actualizar_salario($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE salarios SET id_empleado=?, monto=?, fecha_pago=?, estado=?, frecuencia_pago=? WHERE id_salario=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->fecha_pago);
        $resultado->bindValue(4, $data->estado);
        $resultado->bindValue(5, $data->frecuencia_pago);
        $resultado->bindValue(6, $data->id_salario, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_salario($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM salarios WHERE id_salario=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
