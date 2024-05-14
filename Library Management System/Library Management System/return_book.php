<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name'];?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email'];?></strong></span></font>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="" method="post">
                <div class="form-group">
                    <label for="book_name">Book Name:</label>
                    <select name="book_name" class="form-control" required>
                        <option value="">-Select Book-</option>
                        <?php  
                            $connection = mysqli_connect("localhost", "root", "");
                            $db = mysqli_select_db($connection, "lms");
                            $query = "SELECT book_name FROM books";
                            $query_run = mysqli_query($connection, $query);
                            while($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                            <option value="<?php echo $row['book_name']; ?>"><?php echo $row['book_name']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="book_no">ISBN Number:</label>
                    <input type="text" name="book_no" class="form-control" required>
                </div>
                <button type="submit" name="return_book" class="btn btn-primary">Return Book</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</body>
</html>

<?php
    if(isset($_POST['return_book']))
    {
        $connection = mysqli_connect("localhost","root","");
        $db = mysqli_select_db($connection,"lms");
        
        $book_name = $_POST['book_name'];
        $book_no = $_POST['book_no'];
        $student_id = $_SESSION['student_id']; // Assuming you have stored student_id in session
        
        $query_check_issue = "SELECT * FROM issued_books WHERE book_no = '$book_no' AND user_id = '$student_id' AND status = 'issued'";
        $query_run_check_issue = mysqli_query($connection, $query_check_issue);
        $rows = mysqli_num_rows($query_run_check_issue);
        
        if($rows > 0)
        {
            $query_update_issue = "UPDATE issued_books SET status = 'returned' WHERE book_no = '$book_no' AND user_id = '$student_id' AND status = 'issued'";
            $query_run_update_issue = mysqli_query($connection, $query_update_issue);
            
            if($query_run_update_issue)
            {
                $query_delete_issue = "DELETE FROM issued_books WHERE book_no = '$book_no' AND user_id = '$student_id' AND status = 'returned'";
                $query_run_delete_issue = mysqli_query($connection, $query_delete_issue);
                
                if($query_run_delete_issue)
                {
                    echo '<script>alert("Book returned successfully.");</script>';
                }
                else
                {
                    echo '<script>alert("Failed to delete record of returned book.");</script>';
                }
            }
            else
            {
                echo '<script>alert("Failed to return book.");</script>';
            }
        }
        else
        {
            echo '<script>alert("This book is not currently issued to you.");</script>';
        }
    }
?>
