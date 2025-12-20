<?php

class Categoria
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    private function limpiarResultadosCall(): void
    {
        while ($this->conn->more_results() && $this->conn->next_result()) {
            $extra = $this->conn->use_result();
            if ($extra instanceof mysqli_result) {
                $extra->free();
            }
        }
    }

    public function obtenerTodas(): array
    {
        $sql = "CALL sp_categorias_listar()";
        $result = $this->conn->query($sql);
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $this->limpiarResultadosCall();
        return $rows;
    }

    public function obtenerTodasActivas(): array
    {
        $sql = "CALL sp_categorias_listar_activas()";
        $result = $this->conn->query($sql);
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $this->limpiarResultadosCall();
        return $rows;
    }

    public function obtenerPorId(int $id_categoria): ?array
    {
        $sql = "CALL sp_categorias_obtener_por_id(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_categoria);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $result = $stmt->get_result();
        $categoria = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $categoria ?: null;
    }

public function crearCategoria(string $nombre, ?string $descripcion = null, string $estado = 'activo'): array
{
    $sql = "CALL sp_categorias_crear(?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        $this->limpiarResultadosCall();
        return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];
    }

    $stmt->bind_param("sss", $nombre, $descripcion, $estado);

    try {
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultadosCall();
        return ['ok' => true, 'msg' => 'Categoría creada correctamente.'];

    } catch (mysqli_sql_exception $e) {
        $stmt->close();
        $this->limpiarResultadosCall();

        if ((int)$e->getCode() === 45000) {
            return ['ok' => false, 'msg' => $e->getMessage()]; 
        }

        return ['ok' => false, 'msg' => 'No se pudo crear la categoría, ya exite una con este nombre.'];
    }
}



public function editarCategoria(int $id_categoria, string $nombre, ?string $descripcion, string $estado): array
{
    $sql = "CALL sp_categorias_editar(?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        $this->limpiarResultadosCall();
        return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];
    }

    $stmt->bind_param("isss", $id_categoria, $nombre, $descripcion, $estado);

    try {
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultadosCall();
        return ['ok' => true, 'msg' => 'Categoría actualizada correctamente.'];

    } catch (mysqli_sql_exception $e) {
        $stmt->close();
        $this->limpiarResultadosCall();

        if ((int)$e->getCode() === 45000) {
            return ['ok' => false, 'msg' => $e->getMessage()]; // 👈 mensaje del trigger
        }

        return ['ok' => false, 'msg' => 'No se pudo actualizar la categoría, ya exite una con este nombre.'];
    }
}



    public function desactivarCategoria(int $id_categoria): bool
    {
        $sql = "CALL sp_categorias_desactivar(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_categoria);
        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();
        return $ok;
    }

public function eliminarCategoria(int $id_categoria): array
{
    $sql = "CALL sp_categorias_eliminar(?)";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        $this->limpiarResultadosCall();
        return ['ok' => false, 'msg' => 'No se pudo preparar la eliminación.'];
    }

    $stmt->bind_param("i", $id_categoria);

    try {
        $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();

        return ['ok' => true, 'msg' => 'Categoría eliminada correctamente.'];

    } catch (mysqli_sql_exception $e) {

        $stmt->close();
        $this->limpiarResultadosCall();

        // 1451 = No puedes borrar porque hay "hijos" (productos) asociados
        if ((int)$e->getCode() === 1451) {
            return ['ok' => false, 'msg' => 'No se puede eliminar: esta categoría tiene productos asociados.'];
        }

        return ['ok' => false, 'msg' => 'No se pudo eliminar la categoría.'];
    }
}


    public function buscarPorNombre(string $nombre): ?array
    {
        $sql = "CALL sp_categorias_buscar_por_nombre(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $res = $stmt->get_result();
        $dato = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $dato ?: null;
    }

    public function crearRapida(string $nombre): ?int
    {
        $sql = "CALL sp_categorias_crear_rapida(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $row && isset($row['id_categoria']) ? (int)$row['id_categoria'] : null;
    }
}