CC = g++
CFLAGS = -Wall -pedantic -std=c++0x -c

tp1 : 	outilsTab.o mainTP1.o
	${CC} -o tp1 outilsTab.o mainTP1.o

outilsTab.o : outilsTab.cpp
	${CC} ${CFLAGS} outilsTab.cpp

mainTP1.o : mainTP1.cpp outilsTab.h
	${CC} ${CFLAGS} mainTP1.cpp

clean:
	rm -rf *.o

