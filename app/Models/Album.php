<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Album
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $album_name
 * @property string $album_thumb
 * @property string|null $description
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photos
 * @property-read int|null $photos_count
 * @method static \Database\Factories\AlbumFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Query\Builder|Album onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereAlbumName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereAlbumThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Album withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Album withoutTrashed()
 * @mixin \Eloquent
 */
class Album extends Model
{
    use HasFactory;
    use SoftDeletes;

    // protected $table = 'albums';
    // protected $primaryKey = 'album_name';
    protected $fillable = ['album_name', 'description', 'user_id', 'album_thumb'];

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function getPathAttribute()
    {
        $url = $this->album_thumb;
        if (!str_starts_with($url, 'http')) {
            $url = 'storage/' . $url;
        }
        return $url;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
