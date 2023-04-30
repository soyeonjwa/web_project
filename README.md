# web_project

데이트 코스 추천 웹 사이트(date With ME)
---

웹 사이트의 기능
----
사용자가 데이트 하고자하는 역을 선택했을 때, 3가지 데이트 코스를 추천해 줍니다. 데이트 코스는 카페-식당-문화시설
순으로 추천해주며 카페는 해당역에서 가까운 카페 중 평점이 높은 순으로, 식당은 선택된 카페의 반경 300m안에 있는
식당 중에 평점이 젤 높은 것으로 선정됩니다. 문화시설은 식당에서 반경 300m안에 있는 문화시설 중 랜덤으로 하나를 추천해 줍니다. 평점은 카카오맵과 망고 플레이트에 있는 평점을 기준으로 했습니다.

데이트 코스를 정하기 어려운 커플들을 위해 데이트 코스를 정하는 수고를 조금이나마 덜어주기 위해 이 사이트를 개발하게 되었습니다.

전체 프로그램 구조
---
<img width="690" alt="스크린샷 2023-05-01 오전 2 24 17" src="https://user-images.githubusercontent.com/81522548/235367198-4a8975e2-ab09-4bae-bcef-95ec75d89c6b.png">

구현 기간
----
2021.11~2021.12

구현
---
crawling.php: 사용자가 선택한 역 정보를 기반으로 카카오맵API와 망고플레이트의 HTML에서 평점과 위치를 기준으로 카페 정보, 식당 정보, 문화시설 정보를 크롤링 해온다. 크롤링한 정보를 Mysql에 json형식으로 넘겨주고 이를 저장한다.

Query.html: 브라우저 화면을 구성하고 사용자의 입력을 받아 서버에게 전달한다. (html, CSS, javascript를 이용했다.)

Search.php: 사용자의 입력을 바탕으로 DB에 저장된 정보를 꺼내와 json형식의 데이터로 만든다.

Server.php: Query.html에서 post로 넘겨준 정보를 바탕으로 화면에 표시할 태그들을 구성하고 ShowResult.html을 호출해 화면을 구성한다.

ShowResult.html: Server.php에서 구성한 태그를 바탕으로 ShowResult.html에 구현해 놓은 CSS로 화면을 스타일링한다.

동작 화면
---
1) 크롬에서의 동작화면

<img width="636" alt="스크린샷 2023-05-01 오전 2 32 04" src="https://user-images.githubusercontent.com/81522548/235367516-31082ec3-07ae-490f-b6d1-7ac56491c19c.png">

2) 사파리에서의 동작 화면

<img width="677" alt="스크린샷 2023-05-01 오전 2 32 10" src="https://user-images.githubusercontent.com/81522548/235367531-2e57e2c3-4bb1-43a8-b451-47a4e14f4418.png">

3) 핸드폰에서 동작 화면

<img width="392" alt="스크린샷 2023-05-01 오전 2 32 18" src="https://user-images.githubusercontent.com/81522548/235367540-1d8ab1e0-fd18-4caa-91d1-58716e1cf797.png">
