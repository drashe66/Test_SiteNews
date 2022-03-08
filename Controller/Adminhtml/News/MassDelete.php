<?php

namespace Test\SiteNews\Controller\Adminhtml\News;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Test\SiteNews\Model\ResourceModel\News\CollectionFactory as NewsCollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;

class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var NewsCollectionFactory
     */
    protected $newsCollectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param NewsCollectionFactory $newsCollectionFactory
     */
    public function __construct(Context $context, Filter $filter, NewsCollectionFactory $newsCollectionFactory)
    {
        $this->filter = $filter;
        $this->newsCollectionFactory = $newsCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->newsCollectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
