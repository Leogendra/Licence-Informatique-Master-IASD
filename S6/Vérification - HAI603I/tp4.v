(* TP 4 *)
(* Exercice 1 *)
Require Import Classical.
Require Import FunInd.

Inductive is_fact : nat -> nat -> Prop :=
| is_fact_0 : is_fact 0 1
| is_fact_S : forall n s : nat, is_fact n s -> is_fact (S n) (s * (S n)).

Fixpoint fact (n : nat) : nat :=
match n with
| 0 => 1
| (S n) => (fact n) * (S n)
end.


Compute fact 6.

Goal forall n m : nat, (fact n) = m -> is_fact n m.
induction n.
intros.
rewrite <- H.
simpl.
apply is_fact_0.

intros.
rewrite <- H.
simpl.
apply is_fact_S.
apply IHn.
reflexivity.
Qed.


(* Exercice 2 *)
Inductive is_even : nat -> Prop :=
| is_even_0 : is_even 0
| is_even_S : forall n : nat, is_even n -> is_even (S (S n)).

Fixpoint even (n : nat) :=
match n with
| 0 => true
| (S (S n)) => even n
end.