## 初始化

修改主机名

```sh
shell> hostname wp-web1.192.168.1.2.local.aisuhua.net
shell> echo `hostname` > /etc/hostname && echo "127.0.0.1 `hostname` `hostname -s`" >> /etc/hosts
```

- [ubuntu永久修改主机名](https://blog.csdn.net/ruglcc/article/details/7802077)

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

更新系统

```sh
shell> apt-get upgrade
```

安装 ssh

```sh
shell> apt-get install openssh-server
```

允许 root 用户使用 ssh 登录

```sh
shell> vim /etc/ssh/sshd_config
PermitRootLogin yes
shell> service ssh restart
```

安装 ntpdate

```sh
shell> apt-get install ntpdate
```

同步时间

```sh
shell> /usr/sbin/ntpdate ntp7.aliyun.com
```

- [时间配置：NTP服务器与其他基础服务](https://help.aliyun.com/document_detail/92704.html)
- [时间设置：设置Linux实例时区和NTP服务](https://help.aliyun.com/document_detail/92803.html)
- [使用 ntpd 來替換 ntpdate 以完成校時的工作](https://szlin.me/2016/07/19/%E4%BD%BF%E7%94%A8-ntpd-%E4%BE%86%E6%9B%BF%E6%8F%9B-ntpdate-%E4%BB%A5%E5%AE%8C%E6%88%90%E6%A0%A1%E6%99%82%E7%9A%84%E5%B7%A5%E4%BD%9C/)

优化内核参数

```sh
shell> vim /etc/sysctl.conf
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_tw_recycle = 1
net.core.somaxconn = 1024
net.ipv4.tcp_max_syn_backlog = 1024
net.ipv4.tcp_synack_retries = 2
net.ipv4.ip_local_port_range = 1024 65535
net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1
net.ipv6.conf.lo.disable_ipv6 = 1
vm.swappiness = 0
fs.file-max = 655350
```
- [How do ulimit -n and /proc/sys/fs/file-max differ?](https://serverfault.com/questions/122679/how-do-ulimit-n-and-proc-sys-fs-file-max-differ)
- [Linux 实例常用内核网络参数介绍与常见问题处理](https://help.aliyun.com/knowledge_detail/41334.html)

增加文件描述符限制

```sh
shell> vim /etc/systemd/system.conf
[Manager]
DefaultLimitNOFILE=65535
shell> vim /etc/security/limits.conf
root soft nofile 65535
root hard nofile 65535
* soft nofile 65535
* hard nofile 65535
```

- [Increase the open files limit on Linux](https://ro-che.info/articles/2017-03-26-increase-open-files-limit)
- [Cannot Increase open file limit past 4096 (Ubuntu)](https://superuser.com/questions/1200539/cannot-increase-open-file-limit-past-4096-ubuntu)

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

修改 root 密码

```sh
shell> sudo passwd root
```

## 权限

添加 sudo 权限，方法 1

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL) ALL
```

添加 sudo 权限，方法 2

```sh
shell> usermod -a -G sudo suhua
```

- [How do I grant sudo privileges to an existing user? ](https://askubuntu.com/questions/168280/how-do-i-grant-sudo-privileges-to-an-existing-user)

使用 sudo 执行命令时不用输入密码

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL) NOPASSWD: ALL
```

- [Sudoers file, enable NOPASSWD for user, all commands](https://askubuntu.com/questions/334318/sudoers-file-enable-nopasswd-for-user-all-commands)
- [sudoers的深入剖析与用户权限控制](https://segmentfault.com/a/1190000007394449)

查看用户拥有的 sudo 权限

```sh
shell> sudo -l -U suhua
```

## 服务器状态

查看公网 IP

```sh 
shell> curl curl cip.cc
shell> curl ip.cn
```

查看网速

```sh
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

## 添加虚拟IP

临时添加、删除虚拟IP（重启后无效）

```sh
shell> ip address add 192.168.1.169/24 dev enp0s3
shell> ip address del 192.168.1.169/24 dev enp0s3
```

编辑配置文件

```sh
shell> vim /etc/network/interfaces
# The primary network interface
auto enp0s3
iface enp0s3 inet dhcp

iface enp0s3 inet static
address 192.168.1.169/24
```

查看虚拟IP

```sh
shell> ip address
```

- [How can I (from CLI) assign multiple IP addresses to one interface?](https://askubuntu.com/questions/547289/how-can-i-from-cli-assign-multiple-ip-addresses-to-one-interface)

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

添加 swap 分区

```sh
shell> swapon -s
shell> fallocate -l 10G /swapfile
shell> chmod 600 /www/swapfile
shell> mkswap /www/swapfile
shell> swapon /www/swapfile
```

将 swap 分区写入 fstab 文件

```sh
shell> vim /etc/fstab
/www/swapfile none swap defaults 0 0
```

- [Swap (简体中文)](https://wiki.archlinux.org/index.php/Swap_(%E7%AE%80%E4%BD%93%E4%B8%AD%E6%96%87))

## 笔记本问题

解决笔记本风扇狂转、无法正常关机的问题

```sh
shell> vim /etc/modprobe.d/blacklist.conf
blacklist nouveau
shell> reboot
```

## 参考文献

- [Official Ubuntu Documentation](https://help.ubuntu.com/)
