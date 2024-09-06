
TP2 :
Open Scope type_scope.
Section Iso_axioms.
Variables A B C : Set.
Axiom Com : A * B = B * A.
Axiom Ass : A * (B * C) = (A * B) * C.
Axiom Cur : ((A * B) -> C) = (A -> (B * C)).
Axiom Dis : (A -> (B * C)) = (A -> B) * (A -> C).
Axiom P_unit : A * unit = A.
Axiom AR_unit : (A -> unit) = unit.
Axiom AL_unit : (unit -> A) = A.

End Iso_axioms.

Ltac simplifie :=
 intros;
 repeat
  (rewrite P_unit || rewrite AR_unit || rewrite AL_unit);
 try reflexivity.

Lemma ex1_1 : forall A B :Set, A * (B -> unit) = A.
Proof.
 intro.
 intro.
 rewrite AR_unit.
 rewrite P_unit.
 reflexivity.
Qed.

Lemma ex1_2 : forall A B :Set, ((A * unit) * B) = (B * (unit * A)).
Proof.
 intros.
 rewrite <- Com.
 rewrite (Com unit A).
 reflexivity.
Qed.

Lemma ex1_3 : forall A B C : Set, (A * unit -> B * (C * unit)) = (A * unit -> (C -> unit) * C) * (unit -> A -> B).
Proof.
 intros.
 rewrite P_unit.
 rewrite P_unit.
 rewrite AR_unit.
 rewrite AL_unit.
 rewrite Dis.
 rewrite (Com unit C).
 rewrite P_unit.
 rewrite Com.
 reflexivity.
Qed.

Section Peano.
Parameter N : Set.
Parameter o : N.
Parameter s : N -> N.
Parameters plus mult : N -> N -> N.
Variables x y : N.
Axiom ax1 : ~((s x) = o).
Axiom ax2 : exists z, ~(x = o) -> (s z) = x.
Axiom ax3 : (s x) = (s y) -> x = y.
Axiom ax4 : (plus x o) = x.
Axiom ax5 : (plus x (s y)) = s (plus x y).
Axiom ax6 : (mult x o) = o.
Axiom ax7 : (mult x (s y)) = (plus (mult x y) x).
End Peano.


Ltac simplifie2 :=
 intros;
 repeat
  (rewrite ax7 || rewrite ax6 || rewrite ax5 || rewrite ax4);
 try reflexivity.
Lemma ex2_1 : (plus (s o) (s (s o))) = (s (s (s o))).
Proof.
 rewrite ax5.
 rewrite ax5.
 rewrite ax4.
 reflexivity.
Qed.

