# Elastic search

## Basic idea

Take CouchDB document and convert to a schema (see https://project-a.github.io/on-site-search-design-patterns-for-e-commerce/ ) that enables us to specify what we want to search on, and some basic fields we want to display in the results. Do this in CouchDB as a view.

## Elastic search

When developing the code I’m running Elastic 5.6.9 in a Docker container using Kinematic on my Mac. Hence all development is local.

### Create database

http://127.0.0.1:32774/works PUT (where “works” is the name of the database).

### Populate database

See “Actions”.

### Search

Text search, aggregate some fields to help with facets.

http://127.0.0.1:32774/works/_search?pretty POST

```
{
   "size": 30,
   "query": {
      "multi_match": {
         "query": "new species”,
         "fields": [
            "search_data.fulltext",
            "search_data.fulltext_boosted^2"
         ]
      }
   },
   "highlight": {
      "pre_tags": [
         "<span style=\"background-color:yellow;\">"
      ],
      "post_tags": [
         "<\/span>"
      ],
      "fields": {
         "search_data.fulltext": {},
         "search_data.fulltext_boosted": {}
      }
   },
   "aggs": {
      "type": {
         "terms": {
            "field": "type.keyword"
         }
      },
      "year": {
         "terms": {
            "field": "search_data.year"
         }
      },
      "container": {
         "terms": {
            "field": "search_data.container.keyword"
         }
      },
      "author": {
         "terms": {
            "field": "search_data.author.keyword"
         }
      }
   }
}
```

### Filtered search

Find papers on new species in 2010

```
{
	"size": 30,
	"query": {
		"bool": {
			"must": {
				"multi_match": {
					"query": "new species",
					"fields": [
						"search_data.fulltext",
						"search_data.fulltext_boosted^2"
					]
				}
			},
			"filter": {
				"term": {
					"search_data.year": 2010
				}
			}

		}
	},
	"highlight": {
		"pre_tags": [
			"<span style=\"background-color:yellow;\">"
		],
		"post_tags": [
			"<\/span>"
		],
		"fields": {
			"search_data.fulltext": {},
			"search_data.fulltext_boosted": {}
		}
	},
	"aggs": {
		"type": {
			"terms": {
				"field": "type.keyword"
			}
		},
		"year": {
			"terms": {
				"field": "search_data.year"
			}
		},
		"container": {
			"terms": {
				"field": "search_data.container.keyword"
			}
		},
		"author": {
			"terms": {
				"field": "search_data.author.keyword"
			}
		}
	}
}
```

