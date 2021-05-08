<?php
namespace common\components;

use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use Yii;


/*
 * Clean composer's vendor/ of docs, gits, tests, etc
 *
 * based on https://github.com/barryvdh/composer-cleanup-plugin
 * modified to add rules, echo out progress
 *
 * php yii clean-vendors
 */
class CleanVendorsController extends Controller
{

    public $vendor_dir = 'vendor';
    public $dry_run = false;
    public $verbose = false;
    public $silent = false;
    public $rules = [];

    public function actionIndex()
    {
        foreach (Yii::$app->requestedParams as $param) {
            if (stripos($param, 'dry-run') !== false || stripos($param, 'dryrun') !== false) {
                $this->dry_run = true;
            } else if (stripos($param, 'verbose') !== false) {
                $this->verbose = true;
            } else if (stripos($param, 'silent') !== false) {
                $this->silent = true;
            }
        }

        $cleaned = $this->cleanPackages();

        if ($cleaned === false) {
            return 1;
        }

        return 0;
    }

    private function echo_msg($msg, $show=true)
    {
        if (!$this->silent && ($this->dry_run || $this->verbose || $show)) {
            echo $msg."\n";
        }
    }


    /**
     * Clean composer packages
     */
    public function cleanPackages()
    {
        $this->echo_msg('Checking '.$this->vendor_dir.'/ to remove docs, gits, tests, etc .. ');

        $composer_installed_file = $this->vendor_dir.'/composer/installed.json';
        if (!is_file($composer_installed_file)) {
            $this->echo_msg('Composer installed file ['.$composer_installed_file.'] does not exist');
            return false;
        }
        $composer_installed = file_get_contents($composer_installed_file);
        $packages = json_decode($composer_installed, true);

        $this->echo_msg('Checking '.count($packages).' vendor packages ..');

        $this->rules = $this->getRules();

        $nbr_cleaned = 0;
        $nbr_checked = 0;
        $nbr_default = 0;
        foreach ($packages as $package) {
            if (empty($package['name'])) {
                // invalid composer package
                continue;
            }

            if (!isset($this->rules[$package['name']])) {
                // no rule for package, so will use default rule
                $package_name = 'default';
                $nbr_default++;
            } else {
                $package_name = $package['name'];
            }

            $cleaned = $this->cleanPackage($package_name);
            if ($cleaned !== false) {
                $nbr_cleaned += $cleaned;
            }
            $nbr_checked++;
        }
        $nbr_custom = ($nbr_checked - $nbr_default);

        $this->echo_msg('Scanned '.$nbr_checked.' vendor package'.($nbr_checked == 1 ? '' : 's').'; used '.$nbr_custom.' custom rule'.($nbr_custom == 1 ? '' : 's').'; used default rule '.$nbr_default.' times');
        $this->echo_msg('Done. Cleaned '.$nbr_cleaned.' files/dirs');

    }

    /**
     * Clean a package, based on its rules.
     *
     * @return bool True if cleaned
     */
    protected function cleanPackage($package)
    {


        $dir = $this->vendor_dir . '/' . $package;
        if (!is_dir($dir)) {
            return false;
        }

        if ($this->dry_run) {
            $msg_try = 'would have ';
        } else {
            $msg_try = '';
        }

        $nbr_cleaned = 0;
        foreach((array) $this->rules[$package] as $part) {
            // Split patterns for single globs (should be max 260 chars)
            $patterns = explode(' ', trim($part));

            foreach ($patterns as $pattern) {
                try {
                    $files = glob($dir.'/'.$pattern);
                    $nbr_files = count($files);
                    $this->echo_msg('checking '.$nbr_files.' result'.($nbr_files == 1 ? '' : 's').' in '.$dir.'/'.$pattern, false);

                    foreach (glob($dir.'/'.$pattern) as $file) {
                        if (is_dir($file)) {
                            $this->echo_msg($msg_try.'removed dir: '.$file);

                            if (!$this->dry_run) {
                                FileHelper::removeDirectory($file);
                            }
                        } else {
                            $this->echo_msg($msg_try.'removed file: '.$file);

                            if (!$this->dry_run) {
                                unlink($file);
                            }
                        }
                        $nbr_cleaned++;
                    }
                } catch (\Exception $e) {
                    $this->echo_msg('Could not parse ['.$dir.'/'.$pattern.']: '.$e->getMessage());
                }
            }
        }

        return $nbr_cleaned;
    }


