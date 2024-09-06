CC = g++
CFLAGS = -Wall -pedantic -std=c++0x -c

tp4 : 	fichierTP4.o progListeSC.o mainTP4.o
	${CC} -o tp4 progListeSC.o fichierTP4.o mainTP4.o

progListeSC.o : progListeSC.cpp
	${CC} ${CFLAGS} progListeSC.cpp

fichierTP4.o : fichierTP4.cpp
	${CC} ${CFLAGS} fichierTP4.cpp

mainTP4.o : mainTP4.cpp fichierTP4.h progListeSC.h
	${CC} ${CFLAGS} mainTP4.cpp

clean:
	rm -rf *.o

