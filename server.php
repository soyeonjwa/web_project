<?php
echo "<h1><span id='title'>".table_name_to_subway($_POST["where"])."</span>주변 데이트 코스</h1>";
echo "<h3> 이미지를 클릭하면 해당 장소에 관한 카카오맵 검색 결과로 이동할 수 있어요!</h3>";
$category_arr=["cafe","eat","activity"];

for($i=1;$i<4;$i++){
  echo "<div id='table'>choice".$i."</div>";
  echo "<table id='info_list".$i.">";

  $place_info_arr=array();
  foreach($category_arr as $element){

    $str="info".$i."_".$element;
    $a=rtrim($_POST[$str],"|");

    $arr=explode("|",$a,5);

    $place_info_arr[$element]=$arr;

  }

  if($place_info_arr["activity"][3]==""||$place_info_arr["activity"][1]==""||$place_info_arr["activity"][0]==""){
    echo "<tr id='categoty'>";
    echo "<td>카페</td>";
    echo "<td>식당</td>";
    echo "</tr>";

    echo "<tr id='name'>";
    echo "<td>".$place_info_arr["cafe"][0]."</td>";
    echo "<td>".$place_info_arr["eat"][0]."</td>";
    echo "</tr>";

    echo "<tr id='grade'>";
    echo "<td>&starf;".$place_info_arr["cafe"][2]."</td>";
    echo "<td>&starf;".$place_info_arr["eat"][2]."</td>";
    echo "</tr>";

    echo "<tr id='img'>";
    echo "<td>"."<a href=".$place_info_arr["cafe"][3]."><img src=".$place_info_arr["cafe"][4].">"."</a></td>";
    echo "<td>"."<a href=".$place_info_arr["eat"][3]."><img src=".$place_info_arr["eat"][4].">"."</a></td>";
    echo "</tr>";

    echo "<tr id='address'>";
    echo "<td>".$place_info_arr["cafe"][1]."</td>";
    echo "<td>".$place_info_arr["eat"][1]."</td>";
    echo "</tr>";

    echo "</table>";
  }
  else{
    echo "<tr id='categoty'>";
    echo "<td>카페</td>";
    echo "<td>식당</td>";
    echo "<td>문화시설</td>";
    echo "</tr>";

    echo "<tr id='name'>";
    echo "<td>".$place_info_arr["cafe"][0]."</td>";
    echo "<td>".$place_info_arr["eat"][0]."</td>";
    echo "<td>".$place_info_arr["activity"][0]."</td>";
    echo "</tr>";

    echo "<tr id='grade'>";
    echo "<td>&starf;".$place_info_arr["cafe"][2]."</td>";
    echo "<td>&starf;".$place_info_arr["eat"][2]."</td>";
    echo "<td>정보 없음</td>";
    echo "</tr>";

    echo "<tr id='img'>";
    echo "<td>"."<a href=".$place_info_arr["cafe"][3]."><img src=".$place_info_arr["cafe"][4].">"."</a></td>";
    echo "<td>"."<a href=".$place_info_arr["eat"][3]."><img src=".$place_info_arr["eat"][4].">"."</a></td>";
    echo "<td>"."<a href=".$place_info_arr["activity"][2]."><img src=".$place_info_arr["activity"][3].">"."</a></td>";
    echo "</tr>";

    echo "<tr id='address'>";
    echo "<td>".$place_info_arr["cafe"][1]."</td>";
    echo "<td>".$place_info_arr["eat"][1]."</td>";
    echo "<td>".$place_info_arr["activity"][1]."</td>";
    echo "</tr>";

    echo "</table>";
    echo "<br></br>";
  }

}

include('simple_html_dom.php');
echo file_get_html("http://cspro.sogang.ac.kr/~cse20181354/ShowResult.html");

function table_name_to_subway($table_name){
  switch($table_name){
    case "HyeHwa":
      return"혜화역";
    case "ITaeWon":
      return "이태원역";
    case "Sinchon":
      return "신촌역";
    case "HongDae":
      return "홍대역";
    case "GangNam":
      return "강남역";
    case "GunDae":
      return "건대역";
    case "JongRo":
      return "종로역";
    case "WangSimNi":
      return "왕십리역";
    case "MyungDong":
      return "명동역";
  }
}

?>
