<?php

namespace App\Console\Commands;

use App\Models\Rfc;
use Feed;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RfcSyncCommand extends Command
{
    protected $signature = 'rfc:sync';

    protected $description = 'Command description';

    public function handle(): void
    {
        $rss = Feed::loadRss('https://externals.io/rss');

        foreach ($rss->item as $item) {
            if (! Str::startsWith($item->title ?? null, ['[VOTE]'])) {
                continue;
            }

            $rfc = Rfc::updateOrCreate(['title' => $item->title]);

            $this->info("✅  {$rfc->title}");

            if (! $rfc->url) {
                preg_match('/\"(https:\/\/wiki\.php\.net\/rfc\/(.*))\"/', $item->description, $matches);
                $url = $matches[1] ?? null;
                $rfc->url = $url;
                $rfc->save();
                $this->comment("\t{$url}");
            }
        }
    }
}
