
## Getting started

### 테스트 환경 세팅

- 재생 테스트용 플레이어 웹페이지가 로컬호스트(http://localhost)가 아닌 경우, 해당 URL에 HTTPS 설정이 필수입니다. (테스트용 웹 서버에 SSL/TLS 적용 필요)
- PHP Version 7.3 or later.
- TokenSample 폴더는 토큰생성 PHP 샘플 소스 입니다(drm-token-sample-php). 

##### PHP.ini Dynamic Extensions
- curl
- openssl 

### Config 세팅
- [Config.php](../src/Config/Config.php)
- 샘플 프로젝트를 실행하려면 아래와 같은 값들을 설정해야 합니다.

- siteId=> {PallyCon Site Key}
- siteKey=> {PallyCon Access Key}
- siteid=> {PallyCon Site ID}
- license_url=> https://license.pallycon.com/ri/licenseManager.do



### 응답 유형에 대한 옵션

PallyCon 라이선스 서버에서 Proxy 서버에 보내줄 라이선스 응답의 유형과 Proxy 서버에서 클라이언트에 보내줄 응답의 유형을 다음과 같이 설정할 수 있습니다.

```
token_res_format=>[original|custom]
proxy_response_format=>[original|custom]
```

- token_res_format : PallyCon 라이선스 서버의 license response 유형 설정
    - original: 기본적인 라이선스 정보만 응답
    - custom: 라이선스 정보와 Device ID 등의 추가 정보가 포함된 JSON type으로 응답

- proxy_response_format : proxy 서버에서 클라이언트에 전송할 license response 유형 설정
    - original: 기본적인 라이선스 정보만 응답
    - custom: 추가 정보가 포함된 JSON type으로 응답. 해당 응답으로 DRM 콘텐츠를 재생하기 위해서는 클라이언트에서 추가로 응답을 파싱 처리하는 기능이 개발되어야 합니다.
    
    
    
## 샘플 프로젝트 기본 설정

1. url : http://localhost/{base_path}/proxy.php?drmType={drmType} 
    - drmType : fairplay, playready, widevine  
2. cid : test  
3. userId : proxySample  
4. license Rule : 라이선스 만료 시간 3600초
5. custom header name : sample-data


## TODO

전달 받은 sample-data header를 이용한 테스트를 위해서는 `createPallyConCustomdata` 메소드의 `TODO` 사항들을 업데이트해야 합니다.

[PHP](../src/Service/ProxyService.php)  

***

https://pallycon.com | cbiz@inka.co.kr

Copyright 2021 INKA Entworks. All Rights Reserved.

    
