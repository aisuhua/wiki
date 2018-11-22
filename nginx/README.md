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
| MySQL1 服务器  | 192.168.1.40 | 提供 MySQL 服务   |
| MySQL2 服务器  | 192.168.1.41 | 提供 MySQL 服务   |

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

## 参考文献

- [Welcome to NGINX Wiki!](https://www.nginx.com/resources/wiki/)
- [Nginx负载均衡配置](https://blog.csdn.net/xyang81/article/details/51702900)
- [TCP and UDP Load Balancing](https://docs.nginx.com/nginx/admin-guide/load-balancer/tcp-udp-load-balancer/)
