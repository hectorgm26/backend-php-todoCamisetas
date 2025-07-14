<?php
require_once __DIR__ . '/../Models/CamisetasModel.php';

class camisetasController
{

    private $model;

    public function __construct()
    {
        $this->model = new CamisetasModel();
    }

    // Metodo Get para obtener todas las camisetas
    public function getallCamisetas()
    {
        return $this->model->getCamisetas();
    }

    // Metodo Get para obtener una determinada camiseta por ID
    public function getCamisetaById($id)
    {
        return $this->model->getCamisetaById($id);
    }

    // Metodo Post para crear una nueva camiseta
    public function postCamiseta($titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku, $tallas = [])
    {
        // Mapear nombres a IDs
        $tallas_ids = $this->model->obtenerIdsTallasPorNombres($tallas);

        $id = $this->model->postCamiseta($titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku);

        if (is_int($id) && !empty($tallas_ids)) {
            $this->model->asignarTallas($id, $tallas_ids);
        }
        return $id;
    }

    // Metodo Put para actualizar una camiseta existente
    public function putCamiseta($id, $titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku, $tallas = [])
    {
        $tallas_ids = $this->model->obtenerIdsTallasPorNombres($tallas);

        $res = $this->model->updateCamiseta($id, $titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku);

        // Si hubo filas afectadas o las tallas no están vacías, asignar tallas
        if (($res !== false) && !empty($tallas_ids)) {
            $this->model->asignarTallas($id, $tallas_ids);
        }
        return $res;
    }

    // Metodo Delete para eliminar una camiseta por ID
    public function deleteCamiseta($id)
    {
        return $this->model->deleteCamiseta($id);
    }

    // Metodo Patch para actualizar el precio de una camiseta
    public function patchPrecio($id, $precio)
    {
        return $this->model->patchPrecio($id, $precio);
    }

    // Metodo Get para obtener el precio final de una camiseta para un cliente
    public function getCamisetaPrecioFinal($camiseta_id, $cliente_id)
    {
        return $this->model->getCamisetaPrecioFinal($camiseta_id, $cliente_id);
    }

    // Metodo auxiliar de una tabla intermedia para asignar tallas a una camiseta
    public function asignarTallas($camiseta_id, $tallas_ids = [])
    {
        return $this->model->asignarTallas($camiseta_id, $tallas_ids);
    }
}
