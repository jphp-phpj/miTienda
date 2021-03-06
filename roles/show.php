<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

session_start();

// validar variable GET id
if (isset($_GET['id'])) {

    //recuperar el dato que viene de la variable
    $id = (int) $_GET['id'];
    // print_r($id);exit;
    // consultar si hay un ID con el id enviado por GET
    $res =$mbd->prepare("SELECT id, nombre, created_at, updated_at FROM roles WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $rol =$res->fetch();
    // print_r($rol);exit;
}
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>

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
            <h1>Rol Info</h1>

            <!--- Lista de roles  --->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <div class="alert alert-success">
                    El registro esta correcto
                </div>
            <?php endif; ?>

                        <!--listar los roles que estan registrados   -->
            <?php if($rol): ?>
            <table class="table table-hover">
                <tr>
                    <th>ID:</th>
                    <td><?php echo $rol['id'] ?></td>
                </tr>
                <tr>
                    <th>Rol:</th>
                    <td><?php echo $rol['nombre'] ?></td>
                </tr>
                <tr>
                    <th>Created:</th>
                    <td>
                        <?php 
                            $fecha = new DateTime($rol['created_at']);
                            echo $fecha->format('d-m-Y H:i:s');
                        ?>        
                    </td>
                </tr>
                <tr>
                    <th>Updated:</th>
                    <td>
                        <?php 
                            $fecha = new DateTime($rol['updated_at']);
                            echo $fecha->format('d-m-Y H:i:s');
                        ?>  
                    </tr>
            </table>
                <!-- botones  -->
            <p>
                <a href="index.php" class="btn btn-light">Volver</a>
                <a href="edit.php?id=<?php echo $rol['id'] ?> " class="btn btn-primary">Editar</a>
                <a href="delete.php?id=<?php echo $rol['id'] ?> " class="btn btn-warning">Eliminar</a>
            </p>

            <?php else: ?>
                <p class="text-info"> El dato solicitado no existe</p>
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