<html>
<head>
<style>

	table{
		border-collapse:collapse;
		border-spacing: 0;	
	}
	
	th{		
		
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		height:25px;
		font-size:11px;
		text-align:center;
		vertical-align:middle;
		color:#666;
		font-weight:bold;
		border:solid #eceaea thin;
		border-bottom:none;
		background-color:#CFC;
		
	}
	
	td{
	
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:11px;
		height:20px;
		color: #666;
		border:solid #eceaea thin;
		border-top:none;

	}
	.div_title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:14px;
		text-align:left;
		color:#666;
		margin-bottom:10px;
	}
</style>
</head>
<title>Report Summary Test Result Periode</title>
<body>
	<div class="div_title"><u>Report Summary Test Periode: <?=$_GET['datefrom'];?> s/d <?=$_GET['dateto'];?></u></div>
    <br>
<?php
	if (!$empty) {
			echo "Data not found..!!!";
			die;
	}

?>		