<?php

declare(strict_types=1);

namespace DmitryBubyakin\NovaMedialibraryField\Http\Requests;

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use DmitryBubyakin\NovaMedialibraryField\MedialibraryFieldResolver;
use Laravel\Nova\Http\Requests\NovaRequest;

class MediaRequest extends NovaRequest
{
    public function medialibraryField(): Medialibrary
    {
        return call_user_func(new MedialibraryFieldResolver($this));
    }

    public function resourceExists(): bool
    {
        return ! is_null($this->route('resourceId')) and $this->route('resourceId') !== 'undefined';
    }

    public function fieldUuid(): ?string
    {
        return $this->input('fieldUuid');
    }
}
