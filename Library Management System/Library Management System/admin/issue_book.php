<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
        <div class="container-fluid">
            <ul class="nav navbar-nav navbar-center">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Books </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_book.php">Add New Book</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_book.php">Manage Books</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Category </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_cat.php">Add New Category</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_cat.php">Manage Category</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Authors</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_author.php">Add New Author</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_author.php">Manage Author</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="issue_book.php">Issue Book</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    <center><h4>Issue Book</h4><br></center>
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
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="issue_date">Issue Date:</label>
                    <input type="date" name="issue_date" class="form-control" value="<?php echo date("Y-m-d");?>" required>
                </div>
                <div class="form-group">
                    <label for="return_days">Return Days Left:</label>
                    <input type="number" name="return_days" class="form-control" required>
                </div>
                <button type="submit" name="issue_book" class="btn btn-primary">Issue Book</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</body>
</html>

<?php
    if(isset($_POST['issue_book']))
    {
        $connection = mysqli_connect("localhost","root","");
        $db = mysqli_select_db($connection,"lms");
        
        $book_name = $_POST['book_name'];
        $book_no = $_POST['book_no'];
        $student_id = $_POST['student_id'];
        $return_days = $_POST['return_days'];
        $issue_date = $_POST['issue_date'];
        
        $query_check_user = "SELECT * FROM users WHERE id = $student_id";
        $query_run_check_user = mysqli_query($connection, $query_check_user);
        if (mysqli_num_rows($query_run_check_user) == 0) {
            echo '<script>alert("Student ID does not exist.");</script>';
        } else {
        
            $query_check_book = "SELECT * FROM books WHERE book_no = '$book_no' AND book_name = '$book_name'";
            $query_run_check_book = mysqli_query($connection, $query_check_book);
            if (mysqli_num_rows($query_run_check_book) == 0) {
                echo '<script>alert("Book with the entered ISBN number does not exist or does not match the provided book name.");</script>';
            } else {
                $return_date = date('Y-m-d', strtotime($issue_date . ' + ' . $return_days . ' days'));
                
                
                $query_count_books = "SELECT COUNT(*) AS issued_books FROM issued_books WHERE user_id = '$student_id' AND status = 'issued'";
                $query_run_count_books = mysqli_query($connection, $query_count_books);
                $result = mysqli_fetch_assoc($query_run_count_books);
                $issued_books_count = $result['issued_books'];
                
                
                if($issued_books_count >= 5)
                {
                    echo '<script>alert("Sorry, you have already reached the maximum limit of books that can be issued at a time. Please return some of the books before issuing more.");</script>';
                }
                else
                {
                    
                    $query_check_issue = "SELECT * FROM issued_books WHERE book_no = '$book_no' AND user_id = '$student_id' AND status = 'issued'";
                    $query_run_check_issue = mysqli_query($connection, $query_check_issue);
                    $rows = mysqli_num_rows($query_run_check_issue);
                    
                    if($rows > 0)
                    {
                        echo '<script>alert("This book is already issued to the student.");</script>';
                    }
                    else
                    {
                        $query_insert_issue = "INSERT INTO issued_books (user_id, book_no, issue_date, return_date, status, return_days) VALUES ('$student_id', '$book_no', '$issue_date', '$return_date', 'issued', '$return_days')";
                        $query_run_insert_issue = mysqli_query($connection, $query_insert_issue);
                        
                        if($query_run_insert_issue)
                        {
                            echo '<script>alert("Book issued successfully.");</script>';
                        }
                        else
                        {
                            echo '<script>alert("Failed to issue book.");</script>';
                        }
                    }
                }
            }
        }
    }
?>
