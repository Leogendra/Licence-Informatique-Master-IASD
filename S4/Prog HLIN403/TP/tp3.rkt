(define (von_koch prof a) (if (= prof 0) a (von_koch (- prof 1) (* (/ 4 3) a))))

(require (lib "turtles.ss" "graphics"))

(define (spirale x deg av)
  (if (< x 1) (turn 0) ((turn deg)
                        (draw av)
                        (spirale (- x 1) deg (+ av 1))))
)


(define (von_graphic prof a trait) (if (= prof 0) (void) (begin
                                                                (if (not (= prof 1)) (von_graphic (- prof 1) a (/ trait 3)) (draw (/ trait 3))) (begin
                                                                (turn 60)
                                                                (if (not (= prof 1)) (von_graphic (- prof 1) a (/ trait 3)) (draw (/ trait 3))) (begin
                                                                (turn 240)
                                                                (if (not (= prof 1)) (von_graphic (- prof 1) a (/ trait 3)) (draw (/ trait 3))) (begin
                                                                (turn 60)
                                                                (if (not (= prof 1)) (von_graphic (- prof 1) a (/ trait 3)) (draw (/ trait 3)))))))))
                                                             


                                 
(define (von_g prof a) (begin
                          (move (/ a 2))
                          (turn 180)
                          (move a)
                          (turn 180)
                          (von_graphic prof a a)
  ))


(define (floc prof a) (begin
                        (turtles)
                        (clear)
                        (von_g prof a)
                        (turn 240)
                        (move (/ a 2))
                        (von_g prof a)
                        (turn 240)
                        (move (/ a 2))
                        (von_g prof a)
                       ))