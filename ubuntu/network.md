## ip address

查看 IP

```sh
shell> ip address
```

添加 IP 地址（临时）

```sh
shell> ip address add 192.168.31.206/24 dev enp0s3
```

添加 IP 地址（永久）

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
