from random import *
from matplotlib import pyplot as plt
from datetime import datetime


def entiersAleatoires(n,a,b):
	res = []
	for i in range (0,n):
		res.append(randint(a,b))
	return res

def entiersAleatoires2(n,a,b):
	res = []
	for i in range (0,n):
		res.append(randrange(a,b))
	return res

#2) :

def flottantsAleatoires(n):
	res = []
	for i in range (0,n):
		res.append(random())
	return res   

def flottantsAleatoires2(n,a,b):
	res = []
	for i in range (0,n):
		res.append(uniform(a,b))
	return res


"""
nbrr = flottantsAleatoires(100)
nbrr2 = flottantsAleatoires2(100,-3,10)

plt.plot(nbrr)
plt.plot(nbrr2)
plt.show()
"""
#3) :

def pointsDisque(n):
    res = []
    while (n>0):
        n = n-1
        x = uniform(-1,1)
        y = uniform(-1,1)
        while ((x*x + y*y) > 1): 
            x = uniform(-1,1)
            y = uniform(-1,1)
        res.append((x,y))
    return res

def pointsDisque2(n):
    res = []
    while (n>0):
        n = n-1
        x = uniform(-1,1)
        y = uniform(-1,1)
        while ((x*x + y*y) > 1):
            y = uniform(-1,1)
        res.append((x,y))
    return res

def affichagePoints(L):
    X = [x for x,y in L]
    Y = [y for x,y in L]
    plt.scatter(X, Y, s = 2)
    plt.axis('square')
    plt.show()

'''
D1 = pointsDisque(2000)
D2 = pointsDisque2(2000)
affichagePoints(D1)
affichagePoints(D2)
'''

#D2 a des pts concentrés aux poles car comme x ne bouge pas, les points sur cette abcisse sont tous regroupés, alors qu'ils sont étirés pour les x = 0

#4) :

def aleatoireModulo(N):
    k = N.bit_length()
    return getrandbits(k) % N

def aleatoireRejet(N):
    k = N.bit_length()
    res = getrandbits(k)
    while (res > N):
        res = getrandbits(k)
    return res
        
'''    
n = aleatoireModulo(50)
print(n)

n2 = aleatoireRejet(50)
print(n2)


L1 = []
L2 = []
for i in range (0,1000):
    L1.append(aleatoireModulo(100))
    L2.append(aleatoireRejet(100))

plt.hist(L1, bins=100)
plt.hist(L2, bins=100)
plt.show()

#modulo produit uniformément les entier, rejet non
'''

#exercice 2 :

def eltMajDet(T):
    i = 0
    while (i<len(T)):
        cpt = 0
        j = 0
        while (j<len(T) and cpt<len(T)/2):
            if (T[i]==T[j]):
                cpt = cpt + 1
            j = j + 1
        if (cpt>=len(T)/2):
            return T[i]
        i = i + 1


def eltMajProba(T):
    i = randint(0,len(T)-1)
    while (True):
        cpt = 0
        j = 0
        while (j<len(T) and cpt<len(T)/2):
            if (T[i]==T[j]):
                cpt = cpt + 1
            j = j + 1
        if (cpt>=len(T)/2):
            return T[i]
        i = randint(0,len(T)-1)


T = [5,5,1,1,1,1,11,2,2,5,654,64,64,1,1,1,1,1]
'''
print("element majoritaire 1 = ",eltMajDet(T))
print("element majoritaire 2 = ",eltMajProba(T))
'''

def tabAlea(n, a, b, k):
    if k<n/2:
        return "[-1]"
    m = randint(a,b)
    T = []
    for i in range (0,n):
        if i < k:
            T.append(m)
        else:
            m2 = randint(a,b)
            while m2 == m:
                m2 = randint(a,b)
            T.append(m2)
    shuffle(T)
    return T


def TabDeb(n, a, b, k):
    if k<n/2:
        return "[-1]"
    m = randint(a,b)
    T = []
    for i in range (0,n):
        if i < k:
            T.append(m)
        else:
            m2 = randint(a,b)
            while m2 == m:
                m2 = randint(a,b)
            T.append(m2)
    return T


def TabFin(n, a, b, k):
    if k<n/2:
        return "[-1]"
    m = randint(a,b)
    T = []
    for i in range (0,n):
        if i > n-k:
            T.append(m)
        else:
            m2 = randint(a,b)
            while m2 == m:
                m2 = randint(a,b)
            T.append(m2)
    return T

'''
T2 = tabAlea(20, 4, 25, 12)

print("T2 = ",T2, "elmt maj : ",eltMajProba(T2))
print("\ntabdeb = ",TabDeb(20, 4, 25, 12))
print("\ntabdeb = ",TabFin(20, 4, 25, 12))
'''

'''
T3 = TabFin(1000, 0, 100, 800)
temps1 = datetime.now()
tt1 = eltMajDet(T3)
temps2 = datetime.now()
print("elmt maj 1 =",tt1, ", temps =",temps2-temps1) 

temps1 = datetime.now()
tt2 = eltMajProba(T3)
temps2 = datetime.now()
print("elmt maj 2 =",tt2, ", temps =",temps2-temps1)
'''

#ex 3 :

#1) :

def suivant(xn, a, c, m):
    return (a*xn + c) % m

def valeurs(x0, a, c, m, N):
    val = [x0]
    i = 0
    while N>1:
        val.append(suivant(val[i], a, c, m))
        N = N - 1
        i = i + 1
    return val

'''
print(valeurs(0, 3, 5, 136, 20))
   ''' 
#je prédit 64 :)

#2)
   
'''
print(valeurs(0, 75, 74, 2**16, 2**20))
'''

#3) :

plt.plot(valeurs(0, 16807, 0, (2**31)-1, 2**), 'r.')

















