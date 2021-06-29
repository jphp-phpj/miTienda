<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../class/conexion.php');
require('../class/rutas.php');

session_start();

// lista de roles
$res = $mbd->query("SELECT id, nombre FROM marcas ORDER BY nombre");
$marcas = $res->fetchall();

// lista de producto
$res = $mbd->query("SELECT id, nombre FROM producto_tipos ORDER BY nombre");
$producto = $res->fetchall(); 

// validar formulario
if (isset($_POST['confirm']) && $_POST['confirm'] == 1) { 

    $sku = trim(strip_tags($_POST['sku']));
    $nombre = trim(strip_tags($_POST['nombre']));
    $precio = (int) $_POST['precio'];
    $marca = trim(strip_tags($_POST['marca']));
    $prod = trim(strip_tags($_POST['prod']));

    $res = $mbd->prepare("INSERT INTO productos VALUES(null, ?, ?, ?, 1, ?, ? ,now(), now())");
    $res->bindParam(1,$sku);
    $res->bindParam(2,$nombre);
    $res->bindParam(3,$precio);
    $res->bindParam(4,$marca);
    $res->bindParam(5,$prod);
    $res->execute();

    // print_r($res);exit;
        // chequea que exita el ROW recien creado.
    $row = $res->rowCount();

    if($row){
        $_SESSION['success'] = 'ok';
        header('Location: index.php');
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
    <title>Productos</title>
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
                <h1>Nuevo Producto</h1>

                <!---mensaje de validacion y errores ---> 
                <?php if(isset($msg)):  ?> 
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="">Sku<span class="text-danger">*</span></label> 
                        <input type="text" name="sku" value="<?php if(isset($_POST['sku'])) echo $_POST['sku']; ?>" 
                            class="form-control" placeholder="Ingrese el Sku del Producto">
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Nombre<span class="text-danger">*</span></label> 
                        <input type="text" name="nombre" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>" 
                            class="form-control" placeholder="Ingrese el Nombre del Producto">
                    </div>


                    <div class="form-group mb-3">
                        <label for="">Precio<span class="text-danger">*</span></label> 
                        <input type="number" name="precio" value="<?php if(isset($_POST['precio'])) echo $_POST['precio']; ?>" 
                            class="form-control" placeholder="Ingrese el Precio">
                    </div>


                <!--  here goes the drop down menu -->
                    <div class="form-group mb-3">
                        <label for="">Marca<span class="text-danger">*</span></label> 
                        <select name="marca" class="form-control">
                            <option value="">Seleccione...</option>

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
                            <option value="">Seleccione...</option>

                            <?php foreach($producto as $prod): ?>
                                <option value="<?php echo $prod['id']; ?>">
                                <?php echo $prod['nombre']; ?>
                                </option>
                                    
                            <?php endforeach; ?>

                        </select>
                    </div> 

                <!-- and here the drop down menu ends -->

                    <div class="form-group mb-3">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </div>
                </form>
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
        alert('Acceso Indebido');
        window.location = "../index.php";
    </script>
<?php endif; ?>