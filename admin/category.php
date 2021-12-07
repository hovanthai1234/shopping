<?php
    require_once "../database.php";
    require_once "../include/masterpage.php";
    session_start();
    $category_error = "";
    if(!isset($_SESSION['tf']))$_SESSION['tf']=true;
    if(!isset($_SESSION['tf1']))$_SESSION['tf1']=true;
    if(!isset($_SESSION['category_id']))$_SESSION['category_id']='';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['new'])) {
            if($_SESSION['tf1'])$_SESSION['tf'] = false;
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
            $_SESSION['category_id']="";
        }
    }

    myHeader("../images","../");
    $xau = ' <div class="container">
    <h1>Category List</h1>
    <form action="" method="post">
    <div class="form-group">
        <div>
            <input type="text" name="category_name"';  if(!$_SESSION['tf'] || !$_SESSION['tf1']) $xau .= 'abled'; else $xau .= 'disabled'; 
            $xau .= 'value=" ';
                $sl = "SELECT * FROM category WHERE category_id = '".$_SESSION['category_id']."'";
                $xau .= readOneRecord($sl);
                $xau .= '"autofocus>
            <button type="submit" name="new" class="btn btn-primary"'; 
            if($_SESSION['tf']) $xau .= 'abled'; else $xau .= 'disabled';
            $xau .= '>...</button>
            <br>
            <small class="text-danger"><?php echo $category_error; ?></small>
           
        </div>
        <div  class="mb-3">
            <br>';
                
                    $s = 'select * from category';
                    $xau .= loadDatatpTable($s);
                    $xau .= '
                
        </div>
        
        <div class="mb-3">
            <button type="submit" name="add" class="btn btn-primary"'; if($_SESSION['tf']) $xau .= 'disabled'; else $xau .= 'abled'; $xau .= '>Add</button>
            <button type="submit" name="update" class="btn btn-primary"'; if($_SESSION['tf1']) $xau .= 'disabled'; else $xau .= 'abled'; $xau .= '>Update</button>
            <button type="submit" name="delete" class="btn btn-primary"'; if($_SESSION['tf1']) $xau .= 'disabled'; else $xau .= 'abled'; $xau .='>Delete</button>
            <button type="submit" name="cancel" class="btn btn-primary">Cancel</button>
            <button type="submit" name="back" class="btn btn-primary">Back</button>
        </div>
    </div>
</form> </div>
    ';
    echo $xau;
    myFooter();
?>