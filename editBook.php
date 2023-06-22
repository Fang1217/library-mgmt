<?php
require "dbQuery.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $book_id = $_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $publication_year = $_POST["publication_year"];
    $isbn = $_POST["isbn"];

    // Process the data

    if (!preg_match('/\d+/', $book_id)) { //id is not numbers
        return;
    }

    // Execute the query
    $query = "UPDATE books SET " .
    "title = " . ($title != "" ? "'$title'" : "title") . ", " .
    "author = " . ($author != "" ? "'$author'" : "author") . ", " .
    "publisher = " . ($publisher != "" ? "'$publisher'" : "publisher") . ", " .
    "publication_year = " . ($publication_year != "" ? "'$publication_year'" : "publication_year") . ", " .
    "isbn = " . ($isbn != "" ? "'$isbn'" : "isbn") . " " .
    "WHERE book_id = $book_id;";    
    $result = dbConnect($query);
    
    // Check if the query was successful and if any matching records were found
    if ($result) {
        echo "
        <script>
            alert('Book ID $book_id has successfully edited.');
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
};

?>
