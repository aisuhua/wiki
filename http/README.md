## HTTP Basic authentication

帐号密码写在地址栏

```http
https://username:password@www.example.com
```

base64(username:password) 的结果放在 Authorization 报头

```http
curl https://www.example.com -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t'
```

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)

