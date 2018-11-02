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

### /etc/hosts 

配置文件内容

```sh
shell> cat /etc/hosts
127.0.0.1	localhost
127.0.1.1	ubuntu-server1

# The following lines are desirable for IPv6 capable hosts
::1     localhost ip6-localhost ip6-loopback
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
127.0.0.1 wp-web1.192.168.1.2.local.aisuhua.net wp-web1
```

逐步解析配置文件

```sh
augtool> ls /files/etc/hosts
1/ = (none)
2/ = (none)
#comment = The following lines are desirable for IPv6 capable hosts
3/ = (none)
4/ = (none)
5/ = (none)
6/ = (none)
augtool> ls /files/etc/hosts/6
ipaddr = 127.0.0.1
canonical = wp-web1.192.168.1.2.local.aisuhua.net
alias = wp-web1
augtool> print /files/etc/hosts/6
/files/etc/hosts/6
/files/etc/hosts/6/ipaddr = "127.0.0.1"
/files/etc/hosts/6/canonical = "wp-web1.192.168.1.2.local.aisuhua.net"
/files/etc/hosts/6/alias = "wp-web1"
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
Defaults	env_reset
Defaults	mail_badpass
Defaults	secure_path="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin"

# Host alias specification

# User alias specification

# Cmnd alias specification

# User privilege specification
root	ALL=(ALL:ALL) ALL

# Members of the admin group may gain root privileges
%admin ALL=(ALL) ALL

# Allow members of group sudo to execute any command
%sudo	ALL=(ALL:ALL) ALL

# See sudoers(5) for more information on "#include" directives:

suhua	ALL=(ALL:ALL) NOPASSWD: ALL

#includedir = /etc/sudoers.d
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
#comment[11] = See sudoers(5) for more information on "#include" directives:
spec[4]/ = (none)
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
```

- [Resource tips and examples: Augeas](https://puppet.com/docs/puppet/5.5/resources_augeas.html)
