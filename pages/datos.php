<?php

class datos{

    public $servidor;
    public $basedatos;
    public $puerto;
    public $usuario;
    public $clave;

    public function __construct($servidor, $basedatos, $puerto, $usuario, $clave){
        $this->servidor = $servidor;
        $this->basedatos = $basedatos;
        $this->puerto = $puerto;
        $this->usuario = $usuario;
        $this->clave = $clave;
    }

    public function ejecutar($opc, $cad, $bdname){
        // $opc (1/0)-> Consulta / Transaccion
        // $cad -> Consulta de Transact SQL
        // $bdname -> Base de datos
        
        // Utilizacion de la topologia de conectividad PDO

        // Resultados
        // ----------
        // 100 - Sin resultado operacion correcta
        // 200 - Con resultado operacion correcta
        // 201 - No data

        if($bdname == ''){
            $dsn = "mysql:host=".$this->servidor.";dbname=".$this->basedatos.";port=".$this->puerto.";charset=utf8mb4";
        }else{
            $dsn = "mysql:host=".$this->servidor.";dbname=".$bdname.";port=".$this->puerto.";charset=utf8mb4";
        };

        try {

            $options = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            $cnx = new \PDO($dsn, $this->usuario, $this->clave, $options);
            $cnx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $cnx->exec("SET collation_connection = 'utf8mb4_unicode_ci'");
            $cnx->exec("SET collation_database = 'utf8mb4_unicode_ci'");
            $cnx->exec("SET collation_server = 'utf8mb4_unicode_ci'");
            $cnx->exec("SET character_set_client = 'utf8mb4'");
            $cnx->exec("SET character_set_connection = 'utf8mb4'");
            $cnx->exec("SET character_set_results = 'utf8mb4'");

        }catch(\PDOException $e){
            echo 'Error: '. $e->getmessage();
        }

        $resultado = $cnx->prepare($cad);
        $resultado->execute();
        if ($opc == 0){
            $arreglo = array("resultado"=>"100","consulta"=>"ok",array());
        }else{
            $numRow = $resultado->rowCount();
            if($numRow != 0){
                $arreglo = array("resultado"=>"200","consulta"=>"ok",$resultado->fetchAll(\PDO::FETCH_ASSOC));
            }else{
                $arreglo = array("resultado"=>"201","consulta"=>"SinDatos",array()); 
            }
        }
        $resultado = null;
        return $arreglo;
    }

    public function combo_help($vista, $selectedValue = 0, $db = __BASEDATOS__) {
        $sentencia = "select nombre, id from {$vista}";
        $resultado = $this->ejecutar(1, $sentencia, $db);
        $combo = '';
        
        if ($resultado[0] == 201) { 
            return null;
        } else {
            $cursor_combo = $resultado[0];
            for ($i = 0; $i < count($cursor_combo); $i++) {
                if (isset($cursor_combo[$i]['id']) && isset($cursor_combo[$i]['nombre'])) {
                    $codigo = $cursor_combo[$i]['id'];
                    $glosa = $cursor_combo[$i]['nombre'];
        
                    if ($codigo === null) {
                        break;
                    }
    
                    $selected = ($codigo == $selectedValue) ? ' selected' : '';
                    
                    $combo .= <<<html
                        <option value="{$codigo}"{$selected}>{$glosa}</option>
                    html;
                }
            }
        }
    
        return $combo;
    }
        
    public function guardar_datos($arreglos){
        $id = $arreglos['id'];
        $placa = $arreglos['placa'];
        $marca = $arreglos['marca'];
        $modelo = $arreglos['modelo'];
        $anio = $arreglos['anio'];
        $nombre = $arreglos['nombre'];
        $apellido = $arreglos['apellido'];
        $documento = $arreglos['documento'];
        $correo = $arreglos['correo'];
        $telefono = $arreglos['telefono'];

        $sentencia = "call sp_alta_ficha($id,'$placa', $marca, '$modelo', '$anio', '$nombre', '$apellido', '$documento', '$correo', '$telefono')";
        $resultado = $this->ejecutar(0, $sentencia, '');
        
        return $resultado;
    }

    function listado_datos($filtroPlaca, $filtroApellidos, $filtrolimit, $offset){
        $sentencia = "call sp_listado_datos('$filtroPlaca', '$filtroApellidos', $filtrolimit, $offset)";
        $resultado = $this->ejecutar(1, $sentencia, '');
        
        return $resultado;
    }

    function recupera_datos($id){
        $sentencia = "call sp_retrive_datos($id)";
        $resultado = $this->ejecutar(1, $sentencia, '');
        
        return $resultado;
    }

    function eliminar_datos($id){
        $sentencia = "call sp_eliminar_datos($id)";
        $resultado = $this->ejecutar(0, $sentencia, '');
        return $resultado;
    }
}


