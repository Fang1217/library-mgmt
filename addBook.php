<?php
include "dbQuery.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $resultCount = dbQueryCount("SELECT * from books");

    $book_id = $resultCount + 1;
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $publication_year = $_POST["publication_year"];
    $isbn = $_POST["isbn"];

    $query = "INSERT INTO books (book_id, title, author, publisher, publication_year, isbn, available) 
        VALUES ('$book_id', '$title', '$author', '$publisher', '$publication_year', '$isbn', 1)";

    $result = dbConnect($query);
    if ($result) {
        echo "
        <script>
            alert('Book has successfully added.');
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
