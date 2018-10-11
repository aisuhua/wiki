将未运行的容器文件复制出来

```sh
shell> docker cp my_container:/etc/supervisor/conf.d/program.conf .
```

将文件复制到未运行的容器

```sh
shell> docker my_file my_container:/www/web
```