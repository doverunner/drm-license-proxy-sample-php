
## Getting started

### Test configuration

- If you test the sample player player page online(other than localhost), the page URL should be HTTPS. (SSL/TLS is required)
- PHP Version 7.3 or later.
- The TokenSample folder is a token creation PHP sample source(drm-token-sample-php). 

##### PHP.ini Dynamic Extensions
- curl
- openssl 

### Configuring Config.php
- [Config.php](../src/Config/Config.php)
- You need to configure the below values to run the sample project.


- siteId=> {PallyCon Site Key}
- siteKey=> {PallyCon Access Key}
- siteid=> {PallyCon Site ID}
- license_url=> https://license.pallycon.com/ri/licenseManager.do

### Options for Response types

You can set the type of license response that the PallyCon license server will send to the proxy server and the type of response that the proxy server will send to the client as follows.

```
token_res_format=>[original|json]
proxy_response_format=>[original|json]
```

- pallycon.token.response.format: Set the license response type of PallyCon license server
  - original: basic license information only (same as the response of v1.0 spec)
  - json: responds in JSON type with additional information such as Device ID

- pallycon.response.format: Set the type of license response to be sent from the proxy server to the client
  - original: basic license information only (same as the response of v1.0 spec)
  - json: response in JSON type with additional information. In order to play DRM content with the response, a function to parse the response additionally must be implemented on the client side.


### Notes
1. At the time of initial authentication, Widevine requests a license to obtain a Widevine certificate, download the certificate, and request a license.
2. NCG calls `mode=getserverinfo` to download a certificate for each device and requests a license.



## Default configuration of this sample

1. url : http://localhost/{base_path}/proxy.php?drmType={drmType} 
   - drmType : fairplay, playready, widevine, ncg  
2. cid : test  
3. userId : proxySample  
4. license Rule : license duration is 3600 seconds

## TODO

1. For testing, you need to update the `TODO` items in the `createPallyConCustomdata` method.

   - [PHP](../src/Service/ProxyService.php)
   - [Config](../src/Config/Config.php)


2. When the client (SDK, Browser) and proxy server connection, if `user_id` and `content_id` need to connection with the proxy server, the encryption method used by the company shall be applied and communicated.
- Different companies have different encryption methods, so we don't provide separate guides.


3. Specify the policy to be used using `new PallyConDrmTokenClient()`




***

https://pallycon.com | obiz@inka.co.kr

Copyright 2021 INKA Entworks. All Rights Reserved.
