<?php

namespace Aregsar\Converter\Tests\Models;

use Aregsar\Converter\Models\Note;
use Aregsar\Converter\Tests\BaseTestCase;
use Aregsar\Converter\Tests\Fixtures\Doubles\TestUser;

use Illuminate\Foundation\Testing\RefreshDatabase;


class NoteTest extends BaseTestCase
{
    //use RefreshDatabase;

    /** @test */
    function note_belongs_to_testuser_owner()
    {

        //create the owner thorough the TestUserFactory
        $owner = TestUser::factory()->create();

        //create a note through the owner relationship
        $owner->notes()->create([
            'content'  => 'buy milk',
        ]);

        $this->assertCount(1, Note::all());
        $this->assertCount(1, $owner->notes);

        $note = $owner->notes()->first();
        $this->assertTrue($note->owner->is($owner));
        $this->assertEquals('buy milk', $note->content);
    }


    /** @test */
    function note_type_is_owner()
    {
        //create the owner thorough the TestUserFactory
        $owner = TestUser::factory()->create();

        //create the note through the NoteFactory
        $note = Note::factory()->create([
            "owner_id" => $owner->id,
            "owner_type" => TestUser::class
        ]);

        $this->assertTrue($note->owner->is($owner));
        $this->assertEquals(TestUser::class, $note->owner_type);
    }
}
