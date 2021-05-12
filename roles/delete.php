<?php
require('../class/conexion.php');
require('../class/rutas.php');
if(isset($_GET['id'])) {

    $id = (int) $_GET['id'];   // guardar variable id que viene por GET

    $res = $mbd->prepare("SELECT id FROM roles WHERE id = ?"); // dont forget to close ()s []s {}s ""s ''s ``s......
    $res->bindParam(1, $id); // sanitizar antes de ejecutar
    $res->execute(); // ejecutar consulta
    $rol = $res->fetch(); // recuramos la fila si existe

    // validamos la existencia del reol que se dease eliminar
    if ($rol) {
        // procedemos a elimnar 
        $res = $mbd->prepare("DELETE FROM roles WHERE id = ?");
        $res->bindParam(1,$id); 
        $res->execute();

        $row = $res->rowCount(); 

        if($row){
            $msg = 'ok';
            header('Location: index.php?e=' . $msg);
        }else {
            $error = 'error';
            header('Location: index.php?e=' . $error);
        }   
    }
}

