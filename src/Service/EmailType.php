<?php

namespace App\Service;

class EmailType
{
    private array $value;

    public const PASSWORD_CHANGED = ['twigFile' => 'passwordChanged', 'subject' => 'Villette - Mot de passe changé !'];
    public const PASSWORD_LOST = ['twigFile' => 'passwordLost', 'subject' => 'Villette - Récupération de mot de passe'];
    public const REGISTER = ['twigFile' => 'register', 'subject' => 'Villette - Bienvenue au CTT La villette-Charleroi !'];
    public const ACCOUNT_CONFIRMATION = ['twigFile' => 'confirmation', 'subject' => 'Villette - Votre compte a été activé !'];

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
