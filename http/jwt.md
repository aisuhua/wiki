# JSON Web Token

JSON Web Token (JWT, sometimes pronounced /dʒɒt/) is a JSON-based open standard (RFC 7519) for creating access tokens that assert some number of claims. 
For example, a server could generate a token that has the claim "logged in as admin" and provide that to a client. 
The client could then use that token to prove that it is logged in as admin. 
The tokens are signed by one party's private key (usually the server's), so that both parties 
(the other already being, by some suitable and trustworthy means, in possession of the corresponding public key) are able to verify that the token is legitimate.
The tokens are designed to be compact, URL-safe and usable especially in a web-browser single-sign-on (SSO) context.
JWT claims can be typically used to pass identity of authenticated users between an identity provider and a service provider, 
or any other type of claims as required by business processes.

- [JSON Web Token](https://en.wikipedia.org/wiki/JSON_Web_Token)

## 组成

JWT 实际上就是一段由头部、载荷和签名所组成的字符串，它由服务端生成，存储在客户端的 Cookie 里。

- 头部 Header：说明该 jwt 使用哪一种加密算法；
- 载荷 Payload：用于存储业务数据以及 jwt 的生成和过期时间；
- 签名 Signature：对载荷和头部进行加密，密钥在服务端掌握；

## 使用场景

- 激活帐号、邮箱验证；
- 用户登录、单点登录（密钥泄漏有危险，服务端仍然需存储验证信息）；

由于 Payload 是明文，因此敏感的业务数据不应该放在 JWT。

## 示例

生成 JWT

```php
// Create token header as a JSON string
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

// Create token payload as a JSON string
$payload = json_encode(['user_id' => 123]);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

// eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMjN9.NYlecdiqVuRg0XkWvjFvpLvglmfR1ZT7f8HeDDEoSx8
echo $jwt;
```

- [How to Create a JSON Web Token Using PHP](https://dev.to/robdwaller/how-to-create-a-json-web-token-using-php-3gml)

验证和解析 JWT

```php

$jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMjN9.NYlecdiqVuRg0XkWvjFvpLvglmfR1ZT7f8HeDDEoSx8';

list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

// Check signature
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
$base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
if ($base64UrlSignature !== $base64UrlSignatureCheck)
{
    exit('Invalid signature.');
}

$header = base64_decode(str_pad(
    str_replace(['-', '_'], ['+', '/'], $base64UrlHeader),
    strlen($base64UrlHeader) % 4,
    '=',
STR_PAD_RIGHT
));

$payload = base64_decode(str_pad(
    str_replace(['-', '_'], ['+', '/'], $base64UrlPayload),
    strlen($base64UrlPayload) % 4,
    '=',
    STR_PAD_RIGHT
));

// {"typ":"JWT","alg":"HS256"}
echo $header;

// {"user_id":123}
echo $payload;
```



