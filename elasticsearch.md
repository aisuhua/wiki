## Status

查看集群是否健康

```
_cluster/health
```

查看所有节点、分片信息

```
/_cat/nodes
/_cat/shards
_cat/shards/my_index
```

查看索引所在的节点、分片信息

```
/my_index/_search_shards
```


## Search

对 `_id` 字段进行排序

```json
{
    "size":100,
    "sort":{
        "_uid":"desc"
    }
}
```

- [_uid field](https://www.elastic.co/guide/en/elasticsearch/reference/5.5/mapping-uid-field.html)

使用 routing 进行查询

```
/my_index/my_type/_search?routing=1
```

- [_routing field](https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping-routing-field.html)

## Cross Cluster Search

查看跨集群配置

```
/_cluster/settings
```

跨集群搜索

```
/cluster1:my_index*,cluster2:my_index*,cluster3:my_index*/_search
```

- [Cross Cluster Search](https://www.elastic.co/guide/en/kibana/current/management-cross-cluster-search.html#management-cross-cluster-search)

## 分词

测试分词器

```
/_analyze?analyzer=str_search_analyzer&text=suhuazizi
```

测试字段的分词结果

```
_analyze?field=username&text=suhuazizi
```
