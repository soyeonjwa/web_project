<?php
  error_reporting( E_ALL );
  ini_set( "display_errors", 1 );
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
define("cafe","CE7");
define("eat","FD6");//음식점
define("activity","CT1");//문화시설내용 별로면 AT4

include('simple_html_dom.php');

$subway_arr=["혜화역","이태원역","신촌역","홍대역","강남역","건대역","종로역","왕십리역","명동역"];


for($i=0;$i<100;$i++){
  foreach($subway_arr as $element){
    one_subway_procedure($element);
  }
  flush();
  sleep(86400);
}

//설명이랑 이미지 다운 받는건 아직 안함
function one_subway_procedure($subway){
  $cafe_info_full=array();
  $cafe_info_full=find_cafe($subway);

  $eat_info_full=array();

  foreach($cafe_info_full as $element){

    $arr=array();
    $arr=find_close_place($element,eat);

    $eat_info=array();
    $eat_info=count_grade($arr);

    array_push($eat_info_full,$eat_info[0]);

  }

  $activity_info_full=array();

  foreach($eat_info_full as $element){

    $arr=array();
    $arr=find_close_place($element,activity);

    $index=rand(0,2);
    array_push($activity_info_full,$arr[$index]);

  }

  for($i=0;$i<3;$i++){
    $cafe_info_full[$i]["img_url"]=find_img($subway,$cafe_info_full[$i]["place_name"]);
    $eat_info_full[$i]["img_url"]=find_img($subway,$eat_info_full[$i]["place_name"]);
    $activity_info_full[$i]["img_url"]=find_img($subway,$activity_info_full[$i]["place_name"]);
  }

  $table_name=subway_to_table_name($subway);///이걸로 넣으면 왜 안돼?

  db_insert($cafe_info_full,$eat_info_full,$activity_info_full,$table_name);//여기서 db 넣으면서 안될 확률 있어용

}


//역을 인자로 주면 역주변 카페의 평점 삼등까지의 카페를 반환한다.
function find_cafe($subway){
  $page_num=rand(1,3);
  $url="https://dapi.kakao.com/v2/local/search/keyword.json?query=".urlencode($subway."+카페")."&page=".urlencode($page_num);//페이지 다르게 해서 검색할 때마다 다른 정보 얻게 할 수 있겟다.

  $opts = array(
    'http'=>array(
      'method'=>'GET',
      'header'=>'Authorization: KakaoAK 9cefea93067e9d5f5c0af0b5aa51d0ea',
      'timeout'=>10
    )
  );
  $context=stream_context_create($opts);
  $json_cafe=json_decode(file_get_html($url,false,$context));


  //해당역 주변에 카페에 대한 정보를 카카오 맵 api를 이용하여 크롤링
  //그 정보들을 json_decode를 이용해 파싱

  $cafe_info_arr=array();
  foreach($json_cafe->documents as $element){
    array_push($cafe_info_arr,["place_name"=>$element->place_name,"address_name"=>$element->address_name
    ,"x"=>$element->x,"y"=>$element->y,"link"=>$element->place_url]);
  }
  //장소 이름과 주소에 대한 정보만 카페 정보를 저장하는 배열인 cafe_info_arr에 다중 배열로 저장

  return count_grade($cafe_info_arr);
}

//장소의 정보의 배열을 입력했을 때, 가까운 장소를 15군데 배열로 알려준다.(카페를 넣으면 식당을, 식당을 넣으면 엑티비티를)
function find_close_place($place_info,$place_category){

  $x=$place_info["x"];
  $y=$place_info["y"];

  $url="https://dapi.kakao.com/v2/local/search/category.json?category_group_code=".urlencode($place_category)
  ."&x=".urlencode($x)."&y=".urlencode($y)."&radius=".urlencode(300);

  $opts = array(
    'http'=>array(
      'method'=>'GET',
      'header'=>'Authorization: KakaoAK 9cefea93067e9d5f5c0af0b5aa51d0ea',
      'timeout'=>10
    )
  );
  $context=stream_context_create($opts);
  $json_close_place=json_decode(file_get_html($url,false,$context));

  $out=array();

  foreach($json_close_place->documents as $element){
    array_push($out,["place_name"=>$element->place_name,"address_name"=>$element->address_name,"x"=>$element->x,"y"=>$element->y,"link"=>$element->place_url]);
  }
  return $out;
}
//네이버로 바꿔서 줄글 정보도 같이 따오기
function find_img($subway,$place_name){
  $url="https://dapi.kakao.com/v2/search/image?query=".urlencode($subway." ".$place_name);
  $opts = array(
    'http'=>array(
      'method'=>'GET',
      'header'=>'Authorization: KakaoAK 9cefea93067e9d5f5c0af0b5aa51d0ea',
      'timeout'=>10
    )
  );
  $context=stream_context_create($opts);
  $json_img=json_decode(file_get_html($url,false,$context));

  return $json_img->documents[0]->thumbnail_url;
}



