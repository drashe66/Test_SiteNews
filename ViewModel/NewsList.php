<?php

namespace Test\SiteNews\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Test\SiteNews\Model\News;
use Test\SiteNews\Model\ResourceModel\News\CollectionFactory as NewsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class NewsList implements ArgumentInterface
{
    /**
     * @var NewsCollectionFactory
     */
    protected $newsCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param NewsCollectionFactory $newsCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(NewsCollectionFactory $newsCollectionFactory, StoreManagerInterface $storeManager)
    {
        $this->newsCollectionFactory = $newsCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param bool $isDateDescOrder
     * @return \Magento\Framework\DataObject[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getNewsCollection($isDateDescOrder = true)
    {
        $currentStoreId = $this->storeManager->getStore()->getId();
        $newsCollection = $this->newsCollectionFactory->create();

        return $newsCollection
            ->addStatusFilter(News::STATUS_ENABLED)
            ->addStoreFilter($currentStoreId)
            ->setDateOrder($isDateDescOrder ? 'DESC' : 'ASC')
            ->getItems();
    }
}
