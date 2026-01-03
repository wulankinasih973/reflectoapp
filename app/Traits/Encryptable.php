<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

trait Encryptable
{
    /**
     * Daftar atribut yang harus terenkripsi
     */
    protected array $encryptable = [];

    /**
     * Mutator otomatis: set atribut terenkripsi
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable, true) && !is_null($value)) {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Accessor otomatis: ambil atribut terdekripsi
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable, true) && !is_null($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value; // fallback jika data sudah terenkripsi sebelumnya
            }
        }

        return $value;
    }
}
