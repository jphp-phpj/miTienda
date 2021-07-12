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

    $res = $mbd->prepare("SELECT p.id ,p.sku, p.nombre, p.precio, m.nombre as marca, pt.nombre as produc
    FROM productos as p 
    INNER JOIN marcas as m 
    ON p.marca_id = m.id
    INNER JOIN producto_tipos as pt 
    ON p.producto_tipo_id = pt.id 
    WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();

    //lista de atributos
    $res = $mbd->prepare("SELECT ap.id, a.nombre, ap.valor 
    FROM atributos a 
    INNER JOIN atributo_producto ap 
    ON a.id = ap.atributo_id 
    WHERE ap.producto_id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atributos = $res->fetchall();

    //lista de imagenes
    $res = $mbd->prepare("SELECT id,imagen 
    FROM imagenes 
    WHERE producto_id = ?
    AND activo = 1");
    $res->bindParam(1, $id);
    $res->execute();
    $imagenes = $res->fetchall();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        if (isset($_SESSION['autenticado'])) {
            $prod = (int) $_POST['product'];
            $cantidad = filter_var($_POST['cantidad'],FILTER_VALIDATE_INT); 
            
            if (!$cantidad || $cantidad <= 0) {
                $msg = 'Ingrese cantidad';
            }else{
                // verficar que usuario no tenga ya cotizado el mismo producto
                $res = $mbd->prepare("SELECT id FROM carro_compras WHERE producto_id = ? AND usuario_id = ?");
                $res->bindParam(1, $prod);
                $res->bindParam(2, $_SESSION['usuario_id']);
                $res->execute();

                $compra = $res->fetch();

                if ($compra) {
                    $msg = 'Este producto ya ha sido cotizado';
                }else {
                    //estado 1 = pendiente / 2 = 
                    $res = $mbd->prepare("INSERT INTO carro_compras(producto_id, usuario_id, cantidad, estado, created_at, updated_at) 
                    VALUES(?,?,?,1,now(),now() ) ");
                    $res->bindParam(1, $prod);
                    $res->bindParam(2, $_SESSION['usuario_id']);
                    $res->bindParam(3, $cantidad);
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'Su producto se ha agregado correctamente';
                        header('Location: ' . BASE_URL);
                    }
                }

            }    
        }else{
            $msg = 'Debe iniciar sesion o registrarse';
        }
    }
 }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizar</title>
    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>

    <style>
        .zoom:hover{
            -webkit-transform:scale(1.3);
            transform:scale(2.3);
            transition-duration: .5s;
        }
        .zoom {
            overflow:hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- seccion de cabecera del sitio -->
        <header>
            <!-- navegador principal -->
            <?php include('../partial/menu.php'); ?>
        </header>

        <div class="col-md-12 offset-md-1">
            <h2 class="text-center mt-3 text-primary">Cotizar</h2>
            <!-- mensaje de registro de roles -->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <div class="alert alert-success">
                    El producto se ha modificado correctamente
                </div>
            <?php endif; ?>

                <!-- listar los roles que estan registrados -->
            <?php if($producto): ?>
                <div class="row">
                    <div class="col-md-6">  

                        <div class="row">
                            <?php foreach($imagenes as $imagen):?>
                                <div class="col-md-4">
                                    <img src="<?php echo PRODUCTOS . 'img/' . $imagen['imagen'] ?>" alt="" class="img-thumbnail zoom" >
                                    
                                </div>
                            <?php endforeach;?>
                        </div>
                  
                    </div>
                        <!-- Seccion de muestra de datos -->
                    <div class="col-md-6">
                        <table class="table table-hover">
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
                                <th>Tipo de Producto:</th>
                                <td><?php echo $producto['produc']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="col-md-12">

                    <?php if(isset($msg)): ?>
                    <p class="alert alert-danger"><?php echo $msg; ?></p>
                    <?php endif;?>
                
                    <form action="" method="post"class="form-inline">
                        <div class="form-group mb-2">
                            <input type="hidden" name="product" value="<?php echo $producto['id']; ?>">
                            <input type="hidden" name="confirm" value="1">
                            <input type="number" name="cantidad" value="1">
                            <button type="submit" class="btn btn-success btn-sm">Agregar</button>
                        </div>
                    </form>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-link">Volver</a>
                </div>
                   <!-- formulario de compras -->
                   <form action="" method="post"></form>
            <?php else: ?>
                <p class="text-info">El dato solicitado no existe</p>
            <?php endif; ?>
        </div>
        <hr>
        <div class="col-md-4 offset-md-2">
            <h2 class="text-center mt-3 text-primary">Ficha Técnica</h2>
            <table class="table table-hover">
                <hr>
                <?php foreach($atributos as $atributo): ?>
                    <tr>
                        <td><?php echo $atributo['nombre'];?></td>
                        <td><?php echo $atributo['valor'];?></td>
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
