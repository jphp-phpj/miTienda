<?php
ini_set('display_errors', 1); // esto muestra errores, codigo va justo abajo del tag php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// print_r($_SESSION);exit;
require('class/rutas.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
   
    <!--Enlaces CDN de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>    
</head>
<body>
<div class="container">
    <!-- Header de contenido principal -->
    <header>      
        <?php include('partial/menu.php')  ?>
        
    </header>

    <!-- seccion de contenido principal -->
    <section>
        <?php if(isset($_SESSION['autenticado'])): ?>
            <h4>Bienvenido <?php echo $_SESSION['usuario_nombre']; ?></h4>
        <?php endif; ?>
    </section>

        <!-- pie de pagina -->
    <footer>
       <h2>-- here goes the footer --</h2>
    </footer>
</div>
</body>
</html>