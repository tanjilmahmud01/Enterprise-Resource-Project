<?php
echo "<meta charset = 'UTF-8'>";
	echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
	 echo "<link href='https://fonts.googleapis.com/css2?family=Josefin+Sans:ital@1&display=swap' rel='stylesheet'>";
	 echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'>";
echo"<link rel='stylesheet' href='allcss.css'>";
	
session_start();
	
//attempt mysql server conncetion with default setting
	$link = mysqli_connect("localhost", "root", "", "emp_demo_02");
	
	//check connection
	if($link === false){
		die("ERROR: Could not connect".mysqli_connect_error());
	}
	
	$selected_option = mysqli_real_escape_string($link, $_REQUEST["added_product"]);
	$amount = mysqli_real_escape_string($link, $_REQUEST["quantity"]);
	
	

	$salesid;
	if (!empty($_SESSION['saleID'])) {
    $salesid = $_SESSION['saleID'];
	}
	

	
		$productid;
	
	$sql1 = "SELECT Product_id FROM product WHERE Product_name='$selected_option'"; //sql

		if($result = mysqli_query($link, $sql1)){
		 if(mysqli_num_rows($result) > 0){
			 while($row = mysqli_fetch_array($result)){
				 
				 $productid = $row[0];
					 
				 }
			 }
		 }
		 
		// to inventory table
		$stock;
		
		$sql2 = "SELECT current_stock FROM inventory WHERE Product_id='$productid'"; //sql

		if($result = mysqli_query($link, $sql2)){
		 if(mysqli_num_rows($result) > 0){
			 while($row = mysqli_fetch_array($result)){
				 
				 $stock = $row[0];
					 
				 }
			 }
		 }
	
		
		//check if the item that was selected is already in sale details table
		$sqltest = "SELECT product_id FROM sale_details WHERE sale_id = '$salesid' AND product_id = '$productid'";
		if($resulttest = mysqli_query($link, $sqltest)){
			if(mysqli_num_rows($resulttest)> 0){
				
				
				// if amount 0 as then the item will be deleted from the sale_details table
				if($amount == 0){

					$sqltest3 = "DELETE FROM sale_details WHERE sale_id = '$salesid' AND product_id = '$productid'";
					mysqli_query($link, $sqltest3);
					
				echo"<div class='text-center'>";
				echo "<br>";
				echo "<br>";
				echo "<a href='add_product_sale.php' class='btn btn-primary'>Add more item</a>";
				echo "<br>";
				echo "<br>";
		
				echo "<a href='invoice-db.php' class='btn btn-primary'>Generate bill</a>";
				echo "<br>";
				echo "</div>";	
				}
				
				//normal update of the amount
				else if($amount > 0){
				$sqltest2 = "UPDATE sale_details SET quantity='$amount' WHERE sale_id = '$salesid' AND product_id = '$productid'";
				mysqli_query($link, $sqltest2);
				
				
				echo"<div class='text-center'>";
				echo "<br>";
				echo "<br>";
				echo "<a href='add_product_sale.php' class='btn btn-primary'>Add more item</a>";
				echo "<br>";
				echo "<br>";
				
				echo "<a href='invoice-db.php' class='btn btn-primary'>Generate bill</a>";
				echo "<br>";
				echo "</div>";	
				}
			}
			
			else{
		
		//check whether quantity is greater than available stock or negative input 
		if(($amount>$stock) ||($amount<0)){
			
			echo"<div class='text-center'>";
			echo "<br>";
			echo "<br>";

			echo "<a href='add_product_sale.php' class='btn btn-danger'>Quantity is more than stock. Please try again</a>";
			echo "</div>";	
		}
		//normal case
		else{
			//add the product id and quantity in sales details table
			$sql3 = "INSERT INTO sale_details (sale_id,product_id,quantity) VALUES ('$salesid','$productid', '$amount')";
			if(mysqli_query($link, $sql3)){
				
				
				echo"<div class='text-center'>";
				echo "<br>";
				echo "<br>";
				echo "<a href='add_product_sale.php' class='btn btn-primary'>Add more item</a>";
				echo "<br>";
				echo "<br>";
				
				echo "<a href='invoice-db.php' class='btn btn-primary'>Generate bill</a>";
				echo "<br>";
				echo "</div>";	
				
			}
		}
		
			}
		
		}
		
	?>	
	
	
	
	
<?php
echo "<meta charset = 'UTF-8'>";
	echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
	 echo "<link href='https://fonts.googleapis.com/css2?family=Josefin+Sans:ital@1&display=swap' rel='stylesheet'>";
	 echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'>";
//echo"<link rel='stylesheet' href='allcss.css'>";
	echo "<head>
    <title>Genarate Bill</title>
    </head>";
