## 安装

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

## 配置

添加 autosign.conf

```sh
root@puppetmaster:~> vim /etc/puppetlabs/puppet/autosign.conf
*.aisuhua.net
```

或者手工为 agent 签发证书

```sh
root@puppetmaster:~> puppet cert list
root@puppetmaster:~> puppet cert sign puppetnode1.aisuhua.net
```

## 测试

添加测试脚本

```sh
root@puppetmaster:~> vim /etc/puppetlabs/code/environments/production/manifests/site.pp 
node default {
    file { '/tmp/hello.txt':
        content => 'Hello, World!'
    }
}
```

## 其他

验证语法是否正确

```sh
shell> puppet parser validate site.pp
```

查看资源的状态

```sh
shell> puppet resource service ssh
```

安装外部模块

```sh
shell> puppet module install saz-sudo
```

- [Installing and managing modules from the command line](https://puppet.com/docs/puppet/5.5/modules_installing.html)


查看配置信息

```sh
shell> puppet config print environmentpath basemodulepath modulepath manifest --section master
```

- [Configuration: Checking values of settings](https://puppet.com/docs/puppet/5.5/config_print.html)

