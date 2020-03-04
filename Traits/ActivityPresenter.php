<?php

namespace Kokst\Core\Traits;

use Carbon\Carbon;

trait ActivityPresenter
{
    public function activityData(): string
    {
        return $this->activity()['data'];
    }

    public function activityLabel(): string
    {
        return $this->activity()['label'];
    }

    public function activityValue(): string
    {
        return $this->activity()['value'];
    }

    protected function activity(): array
    {
        $now = new Carbon('now');
        $updated = new Carbon($this->wrappedObject->updated_at);
        $created = new Carbon($this->wrappedObject->created_at);

        $data = $now->format('U') - $updated->format('U');
        $label = __('vendor/kokst/core/components/datatable/index.updated');
        $value = $updated->ago();

        if ($created->greaterThanOrEqualTo($updated)) {
            $data = $now->format('U') - $created->format('U');
            $label = __('vendor/kokst/core/components/datatable/index.created');
            $value = $created->ago();
        }

        return [
            'data' => $data,
            'label' => $label,
            'value' => $value
        ];
    }
}
