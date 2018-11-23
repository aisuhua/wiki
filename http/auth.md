## HTTP Basic authentication

### 帐号密码传输方式

可写在地址栏。

```http
curl https://username:password@www.example.com
```

或放在 Authorization 报头，生成方法是 `base64(username:password)`。

```http
curl https://www.example.com -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t'
```

这两种方式的帐号密码都是明文传输，所以并不安全。

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)

### 在 PHP 中实现

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

