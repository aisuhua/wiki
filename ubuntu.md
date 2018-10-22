## 服务器状态

查看公网 IP

```sh 
shell> curl curl cip.cc
shell> curl ip.cn
```

查看网速

```
shell> apt-get install ifstat 
shell> wget  http://gael.roualland.free.fr/ifstat/ifstat-1.1.tar.gz
shell> tar -zxvf ifstat-1.1.tar.gz
shell> cd ifstat-1.1/
shell> ./configure
shell> make && make install
shell> ifstat
```

- [Ifstat](http://gael.roualland.free.fr/ifstat/)

查看多核 CPU 的使用情况

```sh
shell> top
Press key 1
```

查看当前运行级别

```sh
shell> runlevel
```

## 国内源

使用中科大镜像

```sh
shell> cp /etc/apt/sources.list /etc/apt/sources.list.bak
shell> vim /etc/apt/sources.list
deb https://mirrors.ustc.edu.cn/ubuntu/ xenial main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ xenial main restricted universe multiverse

deb https://mirrors.ustc.edu.cn/ubuntu/ xenial-security main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ xenial-security main restricted universe multiverse

deb https://mirrors.ustc.edu.cn/ubuntu/ xenial-updates main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ xenial-updates main restricted universe multiverse

deb https://mirrors.ustc.edu.cn/ubuntu/ xenial-backports main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ xenial-backports main restricted universe multiverse

## Not recommended
# deb https://mirrors.ustc.edu.cn/ubuntu/ xenial-proposed main restricted universe multiverse
# deb-src https://mirrors.ustc.edu.cn/ubuntu/ xenial-proposed main restricted universe multiverse
shell> apt-get update
```

- [repository file generator](https://mirrors.ustc.edu.cn/repogen/)

## 网络设置

修改主机名

```sh
shell> hostname wp-web1.192.168.1.2.local.aisuhua.net
shell> hostname -s
wp-web1
shell> echo `hostname` > /etc/hostname && echo "127.0.0.1 `hostname` `hostname -s`" >> /etc/hosts && hostname -f
```

- [ubuntu永久修改主机名](https://blog.csdn.net/ruglcc/article/details/7802077)

配置静态 IP

```sh
shell> vim /etc/network/interfaces
auto enp0s3
iface enp0s3 inet static
address 192.168.31.201
netmask 255.255.255.0
gateway 192.168.31.1
dns-nameservers 223.5.5.5 223.6.6.6
```

多网卡（有线和无线）

```sh
shell> vim /etc/network/interfaces
auto enp0s1
iface enp0s1 inet dhcp

auto enp0s2
iface enp0s2 inet static
address 192.168.31.200
netmask 255.255.255.0
gateway 192.168.31.1
wpa-ssid WIFI-NAME
wpa-psk WIFI-PASSWORD
dns-nameservers 223.5.5.5 223.6.6.6
```

重启网络

```sh
shell> service networking restart
```

开启无线网络

```sh
shell> ifconfig enp0s2 up
```

关闭无线网络

```sh
shell> ifconfig enp0s2 down
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

## 用户管理

添加用户

```sh
shell> groupadd suhua
shell> useradd -g suhua -G sudo -s /bin/bash -d /home/suhua -m suhua
shell> passwd suhua
```

- [How to add new user in Linux](https://www.simplified.guide/linux/add-new-user)

删除用户

```sh
shell> userdel -r suhua
```

查看用户所属组

```sh
shell> groups suhua
```

## 权限

添加 sudo 权限，方法 1

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL:ALL) ALL
```

添加 sudo 权限，方法 2

```sh
shell> usermod -a -G sudo suhua
```

- [How do I grant sudo privileges to an existing user? ](https://askubuntu.com/questions/168280/how-do-i-grant-sudo-privileges-to-an-existing-user)

使用 sudo 执行命令时不用输入密码

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL) NOPASSWD:ALL
```

- [Sudoers file, enable NOPASSWD for user, all commands](https://askubuntu.com/questions/334318/sudoers-file-enable-nopasswd-for-user-all-commands)

## 分区

制作 ntfs 类型的分区

```sh
shell> fdisk /dev/sdx
shell> mkfs.ntfs /dev/sdx1
shell> mount /dev/sdx1 /mnt/myNtfsDevice
```

- [How can I use fdisk to create a ntfs partition on /dev/sdx?](https://unix.stackexchange.com/questions/252625/how-can-i-use-fdisk-to-create-a-ntfs-partition-on-dev-sdx)

## 笔记本问题

解决笔记本风扇狂转、无法正常关机的问题

```sh
shell> vim /etc/modprobe.d/blacklist.conf
blacklist nouveau
shell> reboot
```

