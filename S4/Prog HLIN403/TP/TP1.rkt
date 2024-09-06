(define (puis2 x)
  (* x x)
)

(define (puis4 x)
  (puis2 (puis2 x))
)
(require (lib "turtles.ss" "graphics"))

(define (ex10 x)
  (cond ((< x 5) #f) ((> x 7) (* x x)) ((= x 5) 3 ((= x 6) "toto") #t))
)

(define (monabs x)
  (if (< x 0) (- 0 x) x)
)

(define (care-div x y)
  (if (< y 0) (display "errror") (/ x y))
)

(define (signalS t)
  (cond ((and (<= t -1) (> t -3)) 1) ((and (> t 2) (<= t 4)) 2) (0))
)

(define (placement c t a)
  (* c (expt (+ 1 t) a))
)

(define (spirale x deg av)
  (if (< x 1) (turn 0) ((turn deg) (draw av) (spirale (- x 1) deg (+ av 1))))
)
                    