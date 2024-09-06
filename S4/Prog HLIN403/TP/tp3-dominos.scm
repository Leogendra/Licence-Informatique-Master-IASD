(require htdp/draw)
(require lang/posn)
(require racket/trace)

;(require graphics/graphics)

; ; Interface 1 : un domino est un doublet
; 
; (define make-domino cons)
; (define premier car)
; (define second cdr)


; Interface 2 : un domino est une liste.

(define make-domino list)
(define premier car)
(define second cadr)

; Fonctions de base sur les dominos
(define est-double?
  (lambda (d)
    (= (premier d) (second d))))

(define retourner
  (lambda (d)
    (make-domino (second d) (premier d))))

(define eq-dom?
  (lambda (d e)
    (or (and (= (premier d) (premier e)) (= (second d) (second e)))
        (and (= (premier d) (second e)) (= (second d) (premier e))))))


; Interface de représenation d'un jeu
(define make-jeu list)
(define cons-jeu cons)
(define premier-jeu car)
(define suite-jeu cdr)
(define jeu-vide? null?)


(define peut-jouer?
  ;;prend en paramètre un entier n compris entre 0 et 6, un jeu j,
  ;;teste si le jeu j contient au moins un domino qui contient l'entier n.
  (lambda (n j)
    (cond ((jeu-vide? j) #f)
          ((or (= n (premier (premier-jeu j))) (= n (second (premier-jeu j)))) #t)
          (else (peut-jouer? n (suite-jeu j))))))

(define extraire
  ;;prend en paramètre un entier n compris entre 0 et 6, un jeu j,
  ;;retourne le premier domino de j qui contient n
  (lambda (n j)
    (cond ((jeu-vide? j) '())
          ((or (= n (premier (premier-jeu j))) (= n (second (premier-jeu j)))) (premier-jeu j))
          (else (extraire n (suite-jeu j))))))

(define supprimer
  ;;rend un jeu ne contenant pas d
  (lambda (d j)
    (cond ((jeu-vide? j) j)
          ((eq-dom? d (premier-jeu j)) (suite-jeu j))
          (else (cons (premier-jeu j) (supprimer d (suite-jeu j)))))))


; Un jeu est une liste de dominos à poser
; Une chaine est une suite cohérente de dominos posés

(define chaine-valide?
  ;;une chaîne est valide si le second nombre du premier domino est
  ;;égal au premier nombre du premier domino 
  (lambda (j)
    (or (jeu-vide? j)
        (jeu-vide? (suite-jeu j))
        (and (= (second (premier-jeu j)) (premier (premier-jeu (suite-jeu j))))
             (chaine-valide? (suite-jeu j))))))

(define ext-g
  ;;rend le nombre en extrémité gauche d'une chaîne
  (lambda (j)
    (if (jeu-vide? j)
        '()
        (premier (premier-jeu j)))))

(define ext-d
  ;;rend le nombre en extrémité droite d'une chaîne
  (lambda (j)
    (cond ((jeu-vide? j) '())
          ((jeu-vide? (suite-jeu j)) (second (premier-jeu j)))
          (else (ext-d (suite-jeu j))))))

(define ajouter
  ;; prend comme paramètre un domino d et une chaîne, 
  ;; ajoute de façon cohérente le domino à la chaîne ch.
  ;; On suppose que le domino peut toujours être ajouté à la chaîne
  (lambda (d j)
    (let ((r (retourner d)))
      (cond ((jeu-vide? j) (make-jeu d))
          ((= (ext-g j) (second d)) (cons d j))
          ((= (ext-g j) (second r)) (cons r j))
          ((= (ext-d j) (premier d)) (append j (make-jeu d)))
          ((= (ext-d j) (premier r)) (append j (make-jeu r)))
          (else j)))))

; pose prend comme paramètre une liste (j ch) composée d'un jeu j et d'une chaîne ch
; calcule la liste (jp chp) obtenue en ajoutant (si cela est possible) un domino d du jeu j à la chaîne ch.
; le cas échéant, jp est j duquel on a retiré le domino d, 
; et chp est ch à laquelle on a ajouté de manière cohérente le domino d.
; Dans le cas où aucun domino de j ne peut être ajouté à ch, le résultat est la liste (j ch)
(define pose
  (lambda (l)
    (let* ((jeu (car l)) (chaine (cadr l)) (g (ext-g chaine)) (d (ext-d chaine)))
      (cond ((peut-jouer? g jeu) (let ((dom (extraire g jeu))) (list (supprimer dom jeu) (ajouter dom chaine))))
            ((peut-jouer? d jeu) (let ((dom (extraire d jeu))) (list (supprimer dom jeu) (ajouter dom chaine))))
            (else l)))))

; premiers tests
(define dom (make-domino 3 4))
(define domi (make-domino 5 5))
(define j1 (make-jeu dom domi))
(define j2 (make-jeu dom (retourner dom)))



; PARTIE GRAPHIQUE
;--------------------------------------
; un jeu de dominos compte 28 pièces 

(start 342 256)

(define (tracer-gros-point p)
  (draw-solid-disk p 2))

(define (tracer-rectangle p q)
  (begin (draw-solid-line p (make-posn (posn-x p) (posn-y q)))
         (draw-solid-line p (make-posn (posn-x q) (posn-y p)))
         (draw-solid-line q (make-posn (posn-x q) (posn-y p)))
         (draw-solid-line q (make-posn (posn-x p) (posn-y q)))))

(define (tracer-demi-dominos p n)
  (begin (tracer-rectangle (make-posn (- (posn-x p) 12) (- (posn-y p) 12)) 
                           (make-posn (+ (posn-x p) 12) (+ (posn-y p) 12)))
         (cond ((= n 1) (tracer-gros-point p))
               ((= n 2) (begin (tracer-gros-point (make-posn (- (posn-x p) 6) (+ (posn-y p) 6)))
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (- (posn-y p) 6)))))
               ((= n 3) (begin (tracer-gros-point (make-posn (- (posn-x p) 6) (+ (posn-y p) 6))) 
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (- (posn-y p) 6)))
                               (tracer-gros-point p)))
               ((= n 4) (begin (tracer-gros-point (make-posn (- (posn-x p) 6) (+ (posn-y p) 6))) 
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (- (posn-y p) 6)))
                               (tracer-gros-point (make-posn (- (posn-x p) 6) (- (posn-y p) 6)))
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (+ (posn-y p) 6)))))
               ((= n 5) (begin (tracer-gros-point (make-posn (- (posn-x p) 6) (+ (posn-y p) 6))) 
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (- (posn-y p) 6)))
                               (tracer-gros-point (make-posn (- (posn-x p) 6) (- (posn-y p) 6)))
                               (tracer-gros-point (make-posn (+ (posn-x p) 6) (+ (posn-y p) 6)))
                               (tracer-gros-point p)))
               ((= n 6) (begin (tracer-gros-point (make-posn (- (posn-x p) 6) (+ (posn-y p) 6))) 
                            (tracer-gros-point (make-posn (+ (posn-x p) 6) (- (posn-y p) 6)))
                            (tracer-gros-point (make-posn (- (posn-x p) 6) (- (posn-y p) 6)))
                            (tracer-gros-point (make-posn (+ (posn-x p) 6) (+ (posn-y p) 6)))
                            (tracer-gros-point (make-posn (- (posn-x p) 6) (posn-y p)))
                            (tracer-gros-point (make-posn (+ (posn-x p) 6) (posn-y p))))))))

