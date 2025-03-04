<?php

class AdelantoModel extends Conectar
{
    public function get_adelantos()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_adelanto, id_empleado, fecha, monto, estado FROM adelantos WHERE activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_adelanto_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_adelanto, id_empleado, fecha, monto, estado FROM adelantos WHERE id_adelanto = ? AND activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC); 
    }

    public function insertar_adelanto($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO adelantos (id_empleado, fecha, monto, activo) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->fecha);
        $resultado->bindValue(3, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(4,1);
        return $resultado->execute();
    }

    public function actualizar_adelanto($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE adelantos SET id_empleado=?, fecha=?, monto=?, estado=? WHERE id_adelanto=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_empleado, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->fecha);
        $resultado->bindValue(3, $data->monto, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->estado);
        $resultado->bindValue(5, $data->id_adelanto, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function autorizar_adelanto($data){
        $conectar = parent::Conexion();
        parent::set_name();
        $strQuery = "UPDATE adelantos SET estado=? WHERE id_adelanto=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->estado);
        $resultado->bindValue(2, $data->id_adelanto);
        return $resultado->execute();
    }

    public function eliminar_adelanto($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE adelantos SET activo=0 WHERE id_adelanto=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_adelanto, PDO::PARAM_INT);
        return $resultado->execute();
    }
}
