# 图片缩略图

本示例使用 [ngx_small_light][1] 实现图片缩略图功能，该模块实际上也是使用了 [ImageMagick][2] 实现缩图相关的功能。 

## 安装

参考 [README][1] 进行安装即可，在 Ubuntu 16.04 安装前先确保已安装以下两个依赖：

```bash
apt-get install imagemagick libmagickwand-dev
```

## 基础示例

```nginx
# /www/web/thumb_basic.conf
server {
    listen 80;
    server_name thumb.example.com;

    root /tmp/files;

    small_light on;
    location ~ small_light[^/]*/(.+)$ {
        set $file $1;
        rewrite ^ /$file;
    }
}
```

`rewrite` 重写到的地址必须是能读取到图片内容的地址，若以下地址能访问到原图：

```
http://thumb.example.com/goods.jpg
```

则可以通过以下地址访问缩略图：

```
http://thumb.example.com/small_light(dw=300,dh=300)/goods.jpg
```

## 使用远程服务器的图片生成缩略图

基础示例中的原图图片必须是在本机，要实现读取远程服务器上的图片，然后在本机生成缩略图需要借助以下两个模块：

- [X-Sendfile][3]
- [ngx_http_proxy_module][4]

其原理是让 Nginx 代理完成原图的下载，然后提供给本地缩图模块进行处理。

实际上 `X-Accel-Redirect` 一般只用于让 Nginx 下载本地的文件，所以这里需要结合 `proxy_pass` 功能，让它能下载远程服务器上的文件。

```nginx
# /www/web/thumb_remote_img.conf
server {
    listen 80;
    server_name thumb.example.com;
    
    small_light on;
    location ~ small_light[^/]*/(.+)$ {
        set $file $1;
        rewrite ^ /get_image.php?file=$file;
    }
    
    location ~ \.php$ {
        root /www/web;
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
    
    location ~* ^/internal_redirect/(.*?)/(.*?)/(.*) {
        internal;
        
        resolver 114.114.114.114;
        
        set $download_protocol $1;
        set $download_host $2;
        set $download_uri $3;
        set $download_url $download_protocol://$download_host/$download_uri$is_args$args;
        
        proxy_max_temp_file_size 0;
        
        proxy_pass $download_url;
    }
}
```

get_image.php 文件用于获取图片的下载地址，并提供给 Nginx 完成下载。

```php
<?php
$file = $_GET['file']; 
$protocol = 'https';
$host = 'raw.githubusercontent.com';
$uri = "aisuhua/upload-demo/master/basic/uploads/{$file}";
$args = '';

header("X-Accel-Redirect: /internal_redirect/{$protocol}/{$host}/{$uri}{$args}");
```

通过以下地址可以访问到存储在 Github 上的原图：

```
https://raw.githubusercontent.com/aisuhua/upload-demo/master/basic/uploads/1.png
https://raw.githubusercontent.com/aisuhua/upload-demo/master/basic/uploads/2.png
```

查看缩略图

```
http://thumb.example.com/small_light(dw=50,dh=50)/1.png
http://thumb.example.com/small_light(dw=100,dh=100)/2.png
```

## 缩略图缓存

为了避免每次访问缩略图都需要重新生成，我们可以利用 `proxy_cache` 相关指令实现缩略图缓存。

### 缓存层配置

缓存层的主要作用是缓存缩略图，它并不执行生成缩略图，缩略图的生成在下面即将所说的缩图层完成。

```nginx
# /etc/nginx/thumb_cache.conf
proxy_cache_path /tmp/cache levels=1:2 keys_zone=images:10m inactive=1h max_size=10G use_temp_path=off;

server {
    listen 80;
    server_name thumb.example.com;	

    location / {
        # 可选，可在这里实现防盗链等限制
        valid_referers none blocked *.example.com;
        if ($invalid_referer) {
            return 403;
        }

        proxy_cache images;
        proxy_cache_valid 200 1h;
        proxy_cache_key "$scheme://$host$request_uri$is_args$args";
        proxy_pass http://127.0.0.1:81;
        proxy_set_header Host $host;

        # 用于判断缓存是否命中
        add_header X-Cache-Status $upstream_cache_status;
    }
}
```

需要创建缓存目录并赋予写入权限（假设以 www-data 用户运行 Nginx）

```bash
mkdir /tmp/cache
chown www-data:www-data /tmp/cache
```

### 缩图层配置

缩图层是执行生成图片缩略图的一层。

```nginx
# /etc/nginx/thumb.conf
server {
    listen 81;
    server_name thumb.example.com;

    root /tmp/files;

    small_light on;
    location ~ small_light[^/]*/(.+)$ {
        set $file $1;
        rewrite ^ /$file;
    }
}
```

