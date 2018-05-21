# Clustering

We will have many duplicate references, e.g. from lists of literature cited. Need to think how to find these and cluster them.

Note that criterai for cluster membership may vary depending on what information we have, e.g 

Sinclair, J., 1955. A revision of the Malayan Annonaceae. Gardens' Bulletin, Singapore, 14: 149-516.

Searching for this finds at least three versions of the article, if we run one kind of clustering (e.g., hash) do we start from scratch or do we respect existing clusters?

Nee adoption to build existing cluster, or to start from scratch. For example, could use hash, get all works matching that hash, get all cluster_ids, get all members of those clusters, make connections in graph, then add any new connections and reclusters.
