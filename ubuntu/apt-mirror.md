## 创建私有 mirror

安装 apt-mirror

```
sudo apt install apt-mirror
```

修改镜像配置，指定本地保存路径和软件源

```
############# config ##################
#
set base_path    /media/suhua/073E10A30275844E/mirrors/20.04
#
# set mirror_path  $base_path/mirror
# set skel_path    $base_path/skel
# set var_path     $base_path/var
# set cleanscript $var_path/clean.sh
# set defaultarch  <running host architecture>
# set postmirror_script $var_path/postmirror.sh
# set run_postmirror 0
set nthreads     20
set _tilde 0
#
############# end config ##############

deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ focal main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ focal-security main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ focal-updates main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ focal-backports main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ focal-proposed main restricted universe multiverse

deb-src http://mirrors.cloud.tencent.com/ubuntu/ focal main restricted universe multiverse
deb-src http://mirrors.cloud.tencent.com/ubuntu/ focal-security main restricted universe multiverse
deb-src http://mirrors.cloud.tencent.com/ubuntu/ focal-updates main restricted universe multiverse
deb-src http://mirrors.cloud.tencent.com/ubuntu/ focal-backports main restricted universe multiverse
deb-src http://mirrors.cloud.tencent.com/ubuntu/ focal-proposed main restricted universe multiverse

clean http://mirrors.cloud.tencent.com/ubuntu/
```

下载源

```
sudo apt-mirror 
```

## 使用源

配置 Nginx 能访问源

```
ls -s /media/suhua/073E10A30275844E/mirrors/20.04/mirror/mirrors.cloud.tencent.com/ubuntu /var/html/ubuntu
```

使用源，假设通过 mirrors.aisuhua.com 能访问到源

```
# vim /etc/apt/sources.list

deb [arch=amd64] http://mirrors.aisuhua.com/ubuntu/ focal main restricted universe multiverse
deb-src http://mirrors.aisuhua.com/ubuntu/ focal main restricted universe multiverse

deb [arch=amd64] http://mirrors.aisuhua.com/ubuntu/ focal-security main restricted universe multiverse
deb-src http://mirrors.aisuhua.com/ubuntu/ focal-security main restricted universe multiverse

deb [arch=amd64] http://mirrors.aisuhua.com/ubuntu/ focal-updates main restricted universe multiverse
deb-src http://mirrors.aisuhua.com/ubuntu/ focal-updates main restricted universe multiverse

deb [arch=amd64] http://mirrors.aisuhua.com/ubuntu/ focal-backports main restricted universe multiverse
deb-src http://mirrors.aisuhua.com/ubuntu/ focal-backports main restricted universe multiverse
 
deb [arch=amd64] http://mirrors.aisuhua.com/ubuntu/ focal-proposed main restricted universe multiverse
deb-src http://mirrors.aisuhua.com/ubuntu/ focal-proposed main restricted universe multiverse
```

- [Set Up A Local Ubuntu / Debian Mirror with Apt-Mirror](https://blog.programster.org/set-up-a-local-ubuntu-mirror-with-apt-mirror)
- [使用 apt-mirror 和 apt-cacher 创建本地 Ubuntu 仓库](https://blog.fleeto.us/post/build-ubuntu-repository-with-apt-mirror-and-apt-cacher/)

## 常见问题

Added support for cnf directories

参考 [#136](https://github.com/apt-mirror/apt-mirror/pull/136/files) 直接修改 /usr/bin/apt-mirror 文件即可。

- [Ubuntu 19.04 Disco Dingo needs cnf (http://archive.ubuntu.com/ubuntu/dists/disco/main/cnf/)](https://github.com/apt-mirror/apt-mirror/issues/118)
