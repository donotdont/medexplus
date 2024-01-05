<!DOCTYPE html>
<html>
<head>
<title>Database Table Design</title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php



//Your MySQL connection details.
define('MYSQL_SERVER', 'localhost');
define('MYSQL_DATABASE_NAME', 'dev-medexplus');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');

//Instantiate the PDO object and connect to MySQL.
$pdo = new PDO(
        'mysql:host=' . MYSQL_SERVER . ';dbname=' . MYSQL_DATABASE_NAME, 
        MYSQL_USERNAME, 
        MYSQL_PASSWORD
);


     $query = $pdo->query('SHOW TABLES');
     $tables = $query->fetchAll(PDO::FETCH_COLUMN);
	 
foreach($tables as $idt => $table){

//Run a DESCRIBE query with the PDO object.
//The SQL statement is: DESCRIBE [INSERT TABLE NAME]
$statement = $pdo->query('DESCRIBE ' . $table);

//Fetch the result.
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

//The result will be an array of arrays,
//with each array containing information about the columns.
//echo '<pre>';
//var_dump($table);
//var_dump($result);

//Loop through the result and print the column details.


echo '<h3>'.$table.'</h3>
<table class="table table-bordered">
      <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Type</th>
      <th scope="col">NULL?</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>';
  
  foreach($result as $column){
    //echo $column['Field'] . ' - ' . $column['Type'], '<br>';
  echo '<tr>
      <td>'.$column['Field'].'</td>
      <td>'.$column['Type'].'</td>
      <td>'.$column['Null'].'</td>
      <td>'.ucwords(str_replace("_", " ", $column['Field'])).'</td>
    </tr>';
	}
  echo '</tbody></table>';
}
?>

<!--<h3>category</h3>
<table class="table table-bordered">
      <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Type</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>id_category</td>
      <td>int(11)</td>
      <td>Category ID</td>
    </tr>
    <tr>
      <td>category_name</td>
      <td>varchar(255)</td>
      <td>Category Name</td>
    </tr>
  </tbody>
  </table>-->
  
</body>
</html>