<?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "lms");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Issued Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .navbar {
            background-color: #343a40; 
        }
        .navbar-brand,
        .navbar-text,
        .nav-link {
            color: #ffffff; 
        }
        .navbar-brand:hover,
        .navbar-text:hover,
        .nav-link:hover {
            color: #cccccc; 
        }
        .container {
            background-color: #ffffff; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            padding: 20px; 
            margin-top: 20px; 
        }
        h2 {
            color: #343a40; 
        }
        th {
            background-color: #343a40; 
            color: #ffffff; 
        }
        th,
        td {
            text-align: center; 
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2; 
        }
        .navbar-text {
            margin-right: 250px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="navbar-text text-white mr-3">Welcome: <?php echo $_SESSION['name'];?></span>
                        <span class="navbar-text text-white">Email: <?php echo $_SESSION['email'];?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br>
    <div class="container">
        <h2 class="text-center mb-4">Issued Books</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Book Name</th>
                        <th>ISBN Number</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Return Days Left</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT ib.*, b.book_name, u.name as student_name,
                                          DATEDIFF(ib.return_date, CURDATE()) AS return_days_left
                                  FROM issued_books ib 
                                  INNER JOIN books b ON ib.book_no = b.book_no
                                  INNER JOIN users u ON ib.user_id = u.id";
                        $query_run = mysqli_query($connection, $query);
                        while($row = mysqli_fetch_assoc($query_run)) {
                    ?>
                    <tr>
                        <td><a href="view_user_profile.php?id=<?php echo $row['user_id']; ?>"><?php echo $row['student_name']; ?></a></td>
                        <td><?php echo $row['book_name']; ?></td>
                        <td><?php echo $row['book_no']; ?></td>
                        <td><?php echo $row['issue_date']; ?></td>
                        <td><?php echo $row['return_date']; ?></td>
                        <td><?php echo $row['return_days_left']; ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
