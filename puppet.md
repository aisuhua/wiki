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

安装 puppet agent

```sh
```sh
shell> wget https://apt.puppetlabs.com/puppet5-release-xenial.deb
shell> dpkg -i puppet5-release-xenial.deb
shell> apt-get update
shell> apt-get install puppet-agent
shell> /opt/puppetlabs/bin/puppet resource service puppet ensure=running enable=true
shell> ln -s /opt/puppetlabs/bin/puppet /usr/bin/puppet
```
