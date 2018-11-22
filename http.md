## HTTP Basic authentication

URL

```sh
shell> curl https://username:password@www.example.com
```

Authorization

```sh
shell> curl -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t' https://www.example.com
```

> ZWxhc3RpYzoxMTUjMTE1Q29t = base64(username:password)

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)

# Cross-Origin Resource Sharing (CORS)

HTML

```html
// demo.aisuhua.com
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

OPTIONS 请求

```http
# Request
OPTIONS /nginx_cors_server.php HTTP/1.1
Host: foo.aisuhua.com
Access-Control-Request-Method: POST
Origin: http://demo.aisuhua.com
Access-Control-Request-Headers: content-type
Referer: http://demo.aisuhua.com/nginx_cors_client.html

# Response
HTTP/1.1 204 No Content
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Max-Age: 86400
Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With
```

POST 请求

```http
# foo.aisuhua.com
Access-Control-Allow-Origin:http://demo.aisuhua.com
Access-Control-Allow-Credentials:true
```


