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
}
