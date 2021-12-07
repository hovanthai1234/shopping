<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<?php
    require_once "database.php";
    require_once "include/masterpage.php";
    if(isset($_GET['clear'])) {
        session_destroy();
        unset($_SESSION['cart']);
    }

    if(isset($_POST['updateItem'])) {
        $quantity = $_POST['itemQuantity'][$_POST['updateItem']];
        updateCartItem($_POST['updateItem'], $quantity);
        echo '<script>alert("Success");</script>';
    }
    if(isset($_POST['deleteItem'])) {
        deleteCartItem($_POST['deleteItem']);
        echo '<script>alert("Delete success");</script>';
    }
    myHeader("images","");
    showCart();
    myFooter();
?>