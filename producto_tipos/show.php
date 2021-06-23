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

    $res =$mbd->prepare("SELECT id, nombre FROM producto_tipos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();
}

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>

    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    
    <!-- link indica que archivos utilizar y script indica que script utilizar -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>    
</head>
<body>
    <div class="container">
        <!-- Header de contenido principal -->
        <header>
            <?php include('../partial/menu.php')  ?>
        </header>

        <!-- seccion de contenido principal -->
        <section>
        <div class="col-md-6 offset-md-3">
            <h1>Producto Info</h1>

            <!--- Lista de producto_tipos  --->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <div class="alert alert-success">
                    El Producto fue editada correctamente!
                </div>
            <?php endif; ?>

                        <!--listar los producto_tipos registrados   -->
            <?php if($producto): ?>
                <table class="table table-hover">
                    <tr>
                        <th>ID:</th>
                        <td><?php echo $producto['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Producto:</th>
                        <td><?php echo $producto['nombre'] ?></td>
                    </tr>
                    <tr>
 
                </table>
                <p>
                    <a href="index.php" class="btn btn-light">Volver</a>
                    <a href="edit.php?id=<?php echo $producto['id'] ?>" class="btn btn-primary">Editar</a>
                    <a href="delete.php?id=<?php echo $producto['id'] ?>" class="btn btn-warning">Eliminar</a>
                </p>

                <?php else: ?>
                    <p class="text-info">Dato no existe</p>
                <?php endif; ?>

            </div>

        </section>
        <!-- pie de pagina -->
        <footer>
        <h2>-- here goes the footer --</h2>
        </footer>
    </div>
</body>
</html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>
