; TD 4

(define (somcar x) (if (= x 0) 0 (+ (* x x) (somcar (- x 1)))))
(define (screc x res) (if (= x 0) res (screc (- x 1) (+ res (* x x)))))
(define (somcar2 x) (screc x 0))

(define (puissance x y res) (if (or (= x 0) (= y 0)) res (puissance x (- y 1) (* res x))))
(define (puis x y) (puissance x y 1))
(define puisT (lambda (x n)
                      (letrec ((aux (lambda (n acc) (if (= n 0) acc (aux (- n 1) (* acc x))))))
                              (aux n 1)
                        )
                )
 )


