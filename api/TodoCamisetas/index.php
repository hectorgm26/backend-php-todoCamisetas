<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Manejo de preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autorización - El Token es: ipss
try {
    $headers = getallheaders();
    if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer ipss') {
        http_response_code(403);
        echo json_encode(['error' => 'Token no válido o faltante']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error inesperado en la autorización']);
    exit;
}

// Obtener método HTTP para ruteo
$_method = $_SERVER['REQUEST_METHOD'];

// Obtener recurso e id desde PATH_INFO
if (isset($_SERVER['PATH_INFO'])) {
    $path = trim($_SERVER['PATH_INFO'], '/');
    $parts = explode('/', $path);
    $resource = $parts[0] ?? null;
    $id = $parts[1] ?? null;
} else {
    $resource = null;
    $id = null;
}

// Obtener cuerpo JSON
$body = json_decode(file_get_contents("php://input"), true);

// Cargar controladores
require_once __DIR__ . '/v1/Controller/clientesController.php';
require_once __DIR__ . '/v1/Controller/camisetasController.php';

$clientesController = new clientesController();
$camisetasController = new camisetasController();

// Ruteo principal
switch ($resource) {

    case 'cliente':

        // ruteo para clientes
        switch ($_method) {

            // Metodo GET para obtener clientes
            case 'GET':
                if ($id) {
                    // Si se proporciona un ID, se llamara al método para obtener un cliente específico
                    echo json_encode(['data' => $clientesController->getClienteById($id)]);
                } else {
                    // Si no se proporciona ID, se llamara al método para obtener todos los clientes
                    echo json_encode(['data' => $clientesController->getallClientes()]);
                }
                break;

            // Metodo POST para crear un nuevo cliente
            case 'POST':
                if (!$body || !isset($body['nombre_comercial'], $body['rut'], $body['direccion'], $body['categoria'], $body['contacto_nombre'], $body['contacto_email'], $body['porcentaje_oferta'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Datos incompletos']);
                    break;
                }
                $categoria = $body['categoria'];
                if ($categoria !== 'Regular' && $categoria !== 'Preferencial') {
                    http_response_code(400);
                    echo json_encode(['error' => 'Categoría inválida. Debe ser "Regular" o "Preferencial".']);
                    break;
                }
                if (!is_numeric($body['porcentaje_oferta'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'El porcentaje de oferta debe ser un número.']);
                    break;
                }
                $clientesController->postCliente(
                    $body['nombre_comercial'],
                    $body['rut'],
                    $body['direccion'],
                    $body['categoria'],
                    $body['contacto_nombre'],
                    $body['contacto_email'],
                    $body['porcentaje_oferta']
                );
                http_response_code(201);
                echo json_encode(['msg' => 'Cliente creado correctamente']);
                break;

            // Metodo PUT para actualizar un cliente existente
            case 'PUT':
                if (!$id || !$body || !isset($body['nombre_comercial'], $body['rut'], $body['direccion'], $body['categoria'], $body['contacto_nombre'], $body['contacto_email'], $body['porcentaje_oferta'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID y datos completos requeridos']);
                    break;
                }
                $categoria = $body['categoria'];
                if ($categoria !== 'Regular' && $categoria !== 'Preferencial') {
                    http_response_code(400);
                    echo json_encode(['error' => 'Categoría inválida. Debe ser "Regular" o "Preferencial".']);
                    break;
                }
                if (!is_numeric($body['porcentaje_oferta'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'El porcentaje de oferta debe ser un número.']);
                    break;
                }
                $clientesController->putCliente(
                    $id,
                    $body['nombre_comercial'],
                    $body['rut'],
                    $body['direccion'],
                    $body['categoria'],
                    $body['contacto_nombre'],
                    $body['contacto_email'],
                    $body['porcentaje_oferta']
                );
                http_response_code(200);
                echo json_encode(['msg' => 'Cliente actualizado correctamente']);
                break;

            // Metodo PATCH para actualizar la dirección de un cliente
            case 'PATCH':
                if (!$id || !$body || !isset($body['direccion'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID y dirección requeridos']);
                    break;
                }
                $clientesController->patchCliente($id, $body['direccion']);
                http_response_code(200);
                echo json_encode(['msg' => 'Dirección actualizada correctamente']);
                break;

            // Metodo DELETE para eliminar un cliente
            case 'DELETE':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requerido para eliminar']);
                    break;
                }
                $clientesController->deleteCliente($id);
                http_response_code(200);
                echo json_encode(['msg' => 'Cliente eliminado correctamente']);
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido para cliente']);
        }
        break;

    case 'camisetas':

        // Endpoint especial para precio final: /camisetas/{id}/precio-final?clienteId=xx
        if ($_method === 'GET' && $id && preg_match('/^\d+$/', $id)) {

            // Verificar si la ruta termina con precio-final
            $uri = $_SERVER['REQUEST_URI'];
            if (preg_match('#/camisetas/' . $id . '/precio-final#', $uri)) {
                $cliente_id = isset($_GET['clienteId']) ? intval($_GET['clienteId']) : null;

                if (!$cliente_id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'clienteId es requerido']);
                    break;
                }

                try {
                    $resultado = $camisetasController->getCamisetaPrecioFinal(intval($id), $cliente_id);

                    if ($resultado) {
                        $categoria = $resultado['categoria'] ?? 'desconocida';
                        $precioFinal = (float) ($resultado['precio_final'] ?? 0);

                        $msg = $categoria === 'Preferencial'
                            ? "Precio final por ser cliente Preferencial"
                            : "El precio final a pagar como tiene categoría $categoria es: $precioFinal";

                        echo json_encode([
                            'categoria' => $categoria,
                            'precio_final' => $precioFinal,
                            'msg' => $msg
                        ]);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Camiseta no encontrada o cliente inválido']);
                    }
                } catch (Exception $ex) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Error interno: ' . $ex->getMessage()]);
                }

                break;
            }
        }

        // Definir valores válidos para tallas
        $valoresValidosTallas = ['S', 'M', 'L', 'XL'];

        // Ruteo para camisetas
        switch ($_method) {

            // Metodo GET para obtener camisetas
            case 'GET':
                if ($id && is_numeric($id)) {
                    // Si se proporciona un ID, se llamara al método para obtener una camiseta específica
                    echo json_encode($camisetasController->getCamisetaById(intval($id)));
                } else {
                    // Si no se proporciona ID, se llamara al método para obtener todas las camisetas
                    echo json_encode($camisetasController->getallCamisetas());
                }
                break;

            // Metodo POST para crear una nueva camiseta
            case 'POST':
                if (
                    !$body ||
                    empty($body['titulo']) ||
                    empty($body['club']) ||
                    empty($body['pais']) ||
                    empty($body['tipo']) ||
                    empty($body['color']) ||
                    !isset($body['precio']) || !is_numeric($body['precio']) ||
                    empty($body['detalles']) ||
                    empty($body['sku'])
                ) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Datos incompletos o inválidos']);
                    break;
                }

                // Validar tallas: debe ser array no vacío y con valores válidos
                if (!isset($body['tallas']) || !is_array($body['tallas']) || count($body['tallas']) === 0) {
                    http_response_code(400);
                    echo json_encode(['error' => 'El campo tallas es obligatorio y debe ser un array no vacío']);
                    break;
                }

                foreach ($body['tallas'] as $talla) {
                    if (!in_array($talla, $valoresValidosTallas)) {
                        http_response_code(400);
                        echo json_encode(['error' => "Talla inválida: $talla. Los valores válidos son S, M, L, XL"]);
                        break 2;
                    }
                }

                $res = $camisetasController->postCamiseta(
                    $body['titulo'],
                    $body['club'],
                    $body['pais'],
                    $body['tipo'],
                    $body['color'],
                    floatval($body['precio']),
                    $body['detalles'],
                    $body['sku'],
                    $body['tallas']
                );
                http_response_code(201);
                echo json_encode(['msg' => 'Camiseta creada correctamente']);
                break;

            // Metodo PUT para actualizar una camiseta existente
            case 'PUT':
                if (!$id || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requerido válido']);
                    break;
                }
                if (
                    !$body ||
                    empty($body['titulo']) ||
                    empty($body['club']) ||
                    empty($body['pais']) ||
                    empty($body['tipo']) ||
                    empty($body['color']) ||
                    !isset($body['precio']) || !is_numeric($body['precio']) ||
                    empty($body['detalles']) ||
                    empty($body['sku'])
                ) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Datos incompletos o inválidos']);
                    break;
                }

                // Validar tallas: debe ser array no vacío y con valores válidos
                if (!isset($body['tallas']) || !is_array($body['tallas']) || count($body['tallas']) === 0) {
                    http_response_code(400);
                    echo json_encode(['error' => 'El campo tallas es obligatorio y debe ser un array no vacío']);
                    break;
                }

                foreach ($body['tallas'] as $talla) {
                    if (!in_array($talla, $valoresValidosTallas)) {
                        http_response_code(400);
                        echo json_encode(['error' => "Talla inválida: $talla. Los valores válidos son S, M, L, XL"]);
                        break 2;
                    }
                }

                $res = $camisetasController->putCamiseta(
                    intval($id),
                    $body['titulo'],
                    $body['club'],
                    $body['pais'],
                    $body['tipo'],
                    $body['color'],
                    floatval($body['precio']),
                    $body['detalles'],
                    $body['sku'],
                    $body['tallas']
                );
                http_response_code(200);
                echo json_encode(['msg' => 'Camiseta actualizada correctamente']);
                break;

            // Metodo PATCH para actualizar el precio de una camiseta
            case 'PATCH':
                if (!$id || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requerido']);
                    break;
                }
                if (!isset($body['precio'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Precio requerido para patch']);
                    break;
                }
                $res = $camisetasController->patchPrecio(intval($id), $body['precio']);
                echo json_encode(['msg' => 'Precio actualizado correctamente']);
                break;

            // Metodo DELETE para eliminar una camiseta
            case 'DELETE':
                if (!$id || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requerido']);
                    break;
                }
                $res = $camisetasController->deleteCamiseta(intval($id));
                echo json_encode(['msg' => 'Camiseta eliminada correctamente']);
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido para camisetas']);
        }
        break;

    // Recursos no encontrados
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Recurso no encontrado']);
        break;
}
