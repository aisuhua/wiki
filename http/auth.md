# HTTP Basic authentication

## 帐号密码的传输方式

可写在地址栏。

```http
curl https://username:password@www.example.com
```

或放在 Authorization 报头。

```http
curl https://www.example.com -H 'Authorization: Basic base64(username:password)'
```

这两种方式的帐号密码都是明文传输，所以并不安全。

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)

## 在 PHP 中实现

```php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
}
```

- [用 PHP 进行 HTTP 认证](http://php.net/manual/zh/features.http-auth.php)

### 访问该页面

![Alt text](img/basic_auth.jpg?v=1)

服务器返回 `401` 状态码以及 `WWW-Authenticate` 报头，告知浏览器此页面需要认证信息。

```http
HTTP/1.1 401 Unauthorized
WWW-Authenticate: Basic realm="My Realm"
```

### 点击「取消」按钮

```
GET /auth.php HTTP/1.1
Host: demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:63.0) Gecko/20100101 Firefox/63.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2
Accept-Encoding: gzip, deflate
Connection: keep-alive
Upgrade-Insecure-Requests: 1
Pragma: no-cache
Cache-Control: no-cache

HTTP/1.1 401 Unauthorized
Server: nginx/1.14.1
Date: Fri, 23 Nov 2018 01:18:59 GMT
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: keep-alive
WWW-Authenticate: Basic realm="My Realm"
X-Powered-By: aisuhua
X-Server-Name: HN1_wp-web1
X-LB-Name: HN1_lb1

Text to send if user hits Cancel button
```

当用户点击「取消」按钮后，浏览器会放弃认证，直接输出服务端的返回内容。

```http
HTTP/1.1 401 Unauthorized
WWW-Authenticate: Basic realm="My Realm"
Text to send if user hits Cancel button
```

其中，realm 表示该帐号密码应用在的资源，浏览器会根据资源存储对应帐号密码，realm 不同时即需要再次认证。

```http
WWW-Authenticate: Basic realm="My Realm"
```

### 输入帐号密码后点击「确认」

```
GET /auth.php HTTP/1.1
Host: demo.aisuhua.com
User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:63.0) Gecko/20100101 Firefox/63.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2
Accept-Encoding: gzip, deflate
Connection: keep-alive
Upgrade-Insecure-Requests: 1
Pragma: no-cache
Cache-Control: no-cache
Authorization: Basic c3VodWE6MTIzNDU2

HTTP/1.1 200 OK
Server: nginx/1.14.1
Date: Fri, 23 Nov 2018 01:20:46 GMT
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: aisuhua
X-Server-Name: HN1_wp-web2
X-LB-Name: HN1_lb1
Content-Encoding: gzip

<p>Hello suhua.</p><p>You entered 123456 as your password.</p>
```

用户输入帐号密码后，浏览器会使用 `base64` 对此进行编码，然后放在 `Authorization` 报头，之后的每次请求都会带认证信息。

```http
Authorization: Basic c3VodWE6MTIzNDU2
```
