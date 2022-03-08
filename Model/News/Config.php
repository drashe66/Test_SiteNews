<?php

namespace Test\SiteNews\Model\News;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Provides news configuration
 */
class Config
{
    const XML_PATH_NEWS_ENABLE = 'site_news/general/enable';
    const XML_PATH_NEWS_TITLE = 'site_news/general/title';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NEWS_ENABLE,
            ScopeInterface::SCOPE_STORES,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     */
    public function getTitle($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_NEWS_TITLE,
            ScopeInterface::SCOPE_STORES,
            $storeId
        );
    }
}
