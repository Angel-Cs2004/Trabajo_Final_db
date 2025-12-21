<?php
$pageTitle = "Reporte General";
require __DIR__ . '/../../layouts/header.php';

// PDF del reporte general (productos)
$queryPdfGeneral = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfReporteGeneral',
    'id_categoria' => $idCategoria ?? 0,
    'precio_min'   => $precioMin ?? 0,
    'precio_max'   => $precioMax ?? 0,
    'id_negocio'   => $idNegocio ?? 0,
]);

// PDF del reporte usuarios
$queryPdfUsuarios = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfUsuarios',
    'id_rol'  => $idRolU ?? 0,
    'estado'  => $estadoU ?? 'todos',
]);

$runGeneral  = isset($_GET['run_general']) ? (int)$_GET['run_general'] : 0;
$runUsuarios = isset($_GET['run_usuarios']) ? (int)$_GET['run_usuarios'] : 0;
// PDF del detalle tienda
$queryPdfDetalle = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfDetalleTienda',
    'id_propietario' => $idPropietario ?? 0,
    'id_negocio'     => $idNegocioDet ?? 0, // OJO: variable nueva
]);

$runDetalle = isset($_GET['run_detalle']) ? (int)$_GET['run_detalle'] : 0;

?>


<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-8">

  <!-- ===================================================== -->
  <!-- BLOQUE 1: REPORTE GENERAL (TU DISEÑO, NO BORRADO) -->
  <!-- ===================================================== -->
  <div class="bg-white rounded-lg shadow">

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte General</h1>

      <a href="index.php?<?= $queryPdfGeneral ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="reporteGeneral">
        <!-- ✅ esto hace que recién cargue resultados -->
        <input type="hidden" name="run_general" value="1">

        <div>
          <label class="text-sm font-medium">Categoría</label>
          <select name="id_categoria" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idCategoria ?? 0) === 0) ? 'selected' : '' ?>>Todas (muestra todo)</option>
            <?php foreach (($categorias ?? []) as $c): ?>
              <option value="<?= (int)$c['id_categoria'] ?>"
                <?= ((int)($idCategoria ?? 0) === (int)$c['id_categoria']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Precio mínimo</label>
          <input type="number" step="0.01" name="precio_min"
                 value="<?= htmlspecialchars((string)($precioMin ?? 0)) ?>"
                 class="w-full border rounded px-3 py-2"
                 placeholder="0">
        </div>

        <div>
          <label class="text-sm font-medium">Precio máximo</label>
          <input type="number" step="0.01" name="precio_max"
                 value="<?= htmlspecialchars((string)($precioMax ?? 0)) ?>"
                 class="w-full border rounded px-3 py-2"
                 placeholder="0 (muestra todo)">
        </div>

        <div>
          <label class="text-sm font-medium">Negocio (para rango de precio)</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idNegocio ?? 0) === 0) ? 'selected' : '' ?>>Todos los negocios</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>"
                <?= ((int)($idNegocio ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="md:col-span-4 flex justify-end gap-2 pt-2">
          <a href="index.php?c=reporte&a=reporteGeneral"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Aplicar filtros
          </button>
        </div>
      </form>
    </div>

    <!-- Resultados (✅ SOLO SI run_general=1) -->
    <?php if ($runGeneral === 1): ?>
      <div class="px-6 py-6 space-y-8">

        <!-- Productos por Categoría -->
        <section>
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Productos por Categoría</h2>
            <span class="text-xs text-gray-500">
              <?= ((int)($idCategoria ?? 0) === 0) ? 'Mostrando: todas las categorías' : 'Mostrando: categoría seleccionada' ?>
            </span>
          </div>

          <?php if (!empty($porCategoria)): ?>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-2 text-left">Categoría</th>
                    <th class="px-4 py-2 text-left">Producto</th>
                    <th class="px-4 py-2 text-left">Precio</th>
                    <th class="px-4 py-2 text-left">Negocio</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($porCategoria as $p): ?>
                    <tr class="border-t hover:bg-gray-50">
                      <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                      <td class="px-4 py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-gray-500 text-sm">No hay resultados para mostrar.</p>
          <?php endif; ?>
        </section>

        <!-- Productos por Rango de Precio -->
        <section>
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Productos por Rango de Precio</h2>
            <span class="text-xs text-gray-500">
              Rango: S/ <?= htmlspecialchars((string)($precioMin ?? 0)) ?> – S/ <?= htmlspecialchars((string)($precioMax ?? 0)) ?>
              <?= ((int)($idNegocio ?? 0) === 0) ? '(todos los negocios)' : '(negocio seleccionado)' ?>
            </span>
          </div>

          <?php if (!empty($porRango)): ?>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-2 text-left">Producto</th>
                    <th class="px-4 py-2 text-left">Precio</th>
                    <th class="px-4 py-2 text-left">Categoría</th>
                    <th class="px-4 py-2 text-left">Negocio</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($porRango as $p): ?>
                    <tr class="border-t hover:bg-gray-50">
                      <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                      <td class="px-4 py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-gray-500 text-sm">No hay resultados para mostrar.</p>
          <?php endif; ?>
        </section>

      </div>
    <?php endif; ?>

  </div>


  <!-- ===================================================== -->
  <!-- BLOQUE 2: REPORTE DE USUARIOS (NUEVO, SEPARADO) -->
  <!-- ===================================================== -->
  <div class="bg-white rounded-lg shadow">

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte de Usuarios</h1>

      <a href="index.php?<?= $queryPdfUsuarios ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="reporteGeneral">
        <!-- ✅ esto hace que recién cargue usuarios -->
        <input type="hidden" name="run_usuarios" value="1">

        <div>
          <label class="text-sm font-medium">Rol</label>
          <select name="id_rol_u" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idRolU ?? 0) === 0) ? 'selected' : '' ?>>Todos</option>
            <?php foreach (($roles ?? []) as $r): ?>
              <option value="<?= (int)$r['id_rol'] ?>"
                <?= ((int)($idRolU ?? 0) === (int)$r['id_rol']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Estado</label>
          <select name="estado_u" class="w-full border rounded px-3 py-2">
            <option value="todos"   <?= (($estadoU ?? 'todos') === 'todos') ? 'selected' : '' ?>>Todos</option>
            <option value="activo"  <?= (($estadoU ?? 'todos') === 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo"<?= (($estadoU ?? 'todos') === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
          </select>
        </div>

        <div class="md:col-span-3 flex justify-end gap-2 pt-2">
          <a href="index.php?c=reporte&a=reporteGeneral"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Aplicar filtros
          </button>
        </div>
      </form>
    </div>

    <!-- Resultados (✅ SOLO SI run_usuarios=1) -->
    <?php if ($runUsuarios === 1): ?>
      <div class="px-6 py-6">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Usuarios</h2>
          <span class="text-xs text-gray-500">Total: <?= !empty($usuarios) ? count($usuarios) : 0 ?></span>
        </div>

        <?php if (!empty($usuarios)): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left">ID</th>
                  <th class="px-4 py-2 text-left">Nombre</th>
                  <th class="px-4 py-2 text-left">Correo</th>
                  <th class="px-4 py-2 text-left">Identificación</th>
                  <th class="px-4 py-2 text-left">Teléfono</th>
                  <th class="px-4 py-2 text-left">Estado</th>
                  <th class="px-4 py-2 text-left">Rol</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $u): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?= (int)($u['id_usuario'] ?? 0) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['nombre'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['correo'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['identificacion'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['telefono'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['estado'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['rol'] ?? '-') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-sm">No hay resultados para mostrar.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>

  <!-- ===================================================== -->
<!-- BLOQUE 3: DETALLE DE TIENDA -->
<!-- ===================================================== -->
<!-- ===================================================== -->
<!-- BLOQUE 3: DETALLE DE TIENDA (CORREGIDO Y SEGURO) -->
<!-- ===================================================== -->
<div class="bg-white rounded-lg shadow">

  <!-- Header -->
  <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-800">Detalle de Tienda</h1>

    <a href="index.php?<?= $queryPdfDetalle ?>"
       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium
              <?= ($runDetalle === 1 && (int)($idNegocioDet ?? 0) > 0) ? '' : 'opacity-50 pointer-events-none' ?>">
      Descargar PDF
    </a>
  </div>

  <!-- Filtros -->
  <div class="px-6 py-4 border-b border-gray-200">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="hidden" name="c" value="reporte">
      <input type="hidden" name="a" value="reporteGeneral">
      <input type="hidden" name="run_detalle" value="1">

      <!-- Propietario -->
      <div>
        <label class="text-sm font-medium">Propietario</label>
        <select name="id_propietario" class="w-full border rounded px-3 py-2">
          <option value="0">Selecciona</option>
          <?php foreach (($propietarios ?? []) as $p): ?>
            <option value="<?= (int)$p['id_usuario'] ?>"
              <?= ((int)($idPropietario ?? 0) === (int)$p['id_usuario']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Negocio -->
      <div>
        <label class="text-sm font-medium">Negocio</label>
        <select name="id_negocio_det"
                class="w-full border rounded px-3 py-2"
                <?= ((int)($idPropietario ?? 0) <= 0) ? 'disabled' : '' ?>>
          <option value="0">Selecciona</option>
          <?php foreach (($negociosDet ?? []) as $n): ?>
            <option value="<?= (int)$n['id_negocio'] ?>"
              <?= ((int)($idNegocioDet ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($n['negocio'] ?? $n['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Acciones -->
      <div class="flex justify-end items-end gap-2">
        <a href="index.php?c=reporte&a=reporteGeneral"
           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
          Limpiar
        </a>

        <!-- ✅ NUEVO: Cargar negocios (solo recarga combo) -->
        <button type="submit"
                name="cargar_negocios"
                value="1"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Cargar negocios
        </button>

        <!-- Ver detalle -->
        <button type="submit"
                name="ver_detalle"
                value="1"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Ver
        </button>
      </div>
    </form>
  </div>

  <!-- Resultados -->
  <?php if ($runDetalle === 1 && !empty($negocioInfo)): ?>
    <div class="px-6 py-6 space-y-6">

      <!-- Información del negocio -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border rounded p-4">
          <div class="text-xs text-gray-500">Negocio</div>
          <div class="font-semibold">
            <?= htmlspecialchars($negocioInfo['nombre'] ?? '-') ?>
          </div>
        </div>

        <div class="border rounded p-4">
          <div class="text-xs text-gray-500">Propietario</div>
          <div class="font-semibold">
            <?= htmlspecialchars($negocioInfo['propietario'] ?? '-') ?>
          </div>
        </div>

        <div class="border rounded p-4">
          <div class="text-xs text-gray-500">Estado</div>
          <div class="font-semibold">
            <?= htmlspecialchars($disponibilidad ?? '-') ?>
          </div>
        </div>
      </div>

      <!-- Productos por categoría -->
      <?php if (!empty($productosPorCategoria)): ?>
        <?php foreach ($productosPorCategoria as $cat => $items): ?>
          <div class="border rounded-lg">
            <div class="px-4 py-2 border-b bg-gray-50 font-semibold">
              <?= htmlspecialchars($cat) ?> (<?= count($items) ?>)
            </div>
            <div class="p-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-gray-600">
                    <th class="py-1">Producto</th>
                    <th class="py-1">Precio</th>
                    <th class="py-1">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($items as $p): ?>
                    <tr class="border-t">
                      <td class="py-1">
                        <?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?>
                      </td>
                      <td class="py-1">
                        S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?>
                      </td>
                      <td class="py-1">
                        <?= htmlspecialchars($p['estado'] ?? '-') ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-gray-500 text-sm">
          Este negocio no tiene productos registrados.
        </p>
      <?php endif; ?>

    </div>
  <?php elseif ($runDetalle === 1 && !empty($idPropietario) && !empty($idNegocioDet)): ?>
    <div class="px-6 py-6">
      <p class="text-gray-500 text-sm">
        No se encontró información para ese negocio.
      </p>
    </div>
  <?php endif; ?>

</div>



</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
