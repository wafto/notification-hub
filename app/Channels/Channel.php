<?php

namespace App\Channels;

interface Channel
{
    /**
     * The given name that should match when resolving channels.
     */
    public function name(): string;

    /**
     * Rules for payload validation.
     */
    public function rules(): array;
}
