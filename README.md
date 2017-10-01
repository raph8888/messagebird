### MessageBird Connector API

- **API url:** <http://raph-web.eu:2020>
- Sends text message using MessageBird's REST API
- Request must be POST
- Content type must be: application\/json
  - Example:
    - {"recipient":"0629058449","originator":"MessageBird","message":"This is a test message."}
- Recipent, originator and message are required values

### Usage
 
 - Use [Postman](https://www.getpostman.com/) to test the API and get the response
 - Access <http://www.raph-web.eu:4040/messagebird>
 
 
```php
<?php

    //api url
    $url = "http://www.raph-web.eu:2020";
    
    $data_string = json_encode($_POST);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
```
 