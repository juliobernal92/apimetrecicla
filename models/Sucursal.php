<?php

class SucursalModel extends Conectar
{
    public function get_sucursales()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_sucursal, nombre, direccion, telefono, fecha_apertura FROM sucursales";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_sucursal_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_sucursal, nombre, direccion, telefono, fecha_apertura FROM sucursales WHERE id_sucursal = ?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_sucursal($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "INSERT INTO sucursales (nombre, direccion, telefono, fecha_apertura) VALUES (?, ?, ?, ?)";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->direccion);
        $resultado->bindValue(3, $data->telefono, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->fecha_apertura);
        return $resultado->execute();
    }

    public function actualizar_sucursal($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE sucursales SET nombre=?, direccion=?, telefono=?, fecha_apertura=? WHERE id_sucursal=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->direccion);
        $resultado->bindValue(3, $data->telefono, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->fecha_apertura);
        $resultado->bindValue(5, $data->id_sucursal, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_sucursal($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "DELETE FROM sucursales WHERE id_sucursal=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}