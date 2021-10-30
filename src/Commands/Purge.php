<?php

namespace AshAllenDesign\ShortURL\Commands;

use AshAllenDesign\ShortURL\Models\ShortURL;

/**
 * Class Purge
 * @package AshAllenDesign\ShortURL\Commands
 */
class Purge extends BaseCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'short-url:purge';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Purge deactivated links';

	/**
	 *
	 */
	public function handle()
	{
		ShortURL::query()
			->where('deactivated_at', '<', now()->toDateTimeString())
			->orWhere(function ($query) {
				$query->where('single_use', 1)
					->whereHas('visits');
			})
			->delete();
	}
}
