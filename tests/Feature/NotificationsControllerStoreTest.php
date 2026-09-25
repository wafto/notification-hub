<?php

use function Pest\Laravel\postJson;

test('endpoint should return 201 status code', function () {
    $response = postJson('/api/v1/notifications', []);

    $response->assertStatus(201);
});
