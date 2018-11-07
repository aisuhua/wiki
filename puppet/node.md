## 安装

安装

```sh
root@puppetnode1:~> wget https://apt.puppetlabs.com/puppet5-release-xenial.deb
root@puppetnode1:~> dpkg -i puppet5-release-xenial.deb
root@puppetnode1:~> apt-get update
root@puppetnode1:~> apt-get install puppet-agent
root@puppetnode1:~> ln -s /opt/puppetlabs/bin/puppet /usr/bin/puppet
```

查看版本信息

```sh
root@puppetnode1:~> puppet --version
5.5.6
```

启动（可选）

```sh
root@puppetnode1:~> puppet resource service puppet ensure=running enable=true
```

- [Installing Puppet agent: Linux](https://puppet.com/docs/puppet/5.5/install_linux.html)

## 测试

### agent-master

拉取并应用最新的 catalogs

```sh
root@puppetnode1:~> puppet agent --test --environment production --server puppetmaster.aisuhua.net
```

### stand-alone

本地添加测试脚本

```sh
shell> tee /tmp/site.pp <<-'EOF'
file { '/tmp/hello.txt':
    content => 'Hello, World!'
}
EOF
```

应用本地文件

```sh
shell> puppet apply /tmp/site.pp
```

## 配置

修改请求的默认服务器地址和环境 

```sh
root@puppetnode1:~> vim /etc/puppetlabs/puppet/puppet.conf
[agent]
server = puppetmaster.aisuhua.net
environment = production
```

测试配置是否生效

```sh
root@puppetnode1:~> puppet agent --test
```

## 定时更新

有两种方法：

- 启动 puppet agent 后默认 30 分钟更新一次；
- 使用 cron 进行定时更新；

### puppet agent

```sh
root@puppetnode1:~> systemctl enable puppet
root@puppetnode1:~> vim /etc/puppetlabs/puppet/puppet.conf
[agent]
runinterval = 1800
```

- [Run Puppet agent as a service](https://puppet.com/docs/puppet/5.5/services_agent_unix.html#task-6309)

### cron

```sh
root@puppetnode1:~> systemctl disable puppet
root@puppetnode1:~> puppet resource cron puppet-agent ensure=present user=root minute=30 command='/opt/puppetlabs/bin/puppet agent --onetime --no-daemonize --splay --splaylimit 60'
```

- [Run Puppet agent as a cron job](https://puppet.com/docs/puppet/5.5/services_agent_unix.html#task-6309)


