import re


def loadData(dataFile):
    RDFdata =[]
    with open(dataFile, "r") as f:
        for line in f:
            data = [item.strip('" ') for item in line.split()]
            if (len(data) == 4) and (data[3] == "."):
                RDFdata.append((data[0], data[1], data[2]))
    return RDFdata


def createDict(data):
    # On veut associer un entier à chaque ressource de la base RDF, afin d’en permettre un stockage compact. Par exemple, on voit les triplets <Bob, knows, Bob> et <1,2,1> indistinctement avec la correspondance {1:'Bob', 2:'knows'}.
    RDFdict = {}
    for subject, predicate, object in data:
        if subject not in RDFdict.values():
            RDFdict[len(RDFdict)] = subject
        if predicate not in RDFdict.values():
            RDFdict[len(RDFdict)] = predicate
        if object not in RDFdict.values():
            RDFdict[len(RDFdict)] = object

    return RDFdict


def insertTripletInIndex(index, item1, item2, item3):
    # Fonction qui prend en entrée un triplet et qui renvoie l'index avec les clés du dictionnaire à la place des valeurs
    # 1er niveau : dict, 2nd niveau : dict, 3eme niveau : liste

    if item1 not in index.keys():
        index[item1] = {}

    if item2 not in index[item1].keys():
        index[item1][item2] = []

    index[item1][item2].append(item3)

    return index



def createIndex(data, dict, ordre="spo"):
    index = {}
    ordre = ordre.strip().lower()
    if ordre not in ["spo", "sop", "pos", "pso", "osp", "ops"]:
        raise ValueError("l'ordre doit être SPO, SOP, POS, PSO, OSP ou OPS")

    for subject, predicate, objet in data:
        # On remplace les valeurs par les clés du dictionnaire {1:'Bob', 2:'knows'}
        subject = list(dict.keys())[list(dict.values()).index(subject)]
        predicate = list(dict.keys())[list(dict.values()).index(predicate)]
        objet = list(dict.keys())[list(dict.values()).index(objet)]

        # On insère le triplet dans l'index
        if ordre == "spo":
            index = insertTripletInIndex(index, subject, predicate, objet)
        elif ordre == "sop":
            index = insertTripletInIndex(index, subject, objet, predicate)
        elif ordre == "pos":
            index = insertTripletInIndex(index, predicate, objet, subject)
        elif ordre == "pso":
            index = insertTripletInIndex(index, predicate, subject, objet)
        elif ordre == "osp":
            index = insertTripletInIndex(index, objet, subject, predicate)
        elif ordre == "ops":
            index = insertTripletInIndex(index, objet, predicate, subject)

    return index



def loadQuery(queryFile):
    query_pattern = re.compile(r"(SELECT.*?WHERE.*?})", re.DOTALL)

    with open(queryFile, 'r', encoding='utf-8') as file:
        content = file.read()

        queries = content.split('\n\n')
        parsed_queries = []

        for query in queries:
            # Recherche des parties SELECT et WHERE dans chaque requête
            match = query_pattern.search(query)
            if match:
                parsed_query = match.group(1).strip()
                parsed_queries.append(parsed_query)

    return parsed_queries


def executeQuery(query, data):
    pass




if __name__ == "__main__":

    DEBUG = True

    queryFile = "data/sample_query.queryset"
    dataFile = "data/sample_data.nt"

    # Load data
    data = loadData(dataFile)
    if DEBUG:
        for l in data:
            print(l)

    # Create dictionary
    RDFdict = createDict(data)
    if DEBUG:
        for k, v in RDFdict.items():
            print(f"{k} : {v}")

    # Create index
    for ordre in ["spo", "sop", "pos", "pso", "osp", "ops"]:
        index = createIndex(data, RDFdict, ordre)
        if DEBUG:
            for k, v in index.items():
                print(f"{k} : {v}")

    # Load query
    query = loadQuery(queryFile)
    if DEBUG:
        for q in query:
            print(q)
    exit()

    # Execute query
    result = executeQuery(query, data)

    # Print result
    print(result)