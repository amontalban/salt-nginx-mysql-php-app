qli_connect('localhost','newuser','password','employees')
or die('Error connecting to MySQL server.');
?>

<html>
 <head>
 </head>
 <body>
 <h1>Employee Search Results</h1>
</body>
</html>

<?php

$query  = "SELECT * FROM employees WHERE gender='M';";
$query .= "SELECT * FROM employees WHERE birth_date='1965-02-01'";
$query .= "SELECT * FROM employees WHERE hire_date>'1990-01-01'";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_array($result);



/* execute multi query */
if ($db->multi_query($query)) {
    do {
        /* store first result set */
        if ($result = $db->store_result()) {
            while ($row = mysqli_fetch_array($result)) {
 echo $row['first_name'] . ' ,  ' . $row['last_name'] . ' Gender: ' . $row['gender'] . ' Birthdate: ' . $row['birth_date'] . ' Hire Date: ' . $row['hire_date'] .'<br />';
}
            $result->free();
        }
        /* print divider */
        if ($db->more_results()) {
            printf("-----------------\n");
        }
    } while ($db->next_result());
}

/* close connection */
$db->close();
?>

