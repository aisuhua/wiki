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

```sh
shell> vim /tmp/curl.php
<?php
sleep(3);
echo 1, PHP_EOL;
shell> vim /tmp/mycurl.sh
mycurl() {
    START=$(date +%s)
    /usr/bin/php /tmp/curl.php
    END=$(date +%s)
    DIFF=$(( $END - $START ))
    echo "It took $DIFF seconds"
}

export -f mycurl
seq 10 | parallel -j0 mycurl
shell> bash /tmp/mycurl.sh
```

