(define range2 (lambda (n) (letrec ((aux (lambda (i acc) (if (= i 0) acc (aux (- i 1) (cons i acc)))))) (aux n '() ))))

;TD 4 ex4
(define somCE (lambda (n) (if (< n 10) n (+ (modulo n 10) (somCE (quotient n 10))))))
(define somCT (lambda (n) (letrec ((aux (lambda (i acc) (if (< i 10) (+ i acc) (aux (quotient i 10) (+ acc (modulo i 10))))))) (aux n 0))))

;td4 ex5
(define avecE (lambda (c x) (if (< x 10) (= c x) (or (= c (modulo x 10)) (avecE c (quotient x 10))))))
(define avecT (lambda (c x) (if (< x 10) (= c x) (if (= c (modulo x 10)) #t (avecT c (quotient x 10))))))

;td4 ex6
(define (puisMaxT (lambda (p m) (if (> p m) 1 (* p (puisMaxE p (quotient m p)))))))
(define (puisMaxE (lambda (p m) (letrec ((aux (lambda (i acc) (if (> p i) acc (aux (quotient i p) (* p acc))))))) (aux m 1))))

;td4 ex7
(define chCE (lambda (p m) (if (= n 0) 0 (+ n (* 10 (puisMaxT 10 n) (chCE (- n 1)))))))
(define chCE (lambda (p m) (letrec ((aux (lambda (i acc) (if (> i n) acc (aux (+ i 1) (+ (* 10 (puisMaxT 10 i) acc) i))))))) (aux 0 0)))
