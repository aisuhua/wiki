## Cross-Origin Resource Sharing (CORS)

### GET

Request

```sh
curl 'http://demo.aisuhua.com/nginx_cors_client.html'
```

Response

```html
<html>
<body>
<script>
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
  
    xhr.open('post', 'http://foo.aisuhua.com/nginx_cors_server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/xml');
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.write(xhr.responseText);
        }
    };
</script>
</body>
</html>
```

### OPTIONS

Request

```sh
curl 'http://foo.aisuhua.com/nginx_cors_server.php' \
-X OPTIONS \
-H 'Access-Control-Request-Method: POST' \
-H 'Access-Control-Request-Headers: content-type' \
-H 'Origin: http://demo.aisuhua.com' \
-H 'Referer: http://demo.aisuhua.com/nginx_cors_client.html' 
```

Response

```http
HTTP/1.1 204 No Content
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Max-Age: 86400
Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With
```

### POST

Request

```sh
curl 'http://foo.aisuhua.com/nginx_cors_server.php' \
-X POST \
-H 'Origin: http://demo.aisuhua.com' \
-H 'Content-Type: application/xml' \
-H 'Referer: http://demo.aisuhua.com/nginx_cors_client.html' \
-H 'Cookie: ___rl__test__cookies=1542245476890'

(empty body)
```

Response

```http
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true

suhua
```

## 截图解释

![图片alt](img/cors_example.png ''图片title'')