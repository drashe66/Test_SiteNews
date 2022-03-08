<?php

namespace Test\SiteNews\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class News extends AbstractModel implements IdentityInterface
{
    /**
     * Enabled News Item Status
     */
    const STATUS_ENABLED = 1;

    /**
     * Disabled News Item Status
     */
    const STATUS_DISABLED = 0;

    /**
     * Cache tag
     */
    const CACHE_TAG = 'site_news_news';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Test\SiteNews\Model\ResourceModel\News::class);
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
