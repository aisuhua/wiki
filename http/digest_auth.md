Digest access authentication is one of the agreed-upon methods a web server can use to negotiate credentials, such as username or password, with a user's web browser. This can be used to confirm the identity of a user before sending sensitive information, such as online banking transaction history. It applies a hash function to the username and password before sending them over the network. In contrast, basic access authentication uses the easily reversible Base64 encoding instead of encryption, making it non-secure unless used in conjunction with TLS.

Technically, digest authentication is an application of MD5 cryptographic hashing with usage of nonce values to prevent replay attacks. It uses the HTTP protocol.

- [Digest access authentication](https://en.wikipedia.org/wiki/Digest_access_authentication)
- [HTTP摘要认证](https://zh.wikipedia.org/wiki/HTTP%E6%91%98%E8%A6%81%E8%AE%A4%E8%AF%81)
