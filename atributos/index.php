<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

// creamos la consulta a la table 
$res = $mbd->query("SELECT id, nombre FROM atributos ORDER BY nombre");
$atributos = $res->fetchall();  // pido a PDO que disponibilice todas las marcas registradas

// print_r($atributo);
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
    <!-- Header de contenido principal -->
    <header>
        <?php include('../partial/menu.php')  ?>
    </header>
        <!-- tabla de atributos registrados -->
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Atributos</h2>
            <!-- generacion de mensajes de exito o error -->
            <?php include('../partial/mensajes.php'); ?>

            <table class="table table-hover">
                <tr>
                    <th>Id</th>
                    <th>Atributo</th>
                </tr>
                <?php foreach($atributos as $atributo): ?>
                    <tr>
                        <td> <?php echo $atributo['id']; ?> </td>
                        <td>
                            <a href="show.php?id=<?php echo $atributo['id']; ?>"> <?php echo $atributo['nombre']; ?>  </a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <a href="add.php" class="btn btn-primary">Nuevo Atributo</a>
        </div>
        
    </div>

    <!-- pie de pagina -->
    <footer>
    <?php include('../partial/footer.php');  ?>
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