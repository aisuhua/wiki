# Docker

## Dockerfile

使用代理

```
ENV http_proxy http://192.168.88.66:1082
ENV https_proxy http://192.168.88.66:1082
```

## Run

让容器不自动退出

```
docker run -d -t ubuntu
docker run -d ubuntu tail -f /dev/null
docker run -d ubuntu sleep infinity
```

开机启动

```sh
docker update --restart=always my-container
docker update --restart=no my-container
```


## Build

减少镜像大小

```
RUN yum update -y \
  && yum install -y \
  sudo \
  git \
  && yum clean all
```

## 参考温馨

- [How to Keep Docker Container Running for Debugging](https://devopscube.com/keep-docker-container-running/)
- [Dockerfile anti-patterns and best practices](https://beenje.github.io/blog/posts/dockerfile-anti-patterns-and-best-practices/)


