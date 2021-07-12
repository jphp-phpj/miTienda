<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');
// validar variable GET id
if (isset($_GET['id'])) {

    //recuperar el dato que viene de la variable
    $id = (int) $_GET['id'];

    // print_r($id);exit;

    // consultar si hay un ID con el id enviado por GET

    $res =$mbd->prepare("SELECT id, nombre FROM atributos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atributo = $res->fetch();
}

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atributos</title>

    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    
    <!-- link indica que archivos utilizar y script indica que script utilizar -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>    
</head>
<body>
    <header>
        <!-- llamada a navegador del sitio -->
        <?php include('../partial/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Atributos</h2>
            <?php include('../partial/mensajes.php'); ?>
            <!-- validar que el atributo existe     -->
            <?php if($atributo): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Id:</th>
                        <td> <?php echo $atributo['id']; ?>  </td>
                    </tr>
                    <tr>
                        <th>Atributo:</th>
                        <td> <?php echo $atributo['nombre']; ?>  </td>
                    </tr>
                </table>
                <p>
                    <a href="index.php" class="btn btn-link">Volver</a>
                    <a href="edit.php?id=<?php echo $atributo['id']; ?>" class="btn btn-primary">Editar</a>
                </p>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>
    </div>
</body></html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>
