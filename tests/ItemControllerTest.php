<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ItemControllerTest extends PantherTestCase
{
    public function testCreateItem(): void
    {
        $client = static::createPantherClient();
        // Load the index
        $client->request('GET', '/item/');
        $client->followRedirects();

        $this->assertPageTitleSame('Item index');
        $client->clickLink('Create new');

        // On the creation form
        $this->assertPageTitleSame('New Item');
        $this->assertSelectorTextSame('body > h1', 'Create new Item');

        // Fill and submit the form
        $client->submitForm('Save', [
            // item[storyLink] is the name of the form field
            'item[storyLink]' => 'https://masterclass.les-tilleuls.coop',
            'item[publishedAt][date][month]' => '9',
            'item[publishedAt][date][day]' => '18',
            'item[publishedAt][date][year]' => '2019',
        ]);

        $this->assertSelectorTextContains('table', 'https://masterclass.les-tilleuls.coop');
    }

    public function testComment(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/item/1'); // 🔍🙀

        // Panther's magic: wait for the form to appear!
        $client->waitFor('#post-comment');

        $client->submitForm('Post', [
            'body' => 'Very interesting!',
            'author' => 'bob',
        ]);

        // Wait for the post to be processed server-side, fetched and displayed
        $client->waitFor('#status.displayed');

        $this->assertSelectorTextContains('#comments', 'Very interesting!');
    }
}
