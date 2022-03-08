<?php

namespace Test\SiteNews\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Test\SiteNews\Model\News\Config as NewsConfig;
use Magento\Framework\UrlInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultFactory;

    /**
     * @var NewsConfig
     */
    protected $newsConfig;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @param ResultFactory $resultFactory
     * @param NewsConfig $newsConfig
     * @param UrlInterface $url
     */
    public function __construct(ResultFactory $resultFactory, NewsConfig $newsConfig, UrlInterface $url)
    {
        $this->resultFactory = $resultFactory;
        $this->newsConfig = $newsConfig;
        $this->url = $url;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if ($this->newsConfig->isEnabled()) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $noRouteUrl = $this->url->getUrl('noroute');
            $resultRedirect->setPath($noRouteUrl);
        }

        return $resultRedirect;
    }
}
