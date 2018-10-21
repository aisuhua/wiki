## 服务器情况

1 台 master 和 2 台 node，共 3 台服务器

```
puppetmaster.aisuhua.net
puppetnode1.aisuhua.net
puppetnode2.aisuhua.net
```

## puppet master

安装 puppet master

```sh
shell> wget https://apt.puppetlabs.com/puppet5-release-xenial.deb
shell> dpkg -i puppet5-release-xenial.deb
shell> apt-get update
shell> apt-get install puppetserver
shell> service puppetserver start
shell> ln -s /opt/puppetlabs/bin/puppet /usr/bin/puppet
shell> ln -s /opt/puppetlabs/bin/puppetserver /usr/bin/puppetserver
```

- [Puppet Server: Installing From Packages](https://puppet.com/docs/puppetserver/5.3/install_from_packages.html)

添加测试脚本

```sh
shell> vim /etc/puppetlabs/code/environments/production/manifests/site.pp 
node default {
    file { '/tmp/suhua.txt':
        content => 'suhua is a good boy.'
    }
}
```

## puppet agent

安装 puppet agent

```sh
shell> wget https://apt.puppetlabs.com/puppet5-release-xenial.deb
shell> dpkg -i puppet5-release-xenial.deb
shell> apt-get update
shell> apt-get install puppet-agent
shell> /opt/puppetlabs/bin/puppet resource service puppet ensure=running enable=true
shell> ln -s /opt/puppetlabs/bin/puppet /usr/bin/puppet
```

- [Installing Puppet agent: Linux](https://puppet.com/docs/puppet/5.5/install_linux.html)

向 master 申请签名

```sh
a@puppetnode1:~> puppet agent --test --server puppetmaster.aisuhua.net
a@puppetmaster:~> puppet cert list
a@puppetmaster:~> puppet cert sign puppetnode1.aisuhua.net
```

拉取并应用最新的 catalogs

```sh
a@puppetnode1:~> puppet agent --test --server puppetmaster.aisuhua.net
```

查看 master 节点的配置信息

```sh
a@puppetnode1:~> puppet config print modulepath --section master
a@puppetnode1:~> puppet config print default_manifest --section master
```

- [Creating environments](https://puppet.com/docs/puppet/5.5/environments_creating.html)

### 添加配置

设置默认 master 和 environment

```sh
a@puppetnode1:~> vim /etc/puppetlabs/puppet/puppet.conf
[agent]
server=puppetmaster.aisuhua.net
environment=production
root@puppetnode1:~> service puppet restart
```

使用默认配置拉取并应用最新的 catalogs

```sh
a@puppetnode1:~> puppet agent --test
```

使用非默认环境

```sh
a@puppetnode1:~> puppet agent --test --environment huidu
```


