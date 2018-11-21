## 安装

安装

```sh
shell> apt-get install parallel
```

查看帮助

```sh
shell> man parallel_tutorial 
```

## 基本使用

添加 PHP 脚本

```php
# /tmp/curl.php
<?php
sleep(3);
echo 1, PHP_EOL;
```

添加并发执行脚本

```sh
# /tmp/mycurl.sh
mycurl() {
    START=$(date +%s)
    /usr/bin/php /tmp/curl.php
    END=$(date +%s)
    DIFF=$(( $END - $START ))
    echo "It took $DIFF seconds"
}

export -f mycurl
seq 10 | parallel -j0 mycurl
```

并发执行

```sh
shell> bash /tmp/mycurl.sh
```

