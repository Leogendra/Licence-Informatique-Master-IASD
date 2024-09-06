for ((i=211; i>=181; i-=3))
do
    ./urbcsp 35 17 240 $i 10 > "../reseaux/bench40Full/csp_$i.txt"
done
