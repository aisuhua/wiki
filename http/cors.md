# Cross-Origin Resource Sharing (CORS)

跨域资源共享(CORS) 是一种机制，它使用额外的 HTTP 头来告诉浏览器  让运行在一个 origin (domain) 上的Web应用被准许访问来自不同源服务器上的指定的资源。当一个资源从与该资源本身所在的服务器不同的域或端口请求一个资源时，资源会发起一个跨域 HTTP 请求。

## 用户打开网页

```
GET /nginx_cors_client.html HTTP/1.1
Host: demo.aisuhua.com
Connection: keep-alive
Pragma: no-cache
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)
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

用户访问的是 demo.aisuhua.com 域名下的页面。

```http
http://demo.aisuhua.com/nginx_cors_client.html
```

该页面通过 Ajax 调用了 foo.aisuhua.com 域名下的接口，因此产生了跨域请求。

```js
xhr.open('post', 'http://foo.aisuhua.com/nginx_cors_server.php', true);
```

其中，Ajax 进行跨域请求时可以携带 Cookie 信息。

```js
xhr.withCredentials = true;
```

## 浏览器发起 OPTIONS 预检 


```
OPTIONS /nginx_cors_server.php HTTP/1.1
Host: foo.aisuhua.com
Connection: keep-alive
Pragma: no-cache
Cache-Control: no-cache
Access-Control-Request-Method: POST
Origin: http://demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)
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

针对「非安全」的跨域请求，浏览器会首先发起 OPTIONS 预检，预检请求是浏览器根据 CORS 机制自动发起的。

### 解析

浏览器请求时通过添加 `Origin` 报头，告知服务器本次是跨域请求。

```http
Origin: http://demo.aisuhua.com
```

服务器回应浏览器接受本次跨域请求。

```http
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Max-Age: 86400
Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With
```

其中，服务器告知浏览器可以将此 OPTIONS 请求结果缓存 1 天，从而避免了每次都发起 OPTIONS 请求。

```http
Access-Control-Max-Age: 86400
```

并且允许浏览器携带 Cookie 进行请求。

```http
Access-Control-Allow-Credentials: true
```

需要注意的是：当上面为 true 时，`Access-Control-Allow-Origin` 必须是具体的域名而不能是通配符 `*`。

```http
Access-Control-Allow-Origin: http://demo.aisuhua.com
```

## 浏览器正式发起 POST 请求

```
POST /nginx_cors_server.php HTTP/1.1
Host: foo.aisuhua.com
Connection: keep-alive
Content-Length: 0
Pragma: no-cache
Cache-Control: no-cache
Origin: http://demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)
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

服务器响应跨域请求时，须带上表示允许本次跨域的 HTTP 报头。

```http
Access-Control-Allow-Origin: http://demo.aisuhua.com
Access-Control-Allow-Credentials: true
```

## 在 Nginx 中实现

添加 cors.conf 文件

```nginx
set $cors '';
set $access_origin '';
set $access_credentials '';
set $access_methods '';
set $access_age '';
set $access_headers '';

# 允许形如 aisuhua.com、demo.aisuhua.com、foo.aisuhuarc.com 域名进行跨域请求
if ($http_origin ~ '^https?://(.*?\.)?(aisuhua\.com|aisuhuarc\.com)$') {
    set $cors 'true';
}

# 以下可以针对每种类型的请求单独处理
# if ($request_method = 'OPTIONS') {
#     set $cors "${cors}options";
# }
# if ($request_method = 'GET') {
#     set $cors "${cors}get";
# }
# if ($request_method = 'POST') {
#     set $cors "${cors}post";
# }

if ($cors = 'true') {
    set $access_origin "$http_origin";
    set $access_credentials 'true';
}

if ($request_method = 'OPTIONS') {
    set $access_methods 'GET, POST, PUT, DELETE, OPTIONS';
    set $access_age '86400';
    set $access_headers 'Origin, Content-Type, X-Requested-With';
    return 204;
}

add_header 'Access-Control-Allow-Origin' "$access_origin";
add_header 'Access-Control-Allow-Credentials' "$access_credentials";
add_header 'Access-Control-Allow-Methods' "$access_methods";
add_header 'Access-Control-Max-Age' "$access_age";
add_header 'Access-Control-Allow-Headers' "$access_headers";
```

cors.conf 使用示例

```nginx
server {
    listen 80;
    server_name demo.aisuhua.com;
    
    include /etc/nginx/snippets/cors.conf;
    
    root /www/web/demo;

    location / {
        try_files $uri $uri/ =404;
    }
}
```

## 参考文献

- [HTTP访问控制（CORS）](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Access_control_CORS)
- [前端常见跨域解决方案（全）](https://segmentfault.com/a/1190000011145364)
