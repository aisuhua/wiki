# OAuth

OAuth is an open standard for access delegation, commonly used as a way for Internet users to grant websites or applications access to their information on other websites but without giving them the passwords.
This mechanism is used by companies such as Amazon,Google, Facebook, Microsoft and Twitter to permit the users to share information about their accounts with third party applications or websites.

OAuth 能够让第三方网站在用户不提供帐号密码的情况下访问他的个人信息。

- [OAuth](https://en.wikipedia.org/wiki/OAuth)

## 原理图

![Alt text](img/oauth.png)

At its core, OAuth requires you to redirect users to a special URL on Square’s site that includes your application id. 
The merchant then decides whether or not to allow your application access, and which permissions your app will have. 
You application then gets an authorization code, which it will exchange for an access token with an authenticated request.

- [OAuth with PHP, Part One: getting access tokens.](https://medium.com/square-corner-blog/oauth-with-php-part-one-getting-access-tokens-5a18b0b70099)

## 示例

用户授权

```html
<a href="https://github.com/login/oauth/authorize?client_id=CLIENT_ID&response_type=code<br>
&scope=user:email&<br>redirect_uri=https://webapi.115.com/oauth/callback.php">Authorize App</a>
```

![Alt text](img/oauth_index.png)

点击进入授权询问页面

![Alt text](img/oauth_authorization.png)

用户确定授权后，页面会重定向到 `redirect_uri` 并附上 authorization code。

```
https://webapi.115.com/oauth/callback.php?code=7d539c86c74b32f17b39
```

接着，客户端使用 authorization code 和 client secret 获取 access token.

```php
<?php
$client_id = '';
$client_secret = '';
$authorization_code = $_GET['code'];
$url = 'https://github.com/login/oauth/access_token';

$data = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $authorization_code
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
var_dump($result);
```

## 参考文献

- [OAuth with PHP, Part One: getting access tokens.](https://medium.com/square-corner-blog/oauth-with-php-part-one-getting-access-tokens-5a18b0b70099)
- [OAuth with PHP Part Two: refreshing & revoking tokens](https://medium.com/square-corner-blog/oauth-with-php-part-two-refreshing-revoking-tokens-9ae065537c41)
- [Authorizing OAuth Apps](https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/)


