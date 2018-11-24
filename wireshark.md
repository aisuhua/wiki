查询特定 IP 的网络数据

```
ip.addr == 58.215.175.47
```

查询特定域名的网络数据

```
http.host == aisuhua.com
http.host contains aisuhua.com
```

查询特定 TCP 端口号的数据包

```
tcp.port == 34642
```

多条件筛选

```
tcp.port == 34642 || tcp.port == 1318 || ip.addr == 58.215.175.47
```
