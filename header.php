<?php 	date_default_timezone_set('America/La_Paz');
	$fecha_junto				= date("Ymd"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=<? echo $fecha_junto?>" media="screen"></link>

<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<script type="text/javascript" src="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=<? echo $fecha_junto?>"></script>
<script language="javascript" src="jquery.min.js"></script>


<title>SIIM WEB</title>
<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}

.panel {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>

<style>
.div_exito {
  padding: 5px;
  background-color: #cfffd4;
  color: black;
	font-size: 15px;
}

.div_error {
  padding: 5px;
  background-color: #f49e9e;
  color: black;
	font-size: 15px;
}

.closebtn {
  margin-left: 15px;
  color: black;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: blue;
}
</style>

<style type="text/css">
	<!--
	.Estilo25 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 18px;
		font-weight: bold;
		color: #003399;
	}
	.Estilo26 {
		color: #000000;
		font-size: 16px;
	}
	.Estilo30 {font-size: 16px; color: #003399;}
	.Estilo31 {font-size: 16px}
	.Estilo32 {font-size: 16; }
	.Estilo33 {font-size: 14px; color: #003399; }
	.Estilo4 {color: #0033CC}
	-->
</style>
</head>
