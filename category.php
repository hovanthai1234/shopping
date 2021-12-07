<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<?php
    require "database.php";
    session_start();
    $category_error = "";
    if(!isset($_SESSION['tf']))$_SESSION['tf']=true;
    if(!isset($_SESSION['tf1']))$_SESSION['tf1']=true;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['new'])) {
            if($_SESSION['tf1'])$_SESSION['tf'] = false;
            // $_SESSION['tf'] = !$_SESSION['tf'];
        }
        if(isset($_POST['add'])) {
            $_SESSION['tf'] = true;
            if(empty($_POST['category_name'])) {
                $category_error = "Nội dùng chưa có gì";
            }
            $s = "insert into category(category_name) values('".$_POST['category_name']."')";
            if($category_error == "") {
                doSQL($s);
                echo '<script type="text/javascript">alert("insert successful!")</script>';
            }
        }
        if(isset($_POST['choose'])) {
            if($_SESSION['tf']){
                $_SESSION['tf1'] = false;
               echo $_SESSION['category_id'] = $_POST['choose'];
            }
        }

        if(isset($_POST['update'])) {
            $_SESSION['tf1'] = true;
            $category = $_POST['category_name'];
            $id = $_SESSION['category_id'];
            
            $s = "update category set category_name = '$category' where category_id = $id";
            $result = doSQL($s);
            if(!$result) {
                echo 'Failed';
            }
            echo '<script type="text/javascript">alert("Update successful!")</script>';
        }
        
        if(isset($_POST['delete'])) {
            $_SESSION['tf1'] = true;
            $s = "DELETE FROM category WHERE category_id = '".$_SESSION['category_id']."'";
            doSQL($s);
            echo '<script type="text/javascript">alert("Delete successful!")</script>';
        }

        if(isset($_POST['cancel'])) {
            $_SESSION['tf'] = true;
            $_SESSION['tf1'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
   <div style="margin-left: 100px">
   <form action="" method="post">
        <div class="form-group">
            <div>
                Category
                <input type="text" name="category_name" <?php if(!$_SESSION['tf'] || !$_SESSION['tf1']) echo 'abled'; else echo 'disabled';?> 
                value="<?php
                    $sl = "SELECT * FROM category WHERE category_id = '".$_SESSION['category_id']."'";
                    echo readOneRecord($sl);
                ?>"
                autofocus>
                <button type="submit" name="new" class="btn btn-primary" <?php if($_SESSION['tf']) echo 'abled'; else echo 'disabled';?>>...</button>
                <br>
                <small class="text-danger"><?php echo $category_error; ?></small>
               
            </div>
            <div  class="mb-3">
                <h1>Category List</h1>
                <br>
                    <?php
                        $s = 'select * from category';
                        echo loadDatatpTable($s);
                    ?>
            </div>
            
            <div class="mb-3">
                <button type="submit" name="add" class="btn btn-primary" <?php if($_SESSION['tf']) echo 'disabled'; else echo 'abled';?>>Add</button>
                <button type="submit" name="update" class="btn btn-primary" <?php if($_SESSION['tf1']) echo 'disabled'; else echo 'abled';?>>Update</button>
                <button type="submit" name="delete" class="btn btn-primary" <?php if($_SESSION['tf1']) echo 'disabled'; else echo 'abled';?>>Delete</button>
                <button type="submit" name="cancel" class="btn btn-primary">Cancel</button>
                <button type="submit" name="back" class="btn btn-primary">Back</button>
            </div>
        </div>
    </form>
   </div>
</body>
</html>