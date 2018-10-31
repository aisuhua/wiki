## 初始化

修改 root 密码

```
sudo passwd root
```

修改主机名

```
hostname wp-web1.192.168.1.2.local.aisuhua.net
echo `hostname` > /etc/hostname && echo "127.0.0.1 `hostname` `hostname -s`" >> /etc/hosts
```

- [ubuntu永久修改主机名](https://blog.csdn.net/ruglcc/article/details/7802077)

配置静态 IP

```
vim /etc/network/interfaces
auto enp0s3
iface enp0s3 inet static
address 192.168.1.2
netmask 255.255.255.0
gateway 192.168.1.1
dns-nameservers 223.5.5.5 223.6.6.6
```

安装 ssh

```
apt-get install openssh-server
```

允许 root 用户使用 ssh 登录

```
vim /etc/ssh/sshd_config
PermitRootLogin yes
```

重启系统

```sh
reboot
```

## 国内源

使用中科大镜像

```sh
cp /etc/apt/sources.list /etc/apt/sources.list.bak
vim /etc/apt/sources.list
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

deb http://security.ubuntu.com/ubuntu xenial-security main restricted
# deb-src http://security.ubuntu.com/ubuntu xenial-security main restricted
deb http://security.ubuntu.com/ubuntu xenial-security universe
# deb-src http://security.ubuntu.com/ubuntu xenial-security universe
deb http://security.ubuntu.com/ubuntu xenial-security multiverse
# deb-src http://security.ubuntu.com/ubuntu xenial-security multiverse
apt-get update
```

- [repository file generator](https://mirrors.ustc.edu.cn/repogen/)

## 校正时间

安装 ntpdate

```sh
shell> apt-get install ntpdate
```

校正时间

```sh
/usr/sbin/ntpdate ntp7.aliyun.com
```

- [时间配置：NTP服务器与其他基础服务](https://help.aliyun.com/document_detail/92704.html)


## 系统优化

修改默认最大文件打开数

```sh
shell> vim /etc/systemd/system.conf
[Manager]
DefaultLimitNOFILE=65535
shell> vim /etc/security/limits.d/nofile.conf
* - nofile 65535
```

- [Increase the open files limit on Linux](https://ro-che.info/articles/2017-03-26-increase-open-files-limit)

优化内核参数（仅供参考）

```sh
shell> /etc/sysctl.conf
net.ipv4.tcp_fin_timeout=30
net.ipv4.tcp_keepalive_time=1200
net.ipv4.tcp_tw_reuse=1
net.ipv4.tcp_tw_recycle=1
net.core.netdev_max_backlog=262144
net.core.somaxconn=65535
net.ipv4.tcp_max_orphans=262144
net.ipv4.tcp_max_syn_backlog=262144
net.ipv4.tcp_synack_retries=2
net.ipv4.tcp_syn_retries=2
net.ipv4.tcp_sack=1
net.ipv4.tcp_window_scaling=1
net.ipv4.tcp_wmem=4096 65536 524288
net.core.wmem_max=1048576
net.core.wmem_default=1048576
net.core.rmem_max=16777216
net.core.rmem_default=16777216
net.ipv4.ip_local_port_range=1024 65535
net.netfilter.nf_conntrack_max=655350
net.netfilter.nf_conntrack_tcp_timeout_established=300
net.ipv6.conf.all.disable_ipv6=1
net.ipv6.conf.default.disable_ipv6=1
net.ipv6.conf.lo.disable_ipv6=1
vm.swappiness=1
fs.file-max=655350
```

- [Linux 实例常用内核网络参数介绍与常见问题处理](https://help.aliyun.com/knowledge_detail/41334.html)

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

## 网络设置

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

## 同步时间

安装 ntpdate

```sh
shell> apt-get install ntpdate
```

校正时间

```sh
shell> /usr/sbin/ntpdate ntp7.aliyun.com
```

- [时间配置：NTP服务器与其他基础服务](https://help.aliyun.com/document_detail/92704.html)

## 对命令进行版本切换

update-alternatives（待补充）

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

