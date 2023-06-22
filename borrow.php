<!DOCTYPE html>
<html>


<head>
    <title>
        Borrow or Return Books
    </title>
    <style>
        table {
            border-collapse: collapse;
        }
        table.result td, table.result th {
            border: 1px solid black;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet"  href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    <div class="headerBar">
        <table class="navigation">
            <tr>
                <td colspan="4" style="font-size: 32px;">
                    <img width="60px" src="images/logo.png" style="vertical-align: middle;">
                    Library Management System
                </td>
            </tr>
            <tr>
            <td>
                <a href="index.html">HOME</a>
            </td>
            <td>
                <a href="search.php"><i class="fa fa-book"></i>&nbspBOOK INFORMATION</a>
            </td>
            <td>
                <a href="borrow.php"><i class="fa fa-pencil"></i>&nbspBORROW / RETURN BOOK</a></li>
            </td>
            </tr>
        </table>
    </div>


        <!-- Add book info -->
        <div id="divSearch" class="divSection">
            
            <form action="borrow.php" method="get">
                <!-- Search Table Content -->
                <table style="text-align: left;">
                <tr>
                    <th colspan="3"><h1>Borrow/Return Books</h1></th>
                </tr>
                <tr>
                    <th>Search term: &nbsp;&nbsp;</th>
                    <th><input type="text" name="search_term"></th> 
                    <th><button type="submit">Submit</button></th>
                </tr>
                <tr>
                    <th colspan="2">
                        <input type="radio" name="search_type" value="title" checked>
                        Title
                        <input type="radio" name="search_type" value="author">
                        Author
                        <input type="radio" name="search_type" value="publisher">
                        Publisher
                    </th>
                </tr>
                </table>
            </form>
        </div>
    
    
</body>

</html>

<?php

require 'dbQuery.php';


if (isset($_GET["search_term"]) && isset($_GET["search_type"])) {
    // Retrieve the selected field and search term values from the form submission
    $searchTerm = $_GET["search_term"];
    $searchType = $_GET["search_type"];
    
    $headerString = ($searchTerm == "" ? "Displaying all books." : "Displaying all $searchType containing \"$searchTerm\":");
        showTable("SELECT * FROM books WHERE $searchType LIKE '%$searchTerm%'", $headerString);
    
}
else
    // Default: Select all if not set
    showTable("SELECT * FROM books", "Displaying all books.");
    

if (isset($_GET["borrowBookId"]) && preg_match('/\d+/', $_GET['borrowBookId'])) {
    $borrowBookId = $_GET['borrowBookId'];
    $borrowQuery = "SELECT * FROM books WHERE book_id = $borrowBookId";
    $borrowResult = dbConnect($borrowQuery);

    // Check if the query was successful and if any matching records were found
    if ($borrowResult && mysqli_num_rows($borrowResult) > 0) {
        $row = mysqli_fetch_assoc($borrowResult);
        if (($row['available'])!= 1) {
            return;
        }
        echo "<div class='divSection'";
        echo "<table><tr>";
        echo "<tr><td>Borrowing Book with ID $borrowBookId </tr></td>";
        echo "<br>";
        echo "<tr><td>Details:</tr></td>";
        echo "<br>";
        echo "<tr><td>Title: " . $row['title'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Author: " . $row['author'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Publisher: " . $row['publisher'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Publication Year: " . $row['publication_year'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>ISBN: " . $row['isbn'] . "</tr></td>";
        echo "<br>";
        echo "</tr></table>";
        echo "<br>";
    }
    else if ($borrowResult) {
        // No matching records found
        echo "No results found.";
    }
    else {
        echo "Error";
    }
    echo "
    <form id='borrowBook' action='borrowBook.php' method='post'>
    <table style='text-align:left'>
        <tr><th colspan=2>Add borrower's information: </th></tr>  
        <tr>
            <th>Borrowing Book ID:</th>
            <td><input style='background-color: lightgrey;' type='text' name='id' readonly value=$borrowBookId></td>
        </tr>
        <tr>
            <th><label for='name'>Borrower's Name:</label></th>
            <td><input type='text' name='name'></td>
        </tr>
        <tr>
            <th><label for='nric'>Borrower's NRIC:</label></th>
            <td><input type='text' name='nric'></td>
        </tr>
        <tr>
            <th><label for='date'>Borrowing Date:</label></th>
            <td>
            <input type='date' name='date' id='date'>
            <script> //Set
                    var currentDate = new Date();
                    currentDate.setHours(currentDate.getHours() + 8);
                    // Format the date in YYYY-MM-DD format
                    var formattedDate = currentDate.toISOString().split('T')[0];
        
                    document.getElementById('date').value = formattedDate;
            </script>
            </td>
        </tr>
        <tr><th><button type='submit'>Submit</button>
        <button type='reset'>Clear</button></th></tr>
    </table>
    </form></div>
    ";
}
else if (isset($_GET["returnBookId"]) && preg_match('/\d+/', $_GET['returnBookId'])) {
    $returnBookId = $_GET['returnBookId'];
    $returnQuery = "SELECT * FROM borrow 
        JOIN books USING (book_id)
        WHERE book_id = $returnBookId AND return_date = '0000-00-00'";
    $returnResult = dbConnect($returnQuery);
    //include 'borrowbook.php';

    // Check if the query was successful and if any matching records were found
    if ($returnResult && mysqli_num_rows($returnResult) > 0) {
        $row = mysqli_fetch_assoc($returnResult);
        if (($row['available'])!= 0) {
            return;
        }
        $borrow_id = $row['borrow_id'];
        $borrower_name = $row['borrower_name'];
        $borrower_nric = $row['borrower_nric'];

        echo "<div class='divSection'";
        echo "<table><tr>";
        echo "<tr><td>Returning Book with ID $returnBookId </tr></td>";
        echo "<br>";
        echo "<tr><td>Details:</tr></td>";
        echo "<br>";
        echo "<tr><td>Title: " . $row['title'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Author: " . $row['author'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Publisher: " . $row['publisher'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>Publication Year: " . $row['publication_year'] . "</tr></td>";
        echo "<br>";
        echo "<tr><td>ISBN: " . $row['isbn'] . "</tr></td>";
        echo "<br>";
        echo "</tr></table>";
        echo "<br>";
    }
    else if ($returnResult) {
        // No matching records found
        echo "No results found.";
    }
    else {
        echo "Error";
    }
     
    // Check if the query was successful and if any matching records were found
    echo "
    <form id='returnBook' action='returnBook.php' method='post'>
    <table style='text-align:left'>
        <tr><th colspan=2>Borrower's information: </th></tr>
        <tr>
            <th>Borrow ID:</th>
            <td><input style='background-color: lightgrey;' type='text' name='borrowId' readonly value=$borrow_id></td>
        </tr>
        <tr>
            <th>Returning Book ID:</th>
            <td><input style='background-color: lightgrey;' type='text' name='bookId' readonly value=$returnBookId></td>
        </tr>
        <tr>
            <th><label for='name'>Borrower's Name:</label></th>
            <td><input style='background-color: lightgrey;' type='text' name='name' readonly value= $borrower_name></td>
        </tr>
        <tr>
            <th><label for='nric'>Borrower's NRIC:</label></th>
            <td><input style='background-color: lightgrey;' type='text' name='nric' readonly value=$borrower_nric></td>
        </tr>
        <tr>
            <th><label for='date'>Returning Date:</label></th>
            <td><input type='date' name='date' id='date'>
                <script> //Set
                        var currentDate = new Date();
                        currentDate.setHours(currentDate.getHours() + 8);
                        // Format the date in YYYY-MM-DD format
                        var formattedDate = currentDate.toISOString().split('T')[0];
            
                        document.getElementById('date').value = formattedDate;
                </script>
            </td>
        </tr>
        <tr><th><button type='submit'>Submit</button></th></tr>
    </table>
    </form></div>
    ";
};
function showTable($query, $header) {
    
    // Execute the query
    $result = dbConnect($query);
    
    echo "<div class='divSection'>";
    echo "$header<br>";
    
    // Check if the query was successful and if any matching records were found
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table class='result'>";
        echo "<tr>";
        echo "<th>Book ID</th>";
        echo "<th>Title</th>";
        echo "<th>Author</th>";
        echo "<th>Publisher</th>";
        echo "<th>Publication Year</th>";
        echo "<th>ISBN</th>";
        echo "<th>Availability</th>";
        echo "<th>Links</th>";
        echo "</tr>";
        echo "

        ";
        // Generate the table rows with the matching records
        while ($row = mysqli_fetch_assoc($result)) {
            //get borrower name in case of any 
            $book_id = $row['book_id'];
            $borrower = "";
            if ($row['available'] == 0) {
                $borrowQuery = "SELECT borrower_name FROM borrow WHERE book_id = $book_id AND return_date = '0000-00-00'";
                $borrowResult = dbConnect($borrowQuery);
                if ($borrowResult && mysqli_num_rows($borrowResult) > 0) $borrower = (mysqli_fetch_assoc($borrowResult))['borrower_name']; 
            }
            if (!isset($_GET['borrowBookId'])) $borrowBookId = 0;
            else $borrowBookId = $_GET['borrowBookId'];
            echo "\n<tr>";
            echo "<td>";
            echo $book_id . "</td>";
            
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['author'] . "</td>";
            echo "<td>" . $row['publisher'] . "</td>";
            echo "<td>" . $row['publication_year'] . "</td>";
            echo "<td>" . $row['isbn'] . "</td>";
            echo "<td>" . ($row['available'] == 1 ? "Available" : 
            "Unavailable -- Borrowed by $borrower") 
            
            . "</td>";
            //Auto generate link for book ID.
            echo "\n<td>";
            echo 
            "<button onclick=\"modifyBookId($book_id, 'borrow')\"" . ($row['available'] == 0 ? "disabled" : "") . ">Borrow</button>";

            echo "<button onclick=\"modifyBookId($book_id, 'return')\"" . ($row['available'] == 0 ? "" : " disabled") .
            ">Return</button>";
            echo "</td></tr>";
        }
        echo "</table>";

        //Links generation
        echo "

        ";

    } else {
        // No matching records found
        echo "No results found.";
    }
    echo "</div>";
} 
    

echo "
<script>
function modifyQueryString(paramName, paramValue) {
    const url = new URL(window.location.href);
    const searchParams = new URLSearchParams(url.search);
    
    if (paramName === 'returnBookId') {
        searchParams.delete('borrowBookId');
    } else if (paramName === 'borrowBookId') {
        searchParams.delete('returnBookId');
    }

    searchParams.set(paramName, paramValue);
    
    url.search = searchParams.toString();
    window.location.href = url.toString();
}

function modifyBookId(bookId, action) {
    modifyQueryString(action + 'BookId', bookId);
}
</script>
";
?>