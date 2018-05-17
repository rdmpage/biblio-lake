{"_id":"_design/matching","_rev":"33-47a9edd8efd17535cfd3bcc6b3b4dc6e","views":{"hash":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction message(doc) {\n  if (doc.message) {\n    var hash = [];\n    \n    // year\n    if (doc.message.issued) {\n      hash.push(parseInt(doc.message.issued['date-parts'][0][0]));\n    }\n    \n    // volume\n    if (doc.message.volume) {\n      hash.push(parseInt(doc.message.volume));\n    }\n        \n    // starting page\n    if (doc.message['page-first']) {\n      hash.push(parseInt(doc.message['page-first']));\n    } else {\n      var m = doc.message.page.match(/(\\d+)(-(\\d+))?/);\n      if (m) {\n        hash.push(parseInt(m[1]));\n      }\n    }\n    \n    //------ build string representation of work for testing matches\n    \n    var terms = [];\n    \n\t// author\n\tif (doc.message.author) {\n\t\tfor (var i in doc.message.author) {\n     \t\tvar name = [];\n    \t\t\n    \t\tif (doc.message.author[i].given) {\n    \t\t\tname.push(doc.message.author[i].given);\n    \t\t}\n     \t\tif (doc.message.author[i].family) {\n    \t\t\tname.push(doc.message.author[i].family);\n    \t\t}\n\n\t\t\t// just use literal if we have it\n     \t\tif (doc.message.author[i].literal) {\n    \t\t\tname = [doc.message.author[i].literal];\n    \t\t}\n    \t\t\n    \t\tif (name.length > 0) {\n    \t\t\tterms.push(name);\n    \t\t}\n\n\t\t}\n\t}    \n      \n\tif (doc.message.title) {\n\t\tvar container = '';\n\t\tif (Array.isArray(doc.message.title)) {\n\t\t\tterms.push(doc.message.title[0]);          \n\t\t} else {\n\t\t\tterms.push(doc.message.title); \t\t\n\t\t}\n\t}     \n\t\n\t// date\n\tif (doc.message.issued) {\n\t\tif (doc.message.issued['date-parts']) {\n\t\t\tterms.push(doc.message.issued['date-parts'][0][0]);\n\t\t}\n\t} \t \n    \n\tif (doc.message['container-title']) {\n\t\tif (Array.isArray(doc.message['container-title'])) {\n\t\t\tterms.push(doc.message['container-title'][0]);          \n\t\t} else {\n\t\t\tterms.push(doc.message['container-title']); \t\t\n\t\t}\n\t}   \n    \n\t// volume\n\tif (doc.message.volume) {\n\t\tterms.push(doc.message.volume);\n\t} \n\n\t// issue\n\tif (doc.message.issue) {\n\t\tterms.push(doc.message.issue);\n\t} \n\n\t// page\n\tif (doc.message.page) {\n\t\tterms.push(doc.message.page);\n\t} \n    \n    if (hash.length == 3) {\n      emit(hash, terms.join(' '));\n    }    \n  \n  }\n}\n\n//----------------------------------------------------------------------------------------\nfunction zoobank(doc) {\n  if (doc.message) {\n    var hash = [];\n    \n    // year\n    if (doc.message.year) {\n      hash.push(parseInt(doc.message.year));\n    }\n    \n    // volume\n    if (doc.message.volume) {\n      hash.push(parseInt(doc.message.volume));\n    }\n        \n    // starting page\n    if (doc.message.startpage) {\n      hash.push(parseInt(doc.message.startpage));\n    }\n    \n    if (hash.length == 3) {\n      emit(hash, doc.message.label);\n    }    \n  \n  }\n}\n\nfunction (doc) {\n  if (doc['message-format']) {\n\n    // ORCID work\n    if (doc['message-format'] === 'application/vnd.citationstyles.csl+json') {\n      message(doc);\n    }\n\n    // CrossRef\n    if (doc['message-format'] === 'application/vnd.crossref-api-message+json') {\n      message(doc);\n    }\n    \n    // Zoobank\n    if (doc['message-format'] === 'application/vnd.zoobank+json') {\n      zoobank(doc);\n    }\n\n  }\n}\n\n// END COUCHDB VIEW"},"doc_to_hash":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction message(doc) {\n  if (doc.message) {\n    var hash = [];\n    \n    // year\n    if (doc.message.issued) {\n      hash.push(parseInt(doc.message.issued['date-parts'][0][0]));\n    }\n    \n    // volume\n    if (doc.message.volume) {\n      hash.push(parseInt(doc.message.volume));\n    }\n        \n    // starting page\n    if (doc.message['page-first']) {\n      hash.push(parseInt(doc.message['page-first']));\n    } else {\n      var m = doc.message.page.match(/(\\d+)(-(\\d+))?/);\n      if (m) {\n        hash.push(parseInt(m[1]));\n      }\n    }\n    \n    if (hash.length == 3) {\n      emit(doc._id, hash);\n    }    \n  \n  }\n}\n\n//----------------------------------------------------------------------------------------\nfunction zoobank(doc) {\n  if (doc.message) {\n    var hash = [];\n    \n    // year\n    if (doc.message.year) {\n      hash.push(parseInt(doc.message.year));\n    }\n    \n    // volume\n    if (doc.message.volume) {\n      hash.push(parseInt(doc.message.volume));\n    }\n        \n    // starting page\n    if (doc.message.startpage) {\n      hash.push(parseInt(doc.message.startpage));\n    }\n    \n    if (hash.length == 3) {\n       emit(doc._id, hash);\n    }    \n  \n  }\n}\n\nfunction (doc) {\n  if (doc['message-format']) {\n\n    // ORCID work\n    if (doc['message-format'] === 'application/vnd.citationstyles.csl+json') {\n      message(doc);\n    }\n\n    // CrossRef\n    if (doc['message-format'] === 'application/vnd.crossref-api-message+json') {\n      message(doc);\n    }\n    \n    // Zoobank\n    if (doc['message-format'] === 'application/vnd.zoobank+json') {\n      zoobank(doc);\n    }\n    \n\n  }\n}\n\n// END COUCHDB VIEW"},"doi":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction message(doc) {\n  if (doc.message) {\n\n    // DOI\n    if (doc.message.DOI) {\n    \n    \n\n\t\t//------ build string representation of work for testing matches\n\t\n\t\tvar terms = [];\n\t\n\t\t// author\n\t\tif (doc.message.author) {\n\t\t\tfor (var i in doc.message.author) {\n\t\t\t\tvar name = [];\n\t\t\t\n\t\t\t\tif (doc.message.author[i].given) {\n\t\t\t\t\tname.push(doc.message.author[i].given);\n\t\t\t\t}\n\t\t\t\tif (doc.message.author[i].family) {\n\t\t\t\t\tname.push(doc.message.author[i].family);\n\t\t\t\t}\n\n\t\t\t\t// just use literal if we have it\n\t\t\t\tif (doc.message.author[i].literal) {\n\t\t\t\t\tname = [doc.message.author[i].literal];\n\t\t\t\t}\n\t\t\t\n\t\t\t\tif (name.length > 0) {\n\t\t\t\t\tterms.push(name);\n\t\t\t\t}\n\n\t\t\t}\n\t\t}    \n\t  \n\t\tif (doc.message.title) {\n\t\t\tif (Array.isArray(doc.message.title)) {\n\t\t\t\tterms.push(doc.message.title[0]);          \n\t\t\t} else {\n\t\t\t\tterms.push(doc.message.title); \t\t\n\t\t\t}\n\t\t}     \n\t\n\t\t// date\n\t\tif (doc.message.issued) {\n\t\t\tif (doc.message.issued['date-parts']) {\n\t\t\t\tterms.push(doc.message.issued['date-parts'][0][0]);\n\t\t\t}\n\t\t} \t \n\t\n\t\tif (doc.message['container-title']) {\n\t\t\tif (Array.isArray(doc.message['container-title'])) {\n\t\t\t\tterms.push(doc.message['container-title'][0]);          \n\t\t\t} else {\n\t\t\t\tterms.push(doc.message['container-title']); \t\t\n\t\t\t}\n\t\t}   \n\t\n\t\t// volume\n\t\tif (doc.message.volume) {\n\t\t\tterms.push(doc.message.volume);\n\t\t} \n\n\t\t// issue\n\t\tif (doc.message.issue) {\n\t\t\tterms.push(doc.message.issue);\n\t\t} \n\n\t\t// page\n\t\tif (doc.message.page) {\n\t\t\tterms.push(doc.message.page);\n\t\t} \n\t\n\t\temit(doc.message.DOI.toLowerCase(), terms.join(' '));\n\n    }\n  }\n}\n\n//----------------------------------------------------------------------------------------\nfunction zoobank(doc) {\n  if (doc.message) {\n    if (doc.message.doi) {\n      emit(doc.message.doi.toLowerCase(), doc.message.label);\n    }    \n  }\n}\n\n//----------------------------------------------------------------------------------------\nfunction (doc) {\n  if (doc['message-format']) {\n\n    // ORCID work\n    if (doc['message-format'] === 'application/vnd.citationstyles.csl+json') {\n      message(doc);\n    }\n\n    // CrossRef\n    if (doc['message-format'] === 'application/vnd.crossref-api-message+json') {\n      message(doc);\n    }\n    \n    // Zoobank\n    if (doc['message-format'] === 'application/vnd.zoobank+json') {\n     zoobank(doc);\n    }\n\n  }\n}\n\n// END COUCHDB VIEW"},"doc_to_doi":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction message(doc) {\n  if (doc.message) {\n\n    // year\n    if (doc.message.DOI) {\n      emit(doc._id, doc.message.DOI.toLowerCase());\n    }\n  }\n}\n\nfunction (doc) {\n  if (doc['message-format']) {\n\n    // ORCID work\n    if (doc['message-format'] === 'application/vnd.citationstyles.csl+json') {\n      message(doc);\n    }\n\n    // CrossRef\n    if (doc['message-format'] === 'application/vnd.crossref-api-message+json') {\n      message(doc);\n    }\n    \n    // Zoobank\n    if (doc['message-format'] === 'application/vnd.zoobank+json') {\n      message(doc);\n    }\n\n  }\n}\n\n// END COUCHDB VIEW"},"query":{"map":"//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\n//----------------------------------------------------------------------------------------\n\n\n//----------------------------------------------------------------------------------------\nfunction csl_query(doc) {\n  if (doc.message) {\n\n    var query = {};\n    query.parameters = {};\n    \n\t// author\n\tif (doc.message.author) {\n\t\tquery.parameters.author = [];\n\t\tfor (var i in doc.message.author) {\n    \t\t// simple case\n    \t\tvar name = [];\n    \t\t\n    \t\tif (doc.message.author[i].given) {\n    \t\t\tname.push(doc.message.author[i].given);\n    \t\t}\n     \t\tif (doc.message.author[i].family) {\n    \t\t\tname.push(doc.message.author[i].family);\n    \t\t}\n\n\t\t\t// just use literal if we have it\n     \t\tif (doc.message.author[i].literal) {\n    \t\t\tname = [doc.message.author[i].literal];\n    \t\t}\n    \t\t\n    \t\tif (name.length > 0) {\n    \t\t\tquery.parameters.author.push(name.join(' '));\n    \t\t}\n\n\t\t}\n\t}    \n    \n    // year\n    if (doc.message.issued) {\n      query.parameters.year = doc.message.issued['date-parts'][0][0];\n    }    \n    \n\t// title\n\tif (doc.message.title) {\n\t\tif (Array.isArray(doc.message.title)) {\n\t\t\tquery.parameters.title = doc.message.title[0];  \n\t\t} else {\n\t\t\tquery.parameters.title = doc.message.title;  \n\t\t}\n\t}\n\t\n\tif (doc.message['container-title']) {\n\t\tif (Array.isArray(doc.message['container-title'])) {\n\t\t\tquery.parameters.journal = doc.message['container-title'][0];  \n\t\t} else {\n\t\t\tquery.parameters.journal = doc.message['container-title'];  \n\t\t}\n\t}\n\t\n\t// volume\n\tif (doc.message.volume) {\n\t\tquery.parameters.volume = doc.message.volume; \t\t\n\t} \n\n\t// issue\n\tif (doc.message.issue) {\n\t\tquery.parameters.issue = doc.message.issue; \t\t\n\t} \n\n\t// page\n\tif (doc.message.page) {\n\t\tquery.parameters.page = doc.message.page; \t\t\n\t} \n\t\n    // starting page\n    if (doc.message['page-first']) {\n      query.parameters.spage = doc.message['page-first'];\n    } else {\n      var m = doc.message.page.match(/(\\d+)(-(\\d+))?/);\n      if (m) {\n      \tquery.parameters.spage = m[1];\n      \tif (m[2]) {\n      \t\tquery.parameters.epage = m[3];\n      \t}\n      }\n    }\n\t\n\tvar keys = [];\n\tvar values = [];\n\t\n\t// OpenURL\n\t\n\t// Google Scholar\n\t\n\t// Simple string\n\tkeys=['author', 'year', 'title', 'journal', 'volume', 'issue', 'page'];\n\t\n\tvalues = [];\n\tfor (var i in keys) {\n\t\tswitch (keys[i]) {\n\t\t\tcase 'author':\n\t\t\t\tvalues.push(query.parameters[keys[i]].join(' '));\n\t\t\t\tbreak;\n\t\t\tdefault:\n\t\t\t\tvalues.push(query.parameters[keys[i]]);\n\t\t\t\tbreak;\n\t\t}\n\t}\n\t\n\tquery.string = values.join(' ');\n\t\t\n\t//$('#jsonld').html(JSON.stringify(query, null, 2));\n  emit(doc._id, query);\n\n  }\n}\n\n//----------------------------------------------------------------------------------------\n// Reference cited in CrossRef metadata, or web page\nfunction reference_query(doc) {\n  if (doc.message) {\n\n    var query = {};\n    query.parameters = {};\n    \n\t// author\n\tif (doc.message.author) {\n\t\tquery.parameters.author = [];\n\t\tif (Array.isArray(doc.message.author)) {\n\t\t\tfor (var i in doc.message.author) {\n\t\t\t\tquery.parameters.author.push(doc.message.author[i]);\n\t\t\t}\n\t\t} else {\n\t\t\tquery.parameters.author.push(doc.message.author);\n\t\t}\t\t\n\t}    \n    \n    // year\n    if (doc.message.year) {\n      query.parameters.year = doc.message.year;\n    }    \n    \n\t// title\n\tif (doc.message['article-title']) {\n\t\tquery.parameters.title = doc.message['article-title'];  \n\t}\n\t\n\tif (doc.message['journal-title']) {\n\t\tquery.parameters.journal = doc.message['journal-title'];  \n\t}\n\t\n\t// volume\n\tif (doc.message.volume) {\n\t\tquery.parameters.volume = doc.message.volume; \t\t\n\t} \n\n\t// issue\n\tif (doc.message.issue) {\n\t\tquery.parameters.issue = doc.message.issue; \t\t\n\t} \n\n\t// page\n\tif (doc.message['first-page']) {\n\t\tquery.parameters.page = doc.message['first-page']; \t\t\n\t} \n\t\n\t/*\n    // starting page\n    if (doc.message['page-first']) {\n      query.parameters.spage = doc.message['page-first'];\n    } else {\n      var m = doc.message.page.match(/(\\d+)(-(\\d+))?/);\n      if (m) {\n      \tquery.parameters.spage = m[1];\n      \tif (m[2]) {\n      \t\tquery.parameters.epage = m[3];\n      \t}\n      }\n    }\n    */\n\t\n\tvar keys = [];\n\tvar values = [];\n\t\n\t// OpenURL\n\t\n\t// Google Scholar\n\t\n\t// Simple string\t\n\tif (doc.message.unstructured) {\n\t\tquery.string = doc.message.unstructured;\t\n\t} else {\n\t\tkeys=['author', 'year', 'title', 'journal', 'volume', 'issue', 'page'];\n\t\n\t\tvalues = [];\n\t\tfor (var i in keys) {\n\t\t\tswitch (keys[i]) {\n\t\t\t\tcase 'author':\n\t\t\t\t\tvalues.push(query.parameters[keys[i]].join(' '));\n\t\t\t\t\tbreak;\n\t\t\t\tdefault:\n\t\t\t\t\tvalues.push(query.parameters[keys[i]]);\n\t\t\t\t\tbreak;\n\t\t\t}\n\t\t}\n\t\n\t\tquery.string = values.join(' ');\t\n\t}\n\n\t\n\t//$('#jsonld').html(JSON.stringify(query, null, 2));\n  emit(doc._id, query);\n\n  }\n}\n\n//----------------------------------------------------------------------------------------\nfunction zoobank_query(doc) {\n  if (doc.message) {\n\n    var query = {};\n    query.parameters = {};\n    \n\t// author\n\tif (doc.message.author) {\n\t\tquery.parameters.author = [];\n\t\tfor (var i in doc.message.author) {\n\t\t\t// simple case\n\t\t\tvar name = [];\n\t\t\n\t\t\tif (doc.message.author[i].givenname) {\n\t\t\t\tname.push(doc.message.author[i].givenname);\n\t\t\t}\n\t\t\tif (doc.message.author[i].familyname) {\n\t\t\t\tname.push(doc.message.author[i].familyname);\n\t\t\t}\n\t\t\n\t\t\tif (name.length > 0) {\n\t\t\t\tquery.parameters.author.push(name.join(' '));\n\t\t\t}\n\t\t}\n\t}    \n    \n    // year\n    if (doc.message.year) {\n      query.parameters.year = doc.message.year;\n    }    \n    \n\t// title\n\tif (doc.message.title) {\n\t\tquery.parameters.title = doc.message.title;  \n\t}\n\t\n\t// journal\n\tif (doc.message.parentreference) {\n\t\tif (doc.message.parentreference != '') {\n\t\t\tquery.parameters.journal = doc.message.parentreference; \n\t\t}\t\t\n\t} \n\t\t\n\t// volume\n\tif (doc.message.volume) {\n\t\tif (doc.message.volume != '') {\n\t\t\tquery.parameters.volume = doc.message.volume; \n\t\t}\t\t\n\t} \n\n\t// issue\n\tif (doc.message.number) {\n\t\tif (doc.message.number != '') {\n\t\t\tquery.parameters.issue = doc.message.number; \n\t\t}\t\t\n\t} \n\n\t// page\n\tif (doc.message.pagination) {\n\t\tquery.parameters.page = doc.message.pagination; \t\t\n\t} \n\t\n\t// starting page\n\tif (doc.message.startpage) {\n\t\tif (doc.message.startpage != '') {\n\t\t\tquery.parameters.spage = doc.message.startpage; \n\t\t}\t\t\n\t} \n\n\t// ending page\n\tif (doc.message.endpage) {\n\t\tif (doc.message.endpage != '') {\n\t\t\tquery.parameters.epage = doc.message.endpage; \n\t\t}\t\t\n\t} \n\n\t\n\tvar keys = [];\n\tvar values = [];\n\t\n\t// OpenURL\n\t\n\t// Google Scholar\n\t\n\t// Simple string\t\n\tquery.string = doc.message.label;\t\n\n\t\n\t//$('#jsonld').html(JSON.stringify(query, null, 2));\n  emit(doc._id, query);\n\n  }\n}\n\n\n\nfunction (doc) {\n  if (doc['message-format']) {\n\n    // ORCID work\n    if (doc['message-format'] === 'application/vnd.citationstyles.csl+json') {\n      csl_query(doc);\n    }\n\n    // CrossRef\n    if (doc['message-format'] === 'application/vnd.crossref-api-message+json') {\n      csl_query(doc);\n    }\n    \n    // Reference cited in CrossRef metadata or on web page\n    if (doc['message-format'] === 'application/vnd.crossref-citation+json') {\n      reference_query(doc);\n    }\n    \n    // Zoobank\n    if (doc['message-format'] === 'application/vnd.zoobank+json') {\n      zoobank_query(doc);\n    }\n    \n    \n\n  }\n}\n\n// END COUCHDB VIEW"}},"language":"javascript"}