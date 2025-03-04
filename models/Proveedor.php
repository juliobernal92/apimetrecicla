<?php

class ProveedorModel extends Conectar
{
    public function get_proveedores()
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_proveedor, nombre, direccion, telefono, activo FROM proveedores where activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_proveedores_by_sucursal($id_sucursal)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_proveedor, nombre, direccion, telefono, activo FROM proveedores WHERE id_sucursal = ? AND activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id_sucursal, PDO::PARAM_INT);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_proveedor_by_id($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "SELECT id_proveedor, nombre, direccion, telefono, activo FROM proveedores WHERE id_proveedor = ? and activo=1";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar_proveedor($data)
{
    $conectar = parent::Conexion();
    parent::set_name();

    $strQuery = "INSERT INTO proveedores (nombre, direccion, telefono, activo) VALUES (?, ?, ?, ?)";
    $resultado = $conectar->prepare($strQuery);
    $resultado->bindValue(1, $data->nombre);
    $resultado->bindValue(2, $data->direccion);
    $resultado->bindValue(3, $data->telefono);
    $resultado->bindValue(4, $data->activo);

    if ($resultado->execute()) {
        return [
            'success' => true,
            'id_proveedor' => $conectar->lastInsertId()
        ];
    } else {
        return [
            'success' => false,
            'error' => $resultado->errorInfo()
        ];
    }
}

    public function actualizar_proveedor($data)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE proveedores SET nombre=?, direccion=?, telefono=?, activo=? WHERE id_proveedor=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $data->nombre);
        $resultado->bindValue(2, $data->direccion);
        $resultado->bindValue(3, $data->telefono, PDO::PARAM_INT);
        $resultado->bindValue(4, $data->activo, PDO::PARAM_INT);
        $resultado->bindValue(5, $data->id_proveedor, PDO::PARAM_INT);
        return $resultado->execute();
    }

    public function eliminar_proveedor($id)
    {
        $conectar = parent::Conexion();
        parent::set_name();

        $strQuery = "UPDATE proveedores SET activo=0 WHERE id_proveedor=?";
        $resultado = $conectar->prepare($strQuery);
        $resultado->bindValue(1, $id, PDO::PARAM_INT);
        return $resultado->execute();
    }
}