<?php

require_once __DIR__ . '/../Models/ClientesModel.php';

class clientesController {

    private $model;

    public function __construct()
    {
        $this->model = new ClientesModel();
    }

    // Metodo Get para obtener todos los clientes
    public function getallClientes() {
        return $this->model->getClientes();
    }

    // Metodo Get para obtener un cliente por ID
    public function getClienteById($id) {
        return $this->model->getClienteById($id);
    }

    // Metodo Post para crear un nuevo cliente
    public function postCliente($nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta) {
        return $this->model->postCliente($nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta);
    }

    // Metodo Put para actualizar un cliente existente
    public function putCliente($id, $nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta) {
        return $this->model->updateCliente($id, $nombre_comercial, $rut, $direccion, $categoria, $contacto_nombre, $contacto_email, $porcentaje_oferta);
    }

    // Metodo Delete para eliminar un cliente por ID
    public function deleteCliente($id) {
        return $this->model->deleteCliente($id);
    }

    // Metodo Patch para actualizar la dirección de un cliente
    public function patchCliente($id, $direccion) {
        return $this->model->patchCliente($direccion, $id);
    }
}


?>