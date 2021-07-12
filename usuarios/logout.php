<?php
    ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    require('../class/conexion.php');
    require('../class/rutas.php');

    if(isset($_SESSION['autenticado'])){
        // consultamos por productos en carro de compras
        $res = $mbd->prepare("SELECT id FROM carro_compras WHERE usuario_id = ?");
        $res->bindParam(1, $_SESSION['usuario_id']);
        $res->execute();

        $compra = $res->fetchall();

        if ($compra) {
            //eliminar compras registradas
            $res = $mbd->prepare("DELETE FROM carro_compras WHERE usuario_id = ?");
            $res->bindParam(1, $_SESSION['usuario_id']);
            $res->execute();
    
        }


        session_destroy();
        header('Location: ../index.php');
    }else{
        echo "<script>
            alert('Debe iniciar sesión para continuar');
            window.location = 'http://localhost/miTienda/';
        </script>";
    }

