## 安装

安装

```sh
shell> sudo add-apt-repository ppa:nginx/stable
shell> apt-get update
shell> apt-get install nginx
```

- [NGINX PPA](https://launchpad.net/~nginx/+archive/ubuntu/stable)


启动

```sh
shell> service nginx start
```

## 配置示例

静态站点

```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    server_name _;
    root /var/www/html;

    location / {
        try_files $uri $uri/ =404;
    }
}
```

PHP 站点

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name foo.aisuhua.com;
    root /www/web/foo;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

错误页面处理

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name foo.aisuhua.com;
    root /www/web/foo;
    
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
    
    # 在 /www/web/foo 目录下放置 404.html 和 50x.html 页面
    location ~ /(404|50x)\.html {
        internal;
    }
}
```

静态资源缓存

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name foo.aisuhua.com;
    root /www/web/foo;
    
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
    
    # 在 /www/web/foo 目录下放置 404.html 和 50x.html 页面
    location ~ /(404|50x)\.html {
        internal;
    }
    
    # 如需刷新浏览器缓存，则需要在请求时加入随机数 ?r=random()
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 24h;
    }
}
```

- [如何正确配置 Nginx 和 PHP](http://blog.jobbole.com/50121/)

## 负载均衡

### HTTP

服务器规划

| 服务器名称   | IP             | 用途          |
| ------------ | -------------- | ------------- |
| Nginx Master | 192.168.31.220 | 提供负载均衡  |
| Web1 服务器  | 192.168.31.201 | 提供 Web 服务 |
| Web2 服务器  | 192.168.31.202 | 提供 Web 服务 |

配置示例

```nginx
http {
    upstream foo {
        ip_hash;
        server 192.168.31.201:80;
        server 192.168.31.202:80;
    }

    server {
        listen 80;
        server_name foo.aisuhua.com;

        location / {
            include /etc/nginx/proxy_params;
            proxy_pass http://foo;
        }
    }
}
```

- [Using nginx as HTTP load balancer](http://nginx.org/en/docs/http/load_balancing.html)
- [Module ngx_http_upstream_module](http://nginx.org/en/docs/http/ngx_http_upstream_module.html)

### TCP

服务器规划

| 服务器名称   | IP             | 用途            |
| ------------ | -------------- | --------------- |
| Nginx-Master | 192.168.1.168 | 提供负载均衡    |
| DB1 服务器  | 192.168.1.40 | 提供 MySQL 服务   |
| DB2 服务器  | 192.168.1.41 | 提供 MySQL 服务   |

配置示例

```nginx
user www-data;
worker_processes auto;
pid /run/nginx.pid;
include /etc/nginx/modules-enabled/*.conf;

events {
    use epoll;
    worker_connections 65535;
    # multi_accept on;
}

stream {
    upstream mysql_backend {
        # hash $remote_addr consistent;
        server 192.168.1.40:3306;
        server 192.168.1.41:3306;
        # ...
    }

    server {
        listen 3306;
        proxy_pass mysql_backend;
    }
    # ...
}
```

- [Module ngx_stream_core_module](http://nginx.org/en/docs/stream/ngx_stream_core_module.html)
- [TCP/UDP Load Balancing with NGINX: Overview, Tips, and Tricks](https://www.nginx.com/blog/tcp-load-balancing-udp-load-balancing-nginx-tips-tricks/)

## 基本操作
    
重新加载配置文件

```sh
shell> nginx -s reload
```

查看编辑参数

```sh
shell> nginx -V
```

### 重新编译 Nginx，添加新的模块

下载与当前已安装版本一致的安装包

```bash
wget http://nginx.org/download/nginx-1.14.1.tar.gz
tar -zxvf nginx-1.14.1.tar.gz
cd nginx-1.14.1
```

查看原来的编译参数

```bash
nginx -V
```

在编译参数最后面添加所需模块

```bash
./configure --with-http_secure_link_module
make 
```

此时在 objs 文件夹里就有新编译后的 nginx 二进制文件，将它覆盖之前的即可

```bash
mv /usr/sbin/nginx /usr/sbin/nginx.bak
cp objs/nginx /usr/sbin
```

参考[Nginx-重新编译安装模块及平滑升级](https://www.zybuluo.com/cdmonkey/note/712348)

## 日志配置

- [Configuring Logging](https://docs.nginx.com/nginx/admin-guide/monitoring/logging/)

## 其他

proxy_pass 不传递路径

```
location = /en {
    return 302 /en/;
}
location /en/ {
    proxy_pass http://luscious/;  # note the trailing slash here, it matters!
}
```

- [How to remove the path with an nginx proxy_pass](https://serverfault.com/questions/562756/how-to-remove-the-path-with-an-nginx-proxy-pass)

## 参考文献

- [Welcome to NGINX Wiki!](https://www.nginx.com/resources/wiki/)
- [Nginx负载均衡配置](https://blog.csdn.net/xyang81/article/details/51702900)
- [TCP and UDP Load Balancing](https://docs.nginx.com/nginx/admin-guide/load-balancer/tcp-udp-load-balancer/)
