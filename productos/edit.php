<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../class/conexion.php');
require('../class/rutas.php');

session_start();

// lista de marcas
$res = $mbd->query("SELECT id, nombre FROM marcas ORDER BY nombre");
$marcas = $res->fetchall();

// lista de  tipo de producto
$res = $mbd->query("SELECT id, nombre FROM producto_tipos ORDER BY nombre");
$productoTipos = $res->fetchall(); 


if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $res = $mbd->prepare("SELECT p.id, p.sku, p.nombre, p.precio, p.activo, p.marca_id, p.producto_tipo_id, m.nombre as marca , pt.nombre as produc
                    FROM productos as p 
                    INNER JOIN marcas as m ON p.marca_id = m.id
                    INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id
                    WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();

    // validar formulario
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

        $activo = (int) $_POST['activo'];

        if ($activo <= 0 ) {
            $msg = 'Seleccione una opción un estado';
        }else{
                // actualizamos el usuario con id persona enviado via get
                // activo => 1 inactivo => 2
            $res = $mbd->prepare("UPDATE productos SET activo = ? , updated_at = now() WHERE id = ?");
            $res->bindParam( 1, $activo);
            $res->bindParam( 2, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'Estado modificado correctamente';
                header('Location: ../productos/show.php?id=' . $producto['id']);
            }
        } 

        $sku = trim(strip_tags($_POST['sku']));
        $nombre = trim(strip_tags($_POST['nombre']));
        $precio = (int) $_POST['precio'];
        $marca = trim(strip_tags($_POST['marca']));
        $prod = trim(strip_tags($_POST['prod']));

        if ($sku <= 0) {
            $msg = 'Ingrese un SKU correcto';
        }elseif(!$nombre || strlen($nombre) < 4){
            $msg = 'Ingrese un nombre es válido';
        }elseif ($precio <= 0) {
            $msg = 'Ingrese un precio válido';
        }elseif (!$marca) {
            $msg = 'Ingrese la marca';
        }elseif (!$prod) {
            $msg = 'Seleccione un Producto';
        }else {
            $res = $mbd->prepare("UPDATE productos
            SET sku = ?, nombre = ?, precio = ?, marca_id = ?, producto_tipo_id = ?, updated_at = now() 
            WHERE id = ?");
            $res->bindParam(1, $sku);
            $res->bindParam(2, $nombre);
            $res->bindParam(3, $precio);
            $res->bindParam(4, $marca);
            $res->bindParam(5, $prod);
            $res->bindParam(6, $id);
            $res->execute();

            // print_r($res);exit;
            // chequea que exita el ROW recien creado.
            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'ok';
                header('Location: index.php');
            }
        }
    }
}
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] =='Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
    
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
                <h1>Editar Producto</h1>

                <!---mensaje de validacion y errores ---> 
                <?php if(isset($msg)):  ?> 
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <?php if($producto): ?>
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Sku<span class="text-danger">*</span></label> 
                            <input type="text" name="sku" value=" <?php echo $producto['sku']  ?>" 
                                class="form-control" placeholder="Ingrese el Sku del Producto">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombre<span class="text-danger">*</span></label> 
                            <input type="text" name="nombre" value="<?php echo $producto['nombre'] ?>" 
                                class="form-control" placeholder="Ingrese el Nombre del Producto">
                        </div>


                        <div class="form-group mb-3">
                            <label for="">Precio<span class="text-danger">*</span></label> 
                            <input type="number" name="precio" value="<?php echo $producto['precio']  ?>" 
                                class="form-control" placeholder="Ingrese el Precio">
                        </div>


                        <!--  here goes the drop down menu -->
                        <div class="form-group mb-3">
                            <label for="">Marca<span class="text-danger">*</span></label> 
                            <select name="marca" class="form-control">
                                <option value="<?php echo $producto['marca_id'] ?>"><?php echo $producto['marca'] ?></option>
                                <?php foreach($marcas as $marc): ?>
                                    <option value="<?php echo $marc['id']; ?>">
                                    <?php echo $marc['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>                    
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="">Tipo de Producto<span class="text-danger">*</span></label> 
                            <select name="prod" class="form-control">
                                <option value="<?php echo $producto['producto_tipo_id'] ?>"><?php echo $producto['produc'] ?></option>
                                <?php foreach($productoTipos as $pro): ?>
                                    <option value="<?php echo $pro['id']; ?>">
                                        <?php echo $pro['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div> 

                        <!-- and here the drop down menu ends -->
                    
                        <div class="form-group mb-3">
                            <label for="">Estado<span class="text-danger">*</span></label> 
                            <select name="activo" class="form-control" id="">
                                <option value="<?php echo $producto['activo'] ?> ">
                                <?php if($producto['activo'] == 1): ?>
                                    Activo 
                                <?php else: ?>
                                    Desactivado 
                                <?php endif; ?>
                                </option>
                                <option value="1">Activar</option>
                                <option value="2">Desactivar</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="show.php?id=<?php echo $producto['id']; ?>" class="btn bnt-link">Volver</a>
                        </div>
                    </form>
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
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>