## 安装

找不到网卡接口

```
sudo dpkg-reconfigure wireshark-common
sudo usermod -a -G wireshark suhua
```

- [Why doesn't wireshark detect my interface?](https://stackoverflow.com/questions/8255644/why-doesnt-wireshark-detect-my-interface)

## 使用

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

