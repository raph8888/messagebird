### MessageBird API

**API url:** http://raph-web.eu/messagebird/public/api/v1/MessageBirdHandler.php

- Sends text message.
- Request must be POST
- Content type must be: application\/json
  - Example:
    - {"recipient":"0629058449","originator":"MessageBird","message":"This is a test message."}
- Recipent, originator and message are required values.
- Don't try anything funny.