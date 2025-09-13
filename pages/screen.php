<?php
include 'datos.php';
class screen extends datos{
    public function head(){
        $lcCuerpo = <<<HTML
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <title>VIP2CARS - Gestión de Vehículos</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                            <link rel="stylesheet" href="css/testv2c.css">
                        </head>
                    HTML;
        return $lcCuerpo;
    }

    public function js(){
        $lcCuerpo = <<<HTML
                        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script src="js/test.js"></script>
                    HTML;
        return $lcCuerpo;
    }

    public function navbar(){
        $lcCuerpo = <<<HTML
                    <nav class="navbar navbar-expand-lg">
                        <div class="container">
                        <a class="navbar-brand fw-bold" href="#">VIP2CARS</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        </div>
                    </nav>        
        HTML;
        return $lcCuerpo;
    }

    public function contenido_principal($datos = null, $paginaActual = 1, $totalRegistros = 0, $registrosPorPagina = 10){
        $lcCuerpo = <<<HTML
            <div class="container my-4">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-dark">Gestión de Vehículos</h4>
                        <button class="btn btn-vip" id="btnNuevo">
                            <i class="bi bi-plus-circle"></i> 🚕 Nuevo Vehículo
                        </button>
                    </div>    

                    <!-- Filtros -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" id="filtroPlaca" class="form-control form-control-sm" placeholder="Filtrar por Placa">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="filtroApellidos" class="form-control form-control-sm" placeholder="Filtrar por Apellidos">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-vip btn-sm w-100" id="btnFiltrar">
                                <i class="bi bi-search"></i> 🔽 Filtrar
                            </button>
                        </div>
                    </div>
                    <div id="tabla_principal">
                       {$this->tabla($datos)}    
                    </div>
                    {$this->paginador($paginaActual, $totalRegistros, $registrosPorPagina)}
                    {$this->modal()}
                </div>            
            </div>
        HTML;
        return $lcCuerpo;
    }

    public function tabla($datos=null){
        if($datos==null){
            $lcDatos = <<<HTML
                        <tr>
                          <td colspan="9" class="text-center">
                            <i class="bi bi-info-circle"></i> No se encontraron registros
                          </td>
                        </tr>
            HTML;
        }else{
            $lcDatos = '';
            for($i=0; $i<count($datos); $i++){
                  $lcDatos .= <<<HTML
                                <tr data-id="{$datos[$i]['id']}">
                                  <td>{$datos[$i]['placa']}</td>
                                  <td>{$datos[$i]['marca']}</td>
                                  <td>{$datos[$i]['modelo']}</td>
                                  <td>{$datos[$i]['anio']}</td>
                                  <td>{$datos[$i]['nombre']} {$datos[$i]['apellidos']}</td>
                                  <td>{$datos[$i]['documento']}</td>
                                  <td>{$datos[$i]['correo']}</td>
                                  <td>{$datos[$i]['telefono']}</td>
                                  <td>
                                    <button class="btn btn-vip btn-sm" id="btnEditar">
                                      <i class="bi bi-pencil-square"></i> ✏️
                                    </button>
                                    <button class="btn btn-vip btn-sm" id="btnEliminar">
                                      <i class="bi bi-trash"></i> 🗑️ 
                                    </button>
                                  </td>
                                </tr>
               HTML;
            }
        }


        $lcCuerpo = <<<HTML
                        <div class="table-responsive">
                            <table class="table table-striped align-middle table-hover">
                              <thead>
                                  <tr>
                                  <th>Placa</th>
                                  <th>Marca</th>
                                  <th>Modelo</th>
                                  <th>Año</th>
                                  <th>Cliente</th>
                                  <th>Documento</th>
                                  <th>Correo</th>
                                  <th>Teléfono</th>
                                  <th>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody id="tablaVehiculos">
                                  {$lcDatos}
                              </tbody>
                            </table>
                        </div>        
        HTML;
        return $lcCuerpo;
    }

    public function modal(){
        $lcCuerpo = <<<HTML
            <div class="modal fade" id="modalVehiculo" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <form id="formVehiculo">
                            <div class="modal-header bg-dark text-white py-2">
                                <h6 class="modal-title">Registrar Vehículo</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="btnNuevo"></button>
                            </div>
                            <div class="modal-body p-3" style="font-size: 0.85rem;" id="modalCuerpo">
                                {$this->formulario()}
                            </div>
                            <div class="modal-footer py-2">
                                <button type="button" class="btn btn-vip btn-sm" id="btnGuardar">Guardar</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btnCerrar">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>                    
        HTML;
        return $lcCuerpo;
    }

