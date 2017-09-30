### messagebird API

**API url:** http://raph-web.eu/messagebird/public/handler/api/v1/MessageBirdHandler.php

Sends text message to dutch numbers.

- Request must be POST
- Content type must be: application\/json
  - Example:
    - {"recipient":"0629058449","originator":"MessageBird","message":"This is a test message."}
- recipent, originator and message are required values.
- Don't try anything funny.