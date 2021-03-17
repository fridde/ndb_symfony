<?php


namespace App\Security;


use App\Entity\School;
use App\Entity\User;
use App\Entity\Visit;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Fridde\Timing;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationUtils
{
    private EntityManagerInterface $em;

    public AdapterInterface $cache;

    private SessionInterface $session;

    private string $app_secret;

    public const COOKIE_KEY_NAME = 'AuthKey';

    private const COOKIE_KEY_LENGTH = 32;
    private const URL_CODE_LENGTH = 10;
    private const VISIT_CONFIRMATION_CODE_LENGTH = 5;

    public const OWNER_SEPARATOR = '-';

    public function __construct(
        EntityManagerInterface $em,
        AdapterInterface $cache,
        SessionInterface $session,
        $app_secret
    )
    {
        $this->em = $em;
        $this->cache = $cache;
        $this->app_secret = $app_secret;
        $this->session = $session;
    }

    public function getUserIdFromSession(): ?int
    {
        return $this->session->get('user_id');
    }

    public static function createHashFromString(string $string, int $length = -1): string
    {
        $hash = hash('sha256', $string);
        if ($length >= 0) {
            $hash = substr($hash, 0, $length);
        }

        return $hash;
    }

    public function createCookieKeyForUser(UserInterface $user): string
    {
        $id = [$user->getId(), $this->getHalfAYearAgo(), $this->app_secret];
        $id = implode('.', $id);

        $key = $user->getId() . self::OWNER_SEPARATOR;
        $key .= self::createHashFromString($id, self::COOKIE_KEY_LENGTH);

        return $key;
    }


    public function getUserFromAuthKey(string $code = null): ?User
    {
        if (empty($code)) {
            return null;
        }
        $id = explode(self::OWNER_SEPARATOR, $code)[0];

        return $this->em->find(User::class, $id);
    }


    public function authKeyMatchesUser(UserInterface $user, string $cookie_key = null): bool
    {
        return $this->createCookieKeyForUser($user) === $cookie_key;
    }

    public function createUserConfirmationCode(User $user, School $school): string
    {
        $id = ['u', $user->getId(), 's', $school->getId(), $this->getHalfAYearAgo(), $this->app_secret];
        $id = implode('.', $id);

        $code = $user->getId() . self::OWNER_SEPARATOR;
        $code .= $school->getId() . self::OWNER_SEPARATOR;
        $code .= self::createHashFromString($id, self::URL_CODE_LENGTH);

        return $code;
    }

    public function codeMatchesUserAndSchool(string $code, User $user, School $school): bool
    {
        return $code === $this->createUserConfirmationCode($user, $school);
    }


    public function createVisitConfirmationCode(Visit $visit): string
    {
        $id = ['v', $visit->getId(), $this->app_secret];
        $id = implode('.', $id);

        $code = $visit->getId() . self::OWNER_SEPARATOR;
        $code .= self::createHashFromString($id, self::VISIT_CONFIRMATION_CODE_LENGTH);

        return $code;
    }

    public function getVisitFromConfirmationCode(string $code): ?Visit
    {
        $id = explode(self::OWNER_SEPARATOR, $code)[0];

        return $this->em->find(Visit::class, $id);
    }

    public function codeMatchesVisit(Visit $visit, string $code): bool
    {
        return $code === $this->createVisitConfirmationCode($visit);
    }


    public function createCookieWithAuthKey(UserInterface $user, Carbon $exp_date = null): Cookie
    {
        return Cookie::create(self::COOKIE_KEY_NAME)
            ->withValue($this->createCookieKeyForUser($user))
            ->withExpires($exp_date ?? Timing::addDurationToNow([90, 'd']))
            ->withHttpOnly(false)
            ->withSecure(true);
    }

    public function removeCookieKeyFromBrowser(): void
    {
        $key = self::COOKIE_KEY_NAME;
        setcookie($key, null, -1, '/');
    }

    public function setUserInSession(User $user): void
    {
        $this->session->set('user_id', $user->getId());
    }

    public function emptySession(): void
    {
        session_unset();
    }

    /*
     * This hack helps invalidating all codes during
     * the summer weeks and not at the end of the year
    */
    public function getHalfAYearAgo(): int
    {
        return Carbon::today()->subDays(180)->year;
    }

}