<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;

class Post extends Model
{
    protected $fillable = [
        'author_id', 'category_id', 'title', 'slug', 'excerpt', 'body',
        'cover_image', 'status', 'published_at', 'views_count', 'likes_count',
        'reading_time', 'meta_title', 'meta_description', 'is_featured',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'reading_time' => 'integer',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, string $slug): Builder
    {
        return $query->whereHas('category', fn (Builder $q) => $q->where('slug', $slug));
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%");
        });
    }

    /**
     * Average adult reading speed (~200 wpm), rounded up, minimum 1 minute.
     */
    public static function calculateReadingTime(string $body): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($body)) / 200));
    }

    /**
     * Render the Markdown body to sanitized-by-trust HTML (only admins author
     * posts, same trust model as the rest of the admin-authored content).
     * Headings get slugged `id` attributes (no visible permalink icon) so
     * `tableOfContents()` and in-page anchors can target them.
     */
    public function renderedBody(): string
    {
        return (string) Str::markdown($this->body, [
            'heading_permalink' => [
                'apply_id_to_heading' => true,
                'insert' => 'none',
            ],
        ], [
            new HeadingPermalinkExtension,
        ]);
    }

    /**
     * Extract an H2/H3 table of contents from the rendered body.
     *
     * @return array<int, array{id: string, text: string, level: int}>
     */
    public function tableOfContents(): array
    {
        preg_match_all(
            '/<h([23])[^>]*\sid="([^"]+)"[^>]*>(.*?)<\/h\1>/is',
            $this->renderedBody(),
            $matches,
            PREG_SET_ORDER
        );

        return array_map(fn (array $match) => [
            'level' => (int) $match[1],
            'id' => $match[2],
            'text' => trim(strip_tags($match[3])),
        ], $matches);
    }
}
