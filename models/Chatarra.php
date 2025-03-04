<?php

class ChatarraModel extends Conectar
{
    public function get_chatarras()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_chatarra, nombre, precio, activo FROM chatarra WHERE activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_chatarra_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_chatarra, nombre, precio, activo FROM chatarra WHERE id_chatarra = ? AND activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function get_chatarras_by_sucursal($id_sucursal)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_chatarra, nombre, precio, activo FROM chatarra WHERE id_sucursal = ? AND activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id_sucursal, PDO::PARAM_INT);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar_chatarra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO chatarra (nombre, precio, id_sucursal, activo) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->precio);
        $resultado->bindValue(3,$data->id_sucursal);
        $resultado->bindValue(4, 1, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function actualizar_chatarra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE chatarra SET nombre=?, precio=? WHERE id_chatarra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->precio);
        $resultado->bindValue(3, $data->id_chatarra, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_chatarra($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE chatarra SET activo=0 WHERE id_chatarra=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_chatarra);
        return $resultado->execute();
    }
}
