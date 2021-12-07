<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<?php
    session_start();

    class category {
        public $category_id;
        public $category_name;
        function __construct($id, $name) {
            $this->category_id = $id;
            $this->category_name = $name;
        }
    }

    function getArr() {
        $con = new mysqli('localhost','root','','db_shop');
        $q = $con->query('select * from category');
        while($r = $q->fetch_array()) {
            $arr[] = new category($r['category_id'], $r['category_name']);
        }
        $con->close();
        return $arr;
    }

    function getData($s) {
        $con = new mysqli('localhost','root','','db_shop');
        $q = $con->query($s);
        $xau='';
        while($r = $q->fetch_array()) {
            $xau .= '
            <div class="container-fluid p-5 my-5 bg-primary text-white">
                <h3>'.$r['category_name'].'</h3>
            </div>
            ';
            $q1 = $con->query("select * from product where category_id='".$r['category_id']."'");
            $xau .= '<div class="row" style="margin-bottom: 60px; margin-left: 50px;">';
            while($r1 = $q1->fetch_array()) {
                $xau .= '
                <div class="col-sm-3">'.'<img src="images/'.$r1['product_image'].'" width="120px" height="120px">'
                .'<h4><b>'.$r1['product_name'].'</b></h4><h4>Price: '.$r1['product_price'].'$</h4>
                <h4><a href="index.php?product_name='.$r1['product_name'].'&product_price='.$r1['product_price'].'&product_id='.$r1['product_id'].'" class="btn btn-danger">Buy</a>'.'</h4>
                </div>
                ';
            }
            $xau .='</div>';
        }
        return $xau;
    }

    function loadDatatpTable($s) {
        $con = new mysqli('localhost','root','','db_shop');
        $q = $con->query($s);
        $xau='<table>
            <tr>
            <th>Ord</th>
            <th>Category Name</th>
            <th><th>
            </tr>';
            $i = 1;
            if($_SESSION['tf1'])$tmp="abled";
            else $tmp = "disabled";
        while($r = $q->fetch_array())
            $xau .= '<tr><td>'.$i++.'</td><td>'.$r['category_name'].'</td><td>'.
                '<button type="submit" name="choose" '.$tmp.' value="'.$r['category_id'].'">...</button>'
            .'</td></tr>';
        $xau .= '</table>';
        return $xau;
    } 

    function readOneRecord($s) {
        $con = new mysqli('localhost','root','','db_shop');
        $q = $con->query($s);
        $xau='';
        if($r = $q->fetch_array()) 
            $xau = $r['category_name'];
        
        return $xau;
    }

    function doSQL($s) {
        $con = new mysqli('localhost','root','','db_shop');
        $q = $con->query($s);
        return $q;
    }

    // function addItemToCart($product_name, $product_price) {
    //     $i = 0;
    //     if(isset($_SESSION['cart'])) {
    //         $cart = $_SESSION['cart'];
    //         $i = count($cart);
    //         $pos = -1;
    //         for($j = 0; $j < $i; $j++) 
    //             if($cart[$j][0] == $product_name) {
    //                 $pos = $j;
    //                 break;
    //             }
    //             if($pos!=-1) {
    //                 $cart[$pos][1]++;
    //             } else {
    //                 $cart[$i][0] = $product_name;
    //                 $cart[$i][1] = 1;
    //                 $cart[$i][2] = $product_price;
    //             }
    //         }
    //         else {
    //         $cart = array(array());
    //         $cart[0][0] = $product_name;
    //         $cart[0][1] = 1;
    //         $cart[0][2] = $product_price;
    //     }
    //     $_SESSION['cart'] = $cart;
    // }

    // function showCart() {
    //     echo '<h3>DANH SÁCH HÀNH HÓA</h3>';
    //     echo '<table class="table table-striped" style="width:600px"><tr><th>'.
    //     '<th>Ten hang</th><th>So luong</th><th>Don gia</th><th>Thanh tien</th></tr>';
    //     if(isset($_SESSION['cart'])) {
    //         $cart = $_SESSION['cart'];

    //         for($j = 0; $j < count($cart); $j++) {
            
    //             echo '<tr><td>'.($j+1).'</td><td>'.$cart[$j][0].'</td><td>'.$cart[$j][1].'</td>
    //             <td>'.$cart[$j][2].'</td><td>'.($cart[$j][1]*$cart[$j][2]).'</td></tr>';
    //         }
    //     }
        
    //     echo '</table>';
    //     echo '<a href="index.php">Back</a>   <a href="cart.php?clear=OK">Clear</a>';
    // }

    function addItemToCart($product_name, $product_price, $product_id) {
        $i = 0;
        if(isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $i = count($cart);
            $pos = -1;
            //Tim mat hang them vao da co trong gio hang hay chua?
            for($j = 0; $j < $i; $j++) {
                if($cart[$j][3]==$product_id) {
                    $pos=$j; break;
                }
            }
            //Neu hang them vao da ton tai thi so luong hang do se tang len 1
            if($pos != -1) {
                $cart[$pos][1]++;
            } else {
                $cart[$i][0] = $product_name;
                $cart[$i][1] = 1;
                $cart[$i][2] = $product_price;
                $cart[$i][3] = $product_id;
            }
        } else {
            $cart = array(array());
            $cart[0][0] = $product_name;
            $cart[0][1] = 1;
            $cart[0][2] = $product_price;
            $cart[$i][3] = $product_id;
        }
        $_SESSION['cart'] = $cart;
    }

    function showCart() {
        $s = '
        <form action="" method="post">
            <div class="form-group" style="margin-left:30px;">
            <div class="mb-3">'. 
            '<h3>DANH SÁCH HÀNG HÓA</h3>'.
            '<table class="table table-striped" style="width:600px;"><tr><th>STT</th>'.
            '<th colspan=2>Tên hàng</th><th>Số lượng</th><th colspan=2>Đơn giá</th><th colspan=2>Thành tiền</th><th colspan=2></th><tr>';
            if(isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
                $tong = 0;
                for($j = 0; $j < count($cart); $j++) {
                    $s .= '<tr><td>'.($j+1).'</td><td colspan=2>'.$cart[$j][0].'</td><td>'.
                    '<input type="text" name="itemQuantity[]" value="'.$cart[$j][1].'" style="width:40px;">'.
                    '</td><td>'. 
                    number_format($cart[$j][2]).'</td><td>'.number_format(($cart[$j][1]*$cart[$j][2])).'</td><td colspan=2>'.
                    '<button type="submit" name="deleteItem" value="'.$j.'">Delete</button>'.
                    '<button type="submit" name="updateItem" value="'.$j.'">Update</button>'.
                    '</td><td>';
                    $tong += $cart[$j][1]*$cart[$j][2];
                }
                $s .= '<tr><td colspan=4><b>Tổng tiền:</b></td><td><b>'.number_format($tong).'</b></td></tr>';
            }

            $s .= '</table>';
            $s .= '<a href="index.php">Back</a> <a class="btn btn-primary" href="cart.php?clear=OK">Empty Cart</a>';
            $s .= '</div></div></form>';
            echo $s;
    }

    function deleteCartItem($pos) {
        if(isset($_SESSION['cart'])) {
            array_splice($_SESSION['cart'], $pos, 1);
        }
    }

    function updateCartItem($pos, $quantity) {
        if(isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $cart[$pos][1] = $quantity;
            $_SESSION['cart'] = $cart;
        }
    }
?>