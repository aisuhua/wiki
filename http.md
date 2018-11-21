## HTTP Basic authentication

URL 请求

```sh
shell> curl https://username:password@www.example.com
```

Authorization 报头

```sh
shell> curl -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t' https://elastic:passwd@www.example.com
```

> ZWxhc3RpYzoxMTUjMTE1Q29t 生成方法是 base64(username:password)。
