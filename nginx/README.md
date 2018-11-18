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

```conf
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

```conf
server {
    listen 80;
    listen [::]:80;
    
    server_name _;
    root /var/www/html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

## 操作

重新加载配置文件

```sh
shell> nginx -s reload
```

查看编辑参数

```sh
shell> nginx -V
```

## 参考文献

- [Install](https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- [Welcome to NGINX Wiki!](https://www.nginx.com/resources/wiki/)
- [如何正确配置 Nginx 和 PHP](http://blog.jobbole.com/50121/)
- [Using nginx as HTTP load balancer](http://nginx.org/en/docs/http/load_balancing.html)
- [Module ngx_http_upstream_module](http://nginx.org/en/docs/http/ngx_http_upstream_module.html)
- [TCP/UDP Load Balancing with NGINX: Overview, Tips, and Tricks](https://www.nginx.com/blog/tcp-load-balancing-udp-load-balancing-nginx-tips-tricks/#filter)
- [TCP and UDP Load Balancing](https://docs.nginx.com/nginx/admin-guide/load-balancer/tcp-udp-load-balancer/)
- [nginx负载均衡的5种策略](https://segmentfault.com/a/1190000014483200)
- [Nginx负载均衡配置](https://blog.csdn.net/xyang81/article/details/51702900)
- [nginx负载均衡](https://thief.one/2017/08/22/1/)
