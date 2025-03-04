<?php

class InventarioModel extends Conectar
{
    public function get_inventarios()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_inventario, id_chatarra, cantidad FROM inventario";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_inventario_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_inventario, id_chatarra, cantidad FROM inventario WHERE id_inventario = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_inventario($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO inventario (id_chatarra, cantidad) VALUES (?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_chatarra, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->cantidad);
        return $resultado->execute();
    }

    public function actualizar_inventario($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE inventario SET id_chatarra=?, cantidad=? WHERE id_inventario=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->id_chatarra, PDO::PARAM_INT);
        $resultado->bindValue(2, $data->cantidad);
        $resultado->bindValue(3, $data->id_inventario, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_inventario($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM inventario WHERE id_inventario=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}