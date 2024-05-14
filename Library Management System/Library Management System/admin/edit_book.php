<?php
    require("functions.php");
    session_start();

    if(isset($_GET['bn'])) {
        $connection = mysqli_connect("localhost", "root", "", "lms");
        $book_no = $_GET['bn'];
        $query = "SELECT books.*, GROUP_CONCAT(authors.author_name) AS author_names FROM books LEFT JOIN book_authors ON books.book_id = book_authors.book_id LEFT JOIN authors ON book_authors.author_id = authors.author_id WHERE book_no = '$book_no' GROUP BY books.book_id";
        $query_run = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($query_run);
        $book_name = $row['book_name'];
        $book_image = $row['book_image'];
        $author_names = $row['author_names'];
        $cat_id = $row['cat_id'];
        $book_price = $row['book_price'];
        $total_copies = $row['total_copies'];
    }

    if(isset($_POST['update_book'])) {
        // Update book details
        $book_name = $_POST['book_name'];
        $author_ids = $_POST['author_ids'];
        $cat_id = $_POST['book_category'];
        $book_no = $_POST['book_no'];
        $book_price = $_POST['book_price'];
        $total_copies = $_POST['book_copies'];

        // Update the book details in the database
        $query_update_book = "UPDATE books SET book_name = '$book_name', cat_id = '$cat_id', book_price = '$book_price', total_copies = '$total_copies' WHERE book_no = '$book_no'";
        $query_update_book_run = mysqli_query($connection, $query_update_book);

        // Delete existing authors for the book
        $query_delete_authors = "DELETE FROM book_authors WHERE book_id = (SELECT book_id FROM books WHERE book_no = '$book_no')";
        $query_delete_authors_run = mysqli_query($connection, $query_delete_authors);

        // Insert new authors for the book
        foreach($author_ids as $author_id) {
            mysqli_query($connection, "INSERT INTO book_authors (book_id, author_id) VALUES ((SELECT book_id FROM books WHERE book_no = '$book_no'), '$author_id')");
        }

        if($query_update_book_run && $query_delete_authors_run) {
            echo "<script>alert('Book updated successfully.')</script>";
            echo "<script>window.location.href = 'manage_book.php';</script>"; // Redirect to manage_book
        } else {
            echo "<script>alert('Failed to update book.')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation and other content -->
    <center><h4>Edit Book</h4><br></center>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="book_name">Book Name:</label>
                    <input type="text" name="book_name" value="<?php echo $book_name; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="author_ids">Authors:</label><br>
                    <?php
                        $authors = explode(',', $author_names);
                        $connection = mysqli_connect("localhost", "root", "", "lms");
                        $authors_query = "SELECT * FROM authors";
                        $authors_query_run = mysqli_query($connection, $authors_query);
                        while ($row = mysqli_fetch_assoc($authors_query_run)) {
                            $checked = in_array($row['author_name'], $authors) ? 'checked' : '';
                            echo "<input type='checkbox' name='author_ids[]' value='".$row['author_id']."' $checked>".$row['author_name']."<br>";
                        }
                    ?>
                </div>
                <div class="form-group">
                    <label for="book_category">Category ID:</label>
                    <input type="text" name="book_category" value="<?php echo $cat_id; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_no">ISBN Number:</label>
                    <input type="text" name="book_no" value="<?php echo $book_no; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_price">Book Price:</label>
                    <input type="text" name="book_price" value="<?php echo $book_price; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="book_copies">Number of Copies:</label>
                    <input type="text" name="book_copies" value="<?php echo $total_copies; ?>" class="form-control" required>
                </div>
                <button type="submit" name="update_book" class="btn btn-primary">Update Book</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</body>
</html>
