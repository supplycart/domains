<?php

namespace App\Traits;

trait GeneratorTraits
{
    /**
     * @param $path
     *
     * @return string
     */
    public function getDomainPath($path = null)
    {
        return $this->domainPath.$path;
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function setDomainPath($path)
    {
        return $this->domainPath = $path;
    }

    /**
     * @return mixed
     */
    public function getProvidedName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
