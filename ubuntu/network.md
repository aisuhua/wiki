## hostname

修改主机名

```sh
shell> hostname wp-web1.192.168.1.2.local.aisuhua.net
shell> echo `hostname` > /etc/hostname && echo "127.0.0.1 `hostname` `hostname -s`" >> /etc/hosts
```

## 静态 IP

配置静态 IP

```sh
shell> vim /etc/network/interfaces
auto enp0s3
iface enp0s3 inet static
address 192.168.1.2
netmask 255.255.255.0
gateway 192.168.1.1
dns-nameservers 223.5.5.5 223.6.6.6
```

无线网卡

```sh
shell> vim /etc/network/interfaces
auto enp0s2
iface enp0s2 inet static
address 192.168.31.200
netmask 255.255.255.0
gateway 192.168.31.1
wpa-ssid WIFI-NAME
wpa-psk WIFI-PASSWORD
dns-nameservers 223.5.5.5 223.6.6.6
```

开启无线网卡

```sh
shell> ifconfig enp0s2 up
```

关闭无线网卡

```sh
shell> ifconfig enp0s2 down
```

## ip address

查看当前 IP

```sh
shell> ip address
```

临时添加 IP 地址

```sh
shell> ip address add 192.168.31.206/24 dev enp0s3
```

将 IP 地址写入配置文件

```sh
shell> vim /etc/network/interfaces
auto enp0s3
iface enp0s3 inet dhcp

iface enp0s3 inet static
address 192.168.31.206/24
```

删除 IP 地址

```sh
shell> ip address del 192.168.31.206/24 dev enp0s3
```

重启网络

```sh
shell> service networking restart
```

- [How can I (from CLI) assign multiple IP addresses to one interface?](https://askubuntu.com/questions/547289/how-can-i-from-cli-assign-multiple-ip-addresses-to-one-interface)

## nslookup

查看域名的 DNS 信息

```sh
shell> nslookup www.baidu.com   
```

## dig

查看 DNS 相关的更多信息

```sh
shell> dig www.baidu.com
```

