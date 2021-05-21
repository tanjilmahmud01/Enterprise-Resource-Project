<?php
	echo "<meta charset = 'UTF-8'>";
	 echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
	 echo "<link href='https://fonts.googleapis.com/css2?family=Josefin+Sans:ital@1&display=swap' rel='stylesheet'>";
	 echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'>";
     echo"<link rel='stylesheet' href='allcss.css'>";

	echo "<head>
    <title>Add Product</title>
    </head>";
	
	//attempt mysql server conncetion with default setting
	$link = mysqli_connect("localhost", "root", "", "emp_demo_02");
	
	//check connection
	if($link === false){
		die("ERROR: Could not connect".mysqli_connect_error());
	}
	
	
	$prodname = mysqli_real_escape_string($link, $_REQUEST["name"]);
	$unitprice = mysqli_real_escape_string($link, $_REQUEST["unit_price"]);
	$currentstock = mysqli_real_escape_string($link, $_REQUEST["current_stock"]);
	$minstock = mysqli_real_escape_string($link, $_REQUEST["min_stock"]);
	
	
	//insert into product table
	$sql1 = "INSERT INTO product (Product_name, Unit_price) VALUES ('$prodname', '$unitprice')";
	$result = mysqli_query($link, $sql1);
	
	
	//get the product id that was just inserted
	$prodid;
	
	$sql2 = " SELECT LAST_INSERT_ID() FROM product";
	
	if($result2 = mysqli_query($link, $sql2)){
		 if(mysqli_num_rows($result2) > 0){
			 while($row = mysqli_fetch_array($result2)){
				 
				 $prodid = $row[0];
			 }
		 }
	}
	
	
	// insert data in inventory table
	$sql3 = "INSERT INTO inventory (Product_id, current_stock, minimum_stock) VALUES ('$prodid', '$currentstock', '$minstock')";
	if($result3 = mysqli_query($link, $sql3)){
		echo "<section class='bg-primary'>
	<article class='py-5 text-center text-white'>
	 	<div>
	 		<h3 class='display-4 text-white'>Product added</h3>
             
	 	</div>
	</article>
</section>
";
		
		
		echo"<div align='center'>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<a href='inventory_dashboard.html' class='btn btn-warning text-center'>Go to inventory Dashboard</a>";
				echo "</div>";
	}
	
	
?>