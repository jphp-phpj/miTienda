<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'){
    if(isset($_GET['id'])) {

        $id = (int) $_GET['id'];   // guardar variable id que viene por GET

        $res = $mbd->prepare("SELECT id, producto_id FROM atributo_producto WHERE id = ?"); // dont forget to close ()s []s {}s ""s ''s ``s......
        $res->bindParam(1, $id); // sanitizar antes de ejecutar
        $res->execute(); // ejecutar consulta
        $atrib_prod = $res->fetch(); // recuramos la fila si existe

        // validamos la existencia de la marca que se dease eliminar
        
        if ($atrib_prod) {
            // procedemos a elimnar 
            $res = $mbd->prepare("DELETE FROM atributo_producto WHERE id = ?");
            $res->bindParam(1,$id); 
            $res->execute();

            $row = $res->rowCount(); // se pregunta si hubo una file (row) afectada

            if($row){
                $_SESSION['success'] = 'El atributo de se eliminado correctamente';
                header('Location: ../productos/show.php?id=' . $atrib_prod['producto_id']);
            }else {
                $_SESSION['danger'] = 'El atributo no existe';
                header('Location: ../productos/show.php?id=' . $atrib_prod['producto_id']);
            }   
        }
    }
}else{
    echo "<script>
    alert('Accesso indebido');
    windows.location='../'
    </script>";
}


