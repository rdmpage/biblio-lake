{"_id":"_design/reference","_rev":"10-ae039bad584f13e32b27a24e59fbda8d","views":{"modified":{"map":"function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] === 'application/vnd.crossref-citation+json') {\n      emit(doc['message-modified'], doc._id);\n    }\n  }\n}"},"elastic":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction add_values(elastic_search_doc, key, value, boost) {\n\telastic_search_doc.search_data.fulltext_values.push(value);\n\t\n\tboosted = (typeof boost !== 'undefined') ?  boost : false;\n\t\n\tif (boosted) {\n\t\telastic_search_doc.search_data.fulltext_boosted_values.push(value);\n\t}\n\t\n\tswitch (key) {\n\t\tcase 'container':\n\t\tcase 'author':\n\t\t\telastic_search_doc.search_data[key].push(value);\n\t\t\tbreak;\n\n\t\tcase 'year':\n\t\t\telastic_search_doc.search_data[key] = value;\n\t\t\tbreak;\n\t\n\t\tdefault:\n\t\t\tbreak;\n\t}\n\t\n\treturn elastic_search_doc;\n}\n\n//----------------------------------------------------------------------------------------\nfunction message(doc) {\n  if (doc.message) {\n\n    var elastic_search_doc = {};\n    \n    elastic_search_doc.id = doc._id;\n    \n    // type of document\n    elastic_search_doc.type = 'citation';\n    \n\t// output to display in list of hits\n\telastic_search_doc.search_result_data = {};\n\t\n\t// possible fields to hold information on how to display this object\n\telastic_search_doc.search_result_data.name = '';\n\telastic_search_doc.search_result_data.description = '';\n\telastic_search_doc.search_result_data.creator = [];\n\telastic_search_doc.search_result_data.thumbnailUrl = '';\n\telastic_search_doc.search_result_data.url = '';\n\t\n\t// temporary\n\telastic_search_doc.search_result_data.description_parts = [];\n\t\n\t/*\n\tif ($url)\n\t{\n\t\t$doc->search_result_data->url = $url;\n\t}\n\t*/\n\n\t// fields that will be searched on\n\telastic_search_doc.search_data = {};\n\t\n\tif (doc.cluster_id) {\n\t  elastic_search_doc.search_data.cluster_id = doc.cluster_id;\n\t} \t\n\t\n\t// text fields for searching on\n\telastic_search_doc.search_data.fulltext_values = [];\n\telastic_search_doc.search_data.fulltext_boosted_values = [];\n\t\n\t// things to use as facets\n\telastic_search_doc.search_data.container = [];\n\telastic_search_doc.search_data.author = [];\n\telastic_search_doc.search_data.year = null;\n\telastic_search_doc.search_data.subject = [];\n\t\n\t/*\n\t      {\n        \"issue\": \"602\",\n        \"key\": \"ref2\",\n        \"doi-asserted-by\": \"crossref\",\n        \"first-page\": \"1\",\n        \"DOI\": \"10.11646/zootaxa.602.1.1\",\n        \"article-title\": \"A new species of Pedinotus (Hymenoptera: Braconidae: Doryctinae) from Brazil\",\n        \"author\": \"FÉLIX FC.\",\n        \"year\": \"2004\",\n        \"journal-title\": \"Zootaxa\"\n      }\n      \n      {\nkey: \"CIT0023\",\nauthor: \"Rambaut A.\",\nyear: \"1996\",\nvolume-title: \"Se-Al: Sequence Alignment Editor\"\n}\n  */\n\t\n\t// title\n\tif (doc.message['article-title']) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'title', doc.message['article-title'], true);\n\t\t\n        elastic_search_doc.search_result_data.name = doc.message['article-title'];\n\t}\n\t\n\tif (doc.message['volume-title']) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'title', doc.message['volume-title'], true);\n\t\t\n        elastic_search_doc.search_result_data.name = doc.message['volume-title'];\n\t}\n\t\n\n\t// journal\n\tif (doc.message['journal-title']) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'container', doc.message['journal-title'], true);\n\t\telastic_search_doc.search_result_data.description_parts.push('in ' + doc.message['journal-title']);\t\n\t}\n\n\t// volume\n\tif (doc.message.volume) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'volume', doc.message.volume, true);\n\t\telastic_search_doc.search_result_data.description_parts.push('in volume ' + doc.message.volume);\t\n\t}\n\n\t// issue\n\tif (doc.message.issue) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'issue', doc.message.issue, true);\n\t\telastic_search_doc.search_result_data.description_parts.push('issue ' + doc.message.issue);\t\n\t}\n\n\t// page\n\tif (doc.message['first-page']) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'volume', doc.message['first-page'], true);\n\t\telastic_search_doc.search_result_data.description_parts.push('page ' + doc.message['first-page']);\t\n\t}\n\n\t// year\n\tif (doc.message.year) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'year', doc.message.year, true);\n\t\telastic_search_doc.search_result_data.description_parts.push('in ' + doc.message.year);\t\n\t}\n\n\t// DOI\n\tif (doc.message.doi) {\n\t\telastic_search_doc = add_values(elastic_search_doc, 'doi', doc.message.doi);\t\t\n\t\t\n\t\telastic_search_doc.search_result_data.doi = doc.message.doi;\n\t} \n\t\n\t// authors\n\tif (doc.message.author) {\n\t\tif (Array.isArray(doc.message.author)) {\t\n\t\t\tfor (var i in doc.message.author) {\n\t\t\t\telastic_search_doc.search_data['author'].push(doc.message.author[i]);\n\t\t\t\telastic_search_doc.search_result_data['creator'].push(doc.message.author[i]);\n\t\t\t}\n\t\t} else {\n\t\t\telastic_search_doc.search_data['author'].push(doc.message.author);\n\t\t\telastic_search_doc.search_result_data['creator'].push(doc.message.author);\t\t\n\t\t}\n\t}\n\t\n\t//------------------------------------------------------------------------------------\n\t\n\t// cleanup\n\tif (doc.message.unstructured) {\n\t\t// If we have unstructured text then we have the full citation text already\n\t\telastic_search_doc.search_data.fulltext = doc.message.unstructured;\n\t\tdelete elastic_search_doc.search_data.fulltext_values;\n\t\t\n\t\telastic_search_doc.search_data.fulltext_boosted = doc.message.unstructured;\n\t\tdelete elastic_search_doc.search_data.fulltext_boosted_values;\t\n\t\t\n\t\tif (elastic_search_doc.search_result_data.name == '') {\n\t\t\telastic_search_doc.search_result_data.name = doc.message.unstructured;\n\t\t}\n\t\t\t\n\t} else {\n\t\t// otherwise concatenate values\n\t\telastic_search_doc.search_data.fulltext = elastic_search_doc.search_data.fulltext_values.join(' ');\n\t\tdelete elastic_search_doc.search_data.fulltext_values;\n\n\t\telastic_search_doc.search_data.fulltext_boosted = elastic_search_doc.search_data.fulltext_boosted_values.join(' ');\n\t\tdelete elastic_search_doc.search_data.fulltext_boosted_values;\t\n\t\t\n\t\tif (elastic_search_doc.search_result_data.name == '') {\n\t\t\telastic_search_doc.search_result_data.name = doc.message.unstructured;\n\t\t}\n\t\t\n\t\telastic_search_doc.search_result_data.description = 'Published ' + elastic_search_doc.search_result_data.description_parts.join(', ');\t\t\n\t\t\n\t}\n\t\n\tdelete elastic_search_doc.search_result_data.description_parts;\n\t\n\tif (!elastic_search_doc.search_result_data.creator) {\n\t\tdelete elastic_search_doc.search_result_data.creator;\n\t}\n\t\n\tif (!elastic_search_doc.search_result_data.thumbnailUrl) {\n\t\tdelete elastic_search_doc.search_result_data.thumbnailUrl;\n\t}\n\n\tif (!elastic_search_doc.search_result_data.url) {\n\t\tdelete elastic_search_doc.search_result_data.url;\n\t}\n\t\n\t//$('#jsonld').html(JSON.stringify(elastic_search_doc, null, 2));\n\temit(doc._id, elastic_search_doc);\n    \n\n  }\n}\n\nfunction (doc) {\n  if (doc['message-format']) {\n\n    if (doc['message-format'] === 'application/vnd.crossref-citation+json') {\n      message(doc);\n    }\n\n\n  }\n}\n\n// END COUCHDB VIEW"}},"language":"javascript"}
