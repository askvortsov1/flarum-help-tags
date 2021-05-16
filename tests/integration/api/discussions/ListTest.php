<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Flarum\Tags\Tests\integration\api\discussions;

use Flarum\Group\Group;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Illuminate\Support\Arr;

class ListTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension('flarum-tags');
        $this->extension('askvortsov-help-tags');

        $this->prepareDatabase([
            'tags' => [
                ['id' => 1, 'name' => 'Help Tag', 'slug' => '1', 'position' => 0, 'parent_id' => null, 'is_restricted' => true],
            ],
            'users' => [
                $this->normalUser()
            ],
            'discussions' => [
                ['id' => 1, 'title' => 'admin discussion', 'user_id' => 1, 'comment_count' => 1],
                ['id' => 2, 'title' => 'user discussion', 'user_id' => 2, 'comment_count' => 1]
            ],
            'posts' => [
                ['id' => 1, 'discussion_id' => 1, 'user_id' => 1, 'type' => 'comment', 'content' => '<t><p></p></t>'],
                ['id' => 2, 'discussion_id' => 2, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p></p></t>']
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 1],
                ['discussion_id' => 2, 'tag_id' => 1]
            ],
            'group_permission' => [
                ['group_id' => Group::MEMBER_ID, 'permission' => 'tag1.startDiscussion'],
            ]
        ]);
    }

    /**
     * @test
     */
    public function admin_sees_all_discussions_in_help_tag()
    {
        $response = $this->send(
            $this->request('GET', '/api/discussions', [
                'authenticatedAs' => 1,
            ])
        );

        $data = json_decode($response->getBody()->getContents(), true)['data'];
        $ids = Arr::pluck($data, 'id');

        $this->assertEqualsCanonicalizing(['1', '2'], $ids);
    }

    /**
     * @test
     */
    public function user_sees_own_discussion_in_help_tag()
    {
        $response = $this->send(
            $this->request('GET', '/api/discussions', [
                'authenticatedAs' => 2,
            ])
        );

        $data = json_decode($response->getBody()->getContents(), true)['data'];
        $ids = Arr::pluck($data, 'id');

        $this->assertEqualsCanonicalizing(['2'], $ids);
    }
}
