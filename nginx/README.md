## 安装

```sh
shell> wget http://nginx.org/keys/nginx_signing.key
shell> sudo apt-key add nginx_signing.key
shell> vim /etc/apt/sources.list.d/nginx.list
deb http://nginx.org/packages/ubuntu/ xenial nginx
deb-src http://nginx.org/packages/ubuntu/ xenial nginx
shell> apt-get update
shell> apt-get install nginx
```
