for dens in 149 297 446 535
do
    for ((i=211; i>178; i-=3))
    do
        ./urbcsp 35 17 $dens $i 10 > "../reseaux/bench$dens/csp_$i.txt"
    done
done