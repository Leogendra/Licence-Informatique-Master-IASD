<?php 

namespace PH\Templates;

final class BootstrapForm extends \Core\Renderer\FormRenderer {

    // --------------------------------------------------------------------------------------------
    // Méthodes protégées.

    /**
     * {@inheritdoc}
     */
    final protected function makeValid(\DOMNode &$node, \DOMDocument $doc, array $messages, string $value) : void {
        $node->setAttribute('class', $node->getAttribute('class') . ' is-valid');
        $this->setNodeValue($node, $value);
    }

    /**
     * {@inheritdoc}
     */
    final protected function makeInvalid(\DOMNode &$node, \DOMDocument $doc, array $messages, string $value) : void {
        $node->setAttribute('class', $node->getAttribute('class') . ' is-invalid');
        $this->setNodeValue($node, $value);

        $invalid_feedback = $doc->createElement('div');
        $invalid_feedback->setAttribute('class', 'invalid-feedback');
        foreach ($messages as $message) {
            $invalid_feedback->nodeValue .= $message . PHP_EOL;
        }

        $node->parentNode->appendChild($invalid_feedback);
    }

    /**
     * {@inheritdoc}
     */
    final protected function makeErrors(\DOMDocument $doc, array $messages) : void {
        if (true === empty($messages)) {
            return;
        }

        $error_div = $doc->createElement('div');
        foreach ($messages as $message) {
            $div = $doc->createElement('div', 'Erreur : ' . htmlspecialchars($message));
            $div->setAttribute('class', 'alert alert-danger');
            $div->setAttribute('role', 'alert');
            $error_div->appendChild($div);
        }

        $doc->insertBefore($error_div, $doc->firstChild);
    }
}