(define (draw-dominos p d)
  (begin (tracer-demi-dominos (make-posn (- (posn-x p) 12) (posn-y p)) (premier d))
         (tracer-demi-dominos (make-posn (+ (posn-x p) 12) (posn-y p)) (second d))))

(define (dessiner-colonne-bis l p i d)
  (if (null? l)
      (void)
      (if (= i 5)
          (begin (draw-dominos p (car l))
                 (dessiner-colonne-bis (cdr l) (make-posn ((if (equal? d 'gd) + -) (posn-x p) 50) (+ (posn-y p) (* 4 24))) 1 d))
          (begin (draw-dominos p (car l))
                 (dessiner-colonne-bis (cdr l) (make-posn (posn-x p) (- (posn-y p) 24)) (+ i 1) d)))))

(define (dessiner-colonne l j)
  (if (= j 1)
      (dessiner-colonne-bis l (make-posn 24 (- 256 12)) 1 'gd)
      (dessiner-colonne-bis l (make-posn 318 (- 256 12)) 1 'dg)))

(define (dessiner-jeux-bis l p)
  (if (null? l)
      (void)
      (if (= (posn-x p) 318)
          (begin (draw-dominos p (car l))
                 (dessiner-jeux-bis (cdr l) (make-posn 24 (+ (posn-y p) 26))))
          (begin (draw-dominos p (car l))
                 (dessiner-jeux-bis (cdr l) (make-posn (+ (posn-x p) 49) (posn-y p)))))))

(define (dessiner-jeux l)
  (dessiner-jeux-bis l (make-posn 24 12)))
      
(dessiner-colonne '((1 3) (4 2) (2 6) (2 2) (5 5) (5 6)) 1)    
(dessiner-colonne '((2 3) (4 2) (5 6) (2 1) (5 5) (4 1) (6 6)) 2)
(dessiner-jeux '((1 5) (5 2) (2 6) (6 1) (1 5) (5 1) (1 6) (6 2)))

;**************************************************
;**************************************************
;Rajout pour jouer :

;Fonction pour générer le jeu d'un joueur, renvoie une liste de x paires
(define (generer-jeu x)
  (if (= x 0)
      ()
      (cons (make-domino (random 7) (random 7)) (generer-jeu (- x 1)))))
  
;Génère aléatoirement 2 jeux, les affiche, et fait jouer le 2ème joueur (Pour éviter que la chaine soit vide au départ)
(define (debut-jeu)
  (let ((j1 (generer-jeu 14))
        (j2 (generer-jeu 14))
        (ch (generer-jeu 1)))
    (list j1 j2 ch)))


(define (jouer j1 j2 ch)
  (let ((G (ext-g ch))
        (D (ext-d ch)))
    
    (clear-all)
    (dessiner-colonne j1 1)
    (dessiner-colonne j2 2)
    (dessiner-jeux ch)
    (sleep-for-a-while 1)
    
    ;condition d'arret : soit un des jeux est vide, soit aucun des deux joueurs ne peut jouer
    (if (or (or (null? j1) (null? j2))
            (not (or (peut-jouer? G j1)(peut-jouer? G j2)(peut-jouer? D j1)(peut-jouer? D j2))))
        (void)
        
        ;sinon
        ;le joueur 1 joue et on dessine :
        (let ((int (pose (list j1 ch ))))
          (clear-all)
          (dessiner-colonne (car int) 1)
          (dessiner-colonne j2 2)
          (dessiner-jeux (car (cdr int)))     
          (sleep-for-a-while 1)
          
        ;le joueur 2 joue :
          (let ((int2 (pose (list j2 (car (cdr int))))))
            (jouer (car int) (car int2) (car (cdr int2))))))))

(trace jouer)

(define (jeu)
  ( let ((L (debut-jeu)))
     (jouer (car L) (cadr L) (caddr L))))
;Pour lancer le jeu :
(jeu)