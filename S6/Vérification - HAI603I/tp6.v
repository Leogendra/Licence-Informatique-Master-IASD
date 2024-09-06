(* TP 6 *)
(* Exercice 1 *)
Require Import Classical.
Require Import FunInd.

Inductive is_fact : nat -> nat -> Prop :=
| is_fact_0 : is_fact 0 1
| is_fact_S : forall n s : nat, is_fact n s -> is_fact (S n) (s * (S n)).


Lemma fact : forall (n : nat), {v : nat | is_fact n v}.
Proof.
intros.
elim n.
exists 1.
apply is_fact_0.
intros.
inversion H.
exists (mult x (S n0)).
apply is_fact_S.
auto.
Defined.

Require Extraction.
Recursive Extraction fact.


Inductive is_equ : nat -> nat -> Prop :=
| is_equ_0 : is_equ 0 0
| is_equ_S : forall (n s : nat), is_equ n s -> is_equ (S n) (S s).



Lemma eq_nat : forall (n m : nat), {n = m} + {n <> m}.
Proof.
(double induction n m).
intros.
decide equality.
intros.
decide equality.
intros.
decide equality.
intros.
decide equality.
Defined.

