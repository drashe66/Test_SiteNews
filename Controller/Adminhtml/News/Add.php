<?php

namespace Test\SiteNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Add extends Action implements HttpGetActionInterface
{
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Test_SiteNews::add_item');
        $resultPage->addBreadcrumb(__('Site News'), __('Site News'));
        $resultPage->addBreadcrumb(__('Add Item'), __('Add Item'));
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Record'));
        return $resultPage;
    }
}
