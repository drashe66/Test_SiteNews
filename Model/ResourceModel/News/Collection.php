<?php

namespace Test\SiteNews\Model\ResourceModel\News;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Test\SiteNews\Helper\Data as NewsHelper;
use Test\SiteNews\Model\News as NewsModel;
use Test\SiteNews\Model\ResourceModel\News as NewsResourceModel;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'test_site_news_news_collection';
    protected $_eventObject = 'news_collection';

    /**
     * @var NewsHelper
     */
    protected $newsHelper;

    /**
     * Add store data flag
     * @var bool
     */
    protected $addStoreDataFlag = false;

    /**
     * @var string
     */
    protected $newsStoresTable;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param NewsHelper $newsHelper
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        NewsHelper $newsHelper,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->newsHelper = $newsHelper;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(NewsModel::class, NewsResourceModel::class);
    }

    /**
     * Add store data
     *
     * @return $this
     */
    public function addStoreData()
    {
        $this->addStoreDataFlag = true;
        return $this;
    }

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this|Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        parent::load($printQuery, $logQuery);
        if ($this->addStoreDataFlag) {
            $this->_addStoreData();
        }
        return $this;
    }

    /**
     * Add store data
     *
     * @return void
     */
    protected function _addStoreData()
    {
        $connection = $this->getConnection();

        $newsIds = $this->getColumnValues('id');
        $storesToNews = [];
        if (count($newsIds) > 0) {
            $inCond = $connection->prepareSqlCondition('news_item_id', ['in' => $newsIds]);
            $select = $connection->select()->from($this->getNewsStoresTable())->where($inCond);
            $result = $connection->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($storesToNews[$row['news_item_id']])) {
                    $storesToNews[$row['news_item_id']] = [];
                }
                $storesToNews[$row['news_item_id']][] = $row['store_id'];
            }
        }

        foreach ($this as $item) {
            if (isset($storesToNews[$item->getId()])) {
                $item->setStores($storesToNews[$item->getId()]);
            } else {
                $item->setStores([]);
            }
        }
    }

    /**
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }
        $inCond = $this->getConnection()->prepareSqlCondition('store.store_id', ['in' => $store]);
        $this->getSelect()->join(
            ['store' => $this->getNewsStoresTable()],
            'main_table.id=store.news_item_id',
            []
        );
        $this->getSelect()->where($inCond);
        return $this;
    }

    /**
     * Add status filter
     *
     * @param int|string $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        if (is_string($status)) {
            $statuses = array_flip($this->newsHelper->getNewsStatuses());
            $status = $statuses[$status] ?? 0;
        }
        if (is_numeric($status)) {
            $this->addFilter('status', $this->getConnection()->quoteInto('main_table.status=?', $status), 'string');
        }
        return $this;
    }

    /**
     * Set date order
     *
     * @param string $dir
     * @return $this
     */
    public function setDateOrder($dir = 'DESC')
    {
        $this->setOrder('main_table.date', $dir);
        return $this;
    }

    /**
     * Get news stores table
     *
     * @return string
     */
    protected function getNewsStoresTable()
    {
        if ($this->newsStoresTable === null) {
            $this->newsStoresTable = $this->getTable('news_stores');
        }
        return $this->newsStoresTable;
    }
}
