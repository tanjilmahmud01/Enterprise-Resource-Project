<?php
session_start();
$salesid;
	if (!empty($_SESSION['saleID'])) {
    $salesid = $_SESSION['saleID'];
	}

require('fpdf17/fpdf.php');

class PDF extends FPDF {
	function Header(){
		$this->SetFont('Arial','B',15);
		
		//dummy cell to put logo
		//$this->Cell(12,0,'',0,0);
		//is equivalent to:
		$this->Cell(12);
		
		//put logo
		$this->Image('logo-small.png',10,10,10);
		
		$this->Cell(100,10,'INVOICE',0,1);
		
		//dummy cell to give line spacing
		//$this->Cell(0,5,'',0,1);
		//is equivalent to:
		$this->Ln(5);
		
	}
	function Footer(){
		
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
				
		$this->SetFont('Arial','',8);
		
		//width = 0 means the cell is extended up to the right margin
		$this->Cell(0,10,'Page '.$this->PageNo()." / {pages}",0,0,'C');
	}
}






//db connection
$con = mysqli_connect('localhost','root','');
mysqli_select_db($con,'emp_demo_02');




$query = mysqli_query($con,"SELECT Sale_date, customer_name, phone FROM customer,sale WHERE customer.customer_id = sale.Customer_id AND sale.Sale_id= $salesid");
while($item = mysqli_fetch_array($query)){
	$var1= $item[0];
	$var2=$item['customer_name'];
	$var3=$item['phone'];
	
}

$query = mysqli_query($con,"SELECT * FROM customer,sale WHERE customer.customer_id = sale.Customer_id AND sale.Sale_id= $salesid");
while($item = mysqli_fetch_array($query)){
	
	$var4=$item[0];
}




//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$pdf = new PDF('P','mm','A4'); //use new class

//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');


$pdf->AddPage();

//Image( file name , x position , y position , width [optional] , height [optional] )
//$pdf->Image('',10,10,189);



//Cell(width , height , text , border , end line , [align] )

$pdf->Cell(130	,5,'My Company',0,0);
$pdf->Cell(59	,5,'',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(130	,5,'Neo-54, Block-C',0,0);
$pdf->Cell(59	,5,'',0,1);//end of line

$pdf->Cell(130	,5,'Khilgaon, Dhaka-1219',0,0);
$pdf->Cell(25	,5,'Date',0,0);
$pdf->Cell(34	,5,$var1,0,1);//end of line

$pdf->Cell(130	,5,'Phone: 01714386862',0,0);
$pdf->Cell(25	,5,'Invoice #',0,0);
$pdf->Cell(34	,5,$salesid,0,1);//end of line

$pdf->Cell(130	,5,'Fax: 01714386862',0,0);
$pdf->Cell(25	,5,'Customer ID',0,0);
$pdf->Cell(34	,5,$var4,0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//billing address
$pdf->Cell(100	,5,'Bill to',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(10	,5,'Name:',0,0);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(10	,5,$var2,0,1);


$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(10	,5,'Phone:',0,0);


$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(10	,5,$var3,0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(105	,5,'Description',1,0);
$pdf->Cell(25	,5,'Quantity',1,0);
$pdf->Cell(25	,5,'Unit Price',1,0);
$pdf->Cell(34	,5,'Amount',1,1);//end of line

$pdf->SetFont('Arial','',12);

//Numbers are right-aligned so we give 'R' after new line parameter

//items
$query = mysqli_query($con,"SELECT Product_name,quantity, Unit_price, (quantity*Unit_price) AS amount FROM sale_details AS s,product AS p WHERE s.product_id=p.Product_id AND sale_id = $salesid");
$tax = 0; //total tax
$amount = 0; //total amount

//display the items
while($item = mysqli_fetch_array($query)){
	$pdf->Cell(105	,5,$item['Product_name'],1,0);
	$pdf->Cell(25	,5,$item['quantity'],1,0);
	//add thousand separator using number_format function
	$pdf->Cell(25	,5,number_format($item['Unit_price']),1,0);
	$pdf->Cell(34	,5,number_format($item['amount']),1,1,'R');//end of line
	//accumulate tax and amount
	
	$amount += $item['amount'];
}

$tax = ($amount*0.10);

//summary
$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Subtotal',0,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,number_format($amount),1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Taxable',0,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,number_format($tax),1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Tax Rate',0,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,'10%',1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Total Due',0,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,number_format($amount + $tax),1,1,'R');//end of line





















$pdf->Output();
?>
