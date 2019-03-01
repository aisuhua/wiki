## Shadowsocks-Qt5

安装

```sh
shell> add-apt-repository ppa:hzwhuang/ss-qt5
shell> apt-get update
shell> apt-get install shadowsocks-qt5
```

- [Shadowsocks-Qt5](https://github.com/shadowsocks/shadowsocks-qt5)

## SwitchyOmega

安装

- [Chrome Web Store](https://chrome.google.com/webstore/detail/padekgcemlokbadohgkifijomclgjgif)
- [SwitchyOmega](https://github.com/FelisCatus/SwitchyOmega)

GFWList URL

https://raw.githubusercontent.com/gfwlist/gfwlist/master/gfwlist.txt

- [GFWList](https://github.com/gfwlist/gfwlist)

## Polipo

安装

```sh
shell> apt-get install polipo
```

配置

```sh
shell> vim /etc/polipo/config
logSyslog = true
logFile = /var/log/polipo/polipo.log

proxyAddress = "0.0.0.0"

socksParentProxy = "127.0.0.1:1080"
socksProxyType = socks5

chunkHighMark = 50331648
objectHighMark = 16384

serverMaxSlots = 64
serverSlots = 16
serverSlots1 = 32
```

重启服务

```sh
shell> service polipo restart
```

测试代理是否正常

```sh
shell> curl http://127.0.0.1:8123/
```

为终端配置 http 代理

```sh
shell> export http_proxy="http://127.0.0.1:8123/"
shell> export https_proxy="http://127.0.0.1:8123/"
```

查看 IP 信息

```sh
shell> curl ip.cn
```

- [Polipo](https://www.irif.fr/~jch/software/polipo/)

## 其他

支持 chacha20-ietf-poly1305 加密算法

```
sudo apt-get install software-properties-common -y
sudo add-apt-repository ppa:max-c-lv/shadowsocks-libev -y
sudo apt-get update
sudo apt install shadowsocks-libev
```

- [ubuntu16.04 配置ss及使用教程，支持chacha20-ietf-poly1305加密方式](https://blog.csdn.net/qq_36265860/article/details/83379138)

启动多个 ss-local 实例

```
shell> vim /etc/ss0.json
{
  "server": "", 
  "server_port": "",
  "local_port": 1080,
  "password": "",
  "timeout": 600,
  "method": "chacha20-ietf-poly1305"
}
shell> vim /etc/ss1.json
{
  "server": [
	"x.x.x.x", 
	"y.y.y.y"
  ],
  "server_port": "",
  "local_port": 1080,
  "password": "",
  "timeout": 600,
  "method": "aes-256-cfb"
}
shell> nohup ss-local -c /etc/ss0.json > ss0.log 2>&1 &
shell> nohup ss-local -c /etc/ss1.json > ss1.log 2>&1 &
```

- [Shadowsocks](https://wiki.archlinux.org/index.php/Shadowsocks_(%E7%AE%80%E4%BD%93%E4%B8%AD%E6%96%87))

开机启动

```bash
shell> /etc/rc.local
ss-local -c /etc/ss0.json
ss-local -c /etc/ss1.json
```

- [ubuntu16.04 配置shadowshocks客户端开机启动](https://blog.csdn.net/H12590400327/article/details/81091306)

## 参考文献

- [fq-book](https://github.com/loremwalker/fq-book)
- [WebSiteUseful](https://github.com/loremwalker/WebSiteUseful)
- [ubuntu16.04安装shadowsocks-qt5并支持chacha20-ietf-poly1305协议](https://www.shangyexin.com/2018/04/20/shadowsocks-qt5/)
