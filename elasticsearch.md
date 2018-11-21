查看集群是否健康

```sh
shell> curl http://localhost:9200/_cluster/health
```

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
