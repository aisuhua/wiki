查看网速

```
shell> apt-get install ifstat 

shell> wget  http://gael.roualland.free.fr/ifstat/ifstat-1.1.tar.gz
shell> tar -zxvf ifstat-1.1.tar.gz
shell> cd ifstat-1.1/
shell> ./configure
shell> make && make install

shell> ifstat

http://gael.roualland.free.fr/ifstat/
```

查看多核 CPU 的使用情况

```sh
shell> top
# 然后按数字 1 即可查看每个核心的状态
```

## 快速创建虚拟机

使用 virtual box 复制出 N 份虚拟机，复制时要勾选重置所有网络设置。

设置主机名

```sh
shell> vim /etc/hostname
rabbit1
shell> vim /etc/hosts
127.0.1.1 rabbit1
```

设置静态 IP

```sh
shell> vim /etc/network/interfaces
# This file describes the network interfaces available on your system
# and how to activate them. For more information, see interfaces(5).

source /etc/network/interfaces.d/*

# The loopback network interface
auto lo
iface lo inet loopback

# The primary network interface
# auto enp0s3
# iface enp0s3 inet dhcp

auto enp0s3
iface enp0s3 inet static
address 192.168.31.201
netmask 255.255.255.0
gateway 192.168.31.1
dns-nameservers 223.5.5.5 223.6.6.6
```

做完上面步骤后，重启即可。

## 分区

制作 ntfs 类型的分区

```sh
shell> fdisk /dev/sdx
shell> mkfs.ntfs /dev/sdx1
shell> mount /dev/sdx1 /mnt/myNtfsDevice
```

- [How can I use fdisk to create a ntfs partition on /dev/sdx?](https://unix.stackexchange.com/questions/252625/how-can-i-use-fdisk-to-create-a-ntfs-partition-on-dev-sdx)



