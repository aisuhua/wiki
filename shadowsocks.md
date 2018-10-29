## shadowsocks-qt5

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

GFWList URL(Github): https://raw.githubusercontent.com/gfwlist/gfwlist/master/gfwlist.txt

- [SwitchyOmega](https://github.com/FelisCatus/SwitchyOmega)

## Polipo

安装

```sh
shell> apt-get install polipo
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
shell> /etc/init.d/polipo restart
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

