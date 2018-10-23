## puppet server

安装

```sh
root@puppetmaster:~> wget https://apt.puppetlabs.com/puppet5-release-xenial.deb
root@puppetmaster:~> dpkg -i puppet5-release-xenial.deb
root@puppetmaster:~> apt-get update
root@puppetmaster:~> apt-get install puppetserver
root@puppetmaster:~> ln -s /opt/puppetlabs/bin/puppet /usr/bin/puppet
root@puppetmaster:~> ln -s /opt/puppetlabs/bin/puppetserver /usr/bin/puppetserver
```

查看版本信息

```sh
root@puppetmaster:~> puppetserver --version 
puppetserver version: 5.3.5
root@puppetmaster:~> puppet --version
5.5.6
```

启动

```sh
root@puppetmaster:~> service puppetserver start
```

## puppet agent

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

启动

```sh
root@puppetnode1:~> /opt/puppetlabs/bin/puppet resource service puppet ensure=running enable=true
```

## 获取证书

agent 首次请求 server

```sh
root@puppetnode1:~> puppet agent --test --server puppetmaster.aisuhua.net
```

server 为 agent 签发证书

```sh
root@puppetmaster:~> puppet cert list
root@puppetmaster:~> puppet cert sign puppetnode1.aisuhua.net
```

## 测试

在 server 端添加测试脚本

```sh
root@puppetmaster:~> vim /etc/puppetlabs/code/environments/production/manifests/site.pp 
node default {
    file { '/tmp/suhua.txt':
        content => 'suhua is a good boy.'
    }
}
```

agent 拉取并应用最新的 catalogs

```sh
root@puppetnode1:~> puppet agent --test --server puppetmaster.aisuhua.net
```

## 添加默认配置

配置 agent 请求的默认服务器和环境 

```sh
root@puppetnode1:~> vim /etc/puppetlabs/puppet/puppet.conf
[agent]
server=puppetmaster.aisuhua.net
environment=production
```

重启 puppet agent，让配置生效

```sh
root@puppetnode1:~> service puppet restart
```

测试配置是否生效

```sh
root@puppetnode1:~> puppet agent --test
```
