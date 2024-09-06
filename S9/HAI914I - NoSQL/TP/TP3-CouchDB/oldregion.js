{
    "_id": "_design/oldregion",
        "language": "javascript",
            "views": {
                "oldregion": {
                    "map" : "function(doc) { if (doc.type=='old_region') {emit(doc.id, doc)}}"
                }
    }
}