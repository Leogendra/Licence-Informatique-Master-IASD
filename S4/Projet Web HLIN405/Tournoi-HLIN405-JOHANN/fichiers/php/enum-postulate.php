<?php 

/**
 * Enumération des différents postulats possibles.
 */
abstract class Postulate {
    const Pending = 'pending';
    const Refused = 'refused';
    const Accepted = 'accepted';
    const None = '';
}