# Phone Verification Api Endpoint

More info >> https://www.miniorange.com/step-by-step-guide-to-set-up-otp-verification

## Usage

- register and get 10 free SMS https://login.xecurify.com/moas/login
- set your keys in index.php from https://login.xecurify.com/moas/admin/customer/customerpreferences

```index.php
$customerKey = "888888";
$apiKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
```

GET index.php?phone=+380123456789

take txId from response and token from SMS

GET index.php?txId=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX&token=8888888