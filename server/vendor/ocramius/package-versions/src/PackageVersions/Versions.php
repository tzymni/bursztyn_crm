<?php

declare(strict_types=1);

namespace PackageVersions;

/**
 * This class is generated by ocramius/package-versions, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 */
final class Versions
{
    public const ROOT_PACKAGE_NAME = '__root__';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    public const VERSIONS          = array (
  'doctrine/annotations' => 'v1.8.0@904dca4eb10715b92569fbcd79e201d5c349b6bc',
  'doctrine/cache' => '1.10.0@382e7f4db9a12dc6c19431743a2b096041bcdd62',
  'doctrine/collections' => '1.6.4@6b1e4b2b66f6d6e49983cebfe23a21b7ccc5b0d7',
  'doctrine/common' => '2.12.0@2053eafdf60c2172ee1373d1b9289ba1db7f1fc6',
  'doctrine/dbal' => 'v2.10.1@c2b8e6e82732a64ecde1cddf9e1e06cb8556e3d8',
  'doctrine/doctrine-bundle' => '2.0.7@6926771140ee87a823c3b2c72602de9dda4490d3',
  'doctrine/doctrine-migrations-bundle' => '2.1.2@856437e8de96a70233e1f0cc2352fc8dd15a899d',
  'doctrine/event-manager' => '1.1.0@629572819973f13486371cb611386eb17851e85c',
  'doctrine/inflector' => '1.3.1@ec3a55242203ffa6a4b27c58176da97ff0a7aec1',
  'doctrine/instantiator' => '1.3.0@ae466f726242e637cebdd526a7d991b9433bacf1',
  'doctrine/lexer' => '1.2.0@5242d66dbeb21a30dd8a3e66bf7a73b66e05e1f6',
  'doctrine/migrations' => '2.2.1@a3987131febeb0e9acb3c47ab0df0af004588934',
  'doctrine/orm' => 'v2.7.2@dafe298ce5d0b995ebe1746670704c0a35868a6a',
  'doctrine/persistence' => '1.3.7@0af483f91bada1c9ded6c2cfd26ab7d5ab2094e0',
  'doctrine/reflection' => '1.2.1@55e71912dfcd824b2fdd16f2d9afe15684cfce79',
  'egulias/email-validator' => '2.1.17@ade6887fd9bd74177769645ab5c474824f8a418a',
  'firebase/php-jwt' => 'v5.2.0@feb0e820b8436873675fd3aca04f3728eb2185cb',
  'jdorn/sql-formatter' => 'v1.2.17@64990d96e0959dff8e059dfcdc1af130728d92bc',
  'monolog/monolog' => '1.25.3@fa82921994db851a8becaf3787a9e73c5976b6f1',
  'nelmio/cors-bundle' => '1.5.6@10a24c10f242440211ed31075e74f81661c690d9',
  'ocramius/package-versions' => '1.5.1@1d32342b8c1eb27353c8887c366147b4c2da673c',
  'ocramius/proxy-manager' => '2.2.3@4d154742e31c35137d5374c998e8f86b54db2e2f',
  'phpdocumentor/reflection-common' => '2.0.0@63a995caa1ca9e5590304cd845c15ad6d482a62a',
  'phpdocumentor/reflection-docblock' => '4.3.4@da3fd972d6bafd628114f7e7e036f45944b62e9c',
  'phpdocumentor/type-resolver' => '1.1.0@7462d5f123dfc080dfdf26897032a6513644fc95',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/link' => '1.0.0@eea8e8662d5cd3ae4517c9b864493f59fca95562',
  'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc',
  'sensio/framework-extra-bundle' => 'v5.5.3@98f0807137b13d0acfdf3c255a731516e97015de',
  'swiftmailer/swiftmailer' => 'v6.2.3@149cfdf118b169f7840bbe3ef0d4bc795d1780c9',
  'symfony/asset' => 'v4.4.6@eb7c4595302888ee82b78acb90cc79cf4c3f213e',
  'symfony/cache' => 'v4.4.6@f20dcf48ecee66ab5ad7ccf4b3b55392b660c3d9',
  'symfony/cache-contracts' => 'v2.0.1@23ed8bfc1a4115feca942cb5f1aacdf3dcdf3c16',
  'symfony/config' => 'v4.4.6@235e5afffd3a1a1b0dd0221973cbf670bc3be1d4',
  'symfony/console' => 'v4.4.6@20bc0c1068565103075359f5ce9e0639b36f92d1',
  'symfony/debug' => 'v4.4.6@f0ae2b4150254b8b4ac578f33d910b9c116618f0',
  'symfony/dependency-injection' => 'v4.4.6@b4242fc7f18c8bf5427f84d5afe2131c9b323a04',
  'symfony/doctrine-bridge' => 'v4.4.6@57a825089b7a9851fe552b08ed83f7625352c9ab',
  'symfony/error-handler' => 'v4.4.6@3727fe33f578a547e0acecd4034401c99c8ce013',
  'symfony/event-dispatcher' => 'v4.4.6@cf57788d1ca64ee7e689698dc0295d25c9fe3780',
  'symfony/event-dispatcher-contracts' => 'v1.1.7@c43ab685673fb6c8d84220c77897b1d6cdbe1d18',
  'symfony/expression-language' => 'v4.4.6@208ceff59b98b8b38bd4426df49be697a8582240',
  'symfony/filesystem' => 'v4.4.6@6d4fdf28187250f671c1edc9cf921ebfb7fe3809',
  'symfony/finder' => 'v4.4.6@ea69c129aed9fdeca781d4b77eb20b62cf5d5357',
  'symfony/flex' => 'v1.6.2@e4f5a2653ca503782a31486198bd1dd1c9a47f83',
  'symfony/form' => 'v4.4.6@3a287b3b5ecd1a534af62b8b530f181e636e72c9',
  'symfony/framework-bundle' => 'v4.4.6@f0ef822516463bef83625e7d33f8e047093f310e',
  'symfony/http-foundation' => 'v4.4.6@0a3b7711229f816a06fac805f4ed4a8f4641c719',
  'symfony/http-kernel' => 'v4.4.6@02ee1d0d616b031fb48a1c9c3e5dc092dd7e448d',
  'symfony/inflector' => 'v4.4.6@f419ab2853cc00471ffd7fc18e544b5f5a90adb1',
  'symfony/intl' => 'v4.4.6@345aa50278b9d02a9cc75a5f19596c21646aa8d8',
  'symfony/lts' => 'dev-master@c1affae45b78aee036effa1759237e7fa96d4af2',
  'symfony/mime' => 'v5.0.6@e9927cabc1519d2498d02b743f2cab6e4722ad3d',
  'symfony/monolog-bridge' => 'v4.4.6@3386058348b9df26122cc42abc60201c59e8dda6',
  'symfony/monolog-bundle' => 'v3.5.0@dd80460fcfe1fa2050a7103ad818e9d0686ce6fd',
  'symfony/options-resolver' => 'v4.4.6@9a02d6662660fe7bfadad63b5f0b0718d4c8b6b0',
  'symfony/orm-pack' => 'v1.0.8@c9bcc08102061f406dc908192c0f33524a675666',
  'symfony/polyfill-ctype' => 'v1.15.0@4719fa9c18b0464d399f1a63bf624b42b6fa8d14',
  'symfony/polyfill-intl-icu' => 'v1.15.0@9c281272735eb66476e0fa7381e03fb0d4b60197',
  'symfony/polyfill-intl-idn' => 'v1.15.0@47bd6aa45beb1cd7c6a16b7d1810133b728bdfcf',
  'symfony/polyfill-mbstring' => 'v1.15.0@81ffd3a9c6d707be22e3012b827de1c9775fc5ac',
  'symfony/polyfill-php72' => 'v1.15.0@37b0976c78b94856543260ce09b460a7bc852747',
  'symfony/polyfill-php73' => 'v1.15.0@0f27e9f464ea3da33cbe7ca3bdf4eb66def9d0f7',
  'symfony/process' => 'v4.4.6@b9863d0f7b684d7c4c13e665325b5ff047de0aee',
  'symfony/property-access' => 'v4.4.6@a35574237897b511e9a30a7bd161d49ec8999661',
  'symfony/property-info' => 'v4.4.6@e6355ba81c738be31c3c3b3cd7929963f98da576',
  'symfony/routing' => 'v4.4.6@bd92312650007d29bbabf00795c591b975a0b9a6',
  'symfony/security-bundle' => 'v4.4.6@3f6e6903960e488dd20b884f13a2ad1b8dff0ac6',
  'symfony/security-core' => 'v4.4.6@77ba37225ddbcc6b34d94a885ad613210d52dd02',
  'symfony/security-csrf' => 'v4.4.6@da4664d94164e2b50ce75f2453724c6c33222505',
  'symfony/security-guard' => 'v4.4.6@bf61166227b28b642055364e6feaaec7d1199dc8',
  'symfony/security-http' => 'v4.4.6@dcf596a85d7759a1b82ab844a51a191a409ee306',
  'symfony/serializer' => 'v4.4.6@f1b7a1d95537d6e3c1e141dddef9831165e1b822',
  'symfony/serializer-pack' => 'v1.0.2@c5f18ba4ff989a42d7d140b7f85406e77cd8c4b2',
  'symfony/service-contracts' => 'v2.0.1@144c5e51266b281231e947b51223ba14acf1a749',
  'symfony/stopwatch' => 'v4.4.6@5f03e4ceeab7473c15d95c7a3c2eddc870bd0637',
  'symfony/swiftmailer-bundle' => 'v3.4.0@553d2474288349faed873da8ab7c1551a00d26ae',
  'symfony/translation-contracts' => 'v2.0.1@8cc682ac458d75557203b2f2f14b0b92e1c744ed',
  'symfony/twig-bridge' => 'v4.4.6@bd446d8e64ef049dd3afc090794e5b7e4f17d272',
  'symfony/twig-bundle' => 'v4.4.6@0b33e802fcd9a10287631d98962ca9164c4ccd45',
  'symfony/validator' => 'v4.4.6@b0e5edb15ec6b0a03aea67ad0bc79edea0091eef',
  'symfony/var-dumper' => 'v4.4.6@6dae4692ac91230b33b70d9a48882ff5c838d67a',
  'symfony/var-exporter' => 'v5.0.6@89d4bfebb1ad985ee8b770d826707658c44e45d8',
  'symfony/web-link' => 'v4.4.6@fda0ec7e0999e52e7b6223f59c5c00365f10f88c',
  'symfony/webpack-encore-pack' => 'v1.0.3@8d7f51379d7ae17aea7cf501d910a11896895ac4',
  'symfony/yaml' => 'v4.4.6@43d7a46b1f80b4fd2ecfac4a9a4cc1f22d029fbb',
  'twig/twig' => 'v3.0.3@3b88ccd180a6b61ebb517aea3b1a8906762a1dc2',
  'webmozart/assert' => '1.7.0@aed98a490f9a8f78468232db345ab9cf606cf598',
  'zendframework/zend-code' => '3.4.1@268040548f92c2bfcba164421c1add2ba43abaaa',
  'zendframework/zend-eventmanager' => '3.2.1@a5e2583a211f73604691586b8406ff7296a946dd',
  'easycorp/easy-log-handler' => 'v1.0.9@224e1dfcf9455aceee89cd0af306ac097167fac1',
  'guzzlehttp/guzzle' => '6.5.2@43ece0e75098b7ecd8d13918293029e555a50f82',
  'guzzlehttp/promises' => 'v1.3.1@a59da6cf61d80060647ff4d3eb2c03a2bc694646',
  'guzzlehttp/psr7' => '1.6.1@239400de7a173fe9901b9ac7c06497751f00727a',
  'nikic/php-parser' => 'v4.3.0@9a9981c347c5c49d6dfe5cf826bb882b824080dc',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'symfony/browser-kit' => 'v4.4.6@4e9a171559f5a9018c90ba9e85b4084d4e045186',
  'symfony/css-selector' => 'v4.4.6@402251c6fd69806a70a2b0e1426d16f8487f0f9a',
  'symfony/debug-bundle' => 'v4.4.6@570c3c69e69f7709f184ee3acbebe45e5ff1adce',
  'symfony/debug-pack' => 'v1.0.7@09a4a1e9bf2465987d4f79db0ad6c11cc632bc79',
  'symfony/dom-crawler' => 'v4.4.6@7e7c7957f6d53757d36b61a1f7408ef0b6683040',
  'symfony/dotenv' => 'v4.4.6@9bba981ecb1f57c04520d4165b3e6a17ac49319f',
  'symfony/maker-bundle' => 'v1.14.6@bc4df88792fbaaeb275167101dc714218475db5f',
  'symfony/phpunit-bridge' => 'v5.0.7@0258b43a94972abf1ee99ce2221359f8ac2a17fd',
  'symfony/profiler-pack' => 'v1.0.4@99c4370632c2a59bb0444852f92140074ef02209',
  'symfony/test-pack' => 'v1.0.6@ff87e800a67d06c423389f77b8209bc9dc469def',
  'symfony/web-profiler-bundle' => 'v4.4.6@76c21d0137f0b9c6bbbc93ac2672cadfdf2e625a',
  'symfony/web-server-bundle' => 'v4.4.6@fe438443231563da8e58dcbd7bb62f4ee4bda383',
  'symfony/polyfill-iconv' => '*@4411412885818aa051e2da390f9fecbd2a9416fd',
  'symfony/polyfill-php71' => '*@4411412885818aa051e2da390f9fecbd2a9416fd',
  'symfony/polyfill-php70' => '*@4411412885818aa051e2da390f9fecbd2a9416fd',
  'symfony/polyfill-php56' => '*@4411412885818aa051e2da390f9fecbd2a9416fd',
  '__root__' => 'dev-forms@4411412885818aa051e2da390f9fecbd2a9416fd',
);

    private function __construct()
    {
    }

    /**
     * @throws \OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     */
    public static function getVersion(string $packageName) : string
    {
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new \OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
