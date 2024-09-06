#include <string.h>
#include <stdio.h>//perror
#include <sys/types.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>


int x;
pthread_mutex_t verrou;
pthread_cond_t valX;



void *f1 (void * p) {
pthread_mutex_lock(&verrou); 
printf("fl attend_que x soit >10\n"); 
pthread_cond_wait(&valX, &verrou); 
pthread_mutex_unlock(&(verrou)); 

printf("fl commence son calcul\n"); 
sleep(2); 
printf(" fl se termine\n"); 
pthread_exit(NULL); 
}

void *f2 (void * p) {
  pthread_mutex_lock(&verrou);
  printf("f2 modifie x\n");
  x = 20;
  pthread_mutex_unlock(&verrou);
  printf("f2 réveille f1\n");
  pthread_cond_signal(&valX);
  printf("f2 termine\n");
  pthread_exit(NULL);
}


int main() {
  pthread_t idf1, idf2;
  x = 5;
  pthread_mutex_init(&verrou, NULL);
  pthread_cond_init(&valX, NULL);
  pthread_create(&idf1, NULL, f1, NULL);
  pthread_create(&idf2, NULL, f2, NULL);
  pthread_join(idf1, NULL);
  pthread_join(idf2, NULL);

  printf("fin thread principal\n");

  return 0;
}