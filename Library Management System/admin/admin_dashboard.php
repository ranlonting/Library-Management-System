<?php
    require("functions.php");
    session_start();

    if(isset($_POST['add_book'])) {
        $book_name = $_POST['book_name'];
        $book_author = $_POST['book_author'];
        $book_category = $_POST['book_category'];
        $book_no = $_POST['book_no'];
        $book_price = $_POST['book_price'];
        $book_copies = $_POST['book_copies'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $connection = mysqli_connect("localhost", "root", "", "lms");
        $query = "INSERT INTO books (book_name, author_id, cat_id, book_no, book_price, book_image, copies) VALUES ('$book_name', '$book_author', '$book_category', '$book_no', '$book_price', '$target_file', '$book_copies')";
        $query_run = mysqli_query($connection, $query);

        if($query_run) {
            echo "<script>alert('Book added successfully.')</script>";
            echo "<script>window.location.href = 'admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to add book.')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/juqery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name'];?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email'];?></strong></font>
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="view_profile.php">View Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
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
              <li class="nav-item">
                <a class="nav-link" href="return_book.php">Return Book</a>
              </li>
            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library mangement system. Library opens at 8:00 AM and close at 8:00 PM</marquee></span><br><br>
    <div class="row">
        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Registered User</div>
                <div class="card-body">
                    <p class="card-text">No. total Users: <?php echo get_user_count();?></p>
                    <a class="btn btn-danger" href="Regusers.php" target="_blank">View Registered Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Total Book</div>
                <div class="card-body">
                    <p class="card-text">No of books available: <?php echo get_book_count();?></p>
                    <a class="btn btn-success" href="Regbooks.php" target="_blank">View All Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Book Categories</div>
                <div class="card-body">
                    <p class="card-text">No of Book's Categories: <?php echo get_category_count();?></p>
                    <a class="btn btn-warning" href="Regcat.php" target="_blank">View Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">No. of Authors</div>
                <div class="card-body">
                    <p class="card-text">No of Authors: <?php echo get_author_count();?></p>
                    <a class="btn btn-primary" href="Regauthor.php" target="_blank">View Authors</a>
                </div>
            </div>
        </div>
    </div><br><br>
    <div class="row">
        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Book Issued</div>
                <div class="card-body">
                    <p class="card-text">No of book issued: <?php echo get_issue_book_count();?></p>
                    <a class="btn btn-success" href="view_issued_book.php" target="_blank">View Issued Books</a>
                </div>
            </div>
        </div>
        
</body>
</html>
