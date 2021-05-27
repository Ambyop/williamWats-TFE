<?php

namespace App\Service;

class EmailType
{
    private array $value;

    public const PASSWORD_CHANGED = ['twigFile' => 'passwordChanged', 'subject' => 'Villette - Mot de passe changé !'];
    public const REGISTER = ['twigFile' => 'register', 'subject' => 'Villette - Bienvenue au CTT La villette-Charleroi !'];

    /**
     * EmailType constructor.
     * @param array $value
     */
    final public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return String
     */
    final public function __toString(): string
    {
        return $this->value['twigFile'];
    }

    /**
     * @return String
     */
    public function getTwigFilePath() : String
    {
        return 'email/' . $this->value['twigFile'] . '.html.twig';
    }

    /**
     * @return String
     */
    public function getSubject() : String
    {
        return $this->value['subject'];
    }
}
