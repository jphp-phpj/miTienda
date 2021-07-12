<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id'])) {
        
    $id = (int) $_GET['id']; 

    //preguntamos si existe el id enviado via GET en regiones
    $res = $mbd->prepare("SELECT i.id, i.titulo, i.imagen, i.descripcion, i.activo, i.portada, i.producto_id, i.created_at, i.updated_at, p.nombre as producto, m.nombre as marca 
    FROM imagenes as i INNER JOIN productos as p ON i.producto_id = p.id INNER JOIN marcas as m ON p.marca_id = m.id WHERE i.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $imagen = $res->fetch();
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
    <div class="container">
        <!-- Header de contenido principal -->
        <header>
            <?php include('../partial/menu.php')  ?>
        </header>

        <!-- seccion de contenido principal -->
        
        <div class="container">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center mt-3 text-primary">Imagenes</h2>
                <!-- generacion de mensaje de exito -->
                <?php include('../partial/mensajes.php'); ?>

                <!-- validar que la region existe     -->
                <?php if($imagen): ?>
                    
                    <div class="col-m-4">
                        <img src="<?php echo PRODUCTOS . 'img/' . $imagen['imagen']; ?>" alt="" class="img-fluid">
                    </div>

                    <table class="table table-hover">
                        <tr>
                            <th>Título:</th>
                            <td> <?php echo $imagen['titulo']; ?>  </td>
                        </tr>
                        <tr>
                            <th>Activo:</th>
                            <td>
                                <?php if($imagen['activo'] == 1): ?>
                                    Si
                                <?php else: ?>
                                    No
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Portada:</th>
                            <td>
                                <?php if($imagen['portada'] == 1): ?>
                                    Si
                                <?php else: ?>
                                    No
                                <?php endif; ?>
                                <a href="editPortada.php?id=<?php echo $id; ?>" class="btn btn-link btn-sm">Cambiar</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td><?php echo $imagen['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td><?php echo $imagen['producto']; ?></td>
                        </tr>
                        <tr>
                            <th>Marca:</th>
                            <td><?php echo $imagen['marca']; ?></td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td> 
                                <?php 
                                    //transformamos la fecha de imagenes en una fecha valida para php
                                    $fecha = new DateTime($imagen['created_at']);
                                    echo $fecha->format('d-m-Y H:i:s'); 
                                ?>  
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td> 
                                <?php 
                                    //transformamos la fecha de imagenes en una fecha valida para php
                                    $fecha = new DateTime($imagen['updated_at']);
                                    echo $fecha->format('d-m-Y H:i:s'); 
                                ?>  
                            </td>
                        </tr>
                    </table>
                    <p>
                        <a href="../productos/showImages.php?id_producto=<?php echo $imagen['producto_id']; ?>" 
                            class="btn btn-link">Volver</a>
                        <?php if($_SESSION['usuario_rol'] == 3): ?>
                            <a href="edit.php?id=<?php echo $imagen['id']; ?>" class="btn btn-primary">Editar</a>
                            
                            <form action="delete.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="confirm" value="1">
                                <button type="submit" class="btn-warning">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p class="text-info">El dato no existe</p>
                <?php endif; ?>
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