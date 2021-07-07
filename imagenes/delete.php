<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');


if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'){
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $id = (int) $_POST['id'];

        $res = $mbd->prepare("SELECT id, producto_id FROM imagenes WHERE id = ? ");
        $res->bindParam(1, $id);
        $res->execute();

        $imagen = $res->fetch();

        if (isset($imagen)) {
            $res = $mbd->prepare("DELETE FROM imagenes WHERE id = ?");
            $res->bindParam(1, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'la imagen se ha eliminado correctamente';
                header('Location: ../productos/showImages.php?producto_id=' . $imagen['producto_id']);
            }
        } else {
            $_SESSION['danger'] = 'La imagen no se ha podido eliminar... intente nuevamente';
            header('Location: show.php?id=' . $id);
        }
    }
}else{
    echo "<script>
    alert('Accesso indebido');
    windows.location='../'
    </script>";
}

