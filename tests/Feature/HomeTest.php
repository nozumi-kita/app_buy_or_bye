<?php

use Tests\TestCase;

test('ルートパスにアクセスした場合、アイテム一覧(items.index)にリダイレクトすること', function () {
    /** @var TestCase $this */
    $this->get('/')->assertRedirect(route('items.index'));
});
