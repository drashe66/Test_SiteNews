<?php

namespace Test\SiteNews\Helper;

use Magento\Framework\App\Helper\Context;
use Test\SiteNews\Model\News;
use Magento\Framework\App\Helper\AbstractHelper;
use Test\SiteNews\Model\News\Config as NewsConfig;

class Data extends AbstractHelper
{
    /**
     * @var NewsConfig
     */
    protected $newsConfig;

    /**
     * @param Context $context
     * @param NewsConfig $newsConfig
     */
    public function __construct(Context $context, NewsConfig $newsConfig)
    {
        $this->newsConfig = $newsConfig;
        parent::__construct($context);
    }

    /**
     * Get news statuses
     *
     * @return array
     */
    public function getNewsStatuses()
    {
        return [
            News::STATUS_ENABLED => __('Enabled'),
            News::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * Get news statuses as option array
     *
     * @return array
     */
    public function getNewsStatusesOptionArray()
    {
        $result = [];
        foreach ($this->getNewsStatuses() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getNewsTitle()
    {
        return $this->newsConfig->getTitle();
    }
}
