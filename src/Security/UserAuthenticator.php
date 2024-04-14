<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
   
    private $urlGenerator;
    private $passwordHasher;
    private $userPasswordEncoderInterface;
    private $entityManager;
    private $passwordEncoder;
    public function __construct( UserPasswordHasherInterface $passwordEncoder,EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager)
    {
        
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * Should this authenticator be used for this request?
     *
     * @param Request $request
     * @return bool|void
     */
    public function supports(Request $request): ?bool
    {
        //return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
        return $request->getPathInfo()==='/login' && $request->isMethod('POST');
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
{
    // Handle authentication failure here
    // For example, you can redirect the user back to the login page with an error message
    $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

    return new RedirectResponse($this->getLoginUrl($request));
}

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
    
        $request->getSession()->set(Security::LAST_USERNAME, $email);
    
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
 // Redirect to the profile page
 return new RedirectResponse($this->urlGenerator->generate('app_user_profil'));
        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_user_profil');
    }
}