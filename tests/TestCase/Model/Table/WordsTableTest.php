<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class WordsTableTest extends TestCase
{
    protected array $fixtures = [
        'app.Words',
        'app.Alternates',
        'app.Definitions',
        'app.Pronunciations',
        'app.Dictionaries',
        'app.DictionariesWords',
        'app.Origins',
        'app.OriginsWords',
        'app.Regions',
        'app.RegionsWords',
        'app.Types',
        'app.TypesWords',
        'app.Sentences',
        'app.SentenceRecordings',
        'app.Users',
        'app.Languages',
    ];

    public function testValidationDefaultRequiresSpelling(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $entity = $words->newEntity([
            'language_id' => 1,
        ], ['validate' => 'default']);

        $errors = $entity->getErrors();
        $this->assertArrayHasKey('spelling', $errors);
    }

    public function testValidationNotloggedinRequiresFullName(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $entity = $words->newEntity([
            'spelling' => 'newword',
            'language_id' => 1,
            'email' => 'person@example.test',
        ], ['validate' => 'notloggedin']);

        $errors = $entity->getErrors();
        $this->assertArrayHasKey('full_name', $errors);
    }

    public function testUniqueSpellingRuleRejectsDuplicateApprovedWord(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $entity = $words->newEntity([
            'spelling' => 'apple',
            'language_id' => 1,
            'approved' => 1,
            'user_id' => '00000000-0000-0000-0000-000000000001',
        ], ['validate' => 'default']);

        $saved = $words->save($entity);
        $this->assertFalse($saved);

        $errors = $entity->getErrors();
        $this->assertArrayHasKey('spelling', $errors);
    }

    public function testBrowseWordsSimplifiedUnknownFilterKeyReturnsSafeQuery(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->browse_words_simplified('page', '2', false, 1);
        $ids = $query->all()->extract('id')->toList();

        $this->assertSame([1, 4], $ids);
    }

    public function testBrowseWordsSimplifiedDisplayTypeAllReturnsApprovedWords(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->browse_words_simplified('displayType', 'all', false, 1);
        $ids = $query->all()->extract('id')->toList();

        $this->assertSame([1, 4], $ids);
    }

    public function testBrowseWordsSimplifiedDictionaryNoneReturnsOnlyWordsNotInDictionary(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->browse_words_simplified('dictionary', 'none', false, 1);
        $ids = $query->all()->extract('id')->toList();

        $this->assertSame([4], $ids);
    }

    public function testBrowseWordsSimplifiedDictionaryOtherReturnsEmptyForFixtureData(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->browse_words_simplified('dictionary', 'other', false, 1);
        $this->assertSame(0, $query->count());
    }

    public function testGetNotInOtherDictionaryCountAndWords(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $count = $words->get_not_in_other_dictionary_count(1);
        $this->assertSame(1, $count);

        $query = $words->get_not_in_other_dictionary_words(1);
        $ids = $query->all()->extract('id')->toList();
        $this->assertSame([4], $ids);
    }

    public function testGetWordsWithNoPronunciations(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $lang1 = $words->get_words_with_no_pronunciations(1)
            ->all()
            ->extract('word_id')
            ->toList();
        $this->assertSame([4], $lang1, 'Expected only word 4 to have no audio pronunciations in language 1.');

        $lang2 = $words->get_words_with_no_pronunciations(2)
            ->all()
            ->extract('word_id')
            ->toList();
        $this->assertSame([2], $lang2, 'Expected word 2 to have no pronunciations in language 2.');
    }

    public function testFindSearchResultsEmptyQueryReturnsNone(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->find();
        $words->findSearchResults($query, '   ', 1);
        $this->assertSame(0, $query->count());
    }

    public function testFindSearchResultsExactAndAlternate(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $query = $words->find();
        $words->findSearchResults($query, 'apple', 1);
        $rows = $query->enableHydration(false)->toArray();

        $this->assertCount(1, $rows);
        $this->assertSame(1, $rows[0]['id']);
        $this->assertSame('apple', $rows[0]['spelling']);
        $this->assertSame(5, (int)$rows[0]['spellingmatch']);

        $query2 = $words->find();
        $words->findSearchResults($query2, 'appl', 1);
        $rows2 = $query2->enableHydration(false)->toArray();

        $this->assertCount(1, $rows2);
        $this->assertSame(1, $rows2[0]['id']);
        $this->assertSame(4, (int)$rows2[0]['spellingmatch']);
    }

    public function testFindSearchResultsByDefinitionMatchesDefinitionAndNotes(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $q1 = $words->find();
        $words->findSearchResultsByDefinition($q1, 'fruit', 1);
        $rows1 = $q1->enableHydration(false)->toArray();
        $ids1 = array_map(static fn ($r) => $r['id'], $rows1);
        $this->assertContains(1, $ids1);

        $q2 = $words->find();
        $words->findSearchResultsByDefinition($q2, 'noteonly', 1);
        $rows2 = $q2->enableHydration(false)->toArray();
        $ids2 = array_map(static fn ($r) => $r['id'], $rows2);
        $this->assertSame([4], $ids2);
    }

    public function testFindWithSpellingRejectsWordOrAlternateDuplicates(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $this->assertFalse($words->findWithSpelling(['spelling' => 'apple', 'language_id' => 1]));
        $this->assertFalse($words->findWithSpelling(['spelling' => 'appl', 'language_id' => 1]));
        $this->assertTrue($words->findWithSpelling(['spelling' => 'brandnew', 'language_id' => 1]));
    }

    public function testGetNewWordIdFindsWordByOldIdAndLanguage(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $found = $words->get_new_word_id(7, 1);
        $this->assertNotNull($found);
        $this->assertSame(10, $found->id);

        $missing = $words->get_new_word_id(7, 999);
        $this->assertNull($missing);
    }

    public function testGetWordForViewReturnsArrayAndNullWhenMissing(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $result = $words->get_word_for_view(1);
        $this->assertIsArray($result);
        $this->assertSame('apple', $result['spelling']);
        $this->assertArrayHasKey('definitions', $result);
        $this->assertNotEmpty($result['definitions']);
        $this->assertArrayHasKey('alternates', $result);

        $this->assertNull($words->get_word_for_view(9999));
    }

    public function testGetPendingWordsReturnsOnlyUnapproved(): void
    {
        $words = TableRegistry::getTableLocator()->get('Words');

        $pending = $words->get_pending_words(1);
        $ids = $pending->all()->extract('id')->toList();
        $this->assertSame([3], $ids);
    }
}
