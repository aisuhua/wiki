安装

```sh
shell> apt-get install libboost-dev libboost-test-dev libboost-program-options-dev libboost-system-dev libboost-filesystem-dev libevent-dev automake libtool flex bison pkg-config g++ make libssl-dev

shell> wget http://archive.apache.org/dist/thrift/0.9.3/thrift-0.9.3.tar.gz
shell> tar -zxvf thrift-0.9.3.tar.gz
shell> cd thrift-0.9.3
shell> ./configure
shell> make
shell> make install

shell> thrift --version
Thrift version 0.9.3
```