## Exercice 1
### 1.1
> curl -X PUT $COUCH3/aalbert_vaccination?q=8

Choix de la structure : 
On va préférer la structure de vaccin2.json, car l'ajout de différents vaccin est plus claire à lire

> curl -X POST $COUCH3/aalbert_vaccination/_bulk_docs -d @Data/vaccin2.json -H "Content-Type:application/json"
> curl -X POST $COUCH3/aalbert_vaccination/_bulk_docs -d @Data/departements.json -H "Content-Type:application/json"

## Exercice 2
### 2.1
function (doc) {
    if (doc.type=='departement')
        emit(doc.type,doc.population);
}
function (keys, values, rereduce) {
    return Math.min.apply({}, values);
}

> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/pop_dep_min

Cette requête renvoie au niveau du map la liste des habitants par departements.
Au niveau du reduce, elle renvoie le département avec la population minimale
de même pour le rereduce.

### 2.2
#### 1
> curl -X GET $COUCH3/aalbert_vaccination/doc_count
#### 2
> curl -X GET $COUCH3/aalbert_vaccination/_all_docs
#### 3
> curl -X GET $COUCH3/aalbert_vaccination/f9eb126d996b0ea76da811f2fdfee5d8

## Exercice 3
##### 1
```js
function (doc) {
  if ((doc.type == 'couverture_vaccinale') && (doc.dep == '34')) {
    emit(doc._id, doc.jour);
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/couv_vacc_herault

##### 2
```js
function (doc) {
  if ((doc.type == 'couverture_vaccinale') && (doc.dep == '34')) {
    emit(doc._id, 1);
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_vacc_herault

##### 3
```js
function (doc) {
  if ((doc.type == 'couverture_vaccinale') && (doc.dep == '34')) {
    var dateDoc = new Date(doc.jour);
    emit(dateDoc.getFullYear(), 1);
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_vacc_herault_annee?group=True

##### 4
```js
function (doc) {
  if (doc.type == 'couverture_vaccinale') {
    var dateDoc = new Date(doc.jour);
    emit([doc.dep, dateDoc.getFullYear(), dateDoc.getMonth()], 1);
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_vacc_dep_annee_mois?group=True

##### 5
```js
function (doc) {
  if (doc.type == 'couverture_vaccinale') {
    for (let i=0; i<4; i++) {
        if (doc.vaccinations[i].vaccin == "Pfizer") {
            emit(doc._id, { "date": doc.jour, "departement": doc.dep });
        }
    }
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/vacc_pfizer

##### 6
```js
function (doc) {
  if (doc.type == 'couverture_vaccinale') {
    for (let i=0; i<4; i++) {
        if (doc.vaccinations[i].vaccin == "Pfizer") {
            emit(doc.dep, 1);
        }
    }
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_vacc_pfizer?group=True

##### 7
```js
function (doc) {
  if (doc.type == 'couverture_vaccinale') {
    for (let i=0; i<4; i++) {
        if (doc.vaccinations[i].vaccin == "Pfizer") {
            var dateDoc = new Date(doc.jour);
            emit([doc.dep, dateDoc.getFullYear(), dateDoc.getMonth()], 1);
        }
    }
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_vacc_pfizer_dep_an_mois?group=True


##### 8
```js
function (doc) {
  if (doc.type == 'couverture_vaccinale') {
    for (let i=0; i<4; i++) {
        if (doc.vaccinations[i].vaccin == "Pfizer") {
            var dateDoc = new Date(doc.jour);
            emit([doc.dep, dateDoc.getFullYear(), dateDoc.getMonth()], doc.vaccinations[i].doses[0].dose1);
        }
    }
  }
}
```
> curl -X GET $COUCH3/aalbert_vaccination/_design/vue_vaccin/_view/nb_dose1_pfizer_dep_an_mois?group=True


## Exercice 4
#### 1
> curl -X GET $COUCH3/aalbert_vaccination/_shards | jq
> curl -X GET $COUCH3/aalbert_vaccination/ | jq
q : partitions
n : copies

#### 2
> curl -X GET $COUCH3/aalbert_vaccination/_shards/f9eb126d996b0ea76da811f2fdff6cf6 | jq

#### 3
> oui car on voit les 3 dans les partitions


