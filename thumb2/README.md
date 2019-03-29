# 图片缩略图2

本示例使用 Nginx 官方的 [image_filter][1] 模块动态生成图片的缩略图，它有别与[此前][3]所介绍的 [ngx_small_light][2] 第三方模块。

主要区别如下：

- [ngx_small_light][2] 支持的更多缩图引擎（ImageMagick/Imlib2/GD），而 [image_filter][1] 只支持 GD。
- [ngx_small_light][2] 功能更加丰富，例如：图片合成、图片格式转换等等，这些 [image_filter][1] 都是不支持。

两者在使用上区别不大，更多有关两者的区别可参考 [PPT][4]。

## 基础示例

该示例演示如何用本地图片生成一张 200x200 的缩略图。

```nginx
server {
    listen 80;
    server_name avatar.example.com;

    root /tmp/files;

    location ~ /resize/(.+)$ {
        set $file $1;
        rewrite ^ /$file break;

        image_filter_interlace on;
        image_filter_buffer 20M;
        image_filter_jpeg_quality 95;
        image_filter resize 200 200;

        error_page 415 = /empty;
    }

    location = /empty {
        empty_gif;
    }
}
```

查看原图

```
http://avatar.example.com/1.png
```

查看缩略图

```
http://avatar.example.com/resize/1.png
```

## 使用远程图片生成缩略图

```nginx
server {
    listen 80;
    server_name avatar.example.com;

    location ~ ^/resize/(.+)$ {
        set $image $1;
        set $width 200;
        set $height 200;

        if ($arg_w) {
            set $width $arg_w;
        }

        if ($arg_h) {
            set $height $arg_h;
        }

        resolver 114.114.114.114;
        resolver_timeout 5s;

        set $backend_host https://raw.githubusercontent.com;
        set $backend_uri /aisuhua/upload-demo/master/basic/uploads/$image;

        proxy_pass $backend_host$backend_uri;

        image_filter_interlace on;
        image_filter_buffer 20M;
        image_filter_jpeg_quality 95;
        image_filter resize $width $height;

        error_page 415 = /empty;
    }

    location = /empty {
        empty_gif;
    }
}
```

访问原图

```
https://raw.githubusercontent.com/aisuhua/upload-demo/master/basic/uploads/1.png
https://raw.githubusercontent.com/aisuhua/upload-demo/master/basic/uploads/2.png
```

访问缩略图

```
http://avatar.example.com/resize/1.png
http://avatar.example.com/resize/1.png?w=100&h=100
http://avatar.example.com/resize/2.png?w=200&h=200
```

## 缩略图缓存

缓存层配置文件

```
proxy_cache_path /tmp/cache levels=1:2 keys_zone=images:10m inactive=1h max_size=10G use_temp_path=off;

server {
    listen 80;
    server_name avatar.example.com;

    location / {
        # 可以在这里完成签名和 referer 验证等
        # secure_link $arg_hash;
        # secure_link_md5 "$uri your-secret-goes-here";
        # if ($secure_link = "") {
        #	return 404;
        # }
        # if ($secure_link = "0") {
        #	return 410;
        # }

        proxy_cache images;
        proxy_cache_valid 200 30d;
        proxy_cache_key "$scheme://$host$request_uri$is_args$args";
        proxy_set_header Host $host;
        proxy_pass http://127.0.0.1:81;

        add_header X-Cache-Status $upstream_cache_status;
        expires 30d;
    }
}
```

创建缩图缓存目录并赋予写入权限（假设以 www-data 用户运行 Nginx）

```
mkdir /tmp/files
chown www-data:www-data /tmp/files
```

缩图层配置文件

```nginx
server {
    listen 81;
    server_name avatar.example.com;
    
    location ~ ^/resize/(.+)$ {
        set $image $1;
        set $width 200;
        set $height 200;
        
        if ($arg_w) {
            set $width $arg_w;
        }		
        
        if ($arg_h) {
            set $height $arg_h;
        }
        
        resolver 114.114.114.114;
        resolver_timeout 5s;
        
        set $backend_host raw.githubusercontent.com;
        set $backend_uri /aisuhua/upload-demo/master/basic/uploads/$image;
        
        proxy_buffering off;
        proxy_pass_request_body off; 
        proxy_pass_request_headers off;
        
        proxy_hide_header X-Cache;
        proxy_hide_header X-Cache-Hits;
        proxy_hide_header X-GitHub-Request-Id;
        
        proxy_set_header Host $backend_host;
        proxy_pass https://$backend_host$backend_uri;
        
        image_filter_interlace on;
        image_filter_buffer 20M;
        image_filter_jpeg_quality 95;	
        image_filter resize $width $height;
        
        error_page 415 = /empty;
    }
    
    location = /empty {
        empty_gif;
    }
}
```

## 最后

更多 image_filter 的用法请参考：

- [NGINX: Image Server with image_filter & secure_link modules][5]

[1]: http://nginx.org/en/docs/http/ngx_http_image_filter_module.html
[2]: https://github.com/cubicdaiya/ngx_small_light
[3]: https://github.com/aisuhua/wiki/blob/master/thumb/README.md
[4]: https://www.slideshare.net/cubicdaiya/ngx-small-light-24010386