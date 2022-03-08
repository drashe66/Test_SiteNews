<?php

namespace Test\SiteNews\Model\News\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Test\SiteNews\Helper\Data as NewsHelper;

class Status implements OptionSourceInterface
{
    /**
     * @var NewsHelper
     */
    protected $newsHelper;

    /**
     * @param NewsHelper $newsHelper
     */
    public function __construct(NewsHelper $newsHelper)
    {
        $this->newsHelper = $newsHelper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->newsHelper->getNewsStatusesOptionArray();
    }
}
