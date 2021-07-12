<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');


//consultar por los productos cotizados por el usuario
$res = $mbd->prepare("SELECT cc.id, cc.cantidad, p.nombre as producto, p.precio 
FROM carro_compras cc
INNER JOIN productos p
ON cc.producto_id = p.id
WHERE cc.usuario_id = ?");
$res->bindParam(1, $_SESSION['usuario_id']);
$res->execute();

$compras = $res->fetchall();

// if (count($compras)) {
//     $_SESSION['count'] = count($compras);
// }else {
//     $_SESSION['count'] = 0;
// }

$count = 0;
$total = 0;

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carro de Compras</title>

    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    
    <!-- link indica que archivos utilizar y script indica que script utilizar -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>    
</head>
<body>
    <header>
        <!-- llamada a navegador del sitio -->
        <?php include('../partial/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Carro de Compra</h2>
            <?php include('../partial/mensajes.php'); ?>
            <!-- validar que el atributo existe     -->
            <?php if($compras): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>A Pagar</th>
                    </tr>
                    <?php foreach($compras as $compra): ?> 
                        <tr>
                            <td> <?php echo $compra['producto']; ?></td>
                            <td class="text-center"> <?php echo $compra['cantidad']; ?></td>
                            <td class="text-end"> <?php echo '$ ' . number_format($compra['precio'],0,',','.'); ?></td>
                            <td class="text-end"> 
                                <?php 
                                    $pago = $compra['cantidad'] * $compra['precio'];
                                    $count = $count + $compra['cantidad'];
                                    $total = $total + $pago;
                                    echo '$ ' . number_format($pago,0,',','.');
                                ?>
                            </td>
                            <td><a href="<?php echo CARRO_COMPRAS . 'delete.php?id=' . $compra['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td colspan="3" class="text-center strong">Total</td>
                        <td class="text-end">
                        <?php 
                            $_SESSION['count'] = $count;
                            echo '$ ' . number_format($total,0,',','.'); 
                        ?>
                        </td>
                    </tr>
                </table>
                <p>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-link">Seguir Comprando</a>
                    <a href="#"class="btn btn-success">Pagar</a>
                    <a href="#" class="btn btn-primary">Cancelar</a>
                </p>
            <?php else: ?>
                <p class="text-info">Su carro esta vacio</p>
                 <a href="<?php echo BASE_URL; ?>" class="btn btn-link">Agregar Productos</a>
            <?php endif; ?>
        </div>
    </div>
</body></html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>
