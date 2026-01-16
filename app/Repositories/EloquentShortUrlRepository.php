<?php

namespace App\Repositories;

use App\Contracts\ShortUrlRepositoryContract;
use App\Exceptions\PoolEmptyException;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\DB;

class EloquentShortUrlRepository implements ShortUrlRepositoryContract
{
    public function __construct(private ShortUrl $model)
    {}

    public function findByUrlHash(string $urlHash): ?ShortUrl
    {
        return $this->model->where('url_hash', $urlHash)->first();
    }

    public function create(string $url, string $hash): ShortUrl
    {
        DB::beginTransaction();

        try {
            $poolEntry = DB::table('code_pool')
            ->lock('FOR UPDATE SKIP LOCKED')
            ->first();

            if (!$poolEntry) {
                throw new PoolEmptyException();
            }

            $shortUrl = $this->model->create([
                'url' => $url,
                'url_hash' => $hash,
                'code' => $poolEntry->code,
            ]);

            // Remove the used code from the pool
            DB::table('code_pool')->where('id', $poolEntry->id)->delete();
            DB::commit();

            return $shortUrl;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
