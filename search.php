
<!DOCTYPE html>
<html>
<head>
    <title>
        Edit Book Information
    </title>
    <style>
        table {
            border-collapse: collapse;
        }
        table.result td, table.result th {
            border: 1px solid black;
        }
        #addBook {
            display: none;
        }
        #editBook {
            display: block;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet"  href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--
    !-->
    <script>
        function toggleAddBookForm() {
            var form = document.getElementById("addBook");
            var otherForm = document.getElementById("editBook");
            if (form.style.display === "none" || form.style.display == "") {
                form.style.display = "block";
                otherForm.style.display = "none";
            } else {
                form.style.display = "none";
            }
        }
        function toggleEditBookForm() {
            var form = document.getElementById("editBook");
            var otherForm = document.getElementById("addBook");
            if (form.style.display === "block" || form.style.display == "") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
                otherForm.style.display = "none";
            }
        }

		function htmlScroll(){
			var top = document.body.scrollTop || document.documentElement.scrollTop;
			if(elFix.data_top<top){
				elFix.style.position='fixed';
				elFix.style.top=1;
				elFix.style.left=elFix.data_left;
			}else{
				elFix.style.position='static';
			}
		}
		function htmlPosition(obj){
			var o=obj;
			var t=o.offsetTop;
			var l=o.offsetLeft;
			while(o=o.offsetParent){
				t += o.offsetTop;
				l += o.offsetLeft;
			}
			obj.data_top=t;
			obj.data_left=l;
		}
		var oldHtmlWidth=document.documentElement.offsetWidth;
		window.onresize=function(){
			var newHtmlWidth=document.documentElement.offsetWidth;
			if(oldHtmlWidth==newHtmlWidth){
				return;
			}
			oldHtmlWidth=newHtmlWidth;
			elFix.style.position='static';
			htmlPosition(elFix);
			htmlScroll();
		}
		window.onscroll=htmlScroll;
		
		var elFix=document.getElementById('divSearch');
		htmlPosition(elFix);
	</script>
</head>

<body>
<!-- style="margin:0px auto;line-height:23px; padding: 10px;  background-attachment: scroll; background-image: url('use1.jpg');">    
!-->
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

        <div id="divSearch" class="divSection">
            
        <form action="search.php" method="get">
            <!-- Search Table Content -->
            <table style="text-align: left;">
            <tr>
                <th colspan="3"><h1>Edit Book Information</h1></th>
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



<?php

require 'dbQuery.php';


if (isset($_GET["search_term"]) && isset($_GET["search_type"])) {
    // Retrieve the selected field and search term values from the form submission
    $searchTerm = $_GET["search_term"];
    $searchType = $_GET["search_type"];
    
    $headerString = ($searchTerm == "" ? "Displaying all books" : "Displaying all $searchType containing \"$searchTerm\":");
    showTable("SELECT * FROM books WHERE $searchType LIKE '%$searchTerm%'", $headerString);

}
else {
    // Default: Select all if not set
    showTable("SELECT * FROM books", "Displaying all books.");
}
if (isset($_GET["editBookId"]) && preg_match('/\d+/', $_GET['editBookId'])) {
    //edit books section
    $editBookId = $_GET['editBookId'];
    $editQuery = "SELECT * FROM books WHERE book_id = $editBookId";
    $result = dbConnect($editQuery);
    echo "<button onclick='toggleEditBookForm()'>Edit Book</button>";
    echo "<form id='editBook' class='edit' action='editBook.php' method='post'>";
    echo "
        <p>Edit Book Details</p>
        <p>Leave the field empty if the information is unchanged.
        <table style='text-align: left;'>
        <tr>
            <th><label for='title'>ID:</label></th>
            <td><input style='background-color: lightgrey;' type='text' name='id' readonly value=$editBookId></td>
        </tr>
        <tr>
            <th><label for='title'>Title:</label></th>
            <td><input type='text' name='title'></td>
        </tr>
        <tr>
            <th><label for='author'>Author:</label></th>
            <td><input type='text' name='author'></td>
        </tr>
        <tr>
            <th><label for='publisher'>Publisher:</label></th>
            <td><input type='text' name='publisher'></td>
        </tr>
        <tr>
            <th><label for='publication_year'>Publication Year:</label></th>
            <td><input type='text' name='publication_year'></td>
        </tr>
        <tr>
            <th><label for='isbn'>ISBN:</label></th>
            <td><input type='text' name='isbn'></td>
        </tr>
        <tr>
            <td><input type='submit' value='Submit'> <input type='reset'></td>
        </tr>
    </table>
    </form>";
}
function showTable($query, $header) {
    // Execute the query
    $result = dbConnect($query);
    if (!isset($_GET['editBookId'])) $editBookId = 0;
    else $editBookId = $_GET['editBookId'];
    // Check if the query was successful and if any matching records were found
    
    echo "<div id='divResult' class='divSection'>";
    echo "$header<br>";
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table class='result'>";
        echo "<tr>";
        echo "<th>Book ID</th>";
        echo "<th>Title</th>";
        echo "<th>Author</th>";
        echo "<th>Publisher</th>";
        echo "<th>Publication Year</th>";
        echo "<th>ISBN</th>";
        echo "<th>Links</th>";
        echo "</tr>";
        
        // Generate the table rows with the matching records
        while ($row = mysqli_fetch_assoc($result)) {
            $book_id = $row['book_id'];
            echo "<tr>";
            echo "<td>";
            echo $book_id;
            
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['author'] . "</td>";
            echo "<td>" . $row['publisher'] . "</td>";
            echo "<td>" . $row['publication_year'] . "</td>";
            echo "<td>" . $row['isbn'] . "</td>";
            //Auto generate link for book ID.
            echo "<td>";
            echo "<button onclick='editBook($book_id)'" . ($editBookId == $book_id ? "disabled" : "") . ">Edit Details</button>";
            echo "<button onclick='deleteBook($book_id)'>Delete</button>";
            echo "</tr>";
        }
        echo "</table>";

        echo "
        <script>
        function modifyQueryString(paramName, paramValue) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);
        
            searchParams.set(paramName, paramValue);
            
            url.search = searchParams.toString();
            window.location.href = url.toString();
        }

        function editBook(editBookId) {
            modifyQueryString('editBookId', editBookId);
        }

        function deleteBook(book_id) {
            if (confirm('Are you sure you want to delete this book?')) 
            {
                url = 'deleteBook.php?book_id=' + book_id;
                window.location.href = url;
            } 
            else {
                window.location.href = 'search.php';
            }
        }
        </script>
        ";
    }
    else {
        echo "No results found.";
    }
    echo "</div>";
    echo "<div id='divAction' class='divSection'><button onclick='toggleAddBookForm()'>Add Book</button>";
}

?>



<form id="addBook" action="addBook.php" method="post">
    <p>Add Book Details</p>
    <table style="text-align: left;">
        <tr>
            <th>
                <label for="title">Title:</label>
            </th>
            <td>
                <input type="text" name="title" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="author">Author:</label>
            </th>
            <td>
                <input type="text" name="author" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="publisher">Publisher:</label>
            </th>
            <td>
                <input type="text" name="publisher" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="publication_year">Publication Year:</label>
            </th>
            <td>
                <input type="" size=4 name="publication_year" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="isbn">ISBN:</label>
            </th>
            <td>
                <input type="text" name="isbn" required>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Submit"> <input type="reset">
            </td>
        </tr>
    </table>
</form>

</html>