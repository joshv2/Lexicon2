<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Event\EventInterface;
use App\Model\Entity\Language;
use \CloudConvert\CloudConvert;
use \CloudConvert\Models\Job;
use \CloudConvert\Models\Task;
use \CloudConvert\Models\ImportUploadTask;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */

    protected ?Language $sitelang = null;

    protected function languageinfo(): ?Language {
        //array_map([$this, 'fetchTable'], ['Languages']);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $urlparts1 = explode('//', $actual_link);
        $urlparts2 = explode('.', $urlparts1[1]);
        $reqsubdomain = $urlparts2[0];

        return $this->fetchTable('Languages')->get_language($reqsubdomain);
        //return $sitelangvalues;
    }

    public function converttomp3($file){
        $cloudconvert = new CloudConvert(['api_key' => Configure::consume('cloudconvertkey'),
        'sandbox' => false]);

        $job = (new Job())
        ->addTask(
            (new Task('import/upload', 'import-1'))
                ->set('file', 'recordings/'. $file)
                ->set('filename', $file)
            )
        ->addTask(
            (new Task('convert', 'task-1'))
                ->set('input_format', 'webm')
                ->set('output_format', 'mp3')
                ->set('engine', 'ffmpeg')
                ->set('input', ["import-1"])
                ->set('audio_codec', 'mp3')
                ->set('audio_qscale', 0)
                //->set('engine_version', '4.4.1')
            )
        ->addTask(
            (new Task('export/url', 'export-1'))
                ->set('input', ["task-1"])
                ->set('inline', false)
                ->set('archive_multiple_files', false)
            ); 

        $response1 = $cloudconvert->jobs()->create($job);
        $uploadTask = $job->getTasks()->whereName('import-1')[0];
        $cloudconvert->tasks()->upload($uploadTask, fopen('recordings/' . $file, 'r'), $file);

        #print_r($response1);
        $cloudconvert->jobs()->wait($job); // Wait for job completion

        foreach ($job->getExportUrls() as $file) {

            $source = $cloudconvert->getHttpTransport()->download($file->url)->detach();
            $dest = fopen('recordings/' . $file->filename, 'w');
            
            stream_copy_to_stream($source, $dest);

        }
    }

    public function getremainingcredits(){
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.cloudconvert.com/v2/users/me');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . Configure::consume('cloudconvertkey')
        ));


        $remaining = curl_exec($cURLConnection);
        $jsonArrayResponse = json_decode($remaining);
        return $jsonArrayResponse->data->credits;
    }
    
    public function initialize(): void
    {
        parent::initialize();

        $config['Auth']['authorize']['CakeDC/Users.SimpleRbac'] = [
            // autoload permissions.php
            'autoload_config' => 'permissions',
            // role field in the Users table
            'role_field' => 'role',
            // default role, used in new users registered and also as role matcher when no role is available
            'default_role' => 'user',
            /*
             * This is a quick roles-permissions implementation
             * Rules are evaluated top-down, first matching rule will apply
             * Each line define
             *      [
             *          'role' => 'admin',
             *          'plugin', (optional, default = null)
             *          'prefix', (optional, default = null)
             *          'extension', (optional, default = null)
             *          'controller',
             *          'action',
             *          'allowed' (optional, default = true)
             *      ]
             * You could use '*' to match anything
             * Suggestion: put your rules into a specific config file
             */
            'permissions' => [], // you could set an array of permissions or load them using a file 'autoload_config'
            // log will default to the 'debug' value, matched rbac rules will be logged in debug.log by default when debug enabled
            'log' => false
        ];

        $sitelang = $this->languageinfo();


        if (null != $sitelang->i18nspec) {
            I18n::setLocale($sitelang->i18nspec);
        } else {
            I18n::setLocale('en_US');
        }

        /*switch($sitelang['name']){
            case "English":
                I18n::setLocale('en_US');
                break;
            case "Portuguese":
                I18n::setLocale('pt');
                break;
            }*/

        //$this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Processfile');
        //$this->loadComponent('Authentication.Authentication');
        //$this->loadComponent('CakeDC.User');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);
        $this->set('languageInfo', $this->languageinfo());
    }
}
