<?php

namespace Test\SiteNews\Block\Adminhtml\News\Edit;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        try {
            return $this->context->getRequest()->getParam('id');
        }
        catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->geturlBuilder()->getUrl($route, $params);
    }
}
