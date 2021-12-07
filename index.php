<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<?php
    require_once "database.php";
    require_once "include/masterpage.php";
    
    myHeader("images","");
    $s = 'select * from category';
    if(isset($_GET['category_id']))$s .= " where category_id = '".$_GET['category_id']."'";
    
    echo getData($s);
    myFooter();
?>
        