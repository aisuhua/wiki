复制文件到未运行的容器

```sh
shell> docker cp my_container:/etc/supervisor/conf.d/program.conf .
shell> docker my_file my_container:/www/web
```

- [How to edit files in stopped/not starting docker container](https://stackoverflow.com/questions/32750748/how-to-edit-files-in-stopped-not-starting-docker-container)