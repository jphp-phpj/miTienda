<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// print_r($_SESSION);exit;
//llamada al archivo conexion para disponer de los datos de la base de datos
require('../class/conexion.php');
require('../class/rutas.php');

//validar la variable GET id
if (isset($_GET['id_producto'])) {
        
    $id_producto = (int) $_GET['id_producto']; //parsear la variable id a numero entero

    //preguntamos si existe el id enviado via GET en la tabla regiones
    $res = $mbd->prepare("SELECT i.id, i.titulo, i.imagen, i.activo, i.portada, p.nombre FROM imagenes i 
    INNER JOIN productos p ON i.producto_id = p.id WHERE producto_id = ?");
    $res->bindParam(1, $id_producto);
    $res->execute();
    $imagenes = $res->fetchall();

    $res = $mbd->prepare("SELECT id, nombre FROM productos WHERE id = ?");
    $res->bindParam(1, $id_producto);
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
    <title>Imágenes</title>

    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    
    <!-- link indica que archivos utilizar y script indica que script utilizar -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>    
</head>
<body>
    <header>
        <?php include('../partial/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-12">
            <h2 class="text-center mt-3 text-primary">
                Imagenes de 
                <a href="../productos/show.php?id=<?php echo $producto['id']; ?>">
                    <?php echo $producto['nombre']; ?>
                </a>
            </h2>
            <?php include('../partial/mensajes.php'); ?>
            <div class="row">
                <?php if(count($imagenes)): ?>
                    
                    <?php foreach($imagenes as $imagen): ?>
                        <div class="col-md-4">
                            <img src="<?php echo PRODUCTOS . 'img/' . $imagen['imagen']; ?>" alt="" class="img-fluid" width="100%">
                            <p><?php echo $imagen['titulo']; ?></p>
                            <p>
                                <?php if($imagen['activo'] == 1): ?>
                                    Activa
                                <?php else: ?>
                                    Inactiva
                                <?php endif; ?>
                            </p>
                            <p>
                                <?php if($imagen['portada'] == 1): ?>
                                    Portada
                                <?php else: ?>
                                    General
                                <?php endif; ?>
                            </p>
                            <p><a href="../imagenes/show.php?id=<?php echo $imagen['id']; ?>" class="btn btn-link">Ver Detalles</a></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    
                    <p class="text-info">Este producto no tiene imágenes</p>
                
                <?php endif; ?>
            </div>
            
        </div>
        
    </div>
    
</body>
</html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>