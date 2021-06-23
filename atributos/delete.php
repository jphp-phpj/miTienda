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

        $res = $mbd->prepare("SELECT id FROM atributos WHERE id = ?"); // dont forget to close ()s []s {}s ""s ''s ``s......
        $res->bindParam(1, $id); // sanitizar antes de ejecutar
        $res->execute(); // ejecutar consulta
        $atributo = $res->fetch(); // recuramos la fila si existe

        // validamos la existencia de la marca que se dease eliminar
        
        if ($atributo) {
            // procedemos a elimnar 
            $res = $mbd->prepare("DELETE FROM atributos WHERE id = ?");
            $res->bindParam(1,$id); 
            $res->execute();

            $row = $res->rowCount(); 

            if($row){
                $_SESSION['success'] = 'ok';
                header('Location: index.php');
            }else {
                $_SESSION['danger'] = 'error';
                header('Location: index.php');
            }   
        }
    }
}else{
    echo "<script>
    alert('Accesso indebido');
    windows.location='../'
    </script>";
}


