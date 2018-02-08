<?php

namespace Sergiors\Silex\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class Recaptcha extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value should not be blank.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'validator.recaptcha';
    }
}