缓存层和缩图层可以在同一台机器上或不同的多台机器上。

例如：缓存层用 2 台硬盘性能好、空间大的机器，而缩图层用 10 台 CPU 性能较好的机器做负载均衡。

### 测试

原图访问地址如下：

```
http://thumb.example.com/goods.jpg
```

查看缩图地址

```
GET http://thumb.example.com/small_light(dw=50,dh=50)/goods.jpg
X-Cache-Status:MISS
```

第二次查看缩图地址

```
GET http://thumb.example.com/small_light(dw=50,dh=50)/goods.jpg
X-Cache-Status:HIT
```

`HIT` 说明命中了缓存，第二次请求的缩略图是从缓存取，没有再次生成。

## 自定义缩图模板

为了方便调用，可以先定义好几个缩略图模板，然后在地址栏中使用模板名称调用对应规格的缩略图。

```nginx
server {
    small_light on;
    small_light_pattern_define msize dw=500,dh=500,da=l,q=95,e=imagemagick,jpeghint=y;
    small_light_pattern_define ssize dw=120,dh=120,da=l,q=95,e=imlib2,jpeghint=y;
}
```

调用方法

```
http://thumb.example.com/small_light(p=msize)/goods.jpg
http://thumb.example.com/small_light(p=ssize)/goods.jpg
```

## 使用 GET 参数

要想通过 `GET` 参数来传递缩略图生成参数，可开启 `small_light_getparam_mode` 配置项。
 
```nginx
server {
    small_light on;
    small_light_getparam_mode on;
}
```

此时可通过以下方式访问缩略图，需要注意的是此时不能再使用 small_light function 的调用方式。

```
http://thumb.example.com/small_light/goods.jpg?dw=120&dh=120
```

## 其他

使用 `proxy_pass` 访问外部地址时，需要配置 `resolver`，可参考 [Nginx resolver DNS 解析超时问题分析及解决][5]。

X-Sendfile 的用法，可参考以下文章：

- [X-Accel][3]
- [Nginx+PHP 做下载服务器配置—X-Accel-Redirect][13]
- [Using X-Accel-Redirect in Nginx to Implement Controlled Downloads][6]
- [Nginx-Fu: X-Accel-Redirect From Remote Servers][7]
- [Using NGINX’s X-Accel with Remote URLs][10]
- [X accell redirect s3][9]

缓存的用法，可参考以下文章：

- [Does ngx_small_light cache resized images?][11]
- [ngx_http_proxy_module][4]
- [A Guide to Caching with NGINX and NGINX Plus][8]

最后，如果你想对 [ImageMagick][2] 有更多了解，可参考 [图像处理 - ImageMagick 简单介绍与案例][12]。

补充 [ngx_small_light][1] 作者演讲的 PPT 和个人博客

- [ngx_small_light at 第2回闇鍋プログラミング勉強会][14]
- [ngx_small_light][15]
- [mod_small_lightのNginx版ngx_small_lightをつくってみた][16]

还可以搭建一个可以生成任何图片的缩图服务，只需要提供一个下载地址给它，相关资料：

- [thumbor][17]
- [OpenRoss – fast, scalable, on-demand image resizer][18]

[1]: https://github.com/cubicdaiya/ngx_small_light
[2]: http://www.imagemagick.org/script/index.php
[3]: https://www.nginx.com/resources/wiki/start/topics/examples/x-accel/
[4]: http://nginx.org/en/docs/http/ngx_http_proxy_module.html
[5]: https://jaminzhang.github.io/nginx/Nginx-resolver-DNS-resolve-timed-out-problem-analysis-and-solve/
[6]: https://kovyrin.net/2006/11/01/nginx-x-accel-redirect-php-rails/
[7]: https://kovyrin.net/2010/07/24/nginx-fu-x-accel-redirect-remote/
[8]: https://www.nginx.com/blog/nginx-caching-guide/
[9]: https://github.com/georgeredinger/secret-video/wiki/X-accell-redirect-s3
[10]: https://www.mediasuite.co.nz/blog/proxying-s3-downloads-nginx/
[11]: https://github.com/cubicdaiya/ngx_small_light/issues/30
[12]: https://aotu.io/notes/2018/06/06/ImageMagick_intro/index.html
[13]: http://tuyile.com/blog/20160118/nginx_php_download_server_configuration.html
[14]: https://www.slideshare.net/cubicdaiya/ngx-small-lightyaminabe
[15]: https://www.slideshare.net/cubicdaiya/ngx-small-light-24010386
[16]: http://cubicdaiya.github.io/blog/ja/blog/2012/06/13/ngx-small-light/
[17]: https://github.com/thumbor/thumbor
[18]: https://news.ycombinator.com/item?id=7931744