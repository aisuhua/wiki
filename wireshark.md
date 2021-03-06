## 安装

找不到网卡接口

```
sudo dpkg-reconfigure wireshark-common
sudo usermod -a -G wireshark suhua
```

- [Why doesn't wireshark detect my interface?](https://stackoverflow.com/questions/8255644/why-doesnt-wireshark-detect-my-interface)

## 使用

来源和目标IP

```
ip.src eq 122.51.144.50 or ip.dst eq 122.51.144.50
```

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

## 其他

查看 HTTPS 的 SNI

- [HTTPS 深入浅出 - 什么是 SNI？](https://blog.csdn.net/firefile/article/details/80532161)
