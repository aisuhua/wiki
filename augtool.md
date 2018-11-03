# 目录

- [安装](#安装)
- [使用](#使用)
  - [/etc/hosts](#etchosts)
  - [/etc/sudoers](#etcsudoers)
  - [/etc/ssh/sshd_config](#etcsshsshd_config)
  - [/etc/sysctl.conf](#etcsysctlconf)
  - [/etc/systemd/system.conf](#etcsystemdsystemconf)
  - [/etc/security/limits.conf](#etcsecuritylimitsconf)
  - [/etc/php/7.2/fpm/php.ini](#etcphp72fpmphpini)
  - [/etc/php/7.2/fpm/pool.d/www.conf](#etcphp72fpmpooldwwwconf)
- [参考文献](#参考文献)

## 安装

卸载旧版本

```sh
shell> apt-get purge augeas-tools augeas-lenses
```

安装新版本

```sh
shell> wget http://download.augeas.net/augeas-1.11.0.tar.gz
shell> tar -zxvf augeas-1.11.0.tar.gz
shell> cd augeas-1.11.0/
shell> apt-get install libreadline-dev pkg-config libxml2-dev
shell> ./configure
shell> make
shell> make install
```

查看版本

```sh
shell> augtool --version
```

查看支持的配置格式

```sh
shell> ls /usr/share/augeas/lenses/dist/
```

- [Download](http://augeas.net/download.html)

## 使用

查看解析结果

```sh
shell> augtool ls /files/etc/hosts 
```

进入交互模式

```sh
shell> augtool
```

- [A quick tour](http://augeas.net/tour.html)

### /etc/hosts 

配置文件内容

```sh
shell> cat /etc/hosts
127.0.0.1	localhost

# The following lines are desirable for IPv6 capable hosts
::1     localhost ip6-localhost ip6-loopback
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
127.0.0.1 wp-web1.192.168.1.2.local.aisuhua.net wp-web1 wp-web1.192.168.1.2.local
```

逐步解析配置文件

```sh
augtool> ls /files/etc/hosts
1/ = (none)
#comment = The following lines are desirable for IPv6 capable hosts
2/ = (none)
3/ = (none)
4/ = (none)
5/ = (none)
augtool> ls /files/etc/hosts/5
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> print /files/etc/hosts/5
/files/etc/hosts/5
/files/etc/hosts/5/ipaddr = "127.0.0.1"
/files/etc/hosts/5/canonical = "wp-web1.192.168.1.2.local.aisuhua.net"
/files/etc/hosts/5/alias[1] = "wp-web1"
/files/etc/hosts/5/alias[2] = "wp-web1.192.168.1.2.local"
```

查看配置项

```sh
augtool> get /files/etc/hosts/5/ipaddr
/files/etc/hosts/5/ipaddr = 127.0.0.1
augtool> get /files/etc/hosts/5/alias[1]
/files/etc/hosts/5/alias[1] = wp-web1
augtool> get /files/etc/hosts/5/alias[2]
/files/etc/hosts/5/alias[2] = wp-web1.192.168.1.2.local
```

表达式的使用

```sh
augtool> ls /files/etc/hosts/*[last()]
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[last()-1]
ipaddr = ff02::2
canonical = ip6-allrouters
augtool> get /files/etc/hosts/*[last()]/ipaddr
/files/etc/hosts/*[last()]/ipaddr = 127.0.0.1
augtool> get /files/etc/hosts/*[last()]/alias[2]
/files/etc/hosts/*[last()]/alias[2] = wp-web1.192.168.1.2.local
augtool> get /files/etc/hosts/*[last()]/alias[last()]
/files/etc/hosts/*[last()]/alias[last()] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[alias] # 具有 alias 的条目
ipaddr = ::1
canonical = localhost
alias[1] = ip6-localhost
alias[2] = ip6-loopback
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[alias = 'wp-web1'] # alias 为 wp-web1 的条目
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> get /files/etc/hosts/*/alias[. = 'wp-web1'] # alias 为 wp-web1 的选项
/files/etc/hosts/*/alias[. = 'wp-web1'] = wp-web1
augtool> ls /files/etc/hosts/*[ipaddr = '127.0.0.1'] # IP 地址一致的条目
ipaddr = 127.0.0.1
canonical = localhost
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[ipaddr = '127.0.0.1'][alias = 'wp-web1'] # IP 地址和 alias 都一致的条目
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local

```

添加配置项

```sh
augtool> set /files/etc/hosts/6/ipaddr 192.168.1.3
augtool> set /files/etc/hosts/6/canonical www.aisuhua.com
augtool> set /files/etc/hosts/6/alias[1] www.aisuhua.net
augtool> set /files/etc/hosts/6/alias[2] www.aisuhua.cn
augtool> save
Saved 1 file(s)
augtool> ls /files/etc/hosts/6
ipaddr = 192.168.1.3
canonical = www.aisuhua.com
alias[1] = www.aisuhua.net
alias[2] = www.aisuhua.cn
augtool> set /files/etc/hosts/6/alias[last()+1] www.aisuhua.cc
augtool> save
Saved 1 file(s)
augtool> ls /files/etc/hosts/6
ipaddr = 192.168.1.3
canonical = www.aisuhua.com
alias[1] = www.aisuhua.net
alias[2] = www.aisuhua.cn
alias[3] = www.aisuhua.cc
```

插入配置项

```sh
augtool> ins 01 after /files/etc/hosts/*[last()]
augtool> set /files/etc/hosts/01/ipaddr 172.16.0.1
augtool> set /files/etc/hosts/01/canonical www.example.com
augtool> set /files/etc/hosts/01/alias[1] www.example.net
augtool> set /files/etc/hosts/01/alias[2] www.example.cn
augtool> save
Saved 1 file(s)
augtool> print /files/etc/hosts/01
/files/etc/hosts/01
/files/etc/hosts/01/ipaddr = "172.16.0.1"
/files/etc/hosts/01/canonical = "www.example.com"
/files/etc/hosts/01/alias[1] = "www.example.net"
/files/etc/hosts/01/alias[2] = "www.example.cn"
```

- [Adding nodes to the tree](https://github.com/hercules-team/augeas/wiki/Adding-nodes-to-the-tree)

插入配置项（写法2）

```sh
augtool> ins 01 after /files/etc/hosts/*[last()]
augtool> set /files/etc/hosts/*[last()]/ipaddr 172.16.0.1
augtool> set /files/etc/hosts/*[ipaddr = '172.16.0.1']/canonical www.example.com
augtool> set /files/etc/hosts/*[ipaddr = '172.16.0.1']/alias[1] www.example.net
augtool> set /files/etc/hosts/*[ipaddr = '172.16.0.1']/alias[2] www.example.cc
augtool> save
Saved 1 file(s)
augtool> print /files/etc/hosts/*[ipaddr = '172.16.0.1']
/files/etc/hosts/01
/files/etc/hosts/01/ipaddr = "172.16.0.1"
/files/etc/hosts/01/canonical = "www.example.com"
/files/etc/hosts/01/alias[1] = "www.example.net"
/files/etc/hosts/01/alias[2] = "www.example.cn"
```

修改配置项

```sh
augtool> get /files/etc/hosts/6/ipaddr
/files/etc/hosts/6/ipaddr = 192.168.1.3
augtool> set /files/etc/hosts/6/ipaddr 192.168.1.4
augtool> save
Saved 1 file(s)
augtool> get /files/etc/hosts/6/ipaddr
/files/etc/hosts/6/ipaddr = 192.168.1.4
```

删除配置项

```sh
augtool> ls /files/etc/hosts/6
ipaddr = 192.168.1.4
canonical = www.aisuhua.com
alias[1] = www.aisuhua.net
alias[2] = www.aisuhua.cn
alias[3] = www.aisuhua.cc
augtool> rm /files/etc/hosts/6/alias[3]
rm : /files/etc/hosts/6/alias[3] 1
augtool> save
Saved 1 file(s)
augtool> ls /files/etc/hosts/6
ipaddr = 192.168.1.4
canonical = www.aisuhua.com
alias = www.aisuhua.net
alias[1] = www.aisuhua.net
alias[2] = www.aisuhua.cn
augtool> rm /files/etc/hosts/6/alias[2]
rm : /files/etc/hosts/6/alias[2] 1
augtool> save
Saved 1 file(s)
augtool> ls /files/etc/hosts/6
ipaddr = 192.168.1.4
canonical = www.aisuhua.com
alias = www.aisuhua.net
```

### /etc/sudoers

配置文件内容

```sh
shell> cat /etc/sudoers
#
# This file MUST be edited with the 'visudo' command as root.
#
# Please consider adding local content in /etc/sudoers.d/ instead of
# directly modifying this file.
#
# See the man page for details on how to write a sudoers file.
#
Defaults        env_reset
Defaults        mail_badpass
Defaults        secure_path="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin"

# Host alias specification

# User alias specification

# Cmnd alias specification

# User privilege specification
root    ALL=(ALL:ALL) ALL

# Members of the admin group may gain root privileges
%admin ALL=(ALL) ALL

# Allow members of group sudo to execute any command
%sudo   ALL=(ALL:ALL) ALL

# Add suhua
suhua   ALL=(ALL:ALL) NOPASSWD: ALL

# See sudoers(5) for more information on "#include" directives:

#includedir /etc/sudoers.d
```

逐步解析配置文件

```sh
augtool> ls /files/etc/sudoers
#comment[1] = This file MUST be edited with the 'visudo' command as root.
#comment[2] = Please consider adding local content in /etc/sudoers.d/ instead of
#comment[3] = directly modifying this file.
#comment[4] = See the man page for details on how to write a sudoers file.
Defaults[1]/ = (none)
Defaults[2]/ = (none)
Defaults[3]/ = (none)
#comment[5] = Host alias specification
#comment[6] = User alias specification
#comment[7] = Cmnd alias specification
#comment[8] = User privilege specification
spec[1]/ = (none)
#comment[9] = Members of the admin group may gain root privileges
spec[2]/ = (none)
#comment[10] = Allow members of group sudo to execute any command
spec[3]/ = (none)
#comment[11] = Add suhua
spec[4]/ = (none)
#comment[12] = See sudoers(5) for more information on "#include" directives:
#includedir = /etc/sudoers.d
augtool> ls /files/etc/sudoers/spec[4]
user = suhua
host_group/ = (none)
augtool> ls /files/etc/sudoers/spec[4]/host_group
host = ALL
command/ = ALL
augtool> ls /files/etc/sudoers/spec[4]/host_group/command
runas_user = ALL
runas_group = ALL
tag = NOPASSWD
augtool> print /files/etc/sudoers/spec[4]
/files/etc/sudoers/spec[4]
/files/etc/sudoers/spec[4]/user = "suhua"
/files/etc/sudoers/spec[4]/host_group
/files/etc/sudoers/spec[4]/host_group/host = "ALL"
/files/etc/sudoers/spec[4]/host_group/command = "ALL"
/files/etc/sudoers/spec[4]/host_group/command/runas_user = "ALL"
/files/etc/sudoers/spec[4]/host_group/command/runas_group = "ALL"
/files/etc/sudoers/spec[4]/host_group/command/tag = "NOPASSWD"
```

添加配置项

```sh
augtool> set /files/etc/sudoers/spec[last()+1]/user aisuhua
augtool> set /files/etc/sudoers/spec[user = 'aisuhua']/host_group/host ALL
augtool> set /files/etc/sudoers/spec[user = 'aisuhua']/host_group/command ALL
augtool> set /files/etc/sudoers/spec[user = 'aisuhua']/host_group/command/runas_user ALL
augtool> set /files/etc/sudoers/spec[user = 'aisuhua']/host_group/command/runas_group ALL
augtool> set /files/etc/sudoers/spec[user = 'aisuhua']/host_group/command/tag NOPASSWD
augtool> save
Saved 1 file(s)
augtool> print /files/etc/sudoers/spec[user = 'aisuhua']
/files/etc/sudoers/spec[5]
/files/etc/sudoers/spec[5]/user = "aisuhua"
/files/etc/sudoers/spec[5]/host_group
/files/etc/sudoers/spec[5]/host_group/host = "ALL"
/files/etc/sudoers/spec[5]/host_group/command = "ALL"
/files/etc/sudoers/spec[5]/host_group/command/runas_user = "ALL"
/files/etc/sudoers/spec[5]/host_group/command/runas_group = "ALL"
/files/etc/sudoers/spec[5]/host_group/command/tag = "NOPASSWD"
```

### /etc/ssh/sshd_config

配置文件内容

```sh
shell> cat /etc/ssh/sshd_config 
# Package generated configuration file
# See the sshd_config(5) manpage for details

# What ports, IPs and protocols we listen for
Port 22
# Use these options to restrict which interfaces/protocols sshd will bind to
#ListenAddress ::
#ListenAddress 0.0.0.0
Protocol 2
..... more items...
# Authentication:
LoginGraceTime 120
PermitRootLogin prohibit-password
StrictModes yes
..... more items...
```

逐步解析配置文件

```sh
augtool> ls /files/etc/ssh/sshd_config
#comment[1] = Package generated configuration file
#comment[2] = See the sshd_config(5) manpage for details
#comment[3] = What ports, IPs and protocols we listen for
Port = 22
#comment[4] = Use these options to restrict which interfaces/protocols sshd will bind to
#comment[5] = ListenAddress ::
#comment[6] = ListenAddress 0.0.0.0
Protocol = 2
..... more items...
#comment[11] = Authentication:
LoginGraceTime = 120
PermitRootLogin = prohibit-password
StrictModes = yes
RSAAuthentication = yes
..... more items...
augtool> print /files/etc/ssh/sshd_config
/files/etc/ssh/sshd_config
/files/etc/ssh/sshd_config/#comment[1] = "Package generated configuration file"
/files/etc/ssh/sshd_config/#comment[2] = "See the sshd_config(5) manpage for details"
/files/etc/ssh/sshd_config/#comment[3] = "What ports, IPs and protocols we listen for"
/files/etc/ssh/sshd_config/Port = "22"
/files/etc/ssh/sshd_config/#comment[4] = "Use these options to restrict which interfaces/protocols sshd will bind to"
/files/etc/ssh/sshd_config/#comment[5] = "ListenAddress ::"
/files/etc/ssh/sshd_config/#comment[6] = "ListenAddress 0.0.0.0"
/files/etc/ssh/sshd_config/Protocol = "2"
..... more items...
/files/etc/ssh/sshd_config/#comment[11] = "Authentication:"
/files/etc/ssh/sshd_config/LoginGraceTime = "120"
/files/etc/ssh/sshd_config/PermitRootLogin = "prohibit-password"
/files/etc/ssh/sshd_config/StrictModes = "yes"
/files/etc/ssh/sshd_config/RSAAuthentication = "yes"
..... more items...
```

修改配置项

```sh
augtool> get /files/etc/ssh/sshd_config/Port
/files/etc/ssh/sshd_config/Port = 22
augtool> get /files/etc/ssh/sshd_config/PermitRootLogin
/files/etc/ssh/sshd_config/PermitRootLogin = prohibit-password
augtool> set /files/etc/ssh/sshd_config/Port 25680
augtool> set /files/etc/ssh/sshd_config/PermitRootLogin yes
augtool> save
Saved 1 file(s)
augtool> get /files/etc/ssh/sshd_config/Port
/files/etc/ssh/sshd_config/Port = 25680
augtool> get /files/etc/ssh/sshd_config/PermitRootLogin
/files/etc/ssh/sshd_config/PermitRootLogin = yes
```

### /etc/sysctl.conf

添加配置项

```sh
augtool> set /files/etc/sysctl.conf fs.file-max 655350
augtool> set /files/etc/sysctl.conf/vm.swappiness 0
augtool> save
Saved 1 file(s)
augtool> get /files/etc/sysctl.conf/fs.file-max
/files/etc/sysctl.conf/fs.file-max = 655350
augtool> get /files/etc/sysctl.conf/vm.swappiness
/files/etc/sysctl.conf/vm.swappiness = 0
```

### /etc/systemd/system.conf

缺少 lenses

- [Allow Augeas Systemd.aug lenses to manage *.conf files](https://github.com/hercules-team/augeas/issues/299)

### /etc/security/limits.conf

配置文件内容

```sh
shell> vim /etc/security/limits.conf
..... more items...
#ftp             -       chroot          /ftp
#@student        -       maxlogins       4

root soft nofile 65535
root hard nofile 65535
* soft nofile 65535
* hard nofile 65535

# End of file
```

逐步解析配置文件

```sh
augtool> ls /files/etc/security/limits.conf
..... more items...
#comment[45] = ftp             -       chroot          /ftp
#comment[46] = @student        -       maxlogins       4
domain[1]/ = root
domain[2]/ = root
domain[3]/ = *
domain[4]/ = *
#comment[47] = End of file
augtool> ls /files/etc/security/limits.conf/domain[1]
type = soft
item = nofile
value = 65535
```

添加配置项

```sh
augtool> set /files/etc/security/limits.conf/domain[last()+1] suhua
augtool> set /files/etc/security/limits.conf/domain[last()]/type soft
augtool> set /files/etc/security/limits.conf/domain[last()]/item nofile
augtool> set /files/etc/security/limits.conf/domain[last()]/value 65535
augtool> save
Saved 1 file(s)
augtool> print /files/etc/security/limits.conf/domain[last()]
/files/etc/security/limits.conf/domain[5] = "suhua"
/files/etc/security/limits.conf/domain[5]/type = "soft"
/files/etc/security/limits.conf/domain[5]/item = "nofile"
/files/etc/security/limits.conf/domain[5]/value = "65535"
```

### /etc/php/7.2/fpm/php.ini

查看配置文件

```sh
shell> cat /etc/php/7.2/fpm/php.ini
[PHP]
..... more items...
upload_max_filesize = 2M
..... more items...
post_max_size = 8M
```

逐步解析配置文件

```sh
augtool> ls /files/etc/php/7.2/fpm/php.ini
PHP/ = (none)
CLI\ Server/ = (none)
Date/ = (none)
..... more items...
augtool> print /files/etc/php/7.2/fpm/php.ini/PHP
..... more items...
/files/etc/php/7.2/fpm/php.ini/PHP/upload_max_filesize = "2M"
..... more items...
/files/etc/php/7.2/fpm/php.ini/PHP/post_max_size = "8M"
..... more items...
```

修改配置项

```sh
augtool> set /files/etc/php/7.2/fpm/php.ini/PHP/upload_max_filesize 30M
augtool> set /files/etc/php/7.2/fpm/php.ini/PHP/post_max_size 30M
augtool> save
Saved 1 file(s)
augtool> get /files/etc/php/7.2/fpm/php.ini/PHP/upload_max_filesize
/files/etc/php/7.2/fpm/php.ini/PHP/upload_max_filesize = 30M
augtool> get /files/etc/php/7.2/fpm/php.ini/PHP/post_max_size
/files/etc/php/7.2/fpm/php.ini/PHP/post_max_size = 30M
```

### /etc/php/7.2/fpm/pool.d/www.conf

查看配置文件

```sh
shell> cat /etc/php/7.2/fpm/pool.d/www.conf 
; Start a new pool named 'www'.
; the variable $pool can be used in any directive and will be replaced by the
; pool name ('www' here)
[www]
..... more items...
listen = /run/php/php7.2-fpm.sock
..... more items...
; Note: There is a real-time FPM status monitoring sample web page available
;       It's available in: /usr/share/php/7.2/fpm/status.html
;
; Note: The value must start with a leading slash (/). The value can be
;       anything, but it may not be a good idea to use the .php extension or it
;       may conflict with a real PHP file.
; Default Value: not set
;pm.status_path = /status

; The ping URI to call the monitoring page of FPM. If this value is not set, no
; URI will be recognized as a ping page. This could be used to test from outside
; that FPM is alive and responding, or to
; - create a graph of FPM availability (rrd or such);
; - remove a server from a group if it is not responding (load balancing);
; - trigger alerts for the operating team (24/7).
; Note: The value must start with a leading slash (/). The value can be
;       anything, but it may not be a good idea to use the .php extension or it
;       may conflict with a real PHP file.
; Default Value: not set
;ping.path = /ping
..... more items...
```

逐步解析配置文件（已省略部分配置项)

```sh
augtool> ls /files/etc/php/7.2/fpm/pool.d/www.conf
.anon/ = (none)
www/ = (none)
augtool> print /files/etc/php/7.2/fpm/pool.d/www.conf
/files/etc/php/7.2/fpm/pool.d/www.conf/www/listen = "/run/php/php7.2-fpm.sock"
/files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[204] = "pm.status_path = /status"
/files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[215] = "ping.path = /ping"
```

修改配置项（打开配置项的方法：首先在注释后插入配置项，然后删除注释）

```sh
augtool> set /files/etc/php/7.2/fpm/pool.d/www.conf/www/listen 127.0.0.1:9000
augtool> save
Saved 1 file(s)
augtool> ins pm.status_path after /files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[. = 'pm.status_path = /status']
augtool> set /files/etc/php/7.2/fpm/pool.d/www.conf/www/pm.status_path phpfpm_status
augtool> rm /files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[. = 'pm.status_path = /status']
augtool> save
Saved 1 file(s)
augtool> ins ping.path after /files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[. = 'ping.path = /ping']
augtool> set /files/etc/php/7.2/fpm/pool.d/www.conf/www/ping.path phpfpm_ping
augtool> rm /files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[. = 'ping.path = /ping']
rm : /files/etc/php/7.2/fpm/pool.d/www.conf/www/#comment[. = 'ping.path = /ping'] 1
augtool> save
Saved 1 file(s)
```

- [php.aug: "set" to uncomment existing directives rather than adding new ones](https://github.com/hercules-team/augeas/issues/550)

## 参考文献

- [Resource tips and examples: Augeas](https://puppet.com/docs/puppet/5.5/resources_augeas.html)
- [Path expressions](https://github.com/hercules-team/augeas/wiki/Path-expressions)

