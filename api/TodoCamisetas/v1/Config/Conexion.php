<?php

require ('parametros.php');

class Conexion {

    protected $con;

    public function __construct()
    {
        $this->con = new mysqli(SERVER, USER, PASSWORD, DATABASE);
        $this->con->set_charset(CHAR);

        // Verificamos si hay errores en la conexión
        if ($this->con->connect_error) {
        die("Error de conexión: " . $this->con->connect_error ) ;
        }
    }

}

?>