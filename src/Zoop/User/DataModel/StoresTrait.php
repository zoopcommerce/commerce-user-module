<?php

namespace Zoop\User\DataModel;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait StoresTrait
{
    /**
     * Array. Stores that this theme is part of.
     * The Zones annotation means this field is used by the Zones filter
     *
     * @ODM\Collection
     * @ODM\Index
     */
    protected $stores = [];

    /**
     * @return array
     */
    public function getStores()
    {
        if (!is_array($this->stores)) {
            $this->stores = [];
        }
        return $this->stores;
    }

    /**
     * @param array $stores
     */
    public function setStores(array $stores)
    {
        $this->stores = $stores;
    }

    /**
     * @param string $store
     */
    public function addStore($store)
    {
        if (!empty($store) && in_array($store, $this->getStores()) === false) {
            $this->stores[] = $store;
        }
    }
}
