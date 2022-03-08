<?php

namespace Test\SiteNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Test\SiteNews\Model\NewsFactory;
use Test\SiteNews\Model\ResourceModel\News as NewsResource;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action implements HttpPostActionInterface
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
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $id = (int)$this->getRequest()->getParam('id');
        try {
            $newsModel = $this->newsFactory->create();
            $newsModel->setData([
                'title' => $data['title'],
                'status' => $data['status'],
                'stores' => $data['stores']
            ]);
            if ($id) $newsModel->setId($id);
            $this->newsResource->save($newsModel);

            $this->messageManager->addSuccessMessage(__('Save data successfully!'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            return $resultRedirect->setPath('*/*/edit');
        }
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['id' => $newsModel->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