Lemma ex2_2 : (plus (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 rewrite ax5.
 rewrite ax5.
 rewrite ax4.
 reflexivity.
Qed.

Lemma ex2_3 : (mult (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 rewrite ax7.
 rewrite ax7.
 rewrite ax5.
 rewrite ax5.
 rewrite ax4.
 rewrite ax5.
 rewrite ax6.
 rewrite ax5.
 rewrite ax4.
 reflexivity.
Qed.


Lemma ex2_12 : (plus (s o) (s (s o))) = (s (s (s o))).
Proof.
 simplifie2.
Qed.

Lemma ex2_22 : (plus (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 simplifie2.
Qed.

Lemma ex2_32 : (mult (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 simplifie2.
Qed.

Hint Rewrite ax7 ax6 ax5 ax4 : toto.

Lemma isos2_ex1_3 : (plus (s o) (s (s o))) = (s (s (s o))).
Proof.
 autorewrite with toto using try reflexivity.
Qed.

Lemma isos2_ex2_3 : (plus (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 autorewrite with toto using try reflexivity.
Qed.

Lemma isos2_ex3_3 : (mult (s (s o)) (s (s o))) = (s (s (s (s o)))).
Proof.
 autorewrite with toto using try reflexivity.
Qed.

TP3 : 
Require Import ListSet.

(*Exercice 1*)
Print mult.
Print Nat.mul.

Fixpoint mult (n m : nat) {struct n} : nat :=
 match n with
  | 0 => 0
  | S p => (plus (mult p m) m)
 end.

Print mult.

Lemma ex1_1 : forall n : nat, (mult 2 n) = (plus n n).
Proof.
 intro.
 simpl.
 reflexivity.
Qed.

Lemma com : forall n m : nat, (plus n m) = (plus m n).
Proof.
 intros.
 elim n.
 elim m.
 reflexivity.
 intros.
 simpl.
 rewrite <- H.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H.
 elim m.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H0.
 reflexivity.
Qed.

Lemma ex1_2 : forall n : nat, (mult n 2) = (plus n n).
Proof.
 intro.
 elim n.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H.
 rewrite com.
 simpl.
 rewrite (com n0 (S n0)).
 simpl.
 reflexivity.
Qed.

(*Exercice 2*)
Open Scope list.
Print app.

Parameters A : Type.

Fixpoint rev (l : list A) {struct l} : list A :=
 match l with
  | nil => nil
  | a :: l1 => (app (rev l1) (a :: nil))
 end.

Lemma ex2_1 : forall l : list A, forall e : A, (rev (app l (e :: nil))) = (e :: (rev l)).
Proof.
 intros.
 elim l.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H.
 simpl.
 reflexivity.
Qed.

Lemma ex2_2 : forall l : list A, (rev (rev l)) = l.
Proof.
 intros.
 elim l.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite ex2_1.
 rewrite H.
 reflexivity.
Qed.

(*Exercice 3*)
Parameters S : Set.

Inductive FProp : Set :=
 | Symb : S -> FProp
 | Not : FProp -> FProp
 | And : FProp -> FProp -> FProp
 | Or : FProp -> FProp -> FProp
 | Impl : FProp -> FProp -> FProp
 | Equ : FProp -> FProp -> FProp.

Print ListSet.

Fixpoint sub (f : FProp) {struct f} : list FProp :=
 match f with
  | (Symb s) => (Symb s) :: nil
  | (Not f1) => (Not f1) :: (sub f1)
  | (Or f1 f2) => (Or f1 f2) :: (app (sub f1) (sub f2))
  | (And f1 f2) => (And f1 f2) :: (app (sub f1) (sub f2))
  | (Impl f1 f2) => (Impl f1 f2) :: (app (sub f1) (sub f2))
  | (Equ f1 f2) => (Equ f1 f2) :: (app (sub f1) (sub f2))
 end.

Fixpoint nbc (f : FProp) {struct f} : nat :=
  match f with
  | (Symb s) => 0
  | (Not f1) => 1 + (nbc f1)
  | (Or f1 f2) => 1 + (nbc f1) + (nbc f2)
  | (And f1 f2) => 1 + (nbc f1) + (nbc f2)
  | (Impl f1 f2) => 1 + (nbc f1) + (nbc f2)
  | (Equ f1 f2) => 1 + (nbc f1) + (nbc f2)
  end.

Parameters a b c : S.

Theorem test1 : (nbc (Symb a)) = 0.
Proof.
 simpl.
 reflexivity.
Qed.

Theorem test2 : (nbc (And (Or (Symb a) (Symb b)) (Symb c))) = 2.
Proof.
 simpl.
 reflexivity.
Qed.


Require Export Omega.

Lemma lsLgtInd : forall L1 L2 : list FProp, (length (L1 ++ L2)) = (length L1) + (length L2).
Proof.
  intros.
  elim L1.
    simpl.
    reflexivity.
    intros.
    simpl.
    rewrite H.
    reflexivity.
Qed.

Ltac prove2F :=
    intros ; simpl ; rewrite lsLgtInd ; omega.

Goal forall F : FProp, (length (sub F)) <=  (2 * (nbc F)) + 1.
Proof.
  intro.
  elim F.
    intro.
    simpl.
    trivial.
    intros.
    simpl.
    omega.
    prove2F.
    prove2F.
    prove2F
    prove2F.
Qed.

TP4:
Require Import FunInd.

(*Exercice 1*)

Inductive is_fact : nat -> nat -> Prop :=
 | is_fact_0 : is_fact 0 1
 | is_fact_S : forall n f : nat, is_fact n f -> is_fact (S n) (f * (S n)).

Fixpoint fact (n : nat) : nat :=
 match n with
  | 0 => 1
  | (S p) => (fact p) * n
 end.

Functional Scheme fact_ind := Induction for fact Sort Prop.

Lemma fact_correction : forall n f : nat, (fact n) = f -> is_fact n f.
Proof.
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

Lemma fact_correction_fun : forall n f : nat, (fact n) = f -> is_fact n f.
Proof.
 intro.
 functional induction (fact n) using fact_ind.
 intros.
 rewrite <- H.
 apply is_fact_0.
 intros.
 rewrite <- H.
 apply is_fact_S.
 apply IHn0.
 reflexivity.
Qed.
 
Lemma fact_completude : forall n f : nat, is_fact n f -> (fact n) = f.
Proof.
 intros.
 elim H.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite <- H1.
 reflexivity.
Qed.

(*Exercice 2*)

Inductive is_even : nat -> Prop :=
 | is_even_0 : is_even 0
 | is_even_S : forall n : nat, is_even n -> is_even (S (S n)).

Fixpoint even(n : nat) : Prop :=
 match n with
  | 0 => True
  | 1 => False
  | S (S p) => even p
 end.

Functional Scheme even_ind := Induction for even Sort Prop.

Lemma even_correction_0 : forall n : nat, (even n) = True -> is_even n.
Proof.
 intro.
 functional induction (even n) using even_ind.
 intros.
 apply is_even_0.
 intros.
 elimtype False.
 rewrite H.
 auto.
 intros.
 apply is_even_S.
 apply IHP.
 rewrite H.
 reflexivity.
Qed.


Is sum :
Require Import FunInd.

Inductive is_sum : nat -> nat -> Prop :=
 | is_sum_0 : is_sum 0 0
 | is_sum_S : forall n s : nat, is_sum n s -> is_sum (S n) (s + (S n)).

Fixpoint sum (n : nat) : nat :=
 match n with
  | 0 => 0
  | (S p) => (sum p) + (S p)
 end.


(*Génération du schéma d'induction fonctionnelle*)
Functional Scheme sum_ind := Induction for sum Sort Prop.

Lemma sum_correction : forall (n s : nat), (sum n) = s -> is_sum n s.
Proof.
 induction n.
 intros.
 rewrite <- H.
 simpl.
 apply is_sum_0.
 intros.
 rewrite <- H.
 simpl.
 apply is_sum_S.
 apply IHn.
 reflexivity.
Qed.
 
Lemma sum_completude : forall (n s : nat), is_sum n s -> (sum n) = s.
Proof.
 intros.
 elim H.
 intros.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H1.
 reflexivity.
Qed.

Lemma sum_completude2 : forall (n s : nat), is_sum n s -> (sum n) = s.
Proof.
 induction n.
 intros.
 simpl.
 inversion H.
 reflexivity.
 intros.
 simpl.
 inversion H.
 rewrite (IHn s0 H1).
 reflexivity.
Qed.



is power :
Require Import FunInd.

Inductive is_power : nat -> nat -> nat -> Prop :=
 | is_power_0 : forall n : nat, (is_power n 0 1)
 | is_power_S : forall n f t : nat, (is_power n f t) -> (is_power n (S f) (t * n)).
 
Fixpoint power (n f : nat) : nat :=
 match f with
  | 0 => 1
  | (S p) => (power n p) * n
 end.

(*Ecrire le schéma d'induction fonctionnelle associé à cette fonction*)
Functional Scheme power_ind := Induction for power Sort Prop.

Lemma powercorrec : forall n f t : nat, (power n f) = t -> (is_power n f t).
Proof.
 intro.
 intro.
 functional induction (power n f) using power_ind.
 intros.
 rewrite <- H.
 apply is_power_0.
 intros.
 rewrite <- H.
 apply is_power_S.
 apply IHn0.
 reflexivity.
Qed.


Fourre tout :
Fixpoint mon_exp (n m : nat) : nat := (* met en exposant*)
  match m with
    | 0 => 1
    | S m' => n * mon_exp n m'
  end.
Eval compute in (mon_exp 2 5).

Inductive formula : Type :=
  MonTrue : formula
| MonFalse : formula
| MonEt : formula -> formula -> formula
| MonOu : formula -> formula -> formula
| MonNon : formula -> formula.

Fixpoint eval (F : formula) : bool :=
  match F with
    | MonTrue => true
    | MonFalse => false
    | MonEt F1 F2 => if eval F1 then eval F2 else false
    | MonOu F1 F2 => if eval F1 then true else eval F2
    | MonNon F' => negb (eval F')
  end.
Eval compute in (eval (MonOu (MonEt MonTrue MonFalse) (MonTrue))).

(*------------------------------------------------------------------------------------*)

Fixpoint plus (n m : nat) : nat := (*fonction plus*)
  match n with
    | O => m
    | S n' => S (plus n' m)
  end.

Lemma plus0 (n : nat) : plus n O = n.
Proof.
 induction n.
 reflexivity.
 simpl.
 rewrite IHn.
 reflexivity.
Qed.

Lemma plusS (n p : nat) : plus n (S p) = S (plus n p).
Proof.
 induction n.
 simpl.
 reflexivity.
 simpl.
 rewrite IHn.
 reflexivity.
Qed.

Lemma com : forall n m : nat, (plus n m) = (plus m n).
Proof.
 intros.
 elim n.
 elim m.
 reflexivity.
 intros.
 simpl.
 rewrite <- H.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H.
 elim m.
 simpl.
 reflexivity.
 intros.
 simpl.
 rewrite H0.
 reflexivity.
Qed.

Theorem symetrie (n m : nat) : plus n m = plus m n.
Proof.
 induction n.
 rewrite com.
 reflexivity.
 rewrite com.
 reflexivity.
Qed.

Theorem associativite (n m p : nat) : plus n (plus m p) = plus (plus n m) p.
Proof.
 induction n.
 simpl.
 reflexivity.
 simpl.
 rewrite IHn.
 reflexivity.
Qed.

(*------------------------------------------------------------------------------------*)

Fixpoint double (n:nat) := (*fonction qui double son argument*)
  match n with
  | O => O
  | S n' => S (S (double n'))
  end.


TP1 ex 2 :
(*Exercice 2*)

Parameter E : Set .
Parameters Q P :E-> Prop.

Lemma ex2_1 : (forall x : E, P(x) -> (exists y : E, P(y) \/ Q(y))).
Proof.
 intros.
 exists x.
 left.
 assumption.
Qed.

Lemma ex2_2 : (exists x : E, P(x) \/ Q(x)) -> (exists x : E, P(x)) \/ (exists x : E, Q(x)).
 intros.
 elim H.
 intros.
 elim H0.
 left.
 exists x.
 assumption.
 right.
 exists x.
 assumption.
Qed.

Lemma ex2_3 : (forall x : E, P(x)) /\ (forall x : E, Q(x)) -> (forall x : E, P(x) /\ Q(x)).
Proof.
 intros.
 elim H.
 intros.
 split.
 apply H0.
 apply H1.
Qed.

Lemma ex2_4 : (forall x : E, P(x) /\ Q(x)) -> (forall x : E, P(x)) /\ (forall x : E, Q(x)).
Proof.
 intros.
 split.
 intro.
 apply H.
 apply H.
Qed.

Lemma ex2_5 : (forall x : E, ~P(x)) -> ~(exists x : E, P(x)).
Proof.
  intro.
  intro.
  elim H0.
  apply H.
Qed.

Require Import Classical.

Lemma ex2_6 : ~(forall x : E, P(x)) -> (exists x : E, ~P(x)).
Proof.
 intro.
 apply NNPP.
 intro.
 elimtype False.
 apply H.
 intro.
 apply NNPP.
 intro.
 elimtype False.
 apply H0.
 exists x.
 assumption.
Qed.

