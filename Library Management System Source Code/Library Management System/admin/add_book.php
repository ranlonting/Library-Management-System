<?php
    require("functions.php");
    session_start();

    if(isset($_POST['add_book'])) {
        $book_name = $_POST['book_name'];
        $book_category = $_POST['book_category'];
        $book_no = $_POST['book_no'];
        $book_price = $_POST['book_price'];
        $book_copies = $_POST['book_copies'];

        // Check if the ISBN number already exists
        $connection = mysqli_connect("localhost", "root", "", "lms");
        $check_query = "SELECT * FROM books WHERE book_no = '$book_no'";
        $check_result = mysqli_query($connection, $check_query);
        if(mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('ISBN number already exists. Please enter a unique ISBN number.')</script>";
        } else {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // File upload validation code...
            
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $query = "INSERT INTO books (book_name, cat_id, book_no, book_price, book_image, total_copies) VALUES ('$book_name', '$book_category', '$book_no', '$book_price', '$target_file', '$book_copies')";
                    $query_run = mysqli_query($connection, $query);

                    if($query_run) {
                        $book_id = mysqli_insert_id($connection); 

                        if(isset($_POST['authors']) && is_array($_POST['authors'])) {
                            foreach($_POST['authors'] as $author_id) {
                                mysqli_query($connection, "INSERT INTO book_authors (book_id, author_id) VALUES ('$book_id', '$author_id')");
                            }
                        }

                        echo "<script>alert('Book added successfully.')</script>";
                        echo "<script>window.location.href = 'admin_dashboard.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add book.')</script>";
                    }
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/juqery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function alertMsg(){
            alert(Book added successfully...);
            window.location.href = "admin_dashboard.php";
        }
    </script>
</head>
<body>
    
    <center><h4>Add a new Book</h4><br></center>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="book_name">Book Name:</label>
                    <input type="text" name="book_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fileToUpload">Select an image to upload:</label>
                    <input type="file" name="fileToUpload" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_category">Category ID:</label>
                    <input type="text" name="book_category" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_no">ISBN Number:</label>
                    <input type="text" name="book_no" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_price">Book Price:</label>
                    <input type="text" name="book_price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_copies">Number of Copies:</label>
                    <input type="text" name="book_copies" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="authors">Authors:</label><br>
                    <?php
                        $connection = mysqli_connect("localhost", "root", "", "lms");
                        $authors_query = "SELECT * FROM authors";
                        $authors_query_run = mysqli_query($connection, $authors_query);
                        while ($row = mysqli_fetch_assoc($authors_query_run)) {
                            echo "<input type='checkbox' name='authors[]' value='".$row['author_id']."'>".$row['author_name']."<br>";
                        }
                    ?>
                </div>
                <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</body>
</html>
