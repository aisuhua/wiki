## 安装

安装

```sh
shell> apt-get install augeas-tools
```

支持的配置格式

```sh
shell> ls /usr/share/augeas/lenses/dist/
```

- [augeas](https://github.com/hercules-team/augeas)

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
- [Resource tips and examples: Augeas](https://puppet.com/docs/puppet/5.5/resources_augeas.html)

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
augtool> get /files/etc/hosts/*/alias[. = 'wp-web1'] # alias 为 wp-web1 的选项
/files/etc/hosts/*/alias[. = 'wp-web1'] = wp-web1
augtool> ls /files/etc/hosts/*[alias = 'wp-web1'] # alias 为 wp-web1 的条目
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[ipaddr = '127.0.0.1'] # IP 地址一致的条目
ipaddr = 127.0.0.1
canonical = localhost
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias[1] = wp-web1
alias[2] = wp-web1.192.168.1.2.local
augtool> ls /files/etc/hosts/*[ipaddr = '127.0.0.1'][alias] # IP 地址一致且有 alias 的条目
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

### /etc/ssh/sshd_config

修改配置项

```sh
augtool> get /files/etc/ssh/sshd_config/Port
/files/etc/ssh/sshd_config/Port = 22
augtool> get /files/etc/ssh/sshd_config/PermitRootLogin
/files/etc/ssh/sshd_config/PermitRootLogin = no
augtool> set /files/etc/ssh/sshd_config/Port 25680
augtool> set /files/etc/ssh/sshd_config/PermitRootLogin yes
augtool> save
Saved 1 file(s)
augtool> get /files/etc/ssh/sshd_config/Port
/files/etc/ssh/sshd_config/Port = 25680
augtool> get /files/etc/ssh/sshd_config/PermitRootLogin
/files/etc/ssh/sshd_config/PermitRootLogin = yes
```
