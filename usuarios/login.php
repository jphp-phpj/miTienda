<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_POST['confirm']) && $_POST['confirm'] == 1){

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // validar que el email es valido (obvio......)
    $clave = trim(strip_tags($_POST['clave']));

    if (!$email) {
        $msg = 'Ingrese su E-mail';

    }elseif(!$email){
        $msg = 'Ingrese su Password';

    }else{
        $clave = sha1($clave);

        // consultamos por el email y password ingresados
        //estan en 2 tablas distintas
        $res = $mbd->prepare("SELECT u.id, p.nombre, r.nombre as rol  FROM usuarios as u INNER JOIN personas as p ON u.persona_id = p.id 
        INNER JOIN roles as r ON p.rol_id = r.id WHERE p.email = ? AND u.clave = ? AND u.activo = 1");
        $res->bindParam(1, $email);
        $res->bindParam(2, $clave);
        $res->execute();

        $usuario = $res->fetch();

        if (!$usuario) {

            $msg = 'El e-mail o password no estan registrados';

        }else{
            //iniciamos la session del usuario a traves de variable de session 
            $_SESSION['autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            header('LOCATION: ../index.php');
        }
    }
}

?>

<?php if(!isset($_SESSION['autenticado'])): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
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
                        <h3>Iniciar Session</h3>

                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">E-mail<span class="text-danger">*</span></label> 
                            <input type="email" name="email" class="form-control" placeholder="Ingrese su E-mail">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Password<span class="text-danger">*</span></label> 
                            <input type="password" name="clave" class="form-control" placeholder="Ingrese su Password">
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
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
<?php else: ?>
    <script>
        alert('Debe cerrar sesion para volver a ingresar');
        window.location = "<?php echo BASE_URL; ?>"
    </script>
<?php endif; ?>