   public static function getRules()
   {
        // Default patterns for common files
        $docs = 'README* CHANGELOG* FAQ* CONTRIBUTING* HISTORY* UPGRADING* UPGRADE* package* demo example examples doc docs readme*';
        $tests = '.travis.yml .scrutinizer.yml phpunit.xml* phpunit.php test tests Tests travis .git';

        return array(
            'default'                               => array($docs, $tests),

            // Symfony components
            'symfony/browser-kit'                   => array($docs, $tests),
            'symfony/class-loader'                  => array($docs, $tests),
            'symfony/console'                       => array($docs, $tests),
            'symfony/css-selector'                  => array($docs, $tests),
            'symfony/debug'                         => array($docs, $tests),
            'symfony/dom-crawler'                   => array($docs, $tests),
            'symfony/event-dispatcher'              => array($docs, $tests),
            'symfony/filesystem'                    => array($docs, $tests),
            'symfony/finder'                        => array($docs, $tests),
            'symfony/http-foundation'               => array($docs, $tests),
            'symfony/http-kernel'                   => array($docs, $tests),
            'symfony/process'                       => array($docs, $tests),
            'symfony/routing'                       => array($docs, $tests),
            'symfony/security'                      => array($docs, $tests),
            'symfony/security-core'                 => array($docs, $tests),
            'symfony/translation'                   => array($docs, $tests),
            'symfony/var-dumper'                    => array($docs, $tests),

            // Default Laravel 4 install
            'classpreloader/classpreloader'         => array($docs, $tests),
            'd11wtq/boris'                          => array($docs, $tests),
            'filp/whoops'                           => array($docs, $tests),
            'ircmaxell/password-compat'             => array($docs, $tests),
            'jeremeamia/SuperClosure'               => array($docs, $tests, 'demo'),
            'laravel/framework'                     => array($docs, $tests, 'build'),
            'monolog/monolog'                       => array($docs, $tests),
            'nesbot/carbon'                         => array($docs, $tests),
            'nikic/php-parser'                      => array($docs, $tests, 'test_old'),
            'patchwork/utf8'                        => array($docs, $tests),
            'phpseclib/phpseclib'                   => array($docs, $tests, 'build'),
            'predis/predis'                         => array($docs, $tests, 'bin'),
            'psr/log'                               => array($docs, $tests),
            'stack/builder'                         => array($docs, $tests),
            'swiftmailer/swiftmailer'               => array($docs, $tests, 'build* notes test-suite create_pear_package.php'),

            // Common packages
            'anahkiasen/former'                     => array($docs, $tests),
            'anahkiasen/html-object'                => array($docs, 'phpunit.xml* tests/*'),
            'anahkiasen/underscore-php'             => array($docs, $tests),
            'anahkiasen/rocketeer'                  => array($docs, $tests),
            'barryvdh/composer-cleanup-plugin'      => array($docs, $tests),
            'barryvdh/laravel-debugbar'             => array($docs, $tests),
            'barryvdh/laravel-ide-helper'           => array($docs, $tests),
            'bllim/datatables'                      => array($docs, $tests),
            'cartalyst/sentry'                      => array($docs, $tests),
            'dflydev/markdown'                      => array($docs, $tests),
            'doctrine/annotations'                  => array($docs, $tests, 'bin'),
            'doctrine/cache'                        => array($docs, $tests, 'bin'),
            'doctrine/collections'                  => array($docs, $tests),
            'doctrine/common'                       => array($docs, $tests, 'bin lib/vendor'),
            'doctrine/dbal'                         => array($docs, $tests, 'bin build* docs2 lib/vendor'),
            'doctrine/inflector'                    => array($docs, $tests),
            'dompdf/dompdf'                         => array($docs, $tests, 'www'),
            'guzzle/guzzle'                         => array($docs, $tests),
            'guzzlehttp/guzzle'                     => array($docs, $tests),
            'guzzlehttp/oauth-subscriber'           => array($docs, $tests),
            'guzzlehttp/streams'                    => array($docs, $tests),
            'imagine/imagine'                       => array($docs, $tests, 'lib/Imagine/Test'),
            'intervention/image'                    => array($docs, $tests, 'public'),
            'jasonlewis/basset'                     => array($docs, $tests),
            'kriswallsmith/assetic'                 => array($docs, $tests),
            'leafo/lessphp'                         => array($docs, $tests, 'Makefile package.sh'),
            'league/stack-robots'                   => array($docs, $tests),
            'maximebf/debugbar'                     => array($docs, $tests, 'demo'),
            'mccool/laravel-auto-presenter'         => array($docs, $tests),
            'mockery/mockery'                       => array($docs, $tests),
            'mrclay/minify'                         => array($docs, $tests, 'MIN.txt min_extras min_unit_tests min/builder min/config* min/quick-test* min/utils.php min/groupsConfig.php min/index.php'),
            'mustache/mustache'                     => array($docs, $tests, 'bin'),
            'oyejorge/less.php'                     => array($docs, $tests),
            'phenx/php-font-lib'                    => array($docs, $tests. 'www'),
            'phpdocumentor/reflection-docblock'     => array($docs, $tests),
            'phpoffice/phpexcel'                    => array($docs, $tests, 'Documentation Examples unitTests changelog.txt'),
            'rcrowe/twigbridge'                     => array($docs, $tests),
            'simplepie/simplepie'                   => array($docs, $tests, 'build compatibility_test ROADMAP.md'),
            'tijsverkoyen/css-to-inline-styles'     => array($docs, $tests),
            'twig/twig'                             => array($docs, $tests),
            'venturecraft/revisionable'             => array($docs, $tests),
            'willdurand/geocoder'                   => array($docs, $tests),



            // codeception
            'codeception/codeception'               => array($docs, $tests),
            'codeception/specify'                   => array($docs, $tests),
            'codeception/verify'                    => array($docs, $tests),

            // guzzle
            'guzzlehttp/promises'                   => array($docs, $tests),
            'guzzlehttp/psr7'                       => array($docs, $tests),

            // phpunit
            'phpunit/php-code-coverage'             => array($docs, $tests),
            'phpunit/php-file-iterator'             => array($docs, $tests),
            'phpunit/php-text-template'             => array($docs, $tests),
            'phpunit/php-timer'                     => array($docs, $tests),
            'phpunit/php-token-stream'              => array($docs, $tests),
            'phpunit/phpunit'                       => array($docs, $tests),
            'phpunit/phpunit-mock-objects'          => array($docs, $tests),


            'phpunit/phpunit-mock-objects'          => array($docs, $tests),

            /**
             * modified by @henry
             */
            
            // INLISlitev3
            'psliwa/php-pdf'                        => array($docs, $tests),
            //'hscstudio/yii2-export'                 => array($docs, $tests),
            'kartivk-v/mpdf'                        => array($docs, $tests),
            'mdmsoft/yii2-admin'                    => array($docs, $tests),
            'nhkey/yii2-activerecord-history'       => array($docs, $tests),
            'phpspec/php-diff'                      => array($docs, $tests),
            'unclead/yii2-multiple-input'           => array($docs, $tests),
            'wbraganca/yii2-dynamicform'            => array($docs, $tests),

            // Yii 2amigos
            '2amigos/yii2-date-picker-widget'          => array($docs, $tests),
            '2amigos/yii2-file-upload-widget'          => array($docs, $tests),
            '2amigos/yii2-gallery-widget'              => array($docs, $tests),
            '2amigos/yii2-highcharts-widget'           => array($docs, $tests),

            // Bower
            'bower/blueimp-canvas-to-blob'           => array($docs, $tests),
            'bower/bootstrap-datepicker'             => array($docs, $tests),
            'bower/chartjs'                          => array($docs, $tests),
            'bower/jQuery-QueryBuilder'                          => array($docs, $tests),
            'bower/sweetalert'                          => array($docs, $tests),
            'bower/typeahead.js'                          => array($docs, $tests),


            // Yiisoft
            'yiisoft/yii2-bootstrap'                         => array($docs, $tests),
            'yiisoft/yii2-codeception'                       => array($docs, $tests),
            'yiisoft/yii2-composer'                          => array($docs, $tests),
            'yiisoft/yii2-debug'                             => array($docs, $tests),
            'yiisoft/yii2-faker'                             => array($docs, $tests),
            'yiisoft/yii2-gii'                             => array($docs, $tests),
            'yiisoft/yii2-jui'                             => array($docs, $tests),
            'yiisoft/yii2-gii'                             => array($docs, $tests),

            // Zendframework
            'zendframework/zend-barcode'                             => array($docs, $tests),
            'zendframework/zend-cache'                             => array($docs, $tests),
            'zendframework/zend-eventmanager'                             => array($docs, $tests),
            'zendframework/zend-hydrator'                             => array($docs, $tests),
            'zendframework/zend-memory'                             => array($docs, $tests),
            'zendframework/zend-memory'                             => array($docs, $tests),
        );
    }
}