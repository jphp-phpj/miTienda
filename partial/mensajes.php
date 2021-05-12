<?php if (isset($_SESSION['success'])): ?>
    <p class="alert alert-success">
        <?php echo $_SESSION['success']; 

            echo $_SESSION['success'];

            unset($_SESSION['success']);

        ?>
    </p>
<?php endif; ?>

<?php if (isset($_SESSION['danger'])): ?>
    <p class="alert alert-danger">
        <?php echo $_SESSION['danger']; 
            // imprime el valor de la variable de session danger
            echo $_SESSION['danger'];
            // elimina 
            unset($_SESSION['danger']);

        ?>
    </p>
<?php endif; ?>