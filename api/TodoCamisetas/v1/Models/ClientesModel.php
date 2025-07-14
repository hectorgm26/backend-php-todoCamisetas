<?php

require_once __DIR__ . '/../Config/Conexion.php';

class ClientesModel extends Conexion {

    public function __construct()
    {
        parent::__construct();
    }

    public function getClientes() {
        try {
            $parametrizacion = $this->con->prepare("SELECT * FROM clientes WHERE estado_disponible = (?)");
            $parametrizacion->bind_param('i', $a);
            $a = 1;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            $r = array();
            while ($fila = $resultado->fetch_assoc()) {
                $cliente = array(
                    'id' => $fila['id'],
                    'nombre_comercial' => $fila['nombre_comercial'],
                    'rut' => $fila['rut'],
                    'direccion' => $fila['direccion'],
                    'categoria' => $fila['categoria'],
                    'contacto_nombre' => $fila['contacto_nombre'],
                    'contacto_email' => $fila['contacto_email'],
                    'porcentaje_oferta' => $fila['porcentaje_oferta'],
                    'estado_disponible' => $fila['estado_disponible']
                );
                $r[] = $cliente;
            }
            return $r;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }

    public function getClienteById($id) {
        try {
            $parametrizacion = $this->con->prepare("SELECT * FROM clientes WHERE id = (?) AND estado_disponible = (?)");
            $parametrizacion->bind_param('ii', $a, $b);
            $a = $id;
            $b = 1;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            $r = array();
            while ($fila = $resultado->fetch_assoc()) {
                $cliente = array(
                    'id' => $fila['id'],
                    'nombre_comercial' => $fila['nombre_comercial'],
                    'rut' => $fila['rut'],
                    'direccion' => $fila['direccion'],
                    'categoria' => $fila['categoria'],
                    'contacto_nombre' => $fila['contacto_nombre'],
                    'contacto_email' => $fila['contacto_email'],
                    'porcentaje_oferta' => $fila['porcentaje_oferta'],
                    'estado_disponible' => $fila['estado_disponible']
                );
                $r[] = $cliente;
            }
            return $r;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }

    public function postCliente($nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta) {
        try {
            $parametrizacion = $this->con->prepare("INSERT INTO clientes (nombre_comercial, rut, direccion, categoria, contacto_nombre, contacto_email, porcentaje_oferta) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $parametrizacion->bind_param('ssssssi', $a, $b, $c, $d, $e, $f, $g);
            $a = $nombre_comercial;
            $b = $rut;
            $c = $direccion;
            $d = $categoria;
            $e = $contacto_nombre;
            $f = $contacto_email;
            $g = $porcentaje_oferta;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();
            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }

    public function updateCliente($id, $nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta) {
        try {
            $parametrizacion = $this->con->prepare("UPDATE clientes SET nombre_comercial = (?), rut = (?), direccion = (?), categoria = (?), contacto_nombre = (?), contacto_email = (?), porcentaje_oferta = (?) WHERE id = (?)");

            $parametrizacion->bind_param('ssssssii', $a, $b, $c, $d, $e, $f, $g, $h);
            $a = $nombre_comercial;
            $b = $rut;
            $c = $direccion;
            $d = $categoria;
            $e = $contacto_nombre;
            $f = $contacto_email;
            $g = $porcentaje_oferta;
            $h = $id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();
            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }

    public function deleteCliente($id) {
        try {
            $parametrizacion = $this->con->prepare("UPDATE clientes SET estado_disponible = (?) WHERE id = (?)");
            $parametrizacion->bind_param('ii', $a, $b);
            $a = 0; // Cambia el estado a no disponible
            $b = $id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();
            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }

    // PATCH - EL PATCH SOLO ACTUALIZARA LA DIRECCION DEL CLIENTE
    public function patchCliente($direccion, $id) {
        try {
            $parametrizacion = $this->con->prepare("UPDATE clientes SET direccion = (?) WHERE id = (?)");
            $parametrizacion->bind_param('si', $a, $b);
            $a = $direccion;
            $b = $id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();
            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        } finally {
            $this->con->close();
        }
    }
}
?>