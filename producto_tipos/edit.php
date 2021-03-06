<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

// validar variable GET id
if (isset($_GET['id'])) {

    //recuperar el dato que viene de la variable
    $id = (int) $_GET['id']; //transforma el dato $_GET a entero
    // consultar si hay un ID con el id enviado por GET
    $res = $mbd->prepare("SELECT id, nombre FROM producto_tipos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto = $res->fetch();

    // validar formulario
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

        $nombre = trim(strip_tags($_POST['nombre']));

        if (!$nombre) {
            $msg = 'Debe ingresar el Tipo de producto';
        } else {
            $res = $mbd->prepare("UPDATE producto_tipos SET nombre = ? WHERE id = ?");
            $res->bindParam(1, $nombre);
            $res->bindParam(2, $id);
            $res->execute();

            $row = $res->rowCount(); // recuperamos el numero de filas afectadas por la consulta

            if ($row) {
                $_SESSION['success'] = 'El tipo de producto se ha correctamente ';
                header('Location: show.php?id=' . $id);
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
    <title>Producto</title>

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
        <section>
            <div class="col-md-6 offset-md-3">
                <h1>Informaci??n de producto</h1>
                <!--- Lista de producto_tipos  --->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-success">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <!-- formulario para editar producto_tipos  -->
                <?php if ($producto) : ?>
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Producto</label> <span class="text-danger">*</span>
                            <input type="text" name="nombre" value="<?php echo $producto['nombre'] ?>" class="form-control" placeholder="Ingrese un producto">
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">GUardar</button>
                            <a href="show.php?id=<?php echo $producto['id'] ?>" class="btn bnt-link">Volver</a>
                        </div>
                    </form>
                <?php else : ?>
                    <p class="text-info">Dato no disponible</p>
                <?php endif; ?>
            </div>

        </section>
        <!-- pie de pagina -->
        <footer>
        <?php include('../partial/footer.php')  ?>
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