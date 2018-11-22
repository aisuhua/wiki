## Cross-Origin Resource Sharing (CORS)

### 打开网页

Request

```http
GET /nginx_cors_client.html HTTP/1.1
Host: demo.aisuhua.com
Connection: keep-alive
Pragma: no-cache
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Encoding: gzip, deflate, sdch
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6

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

### 浏览器发起 OPTIONS 预检 

Request

```sh
OPTIONS /nginx_cors_server.php HTTP/1.1
Host: foo.aisuhua.com
Connection: keep-alive
Pragma: no-cache
Cache-Control: no-cache
Access-Control-Request-Method: POST
Origin: http://demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36
Access-Control-Request-Headers: content-type
Accept: */*
Referer: http://demo.aisuhua.com/nginx_cors_client.html
Accept-Encoding: gzip, deflate, sdch
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6

HTTP/1.1 204 No Content
Server: nginx/1.14.1
Date: Thu, 22 Nov 2018 03:08:40 GMT
Connection: keep-alive
X-Powered-By: aisuhua
X-Server-Name: HN1_wp-web1
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Max-Age: 86400
Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With
X-LB-Name: HN1_lb1
```

针对「非安全」的跨域请求，浏览器会首先自动发起 OPTIONS 预检。

### 浏览器正式发起 POST 请求

```http
POST /nginx_cors_server.php HTTP/1.1
Host: foo.aisuhua.com
Connection: keep-alive
Content-Length: 0
Pragma: no-cache
Cache-Control: no-cache
Origin: http://demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36
Content-Type: application/xml
Accept: */*
Referer: http://demo.aisuhua.com/nginx_cors_client.html
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6
Cookie: ___rl__test__cookies=1542245476890

HTTP/1.1 200 OK
Server: nginx/1.14.1
Date: Thu, 22 Nov 2018 03:08:40 GMT
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: aisuhua
X-Server-Name: HN1_wp-web1
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
X-LB-Name: HN1_lb1
Content-Encoding: gzip

suhua
```

## 截图解释

![图片alt](img/cors_example.png '图片title')
