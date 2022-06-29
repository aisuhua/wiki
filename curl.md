# Curl

请求 HTTPS 时支持 SNI

```
curl -v --resolve api.ocp1.sz.bocsysrc.cn:8443:172.16.230.101 https://api.ocp1.sz.bocsysrc.cn:8443 --cacert rootCA.pem
```

## 参考文献

- [How to troubleshoot SNI enabled endpoints with curl and openssl](https://www.suse.com/support/kb/doc/?id=000020154)
