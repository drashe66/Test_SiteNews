<?php

namespace Test\SiteNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Test\SiteNews\Model\NewsFactory;
use Test\SiteNews\Model\ResourceModel\News as NewsResource;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Delete extends Action implements HttpGetActionInterface
{
    /**
     * @var NewsFactory
     */
    protected $newsFactory;

    /**
     * @var NewsResource
     */
    protected $newsResource;

    /**
     * @param Context $context
     * @param NewsFactory $newsFactory
     * @param NewsResource $newsResource
     */
    public function __construct(
        Context $context,
        NewsFactory $newsFactory,
        NewsResource $newsResource
    ) {
        $this->newsFactory = $newsFactory;
        $this->newsResource = $newsResource;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|ResponseInterface|ResultInterface|void
     */
    public function execute() {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $newsModel = $this->newsFactory->create();
                $this->newsResource->load($newsModel, $id);
                $this->newsResource->delete($newsModel);

                $this->messageManager->addSuccessMessage(__('The News Item has been deleted.'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
    }
}