//주소와 장소명이 있는 배열을 인자로 받았을 때, 그 배열에 평점까지 추가-> 평점이 젤 높은 애 3개 배열로 반환
function count_grade($place_array){
  $arr_index=0;
  foreach($place_array as $arr){
  $url="https://www.mangoplate.com/search/".urlencode($arr["place_name"]);

  $opts = array(
    'http'=>array(
      'header'=>"User-Agent:Chrome/67.0.3396.99\r\n"
    )
  );

  $context = stream_context_create($opts);
  $dom_mangoplate = file_get_html($url, false, $context);
  //앞서 파악한 정보를 가지고 망고 플레이트에 검색하여 평점 크롤링(이부분에서 간단한 설명이랑 이미지도 크롤링 해야 할듯)

  $count=0;
  foreach($dom_mangoplate->find("div") as $element){

    if($element->class=="info"){

      $element2=$element->find('a')[0];
      $a=substr($element2->find('h2')[0]->innertext,0,-1);

      if($a === $arr["place_name"]){
          $b=$element->find('strong')[0]->innertext;
          if($b!==""){
            $place_array[$arr_index]["grade"]=$b;
            $count=1;
          }

          break;
      }
      elseif(substr($a,0,-5) === substr($arr["place_name"],0,-6)){
          $b=$element->find('strong')[0]->innertext;
          if($b!==""){
            $place_array[$arr_index]["grade"]=$b;
            $count=1;
          }
          break;
      }

    }

  }

  if($count==0){
    $place_array[$arr_index]["grade"]=0;
  }
  $arr_index++;


  }
  //평점 크롤링
  foreach ($place_array as $key => $value) {
      $sort[$key] = $value['grade'];
  }
  array_multisort($sort, SORT_DESC, $place_array);  //크롤링한 카페를 평점순으로 배치

  $slice=array();
  for($i=0;$i<3;$i++){
    array_push($slice,$place_array[$i]);
  }

  return $slice;
}
///db에 넣는 함수
function db_insert($cafe,$eat,$activity,$table_name){
  $servername = "localhost";
  $username = "cse20181354";
  $password ="cse20181354";
  $dbname = "db_cse20181354";

  $conn = new mysqli($servername,$username,$password,$dbname);

  if($conn->connect_error){
      die("Connection failed: ".$conn->connect_error);
  }
  $sql0="truncate ".$table_name;
  if($conn->query($sql0)==TRUE){
  }
  else{
      echo "<h1> db error $table_name $sql0 </h1>";
  }

  for($i=0;$i<3;$i++){
    $insert_string = "'".$cafe[$i]["place_name"]."','".$cafe[$i]["address_name"]."','".$cafe[$i]["grade"]."','".$cafe[$i]["link"]."',";
    $insert_string .= "'".$eat[$i]["place_name"]."','".$eat[$i]["address_name"]."','".$eat[$i]["grade"]."','".$eat[$i]["link"]."',";
    $insert_string .= "'".$activity[$i]["place_name"]."','".$activity[$i]["address_name"]."','".$activity[$i]["link"]."',";
    $insert_string .= "'".$cafe[$i]["img_url"]."','".$eat[$i]["img_url"]."','".$activity[$i]["img_url"]."'";
    $sql="INSERT INTO $table_name(cafe_name,cafe_address,cafe_grade,cafe_explain,eat_name,eat_address,eat_grade,eat_explain,activity_name,activity_address,activity_explain,img_url,img_url2,img_url3) VALUES($insert_string)";

    echo $sql;
    if($conn->query($sql)==TRUE){
    }
    else{
        echo "<h1> db error $table_name $sql </h1>";
    }
  }


}
function subway_to_table_name($subway){
  switch($subway){
    case "혜화역":
      return "HyeHwa";
    case "이태원역":
      return "ITaeWon";
    case "신촌역":
      return "Sinchon";
    case "홍대역":
      return "HongDae";
    case "강남역":
      return "GangNam";
    case "건대역":
      return "GunDae";
    case "종로역":
      return "JongRo";
    case "왕십리역":
      return "WangSimNi";
    case "명동역":
      return "MyungDong";
  }
}

 ?>
