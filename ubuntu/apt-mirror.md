## 创建私有软件 mirror

安装 apt-mirror

```
sudo apt install apt-mirror
```

修改镜像配置，指定本地保存路径和软件源

```
############# config ##################
#
set base_path    /mnt/apt-mirror/ubuntu
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

deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ bionic main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ bionic-security main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ bionic-updates main restricted universe multiverse
deb-amd64 http://mirrors.cloud.tencent.com/ubuntu/ bionic-backports main restricted universe multiverse

clean http://mirrors.cloud.tencent.com/ubuntu/
```

下载源

```
sudo apt-mirror 
```

- [Set Up A Local Ubuntu / Debian Mirror with Apt-Mirror](https://blog.programster.org/set-up-a-local-ubuntu-mirror-with-apt-mirror)
- [使用 apt-mirror 和 apt-cacher 创建本地 Ubuntu 仓库](https://blog.fleeto.us/post/build-ubuntu-repository-with-apt-mirror-and-apt-cacher/)
