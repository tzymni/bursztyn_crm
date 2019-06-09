<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = $allowSchemes = array();
        if ($ret = $this->doMatch($pathinfo, $allow, $allowSchemes)) {
            return $ret;
        }
        if ($allow) {
            throw new MethodNotAllowedException(array_keys($allow));
        }
        if (!in_array($this->context->getMethod(), array('HEAD', 'GET'), true)) {
            // no-op
        } elseif ($allowSchemes) {
            redirect_scheme:
            $scheme = $this->context->getScheme();
            $this->context->setScheme(key($allowSchemes));
            try {
                if ($ret = $this->doMatch($pathinfo)) {
                    return $this->redirect($pathinfo, $ret['_route'], $this->context->getScheme()) + $ret;
                }
            } finally {
                $this->context->setScheme($scheme);
            }
        } elseif ('/' !== $pathinfo) {
            $pathinfo = '/' !== $pathinfo[-1] ? $pathinfo.'/' : substr($pathinfo, 0, -1);
            if ($ret = $this->doMatch($pathinfo, $allow, $allowSchemes)) {
                return $this->redirect($pathinfo, $ret['_route']) + $ret;
            }
            if ($allowSchemes) {
                goto redirect_scheme;
            }
        }

        throw new ResourceNotFoundException();
    }

    private function doMatch(string $rawPathinfo, array &$allow = array(), array &$allowSchemes = array()): ?array
    {
        $allow = $allowSchemes = array();
        $pathinfo = rawurldecode($rawPathinfo);
        $context = $this->context;
        $requestMethod = $canonicalMethod = $context->getMethod();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }

        switch ($pathinfo) {
            default:
                $routes = array(
                    '/api/authenticate' => array(array('_route' => 'app_auth_issuejwttoken', '_controller' => 'App\\Controller\\AuthController::issueJWTToken'), null, array('POST' => 0), null),
                    '/cottage/new' => array(array('_route' => 'app_cottages_addcottage', '_controller' => 'App\\Controller\\CottagesController::addCottage'), null, array('POST' => 0), null),
                    '/api/cottages' => array(array('_route' => 'app_cottages_show', '_controller' => 'App\\Controller\\CottagesController::show'), null, array('GET' => 0), null),
                    '/api/leagues' => array(array('_route' => 'app_footballleague_createleague', '_controller' => 'App\\Controller\\FootballLeagueController::createLeague'), null, array('POST' => 0), null),
                    '/api/teams' => array(array('_route' => 'app_footballteam_createteam', '_controller' => 'App\\Controller\\FootballTeamController::createTeam'), null, array('POST' => 0), null),
                    '/users/create' => array(array('_route' => 'app_user_createuser', '_controller' => 'App\\Controller\\UserController::createUser'), null, array('POST' => 0), null),
                    '/api/users' => array(array('_route' => 'app_user_getuserlist', '_controller' => 'App\\Controller\\UserController::getUserList'), null, array('GET' => 0), null),
                    '/_profiler/' => array(array('_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'), null, null, null),
                    '/_profiler/search' => array(array('_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'), null, null, null),
                    '/_profiler/search_bar' => array(array('_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'), null, null, null),
                    '/_profiler/phpinfo' => array(array('_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'), null, null, null),
                    '/_profiler/open' => array(array('_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'), null, null, null),
                );

                if (!isset($routes[$pathinfo])) {
                    break;
                }
                list($ret, $requiredHost, $requiredMethods, $requiredSchemes) = $routes[$pathinfo];

                $hasRequiredScheme = !$requiredSchemes || isset($requiredSchemes[$context->getScheme()]);
                if ($requiredMethods && !isset($requiredMethods[$canonicalMethod]) && !isset($requiredMethods[$requestMethod])) {
                    if ($hasRequiredScheme) {
                        $allow += $requiredMethods;
                    }
                    break;
                }
                if (!$hasRequiredScheme) {
                    $allowSchemes += $requiredSchemes;
                    break;
                }

                return $ret;
        }

        $matchedPathinfo = $pathinfo;
        $regexList = array(
            0 => '{^(?'
                    .'|/([^/]++)(?'
                        .'|/edit(*:24)'
                        .'|(*:31)'
                    .')'
                    .'|/api/(?'
                        .'|leagues/([^/]++)(?'
                            .'|/teams(*:72)'
                            .'|(*:79)'
                        .')'
                        .'|teams/([^/]++)(?'
                            .'|(*:104)'
                        .')'
                        .'|user/([^/]++)(?'
                            .'|(*:129)'
                        .')'
                    .')'
                    .'|/_(?'
                        .'|error/(\\d+)(?:\\.([^/]++))?(*:170)'
                        .'|wdt/([^/]++)(*:190)'
                        .'|profiler/([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:236)'
                                .'|router(*:250)'
                                .'|exception(?'
                                    .'|(*:270)'
                                    .'|\\.css(*:283)'
                                .')'
                            .')'
                            .'|(*:293)'
                        .')'
                    .')'
                .')$}sD',
        );

        foreach ($regexList as $offset => $regex) {
            while (preg_match($regex, $matchedPathinfo, $matches)) {
                switch ($m = (int) $matches['MARK']) {
                    case 104:
                        $matches = array('id' => $matches[1] ?? null);

                        // app_footballteam_updateteam
                        $ret = $this->mergeDefaults(array('_route' => 'app_footballteam_updateteam') + $matches, array('_controller' => 'App\\Controller\\FootballTeamController::updateTeam'));
                        if (!isset(($a = array('PUT' => 0))[$requestMethod])) {
                            $allow += $a;
                            goto not_app_footballteam_updateteam;
                        }

                        return $ret;
                        not_app_footballteam_updateteam:

                        // app_footballteam_deleteteam
                        $ret = $this->mergeDefaults(array('_route' => 'app_footballteam_deleteteam') + $matches, array('_controller' => 'App\\Controller\\FootballTeamController::deleteTeam'));
                        if (!isset(($a = array('DELETE' => 0))[$requestMethod])) {
                            $allow += $a;
                            goto not_app_footballteam_deleteteam;
                        }

                        return $ret;
                        not_app_footballteam_deleteteam:

                        break;
                    case 129:
                        $matches = array('email' => $matches[1] ?? null);

                        // app_user_getuserbymail
                        $ret = $this->mergeDefaults(array('_route' => 'app_user_getuserbymail') + $matches, array('_controller' => 'App\\Controller\\UserController::getUserByMail'));
                        if (!isset(($a = array('GET' => 0))[$canonicalMethod])) {
                            $allow += $a;
                            goto not_app_user_getuserbymail;
                        }

                        return $ret;
                        not_app_user_getuserbymail:

                        // app_user_deleteuser
                        $ret = $this->mergeDefaults(array('_route' => 'app_user_deleteuser') + $matches, array('_controller' => 'App\\Controller\\UserController::deleteUser'));
                        if (!isset(($a = array('DELETE' => 0))[$requestMethod])) {
                            $allow += $a;
                            goto not_app_user_deleteuser;
                        }

                        return $ret;
                        not_app_user_deleteuser:

                        // app_user_updateuser
                        $ret = $this->mergeDefaults(array('_route' => 'app_user_updateuser') + $matches, array('_controller' => 'App\\Controller\\UserController::updateUser'));
                        if (!isset(($a = array('PUT' => 0))[$requestMethod])) {
                            $allow += $a;
                            goto not_app_user_updateuser;
                        }

                        return $ret;
                        not_app_user_updateuser:

                        break;
                    default:
                        $routes = array(
                            24 => array(array('_route' => 'cottages_edit', '_controller' => 'App\\Controller\\CottagesController::edit'), array('id'), array('GET' => 0, 'POST' => 1), null),
                            31 => array(array('_route' => 'cottages_delete', '_controller' => 'App\\Controller\\CottagesController::delete'), array('id'), array('DELETE' => 0), null),
                            72 => array(array('_route' => 'app_footballleague_getleagueteams', '_controller' => 'App\\Controller\\FootballLeagueController::getLeagueTeams'), array('id'), array('GET' => 0), null),
                            79 => array(array('_route' => 'app_footballleague_deleteleague', '_controller' => 'App\\Controller\\FootballLeagueController::deleteLeague'), array('id'), array('DELETE' => 0), null),
                            170 => array(array('_route' => '_twig_error_test', '_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'), array('code', '_format'), null, null),
                            190 => array(array('_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'), array('token'), null, null),
                            236 => array(array('_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'), array('token'), null, null),
                            250 => array(array('_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'), array('token'), null, null),
                            270 => array(array('_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception::showAction'), array('token'), null, null),
                            283 => array(array('_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception::cssAction'), array('token'), null, null),
                            293 => array(array('_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'), array('token'), null, null),
                        );

                        list($ret, $vars, $requiredMethods, $requiredSchemes) = $routes[$m];

                        foreach ($vars as $i => $v) {
                            if (isset($matches[1 + $i])) {
                                $ret[$v] = $matches[1 + $i];
                            }
                        }

                        $hasRequiredScheme = !$requiredSchemes || isset($requiredSchemes[$context->getScheme()]);
                        if ($requiredMethods && !isset($requiredMethods[$canonicalMethod]) && !isset($requiredMethods[$requestMethod])) {
                            if ($hasRequiredScheme) {
                                $allow += $requiredMethods;
                            }
                            break;
                        }
                        if (!$hasRequiredScheme) {
                            $allowSchemes += $requiredSchemes;
                            break;
                        }

                        return $ret;
                }

                if (293 === $m) {
                    break;
                }
                $regex = substr_replace($regex, 'F', $m - $offset, 1 + strlen($m));
                $offset += strlen($m);
            }
        }
        if ('/' === $pathinfo && !$allow) {
            throw new Symfony\Component\Routing\Exception\NoConfigurationException();
        }

        return null;
    }
}
