<?php
require_once __DIR__ . '/../Config/Conexion.php';

class CamisetasModel extends Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    // Destructor para cerrar la conexión cuando el objeto se destruye, esto para evitar que la conexion se cierre prematuramente cuando se usa en un contexto de API REST.
    public function __destruct()
    {
        if ($this->con) {
            $this->con->close();
        }
    }

    // Obtener todas las camisetas activas junto a sus tallas
    public function getCamisetas()
    {
        try {
            $parametrizacion = $this->con->prepare(
                "SELECT c.*, GROUP_CONCAT(t.talla) AS tallas
                 FROM camisetas c
                 LEFT JOIN camiseta_talla ct ON c.id = ct.camiseta_id
                 LEFT JOIN tallas t ON ct.talla_id = t.id
                 WHERE c.estado_disponible = ?
                 GROUP BY c.id"
            );
            $estado = 1;
            $parametrizacion->bind_param('i', $estado);
            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            $r = array();
            while ($fila = $resultado->fetch_assoc()) {
                $fila['tallas'] = $fila['tallas'] !== null ? explode(',', $fila['tallas']) : [];
                $r[] = $fila;
            }
            return $r;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // Obtener camiseta por id con tallas
    public function getCamisetaById($id)
    {
        try {
            $parametrizacion = $this->con->prepare(
                "SELECT c.*, GROUP_CONCAT(t.talla) AS tallas
                 FROM camisetas c
                 LEFT JOIN camiseta_talla ct ON c.id = ct.camiseta_id
                 LEFT JOIN tallas t ON ct.talla_id = t.id
                 WHERE c.id = ? AND c.estado_disponible = ?
                 GROUP BY c.id"
            );
            $estado = 1;
            $parametrizacion->bind_param('ii', $id, $estado);
            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $fila['tallas'] = $fila['tallas'] !== null ? explode(',', $fila['tallas']) : [];
                return $fila;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function postCamiseta($titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku)
    {
        try {
            $parametrizacion = $this->con->prepare(
                "INSERT INTO camisetas (titulo, club, pais, tipo, color, precio, detalles, sku) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $parametrizacion->bind_param('sssssiss', $titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku);
            $parametrizacion->execute();
            // Devuelvo el id insertado para poder asignar tallas luego si se desea
            return $this->con->insert_id;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // Actualizar camiseta
    public function updateCamiseta($id, $titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku)
    {
        try {
            $parametrizacion = $this->con->prepare(
                "UPDATE camisetas SET titulo=?, club=?, pais=?, tipo=?, color=?, precio=?, detalles=?, sku=? WHERE id=?"
            );
            $parametrizacion->bind_param('sssssisii', $titulo, $club, $pais, $tipo, $color, $precio, $detalles, $sku, $id);
            $parametrizacion->execute();
            return $parametrizacion->affected_rows;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // "Eliminar" camiseta (cambiar estado)
    public function deleteCamiseta($id)
    {
        try {
            $parametrizacion = $this->con->prepare("UPDATE camisetas SET estado_disponible = ? WHERE id = ?");
            $estado = 0;
            $parametrizacion->bind_param('ii', $estado, $id);
            $parametrizacion->execute();
            return $parametrizacion->affected_rows;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // PATCH: Actualizar solo el precio (como ejemplo)
    public function patchPrecio($id, $precio)
    {
        try {
            $parametrizacion = $this->con->prepare("UPDATE camisetas SET precio = ? WHERE id = ?");
            $parametrizacion->bind_param('ii', $precio, $id);
            $parametrizacion->execute();
            return $parametrizacion->affected_rows;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // Obtener camiseta con precio final según cliente (lógica oferta)
    public function getCamisetaPrecioFinal($camiseta_id, $cliente_id)
    {
        try {
            $parametrizacion = $this->con->prepare(
                "SELECT c.id, c.titulo, c.detalles, c.precio, cl.categoria, cl.porcentaje_oferta,
                    CASE
                        WHEN cl.categoria = 'Preferencial' AND c.precio IS NOT NULL THEN c.precio * (100 - cl.porcentaje_oferta) / 100
                        ELSE c.precio
                    END AS precio_final,
                (SELECT GROUP_CONCAT(t.talla)
                    FROM camiseta_talla ct
                    JOIN tallas t ON ct.talla_id = t.id
                    WHERE ct.camiseta_id = c.id
                ) AS tallas
                FROM camisetas c
                LEFT JOIN clientes cl ON cl.id = ?
                WHERE c.id = ? AND c.estado_disponible = 1"
            );

            $parametrizacion->bind_param('ii', $cliente_id, $camiseta_id);
            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $fila['tallas'] = explode(',', $fila['tallas']);
                $fila['precio_final'] = (float) $fila['precio_final'];
                return $fila;
            } else {
                return null;
            }
        } catch (mysqli_sql_exception $ex) {
            // En vez de devolver el objeto excepción, lanzar el error
            throw $ex;
        }
    }


    public function asignarTallas($camiseta_id, $tallas_ids = [])
    {
        try {
            // Borramos las tallas previas para evitar duplicados
            $parametrizacion = $this->con->prepare("DELETE FROM camiseta_talla WHERE camiseta_id = ?");
            $parametrizacion->bind_param('i', $camiseta_id);
            $parametrizacion->execute();

            // Insertamos nuevas tallas
            foreach ($tallas_ids as $talla_id) {
                $parametrizacion = $this->con->prepare("INSERT INTO camiseta_talla (camiseta_id, talla_id) VALUES (?, ?)");
                $parametrizacion->bind_param('ii', $camiseta_id, $talla_id);
                $parametrizacion->execute();
            }
            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function obtenerIdsTallasPorNombres($nombresTallas = [])
    {
        $ids = [];
        try {
            $stmt = $this->con->prepare("SELECT id FROM tallas WHERE talla = ?");
            foreach ($nombresTallas as $talla) {
                $stmt->bind_param('s', $talla);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($fila = $res->fetch_assoc()) {
                    $ids[] = intval($fila['id']);
                }
            }
        } catch (Exception $e) {
            return [];
        }
        return $ids;
    }
}
