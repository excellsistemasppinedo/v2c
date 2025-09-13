<?php
    require_once 'screen.php';
    require_once 'constantes.php';
    $loScreen = new screen(__SERVER__,__BASEDATOS__,__PUERTO__,__USUARIO__,__CLAVE__);

    if(isset($_POST['accion'])){
        $accion = $_POST['accion'];
    }else{
        $accion = '';
    }

    switch($accion){
        case 'guardar_datos':
            $arreglo = array(
                'id' => $_POST['id'],
                'placa' => $_POST['placa'],
                'marca' => $_POST['marca'],
                'modelo' => $_POST['modelo'],
                'anio' => $_POST['anio'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'documento' => $_POST['documento'],
                'correo' => $_POST['correo'],
                'telefono' => $_POST['telefono']
            );

            $preresultado = $loScreen->guardar_datos($arreglo);
            $resultado = json_encode($preresultado);
            echo $resultado;
            break;
        case "listado_datos":
            $filtroPlaca = $_POST['filtroPlaca'];
            $filtroApellidos = $_POST['filtroApellidos'];
            $filtrolimit = $_POST['filtrolimit'];
            $offset = $_POST['offset'];

            $datos = $loScreen->listado_datos($filtroPlaca, $filtroApellidos, $filtrolimit, $offset);
            $preresultado = $loScreen-> tabla($datos[0]);
            $resultado = json_encode($preresultado);
            echo $resultado;
            break;
        case 'modificar_registro';
            $id = $_POST['id'];
            $datos = $loScreen->recupera_datos($id);
            $preresultado = $loScreen->formulario($datos[0]);
            $resultado = json_encode($preresultado);
            echo $resultado;
            break;
        case 'eliminar_registro';
            $id = $_POST['id'];
            $datos = $loScreen->eliminar_datos($id);
            $resultado = json_encode($datos);
            echo $resultado;
            break;            


    }