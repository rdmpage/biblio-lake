# Resolvers

For each URL (e.g., https://doi.org/10.3897/zookeys.150.2167 ) we define a resolver, which does the following:

1. Fetch the data corresponding to that URL (typically in JSON).
2. Create a “message” that has that JSON as the payload (modelled on CrossRef’s API).
3. Set the MIME-type for the data (this is used by the document store to decide how to process the data).
4. Set a timestamp so we know how old the document is. This is useful when adding data to other databases, such as Elasticsearch, because we can say “add the documents updated in the last hour”.
5. Add a `cluster_id` field which by default is the same as the `doc._id` field. This enables us to cluster different versions of the same document by setting the `cluster_id` of multiple documents to point to the same “parent” document.
 