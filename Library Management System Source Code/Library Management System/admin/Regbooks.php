<?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "", "lms");
    $search_query = "";
    if(isset($_GET['search'])) {
        $search_query = $_GET['search'];
    }
    $query = "SELECT 
                books.book_id,
                books.book_name, 
                books.book_image, 
                books.book_no, 
                books.book_price, 
                GROUP_CONCAT(authors.author_name) AS author_name, 
                books.total_copies,
                COUNT(issued_books.book_no) AS issued_copies,
                books.total_copies - COUNT(issued_books.book_no) AS available_copies
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
            LEFT JOIN 
                issued_books 
            ON 
                books.book_id = issued_books.book_no";
    if(!empty($search_query)) {
        $query .= " WHERE 
                        books.book_name LIKE '%$search_query%' OR
                        authors.author_name LIKE '%$search_query%'";
    }
    $query .= " GROUP BY 
                    books.book_id";
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Reg Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
    <center><h4>Registered Book's Detail</h4><br></center>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <form method="GET">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search by book name or author name" name="search" value="<?php echo $search_query; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
            <table class="table-bordered" width="900px" style="text-align: center">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>ISBN No.</th>
                    <th>Total Quantity</th>
                    <th>Issued Copies</th>
                    <th>Available Copies</th>
                </tr>
            
                <?php
                    $query_run = mysqli_query($connection,$query);
                    while ($row = mysqli_fetch_assoc($query_run)){
                ?>
                    <tr>
                        <td><img src="<?php echo $row['book_image'];?>" style="max-width: 100px; max-height: 100px;"></td>
                        <td><?php echo $row['book_name'];?></td>
                        <td><?php echo $row['author_name'];?></td>
                        <td><?php echo $row['book_price'];?></td>
                        <td><?php echo $row['book_no'];?></td>
                        <td><?php echo $row['total_copies'];?></td>
                        <td><?php echo $row['issued_copies'];?></td>
                        <td><?php echo $row['available_copies'];?></td>
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
