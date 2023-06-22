<?php 

function dbQueryCount($query) {
    // Connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'dblibrary');
    
    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Execute the query
    $result = mysqli_query($conn, $query);    
    return mysqli_num_rows($result);
    // Close the database connection
    mysqli_close($conn);    
}

function dbConnect($query) {
    //Return true if successfully query.
    $conn = mysqli_connect('localhost', 'root', '', 'dblibrary');
    
    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Execute the query
    $result = mysqli_query($conn, $query);
    
    // Close the database connection
    mysqli_close($conn);

    return $result;
}
?>