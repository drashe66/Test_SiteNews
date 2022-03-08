<?php

namespace Test\SiteNews\Ui\DataProvider\News\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Test\SiteNews\Model\ResourceModel\News\Collection;
use Test\SiteNews\Model\ResourceModel\News\CollectionFactory as NewsCollectionFactory;

class NewsDataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param NewsCollectionFactory $newsCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        NewsCollectionFactory $newsCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $newsCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        /** @var  Collection $items */
        $items = $this->collection->addStoreData()->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }
        return $this->loadedData;
    }
}
