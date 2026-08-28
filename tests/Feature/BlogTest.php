<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\TeamMember;
use App\Models\User;
use Livewire\Volt\Volt;

function makeBlogFixtures(): array
{
    $category = Category::create([
        'name' => 'Web Architecture',
        'slug' => 'web-architecture',
        'description' => 'Arsitektur sistem & backend.',
        'color' => 'primary',
    ]);

    $author = TeamMember::create([
        'name' => 'Test Author',
        'position' => 'Engineer',
        'order' => 1,
    ]);

    return [$category, $author];
}

function makePost(Category $category, TeamMember $author, array $overrides = []): Post
{
    return Post::create(array_merge([
        'author_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Membangun Arsitektur Microservices dengan Docker',
        'slug' => 'membangun-arsitektur-microservices-dengan-docker',
        'excerpt' => 'Panduan singkat microservices.',
        'body' => "## Pendahuluan\n\nIsi artikel tentang Docker dan microservices.",
        'status' => 'published',
        'published_at' => now()->subDay(),
        'reading_time' => 3,
    ], $overrides));
}

test('blog index page is reachable', function () {
    [$category, $author] = makeBlogFixtures();
    makePost($category, $author);

    $this->get('/blog')
        ->assertOk()
        ->assertSeeVolt('pages.blog.index');
});

test('a published post detail page is reachable', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author);

    $this->get('/blog/'.$post->slug)
        ->assertOk()
        ->assertSeeVolt('pages.blog.show')
        ->assertSee($post->title);
});

test('a draft post returns 404 to public visitors', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author, [
        'title' => 'Draf Belum Terbit',
        'slug' => 'draf-belum-terbit',
        'status' => 'draft',
        'published_at' => null,
    ]);

    $this->get('/blog/'.$post->slug)->assertNotFound();
});

test('live search narrows the article list', function () {
    [$category, $author] = makeBlogFixtures();
    $docker = makePost($category, $author);
    $design = makePost($category, $author, [
        'title' => 'Prinsip Design System Modern',
        'slug' => 'prinsip-design-system-modern',
        'excerpt' => 'Tentang tipografi dan warna.',
    ]);

    Volt::test('pages.blog.index')
        ->set('search', 'Docker')
        ->assertSee($docker->title)
        ->assertDontSee($design->title);
});

test('category filter narrows the article list', function () {
    $category = Category::create(['name' => 'Web Architecture', 'slug' => 'web-architecture', 'color' => 'primary']);
    $otherCategory = Category::create(['name' => 'Case Studies', 'slug' => 'case-studies', 'color' => 'primary']);
    $author = TeamMember::create(['name' => 'Test Author', 'position' => 'Engineer', 'order' => 1]);

    $webPost = makePost($category, $author);
    $casePost = makePost($category, $author, [
        'title' => 'Studi Kasus Klien Manufaktur',
        'slug' => 'studi-kasus-klien-manufaktur',
        'category_id' => $otherCategory->id,
    ]);

    Volt::test('pages.blog.index')
        ->call('selectCategory', 'case-studies')
        ->assertSee($casePost->title)
        ->assertDontSee($webPost->title);
});

test('liking a post increments its like count once per click', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author);

    Volt::test('pages.blog.show', ['slug' => $post->slug])
        ->call('incrementLike');

    expect($post->fresh()->likes_count)->toBe(1);
});

test('a visitor can submit a valid comment and it lands as pending', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author);

    Volt::test('pages.blog.comments', ['postId' => $post->id])
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('comment', 'Artikel yang sangat membantu, terima kasih!')
        ->call('submitComment')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'name' => 'Budi Santoso',
        'status' => 'pending',
    ]);
});

test('invalid comment submissions show friendly validation errors', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author);

    Volt::test('pages.blog.comments', ['postId' => $post->id])
        ->set('name', 'A')
        ->set('email', 'bukan-email')
        ->set('comment', '')
        ->call('submitComment')
        ->assertHasErrors(['name', 'email', 'comment']);

    $this->assertDatabaseCount('comments', 0);
});

test('honeypot field silently drops bot comment submissions', function () {
    [$category, $author] = makeBlogFixtures();
    $post = makePost($category, $author);

    Volt::test('pages.blog.comments', ['postId' => $post->id])
        ->set('name', 'Bot')
        ->set('email', 'bot@example.com')
        ->set('comment', 'Pesan spam otomatis.')
        ->set('website', 'http://spam.example.com')
        ->call('submitComment')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    expect(Comment::count())->toBe(0);
});

test('admin dashboard blog tab is only accessible to admins', function () {
    $user = User::factory()->create(['role' => 'user']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->get('/admin/dashboard')->assertRedirect('/login');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden();

    $this->actingAs($admin);

    $this->get('/admin/dashboard')->assertOk();

    // The Blog tab's content only renders once selected (it starts on
    // Overview), so switch tabs at the component level to confirm it mounts.
    Volt::test('admin.dashboard')
        ->set('activeTab', 'blog')
        ->assertSeeVolt('admin.blog-manager');
});

test('admin can create and publish a post through the blog CRUD component', function () {
    [$category, $author] = makeBlogFixtures();
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    Volt::test('admin.blog-posts-manager')
        ->set('postTitle', 'Artikel Baru dari Tes')
        ->set('postCategoryId', $category->id)
        ->set('postAuthorId', $author->id)
        ->set('postExcerpt', 'Ringkasan artikel tes.')
        ->set('postBody', 'Isi lengkap artikel untuk pengujian otomatis.')
        ->set('postStatus', 'published')
        ->call('savePost')
        ->assertHasNoErrors();

    $post = Post::firstWhere('title', 'Artikel Baru dari Tes');
    expect($post)->not->toBeNull();
    expect($post->status)->toBe('published');
    expect($post->published_at)->not->toBeNull();
    expect($post->reading_time)->toBeGreaterThanOrEqual(1);
});