    public function formulario($data=0){
      if($data == null){
        $id = 0;
        $placa = '';
        $marca = 0;
        $modelo = '';
        $anio = date('Y');
        $nombre = '';
        $apellido = '';
        $documento = '';
        $correo = '';
        $telefono = '';
      }else{
        $dt=$data[0];
        $id = $dt['id'];
        $placa = $dt['placa'];
        $marca = $dt['marca'];
        $modelo = $dt['modelo'];
        $anio = $dt['anio'];
        $nombre = $dt['nombre'];
        $apellido = $dt['apellidos'];
        $documento = $dt['documento'];
        $correo = $dt['correo'];
        $telefono = $dt['telefono'];
      }
      $anio_actual = date('Y');
      $lcCuerpo = <<<HTML
            <!-- Datos del Auto -->
            <div class="card mb-2">
              <div class="card-header bg-danger text-white fw-bold py-1" style="font-size: 0.9rem;">
                Datos del Vehículo
              </div>
              <div class="card-body py-2">
                <div class="row g-2">
                  <input type="hidden" name="id" id="id" value="$id">
                  <div class="col-md-4">
                    <label class="form-label mb-1">Placa</label>
                    <input type="text" name="placa" class="form-control form-control-sm" id="placa" value="$placa" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Marca</label>
                    <!-- <input type="text" name="marca" class="form-control form-control-sm" required> -->
                     <select name="marca" class="form-select form-select-sm" id="marca" required>
                        <option value="0">Seleccione una Marca</option>
                        {$this->combo_help('vw_help_marca', $marca)}
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Modelo</label>
                    <input type="text" name="modelo" class="form-control form-control-sm" id="modelo" value="$modelo" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Año de Fabricación</label>
                    <input type="number" name="anio" class="form-control form-control-sm" id="anio" value = "$anio" max="{$anio_actual}" min="1973" required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Datos del Cliente -->
            <div class="card">
              <div class="card-header bg-dark text-white fw-bold py-1" style="font-size: 0.9rem;">
                Datos del Cliente
              </div>
              <div class="card-body py-2">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label class="form-label mb-1">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm" id="nombre" value="$nombre" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label mb-1">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control form-control-sm" id="apellido"  value="$apellido" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Nro. Documento</label>
                    <input type="text" name="documento" class="form-control form-control-sm" id="documento" value="$documento" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Correo</label>
                    <input type="email" name="correo" class="form-control form-control-sm" id="correo" value="$correo" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label mb-1">Teléfono</label>
                    <input type="text" name="telefono" class="form-control form-control-sm" id="telefono" value="$telefono" required>
                  </div>
                </div>
              </div>
            </div>                
        HTML;
        return $lcCuerpo;
    }

    public function footer(){
        $lcCuerpo = <<<HTML
            <footer>
                <p>© 2025 TEST - VIP2CARS - Prueba de Conocimientos - Pablo Pinedo Iturrioz</p>
            </footer>
        HTML;
        return $lcCuerpo;
    }

    public function paginador($paginaActual = 1, $totalRegistros = 0, $registrosPorPagina = 10) {
        $totalPaginas = max(1, ceil($totalRegistros / $registrosPorPagina));

        $lcCuerpo = <<<HTML
        <div class="d-flex justify-content-between align-items-center mt-3">
            <!-- Selector de registros -->
            <div>
                <label for="selectRegistros" class="form-label me-2">Mostrar</label>
                <select id="selectRegistros" class="form-select form-select-sm d-inline-block w-auto" onchange="listado_datos(1)">
                    <option value="5"  ".($registrosPorPagina==5?'selected':'').">5</option>
                    <option value="10" ".($registrosPorPagina==10?'selected':'').">10</option>
                    <option value="25" ".($registrosPorPagina==25?'selected':'').">25</option>
                    <option value="50" ".($registrosPorPagina==50?'selected':'').">50</option>
                </select>
                <span class="ms-2">registros por página</span>
            </div>

            <!-- Paginador -->
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
    HTML;

        // Botón "Anterior"
        if ($paginaActual > 1) {
            $prev = $paginaActual - 1;
            $lcCuerpo .= "<li class='page-item'><a class='page-link' href='#' onclick='listado_datos({$prev})'>Anterior</a></li>";
        } else {
            $lcCuerpo .= "<li class='page-item disabled'><span class='page-link'>Anterior</span></li>";
        }

        // Páginas
        for ($i = 1; $i <= $totalPaginas; $i++) {
            $active = ($i == $paginaActual) ? "active" : "";
            $lcCuerpo .= "<li class='page-item {$active}'><a class='page-link' href='#' onclick='listado_datos({$i})'>{$i}</a></li>";
        }

        // Botón "Siguiente"
        if ($paginaActual < $totalPaginas) {
            $next = $paginaActual + 1;
            $lcCuerpo .= "<li class='page-item'><a class='page-link' href='#' onclick='listado_datos({$next})'>Siguiente</a></li>";
        } else {
            $lcCuerpo .= "<li class='page-item disabled'><span class='page-link'>Siguiente</span></li>";
        }

        $lcCuerpo .= <<<HTML
                </ul>
            </nav>
        </div>
    HTML;

        return $lcCuerpo;
    }
}