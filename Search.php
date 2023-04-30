<?php
$servername = "localhost";
$username = "cse20181354";
$password ="cse20181354";
$dbname = "db_cse20181354";

$conn = new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$sql="SELECT * from ".$_REQUEST["query"];

$result=$conn->query($sql);

$sub_arr_0=array();
$sub_arr_1=array();
$sub_arr_2=array();

$count=0;
while ($row = $result->fetch_assoc()) {
  if($count==0){
    $sub_arr_0=$row;
  }
  elseif($count==1){
    $sub_arr_1=$row;
  }
  elseif($count==2){
    $sub_arr_2=$row;
  }

  $count++;
  if($count==3) break;
}


$data=array(
  "first"=>$sub_arr_0,
  "second"=>$sub_arr_1,
  "third"=>$sub_arr_2
);

//header('HTTP/1.1 500 Internal Server Booboo');
header('Content-Type: application/json; charset=UTF-8');
$a=json_encode($data);
echo $a;

?>
