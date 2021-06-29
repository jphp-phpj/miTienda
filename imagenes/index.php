<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

// creamos la consulta a la table 
$res = $mbd->query("SELECT i.id, i.titulo, i.imagen, i.activo, i.portada, p.nombre as producto, m.nombre as marca 
                    FROM imagenes as i 
                    INNER JOIN productos as p
                    ON i.producto_id = p.id 
                    INNER JOIN marcas as m
                    ON p.marca_id = m.id");
$imagenes = $res->fetchall();  // pido a PDO que disponibilice todas las Imagenes Productos registradas

// print_r($marc);
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] !='Cliente'): ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imágenes de Productos</title>

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
        <div class="container">
            <h2 class="text-center mt-3 text-primary"> Imágenes Productos</h2>
                <?php include('../partial/mensajes.php'); ?>
            <div class="row">
                <?php  foreach($imagenes as $imagen): ?>
                    <div class="col-md-3">
                        <img src="<?php  echo PRODUCTOS . 'img/' . $imagen['imagen']; ?>" alt="" class="img-thumbnails" style="height:170px">
                        <table class="table table-hover">
                            <tr>
                                <th>Titulo:</th>
                                <td>
                                    <?php  echo $imagen['producto']; ?>
                                </td>
                            </tr>    
                            <tr>
                                <th>Activo:</th>
                                <td>
                                    <?php  if($imagen['activo'] == 1): ?>
                                        Si
                                    <?php else: ?>
                                        No
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Portada:</th>
                                <td>
                                    <?php  if($imagen['portada'] == 1): ?>
                                        Si
                                    <?php else: ?>
                                        No
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Producto</th>
                                <td>
                                    <?php  echo $imagen['producto'];?>
                                </td>
                            </tr>
                            <tr>
                                <th>Marca</th>
                                <td>
                                    <?php  echo $imagen['marca'];?>
                                </td>
                            </tr>
                        </table>
                        <a href="show.php?id=<?php  echo $imagen['id'];?>" class="bnt bnt-link">Ver</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- solo admin puede agregar nueva imagen -->
            <?php if($_SESSION['usuario_rol']=='Administrador'): ?>
                <a href="add.php"class="btn btn-success">Nueva Imagen</a>
            <?php endif; ?>
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
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>