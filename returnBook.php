<?php
require "dbQuery.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $borrow_id = $_POST["borrowId"];
    $book_id = $_POST["bookId"];
    $borrower_name = $_POST["name"];
    $borrower_nric = $_POST["nric"];
    $return_date = $_POST["date"];

    // Process the data

    if (!preg_match('/\d+/', $book_id)) { //id is not numbers
        return;
    }

    // Execute the query
    $query = "UPDATE borrow SET return_date = '$return_date' WHERE borrow_id = '$borrow_id'";
    $result = dbConnect($query);

    // Check if the query was successful and if any matching records were found
    if ($result) {
        //update book status
        $updateQuery = "UPDATE books SET available = 1 WHERE book_id = '$book_id'";
        $update = dbConnect($updateQuery);

        if (!$update) return; 
        echo "
        <script>
            alert('Book ID $book_id has successfully returned.');
            window.location.href = 'borrow.php';
        </script>
        ";
    }
    else {
        echo "
        <script>
        alert('Something is wrong.');
        window.location.href = 'borrow.php';
        </script>
        ";
    }
};

?>
