<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

session_start();

// creamos la consulta a la table 
$res = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
$reg = $res->fetchall();  // pido a PDO que disponibilice todas las regiones registrados
// print_r($reg);
?>

<!--  que usuario esta autorizado para que el codigo corra 40-> Administrador # en tabla de datos (puede variar) -->
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador' ): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regiones</title>

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
            <h1>Regiones</h1>
            <!--- mensajes de modificacion y error   --->
            <?php include('../partial/mensajes.php'); ?>

            <!-- tabla de regiones que estan registrados -->
            <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Rol</th>
                </tr>
            </thead>
                <tbody>
                    <?php foreach($reg as $rg): ?>
                    <tr>
                        <td><?php echo $rg['id'] ?></td>
                        <td>
                            <a href="show.php?id=<?php echo $rg['id']; ?>"> 
                                <?php echo $rg['nombre']; ?>
                            </a>
                        </td>
                    </tr>    
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if($_SESSION['usuario_rol'] != 'Cliente'): ?>
            <a href="add.php"class="btn btn-success">Nueva Regi??n</a>
            <?php endif; ?>
        </div>
        </section>
        <!-- pie de pagina -->
        <footer>
        <?php include('../partial/footer.php');  ?>
        </footer>
    </div>
</body>
</html>

<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>