## 安装

简易安装

```sh
shell> apt-get install python-software-properties
shell> add-apt-repository ppa:ondrej/php
shell> apt-get update
shell> apt-get install -y php7.2-common php7.2-dev php7.2-cli php7.2-fpm
```

- [ubuntu 使用apt-get install 安装php5.6--php7](https://www.cnblogs.com/phpzhou/p/6288762.html)

完整安装

```sh
shell> apt-get install python-software-properties
shell> add-apt-repository ppa:ondrej/php
shell> apt-get update
shell> apt-get install -y \
php7.2-common \
php7.2-dev \
php7.2-cli \
php7.2-fpm \
php7.2-xml \
php7.2-curl \
php7.2-mbstring \
php7.2-bcmath \
php7.2-gd \
php7.2-bz2 \
php7.2-zip \
php7.2-dba \
php7.2-mysql \
php7.2-soap \
php-pear \
php-imagick \
php-msgpack \
php-igbinary \
php-mongodb \
php-memcache \
php-memcached \
php-redis \
php-amqp
```

- [ppa:ondrej/php](https://launchpad.net/~ondrej/+archive/ubuntu/php?field.series_filter=xenial)

查看当前默认使用的 PHP 版本

```sh
shell> php -v
shell> update-alternatives --display php
shell> update-alternatives --display php-config
shell> update-alternatives --display phpize
```

设置默认版本

```sh
shell> update-alternatives --set php /usr/bin/php7.2
shell> update-alternatives --set php-config /usr/bin/php-config7.2
shell> update-alternatives --set phpize /usr/bin/phpize7.2
```

- [使用update-alternatives命令进行版本的切换](https://blog.csdn.net/JasonDing1354/article/details/50470109)

### 安装其他扩展

#### Xdebug

```sh
shell> apt-get install php-xdebug
```

#### Gearman

```sh
shell> add-apt-repository ppa:ondrej/pkg-gearman
shell> apt-get update
shell> apt-get install php-gearman
```

- [libgearman8 dependency for php-gearman has no installable candidate](https://github.com/oerdnj/deb.sury.org/issues/711)

#### Yar

```sh
shell> apt-get install curl libcurl4-gnutls-dev
shell> pecl install yar
shell> echo 'extension=yar.so' > /etc/php/7.2/mods-available/yar.ini
shell> ln -s /etc/php/7.2/mods-available/yar.ini /etc/php/7.2/cli/conf.d/20-yar.ini
shell> ln -s /etc/php/7.2/mods-available/yar.ini /etc/php/7.2/fpm/conf.d/20-yar.ini
```

- [laruence/yar](https://github.com/laruence/yar)
- [configure: error: Please reinstall the libcurl distribution](https://github.com/laruence/yar/issues/111)

#### Couchbase

```sh
shell> wget http://packages.couchbase.com/releases/couchbase-release/couchbase-release-1.0-4-amd64.deb
shell> dpkg -i couchbase-release-1.0-4-amd64.deb
shell> apt-get update
shell> apt-get install libcouchbase-dev build-essential php7.2-dev zlib1g-dev
shell> pecl install couchbase
shell> echo 'extension=couchbase.so' > /etc/php/7.2/mods-available/couchbase.ini
shell> ln -s /etc/php/7.2/mods-available/couchbase.ini /etc/php/7.2/cli/conf.d/25-couchbase.ini
shell> ln -s /etc/php/7.2/mods-available/couchbase.ini /etc/php/7.2/fpm/conf.d/25-couchbase.ini
```

- [Install and Start Using the PHP SDK with Couchbase Server](https://docs.couchbase.com/php-sdk/2.6/start-using-sdk.html)

#### Phalcon

```sh
shell> apt-get install php-phalcon
```

## 修改配置

可上传的最大文件大小

```sh
shell> vim /etc/php/7.2/fpm/php.ini
upload_max_filesize = 30M
post_max_size = 30M
shell> vim /etc/php/7.2/cli/php.ini
upload_max_filesize = 30M
post_max_size = 30M
```

## 管理

php-fpm 重新加载配置文件

```sh
shell> service php7.2-fpm reload
```

查看扩展所在目录

```sh
shell> php -i | grep extension_dir
```

启用 PHP 扩展，该方法跟添加软连接的效果一样。

```sh
phpenmod -v 7.2 yar
phpenmod -s cli yar
phpenmod -s fpm yar
```

停用 PHP 扩展

```sh
phpdismod -v 7.2 yar
phpenmod -s cli yar
```

- [How to Enable/Disable PHP Modules on Ubuntu 18.04 & 16.04](https://tecadmin.net/enable-disable-php-modules-ubuntu/)

## 内置服务器

基本使用

```sh
shell> cd ~/public_html
shell> tee index.php <<-'EOF'
<?php
phpinfo();
EOF
shell> php -S 0.0.0.0:8000
```

- [Built-in web server](http://docs.php.net/manual/da/features.commandline.webserver.php)

## 参考文献

- [PostgreSQL UNIX domain sockets vs TCP sockets](https://stackoverflow.com/questions/257433/postgresql-unix-domain-sockets-vs-tcp-sockets/257479#257479)
- [nginx和php-fpm 是使用 tcp socket 还是 unix socket ？](https://github.com/gaoxt/blog/issues/9)
