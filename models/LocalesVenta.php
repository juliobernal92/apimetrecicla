<?php

class LocalesVentaModel extends Conectar
{
    public function get_localesventa()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_localventa, nombre, telefono, direccion, activo FROM localesventa where activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_localesventa_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_localventa, nombre, telefono, direccion, activo FROM localesventa WHERE id_localventa = ? and activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_localesventa($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO localesventa (nombre, telefono, direccion, activo) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->telefono, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->direccion);
        $resultado->bindValue(4, $data->activo, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_localesventa($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE localesventa SET nombre=?, telefono=?, direccion=?, activo=? WHERE id_localventa=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->telefono, PDO::PARAM_INT);
        $resultado->bindValue(3, $data->direccion);
        $resultado->bindValue(4, $data->activo, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->id_localventa, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_localesventa($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE localesventa set activo=0 WHERE id_localventa=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}