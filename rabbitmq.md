安装

```sh
shell> wget -O - 'https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc' | sudo apt-key add -
shell> vim /etc/apt/sources.list.d/bintray.rabbitmq.list
deb https://dl.bintray.com/rabbitmq/debian xenial main erlang

shell> apt-get update
shell> apt-get install erlang-nox
shell> apt-get install rabbitmq-server

https://www.rabbitmq.com/install-debian.html
```

创建配置文件

```sh
shell> cd /etc/rabbitmq
shell> wget https://raw.githubusercontent.com/rabbitmq/rabbitmq-server/master/docs/rabbitmq.conf.example
shell> mv rabbitmq.conf.example rabbitmq.conf

https://www.rabbitmq.com/configure.html
```

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

