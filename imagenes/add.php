<!-- aca comienza el codigo del servidor -->
<?php
// codigo que permite mostrar errores en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//validar que los datos del formulario vienen via POST

//llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id_producto'])){
    $id_producto = (int) $_GET['id_producto'];

    // se verifica que el producto exista en la tabla productos, con el id_producto
    $res = $mbd->prepare("SELECT id FROM productos WHERE id = ?");
    $res->bindParam(1,$id_producto);
    $res->execute();

    $producto = $res->fetch();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
    
    //almacena el nombre de la region desde el formulario
        $titulo = trim(strip_tags($_POST['titulo']));
        $imagen = $_FILES['imagen']['name'];
        $dir_tmp = $_FILES['imagen']['tmp_name'];
        $descripcion = trim(strip_tags($_POST['descripcion']));
        

        if (strlen($titulo) < 5) {
            $msg = 'Ingrese un título de al menos 5 carateres';
        }elseif (strlen($descripcion) < 10) {
            $msg = 'Ingrese una descripción de al menos 10 caracteres';
        }elseif (!$imagen) {
            $msg = 'Ingrese una imagen';
        }elseif ($_FILES['imagen']['type'] != 'image/jpeg') {
            $msg = 'La imagen no es válida';
        }elseif ($_FILES['imagen']['size'] > 70000) {
            $msg = 'El tamaño de la imagen está excedido';
        }
        else{

            //preguntar si la imagen ingresada existe en la tabla imagenes
            $res = $mbd->prepare("SELECT id FROM imagenes WHERE imagen = ?");
            $res->bindParam(1, $imagen);
            $res->execute();
            $img = $res->fetch();

            if ($img) {
                $msg = 'La imagen ya existe... intente con otra';
            }else{

                //creamos la ruta de guardado de la imagen en el servidor
                $upload = $_SERVER['DOCUMENT_ROOT'] . '/miTienda/productos/img/';
                $img_subida = $upload . basename($_FILES['imagen']['name']);

                //comprobamos que la imagen se ha subido al servidor
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $img_subida)) {
                    //consultar si hay una imagen del producto seleccionada que sea portada
                    $res = $mbd->prepare("SELECT id FROM imagenes WHERE producto_id = ? AND portada = 1");
                    $res->bindParam(1, $producto);
                    $res->execute();

                    $img_portada = $res->fetch();

                    //portada = 1, no portada = 2
                    if ($img_portada) {
                        $portada = 2;
                    }else{
                        $portada = 1;
                    }

                    //generamos una consulta con opciones de sanitizacion de datos
                    $res = $mbd->prepare("INSERT INTO imagenes VALUES(null,?, ?, ?, 2, ?, ?, now(),now() )");
                    //validamos por cada signo de ? el dato que intentamos enviar a la base de datos
                    $res->bindParam(1, $titulo);
                    $res->bindParam(2, $imagen);
                    $res->bindParam(3, $descripcion);
                    $res->bindParam(4, $portada);
                    $res->bindParam(5, $id_roducto);
                    //se ejecuta la consulta de insercion de datos
                    $res->execute();

                    //pregunte si hubo registros ingresados
                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'La imagen se ha registrado correctamente';
                        header('Location: index.php');
                    }
                }

                
            }
        }
    }
}

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>
<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imágenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- llamada a navegador del sitio -->
        <?php include('../partial/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center mt-3 text-primary">Nueva Imagen</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <?php if($producto): ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="titulo">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" value="<?php if(isset($_POST['titulo'])) echo $_POST['titulo']; ?>" class="form-control" placeholder="Ingrese el título de la imagen">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control text-left" rows="4" placeholder="Ingrese la descripción de la imagen">
                            <?php if(isset($_POST['descripcion'])) echo $_POST['descripcion']; ?>
                        </textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="imagen">Imagen <span class="text-danger">*</span></label>
                        <input type="file" name="imagen" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </div>
                </form>
            <?php else:?>
                <p class="text-info"> El dato no existe</p>
            <?php endif;?>
        </div>
    </div>
    

</body>
</html>
<?php else: ?>
    <script>
        alert('Acceso Indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>