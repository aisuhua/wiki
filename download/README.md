## 基础示例

单纯使用 Nginx 实现文件下载，需具备以下特征：

- 能浏览文件和目录；
- 实现 HTTP Basic Authentication 验证；

使用到以下两个模块：

- [ngx_http_autoindex_module](http://nginx.org/en/docs/http/ngx_http_autoindex_module.html)
- [ngx_http_auth_basic_module](http://nginx.org/en/docs/http/ngx_http_auth_basic_module.html)

Nginx 配置示例

```nginx
server {
    listen 80 default_server;
    server_name download.example.com;
    
    # auth
    auth_basic "Restricted site";
    auth_basic_user_file /etc/nginx/.htpasswd;
    
    autoindex on;               # enable directory listing output
    autoindex_exact_size off;   # output file sizes rounded to kilobytes, megabytes, and gigabytes
    autoindex_localtime on;
    
    location / {
        root /tmp/files;
    }
}
```

`.htpasswd` 存放用户名和密码，其生成方法如下：

```bash
echo -n 'foo:' >> .htpasswd
openssl passwd -apr1 >> .htpasswd
# type your password twice
cat .htpasswd
```

```
foo:$apr1$jZqtEmFt$EpceKA4NqfxgXUgjQPuCN1
```

参考 [NGINX as a file server](https://www.yanxurui.cc/posts/server/2017-03-21-NGINX-as-a-file-server/)

## 下载限速

目标

- 当文件下载了 200K 后，将速度限制为 10K/s；
- 单个 IP 最多只能发起 2 个并发下载请求；

需用到的模块

- [ngx_http_limit_conn_module](http://nginx.org/en/docs/http/ngx_http_limit_conn_module.html)
- [ngx_http_core_module#limit_rate](http://nginx.org/en/docs/http/ngx_http_core_module.html#limit_rate)

Nginx 配置示例

```nginx
http {
    limit_conn_zone $binary_remote_addr zone=addr:10m;
    
    server {
        listen 80;
        server_name download.example.com;
    
        autoindex on;
        autoindex_exact_size off;
        autoindex_localtime on;
    
        location / {
            root /tmp/files;
            limit_conn addr 2;
            limit_rate 10k;
            limit_rate_after 200K;
        }
    }
}
```

参考 [Nginx实现文件下载限速的功能](https://junzhou2016.github.io/2018/01/14/Nginx%E5%AE%9E%E7%8E%B0%E6%96%87%E4%BB%B6%E4%B8%8B%E8%BD%BD%E7%9A%84%E9%99%90%E9%80%9F%E5%8A%9F%E8%83%BD/)

## 安全下载1

需达到的目标

- 验证下载链接的签名是否正确；
- 验证下载链接是否过期；
- User-Agent 是否一致（可选）；

需用到的模块

- [ngx_http_secure_link_module](http://nginx.org/en/docs/http/ngx_http_secure_link_module.html)

生成下载文件地址

```php
<?php
$host = 'http://download.example.com';
$uri = '/download/nginx-1.14.1.tar.gz';
$key = '123456';
$expires = time() + 10;
$md5_hash = str_replace('=', '', strtr(base64_encode(md5("{$key}{$expires}{$uri}", true)), '+/', '-_'));

$download_url = "{$host}{$uri}?md5={$md5_hash}&expires={$expires}";
echo $download_url, PHP_EOL;
```

> http://download.example.com/download/nginx-1.14.1.tar.gz?md5=OhDHs93Zfnfexwl2Am7DOg&expires=1553503005

Nginx 配置示例

```nginx
server {
    listen 80;
    server_name download.example.com;

    # /tmp/files/nginx-1.14.1.tar.gz 
    location /download {
        secure_link $arg_md5,$arg_expires;
        secure_link_md5 "123456$secure_link_expires$uri";

        if ($secure_link = "") {
            return 403;
        }

        if ($secure_link = "0") {
            return 410;
        }

        alias /tmp/files;
    }
}
```

## 安全下载2

目标

- 验证下载链接的签名是否正确；
- 验证下载链接是否过期；
- User-Agent 限制；
- 验证用户的登录状态（Cookie）；
- 验证用户是否有权限访问该资源；
- 限制下载速度；

需用到的模块

- [X-Sendfile](https://www.nginx.com/resources/wiki/start/topics/examples/x-accel/)

生成下载地址脚本

```php
<?php
$host = 'http://download.example.com';
$key = '123456';
$expires = time() + 3600;
$file = 'myfile';
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$user_id = '10086'; // Login info
$speed = 200 * 1024; // 100KB

$hash = md5($key . "{$expires}{$file}{$ua}{$speed}{$user_id}");

$download_url = "{$host}/download.php?file={$file}&expires={$expires}&speed={$speed}&user_id={$user_id}&hash={$hash}";
echo $download_url, PHP_EOL;
```

> http://download.example.com/download.php?file=myfile&expires=1553502586&speed=204800&user_id=10086&hash=b594b7e38f59708d87351e11a9495365

Nginx 配置

```nginx
server {
    listen 80;
    server_name download.example.com;
    
    root /www/web/wiki/download/code;

    location ~ \.php$ {
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
    
    # /tmp/files/myfile
    location /download {
        internal;
        alias /tmp/files;
    }
}
```

下载地址验证脚本

```php
<?php
$file = $_GET['file'];
$expires = $_GET['expires'];
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$speed = $_GET['speed'];
$user_id = $_GET['user_id'];
$hash = $_GET['hash'];

// 验证 hash 是否正确（含 UA 校验）
$key = '123456';
$hash_real = md5($key . "{$expires}{$file}{$ua}{$speed}{$user_id}");

if ($hash !== $hash_real) {
    return http_response_code(403);
}

// 验证时间是否过期
if ($expires < time()) {
    return http_response_code(410);
}

// 验证用户是否登录
function check_login() {
    // $_COOKIE
    return true;
}

if (!check_login()) {
    return http_response_code(403);
}

// 验证用户是否有权限下载
// 如需验证权限可在生成下载地址时传递更多的业务字段
function check_privilege() {
    return true;
}

if (!check_privilege()) {
    return http_response_code(403);
}

header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"'); // 自定义文件名
header('Cache-Control: no-cache');
header("X-Accel-Limit-Rate: {$speed}"); // 限速
header("X-Accel-Redirect: /download/{$file}");
```

参考 [使用 Nginx 的 X-Sendfile 机制提升 PHP 文件下载性能](https://www.lovelucy.info/x-sendfile-in-nginx.html)

