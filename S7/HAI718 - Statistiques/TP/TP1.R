# Exo 1
# # n <- 15
# 5 -> n
# x <- 1
# X <- 10
# n
# x
# X
# n <- 10 + 2
# n
# n <- 3 + rnorm(1)
# (10 + 2)*5
# name <- "Carmen"; n1 <- 10; n2 <- 100; m<- 0.5
# ls.str(pat = "n")

# Exo 2
# M <- data.frame(n1, n2, m)
# ls.str(pat = "M")
# rm()

#Ex 3
#dev.list()

#Ex 4
# x11(); x11(); pdf();
# dev.list()
# dev.cur()
# dev.set(3)
# dev.off()
# dev.list()

#Ex 5
x <- rnorm(1000)
y <- x + rnorm(1000)
#plot(x, 3*x+5)
#plot(x,y,xlab="Mille valeurs au hasard", ylab="Mille autres valeurs", xlim=c(-2,2), ylim=c(-2,2), pch=20, col="red", bg="yellow", bty="l", tcl=0.3, main="Configurer les graphiques en R", las=1, cex=0.5)
#points(0,0,col="blue")
#lines(stats::lowess(x,y))
# Ex 6
hist(x,col="green",probability=T)
lines(density(x),col="red")

# Ex 7
P_inf_3 <- pbinom(3,18,1/6)
P_egal_3 <- P_inf_3 - pbinom(2,18,1/6)
P_sup_3 <- 1 - pbinom(2,18,1/6)
P_inf_16 <- pbinom(16,18,1/6)

P_inf_3*100
P_egal_3*100
P_sup_3*100
P_inf_16*100

# Ex 8
pnorm(1.41,0,1)*100 #P(U < 1.41)
pnorm(-2.07,0,1)*100 #P(U < -1.07)
(1 - pnorm(-1.26,0,1))*100 #P(U > -1.26)

qnorm(0.95,0,1) # P(U < u) = 0.95
qnorm(0.1,0,1) # P(U < u) = 0.1
qnorm(0.01,0,1) # P(U < u) = 0.01 <=> P(U > u) = 0.99
#2
pnorm(-5,-5,16)*100 #P(U < -5)
pnorm(0,-5,16)*100 #P(U < 0)
(1 - pnorm(5,-5,16))*100 #P(U > 5)

qnorm(0.95,-5,16) # P(U < u) = 0.95
qnorm(0.05,-5,16) # P(U < u) = 0.05
qnorm(0.99,-5,16) # P(U > u) = 0.01

# Ex 9
pchisq(6.26,15)*100 # P(X15 < 6.26)
(1 - pchisq(3.25,10))*100 # P(X10 > 3.25) 
(1 - pchisq(11.52,10+15))*100 # P(X10+X15 > 3.25) 

#X ~ X15
qchisq(0.01,15) # P(X < x) = 0.01
qchisq(0.05,15) # P(X < x) = 0.05
qchisq(0.99,15) # P(X < x) = 0.99

# Ex 10
pt(0.408,5)*100
pt(-2.07,5)*100
(1 - pt(0.132,5))*100

qt(0.05,5)
qt(1-0.9,5)
qt(0.5,5)

# Ex 11
ech100 <- rbinom(100,20,0.04)
ech100000 <- rbinom(100000,20,0.04)

hist(ech100,col="darkgreen")
hist(ech100000,col="darkblue")

# Ex 12
norm100 <- rnorm(100,-5,16)
norm100000 <- rnorm(100000,-5,16)

hist(norm100, col="darkgreen", probability=T)
hist(norm100000, col="darkblue", probability=T)

points(density(norm100000),col="red")

# Ex 13
#skip
####################################################
# TP1 BIS
# Ex 1
#c(1:5) # liste
#1:6 # range
seq(1,5,by=0.5) # range + param
##rep(3,2) # repetition
rep(1:3,5)
cat(10,3:6,10,100,100,seq(10,40,by=10))

# Ex 2
a <- vector(length=10, mode="numeric")
a
#x<-10:15
#x
#x[2]
#x[c(2,4)]
#x[-4]
x <- c(1,4,5)
y <- seq(1,9,2)
y[2]
y[-2]
xy <- y[x]
y[2:4]

# Ex 3
x <- 1:3
x**2
x/c(2,4,6)
y <- 1:5
x*y
# il multiplie chaque terme de chaque suite


