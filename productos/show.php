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
if (isset($_GET['id'])) {
    
    //recuperar el dato que viene en la variable id
    $id = (int) $_GET['id']; //transforma el dato $_GET a entero

    //print_r($id);exit;

    //consultar si hay una producto con el id enviado por GET
    $res = $mbd->prepare("SELECT p.id ,p.sku, p.nombre, p.precio, m.nombre as marca , pt.nombre as produc, p.created_at, p.updated_at 
    FROM productos as p 
    INNER JOIN marcas as m ON p.marca_id = m.id
    INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id 
    WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();

    // preguntar si producto tiene un producto esta ACTIVO O NO
    $res = $mbd->prepare("SELECT id, activos FROM productos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $producto_act = $res->fetch();

    /* echo '<pre>';
    print_r($producto);exit;
    echo '</pre>'; */

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
    
    <div class="container">
        <!-- seccion de cabecera del sitio -->
        <header>
            <!-- navegador principal -->
            <?php include('../partial/menu.php'); ?>
        </header>

        <!-- seccion de contenido principal -->
        <section>
            <div class="col-md-6 offset-md-3">
                <h1>Productos</h1>
                <!-- mensaje de registro de roles -->
                <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                    <div class="alert alert-success">
                        El producto se ha modificado correctamente
                    </div>
                <?php endif; ?>

                <?php include('../partial/mensajes.php'); ?>



             
                <!-- listar los roles que estan registrados -->
                <?php if($producto): ?>
                    <table class="table table-hover">
                        <tr>
                            <th>Sku:</th>
                            <td><?php echo $producto['sku']; ?></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><?php echo $producto['nombre']; ?></td>
                        </tr>
                        <tr>
                            <th>Precio:</th>
                            <td> 
                                    $
                                        <?php echo $producto['precio']; ?></td>
                        </tr>
                        <tr>
                            <th>Marca:</th>
                            <td><?php echo $producto['marca']; ?></td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td><?php echo $producto['produc']; ?></td>
                        </tr>
      
                        <tr>
                            <th>Estado:</th>
                            <td>    
                                <?php if($producto_act): ?>
                                    <a href="../productos/edit.php?id=<?php echo $producto_act['id'] ?>" class="btn-link bnt-sm">Modificar |</a>
                                <?php endif; ?>

                                <?php if(!empty($producto_act) && $producto_act['activos'] == 1): ?>
                                    Activo 
                                <?php else: ?> 
                                    Inactivo
                                <?php endif; ?>

                              
                            </td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>
                                <?php 
                                    $fecha = new DateTime($producto['created_at']);
                                    echo $fecha->format('d-m-Y H:i:s'); 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>
                                <?php 
                                    $fecha = new DateTime($producto['updated_at']);
                                    echo $fecha->format('d-m-Y H:i:s'); 
                                ?>
                            </td>
                        </tr>
                    </table>

                <?php else: ?>
                    <p class="text-info">El dato solicitado no existe</p>
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
