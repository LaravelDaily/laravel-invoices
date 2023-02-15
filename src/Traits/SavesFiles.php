<?php

namespace LaravelDaily\Invoices\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Trait SavesFiles
 */
trait SavesFiles
{
    /**
     * @var string
     */
    public $disk;

    /**
     * @param string $disk
     * @return $this
     */
    public function save(string $disk = '')
    {
        if ($disk !== '') {
            $this->disk = $disk;
        }

        $this->render();

        Storage::disk($this->disk)->put($this->filename, $this->output);

        return $this;
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return Storage::disk($this->disk)->url($this->filename);
    }
}
