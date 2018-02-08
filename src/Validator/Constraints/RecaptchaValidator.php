<?php

namespace Sergiors\Silex\Validator\Constraints;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
final class RecaptchaValidator extends ConstraintValidator
{
    /**
     * @var ReCaptcha
     */
    private $recaptcha;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param string       $secretkey
     * @param RequestStack $requestStack
     */
    public function __construct($secretkey, RequestStack $requestStack)
    {
        $this->recaptcha = new \ReCaptcha\ReCaptcha($secretkey);
        $this->requestStack = $requestStack;
    }

    /**
     * @param string     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();
        $remoteIp = $request->getClientIp();
        $recaptchaToken = $request->get('g-recaptcha-response');

        if ($this->recaptcha->verify($recaptchaToken, $remoteIp)->isSuccess()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setInvalidValue($value)
            ->addViolation();
    }
}
