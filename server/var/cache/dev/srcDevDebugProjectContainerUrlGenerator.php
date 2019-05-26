<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcDevDebugProjectContainerUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes;
    private $defaultLocale;

    public function __construct(RequestContext $context, LoggerInterface $logger = null, string $defaultLocale = null)
    {
        $this->context = $context;
        $this->logger = $logger;
        $this->defaultLocale = $defaultLocale;
        if (null === self::$declaredRoutes) {
            self::$declaredRoutes = array(
        'app_auth_issuejwttoken' => array(array(), array('_controller' => 'App\\Controller\\AuthController::issueJWTToken'), array(), array(array('text', '/api/authenticate')), array(), array()),
        'cottages_index' => array(array(), array('_controller' => 'App\\Controller\\CottagesController::index'), array(), array(array('text', '/cottages/')), array(), array()),
        'cottages_new' => array(array(), array('_controller' => 'App\\Controller\\CottagesController::add'), array(), array(array('text', '/cottages/new')), array(), array()),
        'app_cottages_show' => array(array(), array('_controller' => 'App\\Controller\\CottagesController::show'), array(), array(array('text', '/cottages/api/cottages')), array(), array()),
        'cottages_edit' => array(array('id'), array('_controller' => 'App\\Controller\\CottagesController::edit'), array(), array(array('text', '/edit'), array('variable', '/', '[^/]++', 'id'), array('text', '/cottages')), array(), array()),
        'cottages_delete' => array(array('id'), array('_controller' => 'App\\Controller\\CottagesController::delete'), array(), array(array('variable', '/', '[^/]++', 'id'), array('text', '/cottages')), array(), array()),
        'app_footballleague_createleague' => array(array(), array('_controller' => 'App\\Controller\\FootballLeagueController::createLeague'), array(), array(array('text', '/api/leagues')), array(), array()),
        'app_footballleague_getleagueteams' => array(array('id'), array('_controller' => 'App\\Controller\\FootballLeagueController::getLeagueTeams'), array(), array(array('text', '/teams'), array('variable', '/', '[^/]++', 'id'), array('text', '/api/leagues')), array(), array()),
        'app_footballleague_deleteleague' => array(array('id'), array('_controller' => 'App\\Controller\\FootballLeagueController::deleteLeague'), array(), array(array('variable', '/', '[^/]++', 'id'), array('text', '/api/leagues')), array(), array()),
        'app_footballteam_createteam' => array(array(), array('_controller' => 'App\\Controller\\FootballTeamController::createTeam'), array(), array(array('text', '/api/teams')), array(), array()),
        'app_footballteam_updateteam' => array(array('id'), array('_controller' => 'App\\Controller\\FootballTeamController::updateTeam'), array(), array(array('variable', '/', '[^/]++', 'id'), array('text', '/api/teams')), array(), array()),
        'app_footballteam_deleteteam' => array(array('id'), array('_controller' => 'App\\Controller\\FootballTeamController::deleteTeam'), array(), array(array('variable', '/', '[^/]++', 'id'), array('text', '/api/teams')), array(), array()),
        'app_user_createuser' => array(array(), array('_controller' => 'App\\Controller\\UserController::createUser'), array(), array(array('text', '/users/create')), array(), array()),
        'app_user_getuserlist' => array(array(), array('_controller' => 'App\\Controller\\UserController::getUserList'), array(), array(array('text', '/api/users')), array(), array()),
        'app_user_getuserbymail' => array(array('email'), array('_controller' => 'App\\Controller\\UserController::getUserByMail'), array(), array(array('variable', '/', '[^/]++', 'email'), array('text', '/api/user')), array(), array()),
        'app_user_deleteuser' => array(array('email'), array('_controller' => 'App\\Controller\\UserController::deleteUser'), array(), array(array('variable', '/', '[^/]++', 'email'), array('text', '/api/user')), array(), array()),
        'app_user_updateuser' => array(array('email'), array('_controller' => 'App\\Controller\\UserController::updateUser'), array(), array(array('variable', '/', '[^/]++', 'email'), array('text', '/api/user')), array(), array()),
        '_twig_error_test' => array(array('code', '_format'), array('_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'), array('code' => '\\d+'), array(array('variable', '.', '[^/]++', '_format'), array('variable', '/', '\\d+', 'code'), array('text', '/_error')), array(), array()),
        '_wdt' => array(array('token'), array('_controller' => 'web_profiler.controller.profiler::toolbarAction'), array(), array(array('variable', '/', '[^/]++', 'token'), array('text', '/_wdt')), array(), array()),
        '_profiler_home' => array(array(), array('_controller' => 'web_profiler.controller.profiler::homeAction'), array(), array(array('text', '/_profiler/')), array(), array()),
        '_profiler_search' => array(array(), array('_controller' => 'web_profiler.controller.profiler::searchAction'), array(), array(array('text', '/_profiler/search')), array(), array()),
        '_profiler_search_bar' => array(array(), array('_controller' => 'web_profiler.controller.profiler::searchBarAction'), array(), array(array('text', '/_profiler/search_bar')), array(), array()),
        '_profiler_phpinfo' => array(array(), array('_controller' => 'web_profiler.controller.profiler::phpinfoAction'), array(), array(array('text', '/_profiler/phpinfo')), array(), array()),
        '_profiler_search_results' => array(array('token'), array('_controller' => 'web_profiler.controller.profiler::searchResultsAction'), array(), array(array('text', '/search/results'), array('variable', '/', '[^/]++', 'token'), array('text', '/_profiler')), array(), array()),
        '_profiler_open_file' => array(array(), array('_controller' => 'web_profiler.controller.profiler::openAction'), array(), array(array('text', '/_profiler/open')), array(), array()),
        '_profiler' => array(array('token'), array('_controller' => 'web_profiler.controller.profiler::panelAction'), array(), array(array('variable', '/', '[^/]++', 'token'), array('text', '/_profiler')), array(), array()),
        '_profiler_router' => array(array('token'), array('_controller' => 'web_profiler.controller.router::panelAction'), array(), array(array('text', '/router'), array('variable', '/', '[^/]++', 'token'), array('text', '/_profiler')), array(), array()),
        '_profiler_exception' => array(array('token'), array('_controller' => 'web_profiler.controller.exception::showAction'), array(), array(array('text', '/exception'), array('variable', '/', '[^/]++', 'token'), array('text', '/_profiler')), array(), array()),
        '_profiler_exception_css' => array(array('token'), array('_controller' => 'web_profiler.controller.exception::cssAction'), array(), array(array('text', '/exception.css'), array('variable', '/', '[^/]++', 'token'), array('text', '/_profiler')), array(), array()),
    );
        }
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $locale = $parameters['_locale']
            ?? $this->context->getParameter('_locale')
            ?: $this->defaultLocale;

        if (null !== $locale && (self::$declaredRoutes[$name.'.'.$locale][1]['_canonical_route'] ?? null) === $name) {
            unset($parameters['_locale']);
            $name .= '.'.$locale;
        } elseif (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens, $requiredSchemes) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
    }
}
