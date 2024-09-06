########################################
#~ définitions
########################################

# nom de l'executable
#BIN=runPeriod

BIN=bin/node


# liste des fichiers sources 
SRCS0=Pconfig.c
SRCS1=node.c
SRCS2=common.c
SRCS3=node_interconnexions.c
SRCS4=calcul.c

default: $(BIN)

########################################
#~ regles pour l'executable
########################################

obj/%.o: %.c
	gcc -Wall -Iinclude -c $< -o $@

bin/node: $(SRCS1:%.c=obj/%.o) $(SRCS2:%.c=obj/%.o) $(SRCS3:%.c=obj/%.o)  $(SRCS4:%.c=obj/%.o)
	gcc -o $@ $+ -lpthread

bin/pconfig: $(SRCS0:%.c=obj/%.o)
	gcc -o $@ $+



clean:
	rm -f bin/node obj/node.o *~
