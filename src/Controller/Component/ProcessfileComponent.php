<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use \CloudConvert\CloudConvert;
use \CloudConvert\Models\Job;
use \CloudConvert\Models\Task;
use \CloudConvert\Models\ImportUploadTask;

class ProcessfileComponent extends Component
{
    protected array $components = ['Flash'];

    public function areThereAnyFiles($soundFiles){
        $errorArray = [];
        foreach ($soundFiles as $soundFile) {
            array_push($errorArray, $soundFile->getError());
        }
        if (in_array(0, $errorArray)){
            return TRUE; 
        } else {
            return FALSE;
        }
    }

    public function checkFormats($soundFiles){
        $filetypeArray = [];
        foreach ($soundFiles as $soundFile) {
            if ($soundFile->getError() == 4){} 
            elseif ('' !== $soundFile->getClientFilename() &&
                $soundFile->getSize() > 0 && 
                $soundFile->getError() == 0) {
                    $name = $soundFile->getClientFilename();
                    $type = $soundFile->getClientMediaType();
                    if (in_array($type, ['audio/webm', 'audio/mpeg'])){
                        array_push($filetypeArray, 1);
                    }
                }
        }

        if (!in_array(1,$filetypeArray)){
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function processSoundfiles($soundFiles, $controller, $id)
    {
        $i = 0;
        $soundFileArray = [];
        foreach ($soundFiles as $soundFile) {
            if ($soundFile->getError() == 4){} 
            elseif ('' !== $soundFile->getClientFilename() &&
                $soundFile->getSize() > 0 && 
                $soundFile->getError() == 0) {
                    $name = $soundFile->getClientFilename();
                    $type = $soundFile->getClientMediaType();
                    
                    $recordingname = $controller . $id . time() . $i;


                    switch ($type){
                        case 'audio/webm':
                            $extension = '.webm';
                            break;
                        case 'audio/mpeg':
                            $extension = '.mp3';
                            break;
                        }

                    $finalname = $recordingname . $extension;
                    $targetPath = WWW_ROOT. 'recordings'. DS . $finalname;
                    $soundFile->moveTo($targetPath);
                    $soundFileArray[$i] = $finalname;
                    return $soundFileArray;
                } 
            $i++;
        }
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
        //$cloudconvert->tasks()->upload($uploadTask, fopen('recordings/' . $file, 'r'), $file);
        $cloudconvert->tasks()->upload($uploadTask, fopen(WWW_ROOT . DS . 'recordings/' . $file, 'r'), $file);

        #print_r($response1);
        $cloudconvert->jobs()->wait($job); // Wait for job completion

        foreach ($job->getExportUrls() as $file) {

            $source = $cloudconvert->getHttpTransport()->download($file->url)->detach();
            //$dest = fopen('recordings/' . $file->filename, 'w');
            $dest = fopen(WWW_ROOT . DS . 'recordings/' . $file->filename, 'w');
            stream_copy_to_stream($source, $dest);

        }
    }

    public function convertMP3($soundfiles){
        foreach ($soundfiles as $soundFile) {
            if (substr($soundFile, -4) == 'webm'){
                $this->converttomp3($soundFile);
            }

        }
    }
}