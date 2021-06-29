<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id_producto'])){
    $id_producto = (int) $_GET['id_producto'];

    $res = $mbd->prepare("SELECT id, nombre FROM productos WHERE id = ?");
    $res->bindParam(1, $id_producto);
    $res->execute();

    $producto = $res->fetch();

    // lista de atributos
    $res = $mbd->query("SELECT id, nombre FROM atributos ORDER BY nombre");
    $atributos = $res->fetchall();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
        $atributo = filter_var($_POST['atributo'], FILTER_VALIDATE_INT);
        $valor = trim(strip_tags($_POST['valor']));

        if (!$atributo){
            $msg = 'Seleccione un atributo';

        }elseif(!$valor){
            $msg = 'Ingrese un valor';
        }else{
            // validamos que atributo no este en producto ingresado
            $res = $mbd->prepare("SELECT id FROM atributo_producto WHERE atributo_id = ? AND producto_id = ?");
            $res->bindParam(1, $atributo);
            $res->bindParam(2, $id_producto);
            $res->execute();
            $atrib_prod = $res->fetch();

            if ($atrib_prod){
                $msg = 'El atributo seleccionado ya existe en este producto, intente otro';
            }else{
                // registramos el atributo y el producto
                $res = $mbd->prepare("INSERT INTO atributo_producto(atributo_id,producto_id,valor)
                VALUE(?,?,?)");
                $res->bindParam(1,$atributo);
                $res->bindParam(2,$id_producto);
                $res->bindParam(3,$valor);
                $res->execute();

                $row = $res->rowCount();

                if($row){
                    $_SESSION['success'] = 'El atributo se ha registrado correctamente.';
                    header('Location: ' . PRODUCTOS . 'show.php?id=' . $id_producto);
                }
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
    <title>Atributo Producto</title>
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
                <h1>Nuevo Atributo para <?php echo $producto['nombre'];?></h1>

                <!---mensaje de validacion y errores ---> 
                <?php if(isset($msg)):  ?> 
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <?php if($producto): ?>
                    <!-- formulario - recibe input de usuario y se lo otorga a variable name="" -->
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="atributo">Atributo <span class="text-danger">*</span></label>
                            <select name="atributo" class="form-control">
                                <option value="">Seleccione...</option>

                                <?php foreach($atributos as $atributo): ?>
                                    <option value="<?php echo $atributo['id']; ?>">
                                        <?php echo $atributo['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Valor<span class="text-danger">*</span></label> 
                            <input type="text" name="valor" class="form-control" placeholder="Ingrese el Valor">
                        </div>

                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="index.php" class="btn btn-link">Volver</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-info"> EL dato no existe</p>
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
        window.location = "../index.php";
    </script>
<?php endif; ?>