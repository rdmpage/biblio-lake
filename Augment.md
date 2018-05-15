# Augment

Many bibliographic records may lack identifiers, such as DOIs, even if those identifiers exist. For example, ORCID profiles are often lacking DOIs for references. Scripts in this folder can be used to find and add the missing identifiers.

## Adding a DOI

If we find a DOI then we do the following:

1. Add DOI to record
2. Set `cluster_id` of record to the DOI so that the record is linked to the `parent` record
3. Update record’s timestamp to “now”
4. If record for DOI doesn’t exist in document store then enqueue the DOI.

