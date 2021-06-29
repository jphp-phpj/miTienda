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

    $res = $mbd->prepare("SELECT p.id ,p.activo ,p.sku, p.nombre, p.precio, m.nombre as marca , pt.nombre as produc, p.created_at, p.updated_at 
    FROM productos as p 
    INNER JOIN marcas as m ON p.marca_id = m.id
    INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id 
    WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();

    //lista de atributos
    $res = $mbd->prepare("SELECT ap.id, a.nombre, ap.valor FROM atributos a INNER JOIN atributo_producto ap ON a.id = ap.atributo_id WHERE ap.producto_id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atributos = $res->fetchall();

    //lista de imagenes
    $res = $mbd->prepare("SELECT id FROM imagenes WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $imagenes = $res->fetchall();

 }

?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

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

            <div class="col-md-6 offset-md-3">
                <h2 class="text-center mt-3 text-primary">Productos</h2>
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
                            <td>$<?php echo number_format($producto['precio'],0,',','.'); ?></td>
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
                               <?php if(!empty($producto_act) && $producto['activo'] == 1): ?>
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
                    <p>
                        <a href="index.php" class="btn btn-link">Volver</a>
                        <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary">Editar</a>
                        <a href="../atributo_producto/add.php?id_producto=<?php echo $id; ?>" class="btn btn-secondary">Agregar Atributo</a>
                        <a href="../imagenes/add.php?id_producto=<?php echo $id; ?>" class="btn btn-success">Agregar Imagen</a>
                    </p>
                <?php else: ?>
                    <p class="text-info">El dato solicitado no existe</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center mt-3 text-primary">Atributos de <?php echo $producto['nombre']; ?></h2>
                <table class="table table-hover">
                    <tr>
                        <th>Atributo</th>
                        <th>Valor</th>
                    </tr>
                    <?php foreach($atributos as $atributo): ?>
                        <tr>
                            <td><?php echo $atributo['nombre'];?></td>
                            <td><?php echo $atributo['valor'];?></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                <a href="#" class="btn btn-primary btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
   
    </div>
</body>        
<footer>
    <?php include('../partial/footer.php');  ?>
</footer>
</html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>