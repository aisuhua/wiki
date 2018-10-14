安装

```sh
shell> wget -O - 'https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc' | sudo apt-key add -
shell> vim /etc/apt/sources.list.d/bintray.rabbitmq.list
deb https://dl.bintray.com/rabbitmq/debian xenial main erlang

shell> apt-get update
shell> apt-get install erlang-nox
shell> apt-get install rabbitmq-server
```

- [Installing on Debian and Ubuntu](https://www.rabbitmq.com/install-debian.html)

查看是否启动

```sh
shell> service rabbitmq-server status
```

创建配置文件

```sh
shell> cd /etc/rabbitmq
shell> wget https://raw.githubusercontent.com/rabbitmq/rabbitmq-server/master/docs/rabbitmq.conf.example
shell> mv rabbitmq.conf.example rabbitmq.conf
```

- [Configuration](https://www.rabbitmq.com/configure.html)

安装控制台

```sh
shell> rabbitmq-plugins enable rabbitmq_management

```

访问控制台 http://localhost:15672

允许 guest 帐号远程登录访问

```sh
shell> vim /etc/rabbitmq/rabbitmq.conf
## Uncomment the following line if you want to allow access to the
## guest user from anywhere on the network.
loopback_users.guest = false
```

查看运行状态，比如版本信息

```sh
shell> rabbitmqctl status
```

查看正在运行的实例配置信息

```sh
shell> rabbitmqctl environment
```

## 创建集群

修改 hosts 

```sh
shell> vim /etc/hosts
192.168.31.200 rabbit1
192.168.31.201 rabbit2
192.168.31.202 rabbit3
```

复制 Erlang Cookie

```sh
root@rabbit1:> scp /var/lib/rabbitmq/.erlang.cookie root@192.168.31.201:/var/lib/rabbitmq/.erlang.cookie
root@rabbit1:> scp /var/lib/rabbitmq/.erlang.cookie root@192.168.31.202:/var/lib/rabbitmq/.erlang.cookie
```

重启节点，让 cookie 生效

```sh
root@rabbit2:> service rabbitmq-server restart
root@rabbit3:> service rabbitmq-server restart
```

加入集群

```sh
root@rabbit2:> rabbitmqctl stop_app
root@rabbit2:> rabbitmqctl join_cluster rabbit@rabbit1
root@rabbit2:> rabbitmqctl start_app
root@rabbit2:> rabbitmqctl cluster_status

root@rabbit3:> rabbitmqctl stop_app
root@rabbit3:> rabbitmqctl join_cluster rabbit@rabbit1
root@rabbit3:> rabbitmqctl start_app
root@rabbit3:> rabbitmqctl cluster_status
```

安装 management UI

```sh
root@rabbit2:> rabbitmq-plugins enable rabbitmq_management
root@rabbit3:> rabbitmq-plugins enable rabbitmq_management
```

参考

- [Clustering Guide](https://www.rabbitmq.com/clustering.html)

