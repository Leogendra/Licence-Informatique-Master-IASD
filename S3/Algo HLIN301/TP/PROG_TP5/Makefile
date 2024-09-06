CC = g++
CFLAGS = -Wall -pedantic -std=c++0x -c

tp5 : 	outilsTab5.o  mainTP5.o
	${CC} -o tp5  outilsTab5.o mainTP5.o


outilsTab5.o : outilsTab5.cpp
	${CC} ${CFLAGS} outilsTab5.cpp

mainTP5.o : mainTP5.cpp outilsTab5.h 
	${CC} ${CFLAGS} mainTP5.cpp

clean:
	rm -rf *.o

