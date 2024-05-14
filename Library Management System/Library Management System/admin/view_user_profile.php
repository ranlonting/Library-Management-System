<?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "lms");
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            margin-right: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br>
    <div class="container">
        <h2 class="text-center mb-4">User Profile</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $row['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $row['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td><?php echo $row['mobile']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo $row['address']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
