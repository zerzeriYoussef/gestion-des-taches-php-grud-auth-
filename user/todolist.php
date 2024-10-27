<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <!-- Image and text -->
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">zarzour</a>
        <img src="../img/img1.jpg" width="50" height="50" class="d-inline-block align-top" alt="" loading="lazy">
    </nav>

    <main class="container m-auto" style="max-width: 720px; direction: rtl; text-align: right;">
        <form method="POST">
            <br>
            <a class="w-100 btn btn-light mb-3" href="index.php"> عودة لصفحة الرئيسية</a>
            <input class="form-control" type="text" name="text" required/>
            <button class="w-100 btn btn-warning mb-3" type="submit" name="add">إضافة</button>
        </form> 

        <!-- Form for exporting to Excel -->
        <form method="POST" action="">
            <button type="submit" class="btn btn-primary" name="export">Export to Excel</button>
        </form>

        <?php
        session_start();
        require_once '../connectToDatabase.php';

        // Add task logic
        if(isset($_POST['add'])){
            $addItem = $database->prepare("INSERT INTO todolist(text,userId,status) VALUES(:text,:userId,'no')");
            $addItem->bindParam("text",$_POST['text']);
            $userId = $_SESSION['user']->ID;
            $addItem->bindParam("userId",$userId);

            if($addItem->execute()){
                echo '<div class="alert alert-success mt-3 mb-3"> تم اضافة بنجاح </div>';
                header("refresh:2;");
            }
        }

        // Export to Excel logic
        if(isset($_POST['export'])){
            $fileName = "todolist_" . date('Y-m-d') . ".xlsx";
            $excelData[] = array('ID', 'Text', 'User ID', 'Status');

            $query = $database->prepare("SELECT * FROM todolist WHERE userId = :id");
            $userId = $_SESSION['user']->ID;
            $query->bindParam("id",$userId);
            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $status = ($row['status'] == 'yes') ? 'منجز' : 'غير منجز';
                $lineData = array($row['id'], $row['text'], $row['userId'], $status);
                $excelData[] = $lineData;
            }

            require_once 'PhpXlsxGenerator.php';
            $xlsx = PhpXlsxGenerator::fromArray($excelData);
            $xlsx->downloadAs($fileName);
            exit;
        }

        // Display tasks
        $toDoItems = $database->prepare("SELECT * FROM todolist WHERE userId = :id");
        $userId = $_SESSION['user']->ID;
        $toDoItems->bindParam("id",$userId);
        $toDoItems->execute();

        echo '<table class="table">';
        echo '<tr>';
        echo '<th>المهمة</th>';
        echo '<th>الحالة</th>';
        echo '<th>حذف</th>';
        echo '</tr>';

        foreach($toDoItems AS $items){
            echo ' <form> <tr> ';
            echo '<th>'.$items['text'].'</th>';

            if($items['status'] ==="no"){
                echo '<th>
                <input type="hidden" name="statusValue" value="'.$items['status'].'"/>
                <button type="submit" class="btn btn-warning" name="status" value="'.$items['id'].'">غير منجز</button> </th>';
            } else if($items['status'] ==="yes"){
                echo '<th> 
                <input type="hidden" name="statusValue" value="'.$items['status'].'"/>
                <button type="submit" class="btn btn-success" name="status" value="'.$items['id'].'">منجز</button></th>';
            }

            echo '<th> <button type="submit" class="btn btn-outline-danger" name="remove" value="'.$items['id'].'">حذف</button></th>';
            echo '</tr> </form>';
        }

        echo '</table>';

        // Update status logic
        if(isset($_GET['status'])){
            if($_GET['statusValue'] ==="no"){
                $updateStatus = $database->prepare("UPDATE todolist SET status = 'yes' WHERE id = :id");
                $updateStatus->bindParam("id",$_GET['status']);
                $updateStatus->execute();
                header("location:todolist.php",true);
            } else if($_GET['statusValue'] ==="yes"){
                $updateStatus = $database->prepare("UPDATE todolist SET status = 'no' WHERE id = :id");
                $updateStatus->bindParam("id",$_GET['status']);
                $updateStatus->execute();
                header("location:todolist.php",true);
            }
        }

        // Remove task logic
        if(isset($_GET['remove'])){
            $removeItem = $database->prepare("DELETE FROM todolist WHERE id = :id");
            $removeItem->bindParam('id',$_GET['remove']);
            $removeItem->execute();
            header("location:todolist.php",true);
        }

        // Logout logic
        if(isset($_GET['logout'])){
            session_unset();
            session_destroy();
            header("location:http://localhost/App/login.php",true); 
        }
        ?> 
    </main>
</body>
</html>
