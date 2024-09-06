CC = g++
CFLAGS = -Wall -pedantic -std=c++0x -c

tp2 : 	fichierTP2.o progListeSC.o mainTP2.o
	${CC} -o tp2 progListeSC.o fichierTP2.o mainTP2.o

progListeSC.o : progListeSC.cpp
	${CC} ${CFLAGS} progListeSC.cpp

fichierTP2.o : fichierTP2.cpp
	${CC} ${CFLAGS} fichierTP2.cpp

mainTP2.o : mainTP2.cpp fichierTP2.h progListeSC.h
	${CC} ${CFLAGS} mainTP2.cpp

clean:
	rm -rf *.o

