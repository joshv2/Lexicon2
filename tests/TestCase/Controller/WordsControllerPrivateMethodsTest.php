<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use App\Controller\WordsController;
use ReflectionMethod;

class WordsControllerPrivateMethodsTest extends TestCase
{
    protected array $fixtures = [
        'app.Origins',
        'app.Types',
    ];

    public function testProcessOtherAssociationsPrivate()
    {
        // Minimal controller instantiation with request/response
        $req = new ServerRequest();
        $res = new Response();
        $controller = new WordsController($req);

        // sample post data with existing id and an "other" new entry
        $postData = [
            'origins' => ['_ids' => ['1', '']], 
            'origins_other_entry' => 'NewOrigin; ExistingOrigin',
            'types' => ['_ids' => ['']],
            'types_other_entry' => '',
        ];

        // call private method via Reflection
        $rm = new ReflectionMethod(WordsController::class, 'processOtherAssociations');
        $rm->setAccessible(true);

        $result = $rm->invoke($controller, $postData, 'origins');

        // assert resulting structure: origins key should be an array of items with id or origin fields
        $this->assertArrayHasKey('origins', $result);
        $this->assertIsArray($result['origins']);
        // ensure at least one entry with 'origin' or 'id' exists
        $hasOriginOrId = false;
        foreach ($result['origins'] as $entry) {
            if (isset($entry['id']) || isset($entry['origin'])) {
                $hasOriginOrId = true;
                break;
            }
        }
        $this->assertTrue($hasOriginOrId);
    }
}