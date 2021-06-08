<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

$res = $mbd->query('SELECT id, nombre FROM productos ORDER BY nombre');
$productos = $res->fetch();



//validar que los datos del formulario lleguen via post
if (isset($_POST['confirm']) && $_POST['confirm'] == 1 ) {
    # code...
    #print_r($_POST);
    $titulo = trim(strip_tags($_POST['titulo']));
    $imagen = $_FILES['imagen']['name'];
    $dir_tmp = $_FILES['imagen']['tmp_name'];
    $descripcion = trim(strip_tags($_POST['descripcion']));
    $producto = (int) $_POST['producto'];

    if (strlen($titulo) < 5){
        $msg = ' Ingrese el titulo de al menos 5 caracteres';
    }elseif (strlen($descripcion) < 10){
        $msg = 'Ingrese una descripción de al menos 10 caracteres';
    }elseif ($producto <= 0 ){
        $msg = 'Seleccione un Producto';
    }elseif (!$imagen) {
        $msg = 'Ingrese una Imagen';
    }elseif ($_FILES['imagen']['type'] != 'image/jpeg'){
        $msg = 'La imagen no es valida';
    }elseif ($_FILES['imagen']['size'] > 10000){
        $msg = 'La imagen excede el tamaño maximo';
    
    if (!$nombre){
    #echo 'Debe ingresar el nombre de la imagenes';   // no se ve bien y no utilizar
        $msg = 'Debe ingresar el nombre de la imagenes';
    }else {
        // creamos la ruta de descarga de la imagen en el servidor
        

        // comprobamos que la imagen se ha subido al servidor

        // validamos una consulta con opciones de sanitizacion de datos


        // validamos por cada signo de ? el dato que intentamos enviar a la base de datos 

        // se ejecuta la consulta de insercion de datos 

        // pregunta si hubo regristros ingresados

        // preguntar si hubo registros ingresados
        


            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Imagen</title>
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
                <h1>Nueva Imagen</h1>

                <!---mensaje de validacion y errores ---> 
                <?php if(isset($msg)):  ?> 
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>
                    <!-- formulario - recibe input de usuario y se lo otorga a variable name="" -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="">Titulo<span class="text-danger">*</span></label> 
                        <input type="text" name="titulo" class="form-control" placeholder="Ingrese el titulo de la imagen"
                        value="<?php if(isset($_POST['descripcion'])) echo $_POST['descripcion']; ?>">
                    </div>                      
                    <div class="form-group mb-3">
                        <label for="">Descripción<span class="text-danger">*</span></label> 
                        <textarea name="descripcion" class="form-control" rows="4"
                        placeholder="Ingrese descripcion de la imagen"></textarea>
                    </div>        
                    <div class="form-group mb-3">
                        <label for="">Producto<span class="text-danger">*</span></label> 
                        <select name="producto" class="form-control">
                            <option value="Seleccione...">
                                <?php foreach($productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>">
                                        <?php echo $producto['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </option>
                        </select>
                    </div>
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
        <h2>-- here goes the footer --</h2>
        </footer>
    </div>
</body>
</html>