## 安装

安装 PHP 7.2

```sh
shell> apt-get install python-software-properties
shell> add-apt-repository ppa:ondrej/php
shell> apt-get update
shell> apt-get install -y php7.2-common php7.2-dev php7.2-cli php7.2-fpm php-pear php7.2-xml
```

- [ubuntu 使用apt-get install 安装php5.6--php7](https://www.cnblogs.com/phpzhou/p/6288762.html)

## 内置服务器

基本使用

```sh
shell> cd ~/public_html
shell> vim index.php
<?php

phpinfo();
shell> php -S 0.0.0.0:8000
```

- [Built-in web server](http://docs.php.net/manual/da/features.commandline.webserver.php)
