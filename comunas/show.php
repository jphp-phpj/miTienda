<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//llamada al archivo conexion para disponer de los datos de la base de datos.
require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];
    //creamos la consulta a la tabla comuna de acuerdo al id ingresado por el sistema
    $res = $mbd->prepare("SELECT c.id, c.nombre as comuna, c.created_at, c.updated_at, r.nombre as region 
    FROM comunas as c INNER JOIN regiones as r ON c.region_id = r.id WHERE c.id = ?");
    $res->bindParam(1,$id);
    $res->execute();
    $comuna = $res->fetch();  // pido a PDO que disponibilice todas las comunas registradas por id
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunas</title>

    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    
    <!-- link indica que archivos utilizar y script indica que script utilizar -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>    
</head>
<body>
    <div class="container">
        <!-- seccion de cabecera del sitio -->
        <header>
            <!-- navegador principal -->
            <?php include('../partial/menu.php'); ?>
        </header>

        <!-- seccion de contenido principal -->
        <section>
            <div class="col-md-6 offset-md-3">
                <h1>Comunas</h1>
                <!-- mensaje de registro de Comunas -->
                <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                    <div class="alert alert-success">
                        La Comuna se ha modificado correctamente
                    </div>
                <?php endif; ?>
                
                <?php if($comuna): ?>
                    <table class="table table-hover">
                        <tr>
                            <th>Comuna:</th>
                            <td> <?php echo $comuna['comuna']; ?> </td>
                        </tr>
                        <tr>
                            <th>Región:</th>
                            <td> <?php echo $comuna['region']; ?> </td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td> 
                                <?php
                                    $created = new DateTime($comuna['created_at']);
                                    echo $created->format('d-m-Y H:i:s'); 
                                ?> 
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td> 
                                <?php
                                    $updated = new DateTime($comuna['updated_at']);
                                    echo $updated->format('d-m-Y H:i:s'); 
                                ?> 
                            </td>
                        </tr> 
                    </table>
                    <p>
                        <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary">Editar</a>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </p>
                <?php else: ?>
                    <p class="text-info">El dato no existe</p>
                <?php endif; ?>
            </div>
            
        </section>
        <!-- pie de pagina -->
        <footer>
            footer
        </footer>
    </div>
</body>
</html>

