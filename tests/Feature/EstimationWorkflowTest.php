<?php

it('can view estimates index when authenticated', function () {
    $user = \App\Models\User::factory()->create();

    test()->actingAs($user)
        ->get(route('estimates.index'))
        ->assertOk()
        ->assertSee('Projects');
});

it('can view estimate creation form when authenticated', function () {
    $user = \App\Models\User::factory()->create();

    test()->actingAs($user)
        ->get(route('estimates.create'))
        ->assertOk()
        ->assertSee('New Estimate');
});

it('redirects unauthenticated users when creating estimates', function () {
    test()->post(route('estimates.store'), [])
        ->assertRedirect();
});

it('shows basic page structure', function () {
    test()->get('/')
        ->assertOk();
});
