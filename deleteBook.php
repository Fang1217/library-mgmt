<?php
    $bookId = $_GET['book_id'];
    //delete book
    $conn = mysqli_connect('localhost', 'root', '', 'dblibrary');
    
    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // After deleting auto increment handling
    $query = "
        DELETE FROM books WHERE book_id = $bookId;
        UPDATE books SET book_id = book_id - 1 WHERE book_id > $bookId;
        UPDATE borrow SET book_id = book_id - 1 WHERE book_id > $bookId;
    ";
    try {
        $result = mysqli_multi_query($conn, $query);
    }
    catch (mysqli_sql_exception $result) {
        // Handle the foreign key constraint exception
        echo "<script>
        alert('Unable to delete specified Book ID due to existing borrow transactions.');". 
        "window.location.href = 'search.php' </script>";
        return;
    }
    // Check if the query was successful and if any matching records were found
    if ($result) {
        echo "
        <script>
            alert('Book has successfully deleted.');
            window.location.href = 'search.php';
        </script>
        ";
    }
    else {
        echo "
        <script>
            alert('Something is wrong.');
            window.location.href = 'search.php';
        </script>
        ";
    }
    
    // Close the database connection
    mysqli_close($conn);

?>