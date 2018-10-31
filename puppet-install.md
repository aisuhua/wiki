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

- [Puppet Server: Installing From Packages](https://puppet.com/docs/puppetserver/5.3/install_from_packages.html)

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

- [Installing Puppet agent: Linux](https://puppet.com/docs/puppet/5.5/install_linux.html)

## 签发证书

### 自动签发

server 添加 autosign.conf 配置

```sh
root@puppetmaster:~> vim /etc/puppetlabs/puppet/autosign.conf
*.aisuhua.net
```

### 手工签发

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

### stand-alone 模式

在 client 端添加测试脚本

```sh
shell> vim /tmp/site.pp
file { '/tmp/suhua.txt':
    content => 'suhua is a good boy.'
}
```

应用本地文件

```sh
shell> puppet apply /tmp/site.pp
```


### agent-master 模式

在 server 端添加测试脚本

```sh
root@puppetmaster:~> vim /etc/puppetlabs/code/environments/production/manifests/site.pp 
node default {
    file { '/tmp/suhua.txt':
        content => 'suhua is a good boy.'
    }
}
```

client 拉取并应用最新的 catalogs

```sh
root@puppetnode1:~> puppet agent --test --server puppetmaster.aisuhua.net
```

## 添加默认配置

修改 client 请求的默认服务器和环境 

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
