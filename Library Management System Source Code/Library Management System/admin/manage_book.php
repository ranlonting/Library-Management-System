<?php
    require("functions.php");
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Book</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
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
                    <a class="dropdown-item" href="#">View Profile</a>
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
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    <center><h4>Manage Books</h4><br></center>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-9">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Author Name</th>
                        <th>Category ID</th>
                        <th>ISBN No.</th>
                        <th>Price</th>
                        <th>Total Copies</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                    $connection = mysqli_connect("localhost", "root", "", "lms");
                    $query = "SELECT 
                                books.book_id,
                                books.book_name, 
                                books.book_image, 
                                books.book_no, 
                                books.book_price, 
                                GROUP_CONCAT(authors.author_name) AS author_name, 
                                books.total_copies,
                                books.cat_id
                            FROM 
                                books 
                            LEFT JOIN 
                                book_authors 
                            ON 
                                books.book_id = book_authors.book_id 
                            LEFT JOIN 
                                authors 
                            ON 
                                book_authors.author_id = authors.author_id
                            GROUP BY 
                                books.book_id";
                    $query_run = mysqli_query($connection, $query);
                    
                    while ($row = mysqli_fetch_assoc($query_run)){
                        // Display the category ID
                        $category_id = $row['cat_id'];
                ?>
                <tr>
                    <td><img src="<?php echo $row['book_image']; ?>" style="width: 100px; height: 100px;"></td>
                    <td><?php echo $row['book_name'];?></td>
                    <td><?php echo $row['author_name'];?></td>
                    <td><?php echo $category_id;?></td>
                    <td><?php echo $row['book_no'];?></td>
                    <td><?php echo $row['book_price'];?></td>
                    <td><?php echo $row['total_copies'];?></td>
                    <td>
                        <button class="btn" name=""><a href="edit_book.php?bn=<?php echo $row['book_no'];?>">Edit</a></button>
                        <button class="btn"><a href="delete_book.php?bn=<?php echo $row['book_no'];?>">Delete</a></button>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</body>
</html>
