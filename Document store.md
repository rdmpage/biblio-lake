# Document store

The core of the project is the document store which functions as a “data lake”. All data is added to a CouchDB database in its original format as a “message.” CouchDB views are used to transform the data into forms used, for example, to populate am Elasticsearch database.

The document store has a queue, implemented using CouchDB views that keep a list of documents ordered in time. See Query.md for details.

Some CouchDB views are complex due to the complexity of the underlying data (e.g., the CSL JSON used by CrossRef) or the challenge of handling multilingual data.

The folder *couchdb-tester* has some HTML and Javascript files used to help develop and debug views.
