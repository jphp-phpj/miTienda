<?php
require('../class/conexion.php');
require('../class/rutas.php');

session_start();

if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador') {

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];   // guardar variable id que viene por GET

        $res = $mbd->prepare("SELECT id FROM roles WHERE id = ?"); // dont forget to close ()s []s {}s ""s ''s ``s......
        $res->bindParam(1, $id); // sanitizar antes de ejecutar
        $res->execute(); // ejecutar consulta
        $rol = $res->fetch(); // recuramos la fila si existe

    // validamos la existencia del reol que se dease eliminar
        if ($rol) {
            // verificamos que el rol no tenga una persona asociada
            $res = $mbd->prepare('SELECT ');
            $res->bindParam(1, $id);
            $res->execute();

            $persona = $res->fetch();

            if (!$persona) {
                // procedemos a elimnar
                $res = $mbd->prepare("DELETE FROM roles WHERE id = ?");
                $res->bindParam(1, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El rol se ha eliminado correctamente.';
                    header('Location: index.php');
                }
            } else {
                $_SESSION['danger'] = 'El rol no se ha podido eliminar.';
                header('Location: index.php');
            }
        }
    }
}else {
    echo "<script>  
        alert('Accesso Indebido');
        window.location = 'http://localhost/miTienda/';
    </script>";
}