//session_start();
//attempt mysql server conncetion with default setting
	$link = mysqli_connect("localhost", "root", "", "emp_demo_02");
	
	//check connection
	if($link === false){
		die("ERROR: Could not connect".mysqli_connect_error());
	}
	
	$salesid;
	if (!empty($_SESSION['saleID'])) {
    $salesid = $_SESSION['saleID'];
	}
	
	if (!empty($_SESSION['Username'])) {
    $soldby = $_SESSION['Username'];
	}
	
	//inventory update
	
	//attempt mysql server conncetion with default setting
	$link = mysqli_connect("localhost", "root", "", "emp_demo_02");
	
	//check connection
	if($link === false){
		die("ERROR: Could not connect".mysqli_connect_error());
	}
	
	$sql1 = "SELECT product_id, quantity FROM sale_details WHERE sale_id = '$salesid'"; //sql
	
		if($result = mysqli_query($link, $sql1)){
		 if(mysqli_num_rows($result) > 0){
			 while($row = mysqli_fetch_array($result)){
				 // update from inventory table
				
				 
				 $prodid = $row[0];
				 $amount = $row[1];
				 
				 //.................................
				 $updated_quantity;
				 
				 $sql2 = "SELECT current_stock FROM inventory WHERE Product_id = '$prodid'"; //sql
				 if($result2 = mysqli_query($link, $sql2)){
					if(mysqli_num_rows($result2) > 0){
						while($row2 = mysqli_fetch_array($result2)){
							$updated_quantity = $row2[0];
								}
							}
				 }
				 //......................................
				 			 
				 $sql3 = "UPDATE inventory SET current_stock = $updated_quantity-$amount WHERE Product_id=$prodid";
						if($result3 = mysqli_query($link, $sql3)){
							
				}
			 }
			 
		 }
	}
		
	
	
	//bill show part
	echo"<div class='text-center'>";
		echo "<br>";
	echo "Sale ID: ";
	echo $salesid;
	echo "<br>";
	
	
	$sql4 = "SELECT Sale_date, customer_name, phone FROM customer,sale WHERE customer.customer_id = sale.Customer_id AND sale.Sale_id= $salesid"; //sql
	
		if($result4 = mysqli_query($link, $sql4)){
		 if(mysqli_num_rows($result4) > 0){
			 while($row4 = mysqli_fetch_array($result4)){
				 echo "Date: ";
				 echo $row4[0];
				 echo "<br>";
				 echo "Customer Name: ";
				 echo $row4[1];
				 echo "<br>";
				 echo "Customer phone: ";
				 echo $row4[2];
				 echo "<br>";
			 }
		 }
		}
	$sql5 = "SELECT Emp_Name FROM employee,sale WHERE employee.Emp_ID = sale.Sold_by AND sale.Sale_id= $salesid"; //sql
	
		if($result5 = mysqli_query($link, $sql5)){
		 if(mysqli_num_rows($result5) > 0){
			 while($row5 = mysqli_fetch_array($result5)){
				 echo "Sold by: ";
				 echo $row5[0];
				 echo "<br>";
				 
			 }
		 }
		}
	
	//table generate
	 echo "<br>";
	  echo "<br>";
	  
	  $sum =0;
	  
	$sql6 = "SELECT Product_name,quantity,Unit_price,(quantity*Unit_price) AS amount FROM sale_details AS s,product AS p WHERE s.product_id=p.Product_id AND sale_id = $salesid"; 
	if($result6 = mysqli_query($link, $sql6)){
		if(mysqli_num_rows($result6) > 0){   
	//echo "<table style = 'border: 1px solid black' align='center' >";
	echo "<table style = 'border: 1px solid black' align='center' cellpadding='15''>";
	echo "<tr>"; 
	echo "<th style = 'border: 1px solid black;'>Product Name</th>"; 
	echo "<th style = 'border: 1px solid black;'>Quantity</th>";      
	echo "<th style = 'border: 1px solid black;'>Unit Price</th>";            
	echo "<th style = 'border: 1px solid black;'>Total</th>";        
	echo "</tr>";   
	while($row6 = mysqli_fetch_array($result6)){    
	echo "<tr>";   
	echo "<td style = 'border: 1px solid black;'>" . $row6[0] . "</td>";       
	echo "<td style = 'border: 1px solid black;'>" . $row6[1] . "</td>";       
	echo "<td style = 'border: 1px solid black;'>" . $row6[2] . "</td>";     
	echo "<td style = 'border: 1px solid black;'>" . $row6[3] . "</td>";   
	echo "</tr>";   
		$sum = $sum+$row6[3];
	}       
	echo "</table>"; 
		}
	}
	
	 echo "<br>";
	 echo "Grand Total: ";
	 echo $sum;
	 echo "<br>";
	 echo "<br>";
	 echo "<br>";
	 
	 					
?>		
		
			
		
		
		
		
	
