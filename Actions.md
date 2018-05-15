# Actions

Scripts in the *actions* folder perform tasks related to adding data to the document store via a queue, and sending data to databases, such as Elastic search.

## Clustering

To handle multiple records of “same” work, each work has two identifiers, `_id` (the URL of the source) and `cluster_id` which, by default, is the same as `_id`. When clustering records we set the `cluster_id` of a record to be the `_id` of record it matches (e.g., because they have the same identifier). We use a [Disjoint-set data structure](https://en.wikipedia.org/wiki/Disjoint-set_data_structure) to find the connected components ion the resulting graph, these components are clusters of the “same” work.

