# 代理服务器

Nginx 可以基于 4 层或 7 层做 HTTPS 正向代理。

## 4 层代理

使用了以下模块

- [ngx_stream_core_module](http://nginx.org/en/docs/stream/ngx_stream_core_module.html)
- [ngx_stream_ssl_preread_module](http://nginx.org/en/docs/stream/ngx_stream_ssl_preread_module.html)

配置如下

```nginx.conf
stream {
  resolver 114.114.114.114;
  server {
    listen 443;
    
    ssl_preread on;
    proxy_connect_timeout 5s;
    proxy_pass $ssl_preread_server_name:$server_port;
  }
}
```

如果需要同时需要支持 HTTP 代理，则需要添加以下配置

```nginx.conf
server {
    listen  80;
    resolver  223.5.5.5;

    location ~/* {
      proxy_pass http://$http_host$request_uri;
    }
}
```

## 7 层代理

需要借助第三方模块

- [ngx_http_proxy_connect_module](https://github.com/chobits/ngx_http_proxy_connect_module)

配置如下

```
server {
    listen  8443;
    resolver  223.5.5.5 ipv6=off;
	  resolver_timeout 500ms;
    
    if ($host !~ '^(.*?\.)?(baidu\.com|google\.com)$') {
      return 404;
    }

    # forward proxy for CONNECT request
    proxy_connect;
    proxy_connect_allow            443;
    proxy_connect_connect_timeout  10s;
    proxy_connect_read_timeout     15s;
    proxy_connect_send_timeout     15s;
	
    # forward proxy for non-CONNECT request
    location / {
        proxy_pass http://$host;
        proxy_set_header Host $host;
    }
}
```

## 参考

- [使用 NGINX 作为 HTTPS 正向代理服务器](https://www.infoq.cn/article/TaUjWGLN6D_6Qls6yj6S)




