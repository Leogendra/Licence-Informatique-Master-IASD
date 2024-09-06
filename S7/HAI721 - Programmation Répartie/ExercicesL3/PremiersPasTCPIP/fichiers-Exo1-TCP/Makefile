########################################
#~ définitions
########################################

# nom de l'executable
#BIN=runPeriod

BIN=bin/client  bin/serveur
#BIN=bin/serveur

# liste des fichiers sources 
SRCS0=client.c
SRCS1=serveur.c

default: $(BIN)

########################################
#~ regles pour l'executable
########################################

obj/%.o: %.c
	gcc -Wall -Iinclude -c $< -o $@

bin/client: $(SRCS0:%.c=obj/%.o)
	gcc -lpthread -o $@ $+

bin/serveur: $(SRCS1:%.c=obj/%.o)
	gcc -lpthread -o $@ $+

clean:
	rm -f $(BIN) obj/*.o *~
