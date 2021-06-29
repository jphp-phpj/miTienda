<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id'])){

    $id = (int) $_GET['id'];

    /* VALIDAR que la persona exista en la tabla personas */
    $res = $mbd->prepare("SELECT u.id, u.activo, u.persona_id, p.nombre FROM usuarios as u INNER JOIN personas as p ON u.persona_id = p.id WHERE u.id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $usuario = $res->fetch();

    // print_r($usuario);exit;

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

        $activo = (int) $_POST['activo'];

        if ($activo <= 0 ) {
            $msg = 'Seleccione una opción un estado';
        }else{
                // actualizamos el usuario con id persona enviado via get
                // activo => 1 inactivo => 2
            $res = $mbd->prepare("UPDATE usuarios SET activo = ? , updated_at = now() WHERE id = ?");
            $res->bindParam( 1, $activo);
            $res->bindParam( 2, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'Estado modificado correctamente';
                header('Location: ../personas/show.php?id=' . $usuario['persona_id']);
            }
        }   
    }
}
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Estado</title>
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
                    <?php if($usuario): ?>
                        <h3>Modificando Estado a <?php echo $usuario['nombre']; ?> </h3>

                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Estado<span class="text-danger">*</span></label> 
                            <select name="activo" class="form-control" id="">
                                <option value="<?php echo $usuario['activo'] ?> ">
                                <?php if($usuario['activo'] == 1): ?>
                                    Activo |
                                <?php else: ?>
                                    Inactivo |
                                <?php endif; ?>
                                </option>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="../personas/show.php?id=<?php echo $usuario['persona_id'] ; ?>" class="btn btn-link">Volver</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-info">Dato no existe</p>
                <?php endif; ?>
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
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>