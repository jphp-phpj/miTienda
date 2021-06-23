<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

// creamos la consulta a la table 
$res = $mbd->query("SELECT id, nombre FROM marcas ORDER BY nombre");
$marc = $res->fetchall();  // pido a PDO que disponibilice todas las marcas registradas

// print_r($marc);
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas</title>

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
            <h1>Marcas</h1>
            <!--- mensajes de modificacion y error --->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <div class="alert alert-success">
                    La Marca ha sido ingresada correctamente
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['e']) && $_GET['e'] == 'ok'): ?>
                <div class="alert alert-success">
                    La Marca fue eliminada
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error']) && $_GET['error'] == 'error'): ?>
                <div class="alert alert-danger">
                    La Marca no se elimino... intente nuevamente
                </div>
            <?php endif; ?>

            <!-- tabla de las marcas que estan registradas -->
            
            <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Marca</th>
                </tr>
            </thead>
                <tbody>
                    <?php foreach($marc as $mrc): ?>
                        <tr>
                            <td><?php echo $mrc['id'] ?></td>
                            <td>
                                <a href="show.php?id=<?php echo $mrc['id']; ?>"> 
                                    <?php echo $mrc['nombre']; ?>
                                </a>
                            </td>
                        </tr>    
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="add.php"class="btn btn-success">Nueva Marca</a>
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
