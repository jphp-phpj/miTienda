<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['persona'])){

    $id_persona = (int) $_GET['persona'];

    /* VALIDAR que la persona exista en la tabla personas */
    $res = $mbd->prepare("SELECT id, nombre FROM personas WHERE id = ?");
    $res->bindParam(1, $id_persona);
    $res->execute();

    $persona = $res->fetch();


    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

        $clave = trim(strip_tags($_POST['clave']));
        $reclave = trim(strip_tags($_POST['reclave']));

        if (!$clave || strlen($clave) < 8 ){
            $msg = 'Ingrese un Password de al menos 8 caracteres';
        }elseif($reclave != $clave){
            $msg = 'El Password no coincide';
        }else{
            // encriptacion de password
            $clave = sha1($clave);
                // registramos usuario con id persona enviado por get
                // activo => 1 inactivo => 0
            $res = $mbd->prepare("INSERT INTO usuarios VALUES(null, ?, 1, ?, now(), now())");
            $res->bindParam( 1, $clave);
            $res->bindParam( 2, $id_persona);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'El Password se ha creado correctamente';
                header('Location: ..personas/show.php?id=' . $id_persona);
            }
        }   
    }

    // print_r($persona);exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario Nuevo</title>
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
                <!---mensaje de validacion y errores ---> 
                <?php if(isset($msg)):  ?> 
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>
                    <!-- formulario - recibe input de usuario y se lo otorga a variable name="" -->
                    <?php if($persona): ?>
                        <h3>Agregando Password a <?php echo $persona['nombre']; ?> </h3>

                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Password<span class="text-danger">*</span></label> 
                            <input type="password" name="clave" class="form-control" placeholder="Ingrese el Password">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Confirmar Password<span class="text-danger">*</span></label> 
                            <input type="password" name="reclave" class="form-control" placeholder="Confirme el Password">
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="../personas/show.php?id=<?php echo $id_persona; ?>" class="btn btn-link">Volver</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-info">Dato no existe</p>
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