Require Import Arith.
Require Import Omega.
Require Export List.
Open Scope list_scope.
Import ListNotations.


Inductive is_perm : list nat -> list nat -> Prop :=
 | is_perm_reflex : forall (l : list nat), is_perm l l
 | is_perm_S : forall (e : nat), forall (l l' : list nat), 
      is_perm l l' -> is_perm (e::l) (e::l')
 | is_perm_S_app : forall (e : nat), forall (l l' : list nat), 
      is_perm l l' -> is_perm (e::l) (l' ++ (e::nil))
 | is_perm_indu : forall (e : nat), forall (l1 l2 l3 : list nat), 
      is_perm l1 (l2++l3) -> is_perm (e::l1) (l2++(e::l3))
 | is_perm_S_sym : forall (l l' : list nat), is_perm l l' -> is_perm l' l
 | is_perm_trans : forall (l1 l2 l3 : list nat), is_perm l1 l2 -> is_perm l2 l3 -> is_perm l1 l2.

Inductive is_sorted : list nat -> Prop :=
| is_sorted_nil : is_sorted nil
| is_sorted_un : forall (e : nat), is_sorted (e :: nil)
| is_sorted_S : forall (e h : nat), forall (l : list nat), 
      e <= h -> is_sorted (h :: l) -> is_sorted (e :: h :: l).

Fixpoint sort (l : list nat) : list nat :=
match l with
| nil => nil
| a :: l1 => app (sort l1) (a :: nil)
end.


Lemma ex1_2 : is_perm [1;2;3] [2;1;3].
Proof.
apply (is_perm_indu 1 [2;3] [2]).
simpl.
apply is_perm_reflex.
Qed.


(*
Lemma ex1_2 : is_perm [1;2;3] [3;2;1].
Proof.
apply (is_perm_S_app 1 [2;3] [3;2]).
apply (is_perm_S_app 2 [3] [3]).
apply is_perm_reflex.
Qed.
*)

Lemma ex1_4 : is_sorted [1; 2; 3].
Proof.
 apply is_sorted_S.
 auto.
 apply is_sorted_S. 
 auto.
 apply is_sorted_un.
Qed.



Check le_dec.
Print sumbool.

Fixpoint inf_10 (n : nat) : Prop :=
match (le_dec n 10) with
| left _ => True
| right _ => False
end.

Eval compute in (inf_10 5).
Eval compute in (inf_10 15).

Fixpoint insert (x : nat) (l : list nat) {struct l} : list nat :=
match l with
| nil => [x]
| t :: q => match (le_dec x t) with
            | left _ => x :: l
            | right _ => t :: (insert x q)
            end
end.



Fixpoint isort (l : list nat) : list nat :=
match l with
| nil => nil
| t :: q => insert t (isort q)
end.


Eval compute in isort [5; 4; 3; 2; 1].


(* Exercice 3 *)

Lemma head_is_perm : forall (x1 x2 : nat) (l : list nat),
is_perm (x1 :: x2 :: l) (x2 :: x1 :: l).
Proof.
intros.
apply (is_perm_indu x1 (x2 :: l) [x2] l).
simpl.
apply (is_perm_reflex).
Qed.


Lemma insert_is_perm : forall (x : nat) (l : list nat),
is_perm (x::l) (insert x l).
Proof.
intros.
elim l.
simpl.
apply is_perm_reflex.
intros.
simpl.
elim (le_dec x a).
intros.
apply is_perm_reflex.
intros.
apply is_perm_S_sym.
apply (is_perm_indu a (insert x l0) [x] l0).
simpl.
apply is_perm_S_sym.
apply H.
Qed.


Lemma insert_is_sorted : forall (x : nat) (l : list nat),
is_sorted l -> is_sorted (insert x l).
Proof.
intro.
intro.
elim l.
simpl.
intro.
apply is_sorted_un.
intros.
simpl.
elim (le_dec x a).
intro.
apply (is_sorted_S).
auto.
auto.
intros.

simpl.
Qed.








