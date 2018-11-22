## HTTP Basic authentication

URL 上带帐号密码

```sh
shell> curl https://username:password@www.example.com
```

或 Authorization 带上签名

```sh
shell> curl -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t' https://www.example.com
```

> ZWxhc3RpYzoxMTUjMTE1Q29t = base64(username:password)

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)
