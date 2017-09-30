### MessageBird API

**API url:** <http://raph-web.eu:2020>

- Sends text message using test api key.
- Request must be POST
- Content type must be: application\/json
  - Example:
    - {"recipient":"0629058449","originator":"MessageBird","message":"This is a test message."}
- Recipent, originator and message are required values.