## ip address

查看 IP

```sh
shell> ip address
```

临时添加IP地址

```sh
shell> ip address add 192.168.31.206/24 dev enp0s3
```

将IP地址写入配置文件

```sh
shell> vim /etc/network/interfaces
auto enp0s3
iface enp0s3 inet dhcp

iface enp0s3 inet static
address 192.168.31.206/24
```

删除IP地址

```sh
shell> ip address del 192.168.31.206/24 dev enp0s3
```

重启网络

```sh
shell> service networking restart
```

- [How can I (from CLI) assign multiple IP addresses to one interface?](https://askubuntu.com/questions/547289/how-can-i-from-cli-assign-multiple-ip-addresses-to-one-interface)
