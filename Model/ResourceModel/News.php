<?php

namespace Test\SiteNews\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * News resource model
 */
class News extends AbstractDb
{
    /**
     * News table
     *
     * @var string
     */
    protected $newsTable;

    /**
     * News stores table
     *
     * @var string
     */
    protected $newsStoresTable;

    /**
     * Core date model
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Core model store manager interface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param StoreManagerInterface $storeManager
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        DateTime $date,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        $this->date = $date;
        $this->storeManager = $storeManager;

        parent::__construct($context, $connectionName);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('news', 'id');
        $this->newsTable = $this->getTable('news');
        $this->newsStoresTable = $this->getTable('news_stores');
    }

    /**
     * Perform actions before object save
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$object->getId()) {
            $object->setDate($this->date->gmtDate());
        }
        if ($object->hasData('stores') && is_array($object->getStores())) {
            $stores = $object->getStores();
            $stores[] = 0;
            $object->setStores($stores);
        } elseif ($object->hasData('stores')) {
            $object->setStores([$object->getStores(), 0]);
        }
        return $this;
    }

    /**
     * Perform actions after object save
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $connection = $this->getConnection();

        /**
         * save stores
         */
        $stores = $object->getStores();
        if (!empty($stores)) {
            $condition = ['news_item_id = ?' => $object->getId()];
            $connection->delete($this->newsStoresTable, $condition);

            $insertedStoreIds = [];
            foreach ($stores as $storeId) {
                if (in_array($storeId, $insertedStoreIds) || is_int($storeId)) {
                    continue;
                }

                $insertedStoreIds[] = $storeId;
                $storeInsert = ['store_id' => $storeId, 'news_item_id' => $object->getId()];
                $connection->insert($this->newsStoresTable, $storeInsert);
            }
        }

        return $this;
    }

    /**
     * @param AbstractModel $object
     * @return $this|News
     * @throws NoSuchEntityException
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->newsStoresTable,
            ['store_id']
        )->where(
            'news_item_id = :news_item_id'
        );
        $stores = $connection->fetchCol($select, [':news_item_id' => $object->getId()]);
        if (empty($stores) && $this->storeManager->hasSingleStore()) {
            $object->setStores([$this->storeManager->getStore(true)->getId()]);
        } else {
            $object->setStores($stores);
        }
        return $this;
    }
}
