<?php
class Conectar
{
    protected $dbh;

    protected function Conexion()
    {
        try {
            $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=metrecicla","root","12345678");
            return $conectar;
        } catch (Exception $e) {
            print "Error en la conexion a  la base de datos:".$e->getMessage();
            die();
        }
    }

    public function set_name(){
        return $this->dbh->query("SET NAMES 'utf8'");
    }
}
