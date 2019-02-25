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
