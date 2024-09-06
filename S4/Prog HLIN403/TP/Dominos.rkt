;; The first three lines of this file were inserted by DrRacket. They record metadata
;; about the language level of this file in a form that our tools can easily process.
#reader(lib "htdp-advanced-reader.ss" "lang")((modname Dominos) (read-case-sensitive #t) (teachpacks ((lib "image.rkt" "teachpack" "2htdp") (lib "draw.rkt" "teachpack" "htdp"))) (htdp-settings #(#t constructor repeating-decimal #t #t none #f ((lib "image.rkt" "teachpack" "2htdp") (lib "draw.rkt" "teachpack" "htdp")) #f)))
;ex 1
(define (random_dom) (list (random 0 7) (random 0 7)))
(define (create_dom a b) (list a b))
(define (set1 d a) (list a (cadr d)))
(define (set2 d b) (list (car d) b))

(define (get_cotes d) (list (car d) (cadr d)))
(define (cote1 d) (car d))
(define (cote2 d) (cadr d))


;ex 2
(define (domino_n j n) (if (< n 2) (car j) (domino_n (cdr j) (- n 1))))
(define (prem_dom j) (if (null? j) '() (car j)))
(define (der_dom j) (domino_n j (nbr_dom j)))
(define (suite_jeu j) (if (null? j) '() (cdr j)))
(define (nbr_dom j) (if (null? j) 0 (+ 1 (nbr_dom (suite_jeu j)))))
(define (ajouter_nbr j d n)
  (cond ((< n 2) (cons d  j))
        ((equal? (der_dom j) (prem_dom j)) (list (prem_dom j) d))
        (else (cons (prem_dom j) (ajouter_nbr (suite_jeu j) d (- n 1))))))
               
(define (ajouter_fin j d) (ajouter_nbr j d (+ (nbr_dom j) 1)))
(define (ajouter_deb j d) (ajouter_nbr j d 1))



;ex 3
(define (est_double? d) (equal? (cote1 d) (cote2 d)))


;ex 4
(define (doubles jeu) (filter (lambda (d) (est_double? d)) jeu)) 


;ex 5
(define (retourner d) (list (cote2 d) (cote1 d)))


;ex 6
(define (peut-jouer? j e) 
  (if (or (> e 6) (< e 0)) #f
      (ormap (lambda (d) (or (= (cote1 d) e) (= (cote2 d) e))) j)))

(define (jouable? j d)
  (cond ((null? j) #f)
        ((= (ext_g j) (cote1 d)) #t)
        ((= (ext_g j) (cote2 d)) #t)
        ((= (ext_d j) (cote1 d)) #t)
        ((= (ext_d j) (cote2 d)) #t)
        (else #f)))


;ex 7
(define (extraire j e) 
  (cond ((or (> e 6) (< e 0)) void)
        ((null? j) '())
        ((or (= (cote1 (domino_n j 1)) e) (= (cote2 (domino_n j 1)) e)) (domino_n j 1))
        (else (extraire (suite_jeu j) e))))


;ex 8
(define (chaine_valide? j) (if (< (nbr_dom j) 2) #t
                               (if (= (cote2 (domino_n j 1)) (cote1 (domino_n j 2)))
                                   (chaine_valide? (suite_jeu j)) #f)))


;ex 9
(define (ext_g j) (cote1 (domino_n j 1)))
(define (ext_d j) (cote2 (domino_n j (nbr_dom j))))


;ex 10
(define (supprimer j d)
  (if (null? j) '()
      (if (equal? (prem_dom j) d) (suite_jeu j)
          (cons (prem_dom j) (supprimer (suite_jeu j) d)))))


;ex 11
(define (ajouter j d)
  (cond ((= (ext_g j) (cote2 d)) (ajouter_deb j d))
        ((= (ext_g j) (cote1 d)) (ajouter_deb j (retourner d)))
        ((= (ext_d j) (cote1 d)) (ajouter_fin j d))
        ((= (ext_d j) (cote2 d)) (ajouter_fin j (retourner d)))
        (else j)))


;ex 12
(define (pose li)
  (let* ((j (car li))
         (ch (cadr li))
         (g (ext_g ch))
         (d (ext_d ch)))
         (cond ((peut-jouer? j g) (list (supprimer j (extraire j g)) (ajouter ch (extraire j g))))
               ((peut-jouer? j d) (list (supprimer j (extraire j d)) (ajouter ch (extraire j d))))
               (else li))))


;ex 13
(define (dessiner-gros-point p) (draw-solid-disk p 2))



;ex 14
(define (dessiner-rectangle p1 p2)
  (let ((x1 (posn-x p1))
         (y1 (posn-y p1))
         (x2 (posn-x p2))
         (y2 (posn-y p2)))
    (begin (draw-solid-line p1 (make-posn x2 y1))
           (draw-solid-line p1 (make-posn x1 y2))
           (draw-solid-line p2 (make-posn x2 y1))
           (draw-solid-line p2 (make-posn x1 y2)))))

;ex 15
(define (dessiner-demi-dominos p n)
  (let ((x (posn-x p))
         (y (posn-y p)))
    (begin (dessiner-rectangle (make-posn (- x 12) (- y 12)) (make-posn (+ x 12) (+ y 12)))
           (cond ((= n 1) (dessiner-gros-point p))
                 ((= n 2) (begin (dessiner-gros-point (make-posn (+ x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (- y 6)))))
                 ((= n 3) (begin (dessiner-gros-point (make-posn (+ x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (- y 6)))
                                 (dessiner-gros-point p)))
                 ((= n 4) (begin (dessiner-gros-point (make-posn (+ x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (+ x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (+ y 6)))))
                 ((= n 5) (begin (dessiner-gros-point (make-posn (+ x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (+ x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (+ y 6)))
                                 (dessiner-gros-point p)))
                 ((= n 6) (begin (dessiner-gros-point (make-posn (+ x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (+ x 6) (- y 6)))
                                 (dessiner-gros-point (make-posn (- x 6) (+ y 6)))
                                 (dessiner-gros-point (make-posn (+ x 6) y))
                                 (dessiner-gros-point (make-posn (- x 6) y))))
                 (else void)
                 ))))
                 

;ex 16
(define (dessiner-dominos p d)
  (let ((x (posn-x p))
        (y (posn-y p)))
    (begin (dessiner-demi-dominos (make-posn (- x 12) y) (cote1 d))
           (dessiner-demi-dominos (make-posn (+ x 12) y) (cote2 d)))))



;ex 17
(define (dessiner-jeu-dominos jeu n)
  (letrec ((dessj (lambda (j num nb)
                   (if (null? j) ""
                       (let ((x (if (= num 1) (+ 30 (* 50 (quotient nb 5))) (- 310 (* 50 (quotient nb 5)))))
                             (y (- 234 (* 26 (remainder nb 5)))))
                         (begin (dessiner-dominos (make-posn x y) (car j))
                                (dessj (cdr j) num (+ nb 1))))))))
    (dessj jeu n 0)))



;ex 18
(define (dessiner-chaine-dominos ch)
  (letrec ((dessch (lambda (j nb)
                   (if (null? j) ""
                       (let ((x (+ 40 (* 50 (remainder nb 6))))
                             (y (+ 30 (* 26 (quotient nb 6)))))
                         (begin (dessiner-dominos (make-posn x y) (car j))
                                (dessch (cdr j) (+ nb 1))))))))
    (dessch ch 0)))



;ex 19
(define (generer_jeu n) (if (= n 0) '() (cons (random_dom) (generer_jeu (- n 1)))))



;ex 20
(define (debut-jeu j1 j2) (list (generer_jeu j1) (generer_jeu j2) (list (random_dom))))



;ex 21
(define (jouer jeu1 jeu2 chaine)
  (letrec ((aux (lambda (j1 j2 ch tour)
                  (let ((j (if (= tour 1) j1 j2)))
                    (cond ((null? j2) "Le joueur 1 n'a plus de domino, il remporte la partie !")
                          ((null? j2) "Le joueur 2 n'a plus de domino, il remporte la partie !")
                                                            
                          ((equal? (pose (list j ch)) (list j ch)) (if (= tour 1)
                               "Le joueur 1 ne peux plus jouer, le joueur 2 gagne"
                               "Le joueur 2 ne peux plus jouer, le joueur 1 gagne"))

                          (else (let ((new_j (car (pose (list j ch))))
                                (new_ch (cadr (pose (list j ch)))))
                                  (begin
                                    (sleep-for-a-while 1)
                                    (clear-all)
                                    (if (= tour 1)
                                        (begin
                                          (dessiner-jeu-dominos new_j 1)
                                           (dessiner-jeu-dominos j2 2))
                                        (begin
                                          (dessiner-jeu-dominos j1 1)
                                           (dessiner-jeu-dominos new_j 2)))
                                    (dessiner-chaine-dominos new_ch)
                                    (if (= tour 1) (aux new_j j2 new_ch 2) (aux j1 new_j new_ch 1))))))))))
    (aux jeu1 jeu2 chaine 1)))



  
;ex 22
(define (jeu j1 j2)
  (let* ((all (debut-jeu j1 j2))
        (jeu1 (car all))
        (jeu2 (cadr all))
        (ch (caddr all)))
    (begin
      (dessiner-jeu-dominos jeu1 1)
      (dessiner-jeu-dominos jeu2 2)
      (dessiner-chaine-dominos ch)
      (jouer jeu1 jeu2 ch))))
    






;tests
;(display "domino aléatoire : ") (define D1 (random_dom)) D1
;(define J1 (generer_jeu 5)) J1
;(display "\njeu J1 aléatoire à 5 dominos : ") J1
;(display "\npeut on jouer 1 sur J1 ? ") (peut-jouer? J1 1)
;(if (peut-jouer? J1 1) (begin (display "Lequel ? ") (extraire J1 1)) "tant pis !")
;(display "est valide ? ") (chaine_valide? J1)
;(display "gauche de la chaine : ") (ext_g J1)
;(display "droite de la chaine : ") (ext_d J1)
;(display "4ème domino : ") (domino_n J1 4)
;(display "Supprimer le 4ème domino de la chaine : ") (supprimer J1 (domino_n J1 4))
;(define D1 (list (random 0 7) (ext_d J1))) 
;(display "domino jouable : ") D1
;(display "domino joué : ") (ajouter J1 D1)
;(display "jeu J2 :") (define J2 '((3 2) (4 5))) J2
;(display "J'ai J2, je veux jouer sur J1 : ") (pose (list J2 J1))
(start 342 256)
;(define A (make-posn 25 25))
;(define B (make-posn 30 70))
;(define C (make-posn 120 40))
;(dessiner-gros-point C)
;(dessiner-rectangle B C)
;(dessiner-demi-dominos C 6)
;(dessiner-dominos A D1)
;(define play (debut-jeu 10 10))
;(dessiner-jeu-dominos (car play) 1)
;(dessiner-jeu-dominos (cadr play) 2)
;(dessiner-chaine-dominos (caddr play))
(jeu 3 3) 

















