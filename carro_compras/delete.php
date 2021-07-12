<?php

ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if(isset($_SESSION['autenticado'])){
    if(isset($_GET['id'])) {

        $id = (int) $_GET['id'];   // guardar variable id que viene por GET

        $res = $mbd->prepare("SELECT id, usuario_id FROM carro_compras WHERE id = ?"); // dont forget to close ()s []s {}s ""s ''s ``s......
        $res->bindParam(1, $id); // sanitizar antes de ejecutar
        $res->execute(); // ejecutar consulta
        $carro = $res->fetch(); // recuramos la fila si existe

        // validamos la existencia de la marca que se dease eliminar
        
        if ($carro) {
            // procedemos a elimnar 
            $res = $mbd->prepare("DELETE FROM carro_compras WHERE id = ?");
            $res->bindParam(1,$id); 
            $res->execute();

            $row = $res->rowCount(); 

            if($row){
                $_SESSION['success'] = 'El producto se ha eliminado';
                header('Location: ' . CARRO_COMPRAS . 'show.php');
            }else {
                $_SESSION['danger'] = 'El producto no se pudo eliminar';
                header('Location: ' . CARRO_COMPRAS . 'show.php');
            }   
        }
    }
}else{
    echo "<script>
    alert('Accesso indebido');
    windows.location='../'
    </script>";
}
