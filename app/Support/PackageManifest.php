<?php

namespace App\Support;

use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class PackageManifest extends BasePackageManifest
{
    /**
     * Build the manifest and write it to disk.
     *
     * Laravel 5.8 expects every Composer installed.json entry to be a package
     * with a name. Newer Composer formats can include top-level metadata, so
     * filter those entries before Laravel maps package names.
     *
     * @return void
     */
    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode($this->files->get($path), true);
        }

        if (isset($packages['packages']) && is_array($packages['packages'])) {
            $packages = $packages['packages'];
        }

        $packages = array_values(array_filter((array) $packages, function ($package) {
            return is_array($package) && isset($package['name']);
        }));

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $this->write(collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()->all());
    }
}
