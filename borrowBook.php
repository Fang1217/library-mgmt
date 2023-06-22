<?php

include "dbQuery.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $resultCount = dbQueryCount("SELECT * from borrow");
    $borrow_id = $resultCount + 1;

    // Retrieve form data
    $book_id = $_POST["id"];
    $borrower_name = $_POST["name"];
    $borrower_nric = $_POST["nric"];
    $borrow_date = $_POST["date"];

    // Process the data

    if (!preg_match('/\d+/', $book_id)) { //id is not numbers
        return;
    }

    
    $query = "INSERT INTO borrow (borrow_id, book_id, borrower_name, borrower_nric, borrow_date)
        VALUES ('$borrow_id', '$book_id', '$borrower_name', '$borrower_nric', '$borrow_date');
    ";

    
    // Execute the query
    $result = dbConnect($query);

    // Check if the query was successful and if any matching records were found
    if ($result) {
        //update book status
        $updateQuery = "UPDATE books SET available = 0 WHERE book_id = '$book_id'";
        $update = dbConnect($updateQuery);

        if (!$update) return; 
        echo "
        <script>
            alert('Book ID $book_id has successfully borrowed.');
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


    // Close the database connection
    mysqli_close($conn);
};




?>
