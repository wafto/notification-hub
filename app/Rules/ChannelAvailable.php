<?php

namespace App\Rules;

use App\Channels\Resolver;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ChannelAvailable implements ValidationRule
{
    public function __construct(
        private Resolver $resolver,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mapping = array_fill_keys($this->resolver->channelsNames()->toArray(), false);

        foreach ($value as $channel) {
            if (! array_key_exists($channel, $mapping)) {
                $fail(sprintf('The :attribute has a invalid channel named %s.', $channel));
            }

            if ($mapping[$channel] ?? false) {
                $fail(sprintf('The :attribute has a duplicated channel %s.', $channel));
            }

            $mapping[$channel] = true;
        }
    }
}
