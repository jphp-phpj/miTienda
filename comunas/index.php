<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');
// creamos la consulta a la table 
$res = $mbd->query("SELECT c.id, c.nombre as comuna, c.region_id, r.nombre as region 
FROM comunas as c INNER JOIN regiones as r ON c.region_id = r.id ORDER BY comuna");
$comunas = $res->fetchall();  // pido a PDO que disponibilice todas las regiones registrados
// print_r($reg);
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunas</title>

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
                <h1>Comunas</h1>
                <!--- mensajes de modificacion y error   --->
                <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                    <div class="alert alert-success">
                        La Comuna fue modificada correctamente
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['e']) && $_GET['e'] == 'ok'): ?>
                    <div class="alert alert-success">
                        La comuna se ha eliminado correctamente
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error']) && $_GET['error'] == 'error'): ?>
                    <div class="alert alert-danger">
                        La comuna no se ha eliminado... intente nuevamente
                    </div>
                <?php endif; ?>

                    <!-- listar tabla de comuna y región -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Comuna</th>
                            <th scope="col">Región</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($comunas as $comuna): ?>
                            <tr>
                                <td>
                                    <a href="show.php?id=<?php echo $comuna['id']; ?>">
                                        <?php echo $comuna['comuna']; ?> 
                                    </a>
                                </td>     
                                <td> <?php echo $comuna['region']; ?> </td>
                            </tr>    
                        <?php endforeach; ?>
                    </tbody>
                </table>
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