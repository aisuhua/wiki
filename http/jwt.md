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

JWT 实际上就是一段由头部、载荷和签名以逗号分割的字符串，该字符串由服务端生成，存储在客户端的 Cookie 里。

- 头部 Header：说明使用哪一种加密方法；
- 载荷 Payload：用于存储业务数据以及 jwt 的生成和过期时间；
- 签名 Signature：对载荷和头部进行加密，密钥在服务端掌握；
